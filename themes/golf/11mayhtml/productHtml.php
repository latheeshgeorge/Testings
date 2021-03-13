<?php
	/*############################################################################
	# Script Name 	: productHtml.php
	# Description 		: Page which holds the display logic for product details
	# Coded by 		: Sny
	# Created on		: 08-May-2009
	# Modified by		: 
	# Modified On		: 
	
	##########################################################################*/
	class product_Html
	{
		// Defining function to show the selected product details
		function Show_ProductDetails($ret_prod)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					 $ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
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
		<div class="treemenu"><?php echo generate_tree(-1,$_REQUEST['product_id'])?></div>
		<form method="post" name="frm_proddetails" id="frm_proddetails" action="<?php url_link('manage_products.html')?>" class="frm_cls" onsubmit="return prod_detail_submit(this)">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
		<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
		<input type="hidden" name="pass_combid" value="<?php echo $_REQUEST['pass_combid']?>" />
		
		<table border="0" cellpadding="0" cellspacing="0" class="productdeatilstable">
          <?php
		  		
				if($alert)
				{
			?>
					  <tr>
						<td colspan="2" align="center" valign="middle" class="red_msg"> - <?php echo $alert?> - </td>
					  </tr>
			<?php
				}
				elseif($_REQUEST['stockalert'])
				{
			?>
					<tr>
						<td colspan="2" align="center" valign="middle" class="red_msg"> - <?php echo $Captions_arr['PROD_DETAILS'][$_REQUEST['stockalert']]	?> - </td>
					  </tr>
			<?php	
				}
			?>
          <tr>
            <td colspan="2" align="left" class="productdeheader"><?php echo stripslashes($row_prod['product_name'])?>
			<?php
			
			if($_REQUEST['for_notification']==1) // make the decision whether the instock message is to be displayed here or in div
			{
				$this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
			}	
			else // case if displaying the instock notification message here itself
				$this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
			?>
			</td>
          </tr>
		 <?php
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
												a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists      
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
		 // Check whether the bar to show the features to be displayed
		 if(($email_show==1) or ($favourite_show==1) or ($writereview_show==1) or ($readreview_show==1) or ($pdf_show==1) or ($compare_show==1)) 
		 {
		 ?>
				<tr>
					<td colspan="2" align="right" class="productdeheader"><table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="1%" align="left" valign="top" class="productdetails_img_td"><img src="<?php url_site_image('pro_link_det_left.gif')?>" title="left" alt="left"/></td>
					<td colspan="2" class="productdetdA" align="right">
						<?php
							// Check whether the compare feature is enabled in the product details page 	
							if( $compare_show==1)
							{
								$def_cat_id = $row_prod['product_default_category_id'];
							
							?>
								<!--<script type="text/javascript">
								function compare_select()
								{
								document.frm_special_comp.submit();
								}
								</script>-->
							<a href="<?php url_productcompare($_REQUEST['product_id'],$row_prod['product_name'])?>" class="productdetailslink" ><? echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_LINK'] ?></a>
							<?php
							}
							if($favourite_show==1) // ** Show the add to favourite only if logged in 
							{
								$sql_prod = "SELECT products_product_id FROM customer_fav_products WHERE sites_site_id=$ecom_siteid AND products_product_id=".$_REQUEST['product_id']." AND customer_customer_id=$cust_id LIMIT 1";
								$ret_num= $db->query($sql_prod);
								if($db->num_rows($ret_num)==0) 
								{ 
								?>
									<a href="#" class="productdetailslink" onclick="if(confirm('<? echo $Captions_arr['PROD_DETAILS']['PRODDET_ADD_CONFIRM'] ?>')){document.frm_proddetails.fpurpose.value='add_favourite';document.frm_proddetails.submit();}"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE'];?></a>
								<?php
								}
								else
								{
								?>
									<a href="#" class="productdetailslink" onclick="if(confirm('<? echo $Captions_arr['PROD_DETAILS']['PRODDET_REM_CONFIRM'] ?>')){document.frm_proddetails.fpurpose.value='remove_favourite';document.frm_proddetails.submit();}"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE'];?></a>
								<?php
								}
							}
							if($email_show==1) // Check whether the email a friend module is there for current site
							{
							?>
								<a href="<?php url_link('emailafriend'.$_REQUEST['product_id'].'.html')?>" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND'];?></a>
							<?php
							}
							if(in_array('mod_product_reviews',$inlineSiteComponents)) // Check whether the product review module is there for current site
							{ 
								if($writereview_show==1)
								{
								?>
									<a href="<?php url_link('writeproductreview'.$_REQUEST['product_id'].'.html')?>" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_WRITEREVIEW'];?></a>
								<?php
								}
								if($readreview_show==1)
								{
								?>	
									<a href="<?php url_link('readproductreview'.$_REQUEST['product_id'].'.html')?>" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_READREVIEW'];?></a>
								<?php
								}
							}
							if($pdf_show==1) // Check whether the download pdf module is there for current site
							{  
							?>
								<a href="<?=url_Prod_PDF($_REQUEST['product_id'],$_REQUEST['category_id'],$row_prod['product_name'])?>" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADPDF'];?></a>
							<?php
							}
							?>
					</td>
					<td width="1%" align="right" valign="top" class="productdetails_img_td"><img src="<?php url_site_image('pro_link_det_right.gif')?>" title="right" alt="right"/></td>
				</tr>
				</table></td>
				</tr>
          <?php				
		  }
			  	// Check whether the images to be shown in flash or as normal
				if($prod_img_show_type=='FLASH' or $prod_img_show_type=='FLASH_ROTATE')
				{
					// Calling the flash wrapper to display the images
					?>
					<tr>
					<td valign="top" class="productdetmain" align="center"><table width="100%" border="0" cellspacing="1" cellpadding="1">
					<? 
					if($ecom_siteid==5)
					{
						if($row_prod['product_webprice']>749)
						{ ?>
						<tr>
						<td align="right" colspan="2" ><img src="<? url_site_image("Request_Lease_Quote.gif")?>" alt="" height="30" width="30" align="absmiddle" border="0"> 
						<a class="fontleesmall" href="#" onclick="window.open('http://<?=$ecom_hostname?>/themes/<? echo $ecom_themename; ?>/html/leasingform.php','ProductPopup');return false">Request Lease Quote</a><br />
						<img  src="<? url_site_image('Send_Brochure_Pack.gif');?>" align="absmiddle"><a class="fontleesmall" href="http://<?=$ecom_hostname?>/themes/<? echo $ecom_themename; ?>/html/contactform.php" target="_blank"> Send Brochure Pack</a>		
						
						</td>
						</tr>
						<?  					
						}
					}
					?>
					<tr>
					<td class="reviewscore" valign="top" align="left"><?php
					// Check whether the product review module is active for the site
					$module_name = 'mod_product_reviews';
					if(in_array($module_name,$inlineSiteComponents))
					{
						echo $Captions_arr['PROD_DETAILS']['PRODDET_REVIEW_CAPTION'];
						for ($i=0;$i<$row_prod['product_averagerating'];$i++)
						{
							echo '<img src="'.url_site_image('reviewstar_on.gif',1).'" border="0" alt="revscoreimg" />&nbsp;'; 
						}
						for ($i=$row_prod['product_averagerating'];$i<10;$i++)
						{
							echo '<img src="'.url_site_image('reviewstar_off.gif',1).'" border="0"  alt="revscoreimg"/>&nbsp;'; 
						}
					}
					if ($Settings_arr['product_show_instock'])
					{
					?>
						<br />
						<span class="stockdetailstd"><?php echo get_stockdetails($_REQUEST['product_id'])?> </span>
					<?php
					}	
					if ($Settings_arr['bonus_points_instock'] and $row_prod['product_bonuspoints']>0)
					{
					?>
						<br />
						<span class="productdeposit_price"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTS'].' '.$row_prod['product_bonuspoints']?></span>
					<?php
					}
					if ($row_prod['product_deposit']>0 and $row_prod['product_deposit_message']!='')
					{
					?>
						<br />
						<span class="productdeposit_price"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DEPOSTIT_REQ']?> <?php echo $row_prod['product_deposit'].' '.$Captions_arr['PROD_DETAILS']['DEP_PERCENTAGE']?></span><br />
						<span class="productdeposit_msg"><?php echo nl2br(stripslashes($row_prod['product_deposit_message']))?> </span>
					<?php	
					}
					?>
					</td>
						<td align="left" valign="top" class="reviewscore"><?php
							$price_class_arr['ul_class'] 			= 'prodeulprice';
							$price_class_arr['normal_class'] 		= 'productdetnormalprice';
							$price_class_arr['strike_class'] 		= 'productdetstrikeprice';
							$price_class_arr['yousave_class'] 	= 'productdetyousaveprice';
							$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
							
							echo show_Price($row_prod,$price_class_arr,'prod_detail');
							show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
							?>
						</td>
					</tr>
					</table>
					<? 
					if($prod_img_show_type=='FLASH') // case of showing images in a flash container
					{
						// pass the ids thru query string
						$prod_tab_id = $_REQUEST['product_id'].'~'.$_REQUEST['prod_curtab']; ?>
						<script type="text/javascript">
							document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="530" height="300">');
							<?php /*?>document.write('<param name="movie" value="http://<?php echo $ecom_hostname?>/themes/<?php echo $ecom_themename?>/swf/xml4.swf?filepath=http://<?php echo $ecom_hostname?>/getflashimage_images.php?prod_tab_id=<?=$prod_tab_id?>" />');<?php */?>
							document.write('<param name="movie" value="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/swf/xml4.swf?filepath=http://<?php echo $ecom_hostname?>/getflashimage_images.php?prod_tab_id=<?=$prod_tab_id?>" />');
							document.write('<param name="quality" value="high" />');
							document.write('<param name="wmode" value="opaque">');
							document.write('<embed src="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/swf/xml4.swf?filepath=http://<?php echo $ecom_hostname?>/getflashimage_images.php?prod_tab_id=<?=$prod_tab_id?>" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="530" height="300" wmode="opaque"></embed>');
							document.write('</object>');
						</script>
					<?php
					}
					elseif($prod_img_show_type=='FLASH_ROTATE') // Case of showing flash which rotates the given images
					{
					?>
							<script type="text/javascript">
								document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="530" height="500">');
								<?php /*?>document.write('<param name="movie" value="http://<?php echo $ecom_hostname?>/themes/<?php echo $ecom_themename?>/swf/rotate_flash.swf?filepath=http://<?php echo $ecom_hostname?>/getflashrotate_images.php?p_id=<?php echo $_REQUEST['product_id']?>" />');<?php */?>
								document.write('<param name="movie" value="http://<?php echo $ecom_hostname?>/themes/<?php echo $ecom_themename?>/swf/rotate_flash.swf?filepath=http://<?php echo $ecom_hostname?>/getflashrotate_images.php?p_id=<?php echo $_REQUEST['product_id']?>" />');
								document.write('<param name="quality" value="high" />');
								document.write('<embed src="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/swf/rotate_flash.swf?filepath=http://<?php echo $ecom_hostname?>/getflashrotate_images.php?p_id=<?php echo $_REQUEST['product_id']?>" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="530" height="500"></embed>');
								document.write('</object>');
							</script>
					<?php
					}
					?>	
					</td>
					</tr>
				<?php	
				}
				else // case of showing the images as normal or swap
				{ 
					?>
					<tr>
					<td valign="middle" class="productdetmain" align="center">
					<?php
					if($prod_img_show_type=='JAVA') // case of showing javascript swap
					{
						$this->Show_Javascript_Image_Swapper($row_prod);
					}
					else // case of showing normal image display
					{
						$ret_arr = $this->Show_Image_Normal($row_prod);
						
						$exclude_tabid			= $ret_arr['exclude_tabid'];
						$exclude_prodid			= $ret_arr['exclude_prodid'];
					}	
					?>
					</td>
					<td width="52%"  class="productdetd" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="1">
					<?
					if($ecom_siteid==5)
					{
						if($row_prod['product_webprice']>749)
						{ ?>
							<tr>
							<td align="left" ><img src="<? url_site_image("Request_Lease_Quote.gif")?>" alt="" height="30" width="30" align="absmiddle" border="0"> 
							<a class="fontleesmall" href="#" onclick="window.open('http://<?=$ecom_hostname?>/themes/<? echo $ecom_themename; ?>/html/leasingform.php','ProductPopup');return false">Request Lease Quote</a><br />
							<img  src="<? url_site_image('Send_Brochure_Pack.gif');?>" align="absmiddle"><a class="fontleesmall" href="http://<?=$ecom_hostname?>/themes/<? echo $ecom_themename; ?>/html/contactform.php" target="_blank"> Send Brochure Pack</a>		
							
							</td>
							</tr>
						<?  					
						}
					}
					?>
					<?php
					// Check whether the product review module is active for the site
					$module_name = 'mod_product_reviews';
					if(in_array($module_name,$inlineSiteComponents))
					{
					?>
						<tr>
						<td class="reviewscore"><?php
							echo $Captions_arr['PROD_DETAILS']['PRODDET_REVIEW_CAPTION'];
							for ($i=0;$i<$row_prod['product_averagerating'];$i++)
							{
								echo '<img src="'.url_site_image('reviewstar_on.gif',1).'" border="0" alt="revscoreimg" />&nbsp;'; 
							}
							for ($i=$row_prod['product_averagerating'];$i<10;$i++)
							{
								echo '<img src="'.url_site_image('reviewstar_off.gif',1).'" border="0"  alt="revscoreimg"/>&nbsp;'; 
							}
							?>
						</td>
						</tr>
					<?php
					}
					if ($Settings_arr['product_show_instock'])
					{
					?>
						<tr>
						<td><span class="stockdetailstd"><?php echo get_stockdetails($_REQUEST['product_id'])?> </span></td>
						</tr>
					<?php
					}	
					if ($Settings_arr['bonus_points_instock'] and $row_prod['product_bonuspoints']>0)
					{
					?>
						<tr>
						<td><span class="productdeposit_price"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTS'].' '.$row_prod['product_bonuspoints']?></span></td>
						</tr>
					<?php
					}
					if ($row_prod['product_deposit']>0 and $row_prod['product_deposit_message']!='')
					{
					?>
						<tr>
						<td><span class="productdeposit_price"><? echo $Captions_arr['PROD_DETAILS']['PRODDET_DEPOSIT_REQ'] ?> <?php echo ($row_prod['product_deposit']).' '.$Captions_arr['PROD_DETAILS']['DEP_PERCENTAGE']?></span><br />
						<span class="productdeposit_msg"><?php echo nl2br(stripslashes($row_prod['product_deposit_message']))?> </span></td>
						</tr>
					<?php	
					}
					if($row_prod['product_variable_in_newrow']==0) // case of showing the variables in the same row as that of image
					{
					
					?>
						<tr>
						<td align="left" valign="top" class="productdetd"><?php
						// Show the price and qty here only if variables are shown in same row as that of product image			
						$price_class_arr['ul_class'] 		= 'prodeulprice';
						$price_class_arr['normal_class'] 	= 'productdetnormalprice';
						$price_class_arr['strike_class'] 	= 'productdetstrikeprice';
						$price_class_arr['yousave_class'] 	= 'productdetyousaveprice';
						$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
						
						echo show_Price($row_prod,$price_class_arr,'prod_detail');
						show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
						// Show the product variables and product messages
						$var_listed = $this->show_ProductVariables($row_prod,'column');
						// Showing the bulk discount details
						$this->show_BulkDiscounts();
						?>
						</td>
						</tr>
					<?php
					}
					?>
					</table>
					<?php
					// decide whether to display the button
					if(!$button_displayed and $row_prod['product_variable_in_newrow']==0)
					$button_displayed=$this->show_buttons($row_prod);
					?>
					<?php
					// Show the price and qty here only if variables are shown in a new row		
					if($row_prod['product_variable_in_newrow']==1)
					{
						$price_class_arr['ul_class'] 		= 'prodeulprice';
						$price_class_arr['normal_class'] 	= 'productdetnormalprice';
						$price_class_arr['strike_class'] 	= 'productdetstrikeprice';
						$price_class_arr['yousave_class'] 	= 'productdetyousaveprice';
						$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
						
						echo show_Price($row_prod,$price_class_arr,'prod_detail');
						show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
					}	
					
					// Showing additional images
					$this->show_more_images($row_prod,$exclude_tabid,$exclude_prodid);
					?>
					</td>
					</tr>
					<?php /*?><tr>
					<td align="left" valign="top">
					
					</td>
					</tr><?php */?>
					<?php
					// Section to handle the case of showing the variables in a new row
					if($row_prod['product_variable_in_newrow']==1)
					{
					?>
					<tr>
					<td align="left" valign="top" class="productdetd" colspan="2">
						<?php
						// Show the product variables and product messages
						$var_listed = $this->show_ProductVariables($row_prod,'row');
						// Show the product label details
						$this->show_ProductLabels('row');
						// show the bulk discount details
						$this->show_BulkDiscounts();
						?>
					</td>
					</tr>
					<?php	
					}
					?>
					<?php
				}
				if($prod_img_show_type=='FLASH' or $prod_img_show_type=='FLASH_ROTATE')
				{
			?>
          <tr>
            <td align="left" valign="top" class="productdetd">
				<?php
					// Show the product variables and product messages
					$var_listed = $this->show_ProductVariables($row_prod,'row');
					// Show the product label details
					if($var_listed)
						$mtd = 'row';
					else
						$mtd = 'col';
					$this->show_ProductLabels($mtd);
					
					// Show the bulk discount details
					$this->show_BulkDiscounts();
				?>
            </td>
          </tr>
          <?php	
				}
				elseif ($row_prod['product_variable_in_newrow']==0 or $var_listed == false) // if flash not set and also variable not set to show in new row
				{
			?>
          <tr>
            <td colspan="2" align="left" class="productdetd"><?php
						$this->show_ProductLabels('col');
						?>
            </td>
          </tr>
          <?php
				}
			?>
          <tr>
            <td colspan="2" align="left" valign="middle" class="productdetd"><?php
				// decide whether to display the button
				if(!$button_displayed)
					$button_displayed=$this->show_buttons($row_prod);
			?>
            </td>
          </tr>
          <?php
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
					/*$sql_set = "SELECT product_sizechart_default_mainheading 
									FROM 
										general_settings_sites_common 
									WHERE 
										sites_site_id = $ecom_siteid 
									LIMIT 
										1";
					$ret_set = $db->query($sql_set);
					$row_set = $db->fetch_array($ret_set);
					$main_title = stripslashes($row_set['product_sizechart_default_mainheading']); 
					*/
					$main_title = $Settings_arr['product_sizechart_default_mainheading'];
				}
				
			if(count($sizevalue))
			{
				foreach($sizevalue as $k=>$v)
				{
					$cnt_hd = count($v);
				}
				?>
          <tr>
            <td colspan="2" align="left" valign="middle" ><table width="100%" border="0" cellpadding="2" cellspacing="0">
                <?PHP if(is_array($heading))
				  { 
				  ?>
                <tr>
                  <td align="left"  class="productchartheader" colspan="<?php echo count($heading)?>" ><div class="sizetabselected"><?PHP echo $main_title; ?></div></td>
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
            </table></td>
          </tr>
          <? 
			}
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
          <tr>
            <td colspan="2" align="left" class="productdetdtab"><a href="#" name="protabs"></a>
                <!--This href is to bring back the user to the tab section after reloadin on tab click -->
                <div class="protabcontainer" >
                  <ul class="protab">
                    <?php
						
								$curtab 		= ($_REQUEST['prod_curtab'])?$_REQUEST['prod_curtab']:0;
								$prodimgdetid 	= (!$_REQUEST['prodimgdet'])?0:$_REQUEST['prodimgdet'];
								foreach($tabs_arr as $k_tabid=>$v_tabtitle)
								{
									$sel = ($k_tabid == $curtab)?' class="selectedtab"':'';
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
									if(!$sel)
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
                </div></td>
          </tr>
          <tr>
            <td colspan="2" align="left" class="productdetd_main"><?php
						echo $tabs_content_arr[$curtab];
					  ?>
            </td>
          </tr>
          <?php
				}	
				// Check whether any downloads exists for current product
				$sql_attach = "SELECT * 
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
          <tr>
            <td colspan="2" align="left" class="productdetd"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="productdownloadtable">
                <tr>
                  <td class="productdownloadheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADS'];?></td>
                </tr>
                <tr>
                  <td><ul class="downloadul">
                      <?php
								// Get the list of video attachments
								$sql_video = "SELECT * 
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
                      <li class="video">
                        <?php //echo $Captions_arr['PROD_DETAILS']['PRODDET_VIDEO'];?>
                        <ul  class="sub">
                          <?php	
								$cnts = 1;
								while ($row_video = $db->fetch_array($ret_video))
								{
								?>
                          <li><a class="downloadlink" href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_video['attachment_id']?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_video['attachment_title'])?></a></li>
                          <?php		
								}
								?>
                        </ul>
                      </li>
                    <?php
								}
								// Get the list of audio attachments
								$sql_audio = "SELECT * 
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
                      <li class="audio">
                        <?php //echo $Captions_arr['PROD_DETAILS']['PRODDET_AUDIO'];?>
                        <ul  class="sub">
                          <?php	
									$cnts = 1;
								while ($row_audio = $db->fetch_array($ret_audio))
								{
								?>
                          <li><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_audio['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_audio['attachment_title'])?></a></li>
                          <?php		
								}
								?>
                        </ul>
                      </li>
                    <?php
								}
								// Get the list of pdf attachments
								$sql_pdf = "SELECT * 
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
                      <li class="pdf">
                        <?php //echo $Captions_arr['PROD_DETAILS']['PRODDET_PDF'];?>
                        <ul  class="sub">
                          <?php	
									$cnts = 1;
								while ($row_pdf = $db->fetch_array($ret_pdf))
								{
								?>
                          <li><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_pdf['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. </a><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_pdf['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo stripslashes($row_pdf['attachment_title'])?></a></li>
                          <?php		
								}
								?>
                        </ul>
                      </li>
                    <?php
								}
								// Get the list of other attachments
								$sql_other = "SELECT * 
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
                      <li class="others">
                        <?php //echo $Captions_arr['PROD_DETAILS']['PRODDET_OTHER'];?>
                        <ul  class="sub">
                          <?php	
									$cnts = 1;
								while ($row_other = $db->fetch_array($ret_other))
								{
								?>
                          <li><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_other['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_other['attachment_title'])?></a></li>
                          <?php		
								}
								?>
                        </ul>
                      </li>
                    <?php
								}
								?>
                  </ul></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2" align="left" class="productdetd">&nbsp;</td>
          </tr>
          <?php
				}
			?>
        </table>
		</form>
		<?php	
			// ** Check whether any linked products exists for current product
			$sql_linked = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
							a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
							a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
							a.product_total_preorder_allowed,a.product_applytax,product_shortdesc,
							a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists      
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
			
			// Check whether compare products section to be displayed
			 //	if ($db->num_rows($ret_comp_prod) and $_REQUEST['after_comp']==1  and isProductCompareEnabledInProductDetails())
			//		$this->Show_Compare_Product($ret_comp_prod);
			?>
			<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
			<tr>
				<td align="center">
				<?php 
						$sql_inline = "SELECT display_order,display_component_id,display_id,display_title 
					FROM 
						display_settings a,features b 
					WHERE 
						a.sites_site_id=$ecom_siteid 
						AND a.display_position='middle' 
						AND b.feature_allowedinmiddlesection = 1  
						AND layout_code='".$default_layout."' 
						AND a.features_feature_id=b.feature_id 
						AND b.feature_modulename='mod_shelf' 
					ORDER BY 
							display_order 
							ASC";
				$ret_inline = $db->query($sql_inline); // to dispplay the advert for the category page
				if ($db->num_rows($ret_inline))
				{
					while ($row_inline = $db->fetch_array($ret_inline))
					{
						//$modname 			= $row_inline['feature_modulename'];
						$body_dispcompid	= $row_inline['display_component_id'];
						$body_dispid			= $row_inline['display_id'];
						$body_title				= $row_inline['display_title'];
						include ("includes/base_files/shelf.php");
					}
				}
		
					
				?>
				</td>
			</tr>	
			</table>
	<?php	
		}
		// ** Function to show the details of products which are linked with current product.
		function Show_Linked_Product($ret_prod)
		{
			global $ecom_siteid,$db,$Captions_arr,$Settings_arr;
			// Calling the function to get the type of image to shown for current 
			$pass_type = get_default_imagetype('link_prod');
			$prod_compare_enabled = isProductCompareEnabled();
			switch($Settings_arr['linked_prodlisting'])
			{
			case '1row':
		?>
				<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
				<tr>
					<td colspan="3" class="pro_de_shelfBheader" align="left"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_PEOPLE_INTERESTED'];?></td>
				</tr>
			<?php
				while($row_prod = $db->fetch_array($ret_prod))
				{
			?>
					<tr onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfBtabletd'">
						<td align="left" valign="middle" class="shelfBtabletd">
							<h1 class="shelfBprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
							<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
						</td>
						<td align="center" valign="middle" class="shelfBtabletd">
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
						</td>
						<td align="left" valign="middle" class="shelfBtabletd">
						<?php
							$price_class_arr['ul_class'] 		= 'shelfBul';
							$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
							$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
							$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
							$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
							
							echo show_Price($row_prod,$price_class_arr,'linkprod_1');
							show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
							$frm_name = uniqid('linked_');
						?>	
								<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
								<input type="hidden" name="fpurpose" value="" />
								<input type="hidden" name="fproduct_id" value="" />
								<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
								<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
								<?php
									if($showqty==1)// this decision is made in the main shop settings
									{
								?>
									<div class="quantity"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY']?><input type="text" class="quainput" name="qty"  value="1" /></div>
								<?php
									}
								?>
								<div class="link_infodiv">
									<div class="link_infodivleft"><?php show_moreinfo($row_prod,'link_infolink')?></div>
									<div class="link_infodivright">
									<?php
										$class_arr 					= array();
										$class_arr['ADD_TO_CART']	= 'quantity_infolink';
										$class_arr['PREORDER']		= 'quantity_infolink';
										$class_arr['ENQUIRE']		= 'quantity_infolink';
										show_addtocart($row_prod,$class_arr,$frm_name);
									?>
									</div>
								</div>
								</form>
						</td>
					</tr>
			<?php
				}
			?>	
			</table>
		<?php
		break;
		case '3row':
		?>
		<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
		<tr>
					<td colspan="3" class="pro_de_shelfBheader" align="left"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_PEOPLE_INTERESTED'];?></td>
		</tr>
		<tr>
		<?php
			$max_col = 3;
			$cur_col = 0;
			$prodcur_arr = array();
			//$row_prod =0;
			while ($row_prod = $db->fetch_array($ret_prod))
			{
			
			 $prodcur_arr[] = $row_prod;
			 
				//$prodcur_arr[] = $searchData;
				//##############################################################
				// Showing the title, description and image part for the product
				//##############################################################
		?>
				<td class="shelfAtabletd" align="left" valign="top"  onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfAtabletd'">
				
					<ul class="shelfAul">
						
								<li><h1 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li>
						
								<li>
									<?php
										$price_class_arr['ul_class'] 		= 'shelfBul';
										$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
										$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
										$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
										$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
										echo show_Price($row_prod,$price_class_arr,'linkprod_3');
										show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
									?>	
								</li>
							
								<li>
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
									<?php if($prod_compare_enabled)  {
							dislplayCompareButton($row_prod['product_id']);
						}?>
								</li>
						
							<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
						
					</ul>
				</td>
			<?php
				//echo $cur_col;
				$cur_col++;
				
				if ($cur_col>=$max_col)
				{
					echo "</tr>";
					$cur_tempcol = $cur_col = 0;
					//##############################################################
					// Showing the more info and add to cart links after each row in 
					// case of breaking to new row while looping
					//##############################################################
					echo "<tr>";
					foreach($prodcur_arr as $k=>$prod_arr)
					{
						$frm_name = uniqid('linked_');
					?>
						<td class="shelfAtabletd">
							<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $prod_arr['product_id']?>)">
							<input type="hidden" name="fpurpose" value="" />
							<input type="hidden" name="fproduct_id" value="" />
							<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
							<input type="hidden" name="fproduct_url" value="<?php url_product($prod_arr['product_id'],$prod_arr['product_name'])?>" />
							<div class="infodiv">
								<div class="infodivleft"><?php show_moreinfo($prod_arr,'infolink')?></div>
								<div class="infodivright">
								<?php
									$class_arr 					= array();
									$class_arr['ADD_TO_CART']	= 'quantity_infolink';
									$class_arr['PREORDER']		= 'quantity_infolink';
									$class_arr['ENQUIRE']		= 'quantity_infolink';
									show_addtocart($prod_arr,$class_arr,$frm_name)
								?>
								</div>
							</div>
							</form>
						</td>
			<?php
						++$cur_tempcol;
						// done to handle the case of breaking to new linel
						if ($cur_tempcol>=$max_col)
						{
							echo "</tr>";
							$cur_tempcol=0;
						}
					}
					echo "<tr>";
					//echo "<tr>";
					$prodcur_arr = array();	
				}
			
			 //
			}
			//echo count($prodcur_arr);
			if ($cur_col<$max_col)
			{
				echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td></tr><tr>";
				$cur_tempcol = $cur_col = 0;
				//##############################################################
				// Done to handle the case of showing the qty, add to cart and more info links
				// in case if total product is less than the max allower per row.
				//##############################################################
				foreach($prodcur_arr as $k=>$prod_arr)
				{
					$frm_name = uniqid('linked_');
				?>
					<td class="shelfAtabletd">
						<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $prod_arr['product_id']?>)">
						<input type="hidden" name="fpurpose" value="" />
						<input type="hidden" name="fproduct_id" value="" />
						<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
						<input type="hidden" name="fproduct_url" value="<?php url_product($prod_arr['product_id'],$prod_arr['product_name'])?>" />
						<div class="infodiv">
							<div class="infodivleft"><?php show_moreinfo($prod_arr,'infolink')?></div>
							<div class="infodivright">
							<?php
								$class_arr 					= array();
								$class_arr['ADD_TO_CART']	= 'quantity_infolink';
								$class_arr['PREORDER']		= 'quantity_infolink';
								$class_arr['ENQUIRE']		= 'quantity_infolink';
								show_addtocart($prod_arr,$class_arr,$frm_name)
							?>
							</div>
						</div>
						</form>
					</td>
		<?php
					++$cur_tempcol;
					if ($cur_tempcol>=$max_col)
					{
						echo "</tr><tr>";
						$cur_tempcol=0;
					}
				}
				echo "<td colspan='".($max_col-$cur_tempcol)."'>&nbsp;</td></tr>";
			}
			else
				echo "</tr>";
		?>	
 </table>
		<?
		break;
		}	
		}
		// ** Function to show the list of products to be compared with current product.
		function Show_Compare_Product($ret_prod)
		{
				global $ecom_siteid,$db,$Captions_arr,$Settings_arr;
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
				<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
				<tr>
					<td colspan="3" class="pro_de_shelfBheader" align="left"><a name="comp_prods"></a><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_HEAD'];?></td>
				</tr>
				<tr>
					<td colspan="3" class="productdetd_main" align="left"><?php  $msg = $Captions_arr['PROD_DETAILS']['PRODDET_MAX_COMP_PROD']; $msg = str_replace('[prodno]',$Settings_arr['no_of_products_to_compare'],$msg); echo $msg?></td>
				</tr>
				<tr>
					<td  align="left"><input type="button" name="prodet_backprod" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_BACK_TO_DETAIL'];?>" class="buttonred_cart" onclick="window.location='<?php url_product($_REQUEST['product_id'],'',-1)?>'"/></td>
				    <td  align="right">&nbsp;</td>
				    <td  align="right"><input type="button" name="prodet_comparebutton" value="<?php echo $Captions_arr['PROD_DETAILS']['COMPARE_PRODUCTS'];?>" class="buttonred_cart" onclick="handle_proddet_compare()"/></td>
				</tr>
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
					<tr onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfBtabletd'">
						<td align="left" valign="middle" class="shelfBtabletd">
						
							<h1 class="shelfBprodname"><input type="checkbox" name="chkproddet_comp_<?php echo $row_prod['product_id']?>" id="chkproddet_comp_<?php echo $row_prod['product_id']?>" value="<?php echo $row_prod['product_id']?>" <?php echo $compare_checked?>/><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
							<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>						</td>
						<td align="center" valign="middle" class="shelfBtabletd">
							
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
							</a>						</td>
						<td align="left" valign="middle" class="shelfBtabletd">
						<?php
							$price_class_arr['ul_class'] 		= 'shelfBul';
							$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
							$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
							$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
							$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
							
							echo show_Price($row_prod,$price_class_arr,'linkprod_1');
							show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
							$frm_name = uniqid('linked_');
						?>	
								<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
								<input type="hidden" name="fpurpose" value="" />
								<input type="hidden" name="fproduct_id" value="" />
								<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
								<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
								<?php
									if($showqty==1)// this decision is made in the main shop settings
									{
								?>
									<div class="quantity"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY']?><input type="text" class="quainput" name="qty"  value="1" /></div>
								<?php
									}
								?>
								<div class="link_infodiv">
									<div class="link_infodivleft"><?php show_moreinfo($row_prod,'link_infolink')?></div>
									<div class="link_infodivright">
									<?php
										$class_arr 					= array();
										$class_arr['ADD_TO_CART']	= 'quantity_infolink';
										$class_arr['PREORDER']		= 'quantity_infolink';
										$class_arr['ENQUIRE']		= 'quantity_infolink';
										show_addtocart($row_prod,$class_arr,$frm_name);
									?>
									</div>
								</div>
								</form>						</td>
					</tr>
			<?php
				}
			?>	
			<tr>
					<td colspan="3"  align="right"><input type="button" name="prodet_comparebutton" value="<?php echo $Captions_arr['COMMON']['COMPARE_PRODUCTS'];?>" class="buttonred_cart" onclick="handle_proddet_compare()"/></td>
				</tr>
			</table>
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
			
			if ($pos!='column') // Check the case of labels only if value of pos is not 'column'
			{
				// Check whether any labels to be displayed for current product
				$sql_labels = "SELECT a.label_id,a.label_name,b.label_value,a.is_textbox,product_site_labels_values_label_value_id  
								FROM
									product_site_labels a,product_labels b 
								WHERE 
									b.products_product_id = ".$_REQUEST['product_id']." 
									AND a.label_hide = 0 
									AND a.label_id = b.product_site_labels_label_id";
				$ret_labels = $db->query($sql_labels);
				$label_exists = false;
				while ($row_labels = $db->fetch_array($ret_labels) and $label_exists==false) // added the AND condition to avoid the case of exiting the loop if atleast one label have value
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
					}
				}	
			}
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
		  		<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="variable_bottom_border">
						<div class="variabletabcontainer">           
							<ul class="variabletab">
								<li id="var_li" class="variableselected" <?php /* onclick is required only in shown in a new row*/if ($pos=='row') {?>onclick="handle_proddetail_variable('var')"<?php }?>><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_PRODVARS']?></li>	
								<?php
								if($pos!='column' and $label_exists) // show the product labels section only if variables in a new row
								{
								?>						
									<li id="label_li" onclick="handle_proddetail_variable('label')"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']?></li>
								<?php
								}
								?>
							</ul>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="variabletable" id="proddet_var_table">
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
								  <tr>
									<td align="left" valign="middle" class="<?php echo $clss?>"><?php echo stripslashes($row_var['var_name'])?></td>
									<td align="left" valign="middle" class="<?php echo $clss?>">
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
									</td>
								  </tr>
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
						?>
							<?php
								while ($row_msg = $db->fetch_array($ret_msg))
								{
									$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
									$var_exists = true;
							?>
									  <tr>
										<td align="left" valign="top" class="<?php echo $clss?>"><?php echo stripslashes($row_msg['message_title'])?></td>
										<td align="left" valign="top" class="<?php echo $clss?>">
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
										</td>
									  </tr>
							<?php
									$i++;
								}
							?>  
						<?php		
						}
						// ######################################################
						// End of variable messages
						// ######################################################
						?>
						</table>
					</td>
				</tr>
			</table>
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
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bulkdiscounttable">
				  <tr>
					<td align="left" class="bulkdiscountheader"><?php echo $Captions_arr['PROD_DETAILS']['BULK_DISC_HEAD']?></td>
				  </tr>
				  <?php
					for($i=0;$i<count($bulkdisc_details['qty']);$i++)
					{
					?>	
					   <tr>
						<td class="bulkdiscountcontent" align="left"><?php echo $bulkdisc_details['qty'][$i].' '.$Captions_arr['PROD_DETAILS']['BULK_FOR'].' '?> 
							<?php echo print_price($bulkdisc_details['price'][$i]).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH']?>
						</td>
					  </tr>
				  <?php
					}
				  ?>
				</table>
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
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="variabletable"  <?php if ($pos=='row'){?> id="proddet_label_table" style="display:none"<?php }?>>
				<?php
				if($pos!='row' and count($label_arr)) // show the product labels section only if variables in a new row
				{
				?>
				<tr>
				<td class="variable_bottom_border" colspan="2">
					<div class="variabletabcontainer">           
						<ul class="variabletab">
												
							<li class="variableselected"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']?></li>
						
						</ul>
					</div>
				</td>
				</tr>
				<?php
				}
					$i=1;
					//while ($row_labels = $db->fetch_array($ret_labels))
					foreach($label_arr as $k=>$v)
					{
						$vals = '';
						$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
						$vals = $v['label_showvalue'];
						//echo '<br/>val'.$v['label_name'].':'.$vals;
						/*if ($row_labels['is_textbox']==1)
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
													
						}*/
						if ($vals)
						{
						?>
							<tr>
								<td align="left" valign="middle" class="<?php echo $clss?>"><?php echo stripslashes($v['label_name'])?></td>
								<td align="left" valign="middle" class="<?php echo $clss?>">: <?php echo $vals?></td>
							</tr>	
						<?php	
							$i++;
						}
							
					}
			?>
				</table>
			<?php
			}
		}
		function show_buttons($row_prod)
		{
			global $Captions_arr,$showqty,$Settings_arr;
			$cust_id 	= get_session_var("ecom_login_customer");
			$showqty	= $Settings_arr['show_qty_box'];// show the qty box
			if($showqty==1)// this decision is made in the main shop settings
			{
	?>
			  <div class="quantity_details"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY']?><input type="text" class="quainput" name="qty"  value="<?php echo ($_REQUEST['qty'])?$_REQUEST['qty']:1?>" maxlength="2" /></div>
	<?php
			}
			// Get the caption to be show in the button
			$caption_key = show_addtocart($row_prod,array(0),'',true);
			if ($caption_key!='') // Check whether the buynow or preorder button is to be displayed
			{
	 ?>
				<input name="Submit_buy" type="submit" class="buttonblackbuy" id="Submit_buy" value="<?php echo $Captions_arr['PROD_DETAILS'][$caption_key];?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addcart'" />
		<?php
			}
			// Check whether the enquire link is to be displayed
			if ($row_prod['product_show_enquirelink']==1)
			{
	 ?>			
				<input name="Submit_enq" type="submit" class="buttonblackbuy" id="Submit_enq" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Enquire'" />
	<?php
			}
		if($cust_id) // ** Show the wishlist button only if logged in 
		{
  ?>
			<br /><input name="submit_wishlist" type="submit" class="buttonblackbuy" id="submit_wishlist" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>"  onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addwishlist'"  />
  <?php
		}	
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
						$prodimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type.',image_thumbpath',$exclude_tabid,0);	
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
						$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type.',image_thumbpath',$exclude_prodid,0);
	
				 } 
					if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
					{
				?>	
					  <table width="100%" border="0" cellpadding="0" cellspacing="3" class="productdethumbtable">
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
							<?php /*?><a href="#" onclick="link_submit('<?php echo $_REQUEST['prod_curtab']?>','<?php echo $v['image_id']?>','<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1)?>',0)" title="<?php echo $title?>"><?php 
							<a href="javascript:showImagePopup('<?php echo $v['image_extralargepath']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>');"  title="<?php echo $title?>">
							*/?>
							<a href="<?php url_root_image($v['image_extralargepath'])?>" rel='lightbox[gallery]' title="<?=$title?>">
							<?php
								 show_image(url_root_image($v['image_iconpath'],1),$title,$title,'preview');
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
				var	arrMainImage = new Array;
				var	arrThumbImage = new Array;
				var	arrBigImage = new Array;
				var tmpHolder = new Array
				var tmpHolderBig = new Array
				var firstTime360 = true
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
					$tabimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type,0,$showonly,1);
					if(count($tabimg_arr))
					{
						$exclude_tabid 	= $tabimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
						$main_img			=  url_root_image($tabimg_arr[0]['image_bigpath'],1);
						$large_img			=  url_root_image($tabimg_arr[0]['image_extralargepath'],1);
						$icon_img			=  url_root_image($tabimg_arr[0]['image_iconpath'],1);
						?>
						<script type="text/javascript">
							arrMainImage[0] = new Array("<?php echo $icon_img?>", "<?php echo $main_img?>", "<?php echo $large_img?>","<?php echo $main_img?>");
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
					$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,$showonly,1);
					if(count($prodimg_arr))
					{
						$exclude_prodid = $prodimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
						$main_img			=  url_root_image($prodimg_arr[0]['image_bigpath'],1);
						$large_img			=  url_root_image($prodimg_arr[0]['image_extralargepath'],1);
						$icon_img			=  url_root_image($prodimg_arr[0]['image_iconpath'],1);
						?>
						<script type="text/javascript">
							arrMainImage[0] = new Array("<?php echo $icon_img?>", "<?php echo $main_img?>", "<?php echo $large_img	?>", "<?php echo $main_img	?>");
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
				$pass_type = 'image_iconpath';
				
				if ($_REQUEST['prod_curtab'])// case if came by clicking the tab
				{
					if ($exclude_tabid)
						$prodthumbimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type.',image_thumbpath',$exclude_tabid,0,0,'rand()');	
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
						$prodthumbimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type.',image_thumbpath',$exclude_prodid,0,0,'rand()');
	
				 }
				 if (count($prodthumbimg_arr))
				 {
				 		$i = 0;
				 		foreach ($prodthumbimg_arr as $k=>$v)
						{
							$icnimg			= url_root_image($v['image_iconpath'],1);
							$icon_arr[$i] 	= $icnimg;
							$bgimg			= url_root_image($v['image_bigpath'],1);
							$large_img		=  url_root_image($v['image_extralargepath'],1);
				?>
								<script type="text/javascript">
									arrThumbImage[<?php echo $i?>] = new Array("<?php echo $icnimg?>", "<?php echo $bgimg?>", "<?php echo $large_img?>", "<?php echo $bgimg?>");
								</script>
				<?php		
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
				<table  style="border:solid 1px  #CCCCCC; width:290px;" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="left" valign="top" style="padding:5px">
					<div class="content_product_container" style="width:290px;">
						<div class="content_product_images"  style="width:290px;">
							<div>
								<div style="display: block;" id="pnlMainImage">
								<div id="dvMainImageZoom" class="content_product_images_mainimage_zoom" style="width: 290px; height: 370px; display: none;" align="left" title="Click and drag image"> <img id="imgMainImageZoom" src="" alt="" style='border:0'> </div>
								<a id="hypMainImage" href="javascript:ShowZoomImage();" style="display: block;clear:both;" onmouseover="window.status='Click on images to enlarge them'; return true;" onmouseout="window.status=''; return true;" title="<?php echo $row_prod['product_name']?>"> <img id="imgMainImage" class="content_product_images_mainimage" src="<?php echo $main_img?>" alt="<?php echo $row_prod['product_name']?>" style="border-width: 0px; display: block;" > </a>
								<a  style="display: block;float:left; width:20%" id="hypZoomPlus" href="javascript:ShowZoomImage();"> <img src="<?php url_site_image('product_ZoomPlus.gif')?>" alt="Zoom In" border="0"></a>
								 <a style="display: none;float:left; width:20%" id="hypZoomMinus" href="javascript:HideZoomImage();"> <img src="<?php url_site_image('product_ZoomMinus.gif')?>" alt="Zoom Out" border="0"></a>
								<a id="hypDragImage" style="border-width: 0px;float:left;clear:right;width:78%;display: block;"> <img src="<?php echo url_site_image('product_clickndrag.gif')?>" alt="Click and drag the zoomed image" style="border-width: 0px;"> </a>
								<?php 
									if($row_prod['product_flv_filename']!='') // make the 
									{
								?>
									<a id="hypVideoImage" href="javascript:ShowVideo();" style="border-width: 0px;float:left;width:78%; overflow:hidden"> <img src="<?php url_site_image('product_ViewCatwalk.gif')?>" alt="Click here to view the video" style="border-width: 0px; "></a> 
								<?php
									}
									else
										echo '<div style="float:left;width:78%; overflow:hidden"><img src="'.url_site_image('blank.gif',1).'" border="0" id="img_blank"/></div>';
								?>	
								</div>
								<?php 
									if($row_prod['product_flv_filename']!='')
									{
								?>
										<div style="display: none; height:370px" id="divFlash" class="content_product_flash">
<script type="text/javascript" src="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/scripts/swfobject.js"></script>
										<script type="text/javascript">
		<?php /*?>var s1 = new SWFObject("http://<?php echo $ecom_hostname?>/themes/<?php echo $ecom_themename?>/swf/player.swf","ply","288","370","9","#FFFFFF");<?php */?>
		var s1 = new SWFObject("http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/swf/player.swf","ply","288","370","9","#FFFFFF");
		s1.addParam("allowfullscreen","true");
		s1.addParam("allowscriptaccess","always");
		s1.addParam("flashvars","file=http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/product_flv/<?php echo $row_prod['product_flv_filename']?>");
		s1.write("divFlash");
	</script>
										</div>
									
										<div class="content_product_images_video_control">
										<div id="pnlVideoControl"><img id="blank_zoom" src="<?php url_site_image('zoom_blank.gif')?>" alt="" style='display: none;float:left; width:20%'> <a id="hypPhotoImage" href="javascript:HideVideo();" style="display: none;float:left; width:78%"> <img src="<?php url_site_image('product_ViewPictures.gif')?>" alt="Click here to go back to images" style="border-width: 0px;"> </a>
										</div>
								 <?php
									}
								?>
							  </div>
								<div id="pnlThumbImages" class="content_product_images_thumbimages">
								<ul class="hoverbox" >
								<?php
									for($i=0;$i<count($icon_arr);$i++)
									{
								?>	
									 <li><a href="javascript:ReplaceImageFromThumb(<?php echo ($i+1)?>);" onmouseover="window.status='Click on images to enlarge them'; return true;" onmouseout="window.status=''; return true;" title="Click on images to enlarge them"> <img id="imgThumb<?php echo ($i+2)?>" src="<?php echo $icon_arr[$i]?>" alt="Click on images to enlarge them" style="border-width: 1px;"></a>
									 </li>
								<?php
									}
								?> 
								</ul>
							  </div>
							</div>
					  </div>
					  </div>
					</td>
				</tr>
				</table>
				<span id="content_product_loading" style="display:none;"><span id="container"><img src="<?php url_site_image('product_loading_spinner.gif')?>" alt="Loading..." style="margin-left:50%; margin-top:50%;"></span></span>	
				<script language="JavaScript">LoadTmpHolder()</script>
			<?php
			}
			function Show_Image_Normal($row_prod)
			{
				global $db,$ecomn_siteid,$ecom_hostname,$ecom_themename;	
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
						<a href="<?php url_root_image($tabimg_arr[0]['image_extralargepath'])?>" title="<?php echo $row_prod['product_name']?>" rel='lightbox[gallery]' >
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
							/*<a href="javascript:showImagePopup('<?php echo $prodimg_arr[0]['image_extralargepath']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')"  title="Click to Zoom">*/
							?>
							<a href="<?php url_root_image($prodimg_arr[0]['image_extralargepath'])?>" rel='lightbox[gallery]' title="<?=$row_prod['product_name']?>">
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
					}
					$ret_arr['exclude_tabid']		= $exclude_tabid;
					$ret_arr['exclude_prodid'] 	= $exclude_prodid;
					return $ret_arr;
			}
	};	
?>