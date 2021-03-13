<?php
	/*############################################################################
	# Script Name 	: productHtml.php
	# Description 	: Page which holds the display logic for product details
	# Coded by 		: Sny
	# Created on	: 12-Aug-2009
	# Modified by	: 
	# Modified On	: 
	
	##########################################################################*/
	class product_Html
	{
		// Defining function to show the selected product details
		function Show_ProductDetails($row_prod)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$glob_qty_displayed,
					 $ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$ecom_twitteraccountId,$meta_arr;
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
				if($ecom_siteid==53)
				{
				   if($_SERVER['REMOTE_ADDR']=='220.225.193.237')
				   {
					if($row_prod['product_id']==94402)
					{
					 // error_reporting(E_ALL);
						//ini_set('display_errors', '1');	
					 
					} 
					}
				}
			/* Code for ajax setting starts here */
			$addto_cart_withajax =  $Settings_arr['enable_ajax_in_site'];
			$enable_special_type_display    = $Settings_arr['proddet_special_display'];
			/* Code for ajax setting ends here */
		 
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
				$alert 	= stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVEXISTS']);
			}
			else if($_REQUEST['result']=='added')
			{
				$alert = stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVADDED']);
			}
			else if($_REQUEST['result']=='removed')
			{
				$alert = stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVREMOVED']);
			}
			
			$prod_img_show_type =$row_prod['product_details_image_type'];
		?>
		 <script type="text/javascript" language="javascript">
		  function download_pdf()
		  {
			document.getElementById('bw').innerHTML = '<iframe src="https://www.web2pdfconvert.com/convert.aspx?cURL=<?=$ecom_selfhttp.$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>&outputmode=stream&allowactivex=yes&ref=form">';
			show_processing();
			setTimeout('hide_processing()', 20000);
		  }
	  </script>
	  		 <link href="https://www.healthstore.uk.com/images/www.healthstore.uk.com/lightjquery/lightbox.min.css" media="screen" type="text/css" rel="stylesheet" />
		<div class="treemenu"><ul><?php echo generate_tree(-1,$_REQUEST['product_id'],'<li>','</li>')?></ul></div>
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
				/* Code for ajax setting starts here */
				if(document.getElementById('how_displayed'))
					var how_displayed = document.getElementById('how_displayed').value;
				else
					var how_displayed = '';	
				/* Code for ajax setting ends here */
				if(how_displayed!='proddet_ajax_list') /* Code for ajax setting starts here */
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
							/* Code for ajax setting starts here */
							if(document.getElementById('ajax_div_holder').value=='cart_details_ajaxholder')
							{
								//alert('proddet*'+docroot);
								ajax_addto_cart_fromlist('show_shop_cart','','',docroot);
								show_ajax_holder('cart');
								hideme('#proddet_loading_div_ajax');
	
							}
							/* Code for ajax setting ends here */
							hide_loading('proddet_loading_div');
						}
						else
						{
							hide_loading('proddet_loading_div');
							/*alert(req.status);*/
						}
					}
				}
				else /* Code for ajax setting starts here */
				{
					var ret_val = '';
					var disp 	= 'no';
					var docroot = '<?php echo SITE_URL?>';
					//var prod_id	= <?php echo $_REQUEST['product_id']?>;
					var prod_id 	= document.frm_proddetails_ajax.product_id_ajax.value;
					var mod = 'ajax';
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
								handle_show_prod_det_bulk_disc('bulk',docroot,prod_id,loading_gif,mod);
								//hideme('#proddet_loading_div_ajaxinner');

							}					
							else if(document.getElementById('ajax_div_holder').value=='bulkdisc_holder')
							{
								//if(document.getElementById('comb_img_allowed').value=='Y' ) /* Do the following only if variable combination image option is set for current product */ 
								handle_show_prod_det_bulk_disc('main_img',docroot,prod_id,loading_gif,mod);
								//hideme('#proddet_loading_div_ajaxinner');
							}	
							else if(document.getElementById('ajax_div_holder').value=='mainimage_holder')
							{
									hideme('#proddet_loading_div_ajaxinner');
							}
							if(document.getElementById('ajax_div_holder').value=='cart_details_ajaxholder')
							{
								ajax_addto_cart_fromlist('show_shop_cart','','',docroot);
								show_ajax_holder('cart');
							}				
							if(document.getElementById('ajax_div_holder').value=='prod_details_ajaxholder')
							{
								show_ajax_holder('prod');
							}
								hide_loading('proddet_loading_div');
								hideme('#proddet_loading_div_ajax');

						}
						else
						{
						hide_loading('proddet_loading_div');
						hideme('#proddet_loading_div_ajax');

						/*alert(req.status);*/
						}
					}	
				}/* Code for ajax setting ends here */
			}
		<?php
			/* Code for ajax setting starts here */
			$var_count = 0 ;
			$var_count = $this->get_countvariables_count($_REQUEST['product_id']);
			$enable_special_display = false;
			if($enable_special_type_display==1 && $var_count==1) // Call special display function
			{
				$enable_special_display = true; 
			}	
			/* Code for ajax setting ends here */
			if($Settings_arr['javascript_jquery']==1)
			{
				echo "jQuery.noConflict();";
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
			var img_path = '<?php echo "$ecom_selfhttp$ecom_hostname/images/$ecom_hostname/"?>';
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
		<div class="det_pdt_con" >
		<div class="det_pdt_top"></div>
		<div class="det_pdt_middle">
		<?php
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
		if($cust_id)
		{
			if($Settings_arr['proddet_showfavourite']==1)
				$favourite_show = 1;
		}
		// Check whether size chart details exists for current product
		$size_checkval = false;
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
		$sizechart_heading[] = $heading;
		$sizechart_heading['size_Availvalues'] = $size_checkval;	   $cnt =   count($sizevalue);
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
		if($alert)
		{
		?>
		  <div class="red_msg"> - <?php echo $alert?> - </div>
		<?php
		}
		elseif($_REQUEST['stockalert'])
		{
		?>
				<div class="red_msg"> - <?php echo stripslash_normal($Captions_arr['PROD_DETAILS'][$_REQUEST['stockalert']])	?> - </div>
		<?php	
		}
		?>
	 <div class="pro_det_left_con">
		<div class="pro_det_name"><h1><?php echo stripslash_normal($row_prod['product_name'])?></h1></div>
		<?php
		if($_REQUEST['for_notification']==1) // make the decision whether the instock message is to be displayed here or in div
		{
			$this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
		}	
		else // case if displaying the instock notification message here itself
			$this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
			
			$HTML_loading = '<div class="proddet_loading_outer_div" style="height:15px"><div id="proddet_loading_div" style="display:none;padding:5px 0 0 0;">
							<img src="'.url_site_image('proddet_loading.gif',1).'" alt="loading..." width="43px" height="11px;align:left">
							</div></div>';	
		?>
		<div class="pro_det_left">
		<div id="moreimage_holder">
		<?php
			$return_arr = $this->Show_Image_Normal($row_prod,true);
			// Showing additional images
			$this->show_more_images($row_prod,$return_arr['exclude_tabid'],$return_arr['exclude_prodid']);
		?>
		</div>
		
		<?php 
		$stk_det = get_stockdetails($_REQUEST['product_id']);
		if($stk_det!='')
		{
		?>
		<div class="stock_txt"><?php echo $stk_det;?></div>
		<?
		}
		if ($Settings_arr['show_bookmarks'])
		{
		?>
		<div class="book_mark">
		<div class="book_head"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['BOOKMARK_HEADING'])?></div>
		<?php bookmarks(url_product($row_prod['product_id'],$row_prod['product_name'],1),htmlspecialchars($meta_arr['title']),$ecom_twitteraccountId); ?>
		</div>
		<?php
		}
		?>
		<div class="det_left_btn">
		<?php
		if ($Settings_arr['bonus_points_instock'] and $row_prod['product_bonuspoints']>0)
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
		if ($Settings_arr['proddet_showwishlist'])
		{
			if($cust_id) // ** Show the wishlist button only if logged in 
			{
			?>
				<img src="<?php url_site_image('add-wishlist.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST']);?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST']);?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addwishlist';document.frm_proddetails.submit()" />
			<?php
			}	
			else
			{
			?>
				<img src="<?php url_site_image('add-wishlist.gif')?>" alt="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>" onclick="window.location='<?php url_link('wishlistcustlogin.html')?>'"/>
			<?php
			}
		}	
		if ($row_prod['product_show_enquirelink']==1)
		{
		?>
			<img src="<?php url_site_image('add-enqry.gif')?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']);?>" alt="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']);?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Enquire';document.frm_proddetails.submit();" />			
		<?php
		}
	
		if( $compare_show==1)
		{
			$def_cat_id = $row_prod['product_default_category_id'];
		?>	
			<a href="<?php url_productcompare($_REQUEST['product_id'],$row_prod['product_name'])?>" class="productdetailslink"  title="<? echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_LINK'])?>"><img src="<?php url_site_image('compare_prods.gif')?>" border="0" alt="<? echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_LINK'])?>" /></a>
		<?php
		}
		?>
		</div>
			    <?=$HTML_loading?>

		</div>
		<div class="pro_det_center">
		<?php
			$cur_disc = '';
			$price_arr = show_Price($row_prod,$price_class_arr,'prod_detail',false,3);	
			if($price_arr['disc_percent']!='')
				$cur_disc = $price_arr['disc_percent'];
			elseif($price_arr['yousave_price']!='')
				$cur_disc = $price_arr['yousave_price']	;		
			if($cur_disc != '' and $row_prod['product_variablecomboprice_allowed']!='Y')
			{
		?>
			<div class="pro_det_off">
			<?php
				echo $cur_disc;
			?>	
			</div>
		<?php
		}
		?>
		<div class="pro_det_image">
		<div id="mainimage_holder">
		<?php
			$this->Show_Image_Normal($row_prod);
		?>
		</div>
		</div>
		<?php
		if($ecom_siteid==97)
		{$module_name = 'mod_product_reviews_nill';}
		else
		{
		$module_name = 'mod_product_reviews';
		}
		if(in_array($module_name,$inlineSiteComponents))
		{
			if($row_prod['product_averagerating']>=0)
			{
		?>
				<div class="pro_det_rate">
				<?php
					display_rating($row_prod['product_averagerating']);
				?>
				</div>
		<?php
			}
		}	
			if($email_show==1)
			{
		?>
				<div class="pro_det_email"><a href="<?php url_link('emailafriend'.$_REQUEST['product_id'].'.html')?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND']);?>"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND']);?></a></div>
		<?php
			}
		?>	
		</div>
		<?php
		if($row_prod['product_flv_filename']!='')
			{
				echo $HTML_video = '<div class="deat_pdt_button">
								<a href="javascript:show_normal_video()"><img src="'.url_site_image('video-but.gif',1).'" border="0" /></a>
								</div>
								<div id="div_defaultFlash_outer" class="flashvideo_outer" style="display:none"></div>
								<div style="display: none;" id="div_defaultFlash" class="content_default_flash">
								<div id="flash_close_div" align="right"><a href="javascript:close_video()">Close</a></div>
								<div id="flash_player_div">
								<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="'.$ecom_selfhttp.'macromedia.com/cabs/swflash.cab#version=6,0,0,0" ID=flaMovie WIDTH=500 HEIGHT=350>
								<param NAME=movie VALUE="'.$ecom_selfhttp.$ecom_hostname.'/images/'.$ecom_hostname.'/swf/player.swf">
								<param NAME=FlashVars VALUE="file='.$ecom_selfhttp.$ecom_hostname.'/images/'.$ecom_hostname.'/product_flv/'.$row_prod['product_flv_filename'].'">
								<param NAME=quality VALUE=medium>
								<param NAME=bgcolor VALUE=#99CC33>
								<embed src="'.$ecom_selfhttp.$ecom_hostname.'/images/'.$ecom_hostname.'/swf/player.swf" FlashVars="file='.$ecom_selfhttp.$ecom_hostname.'/images/'.$ecom_hostname.'/product_flv/'.$row_prod['product_flv_filename'].'" bgcolor=#99CC33 WIDTH=500 HEIGHT=350 TYPE="application/x-shockwave-flash">
								</embed>
								</object>
								</div>
								</div>';
			}
		?>
		<?php
		// Calling function to show the variables and variable messages ( If any )
		$this->show_ProductVariables($row_prod,'',$sizechart_heading);	
		?>
		</div>
		<div class="pro_det_right"> 
		<div class=" pro_det_icons">
		<?php
		if($favourite_show==1) // Decide whether favorite option is to be displayed
		{
			$sql_prod = "SELECT products_product_id FROM customer_fav_products WHERE sites_site_id=$ecom_siteid AND products_product_id=".$_REQUEST['product_id']." AND customer_customer_id=$cust_id LIMIT 1";
			$ret_num= $db->query($sql_prod);
			if($db->num_rows($ret_num)==0) 
			{ 
			?>
				<a href="#" onClick="if(confirm('<? echo stripslash_javascript($Captions_arr['PROD_DETAILS']['PRODDET_ADD_CONFIRM']) ?>')){document.frm_proddetails.fpurpose.value='add_favourite';document.frm_proddetails.submit();}"><img src="<?php url_site_image('favourite.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE']);?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE']);?>" /></a>
			<?php
			}
			else
			{
			?>
				<a href="#" class="productdetailslink" onClick="if(confirm('<? echo stripslash_javascript($Captions_arr['PROD_DETAILS']['PRODDET_REM_CONFIRM']) ?>')){document.frm_proddetails.fpurpose.value='remove_favourite';document.frm_proddetails.submit();}"><img src="<?php url_site_image('remfavourite.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE']);?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE']);?>" /></a>
			<?php
			}
		}
		if($pdf_show==1) // Check whether the download pdf module is there for current site
		{ 
		?>
			<a href="javascript:download_pdf_common('<?=$_SERVER['HTTP_HOST']?>','<?=$_SERVER['REQUEST_URI']?>')" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADPDF']);?>"><img src="<?php url_site_image('pdf.gif')?>" border="0" /></a>

		<?php
		}
		if(in_array('mod_product_reviews',$inlineSiteComponents)) // Check whether the product review module is there for current site
		{
			if($writereview_show==1)
			{
		?>
				<a href="<?php url_link('writeproductreview'.$_REQUEST['product_id'].'.html')?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_WRITEREVIEW']);?>"><img src="<?php url_site_image('write-review.gif')?>" /></a>
		<?php
			}
			if($readreview_show==1)
			{
		?>	
				<a href="<?php url_link('readproductreview'.$_REQUEST['product_id'].'.html')?>" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_READREVIEW']);?>"><img src="<?php url_site_image('read-review.gif')?>" border="0" /></a>
		<?php
			}
		}
		if ($row_prod['product_deposit']>0 and $row_prod['product_deposit_message']!='')
		{
		?>
			<div class=" depositemess_outer">
			<div class="deposite_mess_divA"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_DEPOSTIT_REQ'])?> <?php echo $row_prod['product_deposit'].' '.stripslash_normal($Captions_arr['PROD_DETAILS']['DEP_PERCENTAGE'])?></div>
			<div class="deposite_mess_divB"><?php echo nl2br(stripslash_normal($row_prod['product_deposit_message']))?></div>
			</div>
		<?php	
		}
		?>
		</div>
		<?php
		echo "<div id='price_holder'>";
			$price_class_arr['ul_class'] 			= 'prodeulprice';
			$price_class_arr['normal_class'] 		= 'productdetnormalprice';
			$price_class_arr['strike_class'] 		= 'productdetstrikeprice';
			$price_class_arr['yousave_class'] 		= 'productdetyousaveprice';
			$price_class_arr['discount_class'] 		= 'productdetdiscountprice';
			echo show_Price($row_prod,$price_class_arr,'prod_detail');
		echo "</div>";
		// Calling function to show the buy button
		$this->show_buttons($row_prod);
		echo '<div id="bulkdisc_holder">';	
		// Calling the function to show the bulk discount (if any) 
		$this->show_BulkDiscounts($row_prod);
		echo '</div>';
		?>
		</div>
		
		</div>
		<?php
			// Check whether this product is linked with any of the combo deals which is currently active
			$in_combo = is_product_in_any_valid_combo($row_prod);
			
			if($in_combo==1 or $row_prod['product_freedelivery']==1 or $row_prod['product_show_pricepromise']==1)
			{
		?>
				<div class="det_pdt_middle_btns">
				<?php
				if($in_combo==1)
				{
				?>
					<a href="<?php url_link('showallbundle'.$row_prod['product_id'].'.html')?>" title=""><img src="<?php url_site_image('combo-offer.gif')?>" border="0"/></a>
				<?php
				}
				if($row_prod['product_freedelivery']==1)
				{
				?>	
					<a href="<?php url_link('freedelivery'.$row_prod['product_id'].'.html')?>" title="Free Delivery"><img src="<?php url_site_image('fre-del-det.gif')?>" border="0" /></a>
				<?php
				}
				if($row_prod['product_show_pricepromise']==1)
				{
				?>	
					<a href="javascript:handle_price_promise()" title="Price Promise"><img src="<?php url_site_image('price-promise.gif')?>" border="0"/></a>
				<?php
				}
				?>
				</div>
		<?php
			}
		?>
		<div class="det_pdt_bottom"></div>
		</div>
		<?php
		$tabs_arr			= $tabs_cont_arr	= array();
		if($row_prod['product_longdesc'])
			$tabs_content_arr[0]	= stripslashes($row_prod['product_longdesc']);
		elseif ($row_prod['product_shortdesc'])
			$tabs_content_arr[0]	= stripslashes($row_prod['product_shortdesc']);
		if (count($tabs_content_arr))
			$tabs_arr 		= array ('PRODDET_OVERVIEW'=>'Overview');
							
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
			   $tabs_arr[$row_tab['tab_id']]			= stripslash_normal($row_tab['tab_title']);
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
		?>
		<div class="det_pdtA_con" >
		<div class="det_pdtA_top"></div>
		<div class="det_pdtA_middle">
		<div class="det_pdtA_tabs">
		<?php
		if (count($tabs_arr))
		{		
			$curtab 		= ($_REQUEST['prod_curtab'])?$_REQUEST['prod_curtab']:0;
			if ($curtab==0 && $_REQUEST['prodmod']!='')
				$curtab = '';
			$prodimgdetid 	= (!$_REQUEST['prodimgdet'])?0:$_REQUEST['prodimgdet'];
			$chk=1;
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
						$pass_url		= url_product_tabs($row_prod['product_id'],$row_prod['product_name'],$prodimgdetid,$k_tabid,'#protabs',1);
					}
					else
					{
						$pass_url		= url_product($row_prod['product_id'],$row_prod['product_name'],1);
					}
				}
				else
				{
					$pass_url		= url_product_tabs($row_prod['product_id'],$row_prod['product_name'],$prodimgdetid,$k_tabid,'#protabs',1);
				}	
				if(!$sel)
				{
		 ?>
					<div id="tab_content_div_<?php echo $chk?>_1" class="det_tab_con" <?php /*?>onclick = "link_submit('<?php echo $k_tabid?>','<?php echo $_REQUEST['prodimgdet']?>','<?php echo $pass_url?>',0)"<?php */?>>
					<div id="tab_content_div_<?php echo $chk?>_2" class="det_tab_top"></div>
					<div id="tab_content_div_<?php echo $chk?>_3" class="det_tab_mid" ><a href="<?php echo $pass_url?>" title="<?php echo $v_tabtitle?>"><?php echo $v_tabtitle?></a></div>
					<div id="tab_content_div_<?php echo $chk?>_4" class="det_tab_bottom"></div>
					</div>
	<?php
				}
				else
				{
					$show_tab_title = $v_tabtitle;
		?>
					<div id="tab_content_div_<?php echo $chk?>_1" class="det_sel_tab_con">
					<div id="tab_content_div_<?php echo $chk?>_2" class="det_sel_tab_top"></div>
					<div id="tab_content_div_<?php echo $chk?>_3" class="det_sel_tab_mid"><a href="<?php echo $pass_url?>" title="<?php echo $v_tabtitle?>"><?php echo $v_tabtitle?></a></div>
					<div id="tab_content_div_<?php echo $chk?>_4" class="det_sel_tab_bottom"></div>
					</div>
	<?php		
				}
				$chk++;
			}
		}
		// Check whether labels exists for current product
		$ret_label_content = $this->show_ProductLabels($_REQUEST['product_id']);
		if($ret_label_content!='')
		{
		?>
			<div id="label_content_div_1" class="<?php echo ($_REQUEST['prodmod']=='productlabels')?'det_sel_tab_con':'det_tab_con'?>">
			<div id="label_content_div_2" class="<?php echo ($_REQUEST['prodmod']=='productlabels')?'det_sel_tab_top':'det_tab_top'?>"></div>
			<?php /*?><div id="label_content_div_3" class="det_tab_mid" ><a href="javascript:handle_tab_contents('label_content_div')" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']?>"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']?></a></div><?php */?>
			<div id="label_content_div_3" class="<?php echo ($_REQUEST['prodmod']=='productlabels')?'det_sel_tab_mid':'det_tab_mid'?>" ><a href="<?php url_link('productlabel'.$row_prod['product_id'].'.html')?>#protabs" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES'])?>"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES'])?></a></div>
			<div id="label_content_div_4" class="<?php echo ($_REQUEST['prodmod']=='productlabels')?'det_sel_tab_bottom':'det_tab_bottom'?>"></div>
			</div>
		<?php
		}
		
		if($Settings_arr['showsizechart_in_popup']!=1 and (is_array($heading)) && count($sizevalue)) // check whether size chart is set to show in a pop up window
		{
			if(is_array($heading))
			{ 
		?>
				<div id="sizechart_content_div_1" class="<?php echo ($_REQUEST['prodmod']=='sizechart')?'det_sel_tab_con':'det_tab_con'?>">
				<div id="sizechart_content_div_2" class="<?php echo ($_REQUEST['prodmod']=='sizechart')?'det_sel_tab_top':'det_tab_top'?>"></div>
				<div id="sizechart_content_div_3" class="<?php echo ($_REQUEST['prodmod']=='sizechart')?'det_sel_tab_mid':'det_tab_mid'?>" ><a href="<?php url_link('sizechart'.$row_prod['product_id'].'.html')?>#protabs" title="<?php echo $sizechartmain_title?>"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD'])?></a></div>
				<div id="sizechart_content_div_4" class="<?php echo ($_REQUEST['prodmod']=='sizechart')?'det_sel_tab_bottom':'det_tab_bottom'?>"></div>
				</div>
		<?php	
			}
		}	
	  ?>

	<input type='hidden' name="tab_cnts" id="tab_cnts" value="<?php echo count($tabs_arr)?>" />
	</div> 
	<div class="det_pdtA_tab_cnts" id="tab_content_div">
	<a name="protabs"></a>
	<?php
	if($_REQUEST['prodmod']=='')
	{
	?>
	<div class="det_overview"><?php echo $show_tab_title?></div>
	<?php
		echo $tabs_content_arr[$curtab];
	}
	?>
	</div>
	<?php
	if($Settings_arr['showsizechart_in_popup']!=1 and (is_array($heading)) && count($sizevalue)) // check whether size chart is set to show in a pop up window
	{
	?>
	<div class="det_pdtA_tab_cnts" id="sizechart_content_div" style="display:<?php echo ($_REQUEST['prodmod']=='sizechart')?'':'none'?>">
	<div class="det_overview"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);?></div>
	<?php
		$this->Show_Size_chart($heading,$cnt_hd,$sizevalue);
	?>
	</div>
	<?php
	}	
	// displaying the label content
	if($ret_label_content!='')
		echo $ret_label_content;
	?>
	</div>
	<div class="det_pdtA_bottom"></div>
	</div>
	<?php
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
			
				  <div class="det_pdtA_con" >
				<div class="det_pdtA_top"></div>
				<div class="det_pdtA_middle">
				<div class="det_pdtA_tabs">
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
										$download_icon = "$ecom_selfhttp$ecom_hostname/images/$ecom_hostname/attachments/icons/".$row_video['attachment_icon_img'];
									else
										$download_icon = url_site_image('download-icon.gif',1);
								?>
                          				<li><span><a href="<?php echo $ecom_selfhttp.$ecom_hostname?>/product_download.php?attach_id=<?php echo $row_video['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><img src="<?php echo $download_icon?>" border="0" /></a></span><span><a href="<?php echo $ecom_selfhttp.$ecom_hostname?>/product_download.php?attach_id=<?php echo $row_video['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php //echo $cnts++. ?><?php echo stripslashes($row_video['attachment_title'])?></a></span></li>
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
				</div>
				<div class="det_pdtA_bottom"></div>
				</div>          
				<?php
		}
	// ** Check whether any linked products exists for current product
	$sql_linked = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
					a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
					a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
					a.product_total_preorder_allowed,a.product_applytax,product_shortdesc,
					a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,
					a.product_variablesaddonprice_exists,a.product_variablecomboprice_allowed,
					a.product_variablecombocommon_image_allowed,a.default_comb_id,
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
	?>
	</form>
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
			   <script type="text/javascript" src="https://www.healthstore.uk.com/images/www.healthstore.uk.com/lightjquery/lightbox-plus-jquery.min.js"></script>

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
		case '2row':
