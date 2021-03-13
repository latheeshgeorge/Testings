<?php
	
	if($_REQUEST['fpurpose']=='')
	{
		include ('includes/spend_giftvoucher/spend_giftvouchers.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
		$update_array['voucher_spend_text']						= stripslashes($_REQUEST['voucher_spend_text']);
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$alert .= '<br><span class="redtext"><b>Spend Voucher content added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=spend_voucher">Go Back to the Spend Gift Voucher  page</a><br />
				 <br />
							   
			<?
	}
?>

