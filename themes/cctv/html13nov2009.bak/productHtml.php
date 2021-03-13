<?php
	/*############################################################################
	# Script Name 		: productHtml.php
	# Description 		: Page which holds the display logic for product details
	# Coded by 			: Sny
	# Created on		: 23-Jun-2009
	# Modified by		: 
	# Modified On		: 
	
	##########################################################################*/
	class product_Html
	{
		// Defining function to show the selected product details
		function Show_ProductDetails($ret_prod)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					 $ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$meta_arr,$ecom_twitteraccountId;
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
		<div class="tree_con">
		<div class="tree_top"></div>
        <div class="tree_middle">
     	<div class="pro_det_treemenu"><ul><?php echo generate_tree(-1,$_REQUEST['product_id'],'<li>','</li>')?></ul></div>
        </div>
        <div class="tree_bottom"></div>
      	</div>
		<form method="post" name="frm_proddetails" id="frm_proddetails" action="<?php url_link('manage_products.html')?>" class="frm_cls" onsubmit="return prod_detail_submit(this)">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
		<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
		<input type="hidden" name="pass_combid" value="<?php echo $_REQUEST['pass_combid']?>" />
		<input type="hidden" name="comb_img_allowed" id="comb_img_allowed" value="<?php echo $row_prod['product_variablecombocommon_image_allowed']?>" />
		<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
		<script type="text/javascript">
		function download_pdf() {
			document.getElementById('bw').innerHTML = '<iframe src="http://www.web2pdfconvert.com/HTMLtoPDFMaster.aspx?cURL=http://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>&author=&subj=&title=&app=http://www.web2pdfconvert.com&keywords=&allowcpy=&allowmodif=&allowprn=&keylen=&userpass=&ownerpass=&preservelinks=yes&compress=flate&marginleft=&marginright=&margintop=&marginbottom=&psize=&porient=&ctype=&allowscript=yes&outputmode=stream">';
		show_processing();
		setTimeout('hide_processing()', 20000);
	  	}
		function ajax_return_productdetailscontents() 
		{
			var ret_val = '';
			var disp 	= 'no';
			if(req.readyState==4)
			{
				if(req.status==200)
				{
					ret_val 		= req.responseText;
					targetobj 	= eval("document.getElementById('"+document.getElementById('ajax_div_holder').value+"')");
					targetobj.innerHTML = ret_val; /* Setting the output to required div */
					if(document.getElementById('ajax_div_holder').value=='price_holder')
					{
						handle_show_prod_det_bulk_disc('bulk');
					}
					else if(document.getElementById('ajax_div_holder').value=='bulkdisc_holder')
					{
						if(document.getElementById('comb_img_allowed').value=='Y' ) /* Do the following only if variable combination image option is set for current product */ 
							handle_show_prod_det_bulk_disc('main_img');
					}
					else if(document.getElementById('ajax_div_holder').value=='mainimage_holder')
					{
						if(document.getElementById('comb_img_allowed').value=='Y') /* Do the following only if variable combination image option is set for current product */ 
							handle_show_prod_det_bulk_disc('more_img');
					}
				}
				else
				{
					 alert(req.status);
				}
			}
		}
		function handle_image_swap(src_id)
		{
			imglocal_arr = new Array();
			var img_path = '<?php echo "http://$ecom_hostname/images/$ecom_hostname/"?>';
			var destindex = 0;
			if(document.getElementById('main_img_hold_var'))
			{
				var main_img = document.getElementById('main_img_hold_var').value
				if(main_img!='')
				{
					imglocal_arr[0]  = main_img;
				}	
			}
			if(document.getElementById('more_img_hold_var'))
			{	
				var more_img = document.getElementById('more_img_hold_var').value;
				if(more_img!='')
				{
					more_img_arr = more_img.split('~');
					for(i=0;i<more_img_arr.length;i++)
					{
						imglocal_arr[i+1] = more_img_arr[i];
					}
				}
			}
			if (src_id)
			{
				document.getElementById('main_det_img').src = img_path + 'big/'+imglocal_arr[src_id];
				
				srcobj = eval ("document.getElementById('moreid_"+src_id+"')");
				srcobj.src =  img_path + 'icon/'+imglocal_arr[destindex];
				
				var tempval 			= imglocal_arr[destindex];
				imglocal_arr[destindex] 	= imglocal_arr[src_id];
				imglocal_arr[src_id] 		= tempval;
				
				document.getElementById('main_img_hold_var').value = imglocal_arr[destindex];
				var temp_hold = '';
				if (imglocal_arr.length>1)
				{
					for(i=1;i<imglocal_arr.length;i++)
					{
						if(temp_hold!='')
							temp_hold += '~';
						temp_hold += imglocal_arr[i];
					}
				}
				document.getElementById('more_img_hold_var').value = temp_hold;
			}
		}
		function handle_show_prod_det_bulk_disc(opt)
		{
			var varstr 	= '';
			var varidstr	= '';
			for(i=0;i<document.frm_proddetails.elements.length;i++)
			{
				if (document.frm_proddetails.elements[i].name.substr(0,4)=='var_')
				{
					splt_arr = 	document.frm_proddetails.elements[i].name.split('_');				
					if (varstr!='')
						varstr += '~';
					if (varidstr!='')
						varidstr += '~';	
					varstr 	+= document.frm_proddetails.elements[i].value;	
					varidstr 	+= splt_arr[1];	
				}
			}					
			var fpurpose									= '';
			switch (opt)
			{
				case 'price':
					var retdivid										= 'price_holder';
					var qrystr										= '';
					fpurpose										= 'ajax_show_variable_price';	
				break;
				case 'bulk':
					var retdivid										= 'bulkdisc_holder';
					var qrystr										= '';
					fpurpose										= 'ajax_show_bulk_discount';
				break;	
				case 'main_img':
					var retdivid									= 'mainimage_holder';
					obj = document.getElementById('mainimage_holder');
					if (!obj)
						return;
					var qrystr										= '';
					fpurpose										= 'ajax_show_main_image';
				break;	
				case 'more_img':
					var retdivid									= 'moreimage_holder';
					obj = document.getElementById('moreimage_holder');
					if (!obj)
						return;
					var qrystr										= '';
					if (document.getElementById('main_img_hold_id'))
					{
						qrystr = 'exclude_id='+document.getElementById('main_img_hold_id').value;
						
					}
					fpurpose										= 'ajax_show_more_image';
				break;			
			};	
				document.getElementById('ajax_div_holder').value = retdivid;
				retobj 											= eval("document.getElementById('"+retdivid+"')");
				if(opt=='price')
				{
					retobj.innerHTML 							= "<div align='center'><img src ='<?php echo url_site_image('loading.gif',1)?>' border='0'></div>";		
				}
				else
				{
					retobj.innerHTML 							= "";	
				}	
																
			/* Calling the ajax function */
			Handlewith_Ajax('<?php echo $ORG_DOCROOT?>/includes/base_files/products.php','ajax_fpurpose='+fpurpose+'&'+qrystr+'&prod_id='+<?php echo $_REQUEST['product_id']?>+'&pass_var='+varstr+'&pass_varid='+varidstr);
		}
		</script>
          <?php
		  		
				if($alert)
				{
			?>
					  <div class="red_msg"> - <?php echo $alert?> - </div>
			<?php
				}
				elseif($_REQUEST['stockalert'])
				{
			?>
					<div class="red_msg"> - <?php echo $Captions_arr['PROD_DETAILS'][$_REQUEST['stockalert']]	?> - </div>
			<?php	
				}
			?>
          <div class="pro_det_name"><?php echo stripslashes($row_prod['product_name'])?></div>
			<?php
			if($_REQUEST['for_notification']==1) // make the decision whether the instock message is to be displayed here or in div
			{
				$this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
			}	
			else // case if displaying the instock notification message here itself
				$this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
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
			<div class="pro_det_lnks">
	      	<div class="pro_det_lnks_lft"></div>
		    <div class="pro_det_lnks_mid">
			<?php /*?><? if($row_prod['product_show_pricepromise']==1){?>
			<a href="<?php url_link('pricepromise'.$_REQUEST['product_id'].'.html')?>" class="productdetailslink" title="">Price Promise</a>
			<? }?>
			<? if($row_prod['product_freedelivery']==1){?>
			<a href="<?php url_link('freedelivery'.$_REQUEST['product_id'].'.html')?>" class="productdetailslink" title="">Free Delivery</a>
			<? }?><?php */?>
						<?php
							// Check whether the compare feature is enabled in the product details page 	
							if( $compare_show==1)
							{
								$def_cat_id = $row_prod['product_default_category_id'];
							?>
								
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
								<a href="javascript:download_pdf();" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADPDF'];?></a>
							<?php
							}
							?>
				</div>
				<div class="pro_det_lnks_rht"></div>
		       </div>
          <?php				
		  }
			  	// Check whether the images to be shown in flash or as normal
				if($prod_img_show_type=='FLASH' or $prod_img_show_type=='FLASH_ROTATE')
				{
					// Calling the flash wrapper to display the images
					?>
					<div class="flash_outerdiv">
					<?php
					// Check whether the product review module is active for the site
					$module_name = 'mod_product_reviews';
					if(in_array($module_name,$inlineSiteComponents))
					{
					?>
						<div class="reviewscore">
					<?php
							echo $Captions_arr['PROD_DETAILS']['PRODDET_REVIEW_CAPTION'];
							for ($i=0;$i<$row_prod['product_averagerating'];$i++)
							{
								echo '<img src="'.url_site_image('reviewstar_on.gif',1).'" border="0" alt="revscoreimg" />&nbsp;'; 
							}
							for ($i=$row_prod['product_averagerating'];$i<5;$i++)
							{
								echo '<img src="'.url_site_image('reviewstar_off.gif',1).'" border="0"  alt="revscoreimg"/>&nbsp;'; 
							}
							?>
						</div>
					<?php	
					}
					if ($Settings_arr['product_show_instock'])
					{
					?>
						<div class="stockdetailstd"><?php echo get_stockdetails($_REQUEST['product_id'])?></div>
					<?php
					}	
					if ($Settings_arr['bonus_points_instock'] and $row_prod['product_bonuspoints']>0)
					{
					?>
						<div class="product_bonuspoints"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTS'].' '.$row_prod['product_bonuspoints']?></div>
					<?php
					}
					if ($row_prod['product_deposit']>0 and $row_prod['product_deposit_message']!='')
					{
					?>
						<div class="productdeposit_price"><? echo $Captions_arr['PROD_DETAILS']['PRODDET_DEPOSIT_REQ'] ?> <?php echo ($row_prod['product_deposit']).' '.$Captions_arr['PROD_DETAILS']['DEP_PERCENTAGE']?>
						<?php echo nl2br(stripslashes($row_prod['product_deposit_message']))?></div>
					<?php	
					}
							echo '<div id="price_holder">';			
								$price_class_arr['ul_class'] 		= 'prodeulprice';
								$price_class_arr['normal_class'] 	= 'productdetnormalprice';
								$price_class_arr['strike_class'] 	= 'productdetstrikeprice';
								$price_class_arr['yousave_class'] 	= 'productdetyousaveprice';
								$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
							
							echo show_Price($row_prod,$price_class_arr,'prod_detail');
							echo '</div>';
							show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
					?>
					</div>
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
					<div class="pro_det_main">
		        	<div class="pro_det_main_tp"></div>
			      	<div class="pro_det_main_mid">

					<?php
					if($prod_img_show_type=='JAVA') // case of showing javascript swap
					{
						$this->Show_Javascript_Image_Swapper($row_prod);
					}
					else // case of showing normal image display
					{
					?>
						<div class="pro_det_image" id="mainimage_holder">
					<?php
						$ret_arr = $this->Show_Image_Normal($row_prod);
					?>
						</div>		
					<?php	
						$exclude_tabid			= $ret_arr['exclude_tabid'];
						$exclude_prodid			= $ret_arr['exclude_prodid'];
					}	
					// Check whether the product review module is active for the site
					$module_name = 'mod_product_reviews';
					if(in_array($module_name,$inlineSiteComponents))
					{
					?>
						<div class="reviewscore"><?php
							echo $Captions_arr['PROD_DETAILS']['PRODDET_REVIEW_CAPTION'];
							for ($i=0;$i<$row_prod['product_averagerating'];$i++)
							{
								echo '<img src="'.url_site_image('reviewstar_on.gif',1).'" border="0" alt="revscoreimg" />&nbsp;'; 
							}
							for ($i=$row_prod['product_averagerating'];$i<5;$i++)
							{
								echo '<img src="'.url_site_image('reviewstar_off.gif',1).'" border="0"  alt="revscoreimg"/>&nbsp;'; 
							}
							?>
						</div>
					<?php
					}
					if ($Settings_arr['product_show_instock'])
					{
					?>
						<div class="stockdetailstd"><?php echo get_stockdetails($_REQUEST['product_id'])?></div>
					<?php
					}	
					if ($Settings_arr['bonus_points_instock'] and $row_prod['product_bonuspoints']>0)
					{
					?>
						<div class="product_bonuspoints"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTS'].' '.$row_prod['product_bonuspoints']?></div>
					<?php
					}
					if ($row_prod['product_deposit']>0 and $row_prod['product_deposit_message']!='')
					{
					?>
						<div class="productdeposit_price"><? echo $Captions_arr['PROD_DETAILS']['PRODDET_DEPOSIT_REQ'] ?> <?php echo ($row_prod['product_deposit']).' '.$Captions_arr['PROD_DETAILS']['DEP_PERCENTAGE']?>
						<?php echo nl2br(stripslashes($row_prod['product_deposit_message']))?></div>
					<?php	
					}?>
					<div><br /><br /><b><?php echo $Captions_arr['PROD_DETAILS']['BOOKMARK_HEADING']?></b><br />
					<?php bookmarks(url_product($row_prod['product_id'],$row_prod['product_name'],1),htmlspecialchars($meta_arr['title']),$ecom_twitteraccountId); ?>
					</div>
						<div class="pro_det_price">
						<?php
						echo '<div id="price_holder">';		
						$price_class_arr['ul_class'] 		= 'prodeulprice';
						$price_class_arr['normal_class'] 	= 'productdetnormalprice';
						$price_class_arr['strike_class'] 	= 'productdetstrikeprice';
						$price_class_arr['yousave_class'] 	= 'productdetyousaveprice';
						$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
						
						echo show_Price($row_prod,$price_class_arr,'prod_detail');
						echo '</div>';
						// show the bulk discount details
						echo '<div id="bulkdisc_holder">';
						$this->show_BulkDiscounts($row_prod);
						echo '</div>';
						if($row_prod['product_variable_in_newrow']==0) // case of showing the variables in the same row as that of image
						{
						// Show the product variables and product messages
							$var_listed = $this->show_ProductVariables($row_prod,'column');
						}
						?>	
						<div class="detils_buy">	
						<?php
						if(!$button_displayed)
							$button_displayed=$this->show_buttons($row_prod);
						?>
						</div>
						 <div id="moreimage_holder">
						 <?php
							// Showing additional images
							$this->show_more_images($row_prod,$exclude_tabid,$exclude_prodid);
						?>
						</div>
						</div>
						</div>
						
						<div class="pro_det_main_btm"></div>
						</div>
						<?php
						if($row_prod['product_variable_in_newrow']==1) // case of showing the variables in the same row as that of image
						{
						// Show the product variables and product messages
							$var_listed = $this->show_ProductVariables($row_prod,'row');
						}
						// Show the product label details
						$this->show_ProductLabels('row');
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
								echo '<div id="bulkdisc_holder">';
								$this->show_BulkDiscounts($row_prod);
								echo '</div>';
							?>
						</td>
					  </tr>
          <?php	
				}
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
        <div class="pro_det_size">
        <div class="pro_det_size_tp"></div>
       	<div class="pro_det_size_mid">
		  <table width="100%" border="0" cellpadding="2" cellspacing="0" class="productsizecharttable">
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
            </table>
			</div>
	       	<div class="pro_det_size_btm"></div>
    		</div>
          <? 
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
           <div class="pro_det_dwn" >
	       <div class="pro_det_dwn_tp"></div>
			<div class="pro_det_dwn_hdr">
	   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="productdownloadtable">
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
									if($row_video['attachment_icon_img']!='')
										$download_icon = "http://$ecom_hostname/images/$ecom_hostname/attachments/icons/".$row_video['attachment_icon_img'];
									else
										$download_icon = url_site_image('download-icon.gif',1);
								?>
                          				<li><span><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_video['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><img src="<?php echo $download_icon?>" border="0" /></a></span><span><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_video['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php //echo $cnts++. ?><?php echo stripslashes($row_video['attachment_title'])?></a></span></li>
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
            </table>
			 </div>
	       <div class="pro_det_dwn_btm"></div>
    	   </div>
          <?php
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
          <div class="pro_det_conts">
           
            <div class="pro_det_conts_top"></div>
            <div class="pro_det_conts_mid">
            <div class="pro_det_tab">
			 <a href="#" name="protabs"></a>
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
                </div>
				<div class="pro_det_tab_cnts"><?php
						echo $tabs_content_arr[$curtab];
					  ?>
           		</div>
       		  </div>
			  </div>
                 <div class="pro_det_conts_btm"></div>
          </div>
          <?php
				}	
		?>		
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
		}
		// ** Function to show the details of products which are linked with current product.
		function Show_Linked_Product($ret_prod)
		{
			global $ecom_siteid,$db,$Captions_arr,$Settings_arr;
			// Calling the function to get the type of image to shown for current 
			$pass_type = get_default_imagetype('link_prod');
			$prod_compare_enabled = isProductCompareEnabled();
			?>
			<div class="link_pdt_headr"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_PEOPLE_INTERESTED'];?></div>
			<?php
			switch($Settings_arr['linked_prodlisting'])
			{
			case '1row':
		?>
				<?php 
											?>	
											<?php
											while($row_prod = $db->fetch_array($ret_prod))
											{
												?>
										        <div class="mid_shelfB">
												<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfB_table">
												
												<tr>
													<td class="mid_shelfB_top_lf">&nbsp;</td>
													<td class="mid_shelfB_top_mid"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a></td>
													<td class="mid_shelfB_top_rt">&nbsp;</td>
												</tr>
												<tr>
														<td colspan="3" class="mid_shelfB_mid">
														<div class="shelfBimg">
																
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" >
																<?php
																	// Calling the function to get the type of image to shown for current 
																	//$pass_type = get_default_imagetype('midshelf');
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
															<?php if($comp_active)  {
																dislplayCompareButton($row_prod['product_id']);
															}?>
														</div> 
														<div class="shelfB_cnts">
																	 
																	<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
																<ul class="shelfBul">
																<?php
																 
																	$price_class_arr['ul_class'] 		= 'shelfBul';
																	$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
																	$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
																	$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
																	$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
																	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																	show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
																	show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
																	?>


																
																</ul>	
													   </div>
													  </td>
												</tr>
												<tr>
												<td class="mid_shelfB_btm_lf">&nbsp;</td>
												<td class="mid_shelfB_btm_mid">
													<?php
							                          $frm_name = uniqid('linked_');
													?>	
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
														<input type="hidden" name="fpurpose" value="" />
														<input type="hidden" name="fproduct_id" value="" />
														<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
														<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
														<div class="infodiv">
															<div class="infodivleft">		<?php show_moreinfo($row_prod,'infolink')?>	</div>
															<div class="infodivright"><?php
																$class_arr 					= array();
																$class_arr['ADD_TO_CART']	= 'quantity_infolink';
																$class_arr['PREORDER']		= 'quantity_infolink';
																$class_arr['ENQUIRE']		= 'quantity_infolink';
																show_addtocart($row_prod,$class_arr,$frm_name)
															?> </div>
														</div>
														</form>
												  </td>
												<td class="mid_shelfB_btm_rt">&nbsp;</td>
												</tr>
												</table>
												
										</div>
										 <?php 
										 }
		break;
		case '2row':
									$pass_type = 'image_gallerythumbpath';
									?>
									<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA">
									
									<?php
									$max_col = 3;
									$cur_col = 0;
									$prodcur_arr = array();
									while($row_prod = $db->fetch_array($ret_prod))
									{
									if($cur_col==0)
										echo '<tr>';
									$cur_col++;
									$prodcur_arr[] = $row_prod;
									//##############################################################
									// Showing the title, description and image part for the product
									//##############################################################
									
									if($cur_col%3==0)
									{
									$cls ='spcl_shelfA_right'; 
									}
									else
									$cls ='spcl_shelfA_left'; 
									?>
									<td class="spcl_shelfA_left">
									<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="spcl_shelfA_table">
										<tr>
											<td class="spcl_shelfA_top_lf">&nbsp;</td>
											<td class="spcl_shelfA_top_mid" ><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a></td>
											<td class="spcl_shelfA_top_rt">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" class="spcl_shelfA_mid">
											<ul class="spcl_shelfAul">
											<li class="spcl_shelfAimg">
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
																				<?php
																					// Calling the function to get the type of image to shown for current 
																					//$pass_type = get_default_imagetype('midshelf');
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
																				
											<?php if($comp_active)  {
														dislplayCompareButton($row_prod['product_id']);
																}?>
											</li>
											<li class="spcl_shelfAimg_A">
											<?php
											$price_class_arr['ul_class'] 		= 'spcl_shelfBul';
											$price_class_arr['normal_class'] 	= 'spcl_shelfAnormalprice';
											$price_class_arr['strike_class'] 	= 'spcl_shelfAstrikeprice';
											$price_class_arr['yousave_class'] 	= 'spcl_shelfAyousaveprice';
											$price_class_arr['discount_class'] 	= 'spcl_shelfAdiscountprice';
											echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
											show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
											?>
											</li></ul>	
											</td>
										</tr>
										<tr>
										<td class="spcl_shelfA_btm_lf">&nbsp;</td>
										<?php  $frm_name = uniqid('shopdet_'); ?>
										<td class="spcl_shelfA_btm_mid">
										
								<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
									<input type="hidden" name="fpurpose" value="" />
									<input type="hidden" name="fproduct_id" value="" />
									<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
									<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
										<div class="infodiv_shlfA">
										<div class="infodivleft_shlfA"><?php show_moreinfo($row_prod,'infolink')?></div>
										<div class="infodivright_shlfA">
										<?php
										$class_arr 					= array();
										$class_arr['ADD_TO_CART']	= 'quantity_infolink';
										$class_arr['PREORDER']		= 'quantity_infolink';
										$class_arr['ENQUIRE']		= 'quantity_infolink';
										show_addtocart($row_prod,$class_arr,$frm_name)
										?></div>
										</div></form></td>
										<td class="mid_shelfA_btm_rt">&nbsp;</td>
										</tr>
									</table>
									</td>
									<?
									if($cur_col>=$max_col)
									{ 
									echo "</tr>";
									$cur_col = 0;
									}
									}
									if($cur_col<$max_col)
									{ 
									echo "</tr>";
									}	
									?>
									</table>
									<?php
		break;
		}	
		?>
	
		<?php
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
			<div class="tree_con">
										<div class="tree_top"></div>
											<div class="tree_middle">
												<div class="pro_det_treemenu">
													<ul>
													<li><?php echo generate_tree(-1,$_REQUEST['product_id'],'<li>','</li>')?></a> </li>
													</ul>
												</div>
											</div>
										<div class="tree_bottom"></div>
										</div>
				<div class="mid_shelfB_name"><a name="comp_prods"></a><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_HEAD'];?></div>
				
				<div class="productdetd_main"><?php  $msg = $Captions_arr['PROD_DETAILS']['PRODDET_MAX_COMP_PROD']; $msg = str_replace('[prodno]',$Settings_arr['no_of_products_to_compare'],$msg); echo $msg?></div>
				<div class="prod_compare_divout">
					<div class="prod_backdetails"><input type="button" name="prodet_backprod" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_BACK_TO_DETAIL'];?>" class="buttonred_cart" onclick="window.location='<?php url_product($_REQUEST['product_id'],'',-1)?>'"/></div>
					<div  class="prod_comp_button"><input type="button" name="prodet_comparebutton" value="<?php echo $Captions_arr['PROD_DETAILS']['COMPARE_PRODUCTS'];?>" class="buttonred_cart" onclick="handle_proddet_compare()"/></div>
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
					<div class="mid_shelfB">
												<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfB_table">
												
												<tr>
													<td class="mid_shelfB_top_lf">&nbsp;</td>
													<td class="mid_shelfB_top_mid"><div class="prod_compdiv"><input type="checkbox" name="chkproddet_comp_<?php echo $row_prod['product_id']?>" id="chkproddet_comp_<?php echo $row_prod['product_id']?>" value="<?php echo $row_prod['product_id']?>" <?php echo $compare_checked?>/></div><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink_compare"><?php echo stripslashes($row_prod['product_name'])?></a></td>
													<td class="mid_shelfB_top_rt">&nbsp;</td>
												</tr>
												<tr>
														<td colspan="3" class="mid_shelfB_mid">
														<div class="shelfBimg">
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
															<?php if($comp_active)  {
																dislplayCompareButton($row_prod['product_id']);
															}?>
														</div> 
														<div class="shelfB_cnts">
																	 
																	<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
																<ul class="shelfBul">
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
																
																</ul>	
													   </div>
													  </td>
												</tr>
												<tr>
												<td class="mid_shelfB_btm_lf">&nbsp;</td>
												<td class="mid_shelfB_btm_mid">
													<?php
													$frm_name = uniqid('linked_');
													?>	
														<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
								<input type="hidden" name="fpurpose" value="" />
								<input type="hidden" name="fproduct_id" value="" />
								<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
								<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
														<div class="infodiv">
															<div class="infodivleft">		<?php show_moreinfo($row_prod,'infolink')?>	</div>
															<div class="infodivright">
															<?php
																$class_arr 					= array();
																$class_arr['ADD_TO_CART']	= 'quantity_infolink';
																$class_arr['PREORDER']		= 'quantity_infolink';
																$class_arr['ENQUIRE']		= 'quantity_infolink';
																show_addtocart($row_prod,$class_arr,$frm_name);
															?></div>
														</div>
														</form>
												  </td>
												<td class="mid_shelfB_btm_rt">&nbsp;</td>
												</tr>
												</table>
												
										</div>
			<?php
				}
			?>	
			<div class="comp_bottom_button"><input type="button" name="prodet_comparebutton" value="<?php echo $Captions_arr['COMMON']['COMPARE_PRODUCTS'];?>" class="buttonred_cart" onclick="handle_proddet_compare()"/></div>
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
				<div class="<?php echo ($pos=='row')?'pro_det_varble':'pro_det_varble_col'?>">
				<div class="<?php echo ($pos=='row')?'pro_det_varble_tp':'pro_det_varble_tp_col'?>"></div>
				<div class="<?php echo ($pos=='row')?'pro_det_varble_hdr':'pro_det_varble_hdr_col'?>"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_PRODVARS']?></div>
				<div class="<?php echo ($pos=='row')?'pro_det_varble_mid':'pro_det_varble_mid_col'?>">
				<table class="variabletable" id="proddet_var_table" width="100%" border="0" cellpadding="0" cellspacing="0">
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
									if($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
									{
										$onchange_function = " onchange ='handle_show_prod_det_bulk_disc(\"price\")' ";
									}
									elseif($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='N')
									{
										$onchange_function = " onchange ='handle_show_prod_det_bulk_disc(\"price\")' ";
									}
									elseif($row_prod['product_variablecomboprice_allowed']=='N' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
									{
										$onchange_function = " onchange ='handle_show_prod_det_bulk_disc(\"main_img\")' ";
									}
									else
									{
										$onchange_function = '';
									}
								?>
										<select name="var_<?php echo $row_var['var_id']?>" <?php echo $onchange_function?>>
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
			</div>
			<div class="<?php echo ($pos=='row')?'pro_det_varble_btm':'pro_det_varble_btm_col'?>"></div>
			</div>
			<?php
			}
			return $var_exists;
		}
		/* Function to show the bulk discount*/
		function show_BulkDiscounts($row_prod,$var_arr=array())
		{
			global $db,$ecom_siteid,$Captions_arr;
			if (count($var_arr)==0 and $row_prod['product_variablecomboprice_allowed']=='Y') // case if variable combination price is allowed and also if var arr is null
			{
				$sql_var = "SELECT var_id,var_name  
								FROM 
									product_variables 
								WHERE 
									products_product_id = ".$row_prod['product_id']." 
									AND var_hide= 0 
									AND var_value_exists = 1 
								ORDER BY 
									var_order";
				$ret_var = $db->query($sql_var);
				if($db->num_rows($ret_var))
				{
					while ($row_var = $db->fetch_array($ret_var))
					{
						$curvar_id= $row_var['var_id'];
						// Get the value id of first value for this variable
						$sql_data = "SELECT var_value_id 
												FROM 
													product_variable_data 
												WHERE 
													product_variables_var_id = ".$curvar_id." 
												ORDER BY var_order  
												LIMIT 
													1";
						$ret_data = $db->query($sql_data);
						if ($db->num_rows($ret_data))
						{
							$row_data = $db->fetch_array($ret_data);
						}							
						$var_arr[$curvar_id] = $row_data['var_value_id'];
					}
				}
			}
			// Section to show the bulk discount details
			$comb_arr = get_combination_id($row_prod['product_id'],$var_arr);
			$bulkdisc_details = product_BulkDiscount_Details($row_prod['product_id'],$comb_arr['combid']);
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
				if($label_exists==true)
				{
		  ?>
		  		<div class="pro_det_ke_fea">
		        <div class="pro_det_ke_fea_tp"></div>
			   <div class="pro_det_ke_fea_mid">
				<table class="key_table" width="100%" border="0" cellpadding="0" cellspacing="0">
				
					<tr>
					<td class="key_table_hedr" colspan="2"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']?></li>
					</td>
					</tr>
				<?php
					$i=1;
					foreach($label_arr as $k=>$v)
					{
						$vals = '';
						$clss = ($i%2==0)?'key_table_tdA':'key_table_tdB';
						$vals = $v['label_showvalue'];
						if ($vals)
						{
						?>
							<tr>
								<td align="left" valign="middle" class="key_table_tdA"><?php echo stripslashes($v['label_name'])?></td>
								<td align="left" valign="middle" class="key_table_tdB">: <?php echo $vals?></td>
							</tr>	
						<?php	
							$i++;
						}
							
					}
			?>
				</table>
				</div>
			    <div class="pro_det_ke_fea_btm"></div>
			    </div>
			<?php
				}
			}
		}
		function show_buttons($row_prod)
		{
			global $Captions_arr,$showqty,$Settings_arr;
			$cust_id 	= get_session_var("ecom_login_customer");
			$showqty	= $Settings_arr['show_qty_box'];// show the qty box
			$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslashes($row_prod['product_det_qty_caption']):$Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY'];
			if($showqty==1)// this decision is made in the main shop settings
			{
				if($row_prod['product_det_qty_type']=='NOR')
				{
	?>
			  <div class="quantity_details"><?php echo $cur_qty_caption?><input type="text" class="quainput" name="qty"  value="<?php echo ($_REQUEST['qty'])?$_REQUEST['qty']:1?>" maxlength="2" /></div>
	<?php
				}
				elseif($row_prod['product_det_qty_type']=='DROP')
				{
					$dropdown_values = explode(',',stripslashes($row_prod['product_det_qty_drop_values']));
					if (count($dropdown_values) and stripslashes($row_prod['product_det_qty_drop_values'])!='')
					{
					?>
						<div class="quantity_details"><?php echo $cur_qty_caption ?>
						<select name="qty">
						<?php 
							$qty_prefix = stripslashes($row_prod['product_det_qty_drop_prefix']);
							$qty_suffix = stripslashes($row_prod['product_det_qty_drop_suffix']);
							foreach ($dropdown_values as $k=>$v)
							{
								$show_val = trim($v);
								if (is_numeric($show_val))
								{
						?>
								<option value="<?php echo $show_val?>"><?php echo $qty_prefix.' '.$show_val.' '.$qty_suffix?></option>
						<?php
								}	
							}
						?>
						</select>
						</div>
					<?php	
					}				
				}
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
			$pass_type = 'image_iconpath';
			
			if ($_REQUEST['prod_curtab'] and $row_prod['product_variablecombocommon_image_allowed']=='N')
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
				if($row_prod['product_variablecombocommon_image_allowed']=='Y')
				{
					if ($exclude_prodid)
						$prodimg_arr = get_imagelist_combination($row_prod['default_comb_id'],'image_thumbpath',$exclude_prodid,0);
				}		
				else
				{
					if ($exclude_prodid)
						$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type.',image_thumbpath',$exclude_prodid,0);
				}
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
				  if ($_REQUEST['prod_curtab'] and $row_prod['product_variablecombocommon_image_allowed']=='N')
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
						if($row_prod['product_variablecombocommon_image_allowed']=='Y')
							$prodimg_arr = get_imagelist_combination($row_prod['default_comb_id'],$pass_type,0,1);
						else
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