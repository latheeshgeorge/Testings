<?php
/*############################################################################
	# Script Name 	: myfavoritesHtml.php
	# Description 	: Page which holds the display logic for listing my favorite categories and products
	# Coded by 		: ANU
	# Created on	: 02-Feb-2008
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class myhome_Html
	{
		// Defining function to show the site review
		function Show_MyhomeFavoriteCategories($ret_favcat,$tot_cntcateg,$start_varcateg,$pg_variablecateg)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$customer_id,$ids;
			$sql_last_login = "SELECT customer_last_login_date FROM customers WHERE sites_site_id=$ecom_siteid AND customer_id=$customer_id";
			$ret_last_login = $db->query($sql_last_login);
			list($row_last_login) = $db->fetch_array($ret_last_login);
		
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
		?>
		<?php
		while($row_favcat = $db->fetch_array($ret_favcat)) 
		{
			$prodcur_arr =array();
			$limit			= $Settings_arr['product_limit_homepage_favcat_recent'];
			if($limit==0)
			$limit = 3;
			//Taking the New products added in the category after customer's last login	
			$sql_prod_first = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
												a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
												a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
												product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints ,
												a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists     
											FROM 
												products a,product_category_map b 
											WHERE 
												b.product_categories_category_id = ".$row_favcat['category_id']." 
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
													a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists ,
													CASE product_discount_enteredasval
														WHEN  '0'
															THEN (product_webprice * product_discount /100)
														WHEN  '1'
															THEN product_discount
														WHEN  '2'
															THEN (product_webprice-product_discount)
														END  AS discountval
													FROM  
															products a,product_category_map b  
													WHERE 
															sites_site_id =$ecom_siteid
															AND product_discount >0 
															AND b.product_categories_category_id = ".$row_favcat['category_id']." 
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
												a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists       
											FROM 
												products a,product_category_map b 
											WHERE 
												b.product_categories_category_id = ".$row_favcat['category_id']." 
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
			
				if(count($prodcur_arr))
				{
				?>
			<div class="mid_shelfB_name"><a href="<?php url_category($row_favcat['category_id'],$row_favcat['category_name'],-1,$_REQUEST['catgroup_id'],0)?>" class="categoreyname_headerlink" title="<?php echo stripslashes($row_favcat['category_name'])?>"><?php echo stripslashes($row_favcat['category_name'])?></a></div>
				
				<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA">
				<tr>
			  <?
				$max_col = 2;
				$cur_col = 0;
				//$prodcur_arr = array();
				
				foreach( $prodcur_arr as $k=>$product_array)
				{
				$cur_col++;
				 $prodcurtd_arr[] = $product_array;
				/*while($row_prod = $db->fetch_array($ret_prod))
				{
					$prodcur_arr[] = $row_prod;*/
					//##############################################################
					// Showing the title, description and image part for the product
					//##############################################################
					          if($cur_col%2==0)
									{
									$cls ='mid_shelfA_right'; 
									}
									else
									$cls ='mid_shelfA_left'; 
			?>
					           <td class="<?=$cls?>">
									<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA_table">
										<tr>
											<td class="mid_shelfA_top_lf">&nbsp;</td>
											<td class="mid_shelfA_top_mid"><a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>" class="prod_infolink"s><?php echo stripslashes($product_array['product_name'])?></a></td>
											<td class="mid_shelfA_top_rt">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" class="mid_shelfA_mid" align="center">
											<ul class="shelfAul">
											<li class="shelfAimg">
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
																				<?php if($comp_active)  {
														dislplayCompareButton($row_prod['product_id']);
																}?>
											
											</li>
											<li><h6 class="shelfAproddes"><?php echo stripslashes($product_array['product_shortdesc'])?></h6></li>                     
											<?php
											$price_class_arr['ul_class'] 		= 'shelfBul';
											$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
											$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
											$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
											$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
											echo show_Price($product_array,$price_class_arr,'shelfcenter_3');
											show_excluding_vat_msg($product_array,'vat_div');// show excluding VAT msg
										?>
											</ul>	
											</td>
										</tr>
										<tr>
										<td class="mid_shelfA_btm_lf">&nbsp;</td>
										<?php $frm_name = uniqid('mafavhome_'); ?>
										<td class="mid_shelfA_btm_mid">
										
								<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $product_array['product_id']?>)">
								<input type="hidden" name="fpurpose" value="" />
								<input type="hidden" name="fproduct_id" value="" />
								<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
								<input type="hidden" name="fproduct_url" value="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" />
										<div class="infodiv_shlfA">
										<div class="infodivleft_shlfA"><?php show_moreinfo($product_array,'infolink')?></div>
										<div class="infodivright_shlfA">
										<?php
										$class_arr 					= array();
										$class_arr['ADD_TO_CART']	= 'quantity_infolink';
										$class_arr['PREORDER']		= 'quantity_infolink';
										$class_arr['ENQUIRE']		= 'quantity_infolink';
										show_addtocart($product_array,$class_arr,$frm_name)
									?></div>
										</div></form></td>
										<td class="mid_shelfA_btm_rt">&nbsp;</td>
										</tr>
									</table>
									</td>
				<?php
									if ($cur_col>=$max_col)
									{
									echo "</tr>";
									//##############################################################
									// Showing the more info and add to cart links after each row in 
									// case of breaking to new row while looping
									//##############################################################
									echo "<tr>";
									$cur_col=0;
									}
				//}
				}//End For
			
				// If in case total product is less than the max allowed per row then handle that situation
				$prodcurtd_arr = array();
				?>
				</table>

				<div align="right"><h6 align="right"><a href="<?php  url_category_all($row_favcat['category_id'],$row_favcat['category_name'],-1)?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><? echo $Captions_arr['COMMON']['SHOW_ALL']?></a></h6></div>
		
			   <?
				}//End the prod checking
			
			}//Endwhile
		return $ids_in;
	}

