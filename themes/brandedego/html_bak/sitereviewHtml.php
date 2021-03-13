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
		// Defining function to show the product review
		function Write_Sitereview()
		{
		
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$vImage,$product_id,$product_name,$alert;
		$Captions_arr['CUST_REG'] = getCaptions('CUST_REG'); // to get values for the captions from the general settings site captions
		$Captions_arr['COMMON'] = getCaptions('COMMON'); // to get values for the captions from the general settings site captions
		$Captions_arr['PRODUCT_REVIEWS'] = getCaptions('PRODUCT_REVIEWS'); // to get values for the captions from the general settings site captions
		$HTML_treemenu =
						'<div class="tree_menu_conA">
						  <div class="tree_menu_topA"></div>
						  <div class="tree_menu_midA">
							<div class="tree_menu_content">
							  <ul class="tree_menu">
							<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
							 <li>'.stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ADDREVIEW_TITLE']).'</li>
							</ul>
							  </div>
						  </div>
						  <div class="tree_menu_bottomA"></div>
						</div>';
		echo $HTML_treemenu;
		?>
		<form method="post" action="" name="frm_sitereview" id="frm_sitereview" class="frm_cls" >
		<input type="hidden" name="action_purpose" id="action_purpose" value="add_site_review">
		<?php 
			 if($_REQUEST['alert1']==1 && !$alert){ ?>
				<div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
					<div class="cart_msg_txt">
								<?php 
								  echo stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ADDED_SUCCESSFULLY']);
								?>
						</div>
				<div class="cart_msg_bottomA"></div>
				</div>
				<?php } ?>
				
				
				<div class="review_page_div">
				<table width="100%" border="0" cellspacing="3" cellpadding="0" class="review_write_table" >
				<tr>
				<td align="left" valign="top" colspan="2">
				<div class="review_namebtn">
				<div class="review_btn"><div><a href="<?php url_link('sitereview.html')?>" ><?=$Captions_arr['SITE_REVIEWS']['SITE_REVIEW_READREVIEW_TITLE']?></a></div>
				</div>
				</div></td>
				</tr>
				<tr>
				<td align="left" valign="top"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_AUTHOR'])?> </td>
				<td align="left" valign="top"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_AUTHOR_EMAIL'])?> </td>
				</tr>
				
				<tr>
				<td align="left" valign="top"><input name="review_author" type="text" class="reviewsinput" id="review_author" size="45" maxlength="<?=$short?>" /></td>
				<td align="left" valign="top"><input name="review_author_email" type="text" class="reviewsinput" id="review_author_email" size="43" maxlength="<?=$medium?>"/></td>
				</tr>
				<tr>
				<td align="left" valign="top"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_REVIEWTEXT'])?></td>
				<td align="left" valign="top"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_RATING'])?> </td>
				</tr>
				<tr>
				<td align="left" valign="top" ><textarea name="review_details" class="reviewstxt" id="review_details"></textarea></td>
				<td rowspan="5" align="left" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="0" class="review_rate">
				<? for($i=1;$i<=5;$i++){
					if($i==1)
						$checked = 'checked ="checked"';
					else
						$checked = '';
				?>
										<tr>
											<td width="2%" align="left" valign="middle"><label>
											<input type="radio" name="review_rating" id="review_rating" value="<?=$i?>" <?php echo $checked?>/>
											</label></td>
											<td width="98%" align="left" valign="middle">
											<? for($j=1;$j<=$i;$j++) {?>
											<img src="<? url_site_image('star.gif')?>"  />
											<? }?>
											</td>
										</tr>
									<? }?>
				</table></td>
				</tr>
				<?	 if($Settings_arr['imageverification_req_sitereview']) {?>
				<tr>
				<td align="left" valign="top"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_REVIEWCODE'])?></td>
				</tr>
				<tr>
					    <td align="left"><img src="<?php url_verification_image('includes/vimg.php?size=4&amp;pass_vname=sitereview_Vimg&amp;bg=255 255 255&amp;brdr=255 255 255&fnt=255 0 0&circle=1')?>" border="0" alt="Image Verification" class="captcha"/></td>
					   
					    </tr>
					  <tr>
					    <td align="left"><?php 
							// showing the textbox to enter the image verification code
							$vImage->setbgcolor("0 255 0");
							$vImage->showCodBox(1,'sitereview_Vimg','class="img_input"'); 
						?> <div class="imgver_textB"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_LETTER_SENS_CODE'])?></div></td>
					    </tr>
				
				<? }?>
				<tr>
				<td align="right" valign="top"><div class="review_save"><div><a href="#" onclick="javascript:validate_sitereview(document.frm_sitereview);"><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_SAVE_BUTTON'])?></a></div></div></td>
				</tr>
				</table>
				</div>
 
 		<input type="hidden" name="sitereview_Submit" id="sitereview_Submit" value="1">
	</form>
