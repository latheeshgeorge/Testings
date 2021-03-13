<?php
	
	if($_REQUEST['fpurpose']=='')
	{
		include ('includes/general_commoncontent_details/general_commoncontent_details.php');
	}
	elseif($_REQUEST['fpurpose']=='save_add_bonus')
	{
		$update_array['bonus_point_details_content']						= addslashes(stripslashes($_REQUEST['bonus_point_details_content']));
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$alert .= '<br><span class="redtext"><b>Bonus Point Details Contents Added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=general_commoncontent_details&content_Type=<?=$_REQUEST['content_Type']?>&cbo_keytype=<?=$_REQUEST['cbo_keytype']?>">Go to General Content Managing Page</a><br />
				 <br />
							   
			<?
	}
	elseif($_REQUEST['fpurpose']=='save_add_gift')
	{
		$update_array['voucher_buy_text']						= addslashes(stripslashes($_REQUEST['voucher_buy_text']));
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		
                // Creating the general settings cache files to be included in client area to save time to access the settings each time from db
                create_GeneralSettings_CacheFile();
		$alert .= '<br><span class="redtext"><b>Buy Voucher content added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=general_commoncontent_details&content_Type=<?=$_REQUEST['content_Type']?>&cbo_keytype=<?=$_REQUEST['cbo_keytype']?>">Go to General Content Managing Page</a><br />
				 <br />
							   
			<?
	}
	elseif($_REQUEST['fpurpose']=='save_add_download')
	{
		$update_array['general_download_topcontent']				= addslashes($_REQUEST['general_download_topcontent']);
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		
		$alert .= '<br><span class="redtext"><b>General Downloads Top Content Saved Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=general_commoncontent_details&content_Type=<?=$_REQUEST['content_Type']?>&cbo_keytype=<?=$_REQUEST['cbo_keytype']?>">Go to General Content Managing Page</a><br />
				 <br />
							   
			<?
	}
	elseif($_REQUEST['fpurpose']=='save_add_spendvouch')
	{
		$update_array['voucher_spend_text']						= addslashes(stripslashes($_REQUEST['voucher_spend_text']));
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$alert .= '<br><span class="redtext"><b>Spend Voucher content added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=general_commoncontent_details&content_Type=<?=$_REQUEST['content_Type']?>&cbo_keytype=<?=$_REQUEST['cbo_keytype']?>">Go to General Content Managing Page</a><br />
				 <br />
							   
			<?
	}
	elseif($_REQUEST['fpurpose']=='save_add_pricepromise')
	{
		$update_array['pricepromise_topcontent']					= addslashes($_REQUEST['pricepromise_topcontent']);
		$update_array['pricepromise_bottomcontent']					= addslashes($_REQUEST['pricepromise_bottomcontent']);
		/*$update_array['pricepromise_toaddress']						= addslashes($_REQUEST['pricepromise_toaddress']);*/
		$update_array['general_pricepromise_addtocart']				= addslashes($_REQUEST['general_pricepromise_addtocart']);
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		
		$alert .= '<br><span class="redtext"><b>Price Promise Contents Added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=general_commoncontent_details&content_Type=<?=$_REQUEST['content_Type']?>&cbo_keytype=<?=$_REQUEST['cbo_keytype']?>">Go to General Content Managing Page</a><br />
				 <br />
			<?
	}
	elseif($_REQUEST['fpurpose']=='save_add_freedeli')
	{
		$update_array['product_freedelivery_content']						= addslashes(stripslashes($_REQUEST['product_freedelivery_content']));
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$alert .= '<br><span class="redtext"><b>Price Product Free Delivery Contents Added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=general_commoncontent_details&content_Type=<?=$_REQUEST['content_Type']?>&cbo_keytype=<?=$_REQUEST['cbo_keytype']?>">Go to General Content Managing Page</a><br />
				 <br />
			<?
	}
	elseif($_REQUEST['fpurpose']=='save_add_payonacc')
	{
		$update_array['payon_account_details_content']						= addslashes(stripslashes($_REQUEST['payon_account_details_content']));
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$alert .= '<br><span class="redtext"><b>Payon Account Contents Added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=general_commoncontent_details&content_Type=<?=$_REQUEST['content_Type']?>&cbo_keytype=<?=$_REQUEST['cbo_keytype']?>">Go to General Content Managing Page</a><br />
				 <br />
			<?
	}
	elseif($_REQUEST['fpurpose']=='save_add_shops')
	{
		$update_array['general_shopsall_topcontent']						= addslashes(stripslashes($_REQUEST['general_shopsall_topcontent']));
	    $update_array['general_shopsall_bottomcontent']						= addslashes(stripslashes($_REQUEST['general_shopsall_bottomcontent']));
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$alert .= '<br><span class="redtext"><b>Shops Contents Added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=general_commoncontent_details&content_Type=<?=$_REQUEST['content_Type']?>&cbo_keytype=<?=$_REQUEST['cbo_keytype']?>">Go to General Content Managing Page</a><br />
				 <br />
			<?
	}
	elseif($_REQUEST['fpurpose']=='save_add_savedsearch')
	{
		$update_array['general_savedsearch_topcontent']				= addslashes(stripslashes($_REQUEST['general_savedsearch_topcontent']));
	    $update_array['general_savedsearch_bottomcontent']			= addslashes(stripslashes($_REQUEST['general_savedsearch_bottomcontent']));
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$alert .= '<br><span class="redtext"><b>Saved Search Contents Added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=general_commoncontent_details&content_Type=<?=$_REQUEST['content_Type']?>&cbo_keytype=<?=$_REQUEST['cbo_keytype']?>">Go to General Content Managing Page</a><br />
				 <br />
			<?
	}
	elseif($_REQUEST['fpurpose']=='save_add_comboall')
	{
		$update_array['general_comboall_topcontent']						= addslashes(stripslashes($_REQUEST['general_comboall_topcontent']));
	    $update_array['general_comboall_bottomcontent']						= addslashes(stripslashes($_REQUEST['general_comboall_bottomcontent']));
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$alert .= '<br><span class="redtext"><b>ComboAll Contents Added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=general_commoncontent_details&content_Type=<?=$_REQUEST['content_Type']?>&cbo_keytype=<?=$_REQUEST['cbo_keytype']?>">Go to General Content Managing Page</a><br />
				 <br />
			<?
	}
	elseif($_REQUEST['fpurpose']=='save_add_multibuy')
	{
	    $update_array['general_multibuy_bottomcontent']						= addslashes(stripslashes($_REQUEST['general_multibuy_bottomcontent']));
		$db->update_from_array($update_array, 'general_settings_sites_common','sites_site_id', $ecom_siteid);
		$alert .= '<br><span class="redtext"><b>Multibuy Bottom Contents Added Successfully</b></span><br>';
		echo $alert;
		?>
				<br />
				<a class="smalllink" href="home.php?request=general_commoncontent_details&content_Type=<?=$_REQUEST['content_Type']?>&cbo_keytype=<?=$_REQUEST['cbo_keytype']?>">Go to General Content Managing Page</a><br />
				 <br />
			<?
	}
	elseif($_REQUEST['fpurpose']=='showcontent')
	{
	    include("includes/general_commoncontent_details/general_commoncontent_details.php");
	}
?>
