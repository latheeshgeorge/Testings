<?php
	
	if($_REQUEST['fpurpose']=='')
	{
		include ('includes/general_shopsall_content/general_shopsall_content.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
		$update_array['general_shopsall_topcontent']						= stripslashes($_REQUEST['general_shopsall_topcontent']);
	    $update_array['general_shopsall_bottomcontent']						= stripslashes($_REQUEST['general_shopsall_bottomcontent']);
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$alert .= '<br><span class="redtext"><b>Shops Contents Added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=general_shopsall_content">Go Back to the Shops Content Adding Page</a><br />
				 <br />
							   
			<?
	}
?>