<script type="text/javascript">
/* Function to validate the product review */
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
				}else{ document.frm_sitereview.submit();
					return true;
				}";
			}
			 else
		  {
			  ?>
			 	 document.frm_sitereview.submit();
			  <? 
		 }
		 ?>
		return true;
		}
	else
	{
		return false;
	}
}
</script>
<? 
}
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
				$HTML_treemenu .=
						'<div class="tree_menu_conA">
						  <div class="tree_menu_topA"></div>
						  <div class="tree_menu_midA">
							<div class="tree_menu_content">
							  <ul class="tree_menu">
							<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
							 <li>'.stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_TREEMENU_TITLE']).'</li>
							</ul>
							  </div>
						  </div>
						  <div class="tree_menu_bottomA"></div>
						</div>';
			echo $HTML_treemenu;					
			if($_REQUEST['alert1']==1 && !$alert)
			{ ?>
				<div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
					<div class="cart_msg_txt">
								<?php 
								  echo stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ADDED_SUCCESSFULLY']);
								?>
					</div>
				<div class="cart_msg_bottomA"></div>
				</div>
				<?php } ?>
				   
				<?
				
					$pg_variable	= 'review_pg';
					if ($tot_cnt>0)
					{
						$path = url_link('sitereview.html',1);
						$pageclass_arr['container'] = 'pagenavcontainer';
						$pageclass_arr['navvul']	= 'pagenavul';
						$pageclass_arr['current']	= 'pagenav_current';
						
						$query_string = "";
						$paging 			= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Site reviews',$pageclass_arr);
						if($start_var['pages']>1)
						{
							$HTML_paging .='<div class="subcat_nav_pdt_no"><span>'.$paging['total_cnt'].'</span></div>
									<div class="page_nav_conA">
									<div class="page_nav_topA"></div>
										<div class="page_nav_midA">
											<div class="page_nav_content">
											<ul>';
											$HTML_paging .= $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
											$HTML_paging .= ' 
											</ul>
											</div>
										</div>
									<div class="page_nav_bottomA"></div>
								</div>';
							echo $HTML_paging;
						}
					}
					else {
						echo '
						<div class="cart_msg_outerB">
								<div class="cart_msg_topB"></div>
								<div class="cart_msg_txt">'.stripslash_normal($Captions_arr['SITE_REVIEWS']['READ_SITE_NO_REVIEW_MSG']).'
								</div>
								<div class="cart_msg_bottomB"></div>
						</div>';
						}
					?>
					 
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td  valign="middle">&nbsp;
										
										</td>
										<td  valign="middle">
										<?php 
										/*if($db->num_rows($ret_sitereview)>0)
										{
											if($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_READREVIEW_TITLE'])
											{
											?>
												<div class="review_pdt"><?=$Captions_arr['SITE_REVIEWS']['SITE_REVIEW_READREVIEW_TITLE']?></div>
											<? 
											}
										}*/	
										?>
										<div class="review_namebtn">
										<?php
										if(stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ADDREVIEW_TITLE']))
										{
										?>
											<div class="review_btn"><div><a href="<?php url_link('writesitereview.html')?>" ><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['SITE_REVIEW_ADDREVIEW_TITLE'])?></a></div></div>
										<? 
										}
										?>
											</div>
										</td>
									</tr>
								</table>
								
			<?php
			if($db->num_rows($ret_sitereview)>0)
			{
			if ($db->num_rows($ret_sitereview)){
				while($row_prodreview = $db->fetch_array($ret_sitereview)){
				$rating = $row_prodreview['review_rating'];
				$date_arr =array();
				$date_arr = explode('@',$row_prodreview['reviewed_on']);
				?>
					<div class="review_page_div">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="review_table" >
							<tr>
								<td class="review_table_left" valign="middle">
									<div class="review_user">
											<div><span><?=stripslashes($row_prodreview['review_author'])?></span></div>
										<div>
										<?php 
										for($i=1;$i<=$rating;$i++){
										?>
											<img src="<? url_site_image('star.gif')?>" />
										<?
										}
										?>
										</div>
									</div>
								</td>
								<td class="review_table_right" valign="middle">    
								<div>
									<?=stripslashes($row_prodreview['review_details'])?>
								</div>
								<div class="review_date" ><?=$date_arr[0]?></div>
								</td>
							</tr>
						</table>
					</div>
					
				<?php	
				}
			}
		  }
		}
	};	
?>