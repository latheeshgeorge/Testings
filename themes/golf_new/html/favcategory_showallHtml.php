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
            $Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME');
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
									a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
									a.product_freedelivery                                   
								FROM 
									products a,product_category_map b 
								WHERE 
									b.product_categories_category_id = ".$catid." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N'
									AND a.sites_site_id = $ecom_siteid 
									AND a.product_adddate >= '".$row_last_login."' 
								ORDER BY 
									a.product_webprice ASC 
								LIMIT $limit";
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
										a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists ,
										a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
										a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
										a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice  ,
													CASE product_discount_enteredasval   
														WHEN  '0'
															THEN (product_webprice * product_discount /100)
														WHEN  '1'
															THEN product_discount
														WHEN  '2'
															THEN (product_webprice-product_discount)
														END  AS discountval,      
									    a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
										a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
										a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
										a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
										a.product_freedelivery 
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
										a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
										a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
										a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice ,
										a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
										a.product_freedelivery          
									FROM 
										products a,product_category_map b 
									WHERE 
										b.product_categories_category_id = ".$catid." 
										AND a.product_id = b.products_product_id 
										AND a.product_hide = 'N'
										AND a.sites_site_id = $ecom_siteid 
										AND a.product_id NOT IN ($ids_in) 
									ORDER BY 
										a.product_webprice ASC 
									LIMIT $limit";
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
			?>
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php  echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_CATEGORY_HEADER']?></div>
			<?php
			switch($Settings_arr['favoritecategory_prodlisting'])
			{ 
				case '1row':
				?>
				<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
			<?
				if(count($prodcur_arr))
				{
				?>
				<tr>
					<td colspan="3" class="shelfAheader" align="left"><h1><a href="<?php url_category($catid,$catname,-1,$_REQUEST['catgroup_id'],0)?>" class="categoreyname_headerlink" title="<?php echo stripslashes($catname)?>"><?php echo stripslashes($catname)?></a></h1></td>
				</tr>
				
			  <?
				
				foreach( $prodcur_arr as $k=>$product_array)
				{
				    $prodcur_arr[] = $product_array;
									?>						
									<tr >
									<td align="left" valign="middle"class="newshelfBtabletd" onmouseover="this.className='newnormalshelf_hover'" onmouseout="this.className='newshelfBtabletd'">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" class="newshelfBtableinner">
									<tr>
									<td class="newshelfBtableinnertd" valign="top" align="left">
									
									<h2 class="newshelfBprodname"><a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>"><?php echo stripslashes($product_array['product_name'])?></a></h2>
									
									<h6 class="newshelfBproddes"><?php echo stripslashes($product_array['product_shortdesc'])?></h6>
									<?php
										$module_name = 'mod_product_reviews';
										if(in_array($module_name,$inlineSiteComponents))
										{
											if($product_array['product_averagerating']>=0)
											{
										?>
											<div class="shlfa_star">
											<?php
												display_rating($product_array['product_averagerating']);
												?>
											</div>
										<?php
											}
										}	
									    if($product_array['product_bulkdiscount_allowed']=='Y'  || $product_array['product_freedelivery']==1 )
										{
										?>
											<div class="shlf_sale">
											<? if($product_array['product_bulkdiscount_allowed']=='Y')
											{
											?>
											<img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/>
											<?
											}
											if($product_array['product_freedelivery']==1){
											?>
											<img src="<?php url_site_image('free-img.gif')?>" alt="Free Delivery"/>
											<?
											}
											?>
											</div>
										<?php
										}
										?>
									</td>
									
									<td class="newshelfBtableinnertd" valign="middle" align="center">
									
									<a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>">
									<?php
										$pass_type = get_default_imagetype('midshelf');

										$img_arr = get_imagelist('prod',$product_array['product_id'],$pass_type,0,0,1);
										if(count($img_arr))
										{
											show_image(url_root_image($img_arr[0][$pass_type],1),$product_array['product_name'],$product_array['product_name'],'newshelfBimg');
										}
										else
										{
											// calling the function to get the default image
											$no_img = get_noimage('prod',$pass_type); 
											if ($no_img)
											{
												show_image($no_img,$product_array['product_name'],$product_array['product_name'],'newshelfBimg');
											}	
										}	
									?>
									</a> 
									
									<?php if(isProductCompareEnabled())  {
															dislplayCompareButton($product_array['product_id']);
														}?>
									</td>
									<td class="newshelfBtableinnertd" valign="middle" align="left">
									
									<?php
									if($product_array['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($product_array['product_saleicon_text']));
											if($desc!='')
											{
										?>	
												<div class="shlfa_sale_sale"><?php echo $desc?></div>
										<?php
											}
										}
										if($product_array['product_newicon_show']==1)
										{
											$desc = stripslash_normal(trim($product_array['product_newicon_text']));
											if($desc!='')
											{
										?>
												<div class="shlfa_sale_new"><?php echo $desc?></div>
										<?php
											}
										}
											
												$price_class_arr['ul_class'] 		= 'shelfpriceul';
												$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
												$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
												$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
												$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
												echo show_Price($product_array,$price_class_arr,'shelfcenter_1');
												show_excluding_vat_msg($product_array,'vat_div');// show excluding VAT msg
												show_bonus_points_msg($product_array,'bonus_point'); // Show bonus points
												$frm_name = uniqid('shelf_');
										?>	
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $product_array['product_id']?>)">
												<input type="hidden" name="fpurpose" value="" />
												<input type="hidden" name="fproduct_id" value="" />
												<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
												<input type="hidden" name="fproduct_url" value="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" />
												<div class="buy_main">
												<div class="shlf_buy_btn"><div class="shlf_buy_btnleft">
												<?php
														$class_arr 					= array();
														$class_arr['ADD_TO_CART']	= 'link_buy';
														$class_arr['PREORDER']		= 'link_buy';
														$class_arr['ENQUIRE']		= 'link_buy';
														show_addtocart($product_array,$class_arr,$frm_name,false,'','','add_divlink',0)
													?></div>
												</div>      
												<div class="shlf_info_btn"><div class="shlf_info_btnleft">
												<?php show_moreinfo($product_array,'info_buy')?>
												</div></div>   
												</div>  
												</form>
									
									</td>
									</tr>
																			
									</table></td>
									</tr>
									<? 
						}//end of for loop
			   }//end of prod checking
	            ?>
				</table>
				<?
				break;
				case '3row':
			?>					
			<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
			<?
				if(count($prodcur_arr))
				{
				?>
				<tr>
					<td colspan="3" class="shelfAheader" align="left"><h1><a href="<?php url_category($catid,$catname,-1,$_REQUEST['catgroup_id'],0)?>" class="categoreyname_headerlink" title="<?php echo stripslashes($catname)?>"><?php echo stripslashes($catname)?></a></h1></td>
				</tr>
				<tr>
			  <?
			
				$max_col = 3;
				$cur_col = 0;
				//$prodcur_arr = array();
				
				foreach( $prodcur_arr as $k=>$product_array)
				{
					$prodcurtd_arr[] = $product_array;
					if($cur_col == 0)
					{
					  echo "<tr>";
					}
					$cur_col ++;

						?>
								<td class="newshelfAtabletd" onmouseover="this.className='newshelfAtabletd_hover'" onmouseout="this.className='newshelfAtabletd'" align="left" valign="top">												
								<ul class="shelfAul">
									
											<li><h2 class="shelfAprodname"><a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>"><?php echo stripslashes($product_array['product_name'])?></a></h2></li>
										
									
											<li>
												<?php
													$price_class_arr['ul_class'] 		= 'shelfBul';
													$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
													echo show_Price($product_array,$price_class_arr,'shelfcenter_3');
													show_excluding_vat_msg($product_array,'vat_div');// show excluding VAT msg
												?>	
											</li>
											<li class="shelfimg">
												<a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>">
														<?php
															// Calling the function to get the type of image to shown for current 
															$pass_type = get_default_imagetype('fav_prod');
															// Calling the function to get the image to be shown
															$img_arr = get_imagelist('prod',$product_array['product_id'],$pass_type,0,0,1);
															if(count($img_arr))
															{
																show_image(url_root_image($img_arr[0][$pass_type],1),$product_array['product_name'],$product_array['product_name']);
															}
															else
															{
																// calling the function to get the default image
																$no_img = get_noimage('prod',$pass_type); 
																if ($no_img)
																{
																	show_image($no_img,$product_array['product_name'],$product_array['product_name']);
																}	
															}	
														?>
														</a>
												
											</li>
											<?php
											$module_name = 'mod_product_reviews';
											if(in_array($module_name,$inlineSiteComponents))
											{
													if($product_array['product_averagerating']>=0)
													{
													?>
														<li class="shlf_star">
															<?php
															display_rating($product_array['product_averagerating']);
															?>
														</li>
													<?php
													}
											}	
											if($product_array['product_freedelivery']==1 || $product_array['product_bulkdiscount_allowed']=='Y')
											{
												?>
												<li class="shlf_sale">
													<?php
													if($product_array['product_bulkdiscount_allowed']=='Y')
													{	
														?>
														<img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/>
														<?php
													}
													if($product_array['product_freedelivery']==1){
														?>
														<img src="<?php url_site_image('free-img.gif')?>" alt="Free Delivery"/>
														<?
													}
													?>
												</li>
												<?
											}
										?>
										<li><h6 class="shelfAproddes"><?php echo stripslashes($product_array['product_shortdesc'])?></h6></li>
								</ul>
									<?php show_bonus_points_msg($product_array,'bonus_point'); // Show bonus points
									 if($comp_active)  {
										dislplayCompareButton($product_array['product_id']);
										}
										if($product_array['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($product_array['product_saleicon_text']));
											if($desc!='')
											{
												?>	
												<div class="shlf_sale_sale"><?php echo $desc?></div>
												<?php
											}
											}
											if($product_array['product_newicon_show']==1)
											{
											$desc = stripslash_normal(trim($product_array['product_newicon_text']));
											if($desc!='')
											{
												?>
												<div class="shlf_sale_new"><?php echo $desc?></div>
												<?php
											}
										}
									   $frm_name = uniqid('mafavhome_');
										?>				
										<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $product_array['product_id']?>)">
										<input type="hidden" name="fpurpose" value="" />
										<input type="hidden" name="fproduct_id" value="" />
										<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
										<input type="hidden" name="fproduct_url" value="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" />
										<div class="buy_main">
											<div class="shlf_buy_btn">
											<div class="shlf_buy_btnleft">
											<?php
											$class_arr 							= array();
											$class_arr['ADD_TO_CART']	= 'link_buy';
											$class_arr['PREORDER']			= 'link_buy';
											$class_arr['ENQUIRE']			= 'link_buy';
											show_addtocart($product_array,$class_arr,$frm_name,false,'','','add_divlink',0)
											?>
											</div>
											</div>      
											<div class="shlf_info_btn"><div class="shlf_info_btnleft">
											<?php show_moreinfo($product_array,'info_buy')?>
											</div>
											</div>   
										</div>
										</form>
										</td>
											<?php
														if($cur_col>=$max_col)
														{
														$cur_col =0;
														echo "</tr>";
														
														}

														// done to handle the case of breaking to new linel
												}
											// If in case total product is less than the max allowed per row then handle that situation
								                	if($cur_col<$max_col)
													{
													  if($cur_col!=0)
														{ 
															$cur_td=0;
															echo "<td colspan='".($max_col-$cur_col)."'>&nbsp;</td>";
															echo "</tr>";
														} 
													}
				?>
		
			   <?
			  
				}//End the prod checking
			
	            ?>
				</table>
				<? 
				break;
				}//end of switchcase
		}
	};	
?>