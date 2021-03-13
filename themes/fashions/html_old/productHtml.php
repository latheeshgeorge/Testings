<?php
	/*############################################################################
	# Script Name 	: productHtml.php
	# Description 		: Page which holds the display logic for product details
	# Coded by 		: Sny
	# Created on		: 17-Nov-2008
	# Modified by		: Sny
	# Modified On		: 26-Nov-2008
	
	##########################################################################*/
	class product_Html
	{
		function Show_ProductDetails($ret_prod)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					 $ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$components;
			// ** Fetch any captions for product details page
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			
			// ** Fetch the product details
			$row_prod	= $db->fetch_array($ret_prod);
			// ** Check to see whether current user is logged in 
			$cust_id 	= get_session_var("ecom_login_customer");
			
			// ** Check whether qty box is to be shown
			$showqty	= $Settings_arr['show_qty_box'];// show the qty box
			if($_REQUEST['result']=='exists')
			{
			  //$Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVADDED']
			  $alert = $Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVEXISTS'];
			}
			else if($_REQUEST['result']=='added')
			{
			//$Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVEXISTS']
			 $alert = $Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVADDED'];
			}
			else if($_REQUEST['result']=='removed')
			{
			 $alert = $Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVREMOVED'];
			}
			
			$prod_img_show_type =$row_prod['product_details_image_type'];
			?>
			<form method="post" name="frm_proddetails" id="frm_proddetails" action="<?php url_link('manage_products.html')?>" class="frm_cls">
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
			<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
			<input type="hidden" name="pass_combid" value="<?php echo $_REQUEST['pass_combid']?>" />
			<tr>
			<td align="left" valign="top" class="maintable_inner_out">&nbsp;</td>
			<td  colspan="3" align="left" valign="top" class="pro_det_treemenu">
			<?php
					// Decide whether to display the top menu or not
					if(get_session_var('ecom_login_customer'))
					{
						include "themes/$ecom_themename/modules/mod_topmenu.php";
					}
			?>
			<div class="treemenu"><?php echo generate_tree(-1,$_REQUEST['product_id'])?></div>
			<?php
	  		
				if($alert)
				{
			?>
					  <div class="det_message"> - <?php echo $alert?> - </div>
			<?php
				}
				elseif($_REQUEST['stockalert'])
				{
			?>
					<div class="det_message"> - <?php echo $Captions_arr['PROD_DETAILS'][$_REQUEST['stockalert']]	?> - </div>
			<?php	
				}
			?>
			</td>
			<td align="left" valign="top" class="maintable_inner_out">&nbsp;</td>
			</tr>
			<tr>
			<td align="left" valign="top" class="maintable_inner_out">&nbsp;</td>
			<td align="left" valign="top" class="pro_det_image">
			<div>
			<? 
				if($prod_img_show_type=='FLASH') // case of showing images in a flash container
				{
					// pass the ids thru query string
					$prod_tab_id = $_REQUEST['product_id'].'~'.$_REQUEST['prod_curtab']; ?>
					<script type="text/javascript">
						document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="260" height="500">');
						document.write('<param name="movie" value="http://<?php echo $ecom_hostname?>/themes/<?php echo $ecom_themename?>/swf/xml4.swf?filepath=http://<?php echo $ecom_hostname?>/getflashimage_images.php?prod_tab_id=<?=$prod_tab_id?>~ico" />');
						document.write('<param name="quality" value="high" />');
						document.write('<param name="wmode" value="opaque">');
						document.write('<embed src="http://<?php echo $ecom_hostname?>/themes/<?php echo $ecom_themename?>/swf/xml4.swf?filepath=http://<?php echo $ecom_hostname?>/getflashimage_images.php?prod_tab_id=<?=$prod_tab_id?>~ico" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="260" height="500" wmode="opaque"></embed>');
						document.write('</object>');
					</script>
				<?php
				}
				elseif($prod_img_show_type=='FLASH_ROTATE') // Case of showing flash which rotates the given images
				{
				?>
						<script type="text/javascript">
							document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="260" height="500">');
							document.write('<param name="movie" value="http://<?php echo $ecom_hostname?>/themes/<?php echo $ecom_themename?>/swf/rotate_flash.swf?filepath=http://<?php echo $ecom_hostname?>/getflashrotate_images.php?p_id=<?php echo $_REQUEST['product_id']?>" />');
							document.write('<param name="quality" value="high" />');
							document.write('<embed src="http://<?php echo $ecom_hostname?>/themes/<?php echo $ecom_themename?>/swf/rotate_flash.swf?filepath=http://<?php echo $ecom_hostname?>/getflashrotate_images.php?p_id=<?php echo $_REQUEST['product_id']?>" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="260" height="500"></embed>');
							document.write('</object>');
						</script>
				<?php
				}
				elseif($prod_img_show_type=='JAVA') // case of showing javascript swap
				{
					$this->Show_Javascript_Image_Swapper($row_prod);
				}
				else // case of showing normal image display
				{
					$ret_arr = $this->Show_Image_Normal($row_prod);
					$exclude_tabid			= $ret_arr['exclude_tabid'];
					$exclude_prodid			= $ret_arr['exclude_prodid'];
					// Showing additional images
					$this->show_more_images($row_prod,$exclude_tabid,$exclude_prodid);
				}	
			?>	
			</div>
			<?php
			// Check whether any downloads exists for current product
			$sql_attach = "SELECT attachment_id  
							FROM 
								product_attachments 
							WHERE 
								products_product_id = ".$_REQUEST['product_id']."
								AND attachment_hide=0 
							LIMIT 
								1";
			$ret_attach = $db->query($sql_attach);
			if ($db->num_rows($ret_attach))
			{
			?>
			<div class="downloads_header"></div>
			<div class="downloads_link">
			<ul>
			 <?php
			// Get the list of video attachments
			$sql_video = "SELECT attachment_id, attachment_title, attachment_orgfilename, attachment_filename, attachment_type, attachment_hide 
							FROM 
								product_attachments 
							WHERE 
								products_product_id = ".$_REQUEST['product_id']."
								AND attachment_hide=0 
								AND attachment_type='Video' 
							ORDER BY 
								attachment_order";
			$ret_video = $db->query($sql_video);
			if ($db->num_rows($ret_video))
			{
			?>
				<li class="dwn_video">Download Video</li>
				<li>
				<ul>
				<?php	
				$cnts = 1;
				while ($row_video = $db->fetch_array($ret_video))
				{
				?>
					<li><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_video['attachment_id']?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_video['attachment_title'])?></a></li>
				<?php
				}
				?>
				</ul>
				</li>
			<?php
			}
			// Get the list of audio attachments
			$sql_audio = "SELECT attachment_id 
							FROM 
								product_attachments 
							WHERE 
								products_product_id = ".$_REQUEST['product_id']."
								AND attachment_hide=0 
								AND attachment_type='Audio' 
							ORDER BY 
								attachment_order";
			$ret_audio = $db->query($sql_audio);
			if ($db->num_rows($ret_audio))
			{
			?>
			<li class="dwn_audio">Download Audio</li>
			<li>
				<ul>
				<?php	
				$cnts = 1;
				while ($row_audio = $db->fetch_array($ret_audio))
				{
				?>
					<li><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_audio['attachment_id']?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_audio['attachment_title'])?></a></li>
				<?php
				}
				?>
				</ul>
			</li>	
			<?php
			}
			// Get the list of pdf attachments
			$sql_pdf = "SELECT attachment_id, attachment_title, attachment_orgfilename, attachment_filename, attachment_type, attachment_hide  
							FROM 
								product_attachments 
							WHERE 
								products_product_id = ".$_REQUEST['product_id']."
								AND attachment_hide=0 
								AND attachment_type='Pdf' 
							ORDER BY 
								attachment_order";
			$ret_pdf = $db->query($sql_pdf);
			if ($db->num_rows($ret_pdf))
			{
			?>
			<li class="dwn_pdf">Download PDF</li>
			<li>
				<ul>
				<?php	
					$cnts = 1;
					while ($row_pdf = $db->fetch_array($ret_pdf))
					{
				?>
					<li><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_pdf['attachment_id']?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_pdf['attachment_title'])?></a></li>
				<?php
					}
				?>
				</ul>
			</li>	
			<?php
			}
		// Get the list of other attachments
		$sql_other = "SELECT attachment_id 
						FROM 
							product_attachments 
						WHERE 
							products_product_id = ".$_REQUEST['product_id']."
							AND attachment_hide=0 
							AND attachment_type='Other' 
						ORDER BY 
							attachment_order";
		$ret_other = $db->query($sql_other);
		if ($db->num_rows($ret_other))
		{
			?>
			<li class="dwn_other">Download Other Attachments</li>
			<li>
				<ul>
				<?php	
				$cnts = 1;
				while ($row_other = $db->fetch_array($ret_other))
				{
				?>
					<li><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_other['attachment_id']?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_other['attachment_title'])?></a></li>
				<?php
				}
				?>
				</ul>
			</li>	
			<?php
			}
			?>
			</ul>
			</div>
			<?php
			}
			?>
			
			</td>
			<td align="left" valign="top" class="pro_details">
			<div class="pro_det_name" ><?php echo stripslashes($row_prod['product_name'])?>
			<?php
				if($_REQUEST['for_notification']==1) // make the decision whether the instock message is to be displayed here or in div
				{
					$this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
				}	
				else // case if displaying the instock notification message here itself
					$this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
			?>
			</div>
			<div class="pro_det_price">
			<?php
				$price_class_arr['ul_class'] 			= 'prodeulprice';
				$price_class_arr['normal_class'] 		= 'productdetnormalprice';
				$price_class_arr['strike_class'] 		= 'productdetstrikeprice';
				$price_class_arr['yousave_class'] 	= 'productdetyousaveprice';
				$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
				
				echo show_Price($row_prod,$price_class_arr,'prod_detail');
				show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
			?>
			</div>
			<div class="pro_stock_div">
			<ul>
			<?php
			// Check whether the product review module is active for the site
			$module_name = 'mod_product_reviews';
			if(in_array($module_name,$inlineSiteComponents))
			{
			?>
			<li class="det_score"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_REVIEW_CAPTION'];?>
			<?php
				for ($i=0;$i<$row_prod['product_averagerating'];$i++)
				{
					echo '<img src="'.url_site_image('reviewstar_on.gif',1).'" border="0" alt="revscoreimg" />&nbsp;'; 
				}
				for ($i=$row_prod['product_averagerating'];$i<10;$i++)
				{
					echo '<img src="'.url_site_image('reviewstar_off.gif',1).'" border="0"  alt="revscoreimg"/>&nbsp;'; 
				}
			?>
			</li>
			<?php	
			}
			if ($Settings_arr['product_show_instock'])
			{
			?>
				<li class="det_stock"><?php echo get_stockdetails($_REQUEST['product_id'])?></li>
			<?php
			}
			if ($Settings_arr['bonus_points_instock'] and $row_prod['product_bonuspoints']>0)
			{
			?>
				<li class="det_bonus"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTS'].' '.$row_prod['product_bonuspoints']?></li>
			<?php
			}
			if ($row_prod['product_deposit']>0 and $row_prod['product_deposit_message']!='')
			{
			?>
				<li class="det_dep"><? echo $Captions_arr['PROD_DETAILS']['PRODDET_DEPOSIT_REQ'] ?> <?php echo ($row_prod['product_deposit']).' '.$Captions_arr['PROD_DETAILS']['DEP_PERCENTAGE']?> </li>
				<li class="det_depmsg"><?php echo nl2br(stripslashes($row_prod['product_deposit_message']))?></li>
			<?php
			}
			?>
			</ul>    
			</div>
			<?php
			// Showing the bulk discount details
			$this->show_BulkDiscounts();
			// Show the product variables and product messages
			$var_listed = $this->show_ProductVariables($row_prod,'row');
			// Showing the various buttons
			$this->show_buttons($row_prod);
			
			if($var_listed)
				$mtd = 'row';
			else
				$mtd = 'col';
			// Show the product label details
			$this->show_ProductLabels($mtd);

		  // Section which decides which all section to be displayed 
					 
			$email_show 		= 0;
			$favourite_show		= 0;
			$writereview_show	= 0;
			$readreview_show	= 0;
			$pdf_show			= 0;
			$compare_show		= 0;
			if(isProductCompareEnabledInProductDetails())
			{
				$def_cat_id = $row_prod['product_default_category_id'];
				$sql_comp = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
												a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
												a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
												a.product_total_preorder_allowed,a.product_applytax,product_shortdesc,
												a.product_stock_notification_required,a.product_alloworder_notinstock    
											FROM 
												products a,product_category_map b 
											WHERE 
												b.product_categories_category_id=".$def_cat_id." 
												AND a.product_id <>".$_REQUEST['product_id']." 		
												AND a.sites_site_id=$ecom_siteid 
												AND a.product_id = b.products_product_id  	
											ORDER BY 
												b.product_order 
											LIMIT 
												1";
				$ret_comp_prod = $db->query($sql_comp);
				if($db->num_rows($ret_comp_prod))
				{
					$compare_show = 1; // compare link
				}	
			}	
			if(in_array('mod_emailafriend',$inlineSiteComponents) and $Settings_arr['proddet_showemailfriend']==1)
				$email_show = 1; // email a friend link
			if($Settings_arr['proddet_showwritereview']==1)
				$writereview_show = 1;	// write review link
			if($Settings_arr['proddet_showreadreview']==1)
				$readreview_show = 1; // read review link
			if(in_array('mod_downloadpdf',$inlineSiteComponents) and $Settings_arr['proddet_showpdf']==1)
				$pdf_show				= 1;	 // pdf download link
			if($cust_id)
			{
				if($Settings_arr['proddet_showfavourite']==1)
					$favourite_show = 1;
			}	
			 // Check whether the div to show the features to be displayed
		 if(($email_show==1) or ($favourite_show==1) or ($writereview_show==1) or ($readreview_show==1) or ($pdf_show==1) or ($compare_show==1)) 
		 {
			?>
			
			<div class="pro_links_div">
			<ul>
			<?php
				if( $compare_show==1)
				{
					$def_cat_id = $row_prod['product_default_category_id'];
				
				?>
					<li><a href="<?php url_productcompare($_REQUEST['product_id'],$row_prod['product_name'])?>"  class="productdetailslink"><? echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_LINK'] ?></a></li>
				<?php
				}
				if($favourite_show==1) // ** Show the add to favourite only if logged in 
				{
					$sql_prod = "SELECT products_product_id FROM customer_fav_products WHERE sites_site_id=$ecom_siteid AND products_product_id=".$_REQUEST['product_id']." AND customer_customer_id=$cust_id LIMIT 1";
					$ret_num= $db->query($sql_prod);
					if($db->num_rows($ret_num)==0) 
					{ 
					?>
						<li><a href="#" class="productdetailslink" onclick="if(confirm('<? echo $Captions_arr['PROD_DETAILS']['PRODDET_ADD_CONFIRM'] ?>')){document.frm_proddetails.fpurpose.value='add_favourite';document.frm_proddetails.submit();}"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE'];?></a></li>
					<?php
					}
					else
					{
					?>
						<li><a href="#" class="productdetailslink" onclick="if(confirm('<? echo $Captions_arr['PROD_DETAILS']['PRODDET_REM_CONFIRM'] ?>')){document.frm_proddetails.fpurpose.value='remove_favourite';document.frm_proddetails.submit();}"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE'];?></a></li>
					<?php
					}
				}
				if($email_show==1) // Check whether the email a friend module is there for current site
				{
				?>
					<li><a href="<?php url_link('emailafriend'.$_REQUEST['product_id'].'.html')?>" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND'];?></a></li>
				<?php
				}
				if(in_array('mod_product_reviews',$inlineSiteComponents)) // Check whether the product review module is there for current site
				{ 
					if($writereview_show==1)
					{
					?>
						<li><a href="<?php url_link('writeproductreview'.$_REQUEST['product_id'].'.html')?>" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_WRITEREVIEW'];?></a></li>
					<?php
					}
					if($readreview_show==1)
					{
					?>	
						<li><a href="<?php url_link('readproductreview'.$_REQUEST['product_id'].'.html')?>" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_READREVIEW'];?></a></li>
					<?php
					}
				}
				if($pdf_show==1) // Check whether the download pdf module is there for current site
				{  
				?>
					<li><a href="<?=url_Prod_PDF($_REQUEST['product_id'],$_REQUEST['category_id'],$row_prod['product_name'])?>" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADPDF'];?></a></li>
				<?php
				}
			?>	
			</ul>
			</div>
		<?php
		}
		
		// Tabs
		$tabs_arr		= $tabs_cont_arr	= array();
		if($row_prod['product_longdesc'])
			$tabs_content_arr[0]	= stripslashes($row_prod['product_longdesc']);
		elseif ($row_prod['product_shortdesc'])
			$tabs_content_arr[0]	= stripslashes($row_prod['product_shortdesc']);
		if (count($tabs_content_arr))
			$tabs_arr 		= array ('PRODDET_OVERVIEW'=>'Overview');
							
		// Get the list of tabs for current product
		$sql_tab = "SELECT tab_id,tab_title,tab_content 
						FROM 
							product_tabs 
						WHERE 
							products_product_id = ".$_REQUEST['product_id']."
							AND tab_hide=0 
						ORDER BY 
							tab_order"
					;
		$ret_tab = $db->query($sql_tab);
		if($db->num_rows($ret_tab))
		{
			while ($row_tab = $db->fetch_array($ret_tab))
			{
				$tabs_arr[$row_tab['tab_id']]			= stripslashes($row_tab['tab_title']);
				$tabs_content_arr[$row_tab['tab_id']]	= stripslashes($row_tab['tab_content']);
			}
		}
		if (count($tabs_arr))
		{
		?>	
			<div class="pro_details_div"><a href="#" name="protabs"></a>
			<div class="pro_det_header" >
			<ul class="pro_details_header">
			 <?php
				$curtab 		= ($_REQUEST['prod_curtab'])?$_REQUEST['prod_curtab']:0;
				$prodimgdetid 	= (!$_REQUEST['prodimgdet'])?0:$_REQUEST['prodimgdet'];
				foreach($tabs_arr as $k_tabid=>$v_tabtitle)
				{
					$sel = ($k_tabid == $curtab)?'class="selected_link"':'class="link_details"';
					if ($k_tabid=='PRODDET_OVERVIEW')
					{
						$k_tabid = 0;
						if ($_REQUEST['prodimgdet']=='')
						{
							$pass_url		= url_product($row_prod['product_id'],$row_prod['product_name'],1);
						}
						elseif ($prodimgdetid!=0)
						{
							//$pass_url		= 'http://'.$ecom_hostname.'/p'.$row_prod['product_id'].'-'.$prodimgdetid.'-'.$k_tabid.'/'.strip_url($row_prod['product_name']).'/'.strip_url($v_tabtitle).'.html#protabs';
							$pass_url		= url_product_tabs($row_prod['product_id'],$row_prod['product_name'],$prodimgdetid,$k_tabid,'#protabs',1);
						}
						else
						{
							$pass_url		= url_product($row_prod['product_id'],$row_prod['product_name'],1);
						}
					}
					else
					{
						//$pass_url		= 'http://'.$ecom_hostname.'/p'.$row_prod['product_id'].'-'.$prodimgdetid.'-'.$k_tabid.'/'.strip_url($row_prod['product_name']).'/'.strip_url($v_tabtitle).'.html#protabs';
						$pass_url		= url_product_tabs($row_prod['product_id'],$row_prod['product_name'],$prodimgdetid,$k_tabid,'#protabs',1);
					}	
					if($sel=='class="link_details"')
					{
 ?>
						<li <?php echo $sel?> onclick = "link_submit('<?php echo $k_tabid?>','<?php echo $_REQUEST['prodimgdet']?>','<?php echo $pass_url?>',0)"><a href="<?php echo $pass_url?>" class="tablink" title="<?php echo $v_tabtitle?>"><?php echo $v_tabtitle?></a></li>
<?php
					}
					else
					{
?>
						<li <?php echo $sel?>><?php echo $v_tabtitle?></li>
<?php		
					}
				}
?>
			</ul>
			</div>
			<div class="pro_details_content">
			<?php 	echo $tabs_content_arr[$curtab]; ?>			
			</div>
			</div>
		<?php
		}
		// Showing the sizechart section		
		$this->show_Sizechart();
		?>	
		</form>
		<?php
			// ** Check whether any linked products exists for current product
			$sql_linked = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
							a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
							a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
							a.product_total_preorder_allowed,a.product_applytax,product_shortdesc,
							a.product_stock_notification_required,a.product_alloworder_notinstock    
							FROM 
								products a,product_linkedproducts b 
							WHERE 
								b.link_parent_id=".$_REQUEST['product_id']." 
								AND a.sites_site_id=$ecom_siteid 
								AND a.product_id = b.link_product_id 
								AND b.link_hide=0
							ORDER BY 
								b.link_order";
			$ret_linked = $db->query($sql_linked);
			if ($db->num_rows($ret_linked))
			{
				$this->Show_Linked_Product($ret_linked);
			}
		?>
			</td>
			
