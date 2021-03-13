<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/card/list_cards.php");
} else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/card/add_card.php");
} else if($_REQUEST['fpurpose'] == 'edit') {
	if(is_numeric($_REQUEST['cardtype_id']))
	{
		include("includes/card/edit_card.php");
	}
	else
	{
		$alert = '<center><font color="red">Invalid card Id</font></center><br>';
		echo $alert;
		include("includes/card/list_cards.php");
	}	
} else if($_REQUEST['fpurpose'] == 'insert') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired = array($_REQUEST['cardtype_key'],$_REQUEST['cardtype_caption'],$_REQUEST['cardtype_numberofdigits'],$_REQUEST['cardtype_securitycode_count']);
		$fieldDescription = array('Key','Caption','Num Digits','Security Code count');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array($_REQUEST['cardtype_numberofdigits'],$_REQUEST['cardtype_securitycode_count']);
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
			$insert_array['cardtype_issuenumber_req']=($_REQUEST['cardtype_issuenumber_req'])?1:0;
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
			include("includes/card/add_card.php");
		}
		
	}
} else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		
		$alert = '';
		$fieldRequired = array($_REQUEST['cardtype_key'],$_REQUEST['cardtype_caption'],$_REQUEST['cardtype_numberofdigits'],$_REQUEST['cardtype_securitycode_count']);
		$fieldDescription = array('Key','Caption','Num Digits','Security Code count');
		$fieldEmail = array();
		$fieldConfirm = array();
		$fieldConfirmDesc = array();
		$fieldNumeric = array($_REQUEST['cardtype_numberofdigits'],$_REQUEST['cardtype_securitycode_count']);
		$fieldNumericDesc = array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		// Checking for duplicate card names
		$sql_check = "SELECT count(*) as cnt FROM payment_methods_supported_cards WHERE cardtype_caption='".$_REQUEST['cardtype_caption']."' AND cardtype_id<>".$_REQUEST['cardtype_id'];
		$res_check = $db->query($sql_check);
		$row_check = $db->fetch_array($res_check);
	
		if($row_check['cnt'] > 0) {
			$alert = 'Caption already exists';
		}
		if(!$alert) {
			$update_array = array();
			$update_array['cardtype_key']=add_slash($_REQUEST['cardtype_key']);
			$update_array['cardtype_caption']=add_slash($_REQUEST['cardtype_caption']);
			$update_array['cardtype_numberofdigits']=add_slash($_REQUEST['cardtype_numberofdigits']);
			$update_array['cardtype_issuenumber_req']=($_REQUEST['cardtype_issuenumber_req'])?1:0;
			$update_array['cardtype_securitycode_count']=add_slash($_REQUEST['cardtype_securitycode_count']);
			$db->update_from_array($update_array, 'payment_methods_supported_cards', 'cardtype_id', $_REQUEST['cardtype_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=credit_cards&caption=<?=$_REQUEST['caption']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
			<a href="home.php?request=credit_cards&fpurpose=edit&cardtype_id=<?=$_REQUEST['cardtype_id']?>&caption=<?=$_REQUEST['caption']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this Card</a><br /><br />
			<a href="home.php?request=credit_cards&fpurpose=add&caption=<?=$_REQUEST['caption']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Add Card</a></center>
			<?php
			
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/card/edit_card.php");
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
	include("includes/card/list_cards.php");
}

?>