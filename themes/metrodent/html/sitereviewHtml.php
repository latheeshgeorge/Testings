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
			list($review_order_field,$review_order_by,$site_review_per_page)     = array($Settings_arr['sitereview_ord_fld'],$Settings_arr['sitereview_ord_orderby'],$Settings_arr['sitereview_maxcntperpage']);
			$sql_tot_sirereviews = "SELECT count(review_id)
						FROM  
							sites_reviews 
						WHERE  
							sites_site_id = $ecom_siteid  
							AND review_status = 'APPROVED'  
							AND review_hide=0";
			$ret_tot_sirereviews	= $db->query($sql_tot_sirereviews);
			list($tot_cnt) 	        = $db->fetch_array($ret_tot_sirereviews); 	
			$pg_variable	= 'review_pg';
			//$start_arr 		        = prepare_paging($_REQUEST[$pg_variable],$site_review_per_page);
			$start_var 		= prepare_paging($_REQUEST['review_pg'],$site_review_per_page,$tot_cnt);
			$Limitsitereviews		= " LIMIT ".$start_var['startrec'].", ".$site_review_per_page;
			$sql_sitereview         = "SELECT review_id,DATE_FORMAT(review_date,'%e-%b-%Y @ %r') as reviewed_on,
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

				 <div class="treemenu">
				  <ul>
					<li><a href="<? url_link('');?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
					<li> <?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_TREEMENU_TITLE'])?> </li>
				  </ul>
				  <a name="reviewtop"/>
				</div>
				<?php
				 if($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_READREVIEW_TITLE']) 
				{
				?>	
				<div class="inner_header"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_TREEMENU_TITLE'])?></div>
				<?
				}
				?>
				<?php if($_REQUEST['alert1']==1 && !$alert){ ?>
				<div class="inner_con" >
					<div class="inner_top"></div>
						<div class="inner_middle">
							<table width="100%" border="0" cellpadding="0" cellspacing="0" class="regi_table">
								
								<tr>
								<td colspan="2" class="errormsg" align="center">
								<?php 
								  echo stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ADDED_SUCCESSFULLY']);
								?>
								</td>
								</tr>
								
							</table>
						</div>
					<div class="inner_bottom"></div>
				</div>
				<?php } ?>
				 <div class="inner_con" >
				<div class="inner_top"></div>
				<div class="inner_middle">
				  <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" class="reviews">
					<tbody>
					  <tr>
						<td colspan="6" align="left" valign="middle" class="reviews_font"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ADDREVIEW_TOPTEXT'])?></td>
						</tr>
					  <tr>
						<td colspan="6" align="left" valign="middle" class="reviews_font"><span class="reg_header"><span><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ADDREVIEW_TITLE'])?></span></span></td>
						</tr>
					  <tr>
						<td class="reviews_fontbold" valign="middle" width="16%" align="left"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_AUTHOR'])?></td>
						<td width="15%" align="left" valign="middle" class="reviews_fontbold">
						<input name="review_author" type="text" class="reviewsinput" id="review_author" size="25" maxlength="<?=$short?>" />
						</td>
						<td align="left" valign="middle" class="reviews_fontbold"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_AUTHOR_EMAIL'])?>
						 <input name="review_author_email" type="text" class="reviewsinput" id="review_author_email" size="25" maxlength="<?=$medium?>"/>
						  </td>
						<td colspan="2" align="left" valign="middle" class="reviews_fontbold"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_RATING'])?> </td>
						<td width="9%" align="left" valign="middle" class="reviews_fontbold">
							<select name="review_rating" id="review_rating">
							<? for($i=1;$i<=5;$i++)
							{
							?>
							<option value="<?=$i?>"><?=$i?></option>
							<?
							}?>
							</select>
						</td>
					  </tr>
					  <tr>
						<td class="reviews_fontbold" valign="middle" align="left"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_REVIEWTEXT'])?></td>
						<td colspan="2" align="left" valign="middle" class="reviews_fontbold"><label>
						<textarea name="review_details" class="reviewstxt" id="review_details"></textarea>
						</label></td>
						<td colspan="3">
							<?	 if($Settings_arr['imageverification_req_sitereview']) {?>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td  align="left" valign="middle" class="reviews_fontbold">
								<?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_REVIEWCODE'])?></td>
							</tr>
							<tr>	
									<td align="left" valign="middle" class="reviews_fontbold">
									<img src="<?php url_verification_image('includes/vimg.php?size=4&pass_vname=sitereview_Vimg&amp;bg=143 186 2')?>" border="0" alt="Image Verification"/>
									</td>
									<td align="left" valign="middle" class="reviews_fontbold">
									<?php 
									// showing the textbox to enter the image verification code
									$vImage->showCodBox(1,'sitereview_Vimg','class="img_input"'); 
									?></td>
							</tr>
						</table>
						<? 
						}
						?>
						</td>
						</tr>
					  <tr>
						<td colspan="4" align="left" valign="middle" class="reviews_fontbold">
						<input type="hidden" name="action_purpose" id="action_purpose" value="add_site_review">
										 <label></label></td>
						<td colspan="2" align="right" valign="middle" class="reviews_fontbold">
						<input name="sitereview_Submit" type="submit" class="inner_btn_red" id="sitereview_Submit" value="<?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_SAVE_BUTTON'])?>" />
						</td>
						</tr>
					</tbody>
				  </table>
				</div>
				<div class="inner_bottom"></div>
				</div>  
				<?
				if($db->num_rows($ret_sitereview)>0)
				{
				?> 
				<div class="inner_con" >
					<div class="inner_top"></div>  
					<div class="inner_middle">  
						<div class="pagingcontainertd_rw" >
							<div class="pro_nav_pages" align="right"><?php echo paging_show_totalcount($tot_cnt,'Site review(s)',$start_var['pg'],$start_var['pages'])?></div>
								<div class="pro_nav_links" align="right"><a name='read'>&nbsp;</a>
								<?php 
								$pg_variable	= 'review_pg';
								if ($tot_cnt>0)
								{
									$path = url_link('sitereview.html',1);
									$pageclass_arr['container'] = 'pagenavcontainer';
									$pageclass_arr['navvul']	= 'pagenavul';
									$pageclass_arr['current']	= 'pagenav_current';
									
									$query_string = "";
									paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Site reviews',$pageclass_arr,0); 	
								}
								?>
								</div>
								</div>
						 </div>	
					<div class="inner_bottom"></div>
				</div>	 
				 <div class="inner_con" >
				<div class="inner_top"></div>
				<div class="inner_middle">
				<table class="regitable" width="100%" border="0" cellpadding="0" cellspacing="0">
					<tbody>
						<?php if($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_READREVIEW_TITLE']) {?>
						<tr>
							<td colspan="2" class="reviews_fontbold" align="left"><span class="reg_header"><span><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_READREVIEW_TITLE'])?></span></span></td>
						</tr>
						<?php 
						}	
						while($row_sitereview = $db->fetch_array($ret_sitereview))
						{
							$rating 	= $row_sitereview['review_rating'];
							$date_arr 	= array();
							$date_arr 	= explode('@',$row_sitereview['reviewed_on']);
							?>
							<tr>
								 <td class="reviewstxt_nametd" valign="middle" width="43%" align="left"><?=stripslashes($row_sitereview['review_author'])?> 
									 <span class="reviewstxt_red"><?=$date_arr[1]?></span>
									 <?=$date_arr[0]?>
								 </td>
							<td width="57%" align="left" valign="middle" class="reviewstxt_txttd">
								<?=stripslashes($row_sitereview['review_details'])?> <br/>
								<?php 
								for($i=1;$i<=$rating;$i++){
								?>
								<img src="<? url_site_image('star-green.gif',0)?>"  />
								<? } for($k=$rating+1;$k<=5;$k++){?><img src="<? url_site_image('star-white.gif',0)?>"  /><? }?>
							</td>
							</tr>
							<?
						}
						?>
						 <tr>
						<td colspan="2" align="right" valign="middle" class="reviews_fontbold"><a href="#reviewtop">Top</a></td>
						</tr>
					</tbody>
				</table>
				</div>
				<div class="inner_bottom"></div>
				</div>
				<?
				}
				?>
	  </form>
			
			<script language="javascript">
		/* Function to validate the site review */
function validate_sitereview(frm)
{
	fieldRequired 		= Array('review_author','review_author_email','review_details');
	fieldDescription 	= Array('<?=stripslash_javascript($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ALERT_AUTHOR'])?>','<?=stripslash_javascript($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ALERT_EMAIL'])?>','<?=stripslash_javascript($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ALERT_DETAILS'])?>');
	fieldEmail 			= Array('review_author_email');
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	fieldSpecChars 		= Array('review_author');
	fieldCharDesc       = Array('Author Name');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)){
	<?php if($Settings_arr['imageverification_req_sitereview']){ // code for validating the image verification- needs only if it is enabled
				echo "if(frm.sitereview_Vimg.value==''){
					alert('Enter-".stripslash_javascript($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ALERT_VERIFICATION_CODE'])."');
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