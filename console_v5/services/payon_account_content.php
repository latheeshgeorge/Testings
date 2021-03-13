<?php
	
	if($_REQUEST['fpurpose']=='')
	{
		include ('includes/payon_account_content/payon_account_content.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
		$update_array['payon_account_details_content']						= stripslashes($_REQUEST['payon_account_details_content']);
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$alert .= '<br><span class="redtext"><b>Payon Account Contents Added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=payon_account_content">Go Back to the Pay on Account Details Content Adding Page</a><br />
				 <br />
							   
			<?
	}
?>