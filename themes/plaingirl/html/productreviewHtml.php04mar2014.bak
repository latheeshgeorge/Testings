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
		$Captions_arr['CUST_REG'] = getCaptions('CUST_REG'); // to get values for the captions from the general settings site captions
			$Captions_arr['COMMON'] = getCaptions('COMMON'); // to get values for the captions from the general settings site captions
		?>
		<form method="post" action="" name='frm_productreview' id="frm_productreview" class="frm_cls">
		<input type="hidden" name="fpurpose" id="fpurpose" value="" />
		<?php
		$HTML_img = $HTML_alert = $HTML_treemenu='';
		$HTML_treemenu .=
		'<div class="tree_menu_conA">
		  <div class="tree_menu_topA"></div>
		  <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			 <li><a href="'.url_product($product_id,$product_name,1).'">'.$product_name.'</a></li>
			 <li>'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_TREEMENU_TITLE']).'</li>
			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
		$pass_type = 'image_thumbpath';
		// Calling the function to get the image to be shown
		$img_arr = get_imagelist('prod',$product_id,$pass_type,0,0,1);
		if($alert)
		{ 
		$HTML_alert .= 
		'<div class="cart_msg_outerA">
		<div class="cart_msg_topA"></div>
				<div class="cart_msg_txt">';
				if($Captions_arr['PRODUCT_REVIEWS'][$alert]){
					$HTML_alert .= "Error !! ". stripslash_normal($Captions_arr['PRODUCT_REVIEWS'][$alert]);
				}else{
					$HTML_alert .=  "Error !! ". stripslashes($alert);
				}
		$HTML_alert .=	'</div>
		<div class="cart_msg_bottomA"></div>
		</div>';
		}
		echo $HTML_treemenu;
		echo $HTML_alert; 
		echo '
		<div class="my_hm_shlf_inner">
		<div class="my_hm_shlf_inner_top"></div>
			<div class="my_hm_shlf_inner_cont">
				<div class="my_hm_shlf_cont_div">
					<div class="my_hm_shlf_pdt_con">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td  valign="middle">';
								
								$HTML_img .= '<a href="'.url_product($product_id,$product_name,1).'" title="'.stripslashes($product_name).'">';
									$pass_type = 'image_thumbpath';
									// Calling the function to get the image to be shown
									$img_arr = get_imagelist('prod',$product_id,$pass_type,0,0,1);
									if(count($img_arr))
									{
										$HTML_img .=show_image(url_root_image($img_arr[0][$pass_type],1),$product_name,$product_name,'','',1);
									}
									else
									{
										// calling the function to get the default image
										$no_img = get_noimage('prod',$pass_type); 
										if ($no_img)
										{
											$HTML_img .=show_image($no_img,$product_name,$product_name,'','',1);
										}	
									}	
									$HTML_img .='</a>';
									echo $HTML_img;
								echo
								'</td>
								<td  valign="middle">
									<div class="review_pdt">'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_TREEMENU_TITLE']).'</div>
									<div class="review_pdt"><a href="'.url_product($product_id,$product_name,1).'" title="'.stripslashes($product_name).'">'.$product_name.'</a></div>
									<div class="review_namebtn">
										<div class="review_btn"><div><a href="'.url_product($product_id,'',1).'" >'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_BACK_TO_DETAILS_LINK']).'</a></div></div>
										<div class="review_btn"><div><a href="'.url_link('readproductreview'.$product_id.'.html',1).'" >'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_READREVIEW_LINK']).'</a></div></div>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		<div class="my_hm_shlf_inner_bottom"></div>
		</div>';
		echo
		'<div class="review_page_div">
			<table width="100%" border="0" cellspacing="3" cellpadding="0" class="review_write_table" >
				<tr>
					<td align="left" valign="top">'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_AUTHOR']).'</td>
					<td align="left" valign="top">'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_AUTHOR_EMAIL']).'</td>
				</tr>
				<tr>
					<td align="left" valign="top"><input name="review_author" type="text" class="reviewsinput" id="review_author" size="25" /></td>
					<td align="left" valign="top"><input name="review_author_email" type="text" class="reviewsinput" id="review_author_email" size="25" /></td>
				</tr>
				<tr>
					<td align="left" valign="top">'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_REVIEWTEXT']).'</td>
					<td align="left" valign="top">'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_RATING']).'</td>
				</tr>
				<tr>
				<td align="left" valign="top" ><textarea name="review_details" cols="33" rows="4" class="reviewstxt" id="review_details"></textarea></td>
					<td rowspan="5" align="left" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="0" class="review_rate">';
					for($i=1;$i<=5;$i++){
						$checked = ($i==1)?'checked="checked"':'';
					echo 	
						'<tr>
							<td width="2%" align="left" valign="middle"><label>
							<input type="radio" name="review_rating" id="review_rating" value="'.$i.'" '.$checked.' />
							</label></td>
							<td width="98%" align="left" valign="middle">';
							for($j=1;$j<=$i;$j++) {
							echo '<img src="'.url_site_image('star.gif',1).'" />';
							}
						echo '</td>
						</tr>';
					 }
					echo
					'</table></td>
				</tr>';
			 if($Settings_arr['imageverification_req_prodreview']) {
			 echo '
			<tr>
				<td align="left" valign="top">'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_VERIFICATIONCODE']).'</td>
			</tr>
			<tr>
					    <td align="left"><img src="'.url_verification_image('includes/vimg.php?size=4&amp;pass_vname=prodreview_Vimg&amp;bg=255 255 255&amp;brdr=255 255 255&fnt=255 0 0&circle=1',1).'" border="0" alt="Image Verification" class="captcha"/></td>
					   
					    </tr>
					  <tr>
					    <td align="left">';
							// showing the textbox to enter the image verification code
							$vImage->setbgcolor("0 255 0");
							$vImage->showCodBox(1,'prodreview_Vimg','class="img_input"'); 
						?> <div class="imgver_textB"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_LETTER_SENS_CODE'])?></div></td>
					    </tr><?
			}
			echo
			'<tr>
				<td align="left" valign="top"> <div class="review_save"><div><a href="#" onclick="javascript:validate_prodreview(document.frm_productreview);">'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_SAVE_BUTTON']).'</a></div>
			</div></td>
			</tr>
			</table>
		</div>';
		?>
		<input type="hidden" name="action_purpose" id="action_purpose" value="add_prod_review"/>
		<input type="hidden" name="prodreview_Submit" id="prodreview_Submit" value="1"/>
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
				}else{document.frm_productreview.submit();
					return true;
				}";
          }
		  else
		  {
			  ?>
			 	 document.frm_productreview.submit();
			  <? 
		 }
		 ?>
	}
	else
	{
		return false;
	}
}
</script>
<? 
}
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
		$HTML_img = $HTML_alert = $HTML_treemenu='';
		$HTML_treemenu .=
		'<div class="tree_menu_conA">
		  <div class="tree_menu_topA"></div>
		  <div class="tree_menu_midA">
			<div class="tree_menu_content">
			  <ul class="tree_menu">
			<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
			 <li><a href="'.url_product($product_id,$product_name,1).'">'.$product_name.'</a></li>
			 <li>'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_TREEMENU_TITLE']).'</li>
			</ul>
			  </div>
		  </div>
		  <div class="tree_menu_bottomA"></div>
		</div>';
		echo $HTML_treemenu;
		$pass_type = 'image_thumbpath';
		// Calling the function to get the image to be shown
		$img_arr = get_imagelist('prod',$product_id,$pass_type,0,0,1);
		if($_REQUEST['alert']==1)
		{
		if($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ADDED_SUCCESSFULLY']){
	    $HTML_alert .= 
		'<div class="cart_msg_outerA">
			<div class="cart_msg_topA"></div>
					<div class="cart_msg_txt">';
						$HTML_alert .= stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ADDED_SUCCESSFULLY']);
			$HTML_alert .=	'</div>
			<div class="cart_msg_bottomA"></div>
		</div>';
			} 
		}
		echo $HTML_alert ;
		 if ($tot_cnt>0){
					  $pg_variable	= 'review_pg';
						if ($tot_cnt>0)
						{
						 	$path = url_link('readproductreview'.$product_id.'.html',1);
							$pageclass_arr['container'] = 'pagenavcontainer';
							$pageclass_arr['navvul']	= 'pagenavul';
							$pageclass_arr['current']	= 'pagenav_current';
							$query_string = "";
						}
				$paging 			= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Product reviews',$pageclass_arr);
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
				<div class="cart_msg_txt">'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_NO_REVIEW_MSG']).'
				</div>
				<div class="cart_msg_bottomB"></div>
		</div>';
		}
		echo'
		  <div class="my_hm_shlf_inner">
            <div class="my_hm_shlf_inner_top"></div>
				<div class="my_hm_shlf_inner_cont">
				<div class="my_hm_shlf_cont_div">
				<div class="my_hm_shlf_pdt_con">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td  valign="middle">
						';
						$HTML_img .= '<a href="'.url_product($product_id,$product_name,1).'" title="'.stripslashes($product_name).'">';
									$pass_type = 'image_thumbpath';
									// Calling the function to get the image to be shown
									$img_arr = get_imagelist('prod',$product_id,$pass_type,0,0,1);
									if(count($img_arr))
									{
										$HTML_img .=show_image(url_root_image($img_arr[0][$pass_type],1),$product_name,$product_name,'','',1);
									}
									else
									{
										// calling the function to get the default image
										$no_img = get_noimage('prod',$pass_type); 
										if ($no_img)
										{
											$HTML_img .=show_image($no_img,$product_name,$product_name,'','',1);
										}	
									}	
									$HTML_img .='</a>';
									echo $HTML_img;
						echo '	
						</td>
						<td  valign="middle">';
							if($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_READREVIEW_TITLE']) {
								echo '<div class="review_pdt">'.$Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_READREVIEW_TITLE'].'</div>';
							 }
							echo '<div class="review_pdt"><a href="'.url_product($product_id,$product_name,1).'">'.$product_name.'</a></div>
							<div class="review_namebtn">
								<div class="review_btn"><div><a href="'.url_product($product_id,'',1).'" >'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_BACK_TO_DETAILS_LINK']).'</a></div></div>';
								if($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_ADDREVIEW_LINK']){
								 echo '<div class="review_btn"><div><a href="'.url_link('writeproductreview'.$product_id.'.html',1).'" >'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_ADDREVIEW_LINK']).'</a></div></div>';
								}
							echo '</div>
						</td>
					</tr>
				</table>
				</div>
				</div>
			</div>
			<div class="my_hm_shlf_inner_bottom"></div>
           </div>';
			$ret_prodreview = $db->query($sql_prodreview);
			if ($db->num_rows($ret_prodreview)){
				while($row_prodreview = $db->fetch_array($ret_prodreview)){
				$rating = $row_prodreview['review_rating'];
				$date_arr =array();
				$date_arr = explode('@',$row_prodreview['reviewed_on']);
				echo 
					'<div class="review_page_div">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="review_table" >
							<tr>
								<td class="review_table_left" valign="middle">
									<div class="review_user">
											<div>'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_AUTHOR']).'</div>
											<div><span>'.stripslashes($row_prodreview['review_author']).'</span></div>
											<div>'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_RATING']).'</div>
										<div>';
										for($i=1;$i<=$rating;$i++){
										?>
											<img src="<? url_site_image('star.gif')?>" />
										<?
										}
										echo
										'</div>
									</div>
								</td>
								<td class="review_table_right" valign="middle">    
								<div>
									'.stripslashes($row_prodreview['review_details']).'
								</div>
								<div class="review_date" >'.$date_arr[0].'</div>
								</td>
							</tr>
						</table>
					</div>';
				}
			}
		}
	};	
?>