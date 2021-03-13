<?php
	
	if($_REQUEST['fpurpose']=='')
	{
		include ('includes/price_promise_content/price_promise_content.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
		$update_array['pricepromise_topcontent']					= addslashes($_REQUEST['pricepromise_topcontent']);
		$update_array['pricepromise_bottomcontent']					= addslashes($_REQUEST['pricepromise_bottomcontent']);
		$update_array['pricepromise_toaddress']						= addslashes($_REQUEST['pricepromise_toaddress']);
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		
		$alert .= '<br><span class="redtext"><b>Price Promise Contents Added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=price_promise_content">Go Back to the Price Promise Content Adding Page</a><br />
				 <br />
							   
			<?
	}
?>