<?php	
		}
		
		// ** Function to show the details of products which are linked with current product.
		function Show_Linked_Product($ret_prod)
		{
			global $ecom_siteid,$db,$Captions_arr,$Settings_arr;
		?>
			<div class="pro_linkprod_div"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_PEOPLE_INTERESTED'];?></div>
			
			
			<?php
			// Calling the function to get the type of image to shown for current 
			$pass_type = get_default_imagetype('prodcat');
			$prod_compare_enabled = isProductCompareEnabled();
				while($row_prod = $db->fetch_array($ret_prod))
				{
			?>
				<div class="product_list_main">
										<ul>
												<li> <h1 class="pro_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li>
													<li><h1><div class="list_img" align="center">
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
													<?php
														// Calling the function to get the image to be shown
														$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
														
														if(count($img_arr))
														{
															show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
														}
														else
														{
															// calling the function to get the default image
															$no_img = get_noimage('prod',$pass_type); 
															if ($no_img)
															{
																show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
															}	
														}
															
													?>
													</a>
													</div></h1>
													</li>
													<? 
												$price_class_arr['ul_class'] 		= '';
												$price_class_arr['normal_class'] 	= 'pro_price_offer';
												$price_class_arr['strike_class'] 	= 'pro_price';
												$price_class_arr['yousave_class'] 	= 'pro_price_offer';
												$price_class_arr['discount_class'] 	= 'pro_price_dis';
											
												echo show_Price($row_prod,$price_class_arr,'cat_detail_1'); 
												
												$frm_name = uniqid('catdet_');
												$prefix = '<li>';
												$suffix = '</li>';
												show_excluding_vat_msg($row_prod,'vat_div',$prefix,$suffix);// show excluding VAT msg
												show_bonus_points_msg($row_prod,'bonus_point',$prefix,$suffix); // Show bonus points

											 if($prod_compare_enabled)  { 
											
												dislplayCompareButton($row_prod['product_id'],$prefix,$suffix);
											
											 }
											 ?>
											 <li>
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													
													<label><?php show_moreinfo($row_prod,'product_info')?></label>
													<? 
													$prefix = "<DIV class='product_list_button_list'><label>"; 
													$suffix = "</label> </DIV>";
													
															$class_arr 					= array();
															$class_arr['ADD_TO_CART']	= 'product_list_button';
															$class_arr['PREORDER']			= 'product_list_button';
															$class_arr['ENQUIRE']			= 'product_list_button';
															show_addtocart($row_prod,$class_arr,$frm_name,false,$prefix,$suffix)
														?>
														
													</form>
											</li>
											</ul>
											</div>
			<?php	
				}
		}
		// ** Function to show the list of products to be compared with current product.
		function Show_Compare_Product($ret_prod)
		{
				global $ecom_siteid,$db,$Captions_arr,$Settings_arr,$ecom_themename,$components;
				$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		?>
			<form method="post" action="" name="frm_proddet_comp" id="frm_proddet_comp" target="_blank">
			<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
			<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
			<input type="hidden" name="fpurpose" value="proddet_compare" />
			<input type="hidden" name="detcomp_prods" id="detcomp_prods" value=""/>
			</form>
			<script type="text/javascript">
				function handle_proddet_compare()
				{
					var def_prodid 		= '<?php echo $_REQUEST['product_id']?>';
					var comp_chkbox 	= document.getElementsByTagName('input');
					var checked_comp	= '';
					var totcnt				= 0;
					var maxcnt		= '<?php echo $Settings_arr['no_of_products_to_compare']?>';
					for(i=0;i<comp_chkbox.length;i++)
					{
						if(comp_chkbox[i].name.substr(0,15)=='chkproddet_comp')
						{
							if(comp_chkbox[i].checked)
							{
								if(checked_comp!='')
									checked_comp = checked_comp + ',';
								checked_comp = checked_comp + comp_chkbox[i].value;
								totcnt++;
							}	
						}
					}
					if (checked_comp=='')
						alert('<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPARING'];?>'); 
					else if(totcnt>maxcnt) 
						alert('<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_SORRY_COMPARING'];?>'+(maxcnt)+' <?php echo $Captions_arr['PROD_DETAILS']['PRODDET_SORRY_COMPARING_TIME'];?>'); 
					else
					{
						checked_comp = def_prodid+','+checked_comp;
						document.frm_proddet_comp.detcomp_prods.value = checked_comp;
						document.frm_proddet_comp.submit();
					}
				}
			</script>
			<tr>
			<td align="left" valign="top" class="maintable_inner_out">&nbsp;</td>
			<td align="left" valign="top" class="pro_det_comp">
			
			<?php
					// Decide whether to display the top menu or not
					if(get_session_var('ecom_login_customer'))
					{
						include "themes/$ecom_themename/modules/mod_topmenu.php";
					}
			?>
			<div class="pro_det_treemenu"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_HEAD'];?></div>
			<div class="det_message" align="center"><?php  $msg = $Captions_arr['PROD_DETAILS']['PRODDET_MAX_COMP_PROD']; $msg = str_replace('[prodno]',$Settings_arr['no_of_products_to_compare'],$msg); echo $msg?></div>
			<div class="compare_nav" align="right">
				<input type="button" name="prodet_backprod" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_BACK_TO_DETAIL'];?>" class="buttonred_cart" onclick="window.location='<?php url_product($_REQUEST['product_id'],'',-1)?>'"/>
				<input type="button" name="prodet_comparebutton" value="<?php echo $Captions_arr['PROD_DETAILS']['COMPARE_PRODUCTS'];?>" class="buttonred_cart" onclick="handle_proddet_compare()"/>
			</div>
			<?php
				// Calling the function to get the type of image to shown for current 
				$pass_type = get_default_imagetype('link_prod');
				while($row_prod = $db->fetch_array($ret_prod))
				{
					$compare_checked = '';
					if(is_array($_SESSION['compare_products']))
					{
						if(in_array($row_prod['product_id'],$_SESSION['compare_products']))
						{
							$compare_checked = 'checked="checkeed"';
						}
					}	
			?>
					<div class="product_list_main">
					<ul>
								<li> <h1 class="pro_name_compare"><input type="checkbox" name="chkproddet_comp_<?php echo $row_prod['product_id']?>" id="chkproddet_comp_<?php echo $row_prod['product_id']?>" value="<?php echo $row_prod['product_id']?>" <?php echo $compare_checked?>/><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li>
								<li><h1><div class="list_img" align="center">
								<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
								<?php
								
									// Calling the function to get the image to be shown
									$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
									
									if(count($img_arr))
									{
										show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
									}
									else
									{
										// calling the function to get the default image
										$no_img = get_noimage('prod',$pass_type); 
										if ($no_img)
										{
											show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
										}	
									}
								?>
								</a>
								</div></h1>
								</li>
								<? 
							$price_class_arr['ul_class'] 		= '';
							$price_class_arr['normal_class'] 	= 'pro_price_offer';
							$price_class_arr['strike_class'] 	= 'pro_price';
							$price_class_arr['yousave_class'] 	= 'pro_price_offer';
							$price_class_arr['discount_class'] 	= 'pro_price_dis';
						
							echo show_Price($row_prod,$price_class_arr,'cat_detail_1'); 
						
							$frm_name = uniqid('catdet_');
							$prefix = '<li>';
							$suffix = '</li>';
							show_excluding_vat_msg($row_prod,'vat_div',$prefix,$suffix);// show excluding VAT msg
							show_bonus_points_msg($row_prod,'bonus_point',$prefix,$suffix); // Show bonus points
						 ?>
						 <li>
							<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls">
								<input type="hidden" name="fpurpose" value="" />
								<input type="hidden" name="fproduct_id" value="" />
								<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
								<label><?php show_moreinfo($row_prod,'product_info')?></label>
								<? 
								$prefix = "<DIV class='product_list_button_list'><label>"; 
								$suffix = "</label> </DIV>";
								$class_arr 					= array();
								$class_arr['ADD_TO_CART']	= 'product_list_button';
								$class_arr['PREORDER']			= 'product_list_button';
								$class_arr['ENQUIRE']			= 'product_list_button';
								show_addtocart($row_prod,$class_arr,$frm_name,false,$prefix,$suffix)
								?>
							</form>
						</li>
					</ul>
				</div>
			<?php
				}
			 /*?><div class="compare_nav" align="right">
				<input type="button" name="prodet_backprod" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_BACK_TO_DETAIL'];?>" class="buttonred_cart" onclick="window.location='<?php url_product($_REQUEST['product_id'],'',-1)?>'"/>
				<input type="button" name="prodet_comparebutton" value="<?php echo $Captions_arr['PROD_DETAILS']['COMPARE_PRODUCTS'];?>" class="buttonred_cart" onclick="handle_proddet_compare()"/>
			</div> <?php */?>
			</td>
		<?php
	}
		
		// ** Function to display the variables. This is written as function to avoid code repetation in various if conditions
		function show_ProductVariables($row_prod,$pos='column')
		{
			global $db,$ecom_siteid,$Captions_arr;
			$i = 0;
			// ######################################################
			// Check whether any variables exists for current product
			// ######################################################
			$sql_var = "SELECT var_id,var_name,var_value_exists, var_price 
								FROM 
									product_variables 
								WHERE 
									products_product_id = ".$_REQUEST['product_id']." 
									AND var_hide= 0
								ORDER BY 
									var_order";
			$ret_var = $db->query($sql_var);
			$var_cnt = $db->num_rows($ret_var);
			// ##############################################################################
			//  Check whether variable message exists for the product
			// ##############################################################################
			$sql_msg = "SELECT message_id,message_title,message_type 
							FROM 
								product_variable_messages 
							WHERE 
								products_product_id = ".$_REQUEST['product_id']." 
								AND message_hide= 0
							ORDER BY 
								message_order";
			$ret_msg = $db->query($sql_msg);
			
			// Check whether total number of variables is 1 or more than 1
			if($var_cnt==1)
			{
				$vardisp_type = $row_prod['product_variable_display_type']; // take the display type from settings for current product
			}
			else 
				$vardisp_type = 'ADD'; // if the variable count is > 1 then by default the Add option will be displayed
			
			if ($db->num_rows($ret_var) or $db->num_rows($ret_msg))
			{
		  ?>
		  		<div class="pro_variable_div">
						<?php
						// Case of variables
						if ($db->num_rows($ret_var) or $db->num_rows($ret_msg))
						{
							while ($row_var = $db->fetch_array($ret_var))
							{
								if ($row_var['var_value_exists']==1)
								{
									// check whether values exists current variable
									$sql_vals = "SELECT var_value_id, var_addprice,var_value 
													FROM 
														product_variable_data 
													WHERE 
														product_variables_var_id =".$row_var['var_id']." 
													ORDER BY 
														var_order";
									$ret_vals = $db->query($sql_vals);
									if ($db->num_rows($ret_vals))
									{
										$var_Proceed = true;
									}
								}
								else
									$var_Proceed = true;
								if ($var_Proceed)// Show the variable if it is valid to show
								{
									$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
									$var_exists = true;
						?>
								<ul>
								<li><?php echo stripslashes($row_var['var_name'])?></li>
								<li>
								<?php
										if ($row_var['var_value_exists']==1)
										{
										?>
												<select name="var_<?php echo $row_var['var_id']?>">
												<?php 
													while ($row_vals = $db->fetch_array($ret_vals))
													{
												?>
														<option value="<?php echo $row_vals['var_value_id']?>" <?php echo ($_REQUEST['var_'.$row_var['var_id']]==$row_vals['var_value_id'])?'selected':''?>><?php echo stripslashes($row_vals['var_value'])?><?php echo Show_Variable_Additional_Price($row_prod,$row_vals['var_addprice'],$vardisp_type)?></option>
												<?php
													}
												?>
												</select>							
										<?php
										}
										else
										{
										?>
											<input type="checkbox" name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" value="1" <?php echo ($_REQUEST['var_'.$row_var['var_id']])?'checked="checked"':''?>/><?php echo Show_Variable_Additional_Price($row_prod,$row_var['var_price'],$vardisp_type)?>
										<?php
										}
								?>
								</li>
								</ul>
						<?php
									$i++;
								}
								
							}
						?>  
						<?php
						}
						// ######################################################
						// End of variables section
						// ######################################################
						
						// ##############################################################################
						//  Case of variable messages
						// ##############################################################################
						
						if ($db->num_rows($ret_msg))
						{
						
								while ($row_msg = $db->fetch_array($ret_msg))
								{
									$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
									$var_exists = true;
							?>
									<ul>
									<li><?php echo stripslashes($row_msg['message_title'])?></li>
									<li>
									<?php
									if ($row_msg['message_type']=='TXTBX')
									{
									?>
										<input type="text" name="varmsg_<?php echo $row_msg['message_id']?>" id="varmsg_<?php echo $row_msg['message_id']?>" value="<?php echo $_REQUEST['varmsg_'.$row_msg['message_id']]?>" />
									<?php
									}
									else
									{
									?>
										<textarea name="varmsg_<?php echo $row_msg['message_id']?>" id="varmsg_<?php echo $row_msg['message_id']?>" rows="3" cols="15"><?php echo $_REQUEST['varmsg_'.$row_msg['message_id']]?></textarea>
									<?php
									}
									?>
									</li>
									</ul>	
							<?php
									$i++;
								}
						}
						// ######################################################
						// End of variable messages
						// ######################################################
			?>
				</div>
			<?php			
			}
			return $var_exists;
		}
		/* Function to show the bulk discount*/
		function show_BulkDiscounts()
		{
			global $db,$ecom_siteid,$Captions_arr;
			// Section to show the bulk discount details
			$bulkdisc_details = product_BulkDiscount_Details($_REQUEST['product_id']);
			if (count($bulkdisc_details['qty']))
			{
			?>	
				<div class="pro_bulk_div">
				<ul>
				<li><?php echo $Captions_arr['PROD_DETAILS']['BULK_DISC_HEAD']?></li>
				<?php
				for($i=0;$i<count($bulkdisc_details['qty']);$i++)
				{
				?>	
				<li><?php echo $bulkdisc_details['qty'][$i].' '.$Captions_arr['PROD_DETAILS']['BULK_FOR'].' '?> 
							<?php echo print_price($bulkdisc_details['price'][$i]).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH']?></li>
				<?php
				}
				?>
				</ul>
				</div>
			<?php
			}
		}
		/* Function to show the instock notification */
		/* Function to show the bulk discount*/
		function show_Instock_msg($row_prod, $stockalert='')
		{
			global $db,$ecom_siteid,$Captions_arr,$ecom_hostname,$Captions_arr;
			$show_notify = false;
			if ($row_prod['product_stock_notification_required']=='Y' and $row_prod['product_alloworder_notinstock']=='N')
			{
				// Check whether variable stock is managed
				if ($row_prod['product_variablestock_allowed']=='N' and $row_prod['product_webstock']==0)
				{
					if ($row_prod['product_preorder_allowed']=='Y' and $row_prod['product_total_preorder_allowed']>0)
					{
						$show_notify = false;
					}
					else
					{
						$show_notify = true;
					}	
				}
				elseif($row_prod['product_variablestock_allowed']=='Y')
				{
					if($_REQUEST['for_notification']==1)
						$show_notify = true;
					else
					{
						if ($row_prod['product_preorder_allowed']=='Y' and $row_prod['product_total_preorder_allowed']>0)
						{
							$show_notify = false;
						}
						else
						{
							/*// Check whether web stock exists for any of the combination for current product. if not then also show the message
							$sql_stk = "SELECT comb_id 
												FROM 
													product_variable_combination_stock 
												WHERE 
													products_product_id = ".$row_prod['product_id']." 
													AND web_stock>0 
												LIMIT 
													1";
							$ret_stk = $db->query($sql_stk);
							if ($db->num_rows($ret_stk)==0) // case if not stock exists for any of the combinations
								$show_notify = true;
								*/
						}		
					}
				}
				if($show_notify) // Check whether notificataion link is to be shown
				{
					if($_REQUEST['for_notification']==1)// case if coming back to the product details page after validation
					{
					?>
							<script type="text/javascript">
								if(document.getElementById('alert_main_div'))
									document.getElementById('alert_main_div').style.display='';
							</script>
						<div  class="div_alert" id="instockmsg_div">
						<div align="right" class="instockmsg_span" ><a href="javascript: hide_instockmsg_div()"><img src="<?php url_site_image('close.gif')?>" border="0" /></a></div>
							<?php echo $Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK']?>.
						 <br />
						<span class="instockmsg_out_stock">
						<?php echo $Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK_EMAIL']?></span>
						<input type="text"   name="stock_email" />
						<input type="hidden" name="prod_mod" value="stock_notify" />
						<input type="hidden" name="hid_notify" value="stock" /> 
						<input type="button" name="stocknotif_submit" value=" Send Request " class="buttongray" onclick=" validate_stocknotify(document.frm_proddetails)"  />
						<input type="hidden" name="product_id" value="<?PHP echo $_REQUEST['product_id']; ?>" />
					<!--	<span style="font-weight:bold;">
						<a href="javascript:handle_instocknotification('<?php// echo $row_prod['product_id']?>','<?php// echo $ecom_hostname?>')" style="color:#000000;text-decoration:underline">click here</a></span>  -->
						</div>
					<?php	
						}
						else
						{
					?>	
						<div  class="alert_inner"><?php echo $Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK']?>.
						<br />
						<span style="font-size:12px;font-weight:normal;color:#000000;"><?php echo $Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK_EMAIL']?><br />

							<input type="text"   name="stock_email" />
							<input type="hidden" name="prod_mod" value="stock_notify" />
						 	<input type="hidden" name="hid_notify" value="stock" /> 
						  	<input type="button" name="stocknotif_submit" value=" Send Request " class="buttongray" onclick=" validate_stocknotify(document.frm_proddetails)"  />
				         	<input type="hidden" name="product_id" value="<?PHP echo $_REQUEST['product_id']; ?>" />
						</span>
						</div>
			<?php
					}		
				}
			}
		}
		
		/* Function to show the lables set for the product */
		function show_ProductLabels($pos='row')
		{
			global $db,$ecom_siteid,$Captions_arr;
			// ** Get the list of all labels set for the site
			$sql_labels = "SELECT a.label_id,a.label_name,b.label_value,a.is_textbox,product_site_labels_values_label_value_id 
							FROM
								product_site_labels a,product_labels b 
							WHERE 
								b.products_product_id = ".$_REQUEST['product_id']." 
								AND a.label_hide = 0 
								AND a.label_id = b.product_site_labels_label_id 
							ORDER BY 
								a.label_order";
			$ret_labels = $db->query($sql_labels);
			$label_arr = array();
			if ($db->num_rows($ret_labels))
			{
				$label_exists = false;
				while ($row_labels = $db->fetch_array($ret_labels))
				{
					$vals = '';
					if ($row_labels['is_textbox']==1)
						$vals = stripslashes($row_labels['label_value']);
					else
					{
						$sql_labelval = "SELECT label_value 
											FROM 
												product_site_labels_values  
											WHERE 
												product_site_labels_label_id=".$row_labels['label_id']." 
												AND label_value_id = ".$row_labels['product_site_labels_values_label_value_id'];
						$ret_labelval = $db->query($sql_labelval);
						if ($db->num_rows($ret_labelval))
						{
							$row_labelval = $db->fetch_array($ret_labelval);
							$vals = stripslashes($row_labelval['label_value']);
						}
												
					}
					if (trim($vals))
					{
						$label_exists = true;	
						$row_labels['label_showvalue'] = $vals;
						$label_arr[]	  = $row_labels;
					}
				}	
		  ?>
		  	<div class="pro_overview_div">
			<ul>
			<li class="overview_header"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']?></li>
				<?php
					$i=1;
					//while ($row_labels = $db->fetch_array($ret_labels))
					foreach($label_arr as $k=>$v)
					{
						$vals = '';
						$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
						$vals = $v['label_showvalue'];
						if ($vals)
						{
						?>
							<li><span><?php echo stripslashes($v['label_name'])?></span>: <span><?php echo $vals?></span></li>
						<?php	
							$i++;
						}
							
					}
			?>
				</ul>
				</div>
			<?php
			}
		}
		function show_buttons($row_prod)
		{
			global $Captions_arr,$showqty,$Settings_arr;
			$cust_id 	= get_session_var("ecom_login_customer");
			$showqty	= $Settings_arr['show_qty_box'];// show the qty box
			if ($row_prod['product_shortdesc']!='')
			{
				$sht_desc_arr = explode('-',$row_prod['product_shortdesc']);
			?>
				<div class="pro_short_div">
					<ul>
					<?php
						//for ($i=0;$i<count($sht_desc_arr);$i++)
					//	{
					?>	
						<li>
							<?php echo stripslashes($row_prod['product_shortdesc'])?>
						</li>	
					<?php
					//}
					?>	
					</ul>	
				</div>
			<?php
			}
			?>
			<div class="pro_buy_div">
			 <ul>
			<?php
			if($showqty==1)// this decision is made in the main shop settings
			{
	?>
			  <li><div class="quantity_details"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY']?><input type="text" class="quainput" name="qty"  value="1" maxlength="2" /></div></li>
	<?php
			}
			// Get the caption to be show in the button
			$caption_key = show_addtocart($row_prod,array(0),'',true);
			if ($caption_key!='') // Check whether the buynow or preorder button is to be displayed
			{
	 ?>
				<li><input name="Submit_buy" type="submit" class="buttonblackbuy" id="Submit_buy" value="<?php echo $Captions_arr['PROD_DETAILS'][$caption_key];?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addcart'" /></li>
		<?php
			}
			// Check whether the enquire link is to be displayed
			if ($row_prod['product_show_enquirelink']==1)
			{
	 ?>			
				<li><input name="Submit_enq" type="submit" class="buttonblackbuy" id="Submit_enq" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Enquire'" /></li>
	<?php
			}
		if($cust_id) // ** Show the wishlist button only if logged in 
		{
  ?>
			<li><input name="submit_wishlist" type="submit" class="buttonblackbuy" id="submit_wishlist" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>"  onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addwishlist'"  /></li>
  <?php
		}	
	?>
		</ul>
		</div>
	<?php	
			return true;
		}
		function show_more_images($row_prod,$exclude_tabid,$exclude_prodid)
		{
				global $db,$ecom_hostname,$ecom_themename;
				$show_normalimage = false;
				$prodimg_arr		= array();
				
				
				/*$pass_type = ($row_prod['productdetail_moreimages_showimagetype']=='Default')?'Icon':$row_prod['productdetail_moreimages_showimagetype'];
				switch($pass_type)
				{
					case 'Icon';
						$pass_type = 'image_iconpath';
						break;
					case 'Thumb';
						$pass_type = 'image_thumbpath';
						break;
					default:
						$pass_type = 'image_thumbpath';
						break;	
				}*/
				
				$pass_type = 'image_iconpath';
				
				if ($_REQUEST['prod_curtab'])// case if came by clicking the tab
				{
					if ($exclude_tabid)
						$prodimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type.',image_iconpath',$exclude_tabid,0);	
					if (count($prodimg_arr)==0) // case if no more tab images exists
					{
						$show_normalimage = true;
					}
				}
				else // case of coming with out tab id, so show the normal image list if any
				{
					$show_normalimage = true;
				}	
				 
				 if ($show_normalimage==true) // the following is to be done only coming for normal image display
				 {
					if ($exclude_prodid)
						$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type.',image_iconpath',$exclude_prodid,0);
	
				 } 
					if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
					{
				?>	
					  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="productdethumbtable">
						<tr>
						<td>
						<ul class="hoverbox">
						<?php
						if ($pass_type=='image_thumbpath') // If the more image type is Thumb then show 3 in a row otherwise show 2 in a row
						{
							$maximg_col 	=1;
							$width				= '100%';
						}	
						else
						{
							$maximg_col = 3;
							$width			= '45px';
						}	
						$curimg_col = 0;
						$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
						foreach ($prodimg_arr as $k=>$v)
						{
							$title = ($v['image_title'])?stripslashes($v['image_title']):$row_prod['product_name'];
						?>
							<li>
							<?php /*?><a href="#" onclick="link_submit('<?php echo $_REQUEST['prod_curtab']?>','<?php echo $v['image_id']?>','<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1)?>',0)" title="<?php echo $title?>"><?php */?>
							<a href="<?php url_root_image($v['image_extralargepath'])?>"  title="<?php echo $title?>" rel='lightbox[gallery]'>
							<?php
								 show_image(url_root_image($v['image_thumbpath'],1),$title,$title,'preview');
							?>
							</a>
							</li>
						<?php
						}
						?>	
						</ul>
						</td>
						</tr>
					  </table>
			  <?php
				}
			}	
			// Function which holds the logic for image swapping using javascript
			function Show_Javascript_Image_Swapper($row_prod)
			{
				global $ecom_hostname,$ecom_themename,$db;
			?>
			<script type="text/javascript">
			
				// JavaScript Document
     			// Globals
				// Major version of Flash
				var installedMajorVersion = 1;
				// Minor version of Flash
				var installedMinorVersion = 0;
				// Minor version of Flash
				var installedRevision = 0;
				// Major version of Flash required
				var requiredMajorVersion = 9;
				// Minor version of Flash required
				var requiredMinorVersion = 0;
				// Minor version of Flash required
				var requiredRevision = 0;
				// Version check based upon the values entered above in "Globals"
				var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
				var hasAnyVersion = DetectFlashVer(installedMajorVersion, installedMinorVersion, installedRevision);
				var oLoader = new Image();
				oLoader.src = '<?php url_site_image('product_loading_spinner.gif')?>';			
				var oZoomImage = new Image();
				var tZoomLoaded;
				var	arrMainImage = new Array();
				var	arrThumbImage = new Array();
				var	arrBigImage = new Array();
				var tmpHolder = new Array();
				var tmpHolderBig = new Array();
				var firstTime360 = true;
			</script>
			<?php
				$show_normalimage = false;
				if ($_REQUEST['prod_curtab'])
				{
					if ($_REQUEST['prodimgdet'])	
						$showonly = $_REQUEST['prodimgdet'];
					else
						$showonly = 0;
					// Calling the function to get the type of image to shown for current 
					$pass_type = get_default_imagetype('proddet');
					// Calling the function to get the image to be shown
					$tabimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type.', image_bigcategorypath,image_thumbcategorypath',0,$showonly,1);
					if(count($tabimg_arr))
					{
						$exclude_tabid 	= $tabimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
						$main_img			=  url_root_image($tabimg_arr[0]['image_bigpath'],1);
						$large_img			=  url_root_image($tabimg_arr[0]['image_extralargepath'],1);
						$icn_img			= url_root_image($tabimg_arr[0]['image_thumbcategorypath'],1);
						$lnk = "<img src='".url_site_image('gallery.gif',1)."' alt='Gallery' border='0'>" ;
						$light_var .= "<a href=\"".$large_img."\" rel=\"lightbox[gallery]\" style='display:block;border:1px solid #000000;background-color:#000000; padding-right:2px'>$lnk</a>";
						?>
						<script type="text/javascript">
							arrMainImage[0] = new Array("<?php echo $icn_img?>", "<?php echo $main_img	?>", "<?php echo $large_img	?>","<?php echo $main_img	?>");
						</script>
						<?php
						//show_image(url_root_image($tabimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
						?>
						<?php
						$show_noimage 	= false;
					}
					else
						$show_normalimage = true;
				}
				else
					$show_normalimage = true;
					
				if ($show_normalimage)
				{
					if ($_REQUEST['prodimgdet'])	
						$showonly = $_REQUEST['prodimgdet'];
					else
						$showonly = 0;
					// Calling the function to get the type of image to shown for current 
					$pass_type = get_default_imagetype('proddet');
					// Calling the function to get the image to be shown
					$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type.', image_bigcategorypath,image_thumbcategorypath',0,$showonly,1);
					if(count($prodimg_arr))
					{
						$exclude_prodid = $prodimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
						$main_img			=  url_root_image($prodimg_arr[0]['image_bigpath'],1);
						$large_img			=  url_root_image($prodimg_arr[0]['image_extralargepath'],1);
						$icn_img			= url_root_image($prodimg_arr[0]['image_thumbcategorypath'],1);
						$lnk = "<img src='".url_site_image('gallery.gif',1)."' alt='Gallery' border='0'>" ;
						$light_var .= "<a href=\"".$large_img."\" rel=\"lightbox[gallery]\" style='display:block;border:1px solid #000000;background-color:#000000; padding-right:2px'>$lnk</a>";
						?>
						<script type="text/javascript">
							arrMainImage[0] = new Array("<?php echo $icn_img?>", "<?php echo $main_img?>", "<?php echo $large_img?>", "<?php echo $main_img?>");
						</script>
						<?php
						//show_image(url_root_image($prodimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
						$show_noimage 	= false;
					}
					else
					{	
						// calling the function to get the default no image 
						$no_img = get_noimage('prod','big'); 
						if ($no_img)
						{
						?>
							<script type="text/javascript">
								arrMainImage[0] = new Array("<?php echo $no_img?>", "<?php echo $no_img?>", "<?php echo $no_img?>" , "<?php echo $no_img?>");
							</script>
						<?php
							//show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
							
						}	
					}
				}
				// Finding the thumb images
				$pass_type = 'image_thumbcategorypath';
				
				if ($_REQUEST['prod_curtab'])// case if came by clicking the tab
				{
					if ($exclude_tabid)
						$prodthumbimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type.',image_thumbpath,image_bigcategorypath',$exclude_tabid,0,0,'rand()');	
					if (count($prodthumbimg_arr)==0) // case if no more tab images exists
					{
						$show_normalimage = true;
					}
				}
				else // case of coming with out tab id, so show the normal image list if any
				{
					$show_normalimage = true;
				}	
				 
				 if ($show_normalimage==true) // the following is to be done only coming for normal image display
				 {
					if ($exclude_prodid)
						$prodthumbimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type.',image_thumbpath,image_bigcategorypath',$exclude_prodid,0,0,'rand()');
	
				 }
				 if (count($prodthumbimg_arr))
				 {
				 		$i = 0;
				 		foreach ($prodthumbimg_arr as $k=>$v)
						{
							$icon_arr[$i] 	= url_root_image($v['image_thumbcategorypath'],1);
							$bg_img			= url_root_image($v['image_bigpath'],1);
							$large_img		= url_root_image($v['image_extralargepath'],1);
				?>
								<script type="text/javascript">
									arrThumbImage[<?php echo $i?>] = new Array("<?php echo $icon_arr[$i]?>", "<?php echo $bg_img	?>", "<?php echo $large_img?>", "<?php echo $icon_arr[$i]?>");
								</script>
				<?php		
						
							/*if ($i==0)
								$lnk = "<img src='".url_site_image('gallery.gif',1)."' alt='Gallery' border='0'>" ;
							else*/
								$lnk = '';
							$light_var .= "<a href=\"".$large_img."\" rel=\"lightbox[gallery]\" style='display:block;border:1px solid #000000;background-color:#000000; padding-right:2px'>$lnk</a>";
							$i++;
						}
				 }
			?>
				<script type="text/javascript">
					for (i=0; i < arrThumbImage.length; i++) 
					{
						var preload = new Image();
						preload.src = arrThumbImage[i][1];
						preload.src = arrThumbImage[i][2];
					}
					for (i=0; i < arrMainImage.length; i++)
					{
						var preload = new Image();
						preload.src = arrMainImage[i][1];
					}	
				</script>
				<table border="0" cellpadding="0" cellspacing="0" class="content_product_table">
				<tr>
					<td align="left" valign="top" class="content_product_td">
					<div class="content_product_container">
						<div class="content_product_images">
							<div >
								<div style="display: block;" id="pnlMainImage">
								<div id="dvMainImageZoom" class="content_product_images_mainimage_zoom" style="display: none;" align="left" title="Click and drag image"> <img id="imgMainImageZoom" src="" alt="Click and drag image" style='border:0' title="Click and drag image"> </div>
								<div style="height:370px" id='temp_div'>
								<a id="hypMainImage" href="javascript:ShowZoomImage();" style="display: block;clear:both;" onmouseover="window.status='Click on images to enlarge them'; return true;" onmouseout="window.status=''; return true;" title="<?php echo $row_prod['product_name']?>"> <img id="imgMainImage" class="content_product_images_mainimage" src="<?php echo $main_img?>" alt="<?php echo $row_prod['product_name']?>" style="border-width: 0px;" > </a>
								</div>
                                <div style="border:1px solid #000000;border-top:none;height:18px;overflow:hidden;">
								<a  style="display: block;float:left; width:24%" id="hypZoomPlus" href="javascript:ShowZoomImage();"> <img src="<?php url_site_image('product_ZoomPlus.gif')?>" alt="Zoom In" border="0"></a>
								 <a style="display: none;float:left; width:24%" id="hypZoomMinus" href="javascript:HideZoomImage();"> <img src="<?php url_site_image('product_ZoomMinus.gif')?>" alt="Zoom Out" border="0"></a>
								<a id="hypDragImage" style="border-width: 0px;float:left;width:70%;display: block;overflow:hidden"> <img src="<?php echo url_site_image('product_clickndrag.gif')?>" alt="Click and drag the zoomed image" style="border-width: 0px;"> </a>
                               
								<?php 
									if($row_prod['product_flv_filename']!='') // make the 
									{
								?>
									<a id="hypVideoImage" href="javascript:ShowVideo();" style="border-width: 0px;float:left;width:70%; overflow:hidden"> <img src="<?php url_site_image('product_ViewCatwalk.gif')?>" alt="Click here to view the video" style="border-width: 0px; "></a> 
								<?php
									}
									else
										echo '<div style="float:left;width:70%; overflow:hidden"><img src="'.url_site_image('blank.gif',1).'" border="0" id="img_blank"/></div>';
								?>	
								</div>
                                 </div>
								<?php 
									if($row_prod['product_flv_filename']!='')
									{
								?>
										<div style="display: none; height:370px" id="divFlash" class="content_product_flash">
										 <script type="text/javascript" src="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/scripts/swfobject.js"></script>
										<script type="text/javascript">
		var s1 = new SWFObject("http://<?php echo $ecom_hostname?>/themes/<?php echo $ecom_themename?>/swf/player.swf","ply","260","370","9","#FFFFFF");
		s1.addParam("allowfullscreen","true");
		s1.addParam("allowscriptaccess","always");
		s1.addParam("flashvars","file=http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/product_flv/<?php echo $row_prod['product_flv_filename']?>");
		s1.write("divFlash");
	</script>
										</div>
								<div style="border:1px solid #000000;border-top:none;height:18px;display:none;overflow:hidden;" id="flashbrd">
									
										<div id="pnlVideoControl"><img id="blank_zoom" src="<?php url_site_image('zoom_blank.gif')?>" alt="" style='display: none;float:left; width:24%'> <a id="hypPhotoImage" href="javascript:HideVideo();Hideflshr()" style="display: none;float:left; width:70%;overflow:hidden"> <img src="<?php url_site_image('product_ViewPictures.gif')?>" alt="Click here to go back to images" style="border-width: 0px;"> </a>
										</div>
                                        
                                            </div>
								 <?php
									}
								?>
							  </div>
								
							</div>
					  </div>
					    </div>
					
					</td>
				</tr>
				<tr>
				<td align="center">
					<div id="pnlThumbImages" class="content_product_images_thumbimages">
						<ul class="hoverbox" >
						<?php
							for($i=0;$i<count($icon_arr);$i++)
							{
						?>	
							 <li><a href="javascript:ReplaceImageFromThumb(<?php echo ($i+1)?>);Hideflshr()" onmouseover="window.status='Click on images to enlarge them'; return true;" onmouseout="window.status=''; return true;" title="Click on images to enlarge them"> <img id="imgThumb<?php echo ($i+2)?>" src="<?php echo $icon_arr[$i]?>" alt="Click on images to enlarge them" style="border-width: 1px;"></a>
							 </li>
						<?php
							}
						?> 
						</ul>
					</div>
				</td>
				</tr>
				<tr>
				<td align="right" style="padding:5px 0;"><?php echo $light_var?>
				</td></tr>
				</table>
				<span id="content_product_loading" style="display:none;"><span id="container"><img src="<?php url_site_image('product_loading_spinner.gif')?>" alt="Loading..." style="margin-left:50%; margin-top:50%;"></span></span>	
				<script language="JavaScript">LoadTmpHolder()</script>
			<?php
			}
		/*	function Show_StockNotify($id,$alert) 
			{
				global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					 $ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$alert,$succmsg;
					 
					$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
					// Get the name of current product
					$sql_prod= "SELECT product_name 
										FROM 
											products 
										WHERE 
											product_id = $id 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
					$ret_prod = $db->query($sql_prod);
					if ($db->num_rows($ret_prod))
					{
						$row_prod = $db->fetch_array($ret_prod);
						$pname		= stripslashes($row_prod['product_name']);
					}	
			?>		 
				<form action="http://<?php echo $ecom_hostname?>/stocknotify<?php echo $id?>.html" name="frm_stockNotify" id="frm_stockNotify" method="post" onsubmit="return validate_stocknotify(this)">	 
				<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <a href="<? url_product($id,$pname);?>"><?php echo $pname?></a> >> Instock Notification Request</div>
				<table width="100%" border="0" cellspacing="0" cellpadding="8" class="regitable">
				 <?php
				 	
	 				if($alert)
					{
				?>
				  <tr>
					<td colspan="2" align="center"  class="errormsg">
				<?php	
						echo $Captions_arr['PROD_DETAILS'][$alert];
					?>
					</td>
			      </tr>
				<? 
				 	 } 
				?>
				 		<tr>
						<td align="left" class="regifontnormal" colspan="2">Please fill in the following details </td>
						</tr>
					   <tr>
						<td width="24%" align="left" class="regiconent">&nbsp;Title <span class="redtext">*</span></td>
					    <td width="76%" align="left" class="regiconent"><select name="sel_title">
						<option value="Mr" <?PHP if($_REQUEST['sel_title']=='Mr') { ?>  selected="selected" <? } ?>> Mr </option>
					    <option value="Mrs" <?PHP if($_REQUEST['sel_title']=='Mrs') { ?>  selected="selected" <? } ?>> Mrs </option>
						<option value="M/S." <?PHP if($_REQUEST['sel_title']=='M/S.') { ?>  selected="selected" <? } ?>> M/S. </option>
			             </select>				        </td>
				      </tr>
					   <tr>
					     <td align="left" class="regiconent">First Name <span class="redtext">*</span></td>
					     <td align="left" class="regiconent"><input type="text" name="fstname" id="fstname" size="40" value="<?PHP echo $_REQUEST['fstname']; ?>" /></td>
			      </tr>
					   <tr>
					     <td align="left" class="regiconent">Middle Name </td>
					     <td align="left" class="regiconent"><input type="text" name="midname" id="midname" size="40" value="<?PHP echo $_REQUEST['midname']; ?>" /></td>
			      </tr>
					   <tr>
					     <td align="left" class="regiconent">Last Name <span class="redtext">*</span></td>
					     <td align="left" class="regiconent"><input type="text" name="lastname" id="lastname" size="40" value="<?PHP echo $_REQUEST['lastname']; ?>" /></td>
			      </tr>
					   <tr>
					     <td align="left" class="regiconent">Email <span class="redtext">*</span></td>
					     <td align="left" class="regiconent"><input type="text" name="email" id="email" size="40" value="<?PHP echo $_REQUEST['email']; ?>" /></td>
			      </tr>
					   <tr>
					     <td align="left" class="regiconent" valign="top">Contact number </td>
					     <td align="left" class="regiconent"><input type="text" name="phone" id="phone" size="40" value="<?PHP echo $_REQUEST['phone']; ?>" /></td>
			      </tr>
					   <tr>
					     <td align="left" class="regiconent" valign="top">Comments</td>
					     <td align="left" class="regiconent"><textarea name="comments" cols="40" rows="6"><?PHP echo $_REQUEST['comments']; ?></textarea></td>
			      </tr>
					   <tr>
					     <td align="right" class="regiconent">&nbsp;</td>
					     <td align="left" class="regiconent">&nbsp;</td>
			      </tr>
					   <tr>
					     <td align="right" >&nbsp;</td>
					     <td align="left"   valign="middle">
						 <input type="submit" name="stocknotif_submit" value=" Send Request " class="buttongray" />
				         <input type="hidden" name="product_id" value="<?PHP echo $_REQUEST['product_id']; ?>" />
						 <input type="hidden" name="pass_combid" value="<?PHP echo $_REQUEST['pass_combid']; ?>" />
				         <input type="hidden" name="prod_mod" value="stock_notify" />
						 <input type="hidden" name="hid_notify" value="stock" />
						 
						 <?php
						 	$rem_arr = array('sel_title','fstname','midname','lastname','email','phone','comments','stocknotif_submit','product_id','pass_combid','prod_mod','hid_notify');
						 	foreach ($_REQUEST as $k=>$v)
							{
								if(!in_array($k,$rem_arr))
								{
							?>
								<input type="hidden" name="<?php echo $k?>" id="<?php echo $k?>" value="<?php echo $v?>" />
							<?php
								}
							}
						 ?>
						 </td>
			      </tr>
				</table>	 
				</form>
			<?PHP	
			} */
			function Show_Image_Normal($row_prod)
			{
				global $db,$ecom_siteid,$ecom_hostname,$ecom_themename;	
				  $show_normalimage = false;
				  if ($_REQUEST['prod_curtab'])
				  {
					if ($_REQUEST['prodimgdet'])	
						$showonly = $_REQUEST['prodimgdet'];
					else
						$showonly = 0;
					// Calling the function to get the type of image to shown for current 
					$pass_type = get_default_imagetype('proddet');
					// Calling the function to get the image to be shown
					$tabimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type,0,$showonly,1);
					if(count($tabimg_arr))
					{
						$exclude_tabid = $tabimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
						?>
						<a href="<?php url_root_image($tabimg_arr[0]['image_extralargepath'])?>" title="<?php echo $row_prod['product_name']?>" rel='lightbox[gallery]'>
						<?php
							show_image(url_root_image($tabimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
						?>
						</a>
						<?php
						$show_noimage 	= false;
					}
					else
						$show_normalimage = true;
				  }
				  else
					$show_normalimage = true;
					
					if ($show_normalimage)
					{
						?>
						<div class="detial_image">
						<?php
						if ($_REQUEST['prodimgdet'])	
							$showonly = $_REQUEST['prodimgdet'];
						else
							$showonly = 0;
						// Calling the function to get the type of image to shown for current 
						$pass_type = get_default_imagetype('proddet');
						// Calling the function to get the image to be shown
						$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,$showonly,1);
						if(count($prodimg_arr))
						{
							$exclude_prodid = $prodimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
							?>
							<a href="<?php url_root_image($prodimg_arr[0]['image_extralargepath'])?>"  title="<?php echo $row_prod['product_name']?>" rel='lightbox[gallery]'>
							<?php
							show_image(url_root_image($prodimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
							?>
							</a>
							<?php
							$show_noimage 	= false;
						}
						else
						{	
							// calling the function to get the default no image 
							$no_img = get_noimage('prod','big'); 
							if ($no_img)
							{
								show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
							}	
						}
						?>
						</div>
						<?php
					}
					$ret_arr['exclude_tabid']		= $exclude_tabid;
					$ret_arr['exclude_prodid'] 	= $exclude_prodid;
					return $ret_arr;
			}
			function show_Sizechart()
			{
				global $db,$ecom_siteid,$ecom_hostname,$ecom_themename;	
				// Size chart section
				$sql = "SELECT heading_title, product_sizechart_heading.heading_id
							FROM 
								product_sizechart_heading, product_sizechart_heading_product_map 
							WHERE 
								product_sizechart_heading.sites_site_id = '".$ecom_siteid."' 
								AND product_sizechart_heading.heading_id=product_sizechart_heading_product_map.heading_id 
								AND product_sizechart_heading_product_map.products_product_id = '".$_REQUEST['product_id']."' 
							ORDER BY 
								product_sizechart_heading_product_map.map_order" ;
				 $res = $db->query($sql);
				 while(list($heading_title, $heading_id) = $db->fetch_array($res))
				 {
					
					$heading[] = $heading_title;
					$charsql = "SELECT size_value 
								 FROM 
									product_sizechart_values 
								 WHERE 
									heading_id='".$heading_id."' 
									AND products_product_id = '".$_REQUEST['product_id']."' 
									AND sites_site_id  ='".$ecom_siteid."' 
								 ORDER BY 
									size_sortorder ";
							   
					$charres = $db->query($charsql);
					while(list($size_value) = $db->fetch_array($charres))
					{
						$sizevalue[$heading_id][] = $size_value;
					}
				 }
	
				   $cnt =   count($sizevalue);
				   $sql_prods = "SELECT product_sizechart_mainheading 
									FROM 
										products 
									WHERE 
										product_id = '".$_REQUEST['product_id']."'
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
					$ret_prods = $db->query($sql_prods);
					if ($db->num_rows($ret_prods))
					{
						$row_prods 	= $db->fetch_array($ret_prods);
						$main_title = stripslashes($row_prods['product_sizechart_mainheading']); 
					}
					if($main_title == '')
					{
						$main_title = $Settings_arr['product_sizechart_default_mainheading'];
					}
					
				if(count($sizevalue))
				{
					foreach($sizevalue as $k=>$v)
					{
						$cnt_hd = count($v);
					}
					?>
					<div class="size_chart_div">
					<table width="100%" border="0" cellpadding="2" cellspacing="0" class="size_chart_table">
					<?PHP if(is_array($heading))
					  { 
					  ?>
					<tr>
					  <td align="left"  class="productchartheader" colspan="<?php echo count($heading)?>" ><?PHP echo $main_title; ?></td>
					</tr>
					<tr>
					  <?PHP 
						  foreach($heading AS $val)
						  { ?>
					  <td align="center"  class="productsizechartheading" ><?PHP echo $val; ?></td>
					  <?PHP
						  } ?>
					</tr>
					<?PHP 
								for($i=0; $i<$cnt_hd; $i++)
								{
									$cls = ($cls=='productsizechartvalueA')?'productsizechartvalueB':'productsizechartvalueA';
							?>
					<tr>
					  <?PHP
							foreach($sizevalue as $k=>$v)
							{
							?>
					  <td class="<?PHP echo $cls; ?>" align="center" ><?PHP echo ($sizevalue[$k][$i])?$sizevalue[$k][$i]:'-'; ?></td>
					  <?PHP
							} 
							  ?>
					</tr>
					<? 
							}
					  } 
					  ?>
				</table>
				</div>
			  <? 
				}
			}
	};	
?>