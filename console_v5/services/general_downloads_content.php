<?php
	
	if($_REQUEST['fpurpose']=='')
	{
		include ('includes/general_downloads_content/general_downloads_content.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
		$update_array['general_download_topcontent']				= addslashes($_REQUEST['general_download_topcontent']);
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		
		$alert .= '<br><span class="redtext"><b>General Downloads Top Content Saved Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=general_downloads_topcontent">Go Back to the General Downloads Top Content Section</a><br />
				 <br />
							   
			<?
	}
?>