?>
		<div class="det_link_con" >
		<div class="det_link_top"></div>
		<div class="det_link_middle">
		<div class="det_link_hdr"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_PEOPLE_INTERESTED']);?></div>
		<div class="det_link_pdt_con">
		<div class="det_link_nav"><a href="#null" onmouseover="scrollDivRight('containerA')" onmouseout="stopMe()"><img src="<?php url_site_image('link-arw-lft.gif')?>" /></a></div>
		<div  id="containerA" class="det_link_pdt_inner">
		<div id="scroller">
		<?php
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
		<div class="det_link_nav"> <a href="#null" onmouseover="scrollDivLeft('containerA')" onmouseout="stopMe()"><img src="<?php url_site_image('link-arw-rht.gif')?>" /></a></div>
		</div>
        </div>
        <div class="det_link_bottom"></div>
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
				alert('<?php echo stripslash_javascript($Captions_arr['PROD_DETAILS']['PRODDET_COMPARING']);?>'); 
			else if(totcnt>maxcnt) 
				alert('<?php echo stripslash_javascript($Captions_arr['PROD_DETAILS']['PRODDET_SORRY_COMPARING']);?>'+(maxcnt)+' <?php echo stripslash_javascript($Captions_arr['PROD_DETAILS']['PRODDET_SORRY_COMPARING_TIME']);?>'); 
			else
			{
				checked_comp = def_prodid+','+checked_comp;
				document.frm_proddet_comp.detcomp_prods.value = checked_comp;
				document.frm_proddet_comp.submit();
			}
		}
	</script>
		<div class="treemenu">
		<ul>
		<li><a href="<? url_link('');?>" title="<?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?>"><?=stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']);?></a> &gt;&gt; </li>
		<li><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_HEAD']);?></li>
		</ul>
		</div>
		<div class="mid_shlf_con" >
		<?php  $msg = stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_MAX_COMP_PROD']); $msg = str_replace('[prodno]',$Settings_arr['no_of_products_to_compare'],$msg); echo $msg?>
		<div class="compare_main_div">
		<div class="compare_back_button">
			<input type="button" name="prodet_backprod" value="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BACK_TO_DETAIL']);?>" class="buttonred_cart" onclick="window.location='<?php url_product($_REQUEST['product_id'],'',-1)?>'"/>
		</div>
		<div class="compare_gobutton">
			<input type="button" name="prodet_comparebutton" value="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['COMPARE_PRODUCTS']);?>" class="buttonred_cart" onclick="handle_proddet_compare()"/>
		</div>
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
			<div class="mid_shlf_top"></div>
			<div class="mid_shlf_middle">
			<div class="mid_shlf_pdt_name"><input type="checkbox" name="chkproddet_comp_<?php echo $row_prod['product_id']?>" id="chkproddet_comp_<?php echo $row_prod['product_id']?>" value="<?php echo $row_prod['product_id']?>" <?php echo $compare_checked?>/><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div>
			<div class="mid_shlf_mid">
				<div class="mid_shlf_pdt_image">
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
				<? 
				if($comp_active)
				{
				?>
					<div class="mid_shlf_pdt_compare" >
					<?php	dislplayCompareButton($row_prod['product_id']);?>
					</div>
				<?php	
				}
				?>
				</div>
			</div>
			<div class="mid_shlf_pdt_des">
				<?php
				echo stripslash_normal($row_prod['product_shortdesc']);
				$module_name = 'mod_product_reviews';
				if(in_array($module_name,$inlineSiteComponents))
				{
					if($row_prod['product_averagerating']>=0)
					{
					?>
						<div class="mid_shlf_pdt_rate">
						<?php
							display_rating($row_prod['product_averagerating']);
						?>
						</div>
					<?php
					}
				}	
				if($row_prod['product_bulkdiscount_allowed']=='Y')
				{
				?>
					<div class="mid_shlf_pdt_bulk"><img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/></div>
				<?php
				}
				if($row_prod['product_saleicon_show']==1)
				{
				$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
				if($desc!='')
				{
				?>	
					<div class="mid_shlf_pdt_sale"><?php echo $desc?></div>
				<?php
				}
				}
				if($row_prod['product_newicon_show']==1)
				{
					$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
					if($desc!='')
					{
					?>
						<div class="mid_shlf_pdt_newsale"><?php echo $desc?></div>
					<?php
					}
				}
				?>
			</div>
			<div class="mid_shlf_pdt_price">
				<?php 
				if($row_prod['product_freedelivery']==1)
				{	
				?>
					<div class="mid_shlf_free"></div>
				<?php
				}
				$price_class_arr['class_type'] 		= 'div';
				$price_class_arr['normal_class'] 	= 'shlf_normalprice';
				$price_class_arr['strike_class'] 	= 'shlf_strikeprice';
				$price_class_arr['yousave_class'] 	= 'shlf_yousaveprice';
				$price_class_arr['discount_class'] 	= 'shlf_discountprice';
				echo show_Price($row_prod,$price_class_arr,'linkprod_1');
				$frm_name = uniqid('linked_');
				?>	
				<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
				<input type="hidden" name="fpurpose" value="" />
				<input type="hidden" name="fproduct_id" value="" />
				<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
				<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
				<div class="mid_shlf_buy">
				<div class="mid_shlf_info_btn"><?php show_moreinfo($row_prod,'mid_shlf_info_link')?></div>
				<div class="mid_shlf_buy_btn">
				<?php
					$class_arr 					= array();
					$class_arr['ADD_TO_CART']	= 'mid_shlf_buy_link';
					$class_arr['PREORDER']		= 'mid_shlf_buy_link';
					$class_arr['ENQUIRE']		= 'mid_shlf_buy_link';
					//show_addtocart($row_prod,$class_arr,$frm_name);
					/* Code for ajax setting starts here */
					$class_arr['BTN_CLS']       = 'mid_shlf_buy_link';
					show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr);
					/* Code for ajax setting ends here */
				?>
				</div>
				</div>
				</form>       
			</div>
			</div>
			<div class="mid_shlf_bottom"></div>
	<?php
		}
	?>
		
	<div  align="right" class="compare_bottom_btn"><input type="button" name="prodet_comparebutton" value="<?php echo stripslash_normal($Captions_arr['COMMON']['COMPARE_PRODUCTS']);?>" class="buttonred_cart" onclick="handle_proddet_compare()"/>
	</div>
	</div>
