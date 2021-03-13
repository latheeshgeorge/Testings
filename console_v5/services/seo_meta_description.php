<?php
/*
	#################################################################
	# Script Name 	: seo_meta_description.php
	# Description 	: Action Page for changing the details of the seo meta description
	# Coded by 		: ANU
	# Created on	: 10-Sep-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/
if($_REQUEST['fpurpose'] == '') {
	include("includes/seo_meta_description/edit_seo_meta_description.php");
}
elseif($_REQUEST['fpurpose'] == 'Save_SiteDesc') { 
if($_REQUEST['Submit']){
		$alert = '';
		$fieldRequired = array();
		$fieldDescription = array();
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		if(!$alert)
		{
			//Check whether there exists record for current site in the se_description table
			$sql_check = "SELECT meta_id FROM se_meta_description WHERE sites_site_id =$ecom_siteid";
			$ret_check = $db->query($sql_check);
			if ($db->num_rows($ret_check)) // Case if a record exists in the table for current site
			{
				$row_check 						= $db->fetch_array($ret_check);
				$update_array					= array();
				//$update_array['home_title']		= addslashes($_REQUEST['txt_title']);
				$update_array['home_meta']		= addslashes($_REQUEST['txt_homemeta']);
				$update_array['static_meta']	= addslashes($_REQUEST['txt_staticmeta']);
				$update_array['product_meta']	= addslashes($_REQUEST['txt_productmeta']);
				$update_array['category_meta']	= addslashes($_REQUEST['txt_categorymeta']);
				$update_array['search_meta']	= addslashes($_REQUEST['txt_searchmeta']);
				$update_array['other_meta']		= addslashes($_REQUEST['txt_othermeta']);
				$update_array['search_content']	= addslashes($_REQUEST['txt_searchcontent']);
				$db->update_from_array($update_array, 'se_meta_description', array('sites_site_id' => $ecom_siteid , 'meta_id' => $row_check['meta_id']));
			}
			else
			{
				$insert_array					= array();
				$insert_array['sites_site_id']		= $ecom_siteid;
				//$insert_array['home_title']		= addslashes($_REQUEST['txt_title']);
				$insert_array['home_meta']		= addslashes($_REQUEST['txt_homemeta']);
				$insert_array['static_meta']	= addslashes($_REQUEST['txt_staticmeta']);
				$insert_array['product_meta']	= addslashes($_REQUEST['txt_productmeta']);
				$insert_array['category_meta']	= addslashes($_REQUEST['txt_categorymeta']);
				$insert_array['search_meta']	= addslashes($_REQUEST['txt_searchmeta']);
				$insert_array['other_meta']	= addslashes($_REQUEST['txt_othermeta']);
				$insert_array['search_content']	= addslashes($_REQUEST['txt_searchcontent']);
				$db->insert_from_array($insert_array,'se_meta_description');
			}
			$alert = "Meta Description Templates Saved Successfully";
		}
		else
		{
			$alert = "<strong>Error!!</strong> $alert";
		}	
		echo "<br /><span class='redtext'> <b>$alert</b></span><br />";
		?>
		<br /><a class="smalllink" href="home.php?request=seo_meta_description">Go Back to the Site Meta Descrition Template Manage page</a><br /><br />
		<?php
	}
			
			
}

?>