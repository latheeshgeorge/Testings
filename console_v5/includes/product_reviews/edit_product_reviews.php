<?php
	/*#################################################################
	# Script Name 	: edit_product_reviews.php
	# Description 	: Page for editing product Reviews
	# Coded by 		: ANU
	# Created on	: 13-Aug-2007
	# Modified by	: ANU
	# Modified On	: 13-Aug-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Product Reviews';
//$help_msg = 'This section helps in editing the Product Reviews';
$help_msg  = get_help_messages('EDIT_PROD_REVIEW_RATING_MESS1');
$review_id=($_REQUEST['review_id']?$_REQUEST['review_id']:$_REQUEST['checkbox'][0]);
$sql="SELECT pr.review_id,pr.products_product_id,pr.review_date,pr.review_author,pr.review_author_email,pr.review_details,pr.review_rating,pr.review_status,pr.review_approved_by,pr.review_hide,p.product_name FROM product_reviews pr,products p WHERE pr.sites_site_id=$ecom_siteid AND pr.review_id=".$review_id." AND p.product_id=pr.products_product_id";
$res=$db->query($sql);
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
<form name='frmEditProductReviews' action='home.php?request=product_reviews' enctype="multipart/form-data" method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=product_reviews&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&srch_productname=<?=$_REQUEST['srch_productname']?>&srch_author=<?=$_REQUEST['srch_author']?>&srch_review_startdate=<?=$_REQUEST['srch_review_startdate']?>&srch_review_enddate=<?=$_REQUEST['srch_review_enddate']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&srch_review_status=<?=$_REQUEST['srch_review_status']?>">List Product Reviews</a><span>Edit Product Reviews 
		  </span></div></td>
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
		  <div class="editarea_div">
		  <table width="100%">
		  <tr>
          <td width="12%" align="left" valign="middle" class="tdcolorgray" >Product Name   <span class="redtext">*</span> </td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray"> <?=stripslashes($row['product_name'])?> </td>
          <td width="17%" align="left" valign="middle" class="tdcolorgray">Review Rating </td>
          <td width="43%" align="left" valign="middle" class="tdcolorgray">
		  <?php
		     	$rating_arr = array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5');
				echo generateselectbox('review_rating',$rating_arr,$row['review_rating'],'','handletype_change(this.value)');
				?>&nbsp;
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_REVIEW_RATING')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		 <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Review Date <br /><br /> Review Time   </td>
		   <td align="left" valign="middle" class="tdcolorgray">
		   
		   <?php $exp_review_date1=explode(" ",$row['review_date']);
		   $exp_review_date=explode("-",$exp_review_date1[0]);// to remove the time part
		 //  print_r($exp_review_date);
		      $val_review_date=$exp_review_date[2]."-".$exp_review_date[1]."-".$exp_review_date[0];
			 //echo $val_review_date."&nbsp&nbsp;".$exp_review_date1[1];
			  $time_explode = explode(':',$exp_review_date1[1]);
			 ?>
			 <input type="text" name="review_date" id="review_date" value="<?php echo $val_review_date?>" readonly="true" />
			 <a href="javascript:show_calendar('frmEditProductReviews.review_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>
		  	 <br /><br />
			 Hour <select name="review_hour">
			 <?php
			 	for($i=0;$i<=23;$i++)
				{
					if($i<10)
						$d = '0'.$i;
					else
						$d = $i;
			 ?>
			 	<option value="<?php echo $d?>" <?php echo ($d==$time_explode[0])?'selected':''?>><?php echo $d?></option>
			<?php
				}
			?>	
			 </select>
			 Min <select name="review_minute">
			 <?php
			 	for($i=0;$i<=59;$i++)
				{
					if($i<10)
						$d = '0'.$i;
					else
						$d = $i;
			 ?>
			 	<option value="<?php echo $d?>" <?php echo ($d==$time_explode[1])?'selected':''?>><?php echo $d?></option>
			<?php
				}
			?>	
			 </select>
			 Sec <select name="review_second">
			 <?php
			 	for($i=0;$i<=59;$i++)
				{
					if($i<10)
						$d = '0'.$i;
					else
						$d = $i;
			 ?>
			 	<option value="<?php echo $d?>" <?php echo ($d==$time_explode[2])?'selected':''?> ><?php echo $d?></option>
			<?php
				}
			?>	
			 </select>	
		   </td>
		   <td align="left" valign="middle" class="tdcolorgray" >Hidden</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="review_hide" value="1" <? if($row['review_hide']==1) echo "checked";?> />
Yes
  <input type="radio" name="review_hide"  value="0" <? if($row['review_hide']==0) echo "checked";?> />
No&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_REVIEW_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
		 <tr  >
		   <td align="left" valign="middle" class="tdcolorgray" >Review Author  <span class="redtext">*</span> </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input name="review_author" type="text" id="review_author" value="<?=$row['review_author']?>" />
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_REVIEW_AUTHOR')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   <td align="left" valign="middle" class="tdcolorgray">Review Status</td>
		   <td align="left" valign="middle" class="tdcolorgray"><?php
		     	$type_arr = array('NEW'=>'NEW','PENDING'=>'PENDING','APPROVED'=>'APPROVED');
				if($row['review_status'] !='APPROVED'){
				$sel_review_status = 'PENDING';
				$update_array							= array();
				$update_array['sites_site_id'] 			= $ecom_siteid;
				$update_array['review_status'] 			= 'PENDING';
				$db->update_from_array($update_array, 'product_reviews', array('review_id' =>$review_id, 'sites_site_id' => $ecom_siteid));
				echo generateselectbox('review_status',$type_arr,$sel_review_status,'','handletype_change(this.value)');
                ?>
				&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_REVIEW_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				<?
				}else{
			echo $row['review_status'];?>
			<input type="hidden" name="review_status" id="review_status" value="<?=$row['review_status']?>" />
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_REVIEW_CHSTATUS_APPROVED')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<?
				}
				?>
				</td>
    </tr>
		 <tr  >
		   <td align="left" valign="middle" class="tdcolorgray" >Author email </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input name="review_author_email" type="text" id="review_author_email" value="<?=$row['review_author_email']?>" />
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_REVIEW_EMAIL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
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
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray">
		   <?php
					//$editor = new FCKeditor('review_details') ;
//					$editor->BasePath 	= '/console/js/FCKeditor/';
//					$editor->Width 		= '650';
//					$editor->Height 	= '300';
//					$editor->ToolbarSet = 'BshopWithImages';
//					$editor->Value 		= stripslashes($row['review_details']);
//					$editor->Create();
				?>
				<textarea style="height:400px; width:650px" id="review_details" name="review_details"><?=stripslashes($row['review_details'])?></textarea>
			  </td>
    </tr>
		  </table>
		  </div>
		  </td>
        </tr>
        
		<tr>
          <td align="right" valign="middle" class="tdcolorgray" colspan="2" >
		  <div class="editarea_div">
		  <table width="100%">
		  <tr>
		  	<td align="right" valign="middle">
			  <input type="hidden" name="review_id" id="review_id" value="<?=$review_id?>" />
			  <input type="hidden" name="srch_productname" id="srch_productname" value="<?=$_REQUEST['srch_productname']?>" />
			  <input type="hidden" name="srch_author" id="srch_author" value="<?=$_REQUEST['srch_author']?>" />
			  <input type="hidden" name="srch_review_startdate" id="srch_review_startdate" value="<?=$_REQUEST['srch_review_startdate']?>" />
			  <input type="hidden" name="srch_review_enddate" id="srch_review_enddate" value="<?=$_REQUEST['srch_review_enddate']?>" />
			  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			  <input type="hidden" name="srch_review_status" id="srch_review_status" value="<?=$_REQUEST['srch_review_status']?>" />
			  <input type="hidden" name="fpurpose" id="fpurpose" value="update_review" />
			  <input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
			  <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
			  <input name="Submit" type="submit" class="red" value="Save" />
			</td>
			</tr>
			</table>
			</div></td>
		</tr>
      </table>
</form>	  

