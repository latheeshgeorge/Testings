
<?php
	
	if($_REQUEST['fpurpose']=='')
	{
		include ('includes/buy_giftvoucher/buy_giftvouchers.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
		$update_array['voucher_buy_text']						= stripslashes($_REQUEST['voucher_buy_text']);
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		
                // Creating the general settings cache files to be included in client area to save time to access the settings each time from db
                create_GeneralSettings_CacheFile();
		$alert .= '<br><span class="redtext"><b>Buy Voucher content added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=buy_voucher">Go Back to the Buy Gift Voucher page</a><br />
				 <br />
							   
			<?
	}
?>