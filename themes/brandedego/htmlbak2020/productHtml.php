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
					 $ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$meta_arr,$ecom_twitteraccountId;
					 
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
				$alert 	= stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVADDED']);
			}
			else if($_REQUEST['result']=='removed')
			{
				$alert 	= stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVREMOVED']);
			}
			$prod_img_show_type = $row_prod['product_details_image_type'];
			$email_show 		= 0;
			$favourite_show		= 0;
			$writereview_show	= 0;
			$readreview_show	= 0;
			$pdf_show			= 0;
			$compare_show		= 0;
			
			if(isProductCompareEnabledInProductDetails())
			{
				$def_cat_id = $row_prod['product_default_category_id'];
				$sql_comp 	= "SELECT a.product_id        
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
				$pdf_show	= 1;	 // pdf download link
			if($cust_id)
			{
				if($Settings_arr['proddet_showfavourite']==1)
					$favourite_show = 1;
			}

			
			/********************** HTML Generating starts here *********************/
			$HTML_treemenu = $HTML_showstock = $HTML_compare = $HTML_saleicon = $HTML_fav = $HTML_readrev = $HTML_writerev = '';
			$HTML_email = $HTML_pdf = $HTML_price = $HTML_bonus = $HTML_wishlist = $HTML_enquiry = $HTML_promise_buttons = '';
			$HTML_treemenu = ' <div class="tree_menu_con">
								<div class="tree_menu_mid_list">
								<div class="tree_menu_content_list">
								<ul class="tree_menu">'.generate_tree_menu(-1,$_REQUEST['product_id'],'','<li>','</li>').'
								</ul>
								</div>
								</div>
								</div>';
			$disp_stk = get_stockdetails($_REQUEST['product_id']);
			if($disp_stk!='')
				$HTML_showstock = '<div class="deat_pdt_stock"><div class="deat_pdt_stock_left"><span>'.$disp_stk.'</span></div></div>';
			if( $compare_show==1)
			{
				$def_cat_id = $row_prod['product_default_category_id'];
				$HTML_compare = '<a href="'.url_productcompare($_REQUEST['product_id'],$row_prod['product_name'],1).'" class="productdetailslink"  title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_LINK']).'"><img src="'.url_site_image('cc.gif',1).'" border="0" alt="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_LINK']).'" /></a>';
				
				
			}
			if($row_prod['product_saleicon_show']==1)
			{
				$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
				if($desc!='')
				{
					$HTML_saleicon = '<div class="deat_pdt_sale">'.$desc.'</div>';
				}
			}
			if($row_prod['product_newicon_show']==1)
			{
				$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
				if($desc!='')
				{
					$HTML_newicon = '<div class="deat_pdt_new">'.$desc.'</div>';
				}
			}
			if($favourite_show==1) // Decide whether favorite option is to be displayed
			{
				$sql_prod = "SELECT products_product_id FROM customer_fav_products WHERE sites_site_id=$ecom_siteid AND products_product_id=".$_REQUEST['product_id']." AND customer_customer_id=$cust_id LIMIT 1";
				$ret_num= $db->query($sql_prod);
				if($db->num_rows($ret_num)==0) 
				{ 
					$HTML_fav = '<a href="#" onClick="if(confirm(\''.stripslash_javascript($Captions_arr['PROD_DETAILS']['PRODDET_ADD_CONFIRM']).'\')){document.frm_proddetails.fpurpose.value=\'add_favourite\';document.frm_proddetails.submit();}"><img src="'.url_site_image('det-icon_01.gif',1).'" alt="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE']).'" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE']).'" /> '.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE']).'</a>';
				}
				else
				{
					$HTML_fav = '<a href="#" class="productdetailslink" onClick="if(confirm(\''.stripslash_javascript($Captions_arr['PROD_DETAILS']['PRODDET_REM_CONFIRM']).'\')){document.frm_proddetails.fpurpose.value=\'remove_favourite\';document.frm_proddetails.submit();}"><img src="'.url_site_image('remfavourite.gif',1).'" alt="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE']).'" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE']).'" /> '.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE']).'</a>';
				}
			}
			if(in_array('mod_product_reviews',$inlineSiteComponents)) // Check whether the product review module is there for current site
			{
				/*if($writereview_show==1)
				{
					$HTML_writerev = '<a href="'.url_link('writeproductreview'.$_REQUEST['product_id'].'.html',1).'" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_WRITEREVIEW']).'"><img src="'.url_site_image('det-icon_03.gif',1).'" /></a>';
				}*/
				if($readreview_show == 1 or $writereview_show == 1)
				{
					$HTML_readrev = '<a href="'.url_link('readproductreview'.$_REQUEST['product_id'].'.html',1).'" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_READREVIEW']).'"><img src="'.url_site_image('det-icon_05.gif',1).'" border="0" /> '.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_READREVIEW']).'</a>';
				}
			}
			if($email_show==1)
			{
				$HTML_email = '<a href="'.url_link('emailafriend'.$_REQUEST['product_id'].'.html',1).'" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND']).'"><img src="'.url_site_image('det-icon_07.gif',1).'" border="0" /> '.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND']).'</a>';
			}
			if($pdf_show==1) // Check whether the download pdf module is there for current site
			{ 
				$HTML_pdf = '<a href="javascript:download_pdf_stream(\''.$_SERVER['HTTP_HOST'].'\',\''.$_SERVER['REQUEST_URI'].'\')" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADPDF']).'"><img src="'.url_site_image('det-icon_09.gif',1).'" border="0" /> '.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADPDF']).'</a>';
			}
			$was_price = $cur_price = $sav_price = '';
			$price_arr = show_Price($row_prod,$price_class_arr,'prod_detail',false,5);
			if($price_arr['price_with_captions']['discounted_price'])
			{
				$was_price = $price_arr['price_with_captions']['base_price'];
				$cur_price = $price_arr['price_with_captions']['discounted_price'];
				if($price_arr['price_with_captions']['disc_percent'])
					$sav_price = $price_arr['price_with_captions']['disc_percent'];
				else
					$sav_price = $price_arr['price_with_captions']['yousave_price'];
			}
			else
			{
				$was_price = '';
				$cur_price = $price_arr['price_with_captions']['base_price'];
				$sav_price = '';
			}
			$HTML_price = '<div class="deat_price">';
			if($was_price)
				$HTML_price .= '<div class="deat_priceA">'.$was_price.'</div>';
			if($cur_price)
				$HTML_price .= '<div class="deat_priceB">'.$cur_price.'</div>';	
			
			if($sav_price)
			{
				$HTML_price .= '<div class="deat_priceC">
				 				<div class="deat_priceCleft"><span>'.$sav_price.'</span></div>
			 					</div>';
			}					
			$HTML_price .= '</div>';
			
			if ($Settings_arr['bonus_points_instock'] and $row_prod['product_bonuspoints']>0)
			{
				$HTML_bonus = '<div class="deat_bonus">
								<div class="deat_bonusA">'.$row_prod['product_bonuspoints'].'</div>
								<div class="deat_bonusB">'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTS']).'</div>
								<div class="deat_bonusC"><!--<a href="'.url_link('bonuspoint_content.html',1).'" title=""><img src="'.url_site_image('bonusmoreinfo.gif',1).'" /></a>--></div>
								</div>';
			}
			if ($Settings_arr['proddet_showwishlist'])
			{
				if($cust_id)
				{
					$wishlist_onclick = 'show_wait_button(this,\'Please Wait...\');document.frm_proddetails.fpurpose.value=\'Prod_Addwishlist\';document.frm_proddetails.submit()';
				}
				else
				{
					$wishlist_onclick = 'window.location=\'http://'.$ecom_hostname.'/wishlistcustlogin.html\'';
				}
				$HTML_wishlist = '<div title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST']).'" onclick="'.$wishlist_onclick.'">'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST']).'</div>';
			}			
			if ($row_prod['product_show_enquirelink']==1)
			{
				if($addto_cart_withajax==1)
				{
				$HTML_enquiry = '<div title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'" alt="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'" onclick="ajax_addto_cart_fromlist(\'add_prod_tocart_ajax\',\'Prod_Enquire\',\'frm_proddetails\',\''.SITE_URL.'\')">'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'</div>';	
				}
				else
				{
				$HTML_enquiry = '<div title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'" alt="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'" onclick="show_wait_button(this,\'Please Wait...\');document.frm_proddetails.fpurpose.value=\'Prod_Enquire\';document.frm_proddetails.submit();">'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'</div>';
				}
			}
			$in_combo = is_product_in_any_valid_combo($row_prod);
			?>
			<?php
			if($in_combo==1 or $row_prod['product_show_pricepromise']==1)
			{
				//$HTML_promise_buttons = '<div class="deat_pdt_offers">';
				if($in_combo==1)
				{
					$HTML_promise_buttons .= '<a href="'.url_link('showallbundle'.$row_prod['product_id'].'.html',1).'" title=""><img src="'.url_site_image('combo.gif',1).'" border="0"/></a>';
				}
				if($row_prod['product_show_pricepromise']==1)
				{
					//$HTML_promise_buttons .= '<a href="'.url_link('pricepromise'.$row_prod['product_id'].'.html',1).'" title="Price Promise"><img src="'.url_site_image('price-promise.gif',1).'" border="0"/></a>';
					$HTML_promise_buttons .= '<a href="javascript:handle_price_promise()" title="Price Promise"><img src="'.url_site_image('price-promise.gif',1).'" border="0"/></a>';
				}
				//$HTML_promise_buttons .= '</div>';
			}
			$tabs_arr			= $tabs_cont_arr	= array();
			$docroot			= SITE_URL;
			$prodid				= $_REQUEST['product_id'];
			$loading_gif		= url_site_image('loading.gif',1);
			if($row_prod['product_longdesc'])
				$tabs_content_arr[0]	= stripslashes($row_prod['product_longdesc']);
			elseif ($row_prod['product_shortdesc'])
				$tabs_content_arr[0]	= stripslashes($row_prod['product_shortdesc']);
			if (count($tabs_content_arr))
			{
				$tabs_arr 			= array (0=>'Info &amp; Care');
				$tabs_arr_onclick[0]= "show_curtab_content('ultab_0','".$docroot."',".$prodid.",'".$loading_gif."')";
			}	
								
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
					$tabs_arr_onclick[$row_tab['tab_id']]	= "show_curtab_content('ultab_".$row_tab['tab_id']."','".$docroot."',".$prodid.",'".$loading_gif."')";
				}
			}
			
			/*$label_val = $this->show_ProductLabels($prodid);
			if (trim($label_val)!='')
			{
				$tabs_arr[-1] 			= stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES']);
				$tabs_content_arr[-1]	= $label_val;
				$tabs_arr_onclick[-1]	= "show_curtab_content('ultab_-1','".$docroot."',".$prodid.",'".$loading_gif."')";
			}*/
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
					if($Settings_arr['showsizechart_in_popup']!=1 && $size_checkval == true) // if size chart is set to show in current page itself
				 	{	
						$tabs_arr[-3] 			= stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);
						$tabs_content_arr[-3]	= ' ';
						$tabs_arr_onclick[-3]	= "show_curtab_content('ultab_-3','".$docroot."',".$prodid.",'".$loading_gif."')";		 
					}
				 
			}
			if($writereview_show == 1 or $readreview_show == 1)
			{
				$sql_prodreview	= "SELECT review_id
										review_author,review_rating,review_details 
									FROM  
										 product_reviews 
									WHERE  
										sites_site_id = $ecom_siteid
										AND products_product_id  =  ".$_REQUEST['product_id']."
										AND review_status = 'APPROVED'  
										AND review_hide=0 
									LIMIT 1";
				$ret_prodreview = $db->query($sql_prodreview);
				if($db->num_rows($ret_prodreview))
				{
					for($i=1;$i<=$row_prod['product_averagerating'];$i++)
					{
						$review_stars .='<img src="'.url_site_image('star-greenA.gif',1).'"  />';
					}
					$tabs_arr[-4] 			= 'Reviews '.$review_stars;//stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);
					$tabs_content_arr[-4]	= 'reviews';
					$tabs_arr_onclick[-4]	= "show_curtab_content('ultab_-4','".$docroot."',".$prodid.",'".$loading_gif."')";
				}
			}
			if($Settings_arr['show_downloads_newrow']!=1)
			{
				$sql_attach = "SELECT attachment_id  
								FROM 
									product_attachments 
								WHERE 
									products_product_id = ".$_REQUEST['product_id']." 
									AND attachment_hide=0  
								LIMIT 1";
				$ret_attach = $db->query($sql_attach);
				if($db->num_rows($ret_attach))
				{
					$tabs_arr[-5] 			= 'Downloads';//stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);
					$tabs_content_arr[-5]	= 'downloads';
					$tabs_arr_onclick[-5]	= "show_curtab_content('ultab_-5','".$docroot."',".$prodid.",'".$loading_gif."')"; 
				}	
			}
			if($row_prod['product_flv_filename']!='')
			{
				
				$HTML_video = '	<a href="javascript:show_normal_video()"><img src="'.url_site_image('video-but.gif',1).'" border="0" /></a>
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
			$HTML_loading = '<div class="proddet_loading_outer_div" style="height:15px"><div id="proddet_loading_div" style="display:none;padding:5px 0 0 0;">
							<img src="'.url_site_image('proddet_loading.gif',1).'" alt="loading..." width="43px" height="11px;align:left">
							</div></div>';	
							
			/*$HTML_bottomblock = '
								<div class="deat_icons_outr">
								<div class="deat_icons_top"></div>
								<div class="deat_icons_bottom">
								<div class="deat_pdt_icons"> 
								'.$HTML_saleicon.'
								<div class="deat_pdt_iconsleft">'.$HTML_fav.$HTML_readrev.$HTML_email.$HTML_pdf.'</div></div>';
			if ($Settings_arr['show_bookmarks'])
			{
				$HTML_bottomblock .='
								<div class="deat_pdt_bookmark_heading">'.stripslash_normal($Captions_arr['PROD_DETAILS']['BOOKMARK_HEADING']).'</div>
								<div class="deat_pdt_bookmark">'.bookmarks(url_product($row_prod['product_id'],$row_prod['product_name'],1),htmlspecialchars($meta_arr['title']),$ecom_twitteraccountId,1).'
								</div>';
			}
			$HTML_bottomblock .='				  
								</div>   
								</div>';*/
			$show_barcode_display_handle = false;
			if ($Settings_arr['proddet_showbarcode']==1)
			{
				$show_barcode_display_handle = true;
			}					
			?>
			<script type="text/javascript" src="<?php url_head_link("images/".$ecom_hostname."/scripts/tootip.js")?>"></script>
			<script type="text/javascript" language="javascript">
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
								if(document.getElementById('ajax_changed_barcode_display'))
									document.getElementById('ajax_changed_barcode_display').value=0;
								handle_show_prod_det_bulk_disc('bulk',docroot,prod_id,loading_gif);
							}
							else if(document.getElementById('ajax_div_holder').value=='bulkdisc_holder')
							{
								if(document.getElementById('comb_img_allowed').value=='Y' ) /* Do the following only if variable combination image option is set for current product */ 
								{
									if(document.getElementById('ajax_changed_barcode_display'))
										document.getElementById('ajax_changed_barcode_display').value=0;
									handle_show_prod_det_bulk_disc('main_img',docroot,prod_id,loading_gif);
								}	
								else
								if(document.getElementById('ajax_changed_barcode_display'))
								{
									if(document.getElementById('ajax_changed_barcode_display').value!=1)
									{	
										document.getElementById('ajax_changed_barcode_display').value=1;
										handle_show_prod_det_bulk_disc('barcode_display',docroot,prod_id,loading_gif);
									}	
								}
							}
							else if(document.getElementById('ajax_div_holder').value=='mainimage_holder')
							{
								if(document.getElementById('comb_img_allowed').value=='Y') /* Do the following only if variable combination image option is set for current product */ 
								{
									if(document.getElementById('ajax_changed_barcode_display'))
										document.getElementById('ajax_changed_barcode_display').value=0;
									handle_show_prod_det_bulk_disc('more_img',docroot,prod_id,loading_gif);
								}	
							}
							else if(document.getElementById('ajax_div_holder').value=='moreimage_holder')
							{
								if(document.getElementById('ajax_changed_barcode_display'))
								{
									if(document.getElementById('ajax_changed_barcode_display').value!=1)
									{	
										document.getElementById('ajax_changed_barcode_display').value=1;
										handle_show_prod_det_bulk_disc('barcode_display',docroot,prod_id,loading_gif);
									}	
								}	
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
				}	/* Code for ajax setting ends here */
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
			</script>
			<form method="post" name="frm_proddetails" id="frm_proddetails" action="<?php url_link('manage_products.html')?>" class="frm_cls" onsubmit="return prod_detail_submit(this)">
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
			<input type="hidden" name="pricepromise_url" value="<?php url_link('pricepromise'.$row_prod['product_id'].'.html')?>" />
			<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
			<input type="hidden" name="pass_combid" value="<?php echo $_REQUEST['pass_combid']?>" />
			<input type="hidden" name="comb_img_allowed" id="comb_img_allowed" value="<?php echo $row_prod['product_variablecombocommon_image_allowed']?>" />
			<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
			<?php
			if($show_barcode_display_handle)
			{
			?>
				<input type="hidden" name="ajax_changed_barcode_display" id="ajax_changed_barcode_display" value="" />
			<?php
			}
			?>
			<input type="hidden" name="pagetype" id="pagetype" value="" />
			
			<?=$HTML_treemenu?>
            
          
            <div class="det_cont_con">
                <div class="det_imge_otr">
					<div class="det_name_otr">
               
                   <div class="det_name_top">
						<?php echo stripslash_normal($row_prod['product_name']); 
                        if($_REQUEST['for_notification']==1) // make the decision whether the instock message is to be displayed here or in div
                        {
                            $this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
                        }	
                        else // case if displaying the instock notification message here itself
                            $this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
                        ?>
                    </div>
                   <div class="det_comp_stock">
                   <div class="det_name_shop">
                   <!--  <img src="<?php /*url_site_image('barand.gif')*/?>">shop image here -->
                     <?php			 
					 $pid = $row_prod['product_id'];
					 
					 $sql_shop = "SELECT a.shopbrand_id, a.shopbrand_name, a.shopbrand_showimageofproduct 
												FROM 
													product_shopbybrand a ,product_shopbybrand_product_map b     
												WHERE 
													b.products_product_id=$pid 
													AND a.shopbrand_id = b.product_shopbybrand_shopbrand_id 
													AND a.sites_site_id = $ecom_siteid 
												ORDER BY 
													shopbrand_name";
					$ret_shop = $db->query($sql_shop);
					
					if ($db->num_rows($ret_shop))
						{
						$HTML_Shop = '';
						$HTML_Shop_Array = '';
						$pass_type = 'image_thumbpath';
						$cnts = 0;
							
							while ($row_shop = $db->fetch_array($ret_shop))
							{
								$show_noimage = false;
								$HTML_image = '';
								
								
								if ($row_shop['shopbrand_showimageofproduct']==0) // Case to check for images directly assigned to shop
									{
													
									// Calling the function to get the image to be shown
									$shopimg_arr = get_imagelist('prodshop',$row_shop['shopbrand_id'],$pass_type,0,0,1); 
									if(count($shopimg_arr))
									{
										$exclude_catid 	= $shopimg_arr[0]['image_id']; // exclude id in case of multi images for category
										$HTML_image 	= show_image(url_root_image($shopimg_arr[0][$pass_type],1),$row_shop['shopbrand_name'],$row_shop['shopbrand_name'],'','',1);
										$show_noimage 	= false;
									}
									else
										$show_noimage = true;
								}
								else // Case of check for the first available image of any of the products under this category
								{
									// Calling the function to get the id of products under current category with image assigned to it
									$cur_prodid = find_AnyProductWithImageUnderShop($row_shop['shopbrand_id']);
									if ($cur_prodid)// case if any product with image assigned to it under current category exists
									{
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
										if(count($img_arr))
										{
											$HTML_image = show_image(url_root_image($img_arr[0][$pass_type],1),$row_shop['shopbrand_name'],$row_shop['shopbrand_name'],'','',1);
											$show_noimage = false;
										}
										else// case if no products exists under current shop with image assigned to it
										$show_noimage = true;
									}
									else// case if no products exists under current shop with image assigned to it
										$show_noimage = true;
								}
								if($show_noimage==false)
								{
									$HTML_Shop = '<a href="'.url_shops($row_shop['shopbrand_id'],$row_shop['shopbrand_name'],'').'" title="'.stripslashes($row_shop['shopbrand_name']).'">'.$HTML_image.'</a>';
									
									$HTML_Shop_Array[] = $HTML_Shop; 
									
									$cnts++;
								}	
								
							
							}
							
							$random = array_rand($HTML_Shop_Array);
							echo $HTML_Shop_Array[$random];
							if(is_array($HTML_Shop_Array))
							unset($HTML_Shop_Array[$random]);
							
						}
					 ?>
					 
                   </div>
                    <div class="deat_pdt_stock"><?=$HTML_showstock?></div>
                    </div>
                   
                </div>
                    <div class="det_comp_con">
                    <!--<div class="det_comp_stock"><?/*=$HTML_showstock*/?></div>
                    <div class="det_comp_compare"> <?=$HTML_compare?></div>-->
                    
                    <div class="show_video_div">
                     <?php echo $HTML_video;?>
                     <?php
						if($row_prod['product_freedelivery']==1)
							{
								?>
								<a href="<?php echo url_link('freedelivery'.$row_prod['product_id'].'.html',1)?>" title="Free Delivery"> <img src="<?php url_site_image('freedela.gif')?>" border="0" > </a>
                                <?php
							}
					?>	
                    </div>
                    
                    </div>
                    <div id="det_imge">
                    <?php
                    if ($Settings_arr['proddet_showbarcode']==1)
                    {
                    ?>
                    <div class="barcode_holder" id="barcode_holder">
                     <?php
                     $this->show_product_barcode($_REQUEST['product_id']);
                     ?>
                     </div>
                     <?php
                    }
                    ?>
                     <div id="mainimage_holder">
                     <?php 
                    //print_r($row_prod);
                     $this->Show_Image_Normal($row_prod);?>
                     </div>
                     
                    </div>
                <?=$HTML_loading?>
                    <div class="det_sub_imge">
                    	<div class="det_sub_imge_hdr"><img src="<?php url_site_image('zoom.gif')?>" border="0"></div>
                            <div class="det_sub_imges">
                                <div id="moreimage_holder">
                                <?php
                                $return_arr = $this->Show_Image_Normal($row_prod,true);
                                // Showing additional images
                                $this->show_more_images($row_prod,$return_arr['exclude_tabid'],$return_arr['exclude_prodid']);
                                ?>
                                </div>
                            </div>
                    </div>
                </div>
            <div class="det_det_otr">
            <div class="det_con_top"></div>
                <div class="det_con_mid">
					<div class="det_price_otr">
                        <div class="det_price_top"></div>
                        <div class="det_price_mid">
                                               <div id="price_holder">
                                                <?=$HTML_price?>
                                                </div>
                                                
                                                <div class="deat_right">
												<?php if($row_prod['product_averagerating']>0)
												{?>
                                                    <div class="deat_right_rate">
													<?php 
														for($i=1;$i<=$row_prod['product_averagerating'];$i++)
														{
															?>
                                                            <img src="<?php url_site_image('star-det.gif')?>">
															<?php
														}
													?>
                                                    </div>
												<?php 
											   }
											   ?>	
                                                <div class="deat_right_bonus"><?=$HTML_bonus?></div>
                                               </div>
                                               
                        </div>
                        <div class="det_price_bottom"></div>
              </div>
                <?php 
                    $this->show_ProductVariables($row_prod,'',$sizechart_heading);
                ?> 
                    <div class="deat_pdt_buy_outr">
                         <div class="deat_pdt_buy_right">
                            <? $this->show_buttons($row_prod);?>
                         </div>
                         <div class="deat_pdt_buy_left">
                            <div class="deat_pdt_buyA"><?=$HTML_enquiry?></div>
                            <div class="deat_pdt_buyA"><?=$HTML_wishlist?>
                        </div>
                        </div>
                    </div>
                    <? $this->show_BulkDiscounts($row_prod,array());?>
                </div>
                <div class="det_con_bottom">
                </div>
                    
                    <?php
					if($this->show_ProductLabels($prodid))
					{
					?>
					<div class="det_label_otr">
                    <div class="det_label_top"></div>
                    <div class="det_label_mid">
                    <?php
                    echo $label_val = $this->show_ProductLabels($prodid);
                    ?>
                    </div>
                    <div class="det_label_bottom"></div> </div>
                    <?php
					}				
					
					if (count($tabs_arr))
					{		
						$curtab 		= ($_REQUEST['prod_curtab'])?$_REQUEST['prod_curtab']:0;
					?>	
						<div class="deat_tab_outr">
						<div class="deat_tab_con">
						<ul class="deat_protab">
					<?
						foreach($tabs_arr as $k_tabid=>$v_tabtitle)
						{
							$sel = ($k_tabid == $curtab)?'pro_seltableft':'protableft';
						?>		
							<li ><div class="<?php echo $sel?>" onclick="<?php echo $tabs_arr_onclick[$k_tabid]?>" id="tabhead_<?php echo $k_tabid?>"><span><?php echo $v_tabtitle?></span></div></li>
						<?php
						}
						?>
						</ul>
						</div>
						<?php
						$sr_array  = array('rgb(0, 0, 0)','#000000');
						$rep_array = array('rgb(255,255,255)','#ffffff'); 
						$sr_arr = array('<font size="1">','font-size: 10px;','<FONT size="1">','FONT-SIZE: 10px;','FONT-SIZE: 10px','<FONT face=StoneSans-Semibold size=1>','FONT size=1','<FONT face=tahoma,arial,sans-serif color=#000000 size=1>','<h6>','</h6>','<h2>','<H6>','</H6>','<H2>','<p>','<P>','margin-bottom: 110px','MARGIN-BOTTOM: 110px');
						$rp_arr = array('<font size="2">','font-size: 12px;','<font size="2">','font-size: 12px;','font-size: 12px;','<font face="StoneSans-Semibold" size="2">','font size=2','<font face=tahoma,arial,sans-serif color=#000000 size=2>','','','<h2 style="display:block; width:100%;padding-top:10px">','','','<h2 style="display:block; width:100%;float:left;padding-top:10px">','<p><br/>','<p><br/>','','');
						foreach ($tabs_content_arr as $k_tabcontid=>$v_tabcontent)
						{
							if ($k_tabcontid==0)
								$display = "style=\"display:\"";
							else
								$display = "style=\"display:none\"";
								
							//$disp_content = str_replace($sr_arr,$rp_arr,$v_tabcontent);
							$disp_content = str_replace($sr_array,$rep_array,$v_tabcontent);
						?>
							<div id='ultab_<?php echo $k_tabcontid?>' class="deat_tab_conts" <?=$display?>><? echo $disp_content?></div>
						<?php
						}
						?>	
							<div class="deat_tab_bottom"></div>
						</div>
					<?php
					}
					?>
					<div class="outer_for_all" id="outer_for_all">
                      <div class="outer_for_all_mid" >
                     
                    <div class="deat_pdt_bookmark_otr">
                    <?php
                    if ($Settings_arr['show_bookmarks'])
                    {
                    echo $HTML_bookmarks .='
                                    <div class="deat_pdt_bookmark_heading">'.stripslash_normal($Captions_arr['PROD_DETAILS']['BOOKMARK_HEADING']).'</div>
                                    <div class="deat_pdt_bookmark">'.bookmarks(url_product($row_prod['product_id'],$row_prod['product_name'],1),htmlspecialchars($meta_arr['title']),$ecom_twitteraccountId,1).'
                                    </div>';
                    }
                    ?>
                    </div>
                <?php echo $HTML_saleicon; ?>
                  <?php echo $HTML_newicon; ?>
                    <div class="det_link_otr">
                    <div class="det_link_top"></div>
                        <div class="det_link_bottom">
                        <div class="deat_pdt_iconsa"><?php echo $HTML_email; ?></div>
                        <div class="deat_pdt_iconsb"><?php echo $HTML_pdf; ?></div>
                        <div class="deat_pdt_iconsc"> <?php echo $HTML_readrev; ?></div>
                        <div class="deat_pdt_iconsd"><?php echo $HTML_fav; ?></div> 
                        </div>
                    </div>
                    <div class="det_comp_otr">
                    <div class="det_comp_top"></div>
                        <div class="det_comp_mid">
                        <?php 
                        echo $HTML_promise_buttons; 
                        ?> 
                        <!--  <img src="<?php/* url_site_image('combo.gif')*/?>">-->
                        </div>
                    <div class="det_comp_bottom"></div>
                    </div>
					</div>
					</div>
					<?php				
					
			?>		
			
            </div>
			</div>
			<?
			$this->show_product_downloads($row_prod['product_id']);
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
			<form method="post" action="">
			<input type="hidden" />
			</form>
			<?php
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
			//print_r($prodimg_arr);
			if(count($prodimg_arr))
			{ 
				$exclude_prodid = $prodimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
				if($just_return_id!=true)
				{
				?>
				<a href="<?php url_root_image($prodimg_arr[0]['image_extralargepath'])?>" rel='lightbox[gallery]' title="<?=$row_prod['product_name']?>">
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

function show_more_images($row_prod,$exclude_tabid,$exclude_prodid)
{
	global $db,$ecom_hostname,$ecom_themename;
	$show_normalimage = false;
	$prodimg_arr		= array();
	$pass_type 	= 'image_thumbpath';
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
		<div class="deat_pdt_thumbimg">
		<div class="det_link_thumbimg_con">
		<div class="det_thumbimg_nav"><a href="#null" onmouseover="scrollDivRight('containerB')" onmouseout="stopMe()"><img src="<?php url_site_image('shlf-arw-l.gif')?>"></a></div>
		<div id="containerB" class="det_thumbimg_inner">

			<div id="scroller_thumb">
			<?php
			$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
			foreach ($prodimg_arr as $k=>$v)
			{ 
				$title = ($v['image_title'])?stripslash_normal($v['image_title']):$row_prod['product_name'];
			?>
				<div class="det_thumbimg_pdt">
					<div class="det_thumbimg_image">
					<a href="<?php url_root_image($v['image_extralargepath'])?>" rel='lightbox[gallery]' title="<?=$title?>">
					<?php
						 show_image(url_root_image($v['image_thumbpath'],1),$title,$title,'preview');
					?>
					</a>
					</div>
				</div>
			<?php
			}
			?>	
			
            </div>
		</div>
		<div class="det_thumbimg_nav"> <a href="#null" onmouseover="scrollDivLeft('containerB',<?php echo (count($prodimg_arr)*150)?>)" onmouseout="stopMe()"><img src="<?php url_site_image('shlf-arw-r.gif')?>"></a></div>
		</div>
		</div>	
	<?php
	}
}
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
	if($Settings_arr['showsizechart_in_popup']==1 && $sizechart_heading['size_Availvalues']==true) // If size chart is set to show in a pop up window
	{
		if(is_array($sizechart_heading))
		{
	?>
		 <div class="deat_pdt_sizechart"><div class="sizechartleft"><div><a href="javascript:showsizechartPopup('<?php echo $row_prod['product_id']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')" title="<?php echo stripslash_normal($sizechartmain_title)?>">Size Chart<?php ?></a></div></div></div>
	<?php	
		}
	}
	if($sizechart_heading['size_Availvalues']==false && $row_prod['product_commonsizechart_link']!='')
	{
	?>
			 <div class="deat_pdt_sizechart"><div class="sizechartleft"><div><a href="<?=$row_prod['product_commonsizechart_link']?>" title="SizeChart" 	target="<?=$row_prod['produt_common_sizechart_target']?>">Size Chart<?php ?></a></div></div></div>
	 <?
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
  		<div class="deat_pdt_varable">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="deat_pdt_varable_table">
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
					$var_exists = true;
				?>
                  <tr>
					<td align="right" valign="middle" class="varable_table_left"  colspan="2"><?php echo stripslash_normal($row_var['var_name'])?> : </td>
                    </tr>	
				  <tr>
					<td align="left" valign="middle" class="varable_table_right" colspan="2">
                        <?php

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
							if ($Settings_arr['proddet_showbarcode']==1)
							{	
								$onchange_function      = $onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"barcode_display\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\")' ";
								$onclick_var            = "barcode_display";
							}
							else
							{
								$onchange_function      = '';
								$onclick_var       		= '';
							}	
						}
						if ($row_var['var_value_exists']==1)
						{
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
										// Calling function to process the variables and get back the required values	
										$ret_arr = handle_variable_color_section($row_vals,$first_val,$color_type);	
										
										$show_value		= $ret_arr['show_value'];
										$clr_val 		= $ret_arr['clr_val'];
										$normal_cls 	= $ret_arr['normal_cls'];
										$special_cls 	= $ret_arr['special_cls'];
										
										$normal_cls_sz 		= "size_var_div";
										$special_cls_sz 	= "size_var_div_sel";
										$normal_cls_clrimg 	= "colorimg_div";
										$special_cls_clrimg	= "colorimg_div_sel";
										$normal_cls_clr 	= "color_div";
										$special_cls_clr	= "color_div_sel";
										$varvaldivid 		= "valdiv_var_".$row_var['var_id']."_".$row_vals['var_value_id'];
										$onclick_function   = " onclick ='var_onclick(\"".$onclick_var."\",\"var_".$row_var['var_id']."\",".$row_vals['var_value_id'].",\"".$normal_cls_clr."\",\"".$special_cls_clr."\",\"".$normal_cls_clrimg."\",\"".$special_cls_clrimg."\",\"".$normal_cls_sz."\",\"".$special_cls_sz."\",\"".$docroot."\",\"".$prodid."\",\"".$loading_gif."\")' ";
									?>
									 <div id='<?php echo $varvaldivid?>' class="<?php echo ($first_one==1)?$special_cls:$normal_cls?>" <?php echo $clr_val. ' '.$onclick_function?> title="<?php echo stripslashes($row_vals['var_value'])?>" <?php if(!$show_value) {?>onmouseover="tooltip.show('<?php echo stripslash_normal($row_vals['var_value'])?>');" onmouseout="tooltip.hide();" <?php }?>>
									 
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
				$var_exists = true;
			?>
				  <tr>
					<td align="right" valign="top" class="varable_table_left"><?php echo stripslash_normal($row_msg['message_title'])?> : </td>
					<td align="left" valign="top" class="varable_table_left">
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
	}
	
	return $var_exists;
}
function show_buttons($row_prod)
{
	global $Captions_arr,$showqty,$Settings_arr,$ecom_siteid,$db,$glob_qty_displayed;
	$addto_cart_withajax =  $Settings_arr['enable_ajax_in_site'];

	$cust_id 	= get_session_var("ecom_login_customer");
	//$showqty	= $Settings_arr['show_qty_box'];// show the qty box
	$showqty = 1;
	$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslash_normal($row_prod['product_det_qty_caption']):stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY']);
	// Get the caption to be show in the button
	$caption_key = show_addtocart($row_prod,array(0),'',true);
	if ($caption_key!='') // Check whether the buynow or preorder button is to be displayed
	{
	?>	
		<div class="deat_pdt_buyB">
		<div class="deat_pdt_buyBinner">
	<?php	
	if($showqty==1)// this decision is made in the main shop settings
	{
		if($row_prod['product_det_qty_type']=='NOR')
		{
?>
			<div class="buyBinner_qty"><?=$cur_qty_caption?></div>
			<div class='buyBinner_txt'><input type="text" class="det_qty_txt" name="qty"  value="<?php echo ($_REQUEST['qty'])?$_REQUEST['qty']:1?>" maxlength="2" /></div>
<?php
		}
		elseif($row_prod['product_det_qty_type']=='DROP')
		{
			$dropdown_values = explode(',',stripslash_normal($row_prod['product_det_qty_drop_values']));
			if (count($dropdown_values) and stripslash_normal($row_prod['product_det_qty_drop_values'])!='')
			{
			?>
				<div class='buyBinner_txt'>
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
?>
<div class="buyBinner_link"><a href="javascript:void(0);" class="det_buy_link" onclick="ajax_addto_cart_fromlist('add_prod_tocart_ajax','Prod_Addcart','frm_proddetails','<?php echo SITE_URL?>')"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS'][$caption_key]);?></a></div>
<?php
	}
	else
	{
?>
<div class="buyBinner_link"><a href="#" class="det_buy_link" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addcart';document.frm_proddetails.submit();"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS'][$caption_key]);?></a></div>
<?php
	}
?>
</div>
</div>
<?php
	}	
	return true;
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
					$ret_val = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="keyfeature" id="proddet_var_table">';
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
										$clss = ($i%2==0)?'keyfeatureB':'keyfeatureA';
										$clssA = ($i%2==0)?'keyfeatureBB':'keyfeatureAA';
										$ret_val .= '
													<tr>
														<td align="left" valign="middle" class="'.$clss.'">'.$v[$i]['name'].'</td>
														<td align="left" valign="middle" class="'.$clssA.'">:&nbsp;'.$v[$i]['val'].'</td>
													</tr>';	
									}
								}
							}
						}
					}	
					$ret_val .= '</table>';	
				}
			}
		}	
	}
	if($display_ok==false)
		$ret_val = '';
	return $ret_val ;	
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
		default:
			$width_one_set 	= 300;
			$min_number_req	= 4;
			$min_width_req 	= $width_one_set * $min_number_req;
			$total_cnt		= $db->num_rows($ret_prod);
			$calc_width		= $total_cnt * $width_one_set;
			if($calc_width < $min_width_req)
				$div_width = $min_width_req;
			else
				$div_width = $calc_width; 
