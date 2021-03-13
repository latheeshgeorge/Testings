<?php
if($_REQUEST['fpurpose']=='')
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/static_page/list_page.php");
}
elseif($_REQUEST['fpurpose']=='change_hide')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$page_ids_arr 		= explode('~',$_REQUEST['page_ids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($page_ids_arr);$i++)
		{
			$update_array= array();
			$update_array['hide']= $new_status;
			$page_id= $page_ids_arr[$i];	
			$db->update_from_array($update_array,'static_pages',array('page_id'=>$page_id));
			
			// Deleting Cache
			delete_static_page_cache($page_id);
		}
		delete_body_cache();
		recreate_entire_websitelayout_cache();
		$alert = 'Status changed successfully.';
		include ('../includes/static_page/list_page.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Page not selected';
		}
		else
		{
			$del_arr = explode("~",$_REQUEST['del_ids']);
			$del_count = 0;
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					   # Check whether home page
					   $sql_check = "SELECT pname FROM static_pages WHERE page_id=".$del_arr[$i]." AND sites_site_id=$ecom_siteid";
					   $ret_check = $db->query($sql_check);
					   $row_check = $db->fetch_array($ret_check);
					   if($row_check['pname']!='Home')
					   {
						   # Check for states of this country
						   $sql_del = "DELETE FROM static_pages WHERE page_id=".$del_arr[$i];
						   $db->query($sql_del);
						   $sql_del = "DELETE FROM static_pagegroup_static_page_map WHERE static_pages_page_id=".$del_arr[$i];
							$db->query($sql_del);
							$del_count ++;
						   //if($alert) $alert .="<br />";
								
							// Deleting Cache
							delete_static_page_cache($del_arr[$i]);
						}
						else
						{   
						      if($alert) $alert .="<br />";
							$alert .= "!! Home page Cannot be deleted. ";
						}
				}	
			}
			if($del_count>0)
			{
				if($alert) $alert .="<br />";
				$alert .= $del_count." Page(s) Deleted Successfully.";
			}
		}
		delete_body_cache();
		recreate_entire_websitelayout_cache();
		include ('../includes/static_page/list_page.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	//include_once("classes/fckeditor.php");
	include("includes/static_page/add_page.php");
}/* SEO tab in static page starts here */
else if($_REQUEST['fpurpose']=='upd_page_seoinfo')
{
	$unq_id = uniqid("");
	if(trim($_REQUEST['pname'])!='Home')
	{
		$sql_check = "SELECT id FROM se_static_title WHERE sites_site_id=$ecom_siteid AND static_pages_page_id = ".$_REQUEST['page_id'];
		$sql_keys = "SELECT se_keywords_keyword_id FROM se_static_keywords WHERE static_pages_page_id = ".$_REQUEST['page_id'];
		$tb_name = 'se_static_title';
	}
	else
	{
		$sql_check = "SELECT id FROM se_home_title WHERE sites_site_id=$ecom_siteid";
		$sql_keys = "SELECT se_keywords_keyword_id FROM se_home_keywords WHERE sites_site_id = ".$ecom_siteid;
		$tb_name = 'se_home_title';
	}
	//echo $sql_check;die();
	$res_check = $db->query($sql_check);
	$row_check = $db->fetch_array($res_check);
	
	$keys_list = array();
	$res_keys = $db->query($sql_keys);
	if($db->num_rows($res_keys)>0) 
	{
		while($row_keys = $db->fetch_array($res_keys))
		{
			$keys_list[] = $row_keys['se_keywords_keyword_id'];
		}
		foreach($keys_list as $keys => $values)
		{
			if(trim($_REQUEST['pname'])!='Home')
			{
				$sql_delkey_rel = "DELETE FROM se_static_keywords WHERE se_keywords_keyword_id = ".$values." AND static_pages_page_id = ".$_REQUEST['page_id'];
				//echo $sql_delkey_rel;echo "<br>";
				$db->query($sql_delkey_rel);
			}
			else
			{
				$sql_delkey_rel = "DELETE FROM se_home_keywords WHERE se_keywords_keyword_id = ".$values." AND sites_site_id = ".$ecom_siteid;
				//echo $sql_delkey_rel;echo "<br>";
				$db->query($sql_delkey_rel);				
			}
			$sql_delkey = "DELETE FROM se_keywords WHERE keyword_id = ".$values." AND sites_site_id = ".$ecom_siteid;
			//echo $sql_delkey;echo "<br>";
			$db->query($sql_delkey);
		}
	}
	for($i=1;$i<=5;$i++)
	{
		if($_REQUEST['keyword_'.$i] != "")
		{
			$insert_array = array();
			$insert_array['sites_site_id']		= $ecom_siteid;
			$insert_array['keyword_keyword']	= trim(add_slash($_REQUEST['keyword_'.$i]));
			$db->insert_from_array($insert_array, 'se_keywords');
			$insert_id = $db->insert_id();
			
			if($insert_id > 0)
			{
				$insert_array = array();
				if(trim($_REQUEST['pname'])!='Home')
				{
					$insert_array['static_pages_page_id']	= $_REQUEST['page_id'];
					$insert_array['se_keywords_keyword_id']	= $insert_id;
					$insert_array['uniq_id']				= $unq_id;
					$db->insert_from_array($insert_array, 'se_static_keywords');
				}
				else
				{
					$insert_array['se_keywords_keyword_id']	= $insert_id;
					$insert_array['sites_site_id']			= $ecom_siteid;
					$insert_array['uniq_id']				= $unq_id;
					$db->insert_from_array($insert_array, 'se_home_keywords');					
				}
			}
		}
	}
	//echo "<pre>";print_r($keys_list);die();
	
	//echo $tb_name;echo "<br>";die();
	if($row_check['id'] != "" && $row_check['id'] > 0)
	{
		if($_REQUEST['page_title'] == "" && $_REQUEST['page_meta'] == "")
		{
			if(trim($_REQUEST['pname'])!='Home')
			{
				$sql_del = "DELETE FROM se_static_title WHERE id=".$row_check['id'];
				
			}
			else
			{
				$sql_del = "DELETE FROM se_home_title WHERE id=".$row_check['id'];
			}
			$db->query($sql_del);
		}
		else
		{
			$update_array['title']					= trim(add_slash($_REQUEST['page_title']));
			$update_array['meta_description']		= trim(add_slash($_REQUEST['page_meta']));
			
			$db->update_from_array($update_array, $tb_name, 'id', $row_check['id']);
		}
	}
	else
	{
		$alert				= '';
		$fieldRequired 		= array($_REQUEST['page_title'],$_REQUEST['page_meta']);
		$fieldDescription 	= array('Page Title','Page Meta Description');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		
		if($_REQUEST['page_id'] == "")
		{
			$alert	.=	" Page id missing.";
		}
		elseif($ecom_siteid == "")
		{
			$alert	.=	" Site id missing.";
		}
		/*elseif($_REQUEST['page_title'] == "")
		{
			$alert	.=	" Page title missing.";
		}
		elseif($_REQUEST['page_meta'] == "")
		{
			$alert	.=	" Page meta description missing.";
		}*/
		if($alert == "")
		{
			$insert_array = array();
			if(trim($_REQUEST['pname'])!='Home')
			{
				$insert_array['static_pages_page_id']	= $_REQUEST['page_id'];
				$insert_array['sites_site_id']			= $ecom_siteid;
				$insert_array['title']					= trim(add_slash($_REQUEST['page_title']));
				$insert_array['meta_description']		= trim(add_slash($_REQUEST['page_meta']));
			}
			else
			{
				$insert_array['sites_site_id']			= $ecom_siteid;
				$insert_array['title']					= trim(add_slash($_REQUEST['page_title']));
				$insert_array['meta_description']		= trim(add_slash($_REQUEST['page_meta']));				
			}
			
			$db->insert_from_array($insert_array, $tb_name);
			$insert_id = $db->insert_id();
			
			if($insert_id == "" || $insert_id == 0)
			{
				$alert	=	"Inserting seo info failed.";
			}
		}
		else
		{
			$alert = 'Error! '.$alert;
			echo '<br />';
			$_REQUEST['curtab']	=	'seo_tab_td';
			include ("includes/static_page/ajax/page_ajax_functions.php");
			include("includes/static_page/edit_page.php");	
		}
	}
	if(!$alert)
	{
		delete_static_page_cache($_REQUEST['page_id']);
		recreate_entire_websitelayout_cache();
		delete_body_cache();
		/* Button code to save and return starts here */
		if($_REQUEST['Submit'] == 'Save & Return')
		{
?>			<script language="javascript">
			show_processing();
			window.parent.location = 'home.php?request=stat_page&fpurpose=edit&page_id=<?=$_REQUEST['page_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&sel_group=<?=$_REQUEST['sel_group']?>';
			</script>
<?php	}
		else
		{				
			$alert .= '<br><span class="redtext"><b>Static Page Updated successfully</b></span><br>';
			echo $alert;
		?>
		<br /><a class="smalllink" href="home.php?request=stat_page&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&sel_group=<?=$_REQUEST['sel_group']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
		<a class="smalllink" href="home.php?request=stat_page&fpurpose=edit&page_id=<?=$_REQUEST['page_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&sel_group=<?=$_REQUEST['sel_group']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
		<a class="smalllink" href="home.php?request=stat_page&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&sel_group=<?=$_REQUEST['sel_group']?>" onclick="show_processing()">Go Back to the Add New Page</a>
<?php	}
		/* Button code to save and return starts here */
	}
	else
	{
		$alert = 'Error! '.$alert;
		echo '<br />';
		$ajax_return_function = 'ajax_return_contents';
		include "ajax/ajax.php";
		$_REQUEST['curtab']	=	'seo_tab_td';
		include ("includes/static_page/ajax/page_ajax_functions.php");
		include("includes/static_page/edit_page.php");	
	}
}
else if($_REQUEST['fpurpose']=='list_page_seoinfo')
{
	include_once("../functions/functions.php");
	include_once('../session.php');
	include_once("../config.php");
	include ("../includes/static_page/ajax/page_ajax_functions.php");
	//include("includes/static_page/edit_page.php");
	show_page_seoinfo($_REQUEST['page_id'],$_REQUEST['page_name'],$alert);
}/* SEO tab in static page starts here */
/*
else if($_REQUEST['fpurpose']=='list_page_maininfo')
{
	//$ajax_return_function = 'ajax_return_contents';
	include_once("functions/console_urls.php");
	include ("includes/static_page/ajax/page_ajax_functions.php");
	include("includes/static_page/edit_page.php");
	show_page_maininfo($_REQUEST['page_id'],$alert);
}
*/ 
else if($_REQUEST['fpurpose']=='edit')
{
	include_once("functions/console_urls.php");
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include ('includes/static_page/ajax/page_ajax_functions.php');
	include("includes/static_page/edit_page.php");
	
}
else if($_REQUEST['fpurpose']=='insert')
{
	
	if($_REQUEST['Submit'])
	{
		
		$alert				= '';
		$fieldRequired 		= array($_REQUEST['title']);
		$fieldDescription 	= array('Page Title');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		
		$sql_check = "SELECT count(*) as cnt FROM static_pages WHERE title = '".trim(add_slash($_REQUEST['title']))."' AND sites_site_id=$ecom_siteid";
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Page Title Already exists '; 
		if(!$alert) 
		{
			$insert_array = array();
			$insert_array['title']					= add_slash($_REQUEST['title']);
			$insert_array['pname']					= add_slash($_REQUEST['title']);
			$insert_array['content']				= add_slash($_REQUEST['content'],false);
			$insert_array['hide']					= $_REQUEST['hide'];
			$insert_array['sites_site_id']			= $ecom_siteid;
			$insert_array['page_type']				= $_REQUEST['page_type'];
			$insert_array['page_link']				= add_slash($_REQUEST['page_link']);
			$insert_array['page_link_newwindow']	= add_slash($_REQUEST['page_link_newwindow']);
			$insert_array['allow_auto_linker']		= ($_REQUEST['allow_auto_linker'])?1:0;
                       	if($ecom_site_mobile_api==1)
			{
			$insert_array['in_mobile_api_sites']		= ($_REQUEST['in_mobile_api_sites'])?1:0;
			}
			$db->insert_from_array($insert_array, 'static_pages');
			$insert_id = $db->insert_id();
			#Page Groups Position
			if(count($_REQUEST['page_group'])>0)
			{
				foreach($_REQUEST['page_group'] as $v)
				{
					$insert_array 								= array();
					$insert_array['static_pages_page_id']		= $insert_id;
					$insert_array['static_pagegroup_group_id']	= $v;
					$db->insert_from_array($insert_array, 'static_pagegroup_static_page_map');
				}
			}
			// Deleting Cache
			delete_static_page_cache($insert_id);
			delete_body_cache();
			recreate_entire_websitelayout_cache();
			/* Button code to save and return starts here */
			if($_REQUEST['Submit'] == 'Save & Return to Edit')
			{
			?>
			<script language="javascript">
			show_processing();
			window.parent.location = 'home.php?request=stat_page&fpurpose=edit&page_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>';
			</script>
			<?
			}
			else
			{
				$alert .= '<br><span class="redtext"><b>Page added successfully</b></span><br>';
				echo $alert;
			
			?>
			<br /><a class="smalllink" href="home.php?request=stat_page&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=stat_page&fpurpose=edit&page_id=<?=$insert_id?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=stat_page&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>" onclick="show_processing()">Go Back to the Add New Page</a>
			<?
			}/* Button code to save and return ends here */
			
		}	
		else
		{
			$alert = '<strong>Error!!</strong> '.$alert;
			$alert .= '<br>';
			include("includes/static_page/add_page.php");
		}
	}
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert				= '';
		$fieldRequired 		= array($_REQUEST['title']);
		$fieldDescription 	= array('Page Title');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = "SELECT count(*) as cnt FROM static_pages WHERE title = '".trim(add_slash($_REQUEST['title']))."' AND sites_site_id=$ecom_siteid AND page_id<>".$_REQUEST['page_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
		
		if($row_check['cnt'] > 0)
			$alert = 'Page Name Already exists '; 
		if(!$alert) {
			$update_array 							= array();
			if(trim($_REQUEST['pname'])!='Home'){
			$update_array['title']					= trim(add_slash($_REQUEST['title']));
			$update_array['pname']					= trim(add_slash($_REQUEST['title']));
			}
			/*else
			{
			 $alert ='<span class="redtext">Home page title cannot be changed!</span><br>';
			}*/
			$update_array['content']				= add_slash($_REQUEST['content'],false);
			$update_array['hide']					= $_REQUEST['hide'];
			$update_array['page_type']				= $_REQUEST['page_type'];
			$update_array['page_link']				= add_slash($_REQUEST['page_link']);
			$update_array['page_link_newwindow']			= add_slash($_REQUEST['page_link_newwindow']);
			$update_array['allow_auto_linker']			= ($_REQUEST['allow_auto_linker'])?1:0;
			if($ecom_site_mobile_api==1)
			{
			 $update_array['in_mobile_api_sites']			= ($_REQUEST['in_mobile_api_sites'])?1:0;
			}
			$db->update_from_array($update_array, 'static_pages', 'page_id', $_REQUEST['page_id']);
                       
                        // calling function to send notification mails to seo engineers
			send_support_notification_emails('home',array('cur_id'=>$_REQUEST['page_id']));
			#Page Group
			
			#Deleting existing records
			$sql_del = "DELETE FROM static_pagegroup_static_page_map WHERE static_pages_page_id=".$_REQUEST['page_id'];;
			$db->query($sql_del);
			if(count($_REQUEST['page_group'])>0)
			{
				foreach($_REQUEST['page_group'] as $v)
				{
					$insert_array 								= array();
					$insert_array['static_pages_page_id']		= $_REQUEST['page_id'];
					$insert_array['static_pagegroup_group_id']	= $v;
					$db->insert_from_array($insert_array, 'static_pagegroup_static_page_map');
				}
			
			}
			// Deleting Cache
			delete_static_page_cache($_REQUEST['page_id']);
			recreate_entire_websitelayout_cache();
			delete_body_cache();
			/* Button code to save and return starts here */
			if($_REQUEST['Submit'] == 'Save & Return')
			{
			?>
			<script language="javascript">
			show_processing();
			window.parent.location = 'home.php?request=stat_page&fpurpose=edit&page_id=<?=$_REQUEST['page_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&sel_group=<?=$_REQUEST['sel_group']?>';
			</script>
			<?
			}
			else
			{				
				$alert .= '<br><span class="redtext"><b>Static Page Updated successfully</b></span><br>';
				echo $alert;
			?>
			<br /><a class="smalllink" href="home.php?request=stat_page&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&sel_group=<?=$_REQUEST['sel_group']?>" onclick="show_processing()">Go Back to the Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=stat_page&fpurpose=edit&page_id=<?=$_REQUEST['page_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&sel_group=<?=$_REQUEST['sel_group']?>" onclick="show_processing()">Go Back to the Edit  Page</a><br /><br />
			<a class="smalllink" href="home.php?request=stat_page&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&start=<?=$_REQUEST['start']?>&sel_group=<?=$_REQUEST['sel_group']?>" onclick="show_processing()">Go Back to the Add New Page</a>
		<?	}/* Button code to save and return ends here */
		}
		else {
			$alert = 'Error! '.$alert;
		
		?>
			<br />
			<?php
			$ajax_return_function = 'ajax_return_contents';
			include "ajax/ajax.php";
			$_REQUEST['curtab']	=	'seo_tab_td';
			include ("includes/static_page/ajax/page_ajax_functions.php");
			include("includes/static_page/edit_page.php");
		}
	}
}
?>
