<?php
/*############################################################################
	# Script Name 	: sitereviewHtml.php
	# Description 	: Page which holds the display logic for site reviews
	# Coded by 		: ANU
	# Created on	: 04-Jan-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class sitereview_Html
	{
		// Defining function to show the site review
		function Show_Sitereview()
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$vImage,$Settings_arr,$alert,$short,$long,$medium;
			
			/*$sql_order_limit = "SELECT sitereview_ord_fld,sitereview_ord_orderby,sitereview_maxcntperpage 
								FROM  general_settings_sites_common 
								WHERE sites_site_id = $ecom_siteid  
								";
			$ret_order_limit	= $db->query($sql_order_limit);*/
			list($review_order_field,$review_order_by,$site_review_per_page)     = array($Settings_arr['sitereview_ord_fld'],$Settings_arr['sitereview_ord_orderby'],$Settings_arr['sitereview_maxcntperpage']);
			$pg_variable	= 'review_pg';
			$sql_tot_sirereviews = "SELECT count(review_id)
						FROM  
							sites_reviews 
						WHERE  
							sites_site_id = $ecom_siteid  
							AND review_status = 'APPROVED'  
							AND review_hide=0";
			$ret_tot_sirereviews	= $db->query($sql_tot_sirereviews);
			list($tot_cnt) 	        = $db->fetch_array($ret_tot_sirereviews); 	
			$pg_variable	= 'sitereview_pg';
			//$start_arr 		        = prepare_paging($_REQUEST[$pg_variable],$site_review_per_page);
			$start_var 		= prepare_paging($_REQUEST['sitereview_pg'],$site_review_per_page,$tot_cnt);
			$Limitsitereviews		= " LIMIT ".$start_var['startrec'].", ".$site_review_per_page;
			
			$sql_sitereview         = "SELECT review_id,DATE_FORMAT(review_date,'%e-%b-%Y : %r') as reviewed_on,
											review_author,review_rating,review_details 
										FROM  
											 sites_reviews 
										WHERE  
											sites_site_id = $ecom_siteid  
											AND review_status = 'APPROVED'  
											AND review_hide=0 
									  	ORDER BY  
											$review_order_field  $review_order_by  
										$Limitsitereviews";
				$ret_sitereview = $db->query($sql_sitereview);	
									
		?>
			<form method="post" action="" name'frm_sitereview' id="frm_sitereview" class="frm_cls" onsubmit="return validate_sitereview(this)">
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?=$Captions_arr['SITE_REVIEWS']['SITE_REVIEW_TREEMENU_TITLE']?></div>
		<table width="100%" border="0" cellpadding="0" cellspacing="3"  class="middle_fav_table">
			<? if($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_READREVIEW_TITLE']) {?>
			<tr>
			<td colspan="2" class="message_header"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ADDREVIEW_TITLE'])?></td>
			</tr>	<?php } ?>	
			<?php if($_REQUEST['alert1']==1 && !$alert){ 
			?>	<tr>
				<td colspan="2" class="message" align="center">
				<?php 
						  echo stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ADDED_SUCCESSFULLY']);
						 
				?>
				</td>
			</tr>
		<?php }
		if($alert)
		{
		?>
		<tr>
				<td colspan="2" class="errormsg" align="center">
		<?
		 if($Captions_arr['SITE_REVIEWS'][$alert]){
						  		echo "Error !! ". stripslash_normal($Captions_arr['SITE_REVIEWS'][$alert]);
						  }else{
						  		echo  "Error !! ". $alert;
						  }
		?>
			</td>
		</tr>
		<?				  
		} 
		?>
			<tr>
			<td width="41%" class="addreivewconent"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_AUTHOR'])?> <span class="redtext">*</span></td>
			<td width="59%" align="left" valign="middle"><input name="review_author" type="text" class="addreivewinput" id="review_author" size="39" maxlength="<?=$short?>" /></td>
			</tr>
			<tr>
			<td width="41%" class="addreivewconent"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_AUTHOR_EMAIL'])?> <span class="redtext">*</span></td>
			<td width="59%" align="left" valign="middle"><input name="review_author_email" type="text" class="addreivewinput" id="review_author_email" size="39" maxlength="<?=$medium?>"/></td>
			</tr>
			<tr>
			<td class="addreivewconent"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_REVIEWTEXT'])?> <span class="redtext">*</span></td>
			<td align="left" valign="middle"><textarea name="review_details" cols="33" rows="4" class="addreivewinput" id="review_details"></textarea></td>
			</tr>
			<tr>
			<td class="addreivewconent"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_RATING'])?></td>
			<td align="left" valign="middle"><label>
			<select name="review_rating" class="addreivewinput" id="review_rating">
			<? for($i=1;$i<=5;$i++)
			{
			?>
			<option value="<?=$i?>"><?=$i?></option>
			<?
			}?>
			</select>
			</label></td>
			</tr>
			<tr>
			<td>&nbsp;</td>
			<td align="left" valign="middle">&nbsp;</td>
			</tr>
			<?	if($Settings_arr['imageverification_req_sitereview']) {?>

			<tr>
			<td class="addreivewconent">&nbsp;</td>
			<td align="left" valign="middle" class="addreivewconentred"><img src="<?php url_verification_image('includes/vimg.php?size=4&pass_vname=sitereview_Vimg')?>" border="0" alt="Image Verification"/>
			</td>
			</tr>
			<tr>
			<td class="addreivewconent"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_REVIEWCODE'])?></td>
			<td align="left" valign="middle" class="addreivewconentred">
			
			<?php 
				// showing the textbox to enter the image verification code
				$vImage->showCodBox(1,'sitereview_Vimg','class="inputA_imgver"'); 
			?>
			</td>
			</tr>
			<? }?>
			<tr>
			<td class="addreivewconent"><input type="hidden" name="action_purpose" id="action_purpose" value="add_site_review"></td>
			<td align="left" valign="middle"><input name="sitereview_Submit" type="submit" class="buttongray" id="sitereview_Submit" value="<?=$Captions_arr['SITE_REVIEWS']['SITE_REVIEW_SAVE_BUTTON']?>" /></td>
			</tr>
			</table>
		<?PHP 	if ($db->num_rows($ret_sitereview)){ ?>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="middle_fav_table">
	
		<?php if($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_READREVIEW_TITLE']) {?>
		<tr>
          <td colspan="2" class="message_header"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_READREVIEW_TITLE'])?></td>
        </tr>
		<?php } ?>
		
		</table>
	<?php 
	
			while($row_sitereview = $db->fetch_array($ret_sitereview)){
			$rating = $row_sitereview['review_rating'];
		?>
	
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="readreivewtable">
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td width="35%" align="left" valign="top"  class="readreivewicon" ><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['READ_SITE_REVIEW_AUTHOR'])?></td>
          <td width="65%" align="left" valign="top" class="readreivewname"><?=$row_sitereview['review_author']?> ( <?=$row_sitereview['reviewed_on']?>)</td>
        </tr>
        <tr>
          <td align="left" valign="top" class="readreivewcontent"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['READ_SITE_REVIEW_REVIEW'])?></td>
          <td align="left" valign="top"><?=$row_sitereview['review_details']?></td>
        </tr>
        <tr>
          <td align="left" valign="top" class="readreivewcontent"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['READ_SITE_REVIEW_RATING'])?></td>
          <td align="left" valign="top">
		  
		  <?php 
		
		  for($i=1;$i<=$rating;$i++){
		 
		  ?>
		  <img src="<? url_site_image('reviewstar_on.gif',0)?>" width="9" height="10" />
		  <? } for($k=$rating+1;$k<=5;$k++){?><img src="<? url_site_image('reviewstar_off.gif',0)?>" width="9" height="10" /><? }?></td>
        </tr></table>
		<?php 
			}
		
		if ($tot_cnt>0){
		?>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="readreivewtable">
		<tr>
			<td colspan="2"  align="center" class="pagingcontainertdA">
			<?php 
			//	$path = '';
				//$page_containerclass	= 'pagenavcontainer'; 	// Paging container class
			///	$page_navurlclass 		= 'pagenavul';			// Paging url class
			//	$page_currentclass		= 'pagenav_current';	// Paging current page class
				//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$site_review_per_page,$pg_variable,'')
				$path = url_link('sitereview.html',1);
				$query_string='';
				$query_string .= '';
				paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Site reviews',$pageclass_arr); 

			?>	
			
			</td>
		</tr>
			
      </table><?php
			}
		}	
			?>
			</form>
			
			<script language="javascript">
		/* Function to validate the site review */
function validate_sitereview(frm)
{
	fieldRequired 		= Array('review_author','review_author_email','review_details');
	fieldDescription 	= Array('<?=$Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ALERT_AUTHOR']?>','<?=$Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ALERT_EMAIL']?>','<?=$Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ALERT_DETAILS']?>');
	fieldEmail 			= Array('review_author_email');
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	fieldSpecChars 		= Array('review_author');
	fieldCharDesc       = Array('Author Name');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)){
	<?php if($Settings_arr['imageverification_req_sitereview']){ // code for validating the image verification- needs only if it is enabled
				echo "if(frm.sitereview_Vimg.value==''){
					alert('Enter-".$Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ALERT_VERIFICATION_CODE']."');
					frm.sitereview_Vimg.focus();
					return false;
				}else{
					return true;
				}";
			}?>
		return true;
		}
	else
	{
		return false;
	}
}
</script>
		<?php	
		}
	};	
?>