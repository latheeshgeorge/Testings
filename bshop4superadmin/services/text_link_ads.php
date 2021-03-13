<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/text_link_ad/list_text_link_ad.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/text_link_ad/add_text_link_ad.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
	if(is_numeric($_REQUEST['site_id']))
	{
		include("includes/text_link_ad/edit_text_link_ad.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid site Id</font></center><br>';
		echo $alert;
		include("includes/text_link_ad/list_text_link_ad.php");
	}	
} else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['cardtype_key'],$_REQUEST['cardtype_caption'],$_REQUEST['cardtype_numberofdigits']);
		$fieldDescription = array('Key','Caption','Num Digits');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		$sql_check = $db->value_exists(array('cardtype_caption' => $_REQUEST['cardtype_caption']), 'payment_methods_supported_cards');
		if($sql_check > 0) {
			$alert = 'Caption already exists';
		}
		if(!$alert) {
			// Checking for duplicate card names
			
			$insert_array = array();
			$insert_array['cardtype_key']=add_slash($_REQUEST['cardtype_key']); 
			$insert_array['cardtype_caption']=add_slash($_REQUEST['cardtype_caption']);
			$insert_array['cardtype_numberofdigits']=add_slash($_REQUEST['cardtype_numberofdigits']);
			$db->insert_from_array($insert_array, 'payment_methods_supported_cards');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Inserted</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=credit_cards&caption=<?=$_REQUEST['caption']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=credit_cards&fpurpose=edit&cardtype_id=<?=$insert_id?>&caption=<?=$_REQUEST['caption']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Card</a><br /><br />
			<a href="home.php?request=credit_cards&fpurpose=add&sort_by=<?=$_REQUEST['sort_by']?>&caption=<?=$_REQUEST['caption']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add new Card</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/text_link_ad/add_text_link_ad.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert = '';
		$fieldRequired = array($_REQUEST['site_xml_filename'],$_REQUEST['site_xml_key']);
		$fieldDescription = array('File Name','Key');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array();
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		if(!$alert) {
			$update_array = array();
			$update_array['site_xml_filename']=add_slash($_REQUEST['site_xml_filename']);
			$update_array['site_xml_key']=add_slash($_REQUEST['site_xml_key']);
			$db->update_from_array($update_array, 'sites', 'site_id', $_REQUEST['site_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=text_link_ad&site_id=<?=$row['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=text_link_ad&fpurpose=edit&site_id=<?=$_REQUEST['site_id']?>&title=<?=$_REQUEST['title']?>&domain=<?=$_REQUEST['domain']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Site</a><br /><br />
			
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/text_link_ad/edit_text_link_ad.php");
		}
		
	}
}
elseif ($_REQUEST['fpurpose'] == 'delete') {
	if($_REQUEST['cardtype_id']) {
		$alert_del = '';
		$sql_check = $db->value_exists(array('cardtype_id' => $_REQUEST['cardtype_id']), 'payment_methods_supported_cards');
		if($sql_check) {
			#Delete the client record.
			$db->delete_id($_REQUEST['cardtype_id'], 'cardtype_id', 'payment_methods_supported_cards');
			#Search Options
			$alert_del = '<font color="red">Successfully Deleted</font>';
				
			
		} else {
			$alert_del = '<font color="red">Error! No country exists with this id</font>';
		}
	}
	include("includes/text_link_ad/list_text_link_ad.php");
}else if($_REQUEST['fpurpose'] == 'manage_category_xml') {
	if($_REQUEST['site_id'])
	{
		include("includes/text_link_ad/manage_category_xml.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid site Id</font></center><br>';
		echo $alert;
		include("includes/text_link_ad/list_text_link_ad.php");
	}	
}else if($_REQUEST['fpurpose'] == 'save_category_xml') {
	if($_REQUEST['site_id'] && $_REQUEST['update_category_xml'])
	{
	if(count($_REQUEST['category_id'])){
		foreach($_REQUEST['category_id'] as $key =>$value){
			$update_array = array();
			$update_array['category_xml_filename']=add_slash($_REQUEST['category_xml_filename'][$key]);
			$update_array['category_xml_key']=add_slash($_REQUEST['category_xml_key'][$key]);
			$db->update_from_array($update_array, 'product_categories', array('sites_site_id'=>$_REQUEST['site_id'],'category_id'=>$value));
		}
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
	}		
		include("includes/text_link_ad/manage_category_xml.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid site Id</font></center><br>';
		echo $alert;
		include("includes/text_link_ad/list_text_link_ad.php");
	}	
}
else if($_REQUEST['fpurpose'] == 'manage_product_xml') {
	if($_REQUEST['site_id'])
	{
		include("includes/text_link_ad/manage_product_xml.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid site Id</font></center><br>';
		echo $alert;
		include("includes/text_link_ad/list_text_link_ad.php");
	}	
}else if($_REQUEST['fpurpose'] == 'save_product_xml') {
	if($_REQUEST['site_id'] && $_REQUEST['update_product_xml'])
	{
	if(count($_REQUEST['product_id'])){
		foreach($_REQUEST['product_id'] as $key =>$value){
			$update_array = array();
			$update_array['product_xml_filename']=add_slash($_REQUEST['product_xml_filename'][$key]);
			$update_array['product_xml_key']=add_slash($_REQUEST['product_xml_key'][$key]);
			$db->update_from_array($update_array, 'products', array('sites_site_id'=>$_REQUEST['site_id'],'product_id'=>$value));
		}
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
	}		
		include("includes/text_link_ad/manage_product_xml.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid site Id</font></center><br>';
		echo $alert;
		include("includes/text_link_ad/list_text_link_ad.php");
	}	
}
else if($_REQUEST['fpurpose'] == 'manage_staticpage_xml') {
	if($_REQUEST['site_id'])
	{
		include("includes/text_link_ad/manage_static_page_xml.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid site Id</font></center><br>';
		echo $alert;
		include("includes/text_link_ad/list_text_link_ad.php");
	}	
}else if($_REQUEST['fpurpose'] == 'save_staticpage_xml') {
	if($_REQUEST['site_id'] && $_REQUEST['update_page_xml'])
	{
	if(count($_REQUEST['page_id'])){
		foreach($_REQUEST['page_id'] as $key =>$value){
			$update_array = array();
			$update_array['page_xml_filename']=add_slash($_REQUEST['page_xml_filename'][$key]);
			$update_array['page_xml_key']=add_slash($_REQUEST['page_xml_key'][$key]);
			$db->update_from_array($update_array, 'static_pages', array('sites_site_id'=>$_REQUEST['site_id'],'page_id'=>$value));
		}
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
	}		
		include("includes/text_link_ad/manage_static_page_xml.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid site Id</font></center><br>';
		echo $alert;
		include("includes/text_link_ad/list_text_link_ad.php");
	}	
}

?>