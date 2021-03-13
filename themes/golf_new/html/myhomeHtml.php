<?php
/*############################################################################
	# Script Name 	: myhomeHtml.php
	# Description 	: Page which holds the display logic for listing my favorite categories and products
	# Coded by 		: Sny
	# Created on	: 21-Sep-2009
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class myhome_Html
	{
		function Display_WelcomeMessage($mesgHeader,$Message)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
			$sql_user = "SELECT customer_fname,customer_discount,customer_allow_product_discount FROM customers where customer_id = ".get_session_var("ecom_login_customer")." LIMIT 1";
			$ret_user = $db->query($sql_user);
			list($username,$customer_discount,$allow_discount) = $db->fetch_array($ret_user);
			$Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME');
			$cats_arr = $prod_arr = array();
			$pass_type = 'image_thumbpath';
			$comp_active = isProductCompareEnabled();
			
		?> 	
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_TREE_MENU']?></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="loginwelcomemsg_table">
			  <tr>
				<td align="left" valign="middle" class="loginwelcomemsg_header" > 
				 <?php $str = str_replace("[username]",$username,$Captions_arr['LOGIN_HOME']['LOGIN_HOME_HEADER_MESG']);
						echo stripslash_normal($str);
				 ?>
				 </td>
			  </tr>
			  <?php
				if($Captions_arr['LOGIN_HOME']['LOGIN_HOME_MESG_TEXT']!='')
				{
			  ?>
				  <tr>
					<td align="left" valign="middle" class="loginwelcomemsg_text"> <?php $str = str_replace("[username]",$username,$Captions_arr['LOGIN_HOME']['LOGIN_HOME_MESG_TEXT']); echo stripslash_normal($str);?></td>
				  </tr>
			  <?php
				}
				?>
				<tr>
				<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="loginwelcomemsg_table">
				<?
				$cnt = 0;
				 $sql_assigned = "SELECT 	customer_discount_group_cust_disc_grp_id 
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
													DISTINCT pc.products_product_id,p.product_id,p.product_name,p.product_variablestock_allowed,p.product_show_cartlink,
														p.product_preorder_allowed,p.product_show_enquirelink,p.product_webstock,p.product_webprice,
														p.product_discount,p.product_discount_enteredasval,p.product_bulkdiscount_allowed,
														p.product_total_preorder_allowed,p.product_applytax,p.product_shortdesc,
														p.product_stock_notification_required,p.product_alloworder_notinstock,
														p.product_variables_exists,p.product_variablesaddonprice_exists,p.product_freedelivery,
														p.product_show_pricepromise, p.product_saleicon_show,p.product_saleicon_text,
														p.product_newicon_show, p.product_newicon_text,p.product_averagerating      
												FROM 
													customer_discount_group_products_map pc,products p 
												WHERE 
													pc.customer_discount_group_cust_disc_grp_id=".$group_id." 
												 AND p.product_hide='N' AND pc.products_product_id=p.product_id 
												 AND p.sites_site_id=$ecom_siteid ";
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
								<tr>
									<td class="logindetailheader" align="left" colspan="2"><?=stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_DISC_DETAILS'])?></td>
								</tr>
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
									  <tr>
										  <td align="left" valign="middle" class="loginwelcomemsg_text" width="40%"><?=stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_NORMAL_DISC'])?></td><td align="left" valign="middle" class="loginwelcomemsg_text">:&nbsp;<?=$customer_discount.'%'?></td>
									  </tr>
								  <?
								  }
							  }  
						 }
						 elseif($db->num_rows($ret_products_id )==0 && $group_id)
						 {	
							if($row_discount['cust_disc_grp_discount']>0)
							{
							?>
								<tr>
									<td align="left" valign="middle" class="loginwelcomemsg_text" width="40%"><?=stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_DISC'])?></td><td align="left" valign="middle" class="loginwelcomemsg_text">:&nbsp;<?=$row_discount['cust_disc_grp_discount'].'%'?></td>
								</tr>
							<?
							 }
						 }	 
						if($row_discount['cust_disc_grp_discount']>0)
						{   
							$Cnt_prd=0;
							?>
							<tr><td colspan="2">
							<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<? 
							if($db->num_rows($ret_products_id )>0)
							{
								if($allow_discount==1)
								{
									if($row_discount['cust_disc_display_category_in_myhome']==1) // case if categories assigned to discount group should be displayed
									{
										if(in_array('mod_catimage',$inlineSiteComponents))
										{
											$img_support = true;
										}
										else
											$img_support = false;
										$homemsg = str_replace("[value]", '<strong>'.$row_discount['cust_disc_grp_discount'].'</strong>%', $Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_CATEGORIES']);
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
											<tr>
												<td align="left" valign="middle" class="logindiscountmsg_text"  colspan="3"><?=stripslash_normal($homemsg)?></td>
											</tr>
										<?php
												$max_col = 3;
												$cur_col = 0;
												while ($row_subcat = $db->fetch_array($ret_subcat))
												{
													$cats_arr[] = $row_subcat['category_id'];
													if($cur_col==0)
														echo "<tr>";
											?>	
												<td width="33%" align="center" valign="middle" class="subcategoreyimage" onmouseover="this.className='subcategory_hover'" onmouseout="this.className='subcategoreyimage'">
												<form method="post" name="frm_subcatedetails_<?=$row_subcat['category_id']?>" id="frm_subcatedetails_<?=$row_subcat['category_id']?>" action="" class="frm_cls">
												<input type="hidden" name="caturl" value="<? echo $url;?>" />
												<input type="hidden" name="type_cat" value="sub_cat" />
												<input type="hidden" name="sub_category_id" value="<? echo $row_subcat['category_id'];?>" />
												<input type="hidden" name="fpurpose" value="" />
												<input type='hidden' name='category_id' value="<?=$_REQUEST['category_id']?>"/>
												 <a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1)?>" class="subcategoreynamelink" title="<?php echo stripslashes($row_subcat['category_name'])?>"><?php echo stripslashes($row_subcat['category_name'])?></a>
												<?php
													if($img_support) // ** Show sub category image only if catimage module is there for the site subcategoreynamelink
													{
												  ?>
														<a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1,$_REQUEST['catgroup_id'],0)?>"  title="<?php echo stripslashes($row_subcat['category_name'])?>">
												 <?php
														
														switch($subcategory_showimagetype)
															{
																case 'Default':
																	$pass_type = 'image_thumbpath';
																break;
																case 'Icon':
																	$pass_type = 'image_iconpath';
																break;
																case 'Thumb':
																	$pass_type = 'image_thumbpath';
																break;
																case 'Medium':
																	$pass_type = 'image_thumbcategorypath';
																break;
																case 'Big':
																	$pass_type = 'image_bigpath';
																break;
																case 'Extra':
																	$pass_type = 'image_extralargepath';
																break;
																default:
																  $pass_type = 'image_thumbpath';
																break;
															};
													
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
												  <?php
													}
													?>
																<h6 class="shelfBproddes">
															<?php	
																echo  stripslashes($row_subcat['category_shortdescription']);
															?>
															</h6>
												</form>
												</td>	
											<?php
													$cur_col++;
													if ($cur_col>=$max_col)
													{
														$cur_col = 0;
														echo "</tr>";
													}
												}
												if ($cur_col<$max_col && $cur_col>0)
													echo '<td colspan="'.($max_col-$cur_col).'" class="subcategoreyimage">&nbsp;</td></tr>';
										}	
									}
									else // case if products assigned to discount group is to be displayed
									{
										$ids = array();
										$homemsg = str_replace("[value]", '<strong>'.$row_discount['cust_disc_grp_discount'].'</strong>%', $Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_PRODUCTS']);
										$pass_type = 'image_thumbpath';
									?>
									<tr>
										<td align="left" valign="middle" class="logindiscountmsg_text"><?=stripslash_normal($homemsg)?></td>
									</tr>
									 <tr>
										<td>
										<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
										<?php	
										$max_col = 3;
										$cur_col = 0;
										while($row_prod = $db->fetch_array($ret_products_id))
										{
											$prod_arr[] = $row_prod['product_id'];
											if($cur_col == 0)
											{
											  echo "<tr>";
											}
											$cur_col ++;
										?>
											<td class="newshelfAtabletd" onmouseover="this.className='newshelfAtabletd_hover'" onmouseout="this.className='newshelfAtabletd'" align="left" valign="top">												
											<ul class="shelfAul">
											<li><h2 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2></li>
											<li>
												<?php
													$price_class_arr['ul_class'] 		= 'shelfBul';
													$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
												?>	
											</li>
											<li class="shelfimg">
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
											</li>
											<?php
												$module_name = 'mod_product_reviews';
													if(in_array($module_name,$inlineSiteComponents))
													{
														if($row_prod['product_averagerating']>=0)
														{
														?>
															<li class="shlf_star">
																<?php
																display_rating($row_prod['product_averagerating']);
																?>
															</li>
														<?php
														}
												}	
												if($row_prod['product_freedelivery']==1 || $row_prod['product_bulkdiscount_allowed']=='Y')
												{
													?>
													<li class="shlf_sale">
														<?php
														if($row_prod['product_bulkdiscount_allowed']=='Y')
														{	
															?>
															<img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/>
															<?php
														}
														if($row_prod['product_freedelivery']==1){
															?>
															<img src="<?php url_site_image('free-img.gif')?>" alt="Free Delivery"/>
															<?
														}
														?>
													</li>
													<?
												}
												?>
											<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
											</ul>
												<?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?>
									<?php
												$frm_name = uniqid('myhome_');
												if($comp_active) 
												{
													dislplayCompareButton($row_prod['product_id']);
												}
												if($row_prod['product_saleicon_show']==1)
												{
													$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
													if($desc!='')
													{
														?>	
														<div class="shlf_sale_sale"><?php echo $desc?></div>
														<?php
													}
													}
													if($row_prod['product_newicon_show']==1)
													{
													$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
													if($desc!='')
													{
														?>
														<div class="shlf_sale_new"><?php echo $desc?></div>
														<?php
													}
												}
										?>
													<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<div class="buy_main">
															<div class="shlf_buy_btn">
															<div class="shlf_buy_btnleft">
															<?php
															$class_arr 							= array();
															$class_arr['ADD_TO_CART']	= 'link_buy';
															$class_arr['PREORDER']			= 'link_buy';
															$class_arr['ENQUIRE']			= 'link_buy';
															show_addtocart($row_prod,$class_arr,$frm_name,false,'','','add_divlink',0)
															?>
															</div>
															</div>      
															<div class="shlf_info_btn"><div class="shlf_info_btnleft">
															<?php show_moreinfo($row_prod,'info_buy')?>
															</div>
															</div>   
														</div>
													</form>
													</td>
									<? 	
										if($cur_col>=$max_col)
										{
											$cur_col =0;
											echo "</tr>";
										
										}
									}
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
									</table>
									</td>
									</tr>
									<?php
									}
								}
							}
							?>
							</table>
							</td>
							</tr>
							<?
							}
						}
					}
				else
				{
				 if($customer_discount>0)
				 {
				 ?>
				  	<tr>
						<td align="left" valign="middle" class="loginwelcomemsg_text" width="40%"><?=stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_NORMAL_DISC'])?></td><td align="left" valign="middle" class="loginwelcomemsg_text">:&nbsp;<?=$customer_discount.'%'?></td>
				 	</tr>
				 <?
				  }
				}
				?>
				<tr>
				<td>&nbsp;</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
		<?php
			$used_val_arr['cats_arr'] = $cats_arr;
			$used_val_arr['prod_arr'] = $prod_arr;
			return $used_val_arr;	
		}
		/* Function to get the list of recently purchased products categories*/
		function Show_Recently_Purchased_Products_With_Categories($used_val_arr)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents;
			$custom_id	= get_session_var('ecom_login_customer');
			$cats_arr	= $used_val_arr['cats_arr'];
			$prod_arr	= $used_val_arr['prod_arr'];
			$Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME');
			$prod_limit			= $Settings_arr['product_limit_homepage_favcat_recent'];
			if($prod_limit<=0)
				$prod_limit 	= 3;
			if(count($cats_arr))
			{
				$exclude_cat_str = " AND a.product_default_category_id NOT IN (".implode(',',$cats_arr).")";
			}
			else
				$exclude_cat_str = '';	
			$cur_cat_arr= array();
			$max_cats	= 3; // The maximum number of categories to be picked from latest 3 orders
			// Get the last 3 orders for current customer
			$sql_ord = "SELECT order_id 
							FROM 
								orders 
							WHERE 
								customers_customer_id = $custom_id 
								AND sites_site_id = $ecom_siteid 
								AND order_status NOT IN ('CANCELLED','NOT_AUTH') 
								AND order_paystatus NOT IN ('Pay_Failed','REFUNDED')
							ORDER BY 
								order_id DESC 
							LIMIT 
								3";
			$ret_ord = $db->query($sql_ord);
			if($db->num_rows($ret_ord))
			{
				while ($row_ord = $db->fetch_array($ret_ord) and $break_while ==false)
				{
					$sql_orderdet = "SELECT DISTINCT product_default_category_id  
										FROM 
											products a, order_details b 
										WHERE 
											b.orders_order_id = ".$row_ord['order_id']." 
											AND a.product_id =b.products_product_id 
											AND a.product_hide='N' 
											$exclude_cat_str";
					$ret_orderdet = $db->query($sql_orderdet);
					if($db->num_rows($ret_orderdet))
					{
						while ($row_orderdet = $db->fetch_array($ret_orderdet) and $break_while ==false)
						{
							if (count($cur_cat_arr))
							{
								if(count($cats_arr))
								{
									if(!in_array($row_orderdet['product_default_category_id'],$cats_arr))
									{
										if(!in_array($row_orderdet['product_default_category_id'],$cur_cat_arr))
											$cur_cat_arr[] = $row_orderdet['product_default_category_id'];
									}		
								}
								else
								{
									if(!in_array($row_orderdet['product_default_category_id'],$cur_cat_arr))
										$cur_cat_arr[] = $row_orderdet['product_default_category_id'];		
								}	
							}
							else
								$cur_cat_arr[] = $row_orderdet['product_default_category_id'];
							if(count($cur_cat_arr)==$max_cats)
								$break_while = true;
						}	
					}						 
										
				}
			}
			$sql_last_login 		= "SELECT customer_last_login_date 
										FROM 
											customers 
										WHERE 
											sites_site_id=$ecom_siteid 
											AND customer_id=$custom_id";
			$ret_last_login 		= $db->query($sql_last_login);
			list($row_last_login) 	= $db->fetch_array($ret_last_login);
			if(count($cur_cat_arr))
			{
			?>
				<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
			<?php	
				for($i=0;$i<count($cur_cat_arr);$i++)
				{
					
					if(count($prod_arr)>0)
					{
						$exclude_prod_str = " AND product_id NOT IN (".implode(',',$prod_arr).")";
					}
					else
						$exclude_prod_str = '';
					$sql_cat = "SELECT category_name 
									FROM 
										product_categories 
									WHERE 
										category_id = ".$cur_cat_arr[$i]." 
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
					$ret_cat = $db->query($sql_cat);
					if($db->num_rows($ret_cat))
						$row_cat = $db->fetch_array($ret_cat);
					
					//*********************************************************
					// Get the list of products which have offers
					//*********************************************************
					$sql_prods_offers = "SELECT product_id,product_name,product_variablestock_allowed,product_show_cartlink,
												product_preorder_allowed,product_show_enquirelink,product_webstock,product_webprice,
												product_discount,product_discount_enteredasval,product_bulkdiscount_allowed,
												product_total_preorder_allowed,product_applytax,product_shortdesc,
												product_stock_notification_required,product_alloworder_notinstock,
												product_variables_exists,product_variablesaddonprice_exists,product_freedelivery,
												product_show_pricepromise,product_saleicon_show,product_saleicon_text,
												product_newicon_show,product_newicon_text,product_averagerating 
											FROM 
												products 
											WHERE 
												product_default_category_id=".$cur_cat_arr[$i]." 
												AND product_hide = 'N' 
												AND sites_site_id=$ecom_siteid 
												$exclude_prod_str 
											ORDER BY 
												product_webprice ASC 
											LIMIT 
												$prod_limit ";
					$ret_prods_offers = $db->query($sql_prods_offers);
						
					//*******************************************************************
					// Get the list of new products since last login in current category
					//*******************************************************************
					$sql_prods_new = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
												a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
												a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
												product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints ,
												a.product_stock_notification_required,a.product_alloworder_notinstock,
												a.product_variables_exists,a.product_variablesaddonprice_exists,a.product_freedelivery,
												a.product_show_pricepromise,a.product_saleicon_show,a.product_saleicon_text,
												a.product_newicon_show,a.product_newicon_text,a.product_averagerating     
											FROM 
												products a 
											WHERE 
												a.product_default_category_id = ".$cur_cat_arr[$i]." 
												AND a.product_hide = 'N'
												AND a.sites_site_id = $ecom_siteid 
												AND a.product_adddate >= '".$row_last_login."' 
												$exclude_prod_str 
											ORDER BY 
												a.product_webprice ASC 
											LIMIT 
												$prod_limit";
					$ret_prods_new = $db->query($sql_prods_new);
					if($db->num_rows($ret_prods_offers) or 	$db->num_rows($ret_prods_new))
					{
			?>
						<tr>
							<td colspan="3" class="shelfAheader" align="left"><?php echo stripslash_normal($row_cat['category_name'])?></td>
						</tr>
				<?php
						if($db->num_rows($ret_prods_offers))
						{
						?>
							<tr>
							<td align="left" class="myhome_offer_subtext" colspan="3"><?php echo str_replace('[category]',stripslash_normal($row_cat['category_name']),stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_RECENT_RECOMM_PROD']))?>
							</td>
							</tr>
							<tr>
							<td colspan="3">	
							<?php	
							//*********************************************************
							// Show the list of products which have offers
							//*********************************************************
							if($db->num_rows($ret_prods_offers))
							{
								$cats_arr[] = $cur_cat_arr[$i]; // assigning the used category id to the array
								$prod_arr	= $this->Show_Products($ret_prods_offers,$prod_arr);
							}	
							?>
							</td>
							</tr>
						<?php
						}
						if($db->num_rows($ret_prods_new))
						{
						?>
							
							<tr>
								<td align="left" class="myhome_offer_subtext" colspan="3"><?php echo str_replace('[category]',stripslash_normal($row_cat['category_name']),stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_RECENT_NEW_PROD']))?>
								</td>
							</tr>
							<tr>
							<td>
							<?php		
							//*******************************************************************
							// Show the list of new products since last login in current category
							//*******************************************************************
							if($db->num_rows($ret_prods_new))
							{
								$cats_arr[] = $cur_cat_arr[$i]; // assigning the used category id to the array
								$prod_arr	= $this->Show_Products($ret_prods_new,$prod_arr);
							}	
							?>
							</td>
							</tr>
						<?php	
						}							
					}
				}
			?>
				</table>
			<?php	
			}
			$used_val_arr['cats_arr'] = $cats_arr;
			$used_val_arr['prod_arr'] = $prod_arr;
			return $used_val_arr;	
		}
		
		function Show_Favourite_Categories($used_val_arr)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents;
			$custom_id	= get_session_var('ecom_login_customer');
			$cats_arr	= $used_val_arr['cats_arr'];
			$prod_arr	= $used_val_arr['prod_arr'];
			$Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME');
			if(count($cats_arr))
			{
				$exclude_cat_str = " AND pc.category_id NOT IN (".implode(',',$cats_arr).")";
			}
			else
				$exclude_cat_str = '';	
			
			$sql_tot_fav_categories 	= "SELECT count(id) 
												FROM 
													product_categories pc,customer_fav_categories cfc 
												WHERE
													pc.category_id = cfc.categories_categories_id 
													AND pc.category_hide =0 
													AND	cfc.sites_site_id = $ecom_siteid 
													AND cfc.customer_customer_id= $custom_id 
													$exclude_cat_str";
			$ret_totfav_categories 	= $db->query($sql_tot_fav_categories);
			list($tot_cntcateg) 	= $db->fetch_array($ret_totfav_categories); 
			$chk_cnt 				= 0;
			$pg_variablecateg		= 'categ_pg';
			$prod_limitcat			= $Settings_arr['product_limit_homepage_favcat_recent'];
			if($prod_limitcat<=0)
				$prod_limitcat 	= 3;
			$sql_fav_categories = "SELECT category_id,category_name,category_showimageofproduct,category_paid_for_longdescription,
										category_paid_description,category_shortdescription,categories_categories_id,id 
										FROM 
											product_categories pc,customer_fav_categories cfc 
										WHERE
											 pc.category_id = cfc.categories_categories_id 
											 AND pc.category_hide =0 
											 AND cfc.sites_site_id = $ecom_siteid 
											 AND cfc.customer_customer_id= $custom_id
											 $exclude_cat_str";
			$ret_favcat = $db->query($sql_fav_categories);
			if ($db->num_rows($ret_favcat))
			{
				$sql_last_login 		= "SELECT customer_last_login_date 
											FROM 
												customers 
											WHERE 
												sites_site_id=$ecom_siteid 
												AND customer_id=$custom_id";
				$ret_last_login 		= $db->query($sql_last_login);
				list($row_last_login) 	= $db->fetch_array($ret_last_login);
				
					?>
					<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
					<?php	
						//for($i=0;$i<count($cur_cat_arr);$i++)
						while ($row_favcat = $db->fetch_array($ret_favcat))
						{
							
							if(count($prod_arr)>0)
							{
								$exclude_prod_str = " AND product_id NOT IN (".implode(',',$prod_arr).")";
							}
							else
								$exclude_prod_str = '';
							
							//*********************************************************
							// Get the list of products which have offers
							//*********************************************************
							$sql_prods_offers = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
														a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
														a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
														product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints,  
														a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,
														a.product_variablesaddonprice_exists,a.product_freedelivery,a.product_show_pricepromise,
														a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,
														a.product_newicon_text,a.product_averagerating 
													FROM  
															products a,product_category_map b  
													WHERE 
															sites_site_id =$ecom_siteid
															
															AND b.product_categories_category_id = ".$row_favcat['category_id']." 
															AND a.product_id = b.products_product_id 
															AND a.product_hide = 'N'
															$exclude_prod_str
													ORDER  BY 
														product_webprice ASC 
													LIMIT 
														$prod_limitcat";
							$ret_prods_offers = $db->query($sql_prods_offers);
								
							//Taking the New products added in the category after customer's last login	
							$sql_prod_first = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
															a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
															a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
															product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints ,
															a.product_stock_notification_required,a.product_alloworder_notinstock,
															a.product_variables_exists,a.product_variablesaddonprice_exists,
															a.product_freedelivery,a.product_show_pricepromise,
															a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,
															a.product_newicon_text,a.product_averagerating 
														FROM 
															products a,product_category_map b 
														WHERE 
															b.product_categories_category_id = ".$row_favcat['category_id']." 
															AND a.product_id = b.products_product_id 
															AND a.product_hide = 'N'
															AND a.sites_site_id = $ecom_siteid 
															AND a.product_adddate >= '".$row_last_login."' 
															$exclude_prod_str 
														ORDER BY 
															a.product_webprice ASC 
														LIMIT 
															$prod_limitcat";
							$ret_prods_new = $db->query($sql_prod_first);
							if($db->num_rows($ret_prods_offers) or 	$db->num_rows($ret_prods_new))
							{
						?>
								<tr>
									<td colspan="3" class="shelfAheader" align="left"><?php echo stripslash_normal($row_favcat['category_name'])?></td>
								</tr>
						<?php
								if($db->num_rows($ret_prods_offers))
								{
								?>
									<tr>
										<td align="left" class="myhome_offer_subtext" colspan="3"><?php echo str_replace('[category]',stripslash_normal($row_cat['category_name']),stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_RECOMM_PROD']))?>
										</td>
									</tr>
									<tr>
									<td>	
									<?php	
									//*********************************************************
									// Show the list of products which have offers
									//*********************************************************
									if($db->num_rows($ret_prods_offers))
									{
										$cats_arr[] = $row_favcat['category_id']; // assigning the used category id to the array
										$prod_arr	= $this->Show_Products($ret_prods_offers,$prod_arr);
									}	
									?>
									</td>
									</tr>
								<?php
								}
								if($db->num_rows($ret_prods_new))
								{
								?>
									<tr>
										<td align="left" class="myhome_offer_subtext" colspan="3"><?php echo str_replace('[category]', stripslash_normal($row_cat['category_name']),stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_NEW_PROD']))?>
										</td>
									</tr>
									<tr>
									<td>
									<?php		
									//*******************************************************************
									// Show the list of new products since last login in current category
									//*******************************************************************
									if($db->num_rows($ret_prods_new))
									{
										$cats_arr[] = $row_favcat['category_id']; // assigning the used category id to the array
										$prod_arr	= $this->Show_Products($ret_prods_new,$prod_arr);
									}	
									?>
									</td>
									</tr>
								<?php	
								}		
								?>
								<tr>
									<td align="right" colspan="3"><a href="<?php  url_category_all($row_favcat['category_id'],$row_favcat['category_name'],-1)?>" class="middle_showall_link" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?>"><? echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?></a>
									</td>
								</tr>	
								<?php					
							}
						}
					?>
					</table>
					<?php	
				
			}	
				$used_val_arr['cats_arr'] = $cats_arr;
				$used_val_arr['prod_arr'] = $prod_arr;
				return $used_val_arr;	
		}
		
		//function Show_Products($cat_id,$ret_prods,$prod_arr)
		function Show_Products($ret_prods,$prod_arr)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents;
			$prod_compare_enabled = isProductCompareEnabled();
			$pass_type = 'image_thumbpath';
			$Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME');
		?>
			<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
		<?php	
			$max_col = 3;
			$cur_col = 0;
			while ($row_prod = $db->fetch_array($ret_prods))
			{
				$prod_arr[] = $row_prod['product_id'];
				if($cur_col == 0)
				{
				  echo "<tr>";
				}
				$cur_col ++;
		?>
			<td class="newshelfAtabletd" onmouseover="this.className='newshelfAtabletd_hover'" onmouseout="this.className='newshelfAtabletd'" align="left" valign="top">												
			<ul class="shelfAul">
						<li><h2 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2></li>
					
						<li>
							<?php
								$price_class_arr['ul_class'] 		= 'shelfBul';
								$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
								$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
								$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
								$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
								echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
								show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
							?>	
						</li>
						<li class="shelfimg">
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
							
						</li>
						<?php
							$module_name = 'mod_product_reviews';
							if(in_array($module_name,$inlineSiteComponents))
							{
								if($row_prod['product_averagerating']>=0)
								{
								?>
									<li class="shlf_star">
										<?php
										display_rating($row_prod['product_averagerating']);
										?>
									</li>
								<?php
								}
							}	
							if($row_prod['product_freedelivery']==1 || $row_prod['product_bulkdiscount_allowed']=='Y')
							{
							?>
								<li class="shlf_sale">
								<?php
									if($row_prod['product_bulkdiscount_allowed']=='Y')
									{	
									?>
										<img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/>
									<?php
									}
									if($row_prod['product_freedelivery']==1)
									{
									?>
										<img src="<?php url_site_image('free-img.gif')?>" alt="Free Delivery"/>
									<?
									}
									?>
								</li>
							<?
							}
							?>
					<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
			</ul>
				<?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?>
	<?php
		
			
				$frm_name = uniqid('myhome_');
			?>
					<?php if($prod_compare_enabled)  {
					dislplayCompareButton($row_prod['product_id']);
					}
					if($row_prod['product_saleicon_show']==1)
					{
						$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
						if($desc!='')
						{
						?>	
							<div class="shlf_sale_sale"><?php echo $desc?></div>
						<?php
						}
					}
					if($row_prod['product_newicon_show']==1)
					{
						$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
						if($desc!='')
						{
						?>
							<div class="shlf_sale_new"><?php echo $desc?></div>
						<?php
						}
					}
					?>
					<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
					<input type="hidden" name="fpurpose" value="" />
					<input type="hidden" name="fproduct_id" value="" />
					<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
					<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
					<div class="buy_main">
					<div class="shlf_buy_btn">
					<div class="shlf_buy_btnleft">
					<?php
					$class_arr 							= array();
					$class_arr['ADD_TO_CART']	= 'link_buy';
					$class_arr['PREORDER']			= 'link_buy';
					$class_arr['ENQUIRE']			= 'link_buy';
					show_addtocart($row_prod,$class_arr,$frm_name,false,'','','add_divlink',0)
					?>
					</div>
					</div>      
					<div class="shlf_info_btn"><div class="shlf_info_btnleft">
					<?php show_moreinfo($row_prod,'info_buy')?>
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
			}			
				// If in case total product is less than the max allowed per row then handle that situation
				if($cur_col<$max_col)
				{
					if($cur_col!=0)
					{ 
						echo '<td colspan="'.($max_col-$cur_col).'">&nbsp;</td>';
						echo "</tr>";
					} 
				}
			?>
			</table>
		<?php	
			return $prod_arr;
		}
		
		function Show_Favourite_Products($used_val_arr)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents;
			$custom_id	= get_session_var('ecom_login_customer');
			$cats_arr	= $used_val_arr['cats_arr'];
			$prod_arr	= $used_val_arr['prod_arr'];
			$displaytype = $Settings_arr['favorite_prodlisting'];
			$Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME');
			$prod_compare_enabled = isProductCompareEnabled();
			$pass_type = 'image_thumbpath';
			if(count($prod_arr)>0)
			{
				$exclude_prod_str = " AND p.product_id NOT IN (".implode(',',$prod_arr).")";
			}
			else
				$exclude_prod_str = '';
			$sql_tot_fav_products 	= "SELECT count(id) 
											FROM 
												products p,customer_fav_products cfp 
											WHERE
												 p.product_id = cfp.products_product_id 
												 AND p.product_hide='N' 
												 $exclude_prod_str
												 AND cfp.sites_site_id = $ecom_siteid 
												 AND cfp.customer_customer_id= $custom_id";
				$ret_totfav_products 	= $db->query($sql_tot_fav_products);
				list($tot_cntprod) 		= $db->fetch_array($ret_totfav_products); 
				$prodperpage			= ($Settings_arr['product_maxcntperpage_favorite']>0)?$Settings_arr['product_maxcntperpage_favorite']:3;//Hardcoded at the moment. Need to change to a variable that can be set in the console.
				$favsort_by				= $Settings_arr['product_orderby_favorite'];
				$prodsort_order			= $Settings_arr['product_orderfield_favorite'];
				switch ($prodsort_order)
				{
					case 'product_name': // case of order by product name
						$prodsort_order		= 'product_name';
					break;
					case 'price': // case of order by price
						$prodsort_order		= 'product_webprice';
					break;
					case 'product_id': // case of order by price
						$prodsort_order		= 'product_id';
					break;	
				};
				$pg_variableprod		= 'prod_pg';
				if ($_REQUEST['req']!='')// LIMIT for Favorites is applied only if not displayed in home page
				{
					$start_varprod 		= prepare_paging($_REQUEST[$pg_variableprod],$prodperpage,$tot_cntprod);
					$Limitprod			= " LIMIT ".$start_varprod['startrec'].", ".$prodperpage;
				}	
				else
					$Limitprod = '';
				$sql_fav_products = "SELECT id,product_name,product_id,products_product_id,product_name,product_shortdesc,
										product_variablestock_allowed,product_show_cartlink,
										product_preorder_allowed,product_show_enquirelink,product_webstock,product_webprice,
										product_discount,product_discount_enteredasval,product_bulkdiscount_allowed,
										product_total_preorder_allowed, product_applytax,p.product_bonuspoints,
										p.product_stock_notification_required,p.product_alloworder_notinstock,
										p.product_variables_exists,p.product_variablesaddonprice_exists,p.product_freedelivery,
										p.product_show_pricepromise,p.product_saleicon_show,p.product_saleicon_text,p.product_newicon_show,
										p.product_newicon_text,p.product_averagerating        
									FROM 
										products p,customer_fav_products cfp
									WHERE
										p.product_id = cfp.products_product_id 
										AND p.product_hide='N' 
										$exclude_prod_str
										AND cfp.sites_site_id = $ecom_siteid 
										AND cfp.customer_customer_id = $custom_id
									ORDER BY 
										$prodsort_order $favsort_by 
									$Limitprod	";
				$ret_fav_products = $db->query($sql_fav_products);
			if($db->num_rows($ret_fav_products)>0)
			{
				switch($displaytype)
				{
					case '1row': // case of horizontal
					?>
						<table class="newshelfBtable" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td class="newshelfBheader" align="left"><?php echo stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PROD'])?></td>
						</tr>
						<?php
						$cnt =0;
						while($row_prod = $db->fetch_array($ret_fav_products))
						{
							$cnt++;
						?>
							<tr >
							<td align="left" valign="middle"class="newshelfBtabletd" onmouseover="this.className='newnormalshelf_hover'" onmouseout="this.className='newshelfBtabletd'">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="newshelfBtableinner">
							<tr>
							<td class="newshelfBtableinnertd" valign="top" align="left">
							<h2 class="newshelfBprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2>
							<h6 class="newshelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
							<?php
								$module_name = 'mod_product_reviews';
								if(in_array($module_name,$inlineSiteComponents))
								{
									if($row_prod['product_averagerating']>=0)
									{
								?>
									<div class="shlfa_star">
									<?php
										display_rating($row_prod['product_averagerating']);
										?>
									</div>
								<?php
									}
								}	
								if($row_prod['product_bulkdiscount_allowed']=='Y'  || $row_prod['product_freedelivery']==1 )
								{
								?>
									<div class="shlf_sale">
									<? if($row_prod['product_bulkdiscount_allowed']=='Y')
									{
									?>
									<img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/>
									<?
									}
									if($row_prod['product_freedelivery']==1){
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
							<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
								<?php
									// Calling the function to get the type of image to shown for current 
									// Calling the function to get the image to be shown
									$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
									if(count($img_arr))
									{
										show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'newshelfBimg');
									}
									else
									{
										// calling the function to get the default image
										$no_img = get_noimage('prod',$pass_type); 
										if ($no_img)
										{
											show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'newshelfBimg');
										}	
									}	
								?>
								</a> 
							
							<?php if($prod_compare_enabled)  {
										dislplayCompareButton($row_prod['product_id']);
									}?>
							</td>
							<td class="newshelfBtableinnertd" valign="middle" align="left">
							
							<?php
							if($row_prod['product_saleicon_show']==1)
								{
									$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
									if($desc!='')
									{
								?>	
										<div class="shlfa_sale_sale"><?php echo $desc?></div>
								<?php
									}
								}
								if($row_prod['product_newicon_show']==1)
								{
									$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
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
									echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
									show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
									show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
									$frm_name = uniqid('myhome_');
								?>	
										<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
										<input type="hidden" name="fpurpose" value="" />
										<input type="hidden" name="fproduct_id" value="" />
										<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
										<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
										<div class="buy_main">
												<div class="shlf_buy_btn"><div class="shlf_buy_btnleft">
												<?php
														$class_arr 					= array();
														$class_arr['ADD_TO_CART']	= 'link_buy';
														$class_arr['PREORDER']		= 'link_buy';
														$class_arr['ENQUIRE']		= 'link_buy';
														show_addtocart($row_prod,$class_arr,$frm_name,false,'','','add_divlink',0)
													?></div>
												</div>      
												<div class="shlf_info_btn"><div class="shlf_info_btnleft">
												<?php show_moreinfo($row_prod,'info_buy')?>
												</div></div>   
												</div>  
										</form>
							
							</td>
							</tr>
							</table>
							</td>
							</tr>
						<? 
						}
						?>
							<tr>
							<td align="right"><div class="middle_showall_div"><h6 align="right"><a href="<?php url_link('showfavprodall'.$custom_id.'.html')?>" class="middle_showall_link" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?>"><? echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?></a></h6></div></td>
							</tr>
							</table>
						<?php
					break;
					case '3row': // case of vertical
					?>
						<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
						<tr>
							<td class="newshelfBheader" align="left" colspan="3"><?php echo stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PROD'])?></td>
						</tr>
						<?php
						$max_col = 3;
						$cur_col = 0;
						$prodcur_arr = array();
						while($row_prod = $db->fetch_array($ret_fav_products))
						{
							$prodcur_arr[] = $row_prod;
						//##############################################################
						// Showing the title, description and image part for the product
						//##############################################################
						if($cur_col == 0)
						{
						  echo "<tr>";
						}
						$cur_col ++;
						?>
						<td class="newshelfAtabletd" onmouseover="this.className='newshelfAtabletd_hover'" onmouseout="this.className='newshelfAtabletd'" align="left" valign="top">												
						<ul class="shelfAul">
						<li><h2 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2></li>
						<li>
							<?php
								$price_class_arr['ul_class'] 		= 'shelfBul';
								$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
								$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
								$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
								$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
								echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
								show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
							?>	
						</li>
						<li class="shelfimg">
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
						</li>
						<?php
						$module_name = 'mod_product_reviews';
						if(in_array($module_name,$inlineSiteComponents))
						{
								if($row_prod['product_averagerating']>=0)
								{
								?>
									<li class="shlf_star">
										<?php
										display_rating($row_prod['product_averagerating']);
										?>
									</li>
								<?php
								}
						}	
						if($row_prod['product_freedelivery']==1 || $row_prod['product_bulkdiscount_allowed']=='Y')
						{
							?>
							<li class="shlf_sale">
								<?php
								if($row_prod['product_bulkdiscount_allowed']=='Y')
								{	
									?>
									<img src="<?php url_site_image('bulk-discount.gif')?>" alt="Bulk Discount"/>
									<?php
								}
								if($row_prod['product_freedelivery']==1)
								{
									?>
									<img src="<?php url_site_image('free-img.gif')?>" alt="Free Delivery"/>
									<?
								}
								?>
							</li>
							<?
						}
						?>
						<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
						</ul>
						<?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?>
				<?php
					
						
							$frm_name = uniqid('myhome_');
						?>
								<?php 
								if($prod_compare_enabled)
								{
									dislplayCompareButton($row_prod['product_id']);
								}
								if($row_prod['product_saleicon_show']==1)
								{
									$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
									if($desc!='')
									{
										?>	
										<div class="shlf_sale_sale"><?php echo $desc?></div>
										<?php
									}
									}
									if($row_prod['product_newicon_show']==1)
									{
									$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
									if($desc!='')
									{
										?>
										<div class="shlf_sale_new"><?php echo $desc?></div>
										<?php
									}
								}
								
								?>
								<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
								<input type="hidden" name="fpurpose" value="" />
								<input type="hidden" name="fproduct_id" value="" />
								<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
								<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
								<div class="buy_main">
								<div class="shlf_buy_btn">
								<div class="shlf_buy_btnleft">
								<?php
								$class_arr 							= array();
								$class_arr['ADD_TO_CART']	= 'link_buy';
								$class_arr['PREORDER']			= 'link_buy';
								$class_arr['ENQUIRE']			= 'link_buy';
								show_addtocart($row_prod,$class_arr,$frm_name,false,'','','add_divlink',0)
								?>
								</div>
								</div>      
								<div class="shlf_info_btn"><div class="shlf_info_btnleft">
								<?php show_moreinfo($row_prod,'info_buy')?>
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
						}
						// If in case total product is less than the max allowed per row then handle that situation
						if($cur_col<$max_col)
						{
							if($cur_col!=0)
							{ 
								echo '<td colspan="'.($max_col-$cur_col).'">&nbsp;</td>';
								echo "</tr>";
							} 
						}
						?>
						<tr>
							<td align="right" colspan="3"><div class="middle_showall_div"><h6 align="right"><a href="<?php url_link('showfavprodall'.$custom_id.'.html')?>" class="middle_showall_link" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?>"><? echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?></a></h6></div></td>
						</tr>
						</table>						
					<?php
					break;
				}
			}	
			$used_val_arr['cats_arr'] = $cats_arr;
			$used_val_arr['prod_arr'] = $prod_arr;
			return $used_val_arr;	
		}
		
		function Show_Highest_Hit_Categories($used_val_arr)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents;
			$custom_id	= get_session_var('ecom_login_customer');
			$cats_arr	= $used_val_arr['cats_arr'];
			$prod_arr	= $used_val_arr['prod_arr'];
			$Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME');
			$prod_limit			= $Settings_arr['product_limit_homepage_favcat_recent'];
			if($prod_limit<=0)
				$prod_limit 	= 3;
			if(count($cats_arr))
			{
				$exclude_cat_str = " AND pc.category_id NOT IN (".implode(',',$cats_arr).")";
			}
			else
				$exclude_cat_str = '';	
			$totcnt = 0;
			$sql_categories = "SELECT pc.category_id,pc.category_name,cfc.total_hits
									FROM 
										product_categories pc,product_category_hit_count_totals cfc 
									WHERE
										 pc.category_id = cfc.product_categories_category_id  
										 AND pc.category_hide =0 
										 AND cfc.sites_site_id = $ecom_siteid 
										 $exclude_cat_str 
									ORDER BY 
										cfc.total_hits DESC ";
			$ret_cats = $db->query($sql_categories);
			if ($db->num_rows($ret_cats))
			{
				$sql_last_login 		= "SELECT customer_last_login_date 
											FROM 
												customers 
											WHERE 
												sites_site_id=$ecom_siteid 
												AND customer_id=$custom_id";
				$ret_last_login 		= $db->query($sql_last_login);
				list($row_last_login) 	= $db->fetch_array($ret_last_login);
				
					?>
					<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
					<?php	
						//for($i=0;$i<count($cur_cat_arr);$i++)
						while ($row_cats = $db->fetch_array($ret_cats) and $totcnt<3)
						{
							
							if(count($prod_arr)>0)
							{
								$exclude_prod_str = " AND product_id NOT IN (".implode(',',$prod_arr).")";
							}
							else
								$exclude_prod_str = '';
							
							//*********************************************************
							// Get the list of products which have offers
							//*********************************************************
							$sql_prods_offers = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
														a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
														a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
														product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints,  
														a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,
														a.product_variablesaddonprice_exists,a.product_freedelivery,a.product_show_pricepromise,
														a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,
														a.product_newicon_text,a.product_averagerating 
													FROM  
															products a,product_category_map b  
													WHERE 
															sites_site_id =$ecom_siteid
															
															AND b.product_categories_category_id = ".$row_cats['category_id']." 
															AND a.product_id = b.products_product_id 
															AND a.product_hide = 'N'
															$exclude_prod_str
													ORDER  BY 
															product_webprice ASC 
													LIMIT 
														$prod_limit";
							$ret_prods_offers = $db->query($sql_prods_offers);
								
							//Taking the New products added in the category after customer's last login	
							$sql_prod_first = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
															a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
															a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
															product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints ,
															a.product_stock_notification_required,a.product_alloworder_notinstock,
															a.product_variables_exists,a.product_variablesaddonprice_exists,
															a.product_freedelivery,a.product_show_pricepromise,
															a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,
															a.product_newicon_text,a.product_averagerating 
														FROM 
															products a,product_category_map b 
														WHERE 
															b.product_categories_category_id = ".$row_cats['category_id']." 
															AND a.product_id = b.products_product_id 
															AND a.product_hide = 'N'
															AND a.sites_site_id = $ecom_siteid 
															AND a.product_adddate >= '".$row_last_login."' 
															$exclude_prod_str 
														ORDER BY 
															a.product_webprice ASC 
														LIMIT 
															$prod_limit";
							$ret_prods_new = $db->query($sql_prod_first);
							if($db->num_rows($ret_prods_offers) or 	$db->num_rows($ret_prods_new))
							{
								$totcnt++;
						?>
								<tr>
									<td colspan="3" class="shelfAheader" align="left"><?php echo stripslash_normal($row_cats['category_name'])?></td>
								</tr>
						<?php
								if($db->num_rows($ret_prods_offers))
								{
								?>
									<tr>
										<td align="left" class="myhome_offer_subtext" colspan="3"><?php echo str_replace('[category]',stripslash_normal($row_cat['category_name']),stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_MOST_HITS_CAT_OFFER']))?>
										</td>
									</tr>
									<tr>
									<td>	
									<?php	
									//*********************************************************
									// Show the list of products which have offers
									//*********************************************************
									if($db->num_rows($ret_prods_offers))
									{
										$cats_arr[] = $row_cats['category_id']; // assigning the used category id to the array
										$prod_arr	= $this->Show_Products($ret_prods_offers,$prod_arr);
									}	
									?>
									</td>
									</tr>
								<?php
								}
								if($db->num_rows($ret_prods_new))
								{
								?>
									<tr>
										<td align="left" class="myhome_offer_subtext" colspan="3"><?php echo str_replace('[category]', stripslash_normal($row_cat['category_name']),stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_MOST_HITS_CAT_NEW']))?>
										</td>
									</tr>
									<tr>
									<td>
									<?php		
									//*******************************************************************
									// Show the list of new products since last login in current category
									//*******************************************************************
									if($db->num_rows($ret_prods_new))
									{
										$cats_arr[] = $row_cats['category_id']; // assigning the used category id to the array
										$prod_arr	= $this->Show_Products($ret_prods_new,$prod_arr);
									}	
									?>
									</td>
									</tr>
								<?php	
								}		
								?>
								<tr>
									<td align="right" colspan="3"><a href="<?php  url_category_all($row_cats['category_id'],$row_cats['category_name'],-1)?>" class="middle_showall_link" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?>"><? echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?></a>
									</td>
								</tr>	
								<?php					
							}
						}
					?>
					</table>
					<?php	
				
			}	
				$used_val_arr['cats_arr'] = $cats_arr;
				$used_val_arr['prod_arr'] = $prod_arr;
				return $used_val_arr;	
		}
		function Display_Message($mesgHeader,$Message)
		{
			$Captions_arr['COMMON'] = getCaptions('COMMON');
		?>
			<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $mesgHeader;?></div>
			<table width="100%" border="0" cellspacing="4" cellpadding="0" class="regifontnormal">
			<tr>
			<td width="7%" align="left" valign="middle" class="message_header" > 
				<?php echo $mesgHeader;?>
			</td>
			</tr>
			<tr>
			<td align="left" valign="middle" class="message"><?php echo $Message; ?></td>
			</tr>
			</table>
		<?php	
		}
	};	
?>
			