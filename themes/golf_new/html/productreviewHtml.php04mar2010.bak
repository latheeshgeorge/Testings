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
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <a href="<?php url_product($product_id,$product_name,-1)?>"><?=$product_name?></a>  >> <?=$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_TREEMENU_TITLE']?></div>
		
			<table width="100%" border="0" cellpadding="0" cellspacing="3"  class="addreivewtable">
			<?php if($alert){ ?>
			<tr>
				<td colspan="2" class="errormsg" align="center">
				<?php 
						  if($Captions_arr['PRODUCT_REVIEWS'][$alert]){
						  		echo "Error !! ". $Captions_arr['PRODUCT_REVIEWS'][$alert];
						  }else{
						  		echo  "Error !! ". $alert;
						  }
				?>
				</td>
			</tr>
		<?php } 
		if($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_READREVIEW_TITLE']) {?>
		<tr>
          <td colspan="2" class="message_header"><h1><?=$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_READREVIEW_TITLE']?></h1></td>
        </tr>
		<?php }  
			if($Captions_arr['PRODUCT_REVIEWS']['PRODUCT_REVIEW_READREVIEW_TITLE']) {?>
			<tr>
			<td colspan="2" class="addreivewheader"><?=$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ADDREVIEW_TITLE']?></td>
			</tr>	<?php } ?>	
			<tr>
			<td width="41%" class="addreivewconent"><?=$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_AUTHOR']?></td>
			<td width="59%" align="left" valign="middle"><input name="review_author" type="text" class="addreivewinput" id="review_author" size="39" /></td>
			</tr>
			<tr>
			<td width="41%" class="addreivewconent"><?=$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_AUTHOR_EMAIL']?></td>
			<td width="59%" align="left" valign="middle"><input name="review_author_email" type="text" class="addreivewinput" id="review_author_email" size="39" /></td>
			</tr>
			<tr>
			<td class="addreivewconent"><?=$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_REVIEWTEXT']?></td>
			<td align="left" valign="middle"><textarea name="review_details" cols="33" rows="4" class="addreivewinput" id="review_details"></textarea></td>
			</tr>
			<tr>
			<td class="addreivewconent"><?=$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_RATING']?></td>
			<td align="left" valign="middle"><label>
			<select name="review_rating" class="addreivewinput" id="review_rating">
			<? for($i=1;$i<=5;$i++){?>
			<option value="<?=$i?>"><?=$i?></option>
			<? }?>
			</select>
			</label></td>
			</tr>
			<tr>
			<td>&nbsp;</td>
			<td align="left" valign="middle">&nbsp;</td>
			</tr><?
			 if($Settings_arr['imageverification_req_prodreview']) {?>
			<tr>
			<td class="addreivewconent">&nbsp;</td>
			<td align="left" valign="middle" class="addreivewconentred"><img src="<?php url_verification_image('includes/vimg.php?size=4&amp;pass_vname=prodreview_Vimg')?>" border="0" alt="Image Verification"/>
			</td>
			</tr>
			
			<tr>
			<td class="addreivewconent"><?=$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_VERIFICATIONCODE']?></td>
			<td align="left" valign="middle" class="addreivewconentred">
			
			<?php 
				// showing the textbox to enter the image verification code
				$vImage->showCodBox(1,'prodreview_Vimg','class="inputA_imgver"'); 
			?>
			</td>
			</tr>
			<? }?>
			<tr>
			<td class="addreivewconent"><input type="hidden" name="action_purpose" id="action_purpose" value="add_prod_review"/></td>
			<td align="left" valign="middle"><input name="prodreview_Submit" type="submit" class="buttongray" id="prodreview_Submit" value="<?=$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_SAVE_BUTTON']?>" /></td>
			</tr><?
			if($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_READREVIEW_LINK']){?>
			 <tr>
          <td  colspan="2" align="left" valign="top"  class="addreivewconent" >
		  <a href="<?php url_link('readproductreview'.$product_id.'.html')?>" class="message_reviewlink">
		 <?= $Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_READREVIEW_LINK'];?>
		 </a></td></tr>
		 <? }
		
			if($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_BACK_TO_DETAILS_LINK']){?>
			 <tr>
          <td  colspan="2" align="left" valign="top"  class="addreivewconent" ><a href="<?php url_product($product_id,'',-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="message_backlink"><?=$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_BACK_TO_DETAILS_LINK']?></a></td>
        </tr>
		<? }?>
			</table></form>
			<script type="text/javascript">
			/* Function to validate the product review */
