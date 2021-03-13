<?php
	
	if($_REQUEST['fpurpose']=='')
	{
		include ('includes/bonus_point_details/bonus_point_details.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
		$update_array['bonus_point_details_content']						= stripslashes($_REQUEST['bonus_point_details_content']);
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$alert .= '<br><span class="redtext"><b>Bonus Point Details Contents Added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=bonus_point_details">Go Back to the Bonus Point Details Content Adding Page</a><br />
				 <br />
							   
			<?
	}
?>