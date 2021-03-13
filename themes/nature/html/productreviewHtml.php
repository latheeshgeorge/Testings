<?php
/*############################################################################
	# Script Name 	: productreviewHtml.php
	# Description 	: Page which holds the display logic for product reviews
	# Coded by 		: ANU
	# Created on	: 08-Feb-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class productreview_Html
	{
		// Defining function to show the product review
		function Write_Productreview()
		{
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$vImage,$product_id,$product_name,$alert;
			
		?>
			<form method="post" action="" name='frm_productreview' id="frm_productreview" class="frm_cls" onsubmit="return validate_prodreview(this)">
        			<input type="hidden" name="fpurpose" id="fpurpose" value="" />

         <div class="treemenu">
          <ul>
            <li><a href="<? url_link('');?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
           	<li> <a href="<?php url_product($product_id,$product_name,-1)?>"><?=$product_name?></a> &gt;&gt; </li>
		    <li> <?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_TREEMENU_TITLE'])?></li>
          </ul>
        </div>
        <div class="inner_header"><?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_TREEMENU_TITLE'])?> on '<?=$product_name?>'</div>
		<?php
		$pass_type = 'image_thumbpath';
		// Calling the function to get the image to be shown
		$img_arr = get_imagelist('prod',$product_id,$pass_type,0,0,1);
		
		 if($alert){ ?>
        <div class="inner_con" >
					<div class="inner_top"></div>
						<div class="inner_middle">
							<table width="100%" border="0" cellpadding="0" cellspacing="0" class="regi_table">
								
								<tr>
								<td colspan="2" class="errormsg" align="center">
								<?php 
						  if($Captions_arr['PRODUCT_REVIEWS'][$alert]){
						  		echo "Error !! ". stripslash_normal($Captions_arr['PRODUCT_REVIEWS'][$alert]);
						  }else{
						  		echo  "Error !! ". stripslashes($alert);
						  }
				?>
								</td>
								</tr>
								
							</table>
						</div>
					<div class="inner_bottom"></div>
				</div>
			<?php 
			} 
			?>
         <div class="inner_con" >
        <div class="inner_top"></div>
        	 
        <div class="inner_middle">
          <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" class="reviews">
            <tbody>
              
              <tr>
                <td colspan="6" align="left" valign="middle" class="reviews_font">
				<?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['PRODUCT_REVIEW_TOPTEXT'])?>
				</td>
                </tr>
			<?php 	if($Captions_arr['PRODUCT_REVIEWS']['PRODUCT_REVIEW_READREVIEW_TITLE']) {?>
              <tr>
                <td colspan="6" align="left" valign="middle" class="reviews_font"><span class="reg_header"><span><?=$Captions_arr['PRODUCT_REVIEWS']['PRODUCT_REVIEW_READREVIEW_TITLE']?></span></span></td>
                </tr>
				<?
				}
				?>
              <tr>
                <td class="reviews_fontbold" valign="middle" width="16%" align="left"><?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_AUTHOR'])?></td>
                <td width="15%" align="left" valign="middle" class="reviews_fontbold">
				<input name="review_author" type="text" class="reviewsinput" id="review_author" size="25" />
				</td>
                <td align="left" valign="middle" class="reviews_fontbold"><?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_AUTHOR_EMAIL'])?> 
               <input name="review_author_email" type="text" class="reviewsinput" id="review_author_email" size="25" />
			    <td colspan="2" align="left" valign="middle" class="reviews_fontbold"><?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_RATING'])?> </td>
                <td width="9%" align="left" valign="middle" class="reviews_fontbold">
				<select name="review_rating" class="addreivewinput" id="review_rating">
				<? for($i=1;$i<=5;$i++){?>
				<option value="<?=$i?>"><?=$i?></option>
				<? }?>
				</select></td>
              </tr>
              <tr>
                <td class="reviews_fontbold" valign="middle" align="left"><?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_REVIEWTEXT'])?></td>
                <td colspan="2" align="left" valign="middle" class="reviews_fontbold"><label>
                  <textarea name="review_details" cols="33" rows="4" class="reviewstxt" id="review_details"></textarea>
                </label></td>
				<td colspan="3">
							<?	 if($Settings_arr['imageverification_req_prodreview']) {?>
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td  align="left" valign="middle" class="reviews_fontbold">
								<?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_VERIFICATIONCODE'])?></td>
							</tr>
							<tr>	
									<td align="left" valign="middle" class="reviews_fontbold">
									<img src="<?php url_verification_image('includes/vimg.php?size=4&amp;pass_vname=prodreview_Vimg&amp;bg=143 186 2')?>" border="0" alt="Image Verification"/>
									</td>
									<td align="left" valign="middle" class="reviews_fontbold">
									<?php 
									// showing the textbox to enter the image verification code
									$vImage->showCodBox(1,'prodreview_Vimg','class="img_input"'); 
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
						<?php
						if($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_READREVIEW_LINK']){?>
						<input type="button" name="bach_button" value="<?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_READREVIEW_LINK'])?>"  class="inner_btn_red" onclick="window.location='<?php url_link('readproductreview'.$product_id.'.html')?>'"/>   
						<?
						} ?>
						<?php
						if($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_BACK_TO_DETAILS_LINK']){?>
						<input type="button" name="bach_button" value="<?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_BACK_TO_DETAILS_LINK'])?>"  class="inner_btn_red" onclick="window.location='<?php url_product($product_id,'',-1)?>'"/>
						<?
						}
						?>
					<label></label></td>
					<td colspan="2" align="right" valign="middle" class="reviews_fontbold">
					<input type="hidden" name="action_purpose" id="action_purpose" value="add_prod_review"/>
					<input name="prodreview_Submit" type="submit" class="inner_btn_red" id="prodreview_Submit" value="<?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_SAVE_BUTTON'])?>" />
					
					</td>
				</tr>
            </tbody>
          </table>
        </div>
        <div class="inner_bottom"></div>
	  </div>  
      
        </form>
			<script type="text/javascript">
			/* Function to validate the product review */