?>
 <div class="releated_pdt_con">
            <div class="shlf_a_top">  
               <div class="shlf_a_hdr">
                   <table border="0" align="center" class="shlf_a_hdrtable" cellpadding="0" cellspacing="0">
                      <tbody><tr>
                        <td class="shlf_a_hdrld"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_PEOPLE_INTERESTED']);?></td>
                        <td class="shlf_a_hdrrd">&nbsp;</td>
                      </tr>
                      </tbody>
                  </table>
               </div>
            </div>
		<div class="shlf_a_bottom">
            <div class="shlf_a_outer">
                <div class="shlf_a_nav"><a href="#null" onmouseover="scrollDivRight('containerA')" onmouseout="stopMe()"><img src="<?php url_site_image('shlf-arw-l.gif')?>"></a></div>
                <div id="containerA" class="shlf_thumb_outerA">
               <div id="scroller" style="width:<?php echo $div_width?>px">
                <?php
                $cnts = $db->num_rows($ret_prod);
				while($row_prod = $db->fetch_array($ret_prod))
				{
				$prodcur_arr[] = $row_prod;
				$HTML_title = $HTML_image = $HTML_desc = '';
				$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
				$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
				
				$HTML_title = '<div class="shlf_a_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
			
			
				$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
				// Calling the function to get the image to be shown
				$pass_type ='image_thumbcategorypath';
				$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
				if(count($img_arr))
				{
					$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
				}
				else
				{
					// calling the function to get the default image
					$no_img = get_noimage('prod',$pass_type); 
					if ($no_img)
					{
						$HTML_image .= show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
					}       
				}       
				$HTML_image .= '</a>';
			
				$price_arr = show_Price($row_prod,$price_class_arr,'prod_detail',false,5);
									
				if($price_arr['price_with_captions']['discounted_price'])
				{
					$was_price = $price_arr['price_with_captions']['base_price'];
					$cur_price = $price_arr['price_with_captions']['discounted_price'];
					if($price_arr['price_with_captions']['disc_percent'])
						$sav_price = $price_arr['price_with_captions']['disc_percent'];
					else
						$sav_price = $price_arr['price_with_captions']['yousave_price'];
				}
				else
				{
					$was_price = '';
					$cur_price = $price_arr['price_with_captions']['base_price'];
					$sav_price = '';
				}
	
			
			?>
			
			
			<div class="shlf_a_pdt">
			  <?php
			/*if($row_prod['product_saleicon_show']==1)
			{
				$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
				//if($desc!='')
				{
					  $HTML_sale = '<div class="pdt_list_sale"></div>';
				}
			}
			if($row_prod['product_newicon_show']==1)
			{
				$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
					  $HTML_new = '<div class="pdt_list_new"></div>';
			}
			echo $HTML_new ;
			echo $HTML_sale;*/
			?>
			<div class="shlf_a_pdt_top"></div>
		   
				<div class="shlf_a_pdt_mid">
				  <?=$HTML_title;?>
				<div class="shlf_a_pdt_r">
					<?php echo $HTML_image;?>
					</div>
					<div class="shlf_a_pdt_l">
				   
					<? /*=$HTML_desc*/ ?>
							<div class="shlf_a_pdt_buy_otr">
							<div class="shlf_a_pdt_price"><?=$cur_price?></div>
							
							<div class="shlf_a_pdt_buy"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>">BUY NOW</a></div>
								
							</div>
				   </div>
				   
					<div class="shlf_a_pdt_more"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>" class="">More Info</a></div>
				  
				</div>
			  
			</div>
			<?
			
			
			
			}
				
	            ?>
                </div>
                </div>
                <div class="shlf_a_nav"> <a href="#null" onmouseover="scrollDivLeft('containerA','<?php echo $div_width?>')" onmouseout="stopMe()"><img src="<?php url_site_image('shlf-arw-r.gif')?>" /></a></div>
             </div>
        </div>
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
	$HTML_treemenu = ' <div class="tree_menu_con">
						<div class="tree_menu_top"></div>
						<div class="tree_menu_mid">
						<div class="tree_menu_content">
						<ul class="tree_menu">
						<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a> </li>
						<li>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_HEAD']).'</li>
						</ul>
						</div>
						</div>
						<div class="tree_menu_bottom"></div>
						</div>';
	$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
	$msg = stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_MAX_COMP_PROD']);
	$msg = str_replace('[prodno]',$Settings_arr['no_of_products_to_compare'],$msg);
	if($msg!='')
	{
		   $HTML_comptitle ='<div class="normal_shlfB_desc_outr">'.$msg.'</div>';
	}
	$HTML_maindesc = ' 	<div class="compare_main_div">
						<div class="compare_back_button">
							<div class="cart_top_links"><div class="cart_shop_cont"><div>
							<input type="button" name="prodet_backprod" value="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BACK_TO_DETAIL']).'" class="buttonred_cart" onclick="window.location=\''.url_product($_REQUEST['product_id'],'',1).'\'"/>
							</div>
							</div>
							</div>
						</div>
						<div class="compare_gobutton">
							<div class="cart_top_links"><div class="cart_shop_cont"><div>
						 	<input type="button" name="prodet_comparebutton" value="'.stripslash_normal($Captions_arr['PROD_DETAILS']['COMPARE_PRODUCTS']).'" class="buttonred_cart" onclick="handle_proddet_compare()"/>
						 	</div>
						 	</div>
						 	</div>
						</div>
						</div>';
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
	<?=$HTML_treemenu?>
		<div class="normal_shlf_mid_con">
		<div class="normal_shlf_mid_top"></div>
		<div class="normal_shlf_mid_mid">
		<? 
		echo $HTML_comptitle;
		echo $HTML_maindesc;
		echo $HTML_paging;
		$max_col = 5;
		$cur_col = 0;
		$prodcur_arr = array();
		while($row_prod = $db->fetch_array($ret_prod))
		{
		
			$prodcur_arr[] = $row_prod;
			$compare_checked = '';
			if(is_array($_SESSION['compare_products']))
			{
				if(in_array($row_prod['product_id'],$_SESSION['compare_products']))
				{
					$compare_checked = 'checked="checkeed"';
				}
			}
			$HTML_title = $HTML_image = $HTML_desc = '';
			$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
			$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
				$HTML_title = '<div class="normal_shlfB_pdt_name"><input type="checkbox" name="chkproddet_comp_'.$row_prod['product_id'].'" id="chkproddet_comp_'.$row_prod['product_id'].'" value="'.$row_prod['product_id'].'" '.$compare_checked.'/><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
				$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
				// Calling the function to get the image to be shown
				$pass_type ='image_thumbpath';
				$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
				if(count($img_arr))
				{
					$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
				}
				else
				{
					// calling the function to get the default image
					$no_img = get_noimage('prod',$pass_type); 
					if ($no_img)
					{
						$HTML_image .= show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
					}       
				}       
				$HTML_image .= '</a>';
				$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
				$price_class_arr['class_type']          = 'div';
				$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
				$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
				$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
				$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
				$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
			            if($cur_col==0)
						{
							echo  '<div class="outer_shlfB_container">';
						}	
						if($cur_col<$max_col-1)
						{
						  $outer_class = 'normal_shlfB_pdt_outr';
						}	
						else
						{
							$outer_class = 'normal_shlfB_pdt_outr_right';
						}
		?>
			<div class="<?=$outer_class?>">
			<div class="normal_shlfB_pdt_mid">
			<?=$HTML_title;?>
			<div class="normal_shlfB_pdt_img_otr">
			<div class="normal_shlfB_pdt_img"><?=$HTML_image?></div>
			</div>
			<div class="normal_shlfB_pdt_price"><?=$HTML_price?></div>
			</div>
			</div> 
		<?
			$cur_col++;
			if($cur_col>=$max_col)
			{
				$cur_col =0;
				echo "</div>";
			}
		}
		if($cur_col<$max_col)
		{
			if($cur_col!=0)
			{ 
				echo "</div>";
			} 
		}
		echo $HTML_paging;
		echo $HTML_showall;
		?>
		<div class="normal_shlfA_mid_bottom"></div> 
		</div>   
		</div>	
