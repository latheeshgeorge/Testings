<?php
if($_REQUEST['fpurpose']=='')
{
	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/site_reviews/list_site_reviews.php");
}

elseif($_REQUEST['fpurpose']=='change_hide')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$review_ids_arr 		= explode('~',$_REQUEST['reviewids']);
		$new_status		= $_REQUEST['ch_status'];
		for($i=0;$i<count($review_ids_arr);$i++)
		{
			$update_array					= array();
			$update_array['review_hide']	= $new_status;
			$review_id 						= $review_ids_arr[$i];	
			$db->update_from_array($update_array,'sites_reviews',array('review_id'=>$review_id));
		}
		
		$alert = 'Status changed successfully.';
		include ('../includes/site_reviews/list_site_reviews.php');
		
}
elseif($_REQUEST['fpurpose']=='change_status')
	{
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");	
		$review_ids_arr 		= explode('~',$_REQUEST['reviewids']);
		$new_reviewstatus		= $_REQUEST['ch_revstatus'];
		$alert_success = 0;
		for($i=0;$i<count($review_ids_arr);$i++)
		{
		$approved_status_count=0;
		$sql_chk_status = "SELECT review_status FROM sites_reviews WHERE review_id = ".$review_ids_arr[$i];
		$ret_chk_status = $db->query($sql_chk_status);
		$chk_status = $db->fetch_array($ret_chk_status);
	
		if($chk_status['review_status'] !='APPROVED'){// if the status is already APPROVED do nothing
		
			$update_array					= array();
			$update_array['review_status']	= $new_reviewstatus;
			$review_id 						= $review_ids_arr[$i];
			if($new_reviewstatus == 'APPROVED'){
			$update_array['review_approved_by'] 	= add_slash($_SESSION['console_id']);
			}	
			$db->update_from_array($update_array,'sites_reviews',array('review_id'=>$review_id));
			$alert_success++;
		}else{
		$approved_status_count++;
		}
		}
		if($alert_success)
		{
		$alert = 'Status changed successfully.';
		}
		//if( $db->num_rows($ret_chk_status)){
		if($approved_status_count){
		if($alert)
		$alert .= "<br>";
		$alert .= "Already Approved status cannot be Changed !!";
		}
		include ('../includes/site_reviews/list_site_reviews.php');
		
}
else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry Reviews not selected';
		}
		else
		{
		
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					  $sql_del = "DELETE FROM sites_reviews WHERE review_id=".$del_arr[$i];
					  $db->query($sql_del);
				}	
			}
			$alert = "Reviews deleted Sucessfully";
		}
		include ('../includes/site_reviews/list_site_reviews.php');
	

}
else if($_REQUEST['fpurpose']=='edit')
{	
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	//include_once("classes/fckeditor.php");
	$review_id = $_REQUEST['checkbox'][0];
	include("includes/site_reviews/edit_site_reviews.php");
	
}
else if($_REQUEST['fpurpose'] == 'update_review') {  // for updating the Review

	if($_REQUEST['review_id'])
	{
		//Function to validate forms
		validate_forms();
		if (!$alert)
		{
			$update_array							= array();
			$update_array['sites_site_id'] 			= $ecom_siteid;
			//$exp_review_date=explode("-",$_REQUEST['review_date']);
			//$val_review_date                        =$exp_review_date[2]."-".$exp_review_date[1]."-".$exp_review_date[0];
			//$update_array['review_date'] 			= addslashes($val_review_date);
			$update_array['review_author'] 			= add_slash($_REQUEST['review_author']);
			$update_array['review_author_email']	= $_REQUEST['review_author_email'];
			$update_array['review_details'] 		= add_slash($_REQUEST['review_details']);
			$update_array['review_rating']			= add_slash($_REQUEST['review_rating']) ;
			if($_REQUEST['review_status']){
			$update_array['review_status']			= $_REQUEST['review_status'];
			}
			$update_array['review_hide'] 			=($_REQUEST['review_hide'])?1:0;
			if($_REQUEST['review_status'] == 'APPROVED'){
		   $chk_status_sql = "SELECT  review_status,review_approved_by FROM sites_reviews WHERE review_id=".$_REQUEST['review_id'];
		   $res_status_sql 			= $db->query($chk_status_sql);
		   $review_status 	= $db->fetch_array($res_status_sql);
		   if($review_status['review_status'] != 'APPROVED'){
		   $update_array['review_approved_by'] 	= add_slash($_SESSION['console_id']);
		   }
		}
		
$db->update_from_array($update_array, 'sites_reviews', array('review_id'=>$_REQUEST['review_id'] ,'sites_site_id'=>$ecom_siteid));
		
				
			?>
			<br><font color="red"><b>Review Updated Successfully</b></font><br>
			<br /><a class="smalllink" href="home.php?request=site_reviews&fpurpose=edit&review_id=<?=$_REQUEST['review_id']?>&srch_author=<?=$_REQUEST['srch_author']?>&srch_review_startdate=<?=$_REQUEST['srch_review_startdate']?>&srch_review_enddate=<?=$_REQUEST['srch_review_enddate']?>&srch_review_status=<?=$_REQUEST['srch_review_status']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Review Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=site_reviews&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&srch_author=<?=$_REQUEST['srch_author']?>&srch_review_startdate=<?=$_REQUEST['srch_review_startdate']?>&srch_review_enddate=<?=$_REQUEST['srch_review_enddate']?>&srch_review_status=<?=$_REQUEST['srch_review_status']?>">Go Back to the Review Listing page</a><br /><br />
			<?php
		}
		else
		{
		?>
			<br><font color="red"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>
	<?php
		}
	}
	else
	{
	?>
		<br><font color="red"><strong>Error!</strong> Invalid Review Id</font><br />
		<br /><a class="smalllink" href="home.php?request=site_reviews&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&srch_author=<?=$_REQUEST['srch_author']?>&srch_review_startdate=<?=$_REQUEST['srch_review_startdate']?>&srch_review_enddate=<?=$_REQUEST['srch_review_enddate']?>&srch_review_status=<?=$_REQUEST['srch_review_status']?>">Go Back to the Listing page</a><br /><br />
		
	<?php	
	} //// updating adverts ends

}


// ===============================================================================
// 						FUNCTIONS USED IN THIS PAGE
// ===============================================================================	
function validate_forms()
{
	global $alert,$db;
	if($_REQUEST['dont_save']!=1)
	{
		//Validations
		$alert = '';
		$fieldRequired 		= array( $_REQUEST['review_author']);
		$fieldDescription 	= array( 'Review Author');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
	}
}



?>
