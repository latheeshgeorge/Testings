<?php
	/*#################################################################
	# Script Name 	: edit_site_reviews.php
	# Description 	: Page for editing site Reviews
	# Coded by 		: ANU
	# Created on	: 13-Aug-2007
	# Modified by	: ANU
	# Modified On	: 13-Aug-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Site Reviews';
$help_msg = get_help_messages('EDIT_SITE_REVIEWS_MESS1');
$review_id=($_REQUEST['review_id']?$_REQUEST['review_id']:$_REQUEST['checkbox'][0]);
$sql="SELECT review_id,review_date,review_author,review_author_email,review_details,review_rating,review_status,
			 review_approved_by,review_hide 
			 		FROM sites_reviews 
						WHERE sites_site_id=$ecom_siteid AND review_id=".$review_id."";
$res=$db->query($sql);
if($db->num_rows($res)==0)  { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
$row=$db->fetch_array($res);
?>	
<script language="javascript" type="text/javascript">

function valform(frm)
{
	fieldRequired = Array('review_author');
	fieldDescription = Array('Review Author');
	fieldEmail = Array('review_author_email');
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}

</script>
<form name='frmEditSiteReviews' action='home.php?request=site_reviews' enctype="multipart/form-data" method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=site_reviews&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&srch_author=<?=$_REQUEST['srch_author']?>&srch_review_status=<?=$_REQUEST['srch_review_status']?>&srch_review_startdate=<?=$_REQUEST['srch_review_startdate']?>&srch_review_enddate=<?=$_REQUEST['srch_review_enddate']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Site Reviews</a><span> Edit Site Reviews</span></div></td>
        </tr>
        <tr>
	  <td colspan="4" align="left" valign="middle" class="helpmsgtd_main">
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg);
	  ?>
	 </td>
	</tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
          <td colspan="4" align="center" valign="middle" class="tdcolorgray" >
			<div class="editarea_div" >
			<table width="100%">
			<tr>
			  <td width="12%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
			  <td width="28%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
			  <td width="14%" align="left" valign="middle" class="tdcolorgray">Review Rating </td>
			  <td width="46%" align="left" valign="middle" class="tdcolorgray"><?php
					$rating_arr = array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5');
					//echo generateselectbox('review_rating',$rating_arr,$row['review_rating'],'','handletype_change(this.value)');
					echo generateselectbox('review_rating',$rating_arr,$row['review_rating'],'','');
					?>
					&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_REVIEW_RATING')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
			 <tr>
			   <td align="left" valign="middle" class="tdcolorgray" >Review Date  </td>
			   <td align="left" valign="middle" class="tdcolorgray">
			   <?php $exp_review_date1=explode(" ",$row['review_date']);
			   $exp_review_date=explode("-",$exp_review_date1[0]);// to remove the time part
			 //  print_r($exp_review_date);
				  $val_review_date=$exp_review_date[2]."-".$exp_review_date[1]."-".$exp_review_date[0];
				  echo $val_review_date."&nbsp&nbsp;".$exp_review_date1[1];
				 ?>
			   <!--<input name="review_date" type="text" id="review_date" value="<?php //$val_review_date?>" /> <a href="javascript:show_calendar('frmEditSiteReviews.review_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>--></td>
			   <td align="left" valign="middle" class="tdcolorgray" >Hidden</td>
			   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="review_hide" value="1" <? if($row['review_hide']==1) echo "checked";?> />
	Yes
	  <input type="radio" name="review_hide"  value="0" <? if($row['review_hide']==0) echo "checked";?> />
	No&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_REVIEW_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
			 <tr  >
			   <td align="left" valign="middle" class="tdcolorgray" >Review Author  <span class="redtext">*</span> </td>
			   <td align="left" valign="middle" class="tdcolorgray"><input name="review_author" type="text" id="review_author" value="<?=$row['review_author']?>" />
			   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_REVIEW_AUTH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			   <td align="left" valign="middle" class="tdcolorgray">Review Status</td>
			   <td align="left" valign="middle" class="tdcolorgray"><?php
					$type_arr = array('NEW'=>'NEW','PENDING'=>'PENDING','APPROVED'=>'APPROVED');
					if($row['review_status'] !='APPROVED'){
					$sel_review_status = 'PENDING';
					$update_array							= array();
					$update_array['sites_site_id'] 			= $ecom_siteid;
					$update_array['review_status'] 			= 'PENDING';
					$db->update_from_array($update_array, 'sites_reviews', array('review_id' =>$review_id, 'sites_site_id' => $ecom_siteid));
					//echo generateselectbox('review_status',$type_arr,$sel_review_status,'','handletype_change(this.value)');
					echo generateselectbox('review_status',$type_arr,$sel_review_status,'','');
					?>
					&nbsp;
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_REVIEW_STATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
					<?
					}else{
				echo $row['review_status'];
				?>
				&nbsp;
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_REVIEW_STATUS_APPROVED')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
	
				<?
					}
					?>   </td> </tr>
			 <tr  >
			   <td align="left" valign="middle" class="tdcolorgray" >Author email </td>
			   <td align="left" valign="middle" class="tdcolorgray"><input name="review_author_email" type="text" id="review_author_email" value="<?=$row['review_author_email']?>" />
			   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_REVIEW_EMAIL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			 <?php if($row['review_status'] == 'APPROVED') {?> 
			  <td align="left" valign="middle" class="tdcolorgray"> Review Approved By</td>
			   <td align="left" valign="middle" class="tdcolorgray"><?php 
		$sql = "SELECT  user_fname,user_lname FROM sites_users_7584 WHERE user_id=".$row['review_approved_by'];
		$res_admin 			= $db->query($sql);
		$fetch_arr_admin 	= $db->fetch_array($res_admin);
		$user_fname			= stripslashes($fetch_arr_admin['user_fname']);
		$user_lname			= stripslashes($fetch_arr_admin['user_lname']);
		echo $user_fname."&nbsp;".$user_lname;?></td>
						   
			   
	<? }?>
		</tr>
			 <tr  >
			   <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
			   <td colspan="3" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		</tr>
			 <tr  >
			   <td align="left" valign="top" class="tdcolorgray" >Review Details </td>
			   <td colspan="3" align="left" valign="middle" class="tdcolorgray"><?php
		   			//$editor_elements = "review_details";
					//include_once("js/tinymce.php");
					/*$editor = new FCKeditor('review_details') ;
					$editor->BasePath 	= '/console/js/FCKeditor/';
					$editor->Width 		= '650';
					$editor->Height 	= '300';
					$editor->ToolbarSet = 'BshopWithImages';
					$editor->Value 		= stripslashes($row['review_details']);
					$editor->Create();*/
					?>
					<textarea style="height:400px; width:650px" id="review_details" name="review_details"><?=stripslashes($row['review_details'])?></textarea>
					</td>
		</tr>
		</table>
		</div>
		<tr>
          <td align="right" valign="middle" class="tdcolorgray" colspan="2" >
		  <div class="editarea_div">
		  <table width="100%">
		  <tr>
		  	<td align="right" valign="middle">
			  <input type="hidden" name="review_id" id="review_id" value="<?=$review_id?>" />
			  <input type="hidden" name="srch_author" id="srch_author" value="<?=$_REQUEST['srch_author']?>" />
			  <input type="hidden" name="srch_review_status" id="srch_review_status" value="<?=$_REQUEST['srch_review_status']?>" />
			  <input type="hidden" name="srch_review_startdate" id="srch_review_startdate" value="<?=$_REQUEST['srch_review_startdate']?>" />
			  <input type="hidden" name="srch_review_enddate" id="srch_review_enddate" value="<?=$_REQUEST['srch_review_enddate']?>" />
			  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			  <input type="hidden" name="fpurpose" id="fpurpose" value="update_review" />
			  <input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
			  <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
			  <input name="Submit" type="submit" class="red" value="Save" />
			</td>
		</tr>
		</table>
		</div>
		</td>
        </tr>
      </table>
</form>	  