function validate_prodreview(frm)
{
	fieldRequired 		= Array('review_author','review_author_email','review_details');
	fieldDescription 	= Array('<?=$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ALERT_AUTHOR']?>','<?=$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ALERT_EMAIL']?>','<?=$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ALERT_DETAILS']?>');
	fieldEmail 			= Array('review_author_email');
	fieldConfirm 		= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 		= Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)){
	<?php if($Settings_arr['imageverification_req_prodreview']){ // code for validating the image verification- needs only if it is enabled
				echo "if(frm.prodreview_Vimg.value==''){
					alert('Enter-".$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ALERT_IMAGE_VERIFICATION']."');
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
			$pg_variable	= 'review_pg';
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
			$start_var 		= prepare_paging($_REQUEST['prodreview_pg'],$prod_review_per_page,$tot_cnt);
			$Limitprodreviews		= " LIMIT ".$start_var['startrec'].", ".$prod_review_per_page;
			
			$sql_prodreview         = "SELECT review_id,DATE_FORMAT(review_date,'%e-%b-%Y : %r') as reviewed_on,
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
		<div class="treemenu"><a href="<? url_link('');?>">Home</a> >> <a href="<?php url_product($product_id,$product_name,-1)?>"><?=$product_name?></a>  >> <?=$Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_TREEMENU_TITLE']?></div>
		<table width="100%" border="0" cellpadding="0" cellspacing="4" class="readreivewtable">
		<?php 
		
		if($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_READREVIEW_TITLE']) {?>
		<tr>
          <td colspan="2" class="message_header"><h1><?=$Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_READREVIEW_TITLE']?></h1></td>
        </tr>	
		<?php 
		if($_REQUEST['alert']==1)
		{
		if($Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ADDED_SUCCESSFULLY']){?>
		<tr>
          <td colspan="2" class="message" align="center"><?=$Captions_arr['PRODUCT_REVIEWS']['WRITE_PRODUCT_REVIEW_ADDED_SUCCESSFULLY']?></td>
        </tr>
		
		<?php } 
		}

	}
	$ret_prodreview = $db->query($sql_prodreview);
		if ($db->num_rows($ret_prodreview)){
			while($row_prodreview = $db->fetch_array($ret_prodreview)){
			$rating = $row_prodreview['review_rating'];
		?>
	
		
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td width="35%" align="left" valign="top"  class="readreivewicon" ><?=$Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_AUTHOR']?></td>
          <td width="65%" align="left" valign="top" class="readreivewname"><?=$row_prodreview['review_author']?> ( <?=$row_prodreview['reviewed_on']?>)</td>
        </tr>
        <tr>
          <td align="left" valign="top" class="readreivewcontent"><?=$Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_REVIEW']?></td>
          <td align="left" valign="top"><?=$row_prodreview['review_details']?></td>
        </tr>
        <tr>
          <td align="left" valign="top" class="readreivewcontent"><?=$Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_RATING']?></td>
          <td align="left" valign="top">
		  
		  <?php 
		
		  for($i=1;$i<=$rating;$i++){
		 
		  ?>
		  <img src="<? url_site_image('reviewstar_on.gif',0)?>" width="9" height="10" />
		  <? } for($k=$rating+1;$k<=5;$k++){?><img src="<? url_site_image('reviewstar_off.gif',0)?>" width="9" height="10" /><? }?></td>
        </tr>
		<?php 
			}
		}
		if ($tot_cnt>0){
		?>
		<tr>
			<td colspan="2"  align="center" class="pagingcontainertd">
			<?php 
				$path = url_link('readproductreview'.$product_id.'.html',1);
				$query_string='';
				$query_string .= '';
				paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Product reviews',$pageclass_arr); 
			?>	
			</td>
		</tr>
			<?php
			}else {
			?>
			 <tr>
          <td  colspan="2" align="center" valign="top"  class="addreivewconent" ><?php echo $Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_NO_REVIEW_MSG'];
		  ?></td>
        </tr>
			<?
			}
		
			if($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_ADDREVIEW_LINK']){?>
			 <tr>
          <td  colspan="2" align="left" valign="top"  class="addreivewconent" >
		  <a href="<?php url_link('writeproductreview'.$product_id.'.html')?>" class="message_reviewlink">
		 <?= $Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_ADDREVIEW_LINK'];?>
		 </a></td></tr>
		 <? }
		 if($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_BACK_TO_DETAILS_LINK']){?>
			 <tr>
          <td  colspan="2" align="left" valign="top"  class="addreivewconent" ><a href="<?php url_product($product_id,'',-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="message_backlink"><?=$Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_REVIEW_BACK_TO_DETAILS_LINK']?></a></td>
        </tr>
		<? }?>
      </table>
		<?php	
		}
	};	
?>