/////////////**********TO DISPLAY FAVORITE PRODUCTS**********///////////////
		// ** Function to list the products
		function Show_MyhomeFavoriteProducts($ret_fav_products,$tot_cntprod,$start_varprod,$pg_variableprod)
		{
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$ids_in;
		
		?>
					<div class="mid_shelfB_name"> <?php
				  	if ($db->num_rows($ret_fav_products)==1)
						echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCT_HEADER'];
					else
						echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCTS_HEADER'];
				  ?></div>
				<?php
						// ** Showing the products listing either 1 in a row or 3 in a row
						//$pg_variable = 'catdet_pg';
					$displaytype = $Settings_arr['favorite_prodlisting'];
						switch($displaytype)
						{
							case '1row': // case of one in a row for normal
							?>
								<?php
									if ($tot_cntprod>0)
									{
									?>
										<div  class="pagingcontainertd" align="center">
											<?php 
												$path = '';
												$pageclass_arr['container'] = 'pagenavcontainer';
												$pageclass_arr['navvul']	= 'pagenavul';
												$pageclass_arr['current']	= 'pagenav_current';
												
												//$query_string = "&amp;catdet_sortby=".$_REQUEST['catdet_sortby'].'&amp;catdet_sortorder='.$_REQUEST['catdet_sortorder'].'&amp;catdet_prodperpage='.$_REQUEST['catdet_prodperpage'];
												$query_string .= '&categ_pg='.$_REQUEST['categ_pg'];
												//paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
												paging_footer($path,$query_string,$tot_cntprod,$start_varprod['pg'],$start_varprod['pages'],'',$pg_variableprod,'Favourite products',$pageclass_arr); 

											?>	
											</div>
											<?php
									}
									$max_col = 2;
									$cur_col = 0;
									$prodcur_arr = array();
									while($row_prod = $db->fetch_array($ret_fav_products))
									{
									$prodcur_arr[] = $row_prod;
									$ids_in .= ",".$row_prod['product_id'];
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
																
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
													<?php
														// Calling the function to get the type of image to shown for current 
														$pass_type = get_default_imagetype('fav_prod');
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
												$frm_name = uniqid('mafavhome_');
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
																	$class_arr['PREORDER']			= 'quantity_infolink';
																	$class_arr['ENQUIRE']			= 'quantity_infolink';
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
							case '2row': // case of three in a row for normal
							?>
																<?php
								if ($tot_cntprod>0)
								{
								?>
									
									<div class="pagingcontainertd" align="center">
										<?php 
											$path = '';
											//show_paging($path,$_REQUEST[$pg_variable],$start_arr['counterstart'],$tot_cnt,$prodperpage,$pg_variable,'')
												$pageclass_arr['container'] = 'pagenavcontainer';
												$pageclass_arr['navvul']	= 'pagenavul';
												$pageclass_arr['current']	= 'pagenav_current';
												$query_string .= '&categ_pg='.$_REQUEST['categ_pg'];
												//$query_string = "&amp;catdet_sortby=".$_REQUEST['catdet_sortby'].'&amp;catdet_sortorder='.$_REQUEST['catdet_sortorder'].'&amp;catdet_prodperpage='.$_REQUEST['catdet_prodperpage'];
												//paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
												paging_footer($path,$query_string,$tot_cntprod,$start_varprod['pg'],$start_varprod['pages'],'',$pg_variableprod,'Favourite products',$pageclass_arr); 

										?>	
										</div>
								<?php
								}
								?>	
								<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA">
									<tr>
								<?php
									$max_col = 2;
									$cur_col = 0;
									$prodcur_arr = array();
									
									while($row_prod = $db->fetch_array($ret_fav_products))
									{
										$prodcur_arr[] = $row_prod;
										$cur_col++;
									//##############################################################
									// Showing the title, description and image part for the product
									//##############################################################
									if($cur_col%2==0)
									{
									$cls ='mid_shelfA_right'; 
									}
									else
									$cls ='mid_shelfA_left'; 
									$ids_in .= ",".$row_prod['product_id'];
									//##############################################################
									// Showing the title, description and image part for the product
									//##############################################################
								?>  
								<td class="<?=$cls?>">
									<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA_table">
										<tr>
											<td class="mid_shelfA_top_lf">&nbsp;</td>
											<td class="mid_shelfA_top_mid"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a></td>
											<td class="mid_shelfA_top_rt">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" class="mid_shelfA_mid"  align="center">
											<ul class="shelfAul">
											<li class="shelfAimg">
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
													<?php
														// Calling the function to get the type of image to shown for current 
														$pass_type = get_default_imagetype('fav_prod');
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
													?></a>
											
											</li>
											<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>                     
											<?php
													$price_class_arr['ul_class'] 		= 'shelfBul';
													$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
												?>	
											</ul>	
											</td>
										</tr>
										<tr>
										<td class="mid_shelfA_btm_lf">&nbsp;</td>
										<?php $frm_name = uniqid('catdet_'); ?>
										<td class="mid_shelfA_btm_mid">
										
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
										if($tot_cntprod==$cur_col)
										{ 
											echo "</tr>";
										}
										else
										{
											if ($cur_col%2==0)
											{
											echo "</tr>";
											//##############################################################
											// Showing the more info and add to cart links after each row in 
											// case of breaking to new row while looping
											//##############################################################
											echo "<tr>";
											}
										}	
									}
									// If in case total product is less than the max allowed per row then handle that situation
									
									$prodcur_arr = array();
									?>
									</table>
						<?php		
							break;
						};
				?>
	<?	}
		
function Show_MyhomePurcahaseProducts($ret_purchase)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents;
		   $tot_cntprod = $db->num_rows($ret_purchase);
		   if($db->num_rows($ret_purchase)>0)
			{
			?>
				<div class="mid_shelfB_name"><?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></div>
			    <table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA">
										
						<tr>				<?php
											$max_col = 2;
											$cur_col = 0;
											$prodcur_arr = array();
											while($row_prod = $db->fetch_array($ret_purchase))
											{
												$cur_col++;
												$prodcur_arr[] = $row_prod;
												//##############################################################
												// Showing the title, description and image part for the product
												//##############################################################
											?>	
										<?
									if($cur_col%2==0)
									{
									$cls ='mid_shelfA_right'; 
									}
									else
									$cls ='mid_shelfA_left'; 
									?>
						  <td class="<?=$cls?>">
									<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfA_table">
										<tr>
											<td class="mid_shelfA_top_lf">&nbsp;</td>
											<td class="mid_shelfA_top_mid"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_prod['product_name'])?></a></td>
											<td class="mid_shelfA_top_rt">&nbsp;</td>
										</tr>
										<tr>
											<td colspan="3" class="mid_shelfA_mid" align="center">
											<ul class="shelfAul">
											<li class="shelfAimg">
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
										<?php
											// Calling the function to get the type of image to shown for current 
											$pass_type = get_default_imagetype('fav_prod');
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
											<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>                     
											<?php
											$price_class_arr['ul_class'] 		= 'shelfBul';
											$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
											$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
											$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
											$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
											echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
											show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
										?>
											</ul>	
											</td>
										</tr>
										<tr>
										<td class="mid_shelfA_btm_lf">&nbsp;</td>
										<?php $frm_name = uniqid('mafavhome_'); ?>
										<td class="mid_shelfA_btm_mid">
										
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
									<?php
									if($tot_cntprod==$cur_col)
									{ 
									echo "</tr>";
									}
									else
									{
									if ($cur_col%2==0)
									{
									echo "</tr>";
									//##############################################################
									// Showing the more info and add to cart links after each row in 
									// case of breaking to new row while looping
									//##############################################################
									echo "<tr>";
									}
									}	
										
									}
									?>
									</table>
									<?php
											$prodcur_arr = array();
											?>
													<div align="right"><h6 align="right"><a href="http://<?=$ecom_hostname?>/showpurchaseall.html" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><? echo $Captions_arr['COMMON']['SHOW_ALL']?></a></h6></div>

			<?
			 }//end Checking
         }