<?php
}
// ** Function to display the variables. This is written as function to avoid code repetation in various if conditions
function show_ProductVariables($row_prod,$pos='column',$sizechart_heading)
{
	global $db,$ecom_siteid,$Captions_arr,$Settings_arr,$ecom_themename,$ecom_hostname;
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
		<div class="pro_varable_con">
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
					if($i%2==0)
					{
					$clss='productvariabletdA';
					$clss2='productvariabletdAA';
					}
					else
					{
					$clss='productvariabletdB';
					$clss2='productvariabletdBB';
					}
					//$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
					$var_exists = true;
				?>	
				  <tr>
					<td align="left" valign="middle" class="<?php echo $clss?>"><?php echo stripslash_normal($row_var['var_name'])?></td>
					<td align="left" valign="middle" class="<?php echo $clss2?>">
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
							<select name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" <?php echo $onchange_function?>>
							<?php 
									while ($row_vals = $db->fetch_array($ret_vals))
									{
							?>
											<option value="<?php echo $row_vals['var_value_id']?>" <?php echo ($_REQUEST['var_'.$row_var['var_id']]==$row_vals['var_value_id'])?'selected':''?>><?php echo stripslash_normal($row_vals['var_value'])?><?php echo Show_Variable_Additional_Price($row_prod,$row_vals['var_addprice'],$vardisp_type)?></option>
							<?php
									}
							?>
							</select>							
						<?php
							} else
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
				if($i%2==0)
					{
					$clss='productvariabletdA';
					$clss2='productvariabletdAA';
					}
					else
					{
					$clss='productvariabletdB';
					$clss2='productvariabletdBB';
					}
				//$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
				$var_exists = true;
			?>
				  <tr>
					<td align="left" valign="top" class="<?php echo $clss?>"><?php echo stripslash_normal($row_msg['message_title'])?></td>
					<td align="left" valign="top" class="<?php echo $clss2?>">
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
							<textarea name="varmsg_<?php echo $row_msg['message_id']?>" id="varmsg_<?php echo $row_msg['message_id']?>" rows="3" cols="25"><?php echo $_REQUEST['varmsg_'.$row_msg['message_id']]?></textarea>
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
		<?php
		if($Settings_arr['showsizechart_in_popup']==1 and $sizechart_heading['size_Availvalues']==true) // If size chart is set to show in a pop up window
		{
			if($i%2==0)
			{
				$clss='productvariabletdA';
				$clss2='productvariabletdAA';
			}
			else
			{
				$clss='productvariabletdB';
				$clss2='productvariabletdBB';
			}
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="variabletable" id="proddet_var_table">
			<tr>
					<td align="right" valign="top" class="<?php echo $clss?>" colspan="2">
			<div class="show_sizechart_popup_div">
			<a href="javascript:showsizechartPopup('<?php echo $row_prod['product_id']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')" title="<?php echo stripslash_normal($sizechartmain_title)?>">size chart<?php /*?><img src="<?php url_site_image('size-chart.gif')?>" alt="<?php echo stripslash_normal($sizechartmain_title)?>" border="0" /><?php */?></a>
			</div>
			</td>
			</tr>
			</table>
		<?php	
		}
	}
	 if($sizechart_heading['size_Availvalues']==false && $row_prod['product_commonsizechart_link']!='')
		{
		?>  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="variabletable" id="proddet_var_table">
				<tr>
					<td align="right" valign="top" class="<?php echo $clss?>" colspan="2">
					   <div class="show_sizechart_popup_div">
						<a href="<?=$row_prod['product_commonsizechart_link']?>" title="SizeChart" 	target="<?=$row_prod['produt_common_sizechart_target']?>">size chart</a>
						</div>
					</td>
				</tr>
			</table>
		 <?
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
		<div class="bulk_con">
		<div class="bulk_top"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_DISC_HEAD'])?></div>
		<div class="bulk_bottom">
		<div class="bulk_inner">
		<?php
		for($i=0;$i<count($bulkdisc_details['qty']);$i++)
		{
			if($i>0)
				echo "<br>";
			echo $bulkdisc_details['qty'][$i].' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_FOR']).' ';
			//echo print_price($bulkdisc_details['price'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);
			echo product_BulkDiscount_Build_Price($bulkdisc_details['price'][$i],$bulkdisc_details['price_without'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);
		}
		?>
		</div>
		</div>
		</div>
	<?php
	}
}
/* Function to show the instock notification */
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
					<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK'])?>.
				 <br />
				<span class="instockmsg_out_stock">
				<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK_EMAIL'])?></span>
				<input type="text"   name="stock_email" />
				<input type="hidden" name="prod_mod" value="stock_notify" />
				<input type="hidden" name="hid_notify" value="stock" /> 
				<input type="button" name="stocknotif_submit" value="Send" class="buttongray" onclick=" validate_stocknotify(document.frm_proddetails)"  />
				<input type="hidden" name="product_id" value="<?PHP echo $_REQUEST['product_id']; ?>" />
			<!--	<span style="font-weight:bold;">
				<a href="javascript:handle_instocknotification('<?php// echo $row_prod['product_id']?>','<?php// echo $ecom_hostname?>')" style="color:#000000;text-decoration:underline">click here</a></span>  -->
				</div>
			<?php	
				}
				else
				{
			?>	
				<div  class="alert_inner"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK'])?>.
				<br />
				<span style="font-size:12px;font-weight:normal;color:#000000;"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK_EMAIL'])?><br />

					<input type="text"   name="stock_email" />
					<input type="hidden" name="prod_mod" value="stock_notify" />
					<input type="hidden" name="hid_notify" value="stock" /> 
					<input type="button" name="stocknotif_submit" value="Send" class="buttongray" onclick=" validate_stocknotify(document.frm_proddetails)"  />
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
					$disp_val = ($_REQUEST['prodmod']=='productlabels')?'':'none';
					$ret_val = '<div class="det_pdtA_tab_cnts" id="label_content_div" style="display:'.$disp_val.'">
								<div class="det_overview">'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']).'</div>
								<table width="100%" border="0" cellpadding="0" cellspacing="0" class="keyfeature" id="proddet_var_table">';
				
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
						$display_ok = true;
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
										$clss = ($i%2==0)?'keyfeatureA':'keyfeatureB';
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
								</div>
								';	
				}
			}
		}	
	}
	if($display_ok==false)
		$ret_val = '';
	return  $ret_val ;	
}
function show_buttons($row_prod)
{
	global $Captions_arr,$showqty,$Settings_arr,$ecom_siteid,$db,$glob_qty_displayed;
	$addto_cart_withajax =  $Settings_arr['enable_ajax_in_site'];
	
	$cust_id 	= get_session_var("ecom_login_customer");
	$showqty	= $Settings_arr['show_qty_box'];// show the qty box
	$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslash_normal($row_prod['product_det_qty_caption']):stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY']);
	// Get the caption to be show in the button
	$caption_key = show_addtocart($row_prod,array(0),'',true);
	if ($caption_key!='') // Check whether the buynow or preorder button is to be displayed
	{
	?>	
	<div class="mid_det_btn">
	<?php	
	if($showqty==1)// this decision is made in the main shop settings
	{
		if($row_prod['product_det_qty_type']=='NOR')
		{
?>
			<div><input type="text" class="det_quainput" name="qty"  value="<?php echo ($_REQUEST['qty'])?$_REQUEST['qty']:1?>" maxlength="4" /></div>
<?php
		}
		elseif($row_prod['product_det_qty_type']=='DROP')
		{
			$dropdown_values = explode(',',stripslash_normal($row_prod['product_det_qty_drop_values']));
			if (count($dropdown_values) and stripslash_normal($row_prod['product_det_qty_drop_values'])!='')
			{
			?>
				<div>
				<select name="qty">
				<?php 
					$qty_prefix = stripslash_normal($row_prod['product_det_qty_drop_prefix']);
					$qty_suffix = stripslash_normal($row_prod['product_det_qty_drop_suffix']);
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
	if($addto_cart_withajax == 1)
	{
?><div><a href="javascript:void(0);" class="det_buy_link" onclick="ajax_addto_cart_fromlist('add_prod_tocart_ajax','Prod_Addcart','frm_proddetails','<?php echo SITE_URL?>')"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS'][$caption_key]);?></a></div>
<?php
	}
	else
	{
?><div><a href="#" class="det_buy_link" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addcart';document.frm_proddetails.submit();"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS'][$caption_key]);?></a></div>
<?php
	}
?>
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
	$pass_type 	= 'image_iconpath';
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
				$prodimg_arr = get_imagelist_combination($row_prod['default_comb_id'],$pass_type,$exclude_prodid,0);
		}		
		else
		{
			if ($exclude_prodid)
				$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,$exclude_prodid,0);
		}
	 } 
	if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
	{
?>	
		<div class="zoom_con"> 
		<div class="zoom_top">Zoom </div>
		<div class="zoom_middle">
		<?php
		$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
		foreach ($prodimg_arr as $k=>$v)
		{ 
			$title = ($v['image_title'])?stripslash_normal($v['image_title']):$row_prod['product_name'];
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
		<div class="zoom_bottom"> </div>
		</div>	
<?php
	}
}	
function Show_Image_Normal($row_prod,$just_return_id=false)
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
			if($just_return_id!=true)
			{
			?>
			<a href="<?php url_root_image($tabimg_arr[0]['image_extralargepath'])?>" title="<?php echo $row_prod['product_name']?>" rel='lightbox[gallery]' >
			<?php
			show_image(url_root_image($tabimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
			?>
			</a>
			<?php
			}
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
				if($just_return_id!=true)
				{
				?>
				<a href="<?php url_root_image($prodimg_arr[0]['image_extralargepath'])?>" class="example-image-link" data-lightbox="example-1" title="<?=$row_prod['product_name']?>">
				<?php
				show_image(url_root_image($prodimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
				?>
				</a>
				<?php
				}
				$show_noimage 	= false;
			}
			else
			{	
				// calling the function to get the default no image 
				$no_img = get_noimage('prod','big'); 
				if ($no_img)
				{
					if($just_return_id!=true)
					{
						show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
					}	
				}	
			}
		}
		$ret_arr['exclude_tabid']		= $exclude_tabid;
		$ret_arr['exclude_prodid'] 		= $exclude_prodid;
		return $ret_arr;
}
function Show_Size_chart($heading,$cnt_hd,$sizevalue)
{
	global $db,$ecomn_siteid,$ecom_hostname,$ecom_themename;
	?>
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
		 <?php
}

/* Code for ajax setting starts here */
function show_buttons_ajax($row_prod)
{
	global $Captions_arr,$showqty,$Settings_arr,$ecom_hostname;
	$addto_cart_withajax =  $settings_arr['enable_ajax_in_site'];
	$cust_id 	= get_session_var("ecom_login_customer");
	$showqty	= $Settings_arr['show_qty_box'];// show the qty box
	$Captions_arr['PROD_DETAILS'] = getCaptions('PROD_DETAILS');
	
	$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslashes($row_prod['product_det_qty_caption']):$Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY'];
	// Get the caption to be show in the button
	$caption_key = show_addtocart($row_prod,array(0),'',true);
	?>			
	<div class="p_content_buy" >   
	<?php
	if ($caption_key!='') // Check whether the buynow or preorder button is to be displayed
	{
	?>			
	<?php
	if($showqty==1)// this decision is made in the main shop settings
	{
	if($row_prod['product_det_qty_type']=='NOR')
	{
	?>
	<div class="p_content_buy_A"><?php echo $cur_qty_caption?></div>
	<div class="p_content_buy_B"><input type="text" class="quainput_big_ajax" name="qty" value="<?php echo ($_REQUEST['qty'])?$_REQUEST['qty']:1?>" maxlength="2" />
	</div>
	<?php
	}
	elseif($row_prod['product_det_qty_type']=='DROP')
	{
	$dropdown_values = explode(',',stripslashes($row_prod['product_det_qty_drop_values']));
	if (count($dropdown_values) and stripslashes($row_prod['product_det_qty_drop_values'])!='')
	{
	?>
	<div class="p_content_buy_A"><?php echo $cur_qty_caption ?></div>
	<div class="p_content_buy_B">
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
	
	?>
	<div class="p_content_buy_C"><input name="Submit_buy" type="button" class="ajax_buy_now" id="Submit_buy" value="<?php echo $Captions_arr['PROD_DETAILS'][$caption_key];?>"	onclick="ajax_addto_cart_fromlist('add_prod_tocart_ajax','Prod_Addcart','frm_proddetails_ajax','<?php echo SITE_URL?>')" /></div>
	<?php
	}
	
	?>
	</div>
	
	<div class="p_content_more_info"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">More Info</a>
	<?php
	if ($row_prod['product_show_enquirelink']==1 )
	{
	?>
	<div class="enq_wish_container_ajax">
	<?php 
	// Check whether the enquire link is to be displayed
	if ($row_prod['product_show_enquirelink']==1)
	{
	?>
	<input name="Submit_enq" type="button" class="buttonblackbuy_ajax" id="Submit_enq" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?>" onclick="ajax_addto_cart_fromlist('add_prod_tocart_ajax','Prod_Enquire','frm_proddetails_ajax','<?php echo SITE_URL?>')" /> 
	<?php
	}			
	?>
	</div>
	<?php	
	}
	?>
	</div>
	
	<?php			
	return true;
}
function show_ProductVariables_ajax($row_prod,$pos='column',$product_id)
{
					global $db,$ecom_siteid,$Captions_arr,$ecom_hostname,$ecom_themename;
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
					<div class="p_content_variable">

					<?php
					if($pos!='column')
					{
					?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td class="variable_bottom_border">
					<div class="variabletabcontainer">
					<ul class="variabletab">
					<li id="var_li" class="variableselected"
					<?php /* onclick is required only in shown in a new row*/if ($pos=='row') {?>
					onclick="handle_proddetail_variable('var')" <?php }?>><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_PRODVARS']?></li>	
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
					</table>
					<?php
					}
					?>

					<?php
					// Case of variables
					if ($db->num_rows($ret_var) or $db->num_rows($ret_msg))
					{
					?>

					<?php

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

					<div class="p_content_variable_name"><?php echo stripslashes($row_var['var_name'])?></div>
					<div class="p_content_variable_drop">
					<?php
					if ($row_var['var_value_exists']==1)
					{

					if($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
					{
					$onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"price\",\"".SITE_URL."\",\"".$product_id."\",\"".url_site_image('loading.gif',1)."\",\"ajax\")' ";
					$onclick_var            = "price";
					}
					elseif($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='N')
					{
					$onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"price\",\"".SITE_URL."\",\"".$product_id."\",\"".url_site_image('loading.gif',1)."\",\"ajax\")' ";
					$onclick_var            = "price";
					}
					elseif($row_prod['product_variablecomboprice_allowed']=='N' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
					{
					$onchange_function      = $onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"main_img\",\"".SITE_URL."\",\"".$product_id."\",\"".url_site_image('loading.gif',1)."\",\"ajax\")' ";
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
					$onclick_function   = " onclick ='var_onclick(\"".$onclick_var."\",\"var_".$row_var['var_id']."\",".$row_vals['var_value_id'].",\"".$normal_cls_clr."\",\"".$special_cls_clr."\",\"".$normal_cls_clrimg."\",\"".$special_cls_clrimg."\",\"".$normal_cls_sz."\",\"".$special_cls_sz."\",\"".$docroot."\",\"".$prodid."\",\"".$loading_gif."\",\"ajax\")' ";
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
					<input type="checkbox" name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" value="1" <?php echo ($_REQUEST['var_'.$row_var['var_id']])?'checked="checked"':''?> /><?php echo Show_Variable_Additional_Price($row_prod,$row_var['var_price'],$vardisp_type)?>
					<?php
					}
					?>
					</div>
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
					<div class="p_content_variable_name"><?php echo stripslashes($row_msg['message_title'])?></div>
					<div class="p_content_variable_drop">
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
					</div>
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
					</div>
					<?php
					$size_checkval = false;
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
						if($db->num_rows($res))
						{ 
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
									
								  $sizechart_heading[] = $heading;
								  $sizechart_heading['size_Availvalues'] = $size_checkval;
						  }		  
							if($sizechart_heading['size_Availvalues']==true) // If size chart is set to show in a pop up window
							{
							if(is_array($sizechart_heading))
							{
							?>
							<div class="deat_pdt_sizechart_A"><div class="sizechartleft"><div><a href="javascript:showsizechartPopup('<?php echo $row_prod['product_id']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')" title="<?php echo stripslash_normal($sizechartmain_title)?>">Size Chart<?php ?></a></div></div></div>
							<?php	
							}
							}
							if($sizechart_heading['size_Availvalues']==false && $row_prod['product_commonsizechart_link']!='')
							{
							?>
							<div class="deat_pdt_sizechart_A"><div class="sizechartleft"><div><a href="<?=$row_prod['product_commonsizechart_link']?>" title="SizeChart" 	target="<?=$row_prod['produt_common_sizechart_target']?>">Size Chart<?php ?></a></div></div></div>
							<?
							} 
					
					}
					return $var_exists;
}
//* Function to get the count of variables
function get_countvariables_count($prod_id)
{
	global $db,$ecom_siteid,$Captions_arr;

		$sql_valuecnt = "SELECT count(var_id) as varcnts  
						FROM 
							product_variables 
						WHERE 
							products_product_id = '".$prod_id."' 
							AND var_value_exists=1 
							AND var_hide=0";
		$ret_valuecnt = $db->query($sql_valuecnt);
		if($db->num_rows($ret_valuecnt)>0)
		{
		$row_valuecnt = $db->fetch_array($ret_valuecnt);
		return $row_valuecnt['varcnts'];
		}
		else
		{
		  return 0;
		}
}
// ** Function to display the variables. This is written as function to avoid code repetation in various if conditions
function show_ProductVariables_specialdisplay($row_prod,$mod='normal')
{
				global $db,$ecom_siteid,$Captions_arr,$Settings_arr,$ecom_hostname,$ecom_themename;
				$i = 0;
				$product_id = $row_prod['product_id'];
				if($mod=='normal')
				{
				     $frm_name = "frm_proddetails";
				}
				else
				{
				    $frm_name = "frm_proddetails_ajax";
				}
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
						<div class="<?php echo ($mod=='normal')?'p_variable_A':'p_variable'; ?>">
						<?php
									$size_checkval = false;
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
						if($db->num_rows($res))
						{ 
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
									
								  $sizechart_heading[] = $heading;
								  $sizechart_heading['size_Availvalues'] = $size_checkval;
						  }		  
							if($sizechart_heading['size_Availvalues']==true) // If size chart is set to show in a pop up window
							{
							if(is_array($sizechart_heading))
							{
							?>
							<div class="deat_pdt_sizechart_A"><div class="sizechartleft"><div><a href="javascript:showsizechartPopup('<?php echo $row_prod['product_id']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')" title="<?php echo stripslash_normal($sizechartmain_title)?>">Size Chart<?php ?></a></div></div></div>
							<?php	
							}
							}
							if($sizechart_heading['size_Availvalues']==false && $row_prod['product_commonsizechart_link']!='')
							{
							?>
							<div class="deat_pdt_sizechart_A"><div class="sizechartleft"><div><a href="<?=$row_prod['product_commonsizechart_link']?>" title="SizeChart" 	target="<?=$row_prod['produt_common_sizechart_target']?>">Size Chart<?php ?></a></div></div></div>
							<?
							}
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
											$var_disp_type ='DROPDOWN';
											$comb_arr =array();
											$var_arr  = array();
											$var_id = '';
											$value_id = '';
												while ($row_vals = $db->fetch_array($ret_vals))
												{
													$stock_arr =array();
													$var_id = $row_var['var_id'];
													$value_id = $row_vals['var_value_id'];
												//$row_prod['combination_id'] = '';
                                                $var_arr[$var_id] =  $value_id;
												$comb_arr = get_combination_id($product_id,$var_arr);
												$row_prod['combination_id'] 		= $comb_arr['combid']; // this done to handle the case of showing the variables price in show price
												$row_prod['check_comb_price'] 	= 'YES';// this done to handle the case of showing the variables price in show price
												$frm = 'frm_prdtdetails_'.$value_id;
												?>
												
												<input type="hidden" id="var_<?php echo $var_id?>" name="var_<?php echo $var_id?>" value="<?php echo $value_id?>"/>

												<div class="<?php echo ($mod=='normal')?'p_variable_variable_name_A':'p_variable_variable_name'; ?>"><?php echo stripslashes($row_var['var_name'])?>&nbsp;:&nbsp;<?php echo stripslashes($row_vals['var_value'])?></div>
												<div class="<?php echo ($mod=='normal')?'p_variable_price_C_A':'p_variable_price_C'; ?>">
													
												<?php													
													$price_class_arr['ul_class'] 			= 'prodeulprice';
													$price_class_arr['normal_class'] 		= 'productdetnormalprice';
													$price_class_arr['strike_class'] 		= 'productdetstrikeprice';
													$price_class_arr['yousave_class'] 	= 'productdetyousaveprice';
													$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
													/* Code for ajax setting starts here */
													$row_prod['cur_var_value_id'] 		= $value_id;
													/* Code for ajax setting ends here */
													echo show_Price($row_prod,$price_class_arr,'prod_detail')." ";
												?>
													<ul class="prodeuladprice">
														<li><?php echo Show_Variable_Additional_Price($row_prod,$row_vals['var_addprice'],$vardisp_type);?></li>
													</ul>													
												</div>
												<?php
												$bulkdisc_details = product_BulkDiscount_Details($product_id,$comb_arr['combid']);
												if (count($bulkdisc_details['qty']))
												{
												?>	
												 <div class="<?php echo ($mod=='normal')?'variable_inner_a_A':'variable_inner_a'; ?>" id="bulkdisc_holder">

													  <?php
														for($i=0;$i<count($bulkdisc_details['qty']);$i++)
														{
														?>	
															 <?php echo $Captions_arr['PROD_DETAILS']['BULK_BUY']?> <?php echo $bulkdisc_details['qty'][$i].' '.$Captions_arr['PROD_DETAILS']['BULK_FOR'].' '?> 
																<?php //echo print_price($bulkdisc_details['price'][$i]).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH']?>
																<?php 	echo product_BulkDiscount_Build_Price($bulkdisc_details['price'][$i],$bulkdisc_details['price_without'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);?><br />
													  <?php
														}
													  ?>
													  
													  </div>
												<?php
												}
																					?>
												<div class="<?php echo ($mod=='normal')?'p_variable_buy_Az':'p_variable_buy'; ?>">
												<?php
												$showqty	= $Settings_arr['show_qty_box'];// show the qty box
												$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslashes($row_prod['product_det_qty_caption']):$Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY'];
												$caption_key    = show_addtocart($row_prod,array(0),'',true);
												$stock_arr  	= check_stock_available($product_id,$var_arr);
												$stock 		    = $stock_arr['stock'];
												if($caption_key=='' || ($stock==0 && $caption_key!='PRODDET_PREORDER') )
												{
												?>
												  <div class="<?php echo ($mod=='normal')?'p_variable_enquire_C_A':'p_variable_enquire_C'; ?>"><a href="javascript:void(0)" onclick="ajax_addto_cart_special_display('add_prod_tocart_ajax','Prod_Enquire','<?php echo $frm_name ?>','<?php echo SITE_URL?>','<?php echo $var_id ?>','<?php echo $value_id ?>')"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?></a></div>									
												<?php
												}
												else
												{
												if($showqty)
												{	
													if($row_prod['product_det_qty_type']=='NOR')
													{
													?>
													<div class="<?php echo ($mod=='normal')?'p_variable_buy_A_A':'p_variable_buy_A'; ?>"><?php echo $cur_qty_caption ?></div>											
													<div class="<?php echo ($mod=='normal')?'p_variable_buy_B_A':'p_variable_buy_B'; ?>"><input name="varqty_<?php echo $value_id?>" value="1" type="text" /></div>
													<?php
													}
													elseif($row_prod['product_det_qty_type']=='DROP')
													{
														$dropdown_values = explode(',',stripslashes($row_prod['product_det_qty_drop_values']));
														if (count($dropdown_values) and stripslashes($row_prod['product_det_qty_drop_values'])!='')
														{
														?>
															<div class="<?php echo ($mod=='normal')?'p_variable_buy_A_A':'p_variable_buy_A'; ?>"><?php echo $cur_qty_caption ?></div>
															<div class="<?php echo ($mod=='normal')?'p_variable_buy_B_A':'p_variable_buy_B'; ?>">
															<select name="varqty_<?php echo $value_id?>">
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
												?>
												<div class="p_variable_buy_C_A"><a href="javascript:void(0)" onclick="ajax_addto_cart_special_display('add_prod_tocart_ajax','Prod_Addcart','<?php echo $frm_name ?>','<?php echo SITE_URL?>','<?php echo $var_id ?>','<?php echo $value_id ?>')"><?php echo $Captions_arr['PROD_DETAILS'][$caption_key];?></a></div>
												<div class="p_variable_buy_C_Otr">
													<?php
													if ($row_prod['product_show_enquirelink']==1)
													{
													?>	
														<div class="p_variable_buy_C_Q"><a href="javascript:void(0)" onclick="ajax_addto_cart_special_display('add_prod_tocart_ajax','Prod_Enquire','<?php echo $frm_name ?>','<?php echo SITE_URL?>','<?php echo $var_id ?>','<?php echo $value_id ?>')"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?></a></div>									
													<?php 
													}
													if ($Settings_arr['proddet_showwishlist'])
													{
													?>
														<div class="p_variable_buy_C_W"><a href="javascript:void(0)" onclick="ajax_addto_cart_special_display('add_prod_tocart_ajax','Prod_Addwishlist','<?php echo $frm_name ?>','<?php echo SITE_URL?>','<?php echo $var_id ?>','<?php echo $value_id ?>')"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?></a></div>									
													<?php
													}
													?>
													</div>
												<?php
											    }											   
												?>
												</div>
												
												<?php
												}
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
										<div class="<?php echo ($mod=='normal')?'p_variable_Mes_A':'p_variable_Mes'; ?>">
										<div class="<?php echo ($mod=='normal')?'p_variable_Mes_A_A':'p_variable_Mes_A'; ?>"><?php echo stripslashes($row_msg['message_title'])?></div>
										<div class="<?php echo ($mod=='normal')?'p_variable_Mes_B_A':'p_variable_Mes_B'; ?>">
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
										</div>	
										</div>									
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
						
						</div>
						<?php
				}
				return $var_exists;
}
/* Code for ajax setting ends here */
};	
?>
