<?php
$section_type_array = array('enquire' => 'Enquire Form', 'register' => 'Registration Form');
if($_REQUEST['fpurpose'] == '') {
	if($_REQUEST['section_id'] && $_REQUEST['submit']) {
			$db->query("UPDATE element_sections SET section_name='".$_REQUEST['section_name']."',sort_no=".$_REQUEST['sort_no'].",position='".$_REQUEST['position']."',message='".$_REQUEST['message']."' WHERE site_id=$ecom_siteid AND section_id=".$_REQUEST['section_id']);
	}
	include("includes/customform/list_customform.php");
} else if ($_REQUEST['fpurpose']=='formcreation') {
	if($_REQUEST['submit'] && $_REQUEST['section_name']) {
		
		$db->query("INSERT INTO element_sections SET section_name='".$_REQUEST['section_name']."', site_id=$ecom_siteid,  activate=0, position='".$_REQUEST['position']."',message='".$_REQUEST['message']."',section_type='".$_REQUEST['form_type']."'");
		$section_id=$db->insert_id();
		echo "<script>window.location ='home.php?request=customform&fpurpose=formcreation&section_id=".$section_id."&form_type=".$_REQUEST['form_type']."'</script>";
	}
	include("includes/customform/ad_checkoutform.php");
} else if($_REQUEST['fpurpose']=='formcreation_section') {
			include("includes/customform/ad_formcreation_section.php");
} else if($_REQUEST['fpurpose']=='formcreation_section_edit') {
			include("includes/customform/edit_formcreation_section.php");
} 
?>