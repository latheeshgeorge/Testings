<?php
if($_REQUEST['fpurpose'] == '') {
	include("includes/console_news/list_news.php");
}
else if($_REQUEST['fpurpose'] == 'add') {
	include("includes/console_news/add_news.php");
}
if($_REQUEST['fpurpose'] == 'edit') {
	include("includes/console_news/edit_news.php");
}
else if($_REQUEST['fpurpose'] == 'update') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired 		= array();
		$fieldDescription 	= array();
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		//Check whether the code existing or not
		if(!$alert) {
			$update_array = array();
			$update_array['news_add_date']		 	= 'now()';
			$update_array['sites_site_id']		 	= $_REQUEST['sites_id'];
			$update_array['news_title']				 = add_slash($_REQUEST['news_title']);
			$update_array['news_text']				 = add_slash($_REQUEST['news_text'],false);
			$update_array['news_priority']			= add_slash($_REQUEST['news_priority']);
			$update_array['news_hide']				 = add_slash($_REQUEST['news_hide']);
			$update_array['news_activeperiod']		 = ($_REQUEST['news_activeperiod'])?1:0;
			/*$exp_news_displaystartdate=explode("-",$_REQUEST['news_displaystartdate']);
			$val_news_displaystartdate=$exp_news_displaystartdate[2]."-".$exp_news_displaystartdate[1]."-".$exp_news_displaystartdate[0];
			$exp_news_displayenddate=explode("-",$_REQUEST['news_displayenddate']);
			$val_news_displayenddate=$exp_news_displayenddate[2]."-".$exp_news_displayenddate[1]."-".$exp_news_displayenddate[0];*/
			$update_array['news_fromdate']			= $_REQUEST['news_displaystartdate'];
			$update_array['news_todate']			= $_REQUEST['news_displayenddate'];
			$db->update_from_array($update_array, 'console_news', 'news_id', $_REQUEST['news_id']);
			$alert = '<center><font color="red"><b>Successfully Updated</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=console_news&news_title_search=<?=$_REQUEST['news_title_search']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to News Listing page</a><br /><br />
			<a href="home.php?request=console_news&fpurpose=edit&news_id=<?=$_REQUEST['news_id']?>&news_title_search=<?=$_REQUEST['news_title_search']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this News</a>
			<br />
			<br />
			<a href="home.php?request=console_news&fpurpose=add&news_title_search=<?=$_REQUEST['news_title_search']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New News</a>
			</center>
			<?php
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/console_news/edit_news.php");
		}
		
	}
}	
else if($_REQUEST['fpurpose'] == 'add_news') {
	if($_REQUEST['Submit'])
	{
		$alert = '';
		$fieldRequired 		= array();
		$fieldDescription 	= array();
		$fieldEmail 		= array();
		$fieldConfirm 		= array();
		$fieldConfirmDesc 	= array();
		$fieldNumeric 		= array();
		$fieldNumericDesc 	= array();
		serverside_validation($fieldRequired, $fieldDescription, $fieldEmail, $fieldConfirm, $fieldConfirmDesc, $fieldNumeric, $fieldNumericDesc);
		//Check whether the code existing or not
		if(!$alert) {
			$insert_array = array();
			$insert_array['news_add_date']		 	= 'now()';
			$insert_array['sites_site_id']		 	= $_REQUEST['sites_id'];
			$insert_array['news_title']				 = add_slash($_REQUEST['news_title']);
			$insert_array['news_text']				 = add_slash($_REQUEST['news_text'],false);
			$insert_array['news_priority']			= add_slash($_REQUEST['news_priority']);
			$insert_array['news_hide']				 = add_slash($_REQUEST['news_hide']);
			$insert_array['news_activeperiod']		 = ($_REQUEST['news_activeperiod'])?1:0;
			$insert_array['news_fromdate']			= $_REQUEST['news_displaystartdate'];
			$insert_array['news_todate']			= $_REQUEST['news_displayenddate'];
			$db->insert_from_array($insert_array, 'console_news');
			$insert_id = $db->insert_id();
			$alert = '<center><font color="red"><b>Successfully Added</b></font><br>';
			echo $alert;
			?>
			<br /><a href="home.php?request=console_news&news_title_search=<?=$_REQUEST['news_title_search']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to News Listing page</a><br /><br />
			<a href="home.php?request=console_news&fpurpose=edit&news_id=<?=$insert_id?>&news_title_search=<?=$_REQUEST['news_title_search']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>">Go Back to Edit this News</a>
			<br />
			<br />
			<a href="home.php?request=console_news&fpurpose=add&news_title_search=<?=$_REQUEST['news_title_search']?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">Add a New News</a>
			</center>
			<?php
		} else {
			$alert = '<center><font color="red">Error! '.$alert;
			$alert .= '</font></center>';
			echo $alert;
			?>
			<br />
			<?php
			include("includes/console_news/add_news.php");
		}
		
	}
}	
else if($_REQUEST['fpurpose'] == 'delete') {
$sql_del = "DELETE FROM console_news WHERE news_id =".$_REQUEST['news_id'];
				 		$db->query($sql_del);
						$error_msg = 'News Deleted Successfully';
include("includes/console_news/list_news.php");
}
else if($_REQUEST['fpurpose'] == 'save_hidden')
{
foreach($_REQUEST['hide'] as $key => $val){
	$update_array = array();
	$update_array['news_hide'] 			= $_REQUEST['hide'][$key];
	$db->update_from_array($update_array, 'console_news', 'news_id', $key);
}
include("includes/console_news/list_news.php");
}
?>