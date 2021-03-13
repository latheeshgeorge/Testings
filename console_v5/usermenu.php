<?php
	$folder_path = $image_path.'/cache';
	// ############## Building the menu over here ##################
	if (!is_dir($folder_path))
		mkdir($folder_path);
	$console_menufile =$folder_path.'/console_menu_'.$_SESSION['console_id'].'.txt';
	//$console_menufile_new = $folder_path.'test.txt';
	// Check whether menu is already in session for current user. If exist use it other wise rebuild the menus
	if(file_exists($console_menufile1)) // case if cache file exists
	{
		$fp = fopen($console_menufile,'r');
		$content = fread($fp,filesize($console_menufile));
		echo $content;
	}
	else // case if cache files does not exists. so create cache and then echo it
	{
		// Starting the cache
		ob_start();
		$myMenu = '';
			
		if (!$myMenu)// do the following if the menu is not in session
		{
			$services_arr		= array();
			$exist_feature_site = array(0);
			$level_arr		 	= array(0);
			// Get the feature ids in current console levels
			$sql_cons = "SELECT features_feature_id FROM console_levels_details WHERE console_levels_level_id=$ecom_levelid";
			$ret_cons = $db->query($sql_cons);
			if ($db->num_rows($ret_cons))
			{
				while ($row_cons = $db->fetch_array($ret_cons))
				{
					$level_arr[] = $row_cons['features_feature_id'];
				}
			}
			$level_str = implode(",",$level_arr);
			if($_SESSION['user_type']=='sa') // case of system administrator. In this case all menu items will be displayed and unavailable will be shown as disabled
			{
				// Getting the list of services avaibale for the current site from mod_menu table and also whose status is not hidden
				//$sql_services = "SELECT service_id as services_service_id,service_name FROM services WHERE hide = 0 ORDER BY ordering";
				$sql_services = "SELECT service_id as services_service_id,service_name,service_icon FROM services WHERE hide = 0 ORDER BY ordering ASC ";
				$ret_services = $db->query($sql_services);
				// Find the list of features under this services for current site from mod_menu
				/*$sql_feat 	= "SELECT a.features_feature_id FROM mod_menu a,features b WHERE 
				sites_site_id=$ecom_siteid AND b.feature_hide = 0 AND a.features_feature_id=b.feature_id 
				 AND b.feature_displaytouser = 1 and a.features_feature_id IN ($level_str) ORDER BY feature_ordering";*/
				 
					$sql_feat 	= "SELECT a.features_feature_id FROM mod_menu a,features b WHERE 
				sites_site_id=$ecom_siteid AND b.feature_hide = 0 AND a.features_feature_id=b.feature_id 
				 AND b.feature_displaytouser = 1 and a.features_feature_id IN ($level_str) ORDER BY b.feature_name ASC";
				$ret_feat_exist_site = $db->query($sql_feat);
				if($db->num_rows($ret_feat_exist_site))
				{
					while ($row_feat_exist_site = $db->fetch_array($ret_feat_exist_site))
					{
						$exist_feature_site[] = $row_feat_exist_site['features_feature_id'];
					}
				}
			}
			else // case of system user. In this case only the menu items existing for the current site only will be shown.
			{
				
				
				$cur_user = $_SESSION['console_id'];
				
				// Get the permissions for current user
				$sql_user_perm = "SELECT a.features_feature_id FROM mod_menu a, site_user_permissions b WHERE 
									b.sites_site_id=$ecom_siteid AND sites_users_user_id=$cur_user AND 
									a.menu_id=b.mod_menu_menu_id and a.features_feature_id IN ($level_str) ";
				$ret_user_perm = $db->query($sql_user_perm);
				if ($db->num_rows($ret_user_perm))
				{
					while ($row_user_perm = $db->fetch_array($ret_user_perm))
					{
						$exist_feature_site[] = $row_user_perm['features_feature_id'];
					}
				}
				$ext_str = implode(",",$exist_feature_site);
				// Getting the list of services avaibale for the current site from mod_menu table and also whose status is not hidden
				/*$sql_services = "SELECT distinct a.services_service_id,b.service_name FROM mod_menu a,services b WHERE sites_site_id=$ecom_siteid 
								AND a.services_service_id=b.service_id AND b.hide = 0 AND a.features_feature_id IN($ext_str) ORDER BY b.ordering";*/
								
				$sql_services = "SELECT distinct a.services_service_id,b.service_name,b.service_icon FROM mod_menu a,services b WHERE sites_site_id=$ecom_siteid 
								AND a.services_service_id=b.service_id AND b.hide = 0 AND a.features_feature_id IN($ext_str) ORDER BY b.ordering ASC";				
				$ret_services = $db->query($sql_services);
									
			}	
			if($db->num_rows($ret_services))
			{
				while ($row_services = $db->fetch_array($ret_services))
				{
					$serv_id 				= $row_services['services_service_id'];
					$img_name			= $row_services['service_icon'];
					$services_arr[$serv_id] = '<div class="topmenu_iconclass"><img src="images/'.$img_name.'"></div>'.stripslashes($row_services['service_name']);
				}
			}	
			// Preparing the variable to hold the menu items to be displayed
			$myMenu = '';
			if(count($services_arr))
			{	
				$myMenu ="[";
				foreach ($services_arr as $k=>$v)
				{
					$name = str_replace(" ","_",$v);
					// Showing the services name here
					$myMenu .="[null,'".$v."',null,null,'".$name."'";
					
					if($_SESSION['user_type']=='sa') // case of system administrator
					{
						// Find the list of features under this services from features table
						/*$sql_feat = "SELECT feature_id as features_feature_id,feature_title as menu_title,feature_consoleurl,feature_new_icon,feature_disable_icon FROM 
						features  WHERE services_service_id = $k AND feature_hide = 0 AND parent_id = 0 AND feature_displaytouser = 1 
						ORDER BY feature_ordering";*/
						$sql_feat = "SELECT feature_id as features_feature_id,feature_title as menu_title,feature_consoleurl,feature_new_icon,feature_disable_icon FROM 
						features  WHERE services_service_id = $k AND feature_hide = 0 AND parent_id = 0 AND feature_displaytouser = 1 
						ORDER BY feature_name ASC";
						$ret_feat = $db->query($sql_feat);
						
					}
					else // case of system user
					{
						if (is_array($exist_feature_site))
						{
							$ext_str = implode(',',$exist_feature_site);
							$ext_st  = " AND a.features_feature_id IN ($ext_str)";
						}
						else
							$ext_str = '';
						// Find the list of features under this services for current site from mod_menu
						/*$sql_feat = "SELECT a.features_feature_id,b.feature_title as menu_title,b.feature_consoleurl,b.feature_new_icon,b.feature_disable_icon FROM mod_menu a,features b WHERE 
						sites_site_id=$ecom_siteid AND a.services_service_id = $k AND b.feature_hide = 0 
						AND a.features_feature_id=b.feature_id AND b.parent_id=0  AND b.feature_displaytouser = 1 $ext_st ORDER BY feature_ordering";*/
						
						$sql_feat = "SELECT a.features_feature_id,b.feature_title as menu_title,b.feature_consoleurl,b.feature_new_icon,b.feature_disable_icon FROM mod_menu a,features b WHERE 
						sites_site_id=$ecom_siteid AND a.services_service_id = $k AND b.feature_hide = 0 
						AND a.features_feature_id=b.feature_id AND b.parent_id=0  AND b.feature_displaytouser = 1 $ext_st ORDER BY b.feature_name ASC";
						$ret_feat = $db->query($sql_feat);
					}	
					if ($db->num_rows($ret_feat))
					{
						$myMenu .=",";
						while ($row_feat = $db->fetch_array($ret_feat))
						{
							$name = str_replace($row_feat['menu_title']," ","_");
							$enable_icon = $row_feat['feature_new_icon'];
							$disable_icon = $row_feat['feature_disable_icon'];
							// Showing the feature name
							if (in_array($row_feat['features_feature_id'],$exist_feature_site))
								$myMenu .= "['<img src=\"js/ThemeOffice/".$enable_icon."\" />','".$row_feat['menu_title']."','".$row_feat['feature_consoleurl']."',null,'$name'";
							else
							{
								//if($_SESSION['user_type']=='sa')
									$myMenu .= "['<img src=\"js/ThemeOffice/".$disable_icon."\" />','<span style=color:#c2d9ff>".$row_feat['menu_title']."</span>','',null,'$name'";	
							}	
							// calling recurssive function to handle the sub features
							$myMenu .= getconsolemenu($row_feat['features_feature_id'],0);
						}
					}
					//if(substr($myMenu,-2)=='],')
					//	$myMenu = substr($myMenu,0,strlen($myMenu)-2);
					$myMenu .="],";
				}	
				$myMenu .="]";
			}
		}
		//echo $myMenu;
		if($myMenu!= '')
		{
	?>		
				
				<div id="myMenuID"></div>
				<script language="JavaScript" type="text/javascript">
				var myMenu = <?php echo $myMenu?>;
				cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
				</script>
	<?php
		}
		$content = ob_get_contents();
		ob_end_clean();
		// Writing to file 
		//$fp = fopen($console_menufile,'w');
		//if ($fp)
		//{
		//	fwrite ($fp,$content);
		//}
		//fclose($fp);
		echo $content;
	}	
?>