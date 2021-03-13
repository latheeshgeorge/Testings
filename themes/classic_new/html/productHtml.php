<?php
	/*############################################################################
	# Script Name 	: productHtml.php
	# Description 		: Page which holds the display logic for product details
	# Coded by 		: Sny
	# Created on		: 22-Jan-2008
	# Modified by		: Sny
	# Modified On		: 04-Aug-2008
	
	##########################################################################*/
	class product_Html
	{
		// Defining function to show the selected product details
		function Show_ProductDetails($row_prod)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					 $ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$meta_arr,$ecom_twitteraccountId;
			// ** Fetch any captions for product details page
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			
			// ** Fetch the product details
			//$row_prod	= $db->fetch_array($ret_prod);
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
	<?php /*?>	<form method="post" name="frm_special_comp" id="frm_special_comp" action="<?php url_productcompare($prodId,$_REQUEST['product_id'],'','')?>#comp_prods" class="frm_cls">
		<input type="hidden" name="after_comp" value="1" />
		</form><?php */?>
		<div class="treemenu"><?php echo generate_tree(-1,$_REQUEST['product_id'])?></div>
		<form method="post" name="frm_proddetails" id="frm_proddetails" action="<?php url_link('manage_products.html')?>" class="frm_cls" onsubmit="return prod_detail_submit(this)">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
		<input type="hidden" name="pricepromise_url" value="<?php url_link('pricepromise'.$row_prod['product_id'].'.html')?>" />
		<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
		<input type="hidden" name="pass_combid" value="<?php echo $_REQUEST['pass_combid']?>" />
		<input type="hidden" name="comb_img_allowed" id="comb_img_allowed" value="<?php echo $row_prod['product_variablecombocommon_image_allowed']?>" />
		<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
		<input type="hidden" name="pagetype" id="pagetype" value="" />
		<script type="text/javascript">
		function ajax_return_productdetailscontents() 
			{
				var ret_val = '';
				var disp 	= 'no';
				var docroot = '<?php SITE_URL?>';
				var prod_id	= <?php echo $_REQUEST['product_id']?>;
				var loading_gif = '<?php echo url_site_image('loading.gif',1)?>';
				if(req.readyState==4)
				{
					if(req.status==200)
					{
						ret_val 		= req.responseText;
						targetobj 	= eval("document.getElementById('"+document.getElementById('ajax_div_holder').value+"')");
						targetobj.innerHTML = ret_val; /* Setting the output to required div */
						if(document.getElementById('ajax_div_holder').value=='price_holder')
						{
							handle_show_prod_det_bulk_disc('bulk',docroot,prod_id,loading_gif);
						}
						else if(document.getElementById('ajax_div_holder').value=='bulkdisc_holder')
						{
							if(document.getElementById('comb_img_allowed').value=='Y' ) /* Do the following only if variable combination image option is set for current product */ 
								handle_show_prod_det_bulk_disc('main_img',docroot,prod_id,loading_gif);
						}
						else if(document.getElementById('ajax_div_holder').value=='mainimage_holder')
						{
							if(document.getElementById('comb_img_allowed').value=='Y') /* Do the following only if variable combination image option is set for current product */ 
								handle_show_prod_det_bulk_disc('more_img',docroot,prod_id,loading_gif);
						}
						hide_loading('proddet_loading_div');
					}
					else
					{
						hide_loading('proddet_loading_div');
						/*alert(req.status);*/
					}
				}
			}
		<?php
			if($Settings_arr['javascript_jquery']==1)
			{
				echo "jQuery.noConflict(); /* This is done to avoid error in light box due to the usage of $ in jquery*/";
			}
			?>
			function handle_price_promise()
			{
				var url 	= '<?php echo url_link("pricepromisecustlogin.html",1)?>';
				var cust_id = '<?php echo $cust_id?>';
				if(cust_id)
				{
					document.frm_proddetails.fpurpose.value = 'price_promise';
					document.frm_proddetails.submit();
				}
				else
				{
					document.frm_proddetails.action = url;
					document.frm_proddetails.pagetype.value = 'prodhtml';
					document.frm_proddetails.submit();
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
		</script>
		<?php
		    $size_checkval = false;
			$sizechart_heading = array();
			// Check whether size chart details exists for current product
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
				if($db->num_rows($charres))
				{  
					$size_checkval = true;
					while(list($size_value) = $db->fetch_array($charres))
					{   
						$sizevalue[$heading_id][] = $size_value;
					}
				}
			 }
		   $sizechart_heading['size_Availvalues'] = $size_checkval;
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
				$row_prods 				= $db->fetch_array($ret_prods);
				$sizechartmain_title 	= stripslash_normal($row_prods['product_sizechart_mainheading']); 
			}
			if($sizechartmain_title == '')
			{
				$sizechartmain_title 	= stripslash_normal($Settings_arr['product_sizechart_default_mainheading']);
			}
				
			if(count($sizevalue))
			{
				foreach($sizevalue as $k=>$v)
				{
					$cnt_hd = count($v);
				}
			}
		$HTML_loading = '<div class="proddet_loading_outer_div" style="height:15px"><div id="proddet_loading_div" style="display:none;padding:5px 0 0 0;">
							<img src="'.url_site_image('proddet_loading.gif',1).'" alt="loading..." width="43px" height="11px;align:left">
							</div></div>';			
		?>
		<table class="productdeatilstable" border="0" cellpadding="0" cellspacing="0">
		<tbody>
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
									a.product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_variable_display_type,
									a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,
									a.product_variablesaddonprice_exists,
									a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
									a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
									a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
									a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
									a.product_freedelivery        
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
			//if($cust_id)
			//{
				if($Settings_arr['proddet_showfavourite']==1)
					$favourite_show = 1;
			//}
		$in_combo = is_product_in_any_valid_combo($row_prod);
		
		?>
		<tr>
		<td colspan="2" class="productdeheader" align="left"><h1 class='productdeheader'><?php echo stripslashes($row_prod['product_name'])?></h1>
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
		 if(($email_show==1) or($writereview_show==1) or ($readreview_show==1) or ($pdf_show==1)) 
		 {
		?>
			<tr>
				<td colspan="2" class="productdeheader" align="right">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tbody>
				<tr>
				<td class="productdetails_img_td" valign="top" width="2%" align="left">
				<img src="<?php url_site_image('pro_link_det_left.gif')?>" title="left" alt="left">
				</td>
				<td colspan="2" class="productdetdA" align="right">
				<?php
				if($email_show==1) // Check whether the email a friend module is there for current site
				{
				?>
					<a href="<?php url_link('emailafriend'.$_REQUEST['product_id'].'.html')?>" class="productdetailslinkA"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND'];?></a>
				<?php
				}
				if(in_array('mod_product_reviews',$inlineSiteComponents)) // Check whether the product review module is there for current site
				{ 
					if($writereview_show==1)
					{
					?>
						<a href="<?php url_link('writeproductreview'.$_REQUEST['product_id'].'.html')?>" class="productdetailslinkB"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_WRITEREVIEW'];?></a>
					<?php
					}
					if($readreview_show==1)
					{
					?>	
						<a href="<?php url_link('readproductreview'.$_REQUEST['product_id'].'.html')?>" class="productdetailslinkC"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_READREVIEW'];?></a>
					<?php
					}
				}
				if($pdf_show==1) // Check whether the download pdf module is there for current site
				{  
				?>
					<a href="javascript:download_pdf_common('<?php echo $_SERVER['HTTP_HOST']?>','<?php echo $_SERVER['REQUEST_URI']?>')" class="productdetailslinkD"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADPDF'];?></a>
				<?php
				}
				?>
				</td>
				<td class="productdetails_img_td" valign="top" width="2%" align="right"><img src="<?php url_site_image('pro_link_det_right.gif')?>" title="right" alt="right"></td>
				</tr>
				</tbody></table>
				</td>
			</tr>
		<?php
		}
		?>
		<tr>
		<td class="productdetmain" valign="middle" align="center">
		<div class="pro_det_image" id="mainimage_holder">
		<?php
			$ret_arr = $this->Show_Image_Normal($row_prod,true);
		?>
		</div>
		<?=$HTML_loading;?>
		<?php	
			$exclude_tabid			= $ret_arr['exclude_tabid'];
			$exclude_prodid			= $ret_arr['exclude_prodid'];
		?>	
		
		<?php
		$module_name = 'mod_product_reviews';
		if(in_array($module_name,$inlineSiteComponents))
		{
			if($row_prod['product_averagerating']>=0)
			{
		?>
				<div class="reviewscore">
				<?php
					display_rating($row_prod['product_averagerating']);
				?>
				</div>
		<?php
			}
		}
		
		if($Settings_arr['bonus_points_instock'] and $row_prod['product_bonuspoints']>0)
		{
		 	  $sql 				= "SELECT bonus_point_details_content FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid." LIMIT 1";
		      $res_admin 		= $db->query($sql);
		      $fetch_arr_admin 	= $db->fetch_array($res_admin);
			   $HTML_content_bonus ='';
			   if($fetch_arr_admin['bonus_point_details_content']!='')
			   {
			   $HTML_content_bonus = '<div class="deat_bonusC"><a href="'.url_link('bonuspoint_content.html',1).'" title=""><img src="'.url_site_image('bonusmoreinfo.gif',1).'" border="0" /></a></div>';
			   }
				$HTML_bonus = '<div class="deat_bonus">
								<div class="deat_bonusA">'.$row_prod['product_bonuspoints'].'</div>
								<div class="deat_bonusB">'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTS']).'</div>'.$HTML_content_bonus.
								'</div>';
		}
		echo $HTML_bonus;
		if($row_prod['product_flv_filename']!='')
		{
			echo $HTML_video = '<div class="deat_pdt_button">
							<a href="javascript:show_normal_video()"><img src="'.url_site_image('video-but.gif',1).'" border="0" /></a>
							</div>
							<div id="div_defaultFlash_outer" class="flashvideo_outer" style="display:none"></div>
							<div style="display: none;" id="div_defaultFlash" class="content_default_flash">
							<div id="flash_close_div" align="right"><a href="javascript:close_video()">Close</a></div>
							<div id="flash_player_div">
							<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://macromedia.com/cabs/swflash.cab#version=6,0,0,0" ID=flaMovie WIDTH=500 HEIGHT=350>
							<param NAME=movie VALUE="http://'.$ecom_hostname.'/images/'.$ecom_hostname.'/swf/player.swf">
							<param NAME=FlashVars VALUE="file=http://'.$ecom_hostname.'/images/'.$ecom_hostname.'/product_flv/'.$row_prod['product_flv_filename'].'">
							<param NAME=quality VALUE=medium>
							<param NAME=bgcolor VALUE=#99CC33>
							<embed src="http://'.$ecom_hostname.'/images/'.$ecom_hostname.'/swf/player.swf" FlashVars="file=http://'.$ecom_hostname.'/images/'.$ecom_hostname.'/product_flv/'.$row_prod['product_flv_filename'].'" bgcolor=#99CC33 WIDTH=500 HEIGHT=350 TYPE="application/x-shockwave-flash">
							</embed>
							</object>
							</div>
							</div>';
		}
		if($row_prod['product_bulkdiscount_allowed']=='Y')
		{
			if($Settings_arr['show_bookmarks'])
			{
				?>
				<div class="book_mark">
				<div class="book_head"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['BOOKMARK_HEADING'])?></div>
				<?php bookmarks(url_product($row_prod['product_id'],$row_prod['product_name'],1),htmlspecialchars($meta_arr['title']),$ecom_twitteraccountId); ?>
				</div>
				<?php
			 }
		}
		?>
		</td>
		<td class="productdetd" valign="top" width="52%">
		<table width="100%" border="0" cellpadding="1" cellspacing="1">
		<tbody>
		<?php
		$stk_det = get_stockdetails($_REQUEST['product_id']);
		if($stk_det!='')
		{
		?>
			<tr>
			<td><span class="stockdetailstd"><?php echo $stk_det;?>  </span></td>
			</tr>
		<?php
		}
		?>
		<tr>
		<td class="productdetdnew" valign="top" align="left">
		<div id="price_holder">
		<?php
			$price_class_arr['ul_class'] 		= 'prodeulprice';
			$price_class_arr['normal_class'] 	= 'productdetnormalprice';
			$price_class_arr['strike_class'] 	= 'productdetstrikeprice';
			$price_class_arr['yousave_class'] 	= 'productdetyousaveprice';
			$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
			echo show_Price($row_prod,$price_class_arr,'prod_detail');
		?>
		</div>
		<?php
			$var_listed = $this->show_ProductVariables($row_prod,'column');
		?>
		<div id="bulkdisc_holder">
		<?php
			$this->show_BulkDiscounts($row_prod);
		?>
		</div>
		</td>
		</tr>
		</tbody></table>
		<?php
		if($Settings_arr['showsizechart_in_popup']==1 and (is_array($heading)) && count($sizevalue)) // check whether size chart is set to show in a pop up window
		{
		?>
			<div class="size_chart">
			<a href="javascript:showsizechartPopup('<?php echo $row_prod['product_id']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')" title="<?php echo stripslash_normal($sizechartmain_title)?>">
			<img src="<?php url_site_image('size-chart.gif')?>"/></a></div>
		<?php
		}
		if($sizechart_heading['size_Availvalues']==false && $row_prod['product_commonsizechart_link']!='')
		{
		?>      <div class="size_chart">
				<a href="<?=$row_prod['product_commonsizechart_link']?>" title="SizeChart" 	target="<?=$row_prod['produt_common_sizechart_target']?>"><img src="<?php url_site_image('size-chart.gif')?>"/></a></div>
		 <?
		}
		$button_displayed=$this->show_buttons($row_prod);
		?>
		<div id="moreimage_holder">
		<?php
		// Showing additional images
		$this->show_more_images($row_prod,$exclude_tabid,$exclude_prodid);
		?>
		</div>
		<div class="detail_buttonsA">
		<?php		
			
		if($favourite_show==1) // ** Show the add to favourite only if logged in 
		{
			if($cust_id)
			{
				$sql_prod = "SELECT products_product_id FROM customer_fav_products WHERE sites_site_id=$ecom_siteid AND products_product_id=".$_REQUEST['product_id']." AND customer_customer_id=$cust_id LIMIT 1";
				$ret_num= $db->query($sql_prod);
				if($db->num_rows($ret_num)==0) 
				{ 
				?>
					<img src="<?php url_site_image('add-to-fav.gif')?>" alt="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE'];?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE'];?>" onclick="if(confirm('<? echo $Captions_arr['PROD_DETAILS']['PRODDET_ADD_CONFIRM'] ?>')){document.frm_proddetails.fpurpose.value='add_favourite';document.frm_proddetails.submit();}"/>
				<?php
				}
				else
				{
				?>
					<img src="<?php url_site_image('rem-to-fav.gif')?>" alt="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE'];?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE'];?>" onclick="if(confirm('<? echo $Captions_arr['PROD_DETAILS']['PRODDET_REM_CONFIRM'] ?>')){document.frm_proddetails.fpurpose.value='remove_favourite';document.frm_proddetails.submit();}"/>
				<?php
				}
			}
			else
			{
			?>
				<img src="<?php url_site_image('add-to-fav.gif')?>" alt="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE'];?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE'];?>" onclick="window.location='<?php echo url_link('custlogin.html')?>'"/>
			<?php	
			}	
		}
		if ($Settings_arr['proddet_showwishlist'])
		{	
			if($cust_id)
			{
		?>
				<img src="<?php url_site_image('add-to-wishlist.gif')?>" alt="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addwishlist';document.frm_proddetails.submit()"/>
		<?php
			}
			else
			{
			?>
				<img src="<?php url_site_image('add-to-wishlist.gif')?>" alt="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>" onclick="window.location='<?php url_link('wishlistcustlogin.html')?>'"/>
			<?php
			}
		}
	?>			
	<?php
		if($compare_show==1)
		{
			$def_cat_id = $row_prod['product_default_category_id'];
		?>	
			<img src="<?php url_site_image('add-to-com.gif')?>" alt="<? echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_LINK'] ?>" title="<? echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_LINK'] ?>" onclick="window.location='<?php echo url_productcompare($_REQUEST['product_id'],$row_prod['product_name'],1)?>'"/>
		<?php
		}
		// Check whether the enquire link is to be displayed
		if ($row_prod['product_show_enquirelink']==1)
		{
	 ?>			
	 		<img alt="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?>" src="<?php url_site_image('add-to-enq.gif')?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Enquire';document.frm_proddetails.submit()" />
	<?php
		}
	?>
		</div>
		<?php
		if($row_prod['product_bulkdiscount_allowed']!='Y')
		{
			if($Settings_arr['show_bookmarks'])
			{
				?>
				<div class="book_mark">
				<div class="book_head"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['BOOKMARK_HEADING'])?></div>
				<?php bookmarks(url_product($row_prod['product_id'],$row_prod['product_name'],1),htmlspecialchars($meta_arr['title'])); ?>
				</div>
				<?php
			} 
		}
		?>
		</td>
		</tr>
		<?php /*?><tr>
		<td class="productdetdnew" valign="middle" align="center">
		
		</td>
		<td class="productdetd" valign="top">&nbsp;</td>
		</tr><?php */?>
		<?php
		// Check whether this product is linked with any of the combo deals which is currently active
			$in_combo = is_product_in_any_valid_combo($row_prod);
		?>	
		<tr>
		<td colspan="2" class="productdetd" align="left">            </td>
		</tr>
		<?php
		if($in_combo==1 or $row_prod['product_freedelivery']==1 or $row_prod['product_show_pricepromise']==1)
		{
		?>
			<tr>
			<td colspan="2" class="productdetdnew" valign="middle" align="left"> 
			<div class="detail_buttons">
			<?php
			if($row_prod['product_show_pricepromise']==1)
			{
			?>
				<a href="javascript:handle_price_promise()"><img src="<?php url_site_image('price-promise.gif')?>" /></a>
			<?php
			}
			if($row_prod['product_freedelivery']==1)
			{
			?>
				<a href="<?php url_link('freedelivery'.$row_prod['product_id'].'.html')?>"><img src="<?php url_site_image('free-delivery.gif')?>" border="0" /></a>
			<?php
			}
			if($in_combo==1)
			{
			?>
				<a href="<?php url_link('showallbundle'.$row_prod['product_id'].'.html')?>" title=""><img src="<?php url_site_image('combo-offer.gif')?>" border="0" /></a>
			<?php
			}
			?>
			</div></td>
			</tr>
		<?php
		}
		if($Settings_arr['showsizechart_in_popup']!=1 and count($heading) && count($sizevalue)) // check whether size chart is set to show in a pop up window
		{
			$this->Show_Size_chart($heading,$cnt_hd,$sizevalue);
		}	
		
		$this->show_ProductLabels($_REQUEST['product_id']);
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
				<td colspan="2">
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
		   </span>
		   </span>
          <?php
		}
		$tabs_arr		= $tabs_cont_arr	= array();
		if($row_prod['product_longdesc'])
			$tabs_content_arr[0]	= stripslashes($row_prod['product_longdesc']);
		elseif ($row_prod['product_shortdesc'])
			$tabs_content_arr[0]	= stripslashes($row_prod['product_shortdesc']);
		if (count($tabs_content_arr))
			$tabs_arr 		= array ('PRODDET_OVERVIEW'=>'Overview');
		
		$arr_sr = array('7751','3397','020','9734','Architectural Components Ltd');
			$arr_rp = array('****','****','***','****','Door Handles');
							
		// Get the list of tabs for current product
		$sql_tab = "SELECT tab_id,tab_title,tab_content,product_common_tabs_common_tab_id 
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
				   if($row_tab['product_common_tabs_common_tab_id']==0)
					{
						$tabs_content_arr[$row_tab['tab_id']]	= stripslashes($row_tab['tab_content']);
					}
					else
					{
					  	    $sql_comm_tab = "SELECT tab_content  	 
							FROM 
								product_common_tabs 
							WHERE 
								common_tab_id = ".$row_tab['product_common_tabs_common_tab_id']."
								AND sites_site_id = ".$ecom_siteid."  
								AND tab_hide=0 LIMIT 1";
			                $ret_comm_tab = $db->query($sql_comm_tab);
							if($db->num_rows($ret_comm_tab))
			                {
								$row_comm_tab = $db->fetch_array($ret_comm_tab);
								$tabs_content_arr[$row_tab['tab_id']]	= stripslashes($row_comm_tab['tab_content']);
							}
					}
			}
		}
		if (count($tabs_arr))
		{
		?>
			<tr>
				<td colspan="2" class="productdetdtab" align="left">
				<a href="#" name="protabs"></a>
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
                </div>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="productdetd_main" align="left">
				<?php	echo str_replace($arr_sr,$arr_rp,$tabs_content_arr[$curtab]);?><br /><br />
				</td>
			</tr>
			
		<?php
		}
		?>
		</tbody></table>
		
		</form>
		<?php	
			// ** Check whether any linked products exists for current product
			$sql_linked = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
								a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
								a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
								product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints,
								a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
								a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
								a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
								a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
								a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
								a.product_freedelivery      
							FROM 
								products a,product_linkedproducts b 
							WHERE 
								b.link_parent_id=".$_REQUEST['product_id']." 
								AND a.sites_site_id=$ecom_siteid 
								AND a.product_id = b.link_product_id 
								AND a.product_hide = 'N' 
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
				<td align="left">
				<?php 
					include ("includes/base_files/combo_middle.php");
				?>
				</td>
			</tr>
			<tr>
				<td align="left">
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
			global $ecom_siteid,$db,$Captions_arr,$Settings_arr,$inlineSiteComponents;
			// Calling the function to get the type of image to shown for current 
			$pass_type = get_default_imagetype('link_prod');
			$prod_compare_enabled = isProductCompareEnabled();
			switch($Settings_arr['linked_prodlisting'])
			{
				case '1row':
				case '2row':
					$width_one_set 	= 166;
					$min_number_req	= 4;
					$min_width_req 	= $width_one_set * $min_number_req;
					$total_cnt		= $db->num_rows($ret_prod);
					$calc_width		= $total_cnt * $width_one_set;
					if($calc_width < $min_width_req)
						$div_width = $min_width_req;
					else
						$div_width = $calc_width; 
		?>
				<div class="link_pdt_outr">
				<div class="link_pdt_top"></div>
				<div class="link_pdt_conts">
				<div class="link_pdt_hdr_outr"><div class="link_pdt_hdr"><span><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_PEOPLE_INTERESTED']);?></span></div></div>
				<div class="det_link_pdt_con">
				<div class="det_link_nav"><a href="#null" onmouseover="scrollDivRight('containerA')" onmouseout="stopMe()"><img src="<?php url_site_image('/arrow-left.gif')?>"></a></div>
				<div id="containerA" class="det_link_pdt_inner">
				<div id="scroller" style="width:<?php echo $div_width?>px">
				<?php
				$cnts = $db->num_rows($ret_prod);
				while($row_prod = $db->fetch_array($ret_prod))
				{
				?>
					<div class="det_link_pdt">
						<div class="det_link_image">
						<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>">
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
						</div>
						<div class="det_link_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div>
					</div>
				<?php
				}
				?>
				</div>
				</div>
				<div class="det_link_nav"> <a href="#null" onmouseover="scrollDivLeft('containerA','<?php echo $div_width?>')" onmouseout="stopMe()"><img src="<?php url_site_image('arrow-right.gif')?>" /></a></div>
				</div>
				</div>
				<div class="link_pdt_bottom"></div>
			  </div>
		<?php
				
			break;
			};
		}
		// ** Function to show the list of products to be compared with current product.
		function Show_Compare_Product($ret_prod)
		{
				global $ecom_siteid,$db,$Captions_arr,$Settings_arr,$inlineSiteComponents;
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
				<tr>
				<td colspan="3">
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
					<div class="shelfBtable">
							<div class="shelfBtabletd">
							<div class="shelfBtabletdinner">
							<div class="shelfBprodname">
							<input type="checkbox" name="chkproddet_comp_<?php echo $row_prod['product_id']?>" id="chkproddet_comp_<?php echo $row_prod['product_id']?>" value="<?php echo $row_prod['product_id']?>" <?php echo $compare_checked?>/><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shelfBprodnamelink"><?php echo stripslashes($row_prod['product_name'])?></a>
							</div>
							<div class="shelfBleft">
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
							<div class="compare_li">
							<?php
							if($prod_compare_enabled)
							{
								dislplayCompareButton($row_prod['product_id']);
							}
							?>	
							</div> 
							</div>
							<div class="shelfBmid">	
							<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
							<?php
								$price_class_arr['ul_class'] 		= 'shelfBpriceul';
								$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
								$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
								$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
								$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
								echo show_Price($row_prod,$price_class_arr,'bestseller_1');
								if($row_prod['product_saleicon_show']==1)
								{
									$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
									if($desc!='')
									{
							?>
										<div class="shelfB_sale"><?php echo $desc?></div>
							<?php
									}
								}	
								if($row_prod['product_newicon_show']==1)
								{
									$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
									if($desc!='')
									{
							?>
										<div class="shelfB_newsale"><?php echo $desc?></div>		
							<?php
									}
								}	
								$module_name = 'mod_product_reviews';
								if(in_array($module_name,$inlineSiteComponents))
								{
									if($row_prod['product_averagerating']>=0)
									{
									?>
										<div class="shelfB_rate">
										<?php
											display_rating($row_prod['product_averagerating']);
										?>
										</div>
									<?php
									}
								}	
							?>						  
							</div>
							<div class="shelfBright"> 
							<?php 
							if($row_prod['product_freedelivery']==1)
							{	
							?>
								<div class="shelfB_free"></div>
							<?php
							}
							if($row_prod['product_bulkdiscount_allowed']=='Y')
							{
							?>
								<div class="shelfB_bulk"></div>
							<?php
							}
							$frm_name = uniqid('comp_');
							?>
							<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
							<input type="hidden" name="fpurpose" value="" />
							<input type="hidden" name="fproduct_id" value="" />
							<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
							<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
							<div class="infodivB">
							<div class="infodivBleft">
							<?php show_moreinfo($row_prod,'infolink')?></div>
							<div class="infodivBright">
							<?php
								$class_arr 					= array();
								$class_arr['ADD_TO_CART']	= 'infolinkB';
								$class_arr['PREORDER']		= 'infolinkB';
								$class_arr['ENQUIRE']		= 'infolinkB';
								show_addtocart($row_prod,$class_arr,$frm_name)
							?>
							</div>		
							</div>
							</form>
							</div>
							</div>
							</div>	
					</div>
			<?php
				}
			?>	
			</td>
			</tr>
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
			$sql_var = "SELECT var_id,var_name,var_value_exists, var_price,var_value_display_dropdown   
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
		  		<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tbody><tr>
				<td class="variable_bottom_border">
				<div class="variabletabcontainer">           
				<ul class="variabletab">
				<li id="var_li" class="variableselected">Variables</li>	
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
									$sql_vals = "SELECT var_value_id, var_addprice,var_value,var_colorcode, images_image_id  
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
												$onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"price\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\")' ";
												$onclick_var            = "price";
											}
											elseif($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='N')
											{
												$onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"price\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\")' ";
												$onclick_var            = "price";
											}
											elseif($row_prod['product_variablecomboprice_allowed']=='N' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
											{
												$onchange_function      = $onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"main_img\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\")' ";
												$onclick_var            = "main_img";
											}
											else
											{
												$onchange_function      = '';
												$onclick_var       		= '';
											}
												$var_disp_type      	= 'DROPDOWN';// Default settings
												if($row_var['var_value_display_dropdown']==1)
													$var_disp_type 		= 'DROPDOWN';
												else
													$var_disp_type 		= 'OTHER';
												$color_type				= false;
												if($var_disp_type == 'OTHER')
												{
													if(var_color_display_check($row_var['var_name']))
													{
														$color_type 	= true;  
													}
												}
												 if($var_disp_type=='DROPDOWN')
							 					 {			
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
												$first_val	 	= '';
												$first_one 		= 1;
												$docroot 		= SITE_URL; 
												$prodid			= $_REQUEST['product_id'];
												$loading_gif 	= url_site_image('loading.gif',1);
												while ($row_vals = $db->fetch_array($ret_vals))
												{
													if($first_val=='')
														$first_val = $row_vals['var_value_id'];
													$ret_arr = handle_variable_color_section($row_vals,$first_val,$color_type);	
											
													$show_value			= $ret_arr['show_value'];
													$clr_val 			= $ret_arr['clr_val'];
													$normal_cls 		= $ret_arr['normal_cls'];
													$special_cls 		= $ret_arr['special_cls'];
													
													$normal_cls_sz 		= "size_var_div";
													$special_cls_sz 	= "size_var_div_sel";
													$normal_cls_clrimg 	= "colorimg_div";
													$special_cls_clrimg	= "colorimg_div_sel";
													$normal_cls_clr 	= "color_div";
													$special_cls_clr	= "color_div_sel";	
													$varvaldivid 		= "valdiv_var_".$row_var['var_id']."_".$row_vals['var_value_id'];
													//$onclick_function   = " onclick ='var_onclick(\"".$onclick_var."\",\"var_".$row_var['var_id']."\",".$row_vals['var_value_id'].",\"".$normal_cls."\",\"".$special_cls."\",\"".$docroot."\",\"".$prodid."\",\"".$loading_gif."\")' ";
													$onclick_function   = " onclick ='var_onclick(\"".$onclick_var."\",\"var_".$row_var['var_id']."\",".$row_vals['var_value_id'].",\"".$normal_cls_clr."\",\"".$special_cls_clr."\",\"".$normal_cls_clrimg."\",\"".$special_cls_clrimg."\",\"".$normal_cls_sz."\",\"".$special_cls_sz."\",\"".$docroot."\",\"".$prodid."\",\"".$loading_gif."\")' ";
												?>
												 <div id='<?php echo $varvaldivid?>' class="<?php echo ($first_one==1)?$special_cls:$normal_cls?>" <?php echo $clr_val. ' '.$onclick_function?> title="<?php echo $row_vals['var_value']?>" >
												 <?
												 
												  if($show_value)
													echo stripslashes($row_vals['var_value']);
												?>
												 </div>
												<?php
												   $first_one=2;
												}
									 ?>
											<input type='hidden' name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" value="<?php echo $first_val?>" />
									 
									<?php
										  }
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
				</tbody>
				</table>
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
			<div class="bulkdiscountdiv">
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
							<?php //echo print_price($bulkdisc_details['price'][$i]).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH']?>
                            <?php 	echo product_BulkDiscount_Build_Price($bulkdisc_details['price'][$i],$bulkdisc_details['price_without'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);?>
						</td>
					  </tr>
				  <?php
					}
				  ?>
				</table>
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
		function show_ProductLabels($prod_id)
		{
			global $db,$ecom_siteid,$Captions_arr;
			$display_ok = false;
			$ret_val = '';
			// Check whether labels exists for current product
			$cats_arr = $grp_arr = array();
			// Get the categories that area linked with current product
			$sql_cats = "SELECT product_categories_category_id 
							FROM 
								product_category_map 
							WHERE 
								products_product_id = $prod_id";
			$ret_cats = $db->query($sql_cats);
			if($db->num_rows($ret_cats))
			{
				while ($row_cats = $db->fetch_array($ret_cats))
				{
					$cats_arr[] = $row_cats['product_categories_category_id'];
				}
				$sql_grps = "SELECT DISTINCT product_labels_group_group_id  
								FROM 
									product_category_product_labels_group_map a, product_labels_group b 
								WHERE 
									a.product_labels_group_group_id = b.group_id 
									AND b.group_hide = 0 
									AND product_categories_category_id IN (".implode(',',$cats_arr).") ";
				$ret_grps = $db->query($sql_grps);
				if($db->num_rows($ret_grps))
				{
					while ($row_grps = $db->fetch_array($ret_grps))
					{
						$grp_arr[] = $row_grps['product_labels_group_group_id'];
					}	
					// Check whether there exists atleast one label to display
					$sql_lblcheck = "SELECT a.map_id 
										FROM 
											product_labels_group_label_map a , product_labels_group b
										WHERE 
											product_labels_group_group_id IN (".implode(',',$grp_arr).") 
											AND a.product_labels_group_group_id=b.group_id 
											AND b.group_hide = 0 
										LIMIT 
											2";
					$ret_lblcheck 	= $db->query($sql_lblcheck);
					$grp_nos		= $db->num_rows($ret_lblcheck);
					if($grp_nos)
					{
						// Get the product label group details in order
						$sql_grp = "SELECT group_id,group_name,group_name_hide  
										FROM 
											product_labels_group 
										WHERE 
											group_id IN (".implode(',',$grp_arr).") 
										ORDER BY 
											group_order";
						$ret_grp = $db->query($sql_grp);
						if($db->num_rows($ret_grp))
						{
							$ret_val = '<tr>
										<td colspan="2">
										<table class="keyfeaturetable" width="100%" border="0" cellpadding="0" cellspacing="0">
										<tr>
										<td class="keyfeature_header" colspan="2">'.$Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES'].'</li>
										</td>
										</tr>
										';
							$i=1;
							$grp_cnt = 0;
							$label_arr = array();
							while ($row_grp = $db->fetch_array($ret_grp))
							{
								// Check whether there exists atleast one label under this group to display
								$sql_labels = "SELECT a.label_id,a.label_name,a.in_search,a.is_textbox,c.product_site_labels_values_label_value_id,c.label_value 
													FROM 
														product_site_labels a,product_labels_group_label_map b,product_labels c
													WHERE 
														b.product_labels_group_group_id = ".$row_grp['group_id']." 
														AND c.products_product_id = $prod_id
														AND a.label_id = b.product_site_labels_label_id 
														AND a.label_id = c.product_site_labels_label_id 
														AND a.label_hide = 0 
														AND (c.product_site_labels_values_label_value_id>0 OR  label_value <> '')
													ORDER BY 
														b.map_order";
								$ret_labels = $db->query($sql_labels);
								if($db->num_rows($ret_labels))
								{
									$grp_cnt++;
									$label_arr[$row_grp['group_name'].'~'.$row_grp['group_name_hide']] = array();
									while ($row_labels = $db->fetch_array($ret_labels))
									{
										$vals = '';
										if ($row_labels['is_textbox']==1)
											$vals = stripslash_normal($row_labels['label_value']);
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
												$vals = stripslash_normal($row_labelval['label_value']);
											}
																	
										}
										if ($vals)
										{
											$label_arr[$row_grp['group_name'].'~'.$row_grp['group_name_hide']][] = array('name'=>stripslash_normal($row_labels['label_name']),'val'=>$vals);
										}
									}	
								}
							}
							if(count($label_arr))
							{
								$display_ok=true;
								$prev_grp = '';
								foreach ($label_arr as $k=>$v)
								{
									if($prev_grp!=$k)
									{
										$gname_arr = explode('~',$k);
										// Show the name only if it is not made hidden
										if($gname_arr[1]==0)
										{
											$ret_val .='<tr>
														<td class="keyfeatureHeading" align="left" colspan="2">'.$gname_arr[0].'</td>
														</tr>';
										}			
									}	
									if(is_array($v))
									{
										if(count($v))
										{
											for($i=0;$i<count($v);$i++)
											{
												$clss = ($i%2==0)?'keyfeaturetdA':'keyfeaturetdB';
												$ret_val .= '
															<tr>
																<td align="left" valign="middle" class="'.$clss.'">'.$v[$i]['name'].'</td>
																<td align="left" valign="middle" class="'.$clss.'">:&nbsp;'.$v[$i]['val'].'</td>
															</tr>';	
											}
										}
									}
								}
							}	
							$ret_val .= '</table>
										</td>
										</tr>';	
						}
					}
				}	
			}
			if($display_ok==false)
				$ret_val = '';
			echo $ret_val ;	
		}
		function show_buttons($row_prod)
		{
			global $Captions_arr,$showqty,$Settings_arr;
			$cust_id 	= get_session_var("ecom_login_customer");
			$showqty	= $Settings_arr['show_qty_box'];// show the qty box
			$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslashes($row_prod['product_det_qty_caption']):$Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY'];
			$caption_key = show_addtocart($row_prod,array(0),'',true);
			if ($caption_key!='') // Check whether the buynow or preorder button is to be displayed
			{
			?>
			<div class="details_buy">
			<?php 
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
			
	 ?>
				<input name="Submit_buy" type="submit" class="button_buy_det" id="Submit_buy" value="<?php echo $Captions_arr['PROD_DETAILS'][$caption_key];?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addcart'" />
				</div>
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
						<div class="thumb_images">
						<div class="thumb_images_hdr"> <img src="<?php url_site_image('zoomimage.gif')?>" /></div>
						<div class="thumb_images_con">
						<?php
						$curimg_col = 0;
						$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
						foreach ($prodimg_arr as $k=>$v)
						{
							$title = ($v['image_title'])?stripslashes($v['image_title']):$row_prod['product_name'];
						?>
							<a href="<?php url_root_image($v['image_extralargepath'])?>" rel='lightbox[gallery]' title="<?=$title?>">
							<?php
								 show_image(url_root_image($v['image_iconpath'],1),$title,$title,'preview');
							?>
							</a>
						<?php
						}
						?>	
						</div>
						</div>	
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
		function Show_Size_chart($heading,$cnt_hd,$sizevalue)
		{
			global $db,$ecomn_siteid,$ecom_hostname,$ecom_themename;
			$Captions_arr['PROD_DETAILS'] = getCaptions('PROD_DETAILS');
			?>
			<tr>
			<td colspan="2" class="sizechart_header"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);?>
			</td>
			</tr>
			<tr>
			<td colspan="2">
			<table width="100%" border="0" cellpadding="2" cellspacing="0">
			<tr>
			<?php 
			foreach($heading AS $val)
			{ 
			?>
				<td align="center"  class="productsizechartheading" ><?PHP echo $val; ?></td>
			<?php
			} 
			?>
			</tr>
			 <?php 
			for($i=0; $i<$cnt_hd; $i++)
			{
				$cls = ($cls=='productsizechartvalueA')?'productsizechartvalueB':'productsizechartvalueA';
			?>
			<tr>
			<?php
			foreach($sizevalue as $k=>$v)
			{
			?>
			  <td class="<?php echo $cls; ?>" align="center" ><?PHP echo ($sizevalue[$k][$i])?$sizevalue[$k][$i]:'-'; ?></td>
			<?php
			} 
			?>
			</tr>
			<? 
			}
			?>
			</table>
			</td>
			</tr>
				 <?php
		}
	};	
?>