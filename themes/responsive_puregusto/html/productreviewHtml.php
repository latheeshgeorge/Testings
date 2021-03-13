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
		$HTML_img = $HTML_alert = $HTML_treemenu='';
		$HTML_treemenu = '	<div class="row breadcrumbs">
												<div class="container">
												<div class="container-tree">
												<ul>
												<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
												<li> &#8594; <a href="'.url_product($product_id,$product_name,1).'">'.$product_name.'</a></li>
												<li> &#8594; '.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_TREEMENU_TITLE']).'</li>

												</ul>
												</div>
												</div></div>';	
							echo $HTML_treemenu;	
		
		?>
		<form method="post" action="" name='frm_productreview' id="frm_productreview" class="frm_cls">
		<input type="hidden" name="fpurpose" id="fpurpose" value="" />
		<?php
		echo '<div class="container">';

		
		$pass_type = 'image_iconpath';
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
		//echo $HTML_treemenu;
		echo $HTML_alert; 
		?>		
<div class="container">

<div class="review-wrap">
	<?php
	/*
<div class="rate_img"><?php
$HTML_img .= '<a href="'.url_product($product_id,$product_name,1).'" title="'.stripslashes($product_name).'">';
									$pass_type = 'image_iconpath';
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
?>

</div>
*/
?> 
<div class="review_details">
<div class="review_form">
	<div class="container">
<form method="post" action="" name='frm_productreview' id="frm_productreview" class="frm_cls">
		<input type="hidden" name="fpurpose" id="fpurpose" value="" />   
		 <div class="form-group">
			 <?php 
			 	echo '<div class="review_pdt"><a href="'.url_product($product_id,$product_name,1).'" title="'.stripslashes($product_name).'">'.$product_name.'</a></div>';

			 ?>
			 </div>
		 <div class="form-group">
			<label for="rating"></label>
			<fieldset id='demo1' class="rating">
                        <input class="stars" type="radio" id="star5" name="review_rating" value="5" />
                        <label class = "full" for="star5" title="Awesome - 5 stars"></label>
                        <input class="stars" type="radio" id="star4" name="review_rating" value="4" />
                        <label class = "full" for="star4" title="Pretty good - 4 stars"></label>
                        <input class="stars" type="radio" id="star3" name="review_rating" value="3" />
                        <label class = "full" for="star3" title="Meh - 3 stars"></label>
                        <input class="stars" type="radio" id="star2" name="review_rating" value="2" />
                        <label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
                        <input class="stars" type="radio" id="star1" name="review_rating" value="1" />
                        <label class = "full" for="star1" title="Sucks big time - 1 star"></label>
 
                    </fieldset>
                    </div>
      		 <div class="form-group">
              
      <label for="email"><?php echo stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_AUTHOR']); ?></label>
    <input name="review_author" type="text" class="form-control" id="review_author" placeholder="Enter Name" />
    </div>
    <div class="form-group">
      <label for="email"><?php echo stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_AUTHOR_EMAIL']);?></label>
    <input name="review_author_email" type="text" class="form-control" id="review_author_email" placeholder="Enter email"/>
    </div>
    
<div class="form-group">
  <label for="comment">Reviews</label>
  <textarea name="review_details" class="form-control" rows="5" class="reviewstxt" id="review_details"></textarea>
</div>
    <div class="form-group">
		  <label for="comment"><?php echo stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_VERIFICATIONCODE']);?></label>
<img src="<?php echo url_verification_image('includes/vimg.php?size=4&amp;pass_vname=prodreview_Vimg&amp;bg=255 255 255&amp;brdr=255 255 255&fnt=255 0 0&circle=1',1) ?>" border="0" alt="Image Verification" class="captcha"/>
		<?php
		// showing the textbox to enter the image verification code
							$vImage->setbgcolor("0 255 0");
							$vImage->showCodBox(1,'prodreview_Vimg','class="form-control"'); 
						?> <div class="imgver_textB"><?=stripslash_normal($Captions_arr['CUST_REG']['CUSTOMER_LETTER_SENS_CODE'])?></div>
						</div>
<a href="#" onclick="javascript:validate_prodreview(document.frm_productreview);" class="btn-primary-bt topcart-bt"><?php echo stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_SAVE_BUTTON']); ?></a> 
 <input type="hidden" name="action_purpose" id="action_purpose" value="add_prod_review"/>
		<input type="hidden" name="prodreview_Submit" id="prodreview_Submit" value="1"/>
		</form>
</div>
</div>
</div>
</div>
</div>
</div>
                   
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
		$HTML_treemenu = '	<div class="row breadcrumbs">
												<div class="container">
												<div class="container-tree">
												<ul>
												<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
												<li> &#8594; <a href="'.url_product($product_id,$product_name,1).'">'.$product_name.'</a></li>
												<li> &#8594; '.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_TREEMENU_TITLE']).'</li>

												</ul>
												</div>
												</div></div>';	
							echo $HTML_treemenu;	
		
		$pass_type = 'image_iconpath';
		// Calling the function to get the image to be shown
		$img_arr = get_imagelist('prod',$product_id,$pass_type,0,0,1);
		echo '<div class="container">';
		echo '<div class="container">';
		if($_REQUEST['alert']==1)
		{
		if($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ADDED_SUCCESSFULLY']){
	    $HTML_alert .= 
		'<div class="cart_msg_outerA">
					<div class="cart_msg_txt"><div class="alert alert-success">';
						$HTML_alert .= stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ADDED_SUCCESSFULLY']);
			$HTML_alert .=	'</div></div>
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
					//echo $HTML_paging;
				}	
		}
		else {
		echo '
		<div class="cart_msg_outerB">
				<div class="cart_msg_txt">'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_NO_REVIEW_MSG']).'
				</div>
		</div>';
		}
		echo'
		  <div class="container"><div class="my_hm_shlf_inner">
            
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
									//echo $HTML_img;
						echo '	
						</td>
						<td  valign="middle">';
							
							echo '<div class="review_pdt"><a href="'.url_product($product_id,$product_name,1).'">'.$product_name.'</a></div>
							
						</td>
					</tr>
				</table>
			</div>
           </div>';
			$ret_prodreview = $db->query($sql_prodreview);
			if ($db->num_rows($ret_prodreview)){
				while($row_prodreview = $db->fetch_array($ret_prodreview)){
				$rating = $row_prodreview['review_rating'];
				$date_arr =array();
				$date_arr = explode('@',$row_prodreview['reviewed_on']);
				echo 
					'<div class="container"><div class="review_page_div">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="review_table" >
							<tr>
								<td class="review_table_left" valign="middle">
									<div class="review_user">
											<div>'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_AUTHOR']).'</div>
											<div><span>'.stripslashes($row_prodreview['review_author']).'</span></div>
											<div>'.stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_RATING']).'</div>
										<div>';
										//for($i=1;$i<=$rating;$i++){
										?>
										      <input id="input-3" name="input-3" value="<?php echo $rating;?>" class="rating-loading">
										<?
										//}
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
					</div></div>';
				}
			}
			echo '</div>';
			echo '</div>';
		}
	};	
?>
