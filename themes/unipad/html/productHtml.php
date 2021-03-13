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
			
								
			
	  			$HTML_treemenu = '<div class="treemenu">'.generate_tree_menu(-1,$_REQUEST['product_id'],'<span>&raquo;</span>','','').'</div>';

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
			//print_r($price_arr);
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
			}/*
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
			*/ 
			
			
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
				$HTML_enquiry = '<div title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'" alt="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'" onclick="show_wait_button(this,\'Please Wait...\');document.frm_proddetails.fpurpose.value=\'Prod_Enquire\';document.frm_proddetails.submit();">'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']).'</div>';			
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
				$tabs_arr 			= array (0=>'Description');
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
			//Faq loading section for product details
			$sql_faq = "SELECT faq_id,faq_question,faq_answer 
						FROM 
							faq 
						WHERE 
							sites_site_id = $ecom_siteid 
						AND 
							faq_hide=0
						ORDER BY 
							faq_sortorder"; 
						
		   $ret_faq = $db->query($sql_faq);
				if($db->num_rows($ret_faq))
				{
					$tabs_arr[-6] 			= 'FAQ';//stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_SIZECHART_HEAD']);
					
					while ($row_faq = $db->fetch_array($ret_faq))
					{
						$faq_arr[$row_faq['faq_id']] = array 	
															(
																'faq_question'=>stripslash_normal($row_faq['faq_question']),
																'faq_answer'=>stripslashes($row_faq['faq_answer'])
															);
					}
					?>
					<script type="text/javascript">
						function Show_faq(vid)
						{
						    if(document.getElementById("faqcontent_"+vid).style.display =="none")
						    document.getElementById("faqcontent_"+vid).style.display = "";
						    else
						    document.getElementById("faqcontent_"+vid).style.display = "none";
						}
						
						</script>
			
		<?php /*$faq_content_str .= "<div class=\"inner_contnt_faqP\" >
        <div class=\"inner_contnt_topP\"></div>
		<div class=\"inner_contnt_middleP\">";
		$faq_content_str1 = "";		 
			// Showing the questions and answers
			foreach ($faq_arr as $k=>$v)
			{
		
			$faq_content_str1 .="<h3 class=\"faqqstP\" onclick=\"Show_faq('".$k."')\">Q:&nbsp;".$v['faq_question']."</h3>
			<div id=\"faqcontent_".$k."\" class=\"faqcontentP\" style=\"display:none\">";
				$sr_array  = array('rgb(0, 0, 0)','#000000');
				$rep_array = array('rgb(255,255,255)','#ffffff'); 
				$ans_desc = $v['faq_answer'];
			$faq_content_str1 .=$ans_desc;
			$faq_content_str1 .="</div>";		
			}
	$faq_content_str .= $faq_content_str1;
		$faq_content_str .="</div>";
		$faq_content_str .="<div class=\"inner_contnt_bottomP\"></div>
		</div>";
		
					$tabs_content_arr[-6]	= $faq_content_str;
					$tabs_arr_onclick[-6]	= "show_curtab_content('ultab_-6','".$docroot."',".$prodid.",'".$loading_gif."')"; 
				*/
				$faq_content_str1="<div style='width:100%;border:solid 0px #000'>";
			foreach ($faq_arr as $k=>$v)
			{
				$faq_content_str1 .= "<div style='cursor:pointer;border-bottom: solid 2px #FFFFFF;padding:3px;background-color:#CCCCCC;color:#000;display:block;width:620px;font-weight:bold' onclick='Show_faq(".$k.")'>Q. ".$v['faq_question']."</div>";
				$faq_content_str1 .= "<div id='faqcontent_".$k."' style='padding:8px;display:block;width:620px;font-weight:normal;display:none'>".$v['faq_answer']."</div>";
			}
			$faq_content_str1.="</div>";
			$tabs_content_arr[-6]	= $faq_content_str1;
			$tabs_arr_onclick[-6]	= "show_curtab_content('ultab_-6','".$docroot."',".$prodid.",'".$loading_gif."')"; 
				
				
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
						
						/*if(document.getElementById('ajax_div_holder').value=='barcode_holder')
						{
							if(document.getElementById('ajax_changed_barcode_display'))
							{
								if(document.getElementById('ajax_changed_barcode_display').value!=1)
								{	
									document.getElementById('ajax_changed_barcode_display').value=1;
									handle_show_prod_det_bulk_disc('barcode_display',docroot,prod_id,loading_gif);
								}	
							}
						}*/		
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
			</script>
			 <script type="text/javascript" language="javascript">
	 function req_callback(frm)
	 {
	   document.frm_callbackproddetails.submit();
	 }
	 </script>
			<?php
			  $callback_url = "http://www.unipad.co.uk/request-a-callback-pg50389.html";
			?>
			<form method="post" name="frm_callbackproddetails" id="frm_callbackproddetails" action="<?php echo $callback_url?>" class="frm_cls" onSubmit="return prod_detail_submit(this)">
			<input type="hidden" name="product_id" value="<?php echo $_REQUEST['product_id']?>" />
			</form>
			<form method="post" name="frm_proddetails" id="frm_proddetails" action="<?php url_link('manage_products.html')?>" class="frm_cls" onSubmit="return prod_detail_submit(this)">
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
            
          <div class="detailwrap">  
<div class="pricewrap">
<h1>
						<?php echo stripslash_normal($row_prod['product_name']); 
                        if($_REQUEST['for_notification']==1) // make the decision whether the instock message is to be displayed here or in div
                        {
                            $this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
                        }	
                        else // case if displaying the instock notification message here itself
                            $this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
                        ?>
                    <strong> <?php 
					if($was_price)
					$HTML_price .= ''.$was_price.'';
					if($cur_price)
					$HTML_price .= ''.$cur_price.'';	
					echo $HTML_price;
				?> </strong>
                    </h1>
                    <?php
                    $sql_pp = "SELECT product_actualstock FROM products WHERE product_id =".$row_prod['product_id']." LIMIT 1";
			$ret_pp = $db->query($sql_pp);
			$row_pp = $db->fetch_array($ret_pp);
								if($row_pp['product_actualstock']>0)
									$availability_msg = '<span class="green_available_det">'.$Captions_arr['COMMON']['PRODDET_AVAIL_1_YEAR'].'</span>';
								else
									$availability_msg = '<span class="red_available_det">'.$Captions_arr['COMMON']['PRODDET_AVAIL_2_YEAR'].'</span>';
								echo $availability_msg;	
								?>
                    </div>

<div class="detail_left">

<div class="detail_main_img_wrap">
<?php 
                    //print_r($row_prod);
                    $ret_arr = $this->Show_Image_Normal($row_prod);
				      $pass_type = get_default_imagetype('proddet');
				      $exclude_tabid			= $ret_arr['exclude_tabid'];
				$exclude_prodid			= $ret_arr['exclude_prodid'];
                     global $load_mobile_theme_arr;
			if($load_mobile_theme_arr[0]==1)
			{
				$zoomtype = '';
			}
			else
			{
				$zoomtype = '';//zoomType: "lens",';
			}
			
			if($row_pp['product_actualstock']==0)
			{
				echo $HTML_image .= '<div class="nowlet_cls_inner_det"><img src="'.url_site_image('nowlet_big.png',1).'" alt="Now Let"></div>';
			}
				
			?>
</div>

 <?php
                                ?>
                                <div id="gallery_01">
                                <?php
                                // Showing additional images
                                $this->show_more_images($row_prod,$exclude_tabid,$exclude_prodid);
                                 ?>
                                 </div>
                                 
                                 <?php
                                 /*
                                 <div class="prod_detimgA"><img src=<?php echo url_site_image('prod_det.jpeg',1) ?> alt="discount"/></div>
                                 */
                                 ?> 
                                 <script type="text/javascript" language="javascript">
            jQuery.noConflict();
            var $j = jQuery; 
              $j(document).ready(function () {
				 $j("#zoom_03").elevateZoom({zoomWindowPosition: 1,responsive:true,preloading:1, zoomWindowOffetx: 5,gallery:'gallery_01',containLensZoom: true,easing : true,scrollZoom : false,zoomLens:false,lensSize:20, zoomWindowWidth:380, zoomWindowHeight:474,tint:true, tintColour:'#DCDDE0', tintOpacity:0.5,loadingIcon: '<?php echo url_site_image('proddet_loading.gif',1) ?>'});
			 });
			 var image = $j('#gallery_01 a');
		var zoomConfig = {  };
		var zoomActive = false;

		image.on('click', function(){

				$j.removeData(image, 'elevateZoom');//remove zoom instance from image


		});
           /* $j(document).ready(function(){
				//Examples of how to assign the Colorbox event to elements
				$j(".group4").colorbox({rel:'group4', slideshow:true});
				
			

				
				//Example of preserving a JavaScript event for inline calls.
				$j("#click").click(function(){ 
					$j('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
			});
			*/
            //initiate the plugin and pass the id of the div containing gallery images
          //  $j("#zoom_03").elevateZoom({gallery:'gallery_01', cursor: 'pointer', galleryActiveClass: 'active',tint:true, tintColour:'#F90', tintOpacity:0.5, imageCrossfade: true, loadingIcon: 'http://www.elevateweb.co.uk/spinner.gif'});
          // $j("#zoom_03").elevateZoom({constrainType:"height", <?php echo $zoomtype?> containLensZoom: false,constrainSize:274, gallery:'gallery_01', cursor: 'pointer', galleryActiveClass: "active"});
						$j("#zoom_03").elevateZoom({zoomWindowPosition: 1,responsive:true,preloading:1, zoomWindowOffetx: 5,gallery:'gallery_01',containLensZoom: true,easing : true,scrollZoom : false,zoomLens:false,lensSize:20, zoomWindowWidth:380, zoomWindowHeight:474,tint:true, tintColour:'#DCDDE0', tintOpacity:0.5,loadingIcon: '<?php echo url_site_image('proddet_loading.gif',1) ?>'});

			//$j("#zoom_03").elevateZoom({constrainType:"height", constrainSize:274, zoomType: "lens", containLensZoom: true, gallery:'gallery_01', cursor: 'pointer', galleryActiveClass: "active"} - See more at: http://www.elevateweb.co.uk/image-zoom/examples#sthash.LDRireBi.dpuf});
            //pass the images to Fancybox
           
             $j("#zoom_03").bind("click", function(e) { var ez = $j('#zoom_03').data('elevateZoom');	
				 $j.fancybox(ez.getGalleryList()); return false; 
				
				 
				 }); 
             
             $j("#gallery_01 a").click(function() {
				   $j("#zoom_03").css("height", 482);
                //$j("#zoom_03").css("width", 645);
            var myVar = setInterval(function(){ // wait for fading

              var height = $j("#zoom_03").css("height");
                var width = $j("#zoom_03").css("width");
                if(width>640)
                {
					$j("#zoom_03").css("width", 650);
				}
                if(width<600)
                {
                $j(".zoomContainer").css("height", height);
                $j(".zoomContainer").css("width", width);
                $j(".zoomContainer .zoomWindow").css("height", height);
                $j(".zoomContainer .zoomWindow").css("width", width);
               
			}

                clearInterval(myVar);
            }, 100);
        });
       
            
            </script>
                                 <div class="tabwrapper">

<?php
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
<?php 
$this->show_ProductVariables($row_prod,'',$sizechart_heading);
?>


</div>
                                 <?php
                                 echo $label_val = $this->show_ProductLabels($prodid);
							    ?>


</div>

<div class="detail_right"><div id="propertymenu">
<ul>
<li class="print2"><a href="javascript:window.print();">Print Details</a></li>

<?php
	$lat = 0;
	 $long = 0 ;
	 $sql_keyword = "SELECT product_keywords FROM products where product_id=".$row_prod['product_id']." AND sites_site_id=$ecom_siteid LIMIT 1";
	$ret_keyword = $db->query($sql_keyword);
	 $db->num_rows($ret_keyword);
	if($db->num_rows($ret_keyword)>0)
	{
		$row_keyword =  $db->fetch_array($ret_keyword);
		$address = urlencode($row_keyword['product_keywords']);
		if($address!='')
		{
		$add_arr = explode(",",$address);
		$to = $add_arr[0];
		$url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_a = json_decode($response);
		 $lat = $response_a->results[0]->geometry->location->lat;
		 $long = $response_a->results[0]->geometry->location->lng;
			 if($lat!=0 && $long!=0)
			 {
			 ?>
			<li class="map2"><a href="#mapcode_head">View map</a></li>

			<?php
			}
		}
}
?>

<?php
$HTML_enquiry = $HTML_email = '';
if ($row_prod['product_show_enquirelink']==1)
			{
				$HTML_enquiry = '<li class="basket2"><a href="javascript:void" onclick="show_wait_button(this,\'Please Wait...\');document.frm_proddetails.fpurpose.value=\'Prod_Enquire\';document.frm_proddetails.submit();">Arrange to View</a></li>';			
			}
			//echo $HTML_enquiry;
if($email_show==1)
{
	$HTML_email = '<li class="friend2"><a href="'.url_link('emailafriend'.$_REQUEST['product_id'].'.html',1).'" title="'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND']).'"> Send to Friend</a></li>';
}	
echo $HTML_email;
	$HTML_callback = '<li class="callback2"><a href="#" onclick="req_callback(this)" title="Request A Call Back"> Request A Call Back</a></li>';
		echo $HTML_callback;

$HTML_faq = '<li class="faq2"><a href="http://'.$ecom_hostname.'/faq.html" title="FAQ"> Got A Question? Visit FAQ</a></li>';
echo $HTML_faq;
			
?>





</ul>
 
<div class="deat_pdt_buy_outr">
				 	 <?php
					 /*if($row_prod['product_show_cartlink']==0)
					 {
					 ?>
					 <div class="enquire_out" title="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'])?>" alt="<?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'])?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Enquire';document.frm_proddetails.submit();"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY']); ?></div>
					<?php
					}
					else
					{
					?>
					<div class="deat_pdt_buy_right">
					 <? $this->show_buttons($row_prod);?>
					 </div>
					 <?php
					 */ 
					 /*
					<div class="deat_pdt_buy_left">
					<div class="deat_pdt_buyA"><? //=$HTML_wishlist?>
					</div>
					</div>
					<?php
                     
              		}*/
					?>
				
					<?php 
					if ($row_prod['product_show_enquirelink']==1)
					{
					?>
						<div class="deat_pdt_buy_right">
						<div class="buyBinner_link"><a href="javascript:void" class="det_buy_link_book" onClick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Enquire';document.frm_proddetails.submit();"><?php echo 'Arrange A Viewing';?></a></div>
						</div>
					<?php
					}
					?>
				</div>
<div style="padding-top:5px;">&nbsp;</div>
<div class="callwrap" >
<ul>
<li class="call_icon"><a href="#" style="font-size:20px;"> +44 07872 377266
 </a></li>
</ul>
</div>

<div class="callwrap1">
<ul>
<li class='fb_call'><a href="https://www.facebook.com/messages/unipadlancaster" target="_blank"><img src='<?php url_site_image('floating_img.png')?>' border="0" alt="Speak With Unipad on Facebook Messenger"></a></li>
</ul>

</div>
</div>
	<?php
	
	 if($lat!=0 && $long!=0)
	 {
		?>
<div class="mapcode">
					<div class ="mapcode_head" id="mapcode_head">Location</div> 

					<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
					<div style="overflow:hidden;height:300px;width:275px;">
					<div id="gmap_canvas" style="height:300px;width:275px;"></div>
					<style>#gmap_canvas img{max-width:none!important;background:none!important}</style>
					</div>
					<script type="text/javascript"> 
					function getdirections()
					{  
						var pcode ;
						pcode = document.getElementById('get-directions').value;
						window.open("https://www.google.co.uk/maps?saddr="+pcode+"&daddr=<?php echo $address?>","_blank");
					}
					function init_map()
					{
					var myOptions = {zoom:15,center:new google.maps.LatLng(<?php echo $lat?>,<?php echo $long ?>),mapTypeId: google.maps.MapTypeId.ROADMAP};
					map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
					marker = new google.maps.Marker({map: map,position: new google.maps.LatLng(<?php echo $lat?>,<?php echo $long ?>)});
					<?php
					/*infowindow = new google.maps.InfoWindow({content:"<b></b><br/>ernakulam <br/> vyttila" });
					google.maps.event.addListener(marker, "click", function(){infowindow.open(map,marker);});
					infowindow.open(map,marker);*/
					?>
					}google.maps.event.addDomListener(window, 'load', init_map);</script>

<div class="outdirections">
	<div class="outdirectionsD">
	<strong >Directions</strong>
	</div>
<div class="outdirectionsA">


<input id="get-directions" class="get-directions" size="20" value="Enter your postcode" onblur="if(this.value=='') this.value='Enter your postcode';" onfocus="if(this.value=='Enter your postcode') this.value='';" type="text">
<span class="outdirectionsA-span">
<a href="#" onclick="getdirections();return false;" class="">
<img src="<?php echo url_site_image('go.png')?>" alt="Go" border="0"></a>
<br/>
<br/>
</span>
</div>
<div class="outdirectionsB">

<br><span >Directions will opens in new window</span>
</div>
</div>
</div>				
	<?php
	}

//print_r($_SERVER);
//echo $_SERVER['REQUEST_URI'];
 $qr_url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
?>			
	

<div id="qrwrap"><strong>Save to mobile</strong><span>Scan this code to save this property on your mobile device.</span>
		 <div id="qrcode" >
		<img src="https://chart.googleapis.com/chart?&amp;cht=qr&amp;chs=95x95&amp;chl=<?php echo $qr_url ?>&amp;choe=UTF-8&amp;chld=H|0" alt="QR code">
		</div>
		</div>

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
				/*
				list($sml_width,$sml_height) = getimagesize(ORG_DOCROOT.'/images/'.$ecom_hostname.'/'.$prodimg_arr[0][$pass_type]);
					list($big_width,$big_height) = getimagesize(ORG_DOCROOT.'/images/'.$ecom_hostname.'/'.$prodimg_arr[0]['image_extralargepath']);
					
										
					if($sml_width and $sml_height and $big_width and $big_height)
					{
						if ($big_width<$sml_width or $big_height<$sml_height)
						{
							$extralarge_img = url_root_image($prodimg_arr[0][$pass_type],1);
						}
						else
							$extralarge_img = url_root_image($prodimg_arr[0]['image_extralargepath'],1);
					}
					else
					*/ 
						$extralarge_img = url_root_image($prodimg_arr[0]['image_extralargepath'],1);
						//echo url_root_image($prodimg_arr[0][$pass_type],1);
				//if($just_return_id!=true)
				{
					/*
				?>
				<a class="group4"  href="<?php echo $extralarge_img ?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>">
					<img id="zoom_03" src="<?php echo url_root_image($prodimg_arr[0][$pass_type],1)?>" data-zoom-image="<?php echo $extralarge_img?>"/>
                </a>
				<?php
				*/
				?>
					<img id="zoom_03" src="<?php echo url_root_image($prodimg_arr[0]['image_bigpath'],1)?>" data-zoom-image="<?php echo $extralarge_img;?>" alt="<?php echo $row_prod['product_name'];?>"/>
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
	$exclude_prodid = 0;

	$show_normalimage = false;
	$prodimg_arr		= array();
	$pass_type 	= 'image_thumbcategorypath';
	//$exclude_prodid = 0;
	$pdt_img = get_default_imagetype('proddet');;
	if ($_REQUEST['prod_curtab'] and $row_prod['product_variablecombocommon_image_allowed']=='N')
	{
		//if ($exclude_tabid)
			$prodimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type.','.$pdt_img,$exclude_tabid,0);	
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
			//if ($exclude_prodid)
				$prodimg_arr = get_imagelist_combination($row_prod['default_comb_id'],$pass_type.','.$pdt_img,$exclude_prodid,0);
		}		
		else
		{
		//if ($exclude_prodid)
		$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type.','.$pdt_img,$exclude_prodid,0);
		}
	} 
	if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
	{

			?>	
			<ul class="thumbnail">
			<li></li>
			<?php
			if ($pass_type=='image_thumbcategorypath') // If the more image type is Thumb then show 3 in a row otherwise show 2 in a row
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
			//print_r($prodimg_arr);
			foreach ($prodimg_arr as $k=>$v)
			{ 
				//print_r($v);
				$title = ($v['image_title'])?stripslash_normal($v['image_title']):$row_prod['product_name'];
				/*
				list($sml_width,$sml_height) = getimagesize(ORG_DOCROOT.'/images/'.$ecom_hostname.'/'.$v['image_bigpath']);
				list($big_width,$big_height) = getimagesize(ORG_DOCROOT.'/images/'.$ecom_hostname.'/'.$v['image_extralargepath']);

				//echo '<br>sml '.$sml_width.'x'.$sml_height;
				//echo '<br>bg '.$big_width.'x'.$big_height;

				if($sml_width and $sml_height and $big_width and $big_height)
				{
				if ($big_width<$sml_width or $big_height<$sml_height)
				{
				$extralarge_img = url_root_image($v['image_bigpath'],1);
				}
				else
				$extralarge_img = url_root_image($v['image_extralargepath'],1);
				}
				else
				*/ 
				$extralarge_img = url_root_image($v['image_extralargepath'],1);
				//echo url_root_image($v['image_bigpath'],1);
				/*
				?>
				<li>
				<a href="<?php echo $extralarge_img?>" title="<?php echo $title;?>" class="group4">
				<img id="<?php echo $k; ?>" src="<?php echo url_root_image($v['image_thumbcategorypath'],1);?>" />
				</a></li>
				<?php
				*/
				?>
				<script type="text/javascript">
				var img=new Image();
				img.src='<?php echo url_root_image($v['image_bigpath'],1);?>';
				</script>
				
							<a data-image="<?php echo url_root_image($v['image_bigpath'],1);?>" data-zoom-image="<?php echo $extralarge_img?>">	<img id="<?php echo $k; ?>" src="<?php echo url_root_image($v['image_thumbcategorypath'],1);?>" alt="<?php echo $row_prod['product_name'];?>"/>
							</a>

				<?php
			}
			?>
			</ul>			
			<?php
	}
}
function show_ProductVariables($row_prod,$pos='column',$sizechart_heading)
{
    global $db,$ecom_siteid,$Captions_arr,$Settings_arr,$ecom_themename,$ecom_hostname;

$prod_id = $row_prod['product_id'];
	$i = 0;

	// ######################################################

	// Check whether any variables exists for current product

	// ######################################################

	$sql_var	=	"SELECT			var_id,var_name

							FROM 	product_variables 

							WHERE 	products_product_id = ".$prod_id." 

							AND		var_value_exists = 0

							ORDER BY var_order";

	//echo "<br>".$sql_var;

	$ret_var = $db->query($sql_var);

	
   /*
	if($db->num_rows($ret_var))

	{

		$var_cnt	=	0;

	    echo "<div class='list_right'>";

		echo "<ul class='list_point_left'>";

		while($row_var = $db->fetch_array($ret_var))

		{

			$var_cnt++;

			if($var_cnt == 8)

			{

				echo "</ul>";

				echo "<ul class='list_point_right'>";

			}

			echo "<li>".$row_var['var_name']."</li>";

		}
		echo "</ul>";
		echo "</div>";
	}
	*/ 	

}
function show_buttons($row_prod)
{
	
	global $Captions_arr,$showqty,$Settings_arr;
	$cust_id 	= get_session_var("ecom_login_customer");
	$showqty	= $Settings_arr['show_qty_box'];// show the qty box
	$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslash_normal($row_prod['product_det_qty_caption']):stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY']);
	// Get the caption to be show in the button
	$caption_key = show_addtocart($row_prod,array(0),'',true);
	if ($caption_key!='') // Check whether the buynow or preorder button is to be displayed
	{
	?>	
		<div class="deat_pdt_buyB">
		<div class="deat_pdt_buyBinner_book">
	<?php	
	if($showqty==1)// this decision is made in the main shop settings
	{
		if($row_prod['product_det_qty_type']=='NOR')
		{
?>
			<div class="buyBinner_qty_book"><?=$cur_qty_caption?></div>
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
	
?>
<div class="buyBinner_link"><a href="#" class="det_buy_link_book" onClick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addcart';document.frm_proddetails.submit();"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS'][$caption_key]);?></a></div>
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

	$display_ok	=	false;

	$ret_val	=	'';

	// Check whether labels exists for current product

	$cats_arr	=	$grp_arr	=	array();

	// Get the categories that area linked with current product

	$sql_cats	=	"SELECT product_categories_category_id FROM product_category_map WHERE products_product_id = $prod_id";	

	//echo "<br>".$sql_cats;

	$ret_cats	=	$db->query($sql_cats);

	if($db->num_rows($ret_cats))

	{

		while ($row_cats = $db->fetch_array($ret_cats))

		{

			$cats_arr[] = $row_cats['product_categories_category_id'];

		}

		$sql_grps	=	"SELECT

								DISTINCT	product_labels_group_group_id

								FROM 		product_category_product_labels_group_map a, product_labels_group b

								WHERE 		a.product_labels_group_group_id = b.group_id 

								AND 		b.group_hide = 0 

								AND			product_categories_category_id IN (".implode(',',$cats_arr).") ";

		//echo "<br>".$sql_grps;

		$ret_grps = $db->query($sql_grps);

		if($db->num_rows($ret_grps))

		{

			while ($row_grps = $db->fetch_array($ret_grps))

			{

				$grp_arr[]	=	$row_grps['product_labels_group_group_id'];

			}	

			// Check whether there exists atleast one label to display

			$sql_lblcheck	=	"SELECT			a.map_id 

										FROM 	product_labels_group_label_map a , product_labels_group b

										WHERE 	product_labels_group_group_id IN (".implode(',',$grp_arr).") 

										AND 	a.product_labels_group_group_id=b.group_id 

										AND		b.group_hide = 0";

			//echo "<br>".$sql_lblcheck;

			$ret_lblcheck 	= $db->query($sql_lblcheck);

			$grp_nos		= $db->num_rows($ret_lblcheck);

			if($grp_nos)

			{

				// Get the product label group details in order

				$sql_grp	=	"SELECT			group_id,group_name,group_name_hide

										FROM 	product_labels_group 

										WHERE 	group_id IN (".implode(',',$grp_arr).") 

										ORDER BY group_order";

				//echo "<br>".$sql_grp;

				$ret_grp	=	$db->query($sql_grp);

				if($db->num_rows($ret_grp))

				{

					$ret_val	=	'<ul class="featurelist">';

					$i			=	1;

					$grp_cnt	=	0;

					$label_arr	=	array();

					while ($row_grp = $db->fetch_array($ret_grp))

					{

						// Check whether there exists atleast one label under this group to display

						$sql_labels	=	"SELECT

														a.label_id,

														a.label_name,

														a.in_search,

														a.is_textbox,

														c.product_site_labels_values_label_value_id,

														c.label_value 

												FROM	product_site_labels a,product_labels_group_label_map b,product_labels c

												WHERE 	b.product_labels_group_group_id = ".$row_grp['group_id']." 

												AND		c.products_product_id = $prod_id

												AND		a.label_id = b.product_site_labels_label_id 

												AND		a.label_id = c.product_site_labels_label_id 

												AND		a.label_hide = 0 

												AND		(c.product_site_labels_values_label_value_id>0 OR  label_value <> '')

												ORDER BY b.map_order";

						//echo "<br>".$sql_labels;

						$ret_labels	=	$db->query($sql_labels);

						if($db->num_rows($ret_labels))

						{

							$grp_cnt++;
							?>
							<div class="listicon">
<ul class="featurelist">

					<?php

							//$label_arr[$row_grp['group_name'].'~'.$row_grp['group_name_hide']] = array();

							while($row_labels = $db->fetch_array($ret_labels))

							{
                                $label_image ='';
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "bedroom")

								{	$label_image	=	'icon_double_bed_no_name.png';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "bathroom")

								{	$label_image	=	'icon_bath_room.png';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "walkable")

								{	$label_image	=	'icon_walkable.png';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "flattv")

								{	$label_image	=	'icon_flat_tv_no_name.png';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "coffeetable")

								{	$label_image	=	'icon_coffee_table_noname.png';		}
								
								
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "utilitybills")

								{	$label_image	=	'icon_utility.png';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "bikestore")

								{	$label_image	=	'icons_bike_store-.png';		}

								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "24/7maintenance")

								{	$label_image	=	'icons_maintenance.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "doublebeds")
								
								{	$label_image	=	'icons_double_bed.png';		}
								
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "toilet")

								{	$label_image	=	'icons_toilet.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "shower")

								{	$label_image	=	'icon_bathroom.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "citycentre")

								{	$label_image	=	'icon_city_center.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "ensuitebathrooms")

								{	$label_image	=	'icon_ensuit.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "broadband")

								{	$label_image	=	'icons_broadband.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "fibrebroadband")

								{	$label_image	=	'icons_fibre_broadband.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "wirelessinternet")
								{	$label_image	=	'icons_wifi.png';		}
								if(strtolower(str_replace(' ','',$row_labels['label_name'])) == "lettingperiod")
								{	$label_image	=	'icon_lease.png';		}
							?>
							
							<li><img src="<?php url_site_image($label_image);?>" alt="features"/>
							<span class='span_label_text'><?php echo $row_labels['label_value'];?></span>
							</li>
									




							<?php

								/*$vals = '';

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

									//echo "<br>".$sql_labelval;

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

								}*/

							}
							?>
							
</ul>








</div>
		
							<?php
								

						}

					}

					

					$ret_val .= '</ul>';	

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
            <div class="shlf_a_outer">
                <div class="shlf_a_navF"><a href="#null" onMouseOver="scrollDivRight('containerA')" onMouseOut="stopMe()"><img src="<?php url_site_image('shlf-arw-l.gif')?>" alt="scroll"></a></div>
                <div id="containerA" class="shlf_thumb_outerA">
               <div id="scroller" style="width:<?php echo $div_width?>px">
                <?php
                $cnts = $db->num_rows($ret_prod);
				while($row_prod = $db->fetch_array($ret_prod))
				{
											$cnt++;
											if($cnt == 3)
											{
												$cnt	=	0;
												echo "<div class='shelfwrapright'>";
											}
											else
											{
												echo "<div class='shelfwrap'>";
											}
										?>
										<?php
							if($row_prod['product_saleicon_show']==1)
							{
							?>
								<div class="icon-list"><img src="<?php url_site_image('big-sale.png')?>" alt="Sale"></div>
							<?php				
							}
							if($row_prod['product_newicon_show']==1)
							{
							?>
								<div class="icon-list"><img src="<?php url_site_image('big-new.png')?>" alt="New"></div>
							<?php				
							}
							?>
											<?php
											//if($shelfData['shelf_showimage']==1) // whether image is to be displayed
											{
											?>
											<div class="shelfimgwrap"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>">
											<?php
														$pass_type = 'image_thumbpath';
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
											</a> </div>
											<?
											}
											?>
                                            <div class="titlewrap"> 
                                            <?php
											//if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
											{
											?>
                                            <span class="title"><?php echo stripslash_normal($row_prod['product_name'])?></span>
											<?php /*?><span class="title"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"  title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a> </span><?php */?>
											<?
											}
											
											//if($shelfData['shelf_showprice']==1) // whether price is to be displayed
											{
											?>
											<span class="price">
											<?php $price_arr =  show_Price($row_prod,array(),'compshelf',false,5);
											//print_r($price_arr);
											$base_price = $price_arr['price_with_captions']['base_price'];
											$discount_price = $price_arr['price_with_captions']['discounted_price'];
												if($discount_price)
													echo $discount_price;
												else
													echo $base_price;
												?>
											</span>
											<?php
											}
											?>
											<span class="btarea">
                                            <input type="button" name="read_<?php echo $row_prod['product_id']?>" value="<?php echo stripslash_normal($Captions_arr['COMMON']['READ_MORE'])?>" onclick="javascript:window.location.href='<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>'" />
                                            <?php /*?><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['READ_MORE'])?></a><?php */?> </span>
                                            </div>
										</div>
										<?	}				
	            ?>
                </div>
                </div>
                <div class="shlf_a_navFR"> <a href="#null" onMouseOver="scrollDivLeft('containerA','<?php echo $div_width?>')" onMouseOut="stopMe()"><img src="<?php url_site_image('shlf-arw-r.gif')?>" alt="scroll" /></a></div>
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
				<input type="button" name="stocknotif_submit" value=" Send Request " class="buttongray" onClick=" validate_stocknotify(document.frm_proddetails)"  />
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
					<input type="button" name="stocknotif_submit" value=" Send Request " class="buttongray" onClick=" validate_stocknotify(document.frm_proddetails)"  />
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
};	
?>