function validate_prodreview(frm)
{
	fieldRequired 		= Array('review_author','review_author_email','review_details');
	fieldDescription 	= Array('<?=stripslash_javascript($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ALERT_AUTHOR'])?>','<?=stripslash_javascript($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ALERT_EMAIL'])?>','<?=stripslash_javascript($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ALERT_DETAILS'])?>');
	fieldEmail 			= Array('review_author_email');
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)){
	<?php if($Settings_arr['imageverification_req_prodreview']){ // code for validating the image verification- needs only if it is enabled
				echo "if(frm.prodreview_Vimg.value==''){
					alert('Enter-".stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ALERT_IMAGE_VERIFICATION'])."');
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
			<? }
	function Show_Productreview()
		{
		
		
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$product_id,$product_name;
			list($review_order_field,$review_order_by,$prod_review_per_page)     = array($Settings_arr['prodreview_ord_fld'],$Settings_arr['prodreview_ord_orderby'],$Settings_arr['productreview_maxcntperpage']);
			$sql_tot_prodreviews = "SELECT count(review_id)
						FROM  
							product_reviews 
						WHERE  
							sites_site_id = $ecom_siteid
							AND products_product_id  =  $product_id  
							AND review_status = 'APPROVED'  
							AND review_hide=0";
			$ret_tot_prodreviews	= $db->query($sql_tot_prodreviews);
			list($tot_cnt) 	        = $db->fetch_array($ret_tot_prodreviews); 	
			$pg_variable	= 'prodreview_pg';
			//$start_arr 		        = prepare_paging($_REQUEST[$pg_variable],$site_review_per_page);
			$start_var 		= prepare_paging($_REQUEST['review_pg'],$prod_review_per_page,$tot_cnt);
			$Limitprodreviews		= " LIMIT ".$start_var['startrec'].", ".$prod_review_per_page;
			
			$sql_prodreview         = "SELECT review_id,DATE_FORMAT(review_date,'%e-%b-%Y @ %r') as reviewed_on,
											review_author,review_rating,review_details 
										FROM  
											 product_reviews 
										WHERE  
											sites_site_id = $ecom_siteid
											AND products_product_id  =  $product_id
											AND review_status = 'APPROVED'  
											AND review_hide=0 
									  	ORDER BY  
											$review_order_field  $review_order_by  
											$Limitprodreviews";
		?>
		<div class="treemenu">
			<ul>
				<li><a href="<? url_link('');?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
				<li> <a href="<?php url_product($product_id,$product_name,-1)?>"><?=$product_name?></a> &gt;&gt; </li>
				<li> <?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_TREEMENU_TITLE'])?></li>
			</ul>
		</div>
		 <div class="inner_header"><?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_READREVIEW_TITLE'])?> on '<?=$product_name?>'</div>
		<?php
		$pass_type = 'image_thumbpath';
		// Calling the function to get the image to be shown
		$img_arr = get_imagelist('prod',$product_id,$pass_type,0,0,1);
		if($_REQUEST['alert']==1)
		{
			if($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ADDED_SUCCESSFULLY']){?> 
			<div class="inner_con" >
				<div class="inner_top"></div>
					<div class="inner_middle">
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="regi_table">
							
							<tr>
							<td colspan="2" class="errormsg" align="center">
							<?php 
					  
							echo  stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ADDED_SUCCESSFULLY']);
			?>
							</td>
							</tr>
							
						</table>
					</div>
				<div class="inner_bottom"></div>
			</div>
			<?php 
			} 
		}
		?>
		<?php if ($tot_cnt>0){
		?>
		<div class="inner_con" >
			<div class="inner_top"></div>  
			<div class="inner_middle">  
				<div class="pagingcontainertd_rw" >
					<div class="pro_nav_pages" align="right"><?php echo paging_show_totalcount($tot_cnt,'Product review(s)',$start_var['pg'],$start_var['pages'])?></div>
						<div class="pro_nav_links" align="right">
						<?php 
						$pg_variable	= 'review_pg';
						if ($tot_cnt>0)
						{
						 $path = url_link('readproductreview'.$product_id.'.html',1);
							$pageclass_arr['container'] = 'pagenavcontainer';
							$pageclass_arr['navvul']	= 'pagenavul';
							$pageclass_arr['current']	= 'pagenav_current';
							
							$query_string = "";
							paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Product reviews',$pageclass_arr,0); 	
						}
						?>
						</div>
						</div>
				 </div>	
			<div class="inner_bottom"></div>
		</div>
		<?
		}
		else {
		?>
		<div class="inner_con" >
			<div class="inner_top"></div>
				<div class="inner_middle">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="regi_table">
						<tr>
							<td  colspan="2" align="center" valign="top"  class="errormsg" ><?php echo stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_NO_REVIEW_MSG']);
						?></td>
						</tr>
					</table>
				</div>
			<div class="inner_bottom"></div>
		</div>
		<?
		}
		?>	
		<div class="inner_con" >
			<div class="inner_top"></div>
				<div class="inner_middle">
					<table class="regitable" width="100%" border="0" cellpadding="0" cellspacing="0">
						<tbody>
							<?php if($Captions_arr['SITE_REVIEWS']['READ_PRODUCT_REVIEW_READREVIEW_TITLE']) {?>
							<tr>
								<td colspan="2" class="reviews_fontbold" align="left"><span class="reg_header"><span><?=stripslash_normal($Captions_arr['SITE_REVIEWS']['READ_PRODUCT_REVIEW_READREVIEW_TITLE'])?></span></span></td>
							</tr>
							<?php 
							}	
							$ret_prodreview = $db->query($sql_prodreview);
								if ($db->num_rows($ret_prodreview)){
									while($row_prodreview = $db->fetch_array($ret_prodreview)){
									$rating = $row_prodreview['review_rating'];
									$date_arr =array();
									$date_arr = explode('@',$row_prodreview['reviewed_on']);
									?>
									<tr>
									<td class="reviewstxt_nametd" valign="middle" width="43%" align="left"><?=stripslashes($row_prodreview['review_author'])?> 
									<span class="reviewstxt_red"><?=$date_arr[1]?></span>
									<?=$date_arr[0]?>
									</td>
									<td width="57%" align="left" valign="middle" class="reviewstxt_txttd">
									<?=stripslashes($row_prodreview['review_details'])?> <br/>
									<?php 
									for($i=1;$i<=$rating;$i++){
									?>
									<img src="<? url_site_image('star-green.gif')?>"  />
									<? } for($k=$rating+1;$k<=5;$k++){?><img src="<? url_site_image('star-white.gif',0)?>"  /><? }?>
									</td>
									</tr>
									<?
								}
							}
							?>
							<tr>
								<td  align="left" valign="middle" class="reviews_fontbold">
										<?php
										if($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_ADDREVIEW_LINK']){?>
										<input type="button" name="bach_button" value="<?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_ADDREVIEW_LINK'])?>"  class="inner_btn_red" onclick="window.location='<?php url_link('writeproductreview'.$product_id.'.html')?>'"/>  
										<?
										} ?>
									<label></label>
									</td>
									<td  align="right" valign="middle" class="reviews_fontbold">
										<?php
										if($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_BACK_TO_DETAILS_LINK']){?>
										<input type="button" name="bach_button" value="<?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_BACK_TO_DETAILS_LINK'])?>"  class="inner_btn_red" onclick="window.location='<?php url_product($product_id,'',-1)?>'"/>
										<?
										}
										?>
								  </td>
							</tr>
						</tbody>
					</table>
				</div>
			<div class="inner_bottom"></div>
		</div>
		<?php	
		}
	};	
?>