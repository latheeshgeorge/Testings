<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/kmlsitemap/list_kmllocation.php");
}
elseif($_REQUEST['fpurpose']=='save_main_kml_details')
{
	$compname 	= addslashes($_REQUEST['main_comp_name']);
	$author			= addslashes($_REQUEST['main_author']);
	$update_sql 	= "UPDATE sites 
								SET 
									kml_companyname='".$compname."',
									kml_author='".$author."' 
								WHERE 
									site_id = $ecom_siteid 
								LIMIT 
									1";
	$db->query($update_sql);
	$alert = 'Common Details Saved Successfully';  
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/kmlsitemap/list_kmllocation.php");
}
elseif($_REQUEST['fpurpose']=='save_kml_order') // help order 
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");	
	$IdArr=explode('~',$_REQUEST['Idstr']);
	$OrderArr=explode('~',$_REQUEST['OrderStr']);
	for($i=0;$i<count($IdArr);$i++)
	{
		$update_array							= array();
		$update_array['kml_order']		= $OrderArr[$i];
		$db->update_from_array($update_array,'seo_kml_location',array('kml_id'=>$IdArr[$i]));
	}
	$alert = 'Order saved successfully.';
	include("../includes/kmlsitemap/list_kmllocation.php");
}
elseif($_REQUEST['fpurpose']=='change_hide') // Update help status
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$type_ids_arr 		= explode('~',$_REQUEST['type_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($type_ids_arr);$i++)
		{
			$update_array						= array();
			$update_array['kml_hide']		= $new_status;
			$help_id 							= $type_ids_arr[$i];	
			$db->update_from_array($update_array,'seo_kml_location',array('kml_id'=>$help_id));
		}
		$alert = 'Hidden Status changed successfully.';
		include("../includes/kmlsitemap/list_kmllocation.php");
}
else if($_REQUEST['fpurpose']=='delete') // Delete help
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry KML sitemap location not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					
					  $sql_del = "DELETE FROM seo_kml_location WHERE sites_site_id = $ecom_siteid AND kml_id=".$del_arr[$i];
					  $db->query($sql_del);
				 }	  
			}
		}
		$alert = 'Delete operation successfull';
		include("../includes/kmlsitemap/list_kmllocation.php");
}
else if($_REQUEST['fpurpose']=='add') // New help
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/kmlsitemap/add_kmllocation.php");
}
else if($_REQUEST['fpurpose']=='edit') // Edit help
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/kmlsitemap/edit_kmllocation.php");
}
else if($_REQUEST['fpurpose']=='insert') // Save new help
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['kml_location_name'],$_REQUEST['kml_company_name'],$_REQUEST['kml_street'],$_REQUEST['kml_city'],$_REQUEST['kml_state'],$_REQUEST['kml_zip'],$_REQUEST['kml_phone'],$_REQUEST['kml_latitude'],$_REQUEST['kml_longitude'],$_REQUEST['kml_description']);
		$fieldDescription = array('Location Name','Company Name','Street','City','State','Zip','Phone','Latitude','Longitude','Description');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array($_REQUEST['kml_latitude'],$_REQUEST['kml_longitude']);
		$fieldNumericDesc = array('Latitude','Longitude');
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		if($alert=='')
		{
			$insert_array 							= array();
			$insert_array['sites_site_id']			= $ecom_siteid;
			$insert_array['kml_location_name']		= add_slash($_REQUEST['kml_location_name']);
			$insert_array['kml_company_name']		= add_slash($_REQUEST['kml_company_name']);
			$insert_array['kml_street']				= add_slash($_REQUEST['kml_street']);
			$insert_array['kml_city']				= add_slash($_REQUEST['kml_city']);
			$insert_array['kml_state']				= add_slash($_REQUEST['kml_state']);
			$insert_array['kml_Zip']				= add_slash($_REQUEST['kml_zip']);
			$insert_array['kml_phone']				= add_slash($_REQUEST['kml_phone']);
			$insert_array['kml_latitude']			= add_slash($_REQUEST['kml_latitude']);
			$insert_array['kml_longitude']			= add_slash($_REQUEST['kml_longitude']);
			$insert_array['kml_description']		= add_slash($_REQUEST['kml_description'],false);
			
			$insert_array['kml_order']				= (is_numeric($_REQUEST['kml_order']))?$_REQUEST['kml_order']:0;
			$insert_array['kml_hide']				= ($_REQUEST['kml_hide'])?1:0;
			$db->insert_from_array($insert_array, 'seo_kml_location');
			$insert_id = $db->insert_id();
			$alert .= '<br><span class="redtext"><b>KML Sitemap Location Added Successfully.</b></span><br>';
			echo $alert;
		?>
			<br /><a class="smalllink" href="home.php?request=kmlsitemap&search_location_name=<?=$_REQUEST['search_location_name']?>&search_company_name=<?=$_REQUEST['search_company_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=kmlsitemap&fpurpose=edit&kml_id=<?=$insert_id?>&search_location_name=<?=$_REQUEST['search_location_name']?>&search_company_name=<?=$_REQUEST['search_company_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=kmlsitemap&fpurpose=add&search_location_name=<?=$_REQUEST['search_location_name']?>&search_company_name=<?=$_REQUEST['search_company_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '';
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include("includes/kmlsitemap/add_kmllocation.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') //Update shelf
{
	if($_REQUEST['Submit'])
	{
		
		$alert='';
		$fieldRequired = array($_REQUEST['kml_location_name'],$_REQUEST['kml_company_name'],$_REQUEST['kml_street'],$_REQUEST['kml_city'],$_REQUEST['kml_state'],$_REQUEST['kml_zip'],$_REQUEST['kml_phone'],$_REQUEST['kml_latitude'],$_REQUEST['kml_longitude'],$_REQUEST['kml_description']);
		$fieldDescription = array('Location Name','Company Name','Street','City','State','Zip','Phone','Latitude','Longitude','Description');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array($_REQUEST['kml_latitude'],$_REQUEST['kml_longitude']);
		$fieldNumericDesc = array('Latitude','Longitude');
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		if(!$alert) {
			$update_array 							= array();
			$update_array['sites_site_id']			= $ecom_siteid;
			$update_array['kml_location_name']		= add_slash($_REQUEST['kml_location_name']);
			$update_array['kml_company_name']		= add_slash($_REQUEST['kml_company_name']);
			$update_array['kml_street']				= add_slash($_REQUEST['kml_street']);
			$update_array['kml_city']				= add_slash($_REQUEST['kml_city']);
			$update_array['kml_state']				= add_slash($_REQUEST['kml_state']);
			$update_array['kml_Zip']				= add_slash($_REQUEST['kml_zip']);
			$update_array['kml_phone']				= add_slash($_REQUEST['kml_phone']);
			$update_array['kml_latitude']			= add_slash($_REQUEST['kml_latitude']);
			$update_array['kml_longitude']			= add_slash($_REQUEST['kml_longitude']);
			$update_array['kml_description']		= add_slash($_REQUEST['kml_description'],false);
			
			$update_array['kml_order']				= (is_numeric($_REQUEST['kml_order']))?$_REQUEST['kml_order']:0;
			$update_array['kml_hide']				= ($_REQUEST['kml_hide'])?1:0;
			$db->update_from_array($update_array, 'seo_kml_location', 'kml_id', $_REQUEST['kml_id']);
			$alert .= '<br><span class="redtext"><b>KML Sitemap Location Updated successfully</b></span><br>';
			echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=kmlsitemap&search_location_name=<?=$_REQUEST['search_location_name']?>&search_company_name=<?=$_REQUEST['search_company_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=kmlsitemap&fpurpose=edit&kml_id=<?=$_REQUEST['kml_id']?>&search_location_name=<?=$_REQUEST['search_location_name']?>&search_company_name=<?=$_REQUEST['search_company_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=kmlsitemap&fpurpose=add&search_location_name=<?=$_REQUEST['search_location_name']?>&search_company_name=<?=$_REQUEST['search_company_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	
		}
		else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
		?>
			<br />
			<?php
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			include("includes/kmlsitemap/edit_kmllocation.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'getcoordinates') //Update shelf
{
	$address		= stripslashes($_REQUEST['address']);
	$address		= str_replace(' ','+',$address);
	$handle 		= fopen("http://maps.google.com/maps/geo?q=$address&sensor=false&output=xml", "rb");
	$contents 	= stream_get_contents($handle);
	//preg_match_all('#<coordinates>(.+?)</coordinates>#is', $contents, $matches);
	preg_match('/<code>(.*)?<\/code>/', $contents, $matches_code);
	if($matches_code[0]=='<code>200</code>')
	{
		preg_match('/<coordinates>(.*)?<\/coordinates>/', $contents, $matches);
		$str_arr = explode(',',str_replace('<coordinates>','',$matches[0]));
		$str_str = $str_arr[1].','.$str_arr[0];
		echo $str_str;
	}	
	else
		echo 'notfound';
	
	
	
	
	
}

?>