<?php
}
// ** Function to display the variables. This is written as function to avoid code repetation in various if conditions

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
	?>	                    <div id="bulkdisc_holder">

		<div class="deat_bulk_outr">
		<div class="deat_bulk_top"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_DISC_HEAD'])?></div>
		<div class="deat_bulk_bottom">
		<div class="deat_bulk_conts">
		<?php
			for($i=0;$i<count($bulkdisc_details['qty']);$i++)
			{
				echo '<span>'.$bulkdisc_details['qty'][$i].' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_FOR']).' ';
				//echo print_price($bulkdisc_details['price'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);
				echo product_BulkDiscount_Build_Price($bulkdisc_details['price'][$i],$bulkdisc_details['price_without'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);
				echo '</span>';
			}
		?>
		</div>
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
				<div id="div_defaultFlash_outer" class="flashvideo_outer"></div>	
				<div  class="div_alert" id="instockmsg_div">
				<div align="right" class="instockmsg_span" ><a href="javascript: hide_instockmsg_div()"><img src="<?php url_site_image('close.gif')?>" border="0" /></a></div>
					<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK'])?>.
				 <br />
				<span class="instockmsg_out_stock">
				<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK_EMAIL'])?></span>
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
				<div  class="alert_inner"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK'])?>.
				<br />
				<span style="font-size:12px;font-weight:normal;color:#000000;"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK_EMAIL'])?><br />

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
function show_product_downloads($prod_id)
{
	global $db,$ecom_siteid,$Captions_arr,$ecom_hostname,$Settings_arr;
	
	$sql_attach = "SELECT * 
						FROM 
							product_attachments 
						WHERE 
							products_product_id = ".$prod_id." 
							AND attachment_hide=0  
						ORDER BY 
							attachment_order";
							
	$ret_attach = $db->query($sql_attach);
	if ($db->num_rows($ret_attach) and $Settings_arr['show_downloads_newrow']==1)
	{
?>
		<div class="deat_conts_outr">
		<div class="deat_conts_con">
		<div class="deat_conts_hdr"><span><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADS'];?></span></div>
		</div>
		<div class="deat_conts_conts">
		<ul class="donloads_ul">
		<?php
		$cnts = 1;
		while ($row_attach = $db->fetch_array($ret_attach))
		{
		?>
			<li><div class="donloads_no"><?php echo $cnts?></div>
			<div class="donloadsleft"><span><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_attach['attachment_id']?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo stripslashes($row_attach['attachment_title'])?></a></span></div>
			</li>
		<?php
        $cnts++;
		}
		?>
		</ul>
		</div>
		<div class="deat_conts_bottom"></div>
		</div>
	<?php
	}
}
function show_product_barcode($product_id,$var_arr=array())
{
	global $db,$ecom_siteid,$Captions_arr;
	$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
	$barcode = '';
	$sql_prod = "SELECT product_id,product_variablestock_allowed,product_variables_exists,
					product_variablecomboprice_allowed,product_variablecombocommon_image_allowed,
					product_barcode  
						FROM 
							products 
						WHERE 
							sites_site_id = $ecom_siteid 
							AND product_id = $product_id 
						LIMIT 
							1";
	$ret_prod = $db->query($sql_prod);
	if ($db->num_rows($ret_prod))
	{
		$row_prod = $db->fetch_array($ret_prod);
	}
	$variable_exists = false;
	// Check whether there exists atleast one variable with values
	$sql_check = "SELECT var_id 
					FROM 
						product_variables 
					WHERE 
						products_product_id = $product_id 
						AND var_value_exists=1 
					LIMIT 
						1";
	$ret_check = $db->query($sql_check);
	if($db->num_rows($ret_check))
	{
		$variable_exists = true;
	}
	if (count($var_arr)==0 and $variable_exists) // case if variable exists and variable details not passed
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
	if($row_prod['product_variablestock_allowed']=='Y' or $row_prod['product_variablecomboprice_allowed']=='Y' or $row_prod['product_variablecombocommon_image_allowed']=='Y')
	{
		// Section to show the bulk discount details
		$comb_arr = get_combination_id($row_prod['product_id'],$var_arr);
		$sql_code = "SELECT comb_barcode 
						FROM 
							product_variable_combination_stock 
						WHERE 
							comb_id=".$comb_arr['combid']." 
						LIMIT 
							1";
		$ret_code = $db->query($sql_code);
		if($db->num_rows($ret_code))
		{
			$row_code = $db->fetch_array($ret_code);
			$barcode = stripslashes($row_code['comb_barcode']);
		}
	}
	elseif($variable_exists)
	{
		// Section to show the bulk discount details
		$comb_arr = get_combination_id_ajax($row_prod['product_id'],$var_arr);
		$sql_code = "SELECT comb_barcode 
						FROM 
							product_variable_combination_stock 
						WHERE 
							comb_id=".$comb_arr['combid']." 
						LIMIT 
							1";
		$ret_code = $db->query($sql_code);
		if($db->num_rows($ret_code))
		{
			$row_code = $db->fetch_array($ret_code);
			$barcode = stripslashes($row_code['comb_barcode']);
		}
	}	
	else
	{
		$barcode = stripslashes($row_prod['product_barcode']);	
	}
	if($barcode!='')
	{
		echo '
				<div class="barcode_det">
				<span class="barcode_caption">'.$Captions_arr['PROD_DETAILS']['BARCODE'].'</span>
				<span class="barcode_code">'.$barcode.'</span>
				</div>
			';
	}		
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
													$row_prod['cur_var_value_id'] 		= $value_id;
													echo show_Price($row_prod,$price_class_arr,'prod_detail')." ";
													/*
												?>
													<ul class="prodeuladprice">
														<li><?php echo Show_Variable_Additional_Price($row_prod,$row_vals['var_addprice'],$vardisp_type);?></li>
													</ul>	
													*/
													?> 												
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