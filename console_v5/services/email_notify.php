<?php

if($_REQUEST['fpurpose']=='') 
{
	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	include("includes/email_notify/list_emailnewsletter.php");
}


else if($_REQUEST['fpurpose']=='delete')
{
	
		include_once("../functions/functions.php");
		include_once('../session.php');
		include_once("../config.php");
		if ($_REQUEST['del_ids'] == '')
		{
			$alert = 'Sorry News letter not selected';
		}
		else
		{
					
			$del_arr = explode("~",$_REQUEST['del_ids']);
			for($i=0;$i<count($del_arr);$i++)
			{
				if(trim($del_arr[$i]))
				{
					  $sql_del = "DELETE FROM customer_email_notification WHERE news_id=".$del_arr[$i];
					  $db->query($sql_del);
				
				}	
			}
			$alert = "Notification deleted Sucessfully";
		}
		include ('../includes/email_notify/list_emailnewsletter.php');
	

}
else if($_REQUEST['fpurpose']=='add')
{
	
	include("includes/email_notify/add_notification.php");
}
else if($_REQUEST['fpurpose']=='edit')
{	$ajax_return_function = 'ajax_return_contents';
	include "ajax/ajax.php";
	//include ('includes/email_notify/ajax/newsletter_ajax_functions.php');
	include_once("classes/fckeditor.php");
	$newsletter_id = $_REQUEST['newsletter_id'];
	include("includes/email_notify/edit_notification.php");
	
}
else if($_REQUEST['fpurpose']=='notify_settings') 
{
	$newsletter_id = $_REQUEST['newsletter_id'];
	include("includes/email_notify/notify_settings.php");
}
else if($_REQUEST['fpurpose']=='edit_notify_settings') 
{
	$newsletter_id = $_REQUEST['newsletter_id'];
	$fmode = 'edit';
	include("includes/email_notify/notify_settings.php");
}
else if($_REQUEST['fpurpose']=='insert')
{

	//Function to validate forms
	validate_forms();

	if($alert)
	{?>
		<br><font color="red"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>
	<?php	
	}
	else
	{
			$insert_array								= array(); 
			$insert_array['sites_site_id'] 				= $ecom_siteid;
			$insert_array['newsletter_template_id'] 	= $_REQUEST['template_name'];
			$insert_array['preview_title'] 				= addslashes($_REQUEST['newsletter_title']);
			$insert_array['preview_contents']			= addslashes($_REQUEST['newsletter_contents']);
			$insert_array['newsletter_createdate']		= date("Y-m-d H:m:s");
			$insert_array['newsletter_updatedate']		= date("Y-m-d H:m:s");
			$insert_array['email_status']				= 1;	
		$db->insert_from_array($insert_array, 'customer_email_notification');
		$insert_id = $db->insert_id();
		?>
<script language="javascript">
	window.location = "home.php?request=email_notify&fpurpose=notify_settings&newsletter_id=<?=$insert_id?>";
</script>
		   <? 
		exit;
	}
}
else if($_REQUEST['fpurpose']=='settings_insert')
{

	//Function to validate forms
	//validate_forms();

	if($alert)
	{?>
		<br><font color="red"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>
	<?php	
	}
	else
	{
			if($_REQUEST['week_rad']=='week') 
				$senttype  = $_REQUEST['sel_week'];
			else 
				$senttype  = $_REQUEST['sel_day'];	
			
			if(!in_array('0',$_REQUEST['newprod_category_id'])) 
			{
				if(is_array($_REQUEST['newprod_category_id']))	
					{
						foreach($_REQUEST['newprod_category_id'] AS $val)	
						{
							$newprodcategs .= $val.",";
						}							
					}
				$newprodcategs = substr($newprodcategs,0,strlen($newprodcategs)-1);
			} else {
				$newprodcategs = 0;
			}
			if(!in_array('0',$_REQUEST['disc_category_id'])) 
			{
				if(is_array($_REQUEST['disc_category_id']))	
					{
						foreach($_REQUEST['disc_category_id'] AS $val)	
						{
							$newdisccategs .= $val.",";
						}							
					}
				$newdisccategs = substr($newdisccategs,0,strlen($newdisccategs)-1);
			 } else {
			 	$newdisccategs = 0;
			 }			
			$seltypeselection = $_REQUEST['sel_prod_selection'];
			if($seltypeselection=='discount') {
				$discount_from        = $_REQUEST['discount_from'];
				$discount_to          = $_REQUEST['discount_to'];
			}
							
			$update_array							= array(); 
			$update_array['number_newproducts'] 	= addslashes($_REQUEST['txt_num_prod']);
			$update_array['number_discproducts'] 	= addslashes($_REQUEST['txt_num_discprod']);
			$update_array['set_senttype']			= $_REQUEST['week_rad'];
			
			$update_array['category_newproducts']	= $newprodcategs;
			$update_array['category_discproducts']	= $newdisccategs;
			$update_array['product_select_type']	= $seltypeselection;
			if($seltypeselection=='discount') {
				$update_array['discount_from']				= $_REQUEST['discount_from'];
				$update_array['discount_to']				= $_REQUEST['discount_to'];
			}	
			$update_array['email_status']			= 1;
			
			
			
			if($_REQUEST['week_rad']=='week') 
			{
				$update_array['week_day']			    = $senttype;
			}	else {
				$update_array['month_date']		        = $senttype;
			}	
		
		$db->update_from_array($update_array, 'customer_email_notification', array('news_id' => $_REQUEST['newsletter_id'] , 'sites_site_id' => $ecom_siteid));				
		?>
<script language="javascript">
	window.location = "home.php?request=email_notify&fpurpose=preview_email&newsletter_id=<?=$_REQUEST['newsletter_id']?>";
</script>
		   <? 
		exit;
	}
}
elseif($_REQUEST['fpurpose']=='settings_update') {
		
		$newsletter_id = $_REQUEST['newsletter_id'];
		
		if($_REQUEST['week_rad']=='week') 
				$senttype  = $_REQUEST['sel_week'];
		else 
				$senttype  = $_REQUEST['sel_day'];	
			
		if(is_array($_REQUEST['newprod_category_id']))	
				{
					foreach($_REQUEST['newprod_category_id'] AS $val)	
					{
						$newprodcategs .= $val.",";
					}							
				}
			$newprodcategs = substr($newprodcategs,0,strlen($newprodcategs)-1);

			if(is_array($_REQUEST['disc_category_id']))	
				{
					foreach($_REQUEST['disc_category_id'] AS $val)	
					{
						$newdisccategs .= $val.",";
					}							
				}
			$newdisccategs = substr($newdisccategs,0,strlen($newdisccategs)-1);
			
			$seltypeselection = $_REQUEST['sel_prod_selection'];
			if($seltypeselection=='discount') {
				$discount_from        = $_REQUEST['discount_from'];
				$discount_to          = $_REQUEST['discount_to'];
			}
							
			$update_array							= array(); 
			$update_array['number_newproducts'] 	= addslashes($_REQUEST['txt_num_prod']);
			$update_array['number_discproducts'] 	= addslashes($_REQUEST['txt_num_discprod']);
			$update_array['set_senttype']			= $_REQUEST['week_rad'];
			
			$update_array['category_newproducts']	= $newprodcategs;
			$update_array['category_discproducts']	= $newdisccategs;
			$update_array['product_select_type']	= $seltypeselection;
			if($seltypeselection=='discount') {
				$update_array['discount_from']				= $_REQUEST['discount_from'];
				$update_array['discount_to']				= $_REQUEST['discount_to'];
			}	
			$update_array['email_status']			= 1;
			
			if($_REQUEST['week_rad']=='week') 
			{
				$update_array['week_day']			    = $senttype;
			}	else {
				$update_array['month_date']		        = $senttype;
			}	
		
		$db->update_from_array($update_array, 'customer_email_notification', array('news_id' => $_REQUEST['newsletter_id'] , 'sites_site_id' => $ecom_siteid));				
		?>
<script language="javascript">
	window.location = "home.php?request=email_notify&fpurpose=preview_email&fmode=edit&newsletter_id=<?=$_REQUEST['newsletter_id']?>";
</script>
		   <? 
		exit;
}
else if($_REQUEST['fpurpose']=='preview_email') 
{
	$newsletter_id = $_REQUEST['newsletter_id'];
	include("includes/email_notify/notification_preview.php");
}
else if($_REQUEST['fpurpose']=='save_preview')
{

	//Function to validate forms
	validate_forms();

	if($alert)
	{?>
		<br><font color="red"><b>Error!!&nbsp;&nbsp;</b> <?php echo $alert?></font><br>
	<?php	
	}
	else
	{
			$update_array								= array();
			$update_array['newsletter_title'] 			= addslashes($_REQUEST['newsletter_title']);
			$update_array['newsletter_content']			= $_REQUEST['newsletter_contents'];
			$update_array['newsletter_updatedate']		= date("Y-m-d H:m:s");
			$update_array['preview_title'] 				= addslashes($_REQUEST['newsletter_title']);
		
		$db->update_from_array($update_array, 'customer_email_notification', array('news_id' => $_REQUEST['newsletter_id'] , 'sites_site_id' => $ecom_siteid));		
	//	$db->insert_from_array($insert_array, 'newsletters');
	//	$insert_id = $db->insert_id();
		
		if($_REQUEST['fpurptype']=='save') {
		$alert = "Saved Sucessfully";
			?>
<script language="javascript">
	window.location = "home.php?request=email_notify&alert=<?=$alert?>";
</script>
		   <? 
		} else {
	
		   $newsletter_id = $_REQUEST['newsletter_id'];
?>
<script language="javascript">
	window.location = "home.php?request=email_notify&newsletter_id=<?PHP echo $newsletter_id; ?>";
</script>
		   <?  //"<META HTTP-EQUIV=Refresh CONTENT='0; URL=home.php?request=newsletter&fpurpose=listnewsgroups&newsletter_id=$newsletter_id> ";
//		 $msg = "<META HTTP-EQUIV=Refresh CONTENT='0; URL=home.php?request=newsletter&fpurpose=listnewsgroups&newsletter_id=$newsletter_id> ";
			
		} 
		echo $msg;
		exit;
	}
}
else if($_REQUEST['fpurpose'] == 'update_newsletter') {  // for updating the adverts

	if($_REQUEST['newsletter_id'])
	{
		//Function to validate forms
		validate_forms();
		if (!$alert)
		{

		$update_array						= array();
			$update_array['sites_site_id'] 		= $ecom_siteid;
		    if($_REQUEST['template_name']) {
				  if($_REQUEST['template_name']=='none') {
				      $update_array['newsletter_template_id']		= 0;
				   } else {
				      $update_array['newsletter_template_id']		= $_REQUEST['template_name'];
				   }	  
			}
			$update_array['preview_title']			= addslashes($_REQUEST['newsletter_title']);
			$update_array['preview_contents']		= addslashes($_REQUEST['newsletter_contents']); 
			
		
			$update_array['newsletter_updatedate'] 	= date("Y-m-d H:m:s ");
			
		$db->update_from_array($update_array, 'customer_email_notification', array('news_id' => $_REQUEST['newsletter_id'] , 'sites_site_id' => $ecom_siteid));
		?>
		<script language="javascript">
			window.location = "home.php?request=email_notify&fpurpose=edit_notify_settings&newsletter_id=<?=$_REQUEST['newsletter_id']?>";
		</script>
		<?
		exit;
		/*$msg = "<META HTTP-EQUIV=Refresh CONTENT='0; URL=home.php?request=newsletter&fpurpose=prodnewsletter&newsletter_id=$_REQUEST[newsletter_id]'> ";
		echo $msg;
		
		
			?>
			<br><font color="red"><b>Newsletter Updated Successfully</b></font><br>
			<br /><a class="smalllink" href="home.php?request=newsletter&fpurpose=edit&newsletter_id=<?=$_REQUEST['newsletter_id']?>&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Newsletter Edit Page</a><br /><br />
			<a class="smalllink" href="home.php?request=newsletter&search_name=<?=$_REQUEST['pass_search_name']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>">Go Back to the Newsletter Listing page</a><br /><br />
			<a class="smalllink" href="home.php?request=newsletter&fpurpose=add&search_name=<?=$_REQUEST['search_name']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>&pass_search_name=<?=$_REQUEST['pass_search_name']?>&pass_sort_by=<?=$_REQUEST['pass_sort_by']?>&pass_sort_order=<?=$_REQUEST['pass_sort_order']?>&pass_records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pass_pg=<?=$_REQUEST['pass_pg']?>">Go Back to Newsletter Add page</a><br /><br />
			<?php
			*/
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
		<br><font color="red"><strong>Error!</strong> Invalid Advert Id</font><br />
		<br /><a class="smalllink" href="home.php?request=property&cbo_ptype=<?php echo $_REQUEST['cbo_ptype']?>&cbo_ptype=<?php echo $_REQUEST['cbo_ptype']?>&cbo_approvestatus=<?php echo $_REQUEST['cbo_approvestatus']?>&cbo_cat=<?php echo $_REQUEST['cbo_cat']?>&search_cname=<?=$_REQUEST['search_cname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to the Listing page</a><br /><br />
		
	<?php	
	} //// updating adverts ends



}

function validate_forms()
{
	global $alert,$db;
	if($_REQUEST['dont_save']!=1)
	{
		//Validations
		$alert = '';
		$fieldRequired 		= array($_REQUEST['newsletter_title'],$_REQUEST['newsletter_contents']);
		$fieldDescription 	= array('News Letter Title','News Letter Contents');
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		
		
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
	}
}



	
	function mailNewsletter($newsletter_id){
	global $ecom_siteid,$db;
	$sql_newsletter = "SELECT newsletter_title,newsletter_contents FROM newsletters WHERE newsletter_id = ".$newsletter_id." AND sites_site_id=".$ecom_siteid;
	$ret_newsletter = $db->query($sql_newsletter);
	return $newsletter_contents = $db->fetch_array($ret_newsletter);
	
	}
	


?>
