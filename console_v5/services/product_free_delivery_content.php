<?php
	
	if($_REQUEST['fpurpose']=='')
	{
		include ('includes/product_free_delivery_content/product_free_delivery_content.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add')
	{
		$update_array['product_freedelivery_content']						= stripslashes($_REQUEST['product_freedelivery_content']);
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$alert .= '<br><span class="redtext"><b>Price Product Free Delivery Contents Added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=product_free_delivery_content">Go Back to the Product Free Delivery Content Adding Page</a><br />
				 <br />
							   
			<?
	}
?>