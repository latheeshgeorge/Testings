<?php
	/*############################################################################
	# Script Name 	: favcategory_showallHtml.php
	# Description 	: Page which holds the display logic for all products under fav category
	# Coded by 		: LSH
	# Created on	: 14-oct-2008
	# Modified by	: LH
	# Modified On	: 
	##########################################################################*/
	class categoryshowall_Html
	{
		// Defining function to show the shelf details
		function Show_favcatproducts($catid,$catname)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
			$customer_id 					= get_session_var("ecom_login_customer");

			$sql_last_login = "SELECT customer_last_login_date FROM customers WHERE sites_site_id=$ecom_siteid AND customer_id=$customer_id";
			$ret_last_login = $db->query($sql_last_login);
			list($row_last_login) = $db->fetch_array($ret_last_login);
			
			$prodcur_arr =array();
			$limit = $Settings_arr['product_maxcnt_fav_category'];
			//Taking the New products added in the category after customer's last login	
			$sql_prod_first = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
												a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
												a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
												product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints,
												a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
												a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
												a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
												a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
												product_freedelivery, product_show_pricepromise, product_saleicon_show, product_saleicon_text, 
												product_newicon_show, product_newicon_text                
											FROM 
												products a,product_category_map b 
											WHERE 
												b.product_categories_category_id = ".$catid." 
												AND a.product_id = b.products_product_id 
												AND a.product_hide = 'N'
												AND a.sites_site_id = $ecom_siteid 
												AND a.product_adddate >= '".$row_last_login."' 
												ORDER BY a.product_webprice ASC LIMIT $limit";
			$ret_prod_first = $db->query($sql_prod_first);
			
			if($db->num_rows($ret_prod_first))
			{ 
				$limit = $limit-$db->num_rows($ret_prod_first);
				if($db->num_rows($ret_prod_first)>0)
				{
					while($row_prod_first = $db->fetch_array($ret_prod_first))
					{
					  $prodcur_arr[] = $row_prod_first;
					  $ids[] = $row_prod_first['product_id'];
					}
				}
			}
			if(count($ids)==0)
			{
			$ids = array('-1');
			}
			$ids_in = implode(',',$ids);
			//if no 3 new products found then
			if($limit>0)
			{
				//second case -  taking products with higest discount
				$sql_prod_sec = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
													a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
													a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
													product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints,  
													a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists  ,
													CASE product_discount_enteredasval   
														WHEN  '0'
															THEN (product_webprice * product_discount /100)
														WHEN  '1'
															THEN product_discount
														WHEN  '2'
															THEN (product_webprice-product_discount)
														END  AS discountval,
														product_variablecomboprice_allowed,product_variablecombocommon_image_allowed,default_comb_id,
														price_normalprefix,price_normalsuffix, price_fromprefix, price_fromsuffix,price_specialofferprefix,price_specialoffersuffix, 
														price_discountprefix, price_discountsuffix, price_yousaveprefix, price_yousavesuffix,price_noprice,
														product_freedelivery, product_show_pricepromise, product_saleicon_show, product_saleicon_text, 
														product_newicon_show, product_newicon_text 
													FROM  
															products a,product_category_map b  
													WHERE 
															sites_site_id =$ecom_siteid
															AND product_discount >0 
															AND b.product_categories_category_id = ".$catid." 
															AND a.product_id = b.products_product_id 
															AND a.product_hide = 'N'
															AND a.product_id NOT IN ($ids_in)
													ORDER  BY 
															discountval DESC LIMIT $limit";
				$ret_prod_sec = $db->query($sql_prod_sec);
				
				if($db->num_rows($ret_prod_sec))
				{ 
				$limit = $limit-$db->num_rows($ret_prod_sec);
					if($db->num_rows($ret_prod_sec)>0)
					{
						while($row_prod_sec = $db->fetch_array($ret_prod_sec))
						{
						  $prodcur_arr[] = $row_prod_sec;
						  $ids[] = $row_prod_sec['product_id'];
						}
					}
				}
				if(count($ids)==0)
				{
					$ids = array('-1');
				}
				$ids_in = implode(',',$ids);
			}
			//Still no 3 products found after new products and discounted products then
			if($limit>0)
			{
				$sql_prod_third = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
												a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
												a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
												product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints, 
												a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
												product_variablecomboprice_allowed,product_variablecombocommon_image_allowed,default_comb_id,
												price_normalprefix,price_normalsuffix, price_fromprefix, price_fromsuffix,price_specialofferprefix,price_specialoffersuffix, 
												price_discountprefix, price_discountsuffix, price_yousaveprefix, price_yousavesuffix,price_noprice,
												product_freedelivery, product_show_pricepromise, product_saleicon_show, product_saleicon_text, 
												product_newicon_show, product_newicon_text       
											FROM 
												products a,product_category_map b 
											WHERE 
												b.product_categories_category_id = ".$catid." 
												AND a.product_id = b.products_product_id 
												AND a.product_hide = 'N'
												AND a.sites_site_id = $ecom_siteid 
												AND a.product_id NOT IN ($ids_in) 
												ORDER BY a.product_webprice ASC LIMIT $limit";
				$ret_prod_third = $db->query($sql_prod_third);
				if($db->num_rows($ret_prod_third)>0)
				{
					while($row_prod_third = $db->fetch_array($ret_prod_third))
					{
					  $prodcur_arr[] = $row_prod_third;
					  $ids[] = $row_prod_third['product_id'];
					}
				}
				if(count($ids)==0)
				{
					$ids = array('-1');
				}
				$ids_in = implode(',',$ids);
			}
			$prod_compare_enabled = isProductCompareEnabled();
			$pass_type = get_default_imagetype('midshelf');
			?>
			<div class="mid_shlf2_hdr">
				<div class="mid_shlf2_hdr_top"></div>
					<div class="mid_shlf2_hdr_middle"><a href="<?php url_category($catid,$catname,-1,$_REQUEST['catgroup_id'],0)?>" class="categoreyname_headerlink" title="<?php echo stripslash_normal($catname)?>"><?php echo stripslash_normal($catname)?></a>
					</div>
				<div class="mid_shlf2_hdr_bottom"></div>
			</div>
			<?php
			switch($Settings_arr['favoritecategory_prodlisting'])
			{ 
				case '1row':
				?>
			<div class="normal_shlf_mid_con">
				<div class="normal_shlf_mid_top"></div>
				<div class="normal_shlf_mid_mid">
				<?
					foreach( $prodcur_arr as $k=>$product_array)
					   {
						$HTML_title = $HTML_image = $HTML_desc = '';
						$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
						$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
						
							$HTML_title = '<div class="normal_shlfB_pdt_name"><a href="'.url_product($product_array['product_id'],$product_array['product_name'],1).'" title="'.stripslash_normal($product_array['product_name']).'">'.stripslash_normal($product_array['product_name']).'</a></div>';
							$HTML_image ='<a href="'.url_product($product_array['product_id'],$product_array['product_name'],1).'" title="'.stripslash_normal($product_array['product_name']).'">';
							// Calling the function to get the image to be shown
							$img_arr = get_imagelist('prod',$product_array['product_id'],$pass_type,0,0,1);
							if(count($img_arr))
							{
								$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$product_array['product_name'],$product_array['product_name'],'','',1);
							}
							else
							{
								// calling the function to get the default image
								$no_img = get_noimage('prod',$pass_type); 
								if ($no_img)
								{
									$HTML_image .= show_image($no_img,$product_array['product_name'],$product_array['product_name'],'','',1);
								}       
							}       
							$HTML_image .= '</a>';
							$HTML_desc = stripslash_normal($product_array['product_shortdesc']);
						if($product_array['product_saleicon_show']==1)
						{
							$desc = stripslash_normal(trim($product_array['product_saleicon_text']));
							if($desc!='')
							{
								  $HTML_sale = '<div class="normal_shlfB_pdt_sale">'.$desc.'</div>';
							}
						}
						if($product_array['product_newicon_show']==1)
						{
							$desc = stripslash_normal(trim($product_array['product_newicon_text']));
							if($desc!='')
							{
								  $HTML_new = '<div class="normal_shlfB_pdt_new">'.$desc.'</div>';
							}
						}
						
							$module_name = 'mod_product_reviews';
							if(in_array($module_name,$inlineSiteComponents))
							{
								if($product_array['product_averagerating']>=0)
								{
									$HTML_rating = display_rating($product_array['product_averagerating'],1);
								}
							}
						
							$price_class_arr['class_type']          = 'div';
							$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
							$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
							$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
							$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
							$HTML_price = show_Price($product_array,$price_class_arr,'shelfcenter_1');
						if($product_array['product_bulkdiscount_allowed']=='Y')
						{
							$HTML_bulk = '<img src="'.url_site_image('multi-buyA.gif',1).'" />';
						}
						else
							$HTML_bulk = '&nbsp;';
						if($product_array['product_bonuspoints']>0 and $shelfData['shelf_showbonuspoints']==1)
						{
							$HTML_bonus = 'Bonus: '.$product_array['product_bonuspoints'];
							$bonus_class = 'normal_shlfB_pdt_bonus';
						}
						else
						{
							$HTML_bonus = '&nbsp;';
							$bonus_class = 'normal_shlfB_pdt_bonus_blank';
						}	
						if($comp_active)
							$HTML_compare = dislplayCompareButton($product_array['product_id'],'','',1);
						if($product_array['product_freedelivery']==1)
						{
							$HTML_freedel = ' <div class="normal_shlfB_free"></div>';

						}
						if($HTML_bonus!='&nbsp;' or $HTML_rating !='&nbsp;')
						{
							$HTML_bonus_bar = ' <div class="normal_shlfB_pdt_bonus_otr">
												<div class="'.$bonus_class.'">'.$HTML_bonus.'</div>
												<div class="normal_shlfB_pdt_rate">'.$HTML_rating.'</div>
												</div>';
						}	
						
						$frm_name = uniqid('best_');
				?>
						<div class="normal_shlfB_pdt_outr">
						<?=$HTML_freedel?>
						<div class="normal_shlfB_pdt_top"></div>
						<div class="normal_shlfB_pdt_mid">
						<?=$HTML_title?>
						<div class="normal_shlfB_pdt_img_otr">
						<div class="normal_shlfB_pdt_img"><?=$HTML_image?></div>
						</div>
						<div class="normal_shlfB_pdt_des_otr">
						<div class="normal_shlfB_pdt_des"><?=$HTML_desc?></div>
						<?=$HTML_sale?>
						<?=$HTML_new?>
						<div class="normal_shlfB_pdt_com_otr">
						<div class="normal_shlfB_multibuy"><?=$HTML_bulk?></div>
						<div class="normal_shlfB_pdt_com"><?=$HTML_compare?></div>
						</div>
						<?=$HTML_bonus_bar?>
						</div>
						<div class="normal_shlfB_pdt_right_otr">
						<div class="normal_shlfB_pdt_price">
						<div class="normal_shlfB_pdt_price_top"></div>
						<div class="normal_shlfB_pdt_price_mid">
						<?=$HTML_price?>
						</div>
						<div class="normal_shlfB_pdt_price_bottom"></div>
						</div>
						<div class="normal_shlfB_pdt_buy_outr">
						<div class="normal_shlfB_pdt_buy">
						<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $product_array['product_id']?>)">
						<input type="hidden" name="fpurpose" value="" />
						<input type="hidden" name="fproduct_id" value="" />
						<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
						<input type="hidden" name="fproduct_url" value="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" />																
						<?
							$class_arr                      = array();
							$class_arr['ADD_TO_CART']       = '';
							$class_arr['PREORDER']          = '';
							$class_arr['ENQUIRE']           = '';
							$class_arr['QTY_DIV']           = 'normal_shlfB_pdt_input';
							$class_arr['QTY']               = ' ';
							show_addtocart($product_array,$class_arr,$frm_name,false,'','','normal_shlfB_pdt_buy_btn');
						?>
						</form>
						</div>
						</div>
						<div class="normal_shlfB_pdt_info"><?php show_moreinfo($product_array,'')?></div>
						</div>
						
						</div>
						</div>
				<?php
					}
				?>
				</div>
				<div class="normal_shlf_mid_bottom"></div> 
				</div>
				<?
				break;
				case '3row':					
				break;
			?>
            <div class="product_list_outer"> 
					<div class="pdt_list_outer">
		<?php	foreach($prodcur_arr as $k=>$product_array)
				{
					$HTML_title = $HTML_image = $HTML_desc = '';
					$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
					$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
					$HTML_new = $HTML_sale = '';
					
					//if($cat_det['product_showtitle']==1)// whether title is to be displayed
						$HTML_title = '<div class="pdt_list_pdt_name"><a href="'.url_product($product_array['product_id'],$product_array['product_name'],1).'" title="'.stripslash_normal($product_array['product_name']).'">'.stripslash_normal($product_array['product_name']).'</a></div>';
				
					//if ($cat_det['product_showimage']==1)// Check whether description is to be displayed
					{
						$HTML_image ='<a href="'.url_product($product_array['product_id'],$product_array['product_name'],1).'" title="'.stripslash_normal($product_array['product_name']).'">';
						// Calling the function to get the image to be shown
						$pass_type = 'image_thumbcategorypath';
						$img_arr = get_imagelist('prod',$product_array['product_id'],$pass_type,0,0,1);
						if(count($img_arr))
						{
							$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$product_array['product_name'],$product_array['product_name'],'','',1);
						}
						else
						{
							// calling the function to get the default image
							$no_img = get_noimage('prod',$pass_type); 
							if ($no_img)
							{
								$HTML_image .= show_image($no_img,$product_array['product_name'],$product_array['product_name'],'','',1);
							}       
						}       
						$HTML_image .= '</a>';
					}
					//if ($cat_det['product_showshortdescription']==1)// Check whether description is to be displayed
					{
						$HTML_desc = stripslash_normal($product_array['product_shortdesc']);
					}
					/*
					$price_class_arr['class_type']          = 'div';
					$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
					$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
					$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
					$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
					$price_arr =  show_Price($product_array,array(),'compshelf',false,4);
					
					if($price_arr['discounted_price'])
						$HTML_price = $price_arr['discounted_price'];
					else
						$HTML_price = $price_arr['base_price'];
						*/ 
				
					//$HTML_price = show_Price($product_array,$price_class_arr,'shelfcenter_3');
					if($cur_col==0)
					{
						echo  '<div class="pdt_list_thumb_outer">';
					}
					if($cur_col%2==0 && $cur_col!=0)	
					{
						$cls = "pdt_list_pdt";
					}
					else
					{
						$cls = "pdt_list_pdt";
					}
		?>			<div class="<?php echo $cls?>">
						<div class="pdt_list_pdt_mid">
		<?php		if($product_array['product_saleicon_show']==1)
					{
						$desc = stripslash_normal(trim($product_array['product_saleicon_text']));
						//if($desc!='')
						{
							$HTML_sale = '<div class="pdt_list_sale_3row"></div>';
						}
					}
					if($product_array['product_newicon_show']==1)
					{
						$desc = stripslash_normal(trim($product_array['product_newicon_text']));
						//if($desc!='')
						{
							$HTML_new = '<div class="pdt_list_new_3row"></div>';
						}
					}
					echo $HTML_new;
					echo  $HTML_sale;
					
					if($cat_det['product_showrating']==1)
					{
						$module_name = 'mod_product_reviews';
						if(in_array($module_name,$inlineSiteComponents))
						{
							if($product_array['product_averagerating']>=0)
							{
								$HTML_rating = '<div class="list_d_pdt_rate">'.display_rating($product_array['product_averagerating'],1).'</div>';
							}
						}
					}
					//echo $HTML_rating;
		?>					<div class="pdt_list_pdt_r"><?php echo $HTML_image?></div>
							<?=$HTML_title;?>
							<?php
							if($cat_det['product_showshortdescription']==1)// whether title is to be displayed
							{
								
								echo '<p class="product_descB">'.stripslash_normal($product_array['product_shortdesc']).'</p>';
								
							}
							$price_class_arr['ul_class'] 		= 'shelfBul_lu';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice_lu';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice_lu';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice_lu';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice_lu';
													//	echo show_Price($product_array,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
													echo show_Price($product_array,$price_class_arr,'shelfcenter_3');
													show_excluding_vat_msg($product_array,'vat_div');// show excluding VAT msg

							?>
							<div class="pdt_list_pdt_l">
                            <div class="pdt_list_pdt_buy_otr">
                            
		<?php
				if($product_array['product_bonuspoints']>0 and $cat_det['product_showbonuspoints']==1)
				{
				$HTML_bonus = 'Bonus: '.$product_array['product_bonuspoints'].'';
				}
				else
				{
				$HTML_bonus = '';
				}
				/*
				?>
				<div class="pdt_list_bonus">  <?php echo $HTML_bonus;?> </div>  
					*/
					?>		
		<?php		
		/*
		if(isProductCompareEnabled())
					{
						?>
						<div class="pdt_list_pdt_compare">
							<?php
						if(is_array($_SESSION['compare_products']))
						{
							if(in_array($product_array['product_id'],$_SESSION['compare_products']))
							{
								$select = "checked";
							}
							else
							{
								$select = "";
							}
						}
						else
							$select = "";
						$compare_button_displayed = true; ?><form name="add_to_compare" id="add_to_compare" action="" method="post" ><input type="hidden" name="submit_Compare_pdts" id="submit_Compare_pdts" value="1"><input type="checkbox" name="compare_products_<?php echo $product_array['product_id'];?>" value="ADD TO COMPARE" class="buttonred_large" onclick="addtoCompare(<?php echo $product_array['product_id']?>)" id="compare_products_<?php echo $product_array['product_id'];?>" <?php echo $select ?> /></form> Select To Compare
						 </div>
						<?php
				}
				*/ 
		?>					
		<div class="more_info_lu">			
		<a href="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" class="det_buy_link_more" title="<?php echo stripslashes($product_array['product_name'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['MORE_INFO'])?></a>
		</div>
        <?php		$frm_name = uniqid('best_');
		?>
					<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $product_array['product_id']?>)">
                        <input type="hidden" name="fpurpose" value="" />
                        <input type="hidden" name="fproduct_id" value="" />
                        <input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
                        <input type="hidden" name="fproduct_url" value="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" />
                        <input type="hidden" name="qty" id="qty" value="1" />																
		<?php
                        $class_arr                      = array();
                        $class_arr['ADD_TO_CART']       = 'cupid-green';
                        $class_arr['PREORDER']          = 'cupid-green';
                        $class_arr['ENQUIRE']           = 'cupid-green';
                        $class_arr['QTY_DIV']           = 'normal_shlfB_pdt_input';
                        $class_arr['QTY']               = 'quainput';							
                        /* Code for ajax setting starts here */
                        $class_arr['BTN_CLS']           = 'cupid-green';												
                        //show_addtocart($product_array,$class_arr,$frm_name,false,'','','normal_shlfB_pdt_buy_btn');
                        show_addtocart_v5_ajax($frm_name,$product_array,$class_arr,false);
                        /* Code for ajax setting ends here */
                        //echo "product display style - ".$cat_det['product_displaytype'];
		?>
					</form>				
				
				</div>
				</div>
				<div class="pdt_list_m_otr">
				           
				<?php
				/*
				<div class="pdt_list_pdt_more"><a class="" title="Love Meter T-Shirt Heart Lights" href="<?php url_product($product_array['product_id'],$product_array['product_name'])?>">More Info</a></div>
				*/?> 
				</div>   
				
				<?php
				/*
				if($product_array['product_bulkdiscount_allowed']=='Y' || $product_array['product_freedelivery']==1)
				{
				echo "<div class='pdt_list_free_otr'>";
				}
				
				if($product_array['product_freedelivery']==1)
				{
				echo $HTML_freedel = ' <div class="pdt_list_free_del"> </div>';
				}
				if($product_array['product_bulkdiscount_allowed']=='Y')
				{
				echo $HTML_bulk = '<div class="pdt_list_free_bulk"> </div>';
				}
				if($product_array['product_bulkdiscount_allowed']=='Y' || $product_array['product_freedelivery']==1)
				{
				echo "</div>";
				}
				*/ 
				/*				  <div class="pdt_list_pdt_des"><?php echo $HTML_desc;?></div>
				*/ 
				
				
				?>
				
				
				</div>
				</div>
				
				<?php
				
				
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
				?>
				</div>
				</div>
            <?php
				}//end of switchcase
		}
	};	
?>