///////////*************END FAVORITE PRODUCTS**************//////////////////

		function Display_WelcomeMessage($mesgHeader,$Message){
			global $Captions_arr,$ecom_hostname,$db,$ecom_siteid;
			$Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME');
		$sql_user = "SELECT customer_fname,customer_discount,customer_allow_product_discount FROM customers where customer_id = ".get_session_var("ecom_login_customer")." LIMIT 1";
		$ret_user = $db->query($sql_user);
		list($username,$customer_discount,$allow_discount) = $db->fetch_array($ret_user);
		?>
	<div class="tree_con">
      <div class="tree_top"></div>
      <div class="tree_middle">
        <div class="pro_det_treemenu">
          <ul>
            <li><a href="<? url_link('')?>">Home</a>  &gt;&gt; </li>
            <li> <?php echo  $Captions_arr['LOGIN_HOME']['LOGIN_HOME_TREE_MENU']?></li>
          </ul>
        </div>
      </div>
      <div class="tree_bottom"></div>
    </div>
	<div class="round_con">
		<div class="round_top"></div>
		<div class="round_middle">
     <div class="loginwelcomemsg_header" > 
       	 <?php echo  str_replace("[username]",$username,$Captions_arr['LOGIN_HOME']['LOGIN_HOME_HEADER_MESG']);?></div>
	  <?php
	  	if($Captions_arr['LOGIN_HOME']['LOGIN_HOME_MESG_TEXT']!='')
		{
	  ?>
		 <div class="loginwelcomemsg_text"> <?php echo  str_replace("[username]",$username,$Captions_arr['LOGIN_HOME']['LOGIN_HOME_MESG_TEXT']);?></div>
	  <?php
	  	}
		$cnt = 0;
			 $sql_assigned="SELECT 
			  							customer_discount_group_cust_disc_grp_id 
									FROM 
										customer_discount_customers_map 
									WHERE 
										sites_site_id = ".$ecom_siteid." 
									AND 
										customers_customer_id=".get_session_var("ecom_login_customer")."";
			$ret_assigned = $db->query($sql_assigned);
			
			$num_assigned = $db->num_rows($ret_assigned); // To get Number OF Rows
      		if($num_assigned>0) 
			{
				while($row_assigned =$db->fetch_array($ret_assigned))
				{ 
				$cnt ++;
				$group_id = $row_assigned['customer_discount_group_cust_disc_grp_id'];
				if($group_id)
				{
					$sql_discount = "SELECT 
											cust_disc_grp_discount,cust_disc_display_category_in_myhome  
										FROM 
											customer_discount_group 
										WHERE 
											cust_disc_grp_id=".$group_id." AND cust_disc_grp_active=1 LIMIT 1";
					$ret_discount = $db->query($sql_discount);
					$row_discount = $db->fetch_array($ret_discount);
					$sql_products_id = "SELECT 
											DISTINCT pc.products_product_id,p.product_id,p.product_name,p.product_shortdesc,p.product_webprice,
											p.product_discount,p.product_discount_enteredasval,p.product_bulkdiscount_allowed,p.product_variables_exists,p.product_variablesaddonprice_exists    
										FROM 
											customer_discount_group_products_map pc,products p 
										WHERE 
											pc.customer_discount_group_cust_disc_grp_id=".$group_id." 
										 AND p.product_hide='N' AND pc.products_product_id=p.product_id 
										 AND p.sites_site_id = $ecom_siteid ";
					$ret_products_id = $db->query($sql_products_id);
					}
					$flag=1;
					if($db->num_rows($ret_products_id)>0)
					{
						if($allow_discount==1 && $row_discount['cust_disc_grp_discount']>0 && $customer_discount>0)
						{
							$flag=2;
						}
					}
					else
					{
					 if($row_discount['cust_disc_grp_discount']>0 )
						$flag=2;
					 elseif($customer_discount>0)
					 {
						$flag=2;
					 }
					}
					if($cnt==1)
					{
						if($flag==2)
						{
						?>
						
					<div class="logindetailheader" align="left" ><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_DISC_DETAILS']?></div>
						
						<?
						}
					}
					if($db->num_rows($ret_products_id )>0 || !$group_id || $row_discount['cust_disc_grp_discount']==0)
					{
					 if($cnt==1)
					 {		
						 if($customer_discount>0)
						 {
						 ?> 
						<div class="loginwelcomemsg_text_left" ><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_NORMAL_DISC']?></div><div class="loginwelcomemsg_text_right">:&nbsp;<?=$customer_discount.'%'?></div>
						  <?
						  }
					  }  
					 }
					 elseif($db->num_rows($ret_products_id )==0 && $group_id)
					 {	
						if($row_discount['cust_disc_grp_discount']>0)
						{
							?>
							<div class="loginwelcomemsg_text_left"><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_DISC']?></div><div class="loginwelcomemsg_text_right">:&nbsp;<?=$row_discount['cust_disc_grp_discount'].'%'?></div>
							<?
						 }
					 }	 
					if($row_discount['cust_disc_grp_discount']>0)
					{   
						$Cnt_prd=0;
						if($db->num_rows($ret_products_id )>0)
						{
							if($allow_discount==1)
							{
								if($row_discount['cust_disc_display_category_in_myhome']==1) // case if categories assigned to discount group should be displayed
								{
									$homemsg = str_replace("[value]", '<strong>'.$row_discount['cust_disc_grp_discount'].'%</strong>', $Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_CATEGORIES']);
									$sql_cats = "SELECT a.category_id,a.category_name   
													FROM 
														product_categories a,customer_discount_group_categories_map b 
													WHERE 
														b.customer_discount_group_cust_disc_grp_id = $group_id 
														AND a.category_id = b.product_categories_category_id 
														AND a.category_hide=0 
														AND a.sites_site_id=$ecom_siteid ";
									$ret_subcat = $db->query($sql_cats);
										$max_col = 3;
										$cur_col = 0;
										if ($db->num_rows($ret_subcat))
										{
								?>
											<div class="logindiscountmsg_text"  colspan="3"><?=$homemsg?></div>
											<table width="100%" border="0" cellpadding="0" cellspacing="0" class="subcategoreytable">
								<?php	
										
										while ($row_subcat = $db->fetch_array($ret_subcat))
										{
											if ($cur_col==0)
												echo '<tr>';
											$cur_col++;
								?>
											<td width="33%" align="center" valign="middle" class="subcategoreyimage" onmouseover="this.className='subcategory_hover'" onmouseout="this.className='subcategoreyimage'">
											<div class="subcate_div_image">
											<form method="post" name="frm_subcatedetails_<?=$row_subcat['category_id']?>" id="frm_subcatedetails_<?=$row_subcat['category_id']?>" action="" class="frm_cls">
											<input type="hidden" name="caturl" value="<? echo $url;?>" />
											<input type="hidden" name="type_cat" value="sub_cat" />
											<input type="hidden" name="sub_category_id" value="<? echo $row_subcat['category_id'];?>" />
											<input type="hidden" name="fpurpose" value="" />
											<input type='hidden' name='category_id' value="<?=$_REQUEST['category_id']?>"/>
											<a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1,$_REQUEST['catgroup_id'],0)?>" class="subcategoreyimage" title="<?php echo stripslashes($row_subcat['category_name'])?>">
											<?php
											$pass_type = 'image_thumbpath';
											
											if ($row_subcat['category_showimageofproduct']==0) // Case to check for images directly assigned to category
											{
											// Calling the function to get the image to be shown
											$img_arr = get_imagelist('prodcat',$row_subcat['category_id'],$pass_type,0,0,1);
												if(count($img_arr))
												{
													show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_subcat['category_name']);
													$show_noimage = false;
												}
												else
													$show_noimage = true;
											}
											else // Case of check for the first available image of any of the products under this category
											{
												// Calling the function to get the id of products under current category with image assigned to it
												$cur_prodid = find_AnyProductWithImageUnderCategory($row_subcat['category_id']);
												if ($cur_prodid)// case if any product with image assigned to it under current category exists
												{
												// Calling the function to get the image to be shown
												$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
												
												if(count($img_arr))
												{
												show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_cat['category_name']);
												$show_noimage = false;
												}
												else 
												$show_noimage = true;
												
												}
												else// case if no products exists under current category with image assigned to it
												$show_noimage = true;
											}
											
											// ** Following section makes the decision whether the no image is to be displayed
											if ($show_noimage)
											{
												// calling the function to get the default no image 
												$no_img = get_noimage('prodcat',$pass_type); 
												if ($no_img)
												{
													show_image($no_img,$row_subcat['category_name'],$row_subcat['category_name']);
												}	
											}
											?>
											</a>
												<a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1)?>" class="subcategoreynamelink" title="<?php echo stripslashes($row_subcat['category_name'])?>"><?php echo stripslashes($row_subcat['category_name'])?></a><br /><br />
												( <?php echo $row_discount['cust_disc_grp_discount'];?>% Off )
											</form>
											</div></td>		
								<?php			
											if($cur_col>=$max_col)
											{
												echo '</tr>';
												$cur_col = 0;
											}	
										}
											if($cur_col<$max_col and $cur_col>0)
												echo '</tr>';
								?>
										</table>
								<?php
										}
									}
									else
									{
									 $homemsg = str_replace("[value]", '<strong>'.$row_discount['cust_disc_grp_discount'].'</strong>%', $Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_PRODUCTS']);
						 ?>
							<div class="logindiscountmsg_text"><?=$homemsg?></div>
							<?
								$ids = array();
								while($row_products_id = $db->fetch_array($ret_products_id))
								{
									//sunil
									$ids[] = $row_products_id['product_id'];
								?>
										<div class="mid_shelfB">
									<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mid_shelfB_table">
									
									<tr>
										<td class="mid_shelfB_top_lf">&nbsp;</td>
										<td class="mid_shelfB_top_mid"><a href="<?php url_product($row_products_id['product_id'],$row_products_id['product_name'],-1)?>" title="<?php echo stripslashes($row_products_id['product_name'])?>" class="prod_infolink"><?php echo stripslashes($row_products_id['product_name'])?></a></td>
										<td class="mid_shelfB_top_rt">&nbsp;</td>
									</tr>
									<tr>
											<td colspan="3" class="mid_shelfB_mid">
											<div class="shelfBimg">
													
													<a href="<?php url_product($row_products_id['product_id'],$row_products_id['product_name'],-1)?>" title="<?php echo stripslashes($row_products_id['product_name'])?>" >
													<?php
														// Calling the function to get the type of image to shown for current 
														$pass_type = get_default_imagetype('fav_prod');
														// Calling the function to get the image to be shown
														$img_arr = get_imagelist('prod',$row_products_id['product_id'],$pass_type,0,0,1);
														if(count($img_arr))
														{
															show_image(url_root_image($img_arr[0][$pass_type],1),$row_products_id['product_name'],$row_products_id['product_name']);
														}
														else
														{
															// calling the function to get the default image
															$no_img = get_noimage('prod',$pass_type); 
															if ($no_img)
															{
																show_image($no_img,$row_products_id['product_name'],$row_products_id['product_name']);
															}	
														}	
													?>
													</a>
												<?php if(isProductCompareEnabled())  {
													dislplayCompareButton($row_products_id['product_id']);
												}?>
											</div> 
											<div class="shelfB_cnts">
														 
														<h6 class="shelfBproddes"><?php echo stripslashes($row_products_id['product_shortdesc'])?></h6>
													<ul class="shelfBul">
													<?php
												
													$price_class_arr['ul_class'] 		= 'shelfBul';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													echo show_Price($row_products_id,$price_class_arr,'shelfcenter_1');
													$frm_name = uniqid('mafavhome_');
											?>	
													
													</ul>	
										   </div>
										  </td>
									</tr>
									<tr>
									<td class="mid_shelfB_btm_lf">&nbsp;</td>
									<td class="mid_shelfB_btm_mid">
										<?php
										$frm_name = uniqid('mafavhome_');
										?>	
											<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_products_id['product_id']?>)">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													<input type="hidden" name="fproduct_url" value="<?php url_product($row_products_id['product_id'],$row_products_id['product_name'])?>" />
		
											<div class="infodiv">
												<div class="infodivleft">		<?php show_moreinfo($row_products_id,'infolink')?>	</div>
												<div class="infodivright"><?php
															$class_arr 					= array();
															$class_arr['ADD_TO_CART']	= 'quantity_infolink';
															$class_arr['PREORDER']		= 'quantity_infolink';
															$class_arr['ENQUIRE']		= 'quantity_infolink';
															show_addtocart($row_products_id,$class_arr,$frm_name)
														?> </div>
											</div>
											</form>
									  </td>
									<td class="mid_shelfB_btm_rt">&nbsp;</td>
									</tr>
									</table>
									
							</div>
								  <?
								   }
								} 
							  }
						}
					}
					}
				}
				else
				{
					 if($customer_discount>0)
					 {
						 ?>
						  <div class="loginwelcomemsg_text" ><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_NORMAL_DISC']?></div><div class="loginwelcomemsg_text">:&nbsp;<?=$customer_discount.'%'?></div>
						 <?
					 }
				}
			  ?>
			  </div>
		<div class="round_bottom"></div>
		</div>
		<?php
			return $ids;	
		}
		function Display_Message($mesgHeader,$Message){
		
		?>
		<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
      <tr>
        <td width="7%" align="left" valign="middle" class="message_header" > 
         <?php echo $mesgHeader;?></td>
      
      </tr>
      <tr>
        <td align="left" valign="middle" class="message"><?php echo $Message; ?></td>
        
      </tr>
        </table>
		<?php	
		}
		
	};	
?>
			