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
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$ids;
			$customer_id 	= get_session_var("ecom_login_customer");
			if($customer_id>0)
			{
				$sql_last_login = "SELECT customer_last_login_date FROM customers WHERE sites_site_id=$ecom_siteid AND customer_id=$customer_id";
				$ret_last_login = $db->query($sql_last_login);
				list($row_last_login) = $db->fetch_array($ret_last_login);
			}
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
		?>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tbody>
		<?php
			while($row_favcat = $db->fetch_array($ret_favcat)) 
			{ 
				?>
				<tr><td>
				<?
				$prodcur_arr =array();
				//$limit = 4;
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
				<div class="best_seller_heading"><a href="<?php url_category($row_favcat['category_id'],$row_favcat['category_name'],-1,$_REQUEST['catgroup_id'],0)?>" class="categoreyname_headerlink_myhome" title="<?php echo stripslashes($row_favcat['category_name'])?>"><?php echo stripslashes($row_favcat['category_name'])?></a>
				</div>
			  <?
				$max_col = 3;
				$cur_col = 0;
				//$prodcur_arr = array();
				foreach( $prodcur_arr as $k=>$product_array)
				{
				 $prodcurtd_arr[] = $product_array;
					//##############################################################
					// Showing the title, description and image part for the product
					//##############################################################
			?>
					<div class="product_list_main">
												<ul>
														<li> <h1 class="pro_name"><a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>"><?php echo stripslashes($product_array['product_name'])?></a></h1></li>
								
										
									<li><h1><div class="list_img" align="center">
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
										
									</div></h1>
								</li>
								<?php
								$prefix = '<li>';
									$suffix = '</li>';
								 if(isProductCompareEnabled())  {									 
									dislplayCompareButton($product_array['product_id'],$prefix,$suffix);
								 }
										
								$price_class_arr['ul_class'] 		= '';
								$price_class_arr['normal_class'] 	= 'pro_price_offer';
								$price_class_arr['strike_class'] 	= 'pro_price';
								$price_class_arr['yousave_class'] 	= 'pro_price_offer';
								$price_class_arr['discount_class'] 	= 'pro_price_dis';
								echo show_Price($product_array,$price_class_arr,'shelfcenter_3');
								//show_excluding_vat_msg($product_array,'vat_div',$prefix,$suffix);// show excluding VAT msg
								//show_bonus_points_msg($product_array,'bonus_point',$prefix,$suffix); // Show bonus points
								$frm_name = uniqid('mafavhome_'); ?>	
										<li>
																	<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $product_array['product_id']?>)">
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" />									
																	<label><?php show_moreinfo($product_array,'product_info')?></label>
																		<?php
																		$prefix = "<DIV class='product_list_button_list'><label>"; 
																		$suffix = "</label> </DIV>";
																			$class_arr 					= array();
																			$class_arr['ADD_TO_CART']	= 'product_list_button';
																			$class_arr['PREORDER']			= 'product_list_button';
																			$class_arr['ENQUIRE']			= 'product_list_button';
																		   show_addtocart($product_array,$class_arr,$frm_name,false,$prefix,$suffix)
																		?>
																	</form>
											</li>
										</ul>
										</div>	
				<?php
				}//End For
				?></td>
			</tr>
			<tr ><td colspan="3" align="right" class="myhome_showall" ><a href="<?php  url_category_all($row_favcat['category_id'],$row_favcat['category_name'],-1)?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><? echo $Captions_arr['COMMON']['SHOW_ALL'];?></a></td></tr>
		
				<?
				}//End the prod checking
			}//Endwhile
	?>
	</tbody></table>
	<?php
		return $ids_in;
	}

/////////////**********TO DISPLAY FAVORITE PRODUCTS**********///////////////
		// ** Function to list the products
		function Show_MyhomeFavoriteProducts($ret_fav_products,$tot_cntprod,$start_varprod,$pg_variableprod)
		{
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$ids_in;
		
		?>
		<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable" width="100%">
				<tr>
					<td colspan="3" > <div class="best_seller_heading"><?php
				  	if ($db->num_rows($ret_fav_products)==1)
						echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCT_HEADER'];
					else
						echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCTS_HEADER'];
				  ?>	</div></td>
				</tr>
				<tr>
			<td colspan="3">
				<?php
						// ** Showing the products listing either 1 in a row or 3 in a row
						//$pg_variable = 'catdet_pg';
					$displaytype = $Settings_arr['favorite_prodlisting'];
						switch($displaytype)
						{
						
						
							case 'nor': // case of one in a row for normal
							?>
								<?php
									if ($tot_cntprod>0)
									{
									?>
									 <div class="pro_nav_right" align="right" >
										 <div class="pro_nav_products" align="center"><?php paging_show_totalcount($tot_cntprod,'Products',$start_varprod['pg'],$start_varprod['pages'])?></div>
											<?php 
												$path = '';
												$pageclass_arr['container'] = 'pagenavcontainer';
												$pageclass_arr['navvul']	= 'pagenavul';
												$pageclass_arr['current']	= 'pagenav_current';
												
												$query_string = "&amp;catdet_sortby=".$_REQUEST['catdet_sortby'].'&amp;catdet_sortorder='.$_REQUEST['catdet_sortorder'].'&amp;catdet_prodperpage='.$_REQUEST['catdet_prodperpage'];
												paging_footer($path,$query_string,$tot_cntprod,$start_varprod['pg'],$start_varprod['pages'],'',$pg_variableprod,'Favourite products',$pageclass_arr,0); 
											?>	
										</div>
									<?php
									}
									while($row_prod = $db->fetch_array($ret_fav_products))
									{
										$prodcur_arr[] = $row_prod;
								?>
										<div class="product_list_main">
										<ul>
														
														<li> <h1 class="pro_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li>
													<li><h1><div class="list_img" align="center">
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
													<?php
													
														// Calling the function to get the type of image to shown for current 
														$pass_type = get_default_imagetype('prodcat');
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
													</div></h1>
													</li>
													<? 
											
												
												$price_class_arr['ul_class'] 		= '';
												$price_class_arr['normal_class'] 	= 'pro_price_offer';
												$price_class_arr['strike_class'] 	= 'pro_price';
												$price_class_arr['yousave_class'] 	= 'pro_price_offer';
												$price_class_arr['discount_class'] 	= 'pro_price_dis';
											?>
										
											<?php
												echo show_Price($row_prod,$price_class_arr,'cat_detail_1'); 
											?>
											
											<?php	
												$frm_name = uniqid('catdet_');
												$prefix = '<li>';
												$suffix = '</li>';
												//show_excluding_vat_msg($row_prod,'vat_div',$prefix,$suffix);// show excluding VAT msg
												//show_bonus_points_msg($row_prod,'bonus_point',$prefix,$suffix); // Show bonus points
											?>	
											<?php if(isProductCompareEnabled())  { 
											
												dislplayCompareButton($row_prod['product_id'],$prefix,$suffix);
											
											 }
											 ?>
											 <li>
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<label><?php show_moreinfo($row_prod,'product_info')?></label>
													<? 
													$prefix = "<DIV class='product_list_button_list'><label>"; 
													$suffix = "</label> </DIV>";
													?>
														<?php
															$class_arr 					= array();
															$class_arr['ADD_TO_CART']	= 'product_list_button';
															$class_arr['PREORDER']			= 'product_list_button';
															$class_arr['ENQUIRE']			= 'product_list_button';
															show_addtocart($row_prod,$class_arr,$frm_name,false,$prefix,$suffix)
														?>
														
													</form>
											</li>
											</ul>
											</div>
								<?php
									}
							break;
						
						};
				?>
				</td></tr>
			
			</table>
		
	<?	}
		
function Show_MyhomePurcahaseProducts($ret_purchase)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents;
		   if($db->num_rows($ret_purchase)>0)
			{
			?>
			<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable" width="100%">
			
											<tr><td colspan="3"><div class="best_seller_heading"><?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></div>
										</td></tr>
										<tr><td colspan="3">
										<?php
											$prodcur_arr = array();
									while($row_prod = $db->fetch_array($ret_purchase))
									{
										$prodcur_arr[] = $row_prod;
								?>
										<div class="product_list_main">
										<ul>
														
														<li> <h1 class="pro_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li>
														
													<li><h1><div class="list_img" align="center">
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
													<?php
													
														// Calling the function to get the type of image to shown for current 
														$pass_type = get_default_imagetype('prodcat');
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
													</div></h1>
													</li>
													<? 
											
												
												$price_class_arr['ul_class'] 		= '';
												$price_class_arr['normal_class'] 	= 'pro_price_offer';
												$price_class_arr['strike_class'] 	= 'pro_price';
												$price_class_arr['yousave_class'] 	= 'pro_price_offer';
												$price_class_arr['discount_class'] 	= 'pro_price_dis';
											?>
										
											<?php
												echo show_Price($row_prod,$price_class_arr,'cat_detail_1'); 
											?>
											
											<?php	
												$frm_name = uniqid('mafavhome_');
												$prefix = '<li>';
												$suffix = '</li>';
												//show_excluding_vat_msg($row_prod,'vat_div',$prefix,$suffix);// show excluding VAT msg
												//show_bonus_points_msg($row_prod,'bonus_point',$prefix,$suffix); // Show bonus points
											?>	
											<?php if(isProductCompareEnabled())  { 
											
												dislplayCompareButton($row_prod['product_id'],$prefix,$suffix);
											
											 }
											 ?>
											 <li>
												<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
													<input type="hidden" name="fpurpose" value="" />
													<input type="hidden" name="fproduct_id" value="" />
													<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
													<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<label><?php show_moreinfo($row_prod,'product_info')?></label>
													<? 
													$prefix = "<DIV class='product_list_button_list'><label>"; 
													$suffix = "</label> </DIV>";
													?>
														<?php
															$class_arr 					= array();
															$class_arr['ADD_TO_CART']	= 'product_list_button';
															$class_arr['PREORDER']			= 'product_list_button';
															$class_arr['ENQUIRE']			= 'product_list_button';
															show_addtocart($row_prod,$class_arr,$frm_name,false,$prefix,$suffix)
														?>
														
													</form>
											</li>
											</ul>
											</div>
								<?php
									}
											// If in case total product is less than the max allowed per row then handle that situation
											$prodcur_arr = array();
											?>
											</td>
											</tr>
													<tr><td colspan="3" align="right" class="myhome_showall"><a href="http://<?=$ecom_hostname?>/showpurchaseall.html" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><? echo $Captions_arr['COMMON']['SHOW_ALL']?></a></td></tr>
										</table>
			<?
			 }//end Checking
         }

///////////*************END FAVORITE PRODUCTS**************//////////////////

		function Display_WelcomeMessage($mesgHeader,$Message){
		global $Captions_arr,$ecom_hostname,$db,$ecom_siteid;
		$sql_user = "SELECT customer_fname,customer_discount,customer_allow_product_discount FROM customers where customer_id = ".get_session_var("ecom_login_customer")." LIMIT 1";
		$ret_user = $db->query($sql_user);
		list($username,$customer_discount,$allow_discount) = $db->fetch_array($ret_user);
		?>
		<div class="treemenu"><a href="<? url_link('')?>">Home</a> >> <?php echo  $Captions_arr['LOGIN_HOME']['LOGIN_HOME_TREE_MENU']?></div>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="loginwelcomemsg_table">
      <tr>
        <td align="left" valign="middle" class="loginwelcomemsg_header" > 
       	 <?php echo  str_replace("[username]",$username,$Captions_arr['LOGIN_HOME']['LOGIN_HOME_HEADER_MESG']);?></td>
      </tr>
	  <?php
	  	if($Captions_arr['LOGIN_HOME']['LOGIN_HOME_MESG_TEXT']!='')
		{
	  ?>
		  <tr>
			<td align="left" valign="middle" class="loginwelcomemsg_text"> <?php echo  str_replace("[username]",$username,$Captions_arr['LOGIN_HOME']['LOGIN_HOME_MESG_TEXT']);?></td>
		  </tr>
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
			?>
				<tr>
				<td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="loginwelcomemsg_table">
			<?php
				while($row_assigned =$db->fetch_array($ret_assigned))
				{ 
				$cnt ++;
				$group_id = $row_assigned['customer_discount_group_cust_disc_grp_id'];
				if($group_id)
				{
					$sql_discount = "SELECT 
											cust_disc_grp_discount 
										FROM 
											customer_discount_group 
										WHERE 
											cust_disc_grp_id=".$group_id." AND cust_disc_grp_active=1 LIMIT 1";
					$ret_discount = $db->query($sql_discount);
					$row_discount = $db->fetch_array($ret_discount);
					$sql_products_id = "SELECT 
											DISTINCT pc.products_product_id,p.product_id,p.product_name,p.product_shortdesc,p.product_webprice,
											p.product_discount,p.product_discount_enteredasval,p.product_bulkdiscount_allowed,p.product_variablestock_allowed,p.product_alloworder_notinstock,p.product_show_cartlink,p.product_preorder_allowed,
											p.product_total_preorder_allowed,p.product_show_enquirelink,p.product_webstock,p.product_variables_exists,p.product_variablesaddonprice_exists  
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
						
						<tr>
							<td class="logindetailheader" align="left" colspan="2"><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_DISC_DETAILS']?></td>
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
							  <td align="left" valign="middle" class="loginwelcomemsg_text" width="40%"><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_NORMAL_DISC']?></td><td align="left" valign="middle" class="loginwelcomemsg_text">:&nbsp;<?=$customer_discount.'%'?></td>
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
								<td align="left" valign="middle" class="loginwelcomemsg_text" width="40%"><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_DISC']?></td><td align="left" valign="middle" class="loginwelcomemsg_text">:&nbsp;<?=$row_discount['cust_disc_grp_discount'].'%'?></td>
							</tr>
							<?
						 }
					 }	 
					  if($row_discount['cust_disc_grp_discount']>0)
							{   
							$Cnt_prd=0;
							?>
							<tr><td colspan="2">

										<? if($db->num_rows($ret_products_id )>0)
											{
											if($allow_discount==1)
											{
											 $homemsg = str_replace("[value]", $row_discount['cust_disc_grp_discount'].'%', $Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_PRODUCTS']);
										 ?>
											 <!--<tr>
												<td align="left" valign="middle" class="logindiscountmsg_text"  colspan="3"><?=$homemsg?></td>
											</tr>-->
											<?
											$ids = array();
											while($row_products_id = $db->fetch_array($ret_products_id))
											{
												//sunil
												$ids[] = $row_products_id['product_id'];
											?>
											 <div class="search_main">
                    
                           					 <div class="search_image"><a href="<?php url_product($row_products_id['product_id'],$row_products_id['product_name'],-1)?>" title="<?php echo stripslashes($row_products_id['product_name'])?>">
<?php // Calling the function to get the type of image to shown for current 
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
																	<?php
														$prefix = "<div class='compare_li'>";
															$suffix = "</div>";
														 if(isProductCompareEnabled())  {
															dislplayCompareButton($row_products_id['product_id'],$prefix,$suffix);
														}?>	
														</div>	
                           								
														 <div class="search_content">
																<div class="search_name" ><a href="<?php url_product($row_products_id['product_id'],$row_products_id['product_name'],-1)?>" title="<?php echo stripslashes($row_products_id['product_name'])?>" class="categoreyname_headerlink_myhome"><?php echo stripslashes($row_products_id['product_name'])?></a></div>
																 <div class="search_des"><?php  echo stripslashes($row_products_id['product_shortdesc'])?></div>
														         <div class="search_price">
																 <UL>
																 
																  <?php $price_class_arr['ul_class'] 		= '';
																$price_class_arr['normal_class'] 	= 'search_normal_price';
																$price_class_arr['strike_class'] 	= 'search_price_strike';
																$price_class_arr['yousave_class'] 	= 'search_price_offer';
																$price_class_arr['discount_class'] 	= 'search_price_dis';
																echo show_Price($row_products_id,$price_class_arr,'shelfcenter_1');
																?>
																</UL>
																  </div>
														          </div>
														 <div class="search_buy" align="right">
															
																<label><a href="<?php url_product($row_products_id['product_id'],$row_products_id['product_name'],-1)?>" title="<?php echo stripslashes($row_products_id['product_name'])?>">
																</a></label>
															
														<?php
															
																$frm_name = uniqid('mafavhome_');
														?>	
																<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_products_id['product_id']?>)">
																<input type="hidden" name="fpurpose" value="" />
																<input type="hidden" name="fproduct_id" value="" />
																<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																<input type="hidden" name="fproduct_url" value="<?php url_product($row_products_id['product_id'],$row_products_id['product_name'])?>" />
																	<label><?php show_moreinfo($row_products_id,'product_info')?></label>
																	
																	<?php
																	$prefix = "<DIV class='product_list_button_list'><label>"; 
																	$suffix = "</label> </DIV>";
																		$class_arr 					= array();
																		$class_arr['ADD_TO_CART']	= 'product_list_button';
																		$class_arr['PREORDER']		= 'product_list_button';
																		$class_arr['ENQUIRE']		= 'product_list_button';
																		show_addtocart($row_products_id,$class_arr,$frm_name,false,$prefix,$suffix)
																	?>
																</form>
																</div>
															</div>	
											  <?
											   }//end while
											  }
											 }
							?>
							
							<?
							}
					}
				?>
						</table>
							</td>
							</tr>
				<?php	
			}
			else
			{
			 if($customer_discount>0)
			 {
			 ?>
			  <tr>
						  <td align="left" valign="middle" class="loginwelcomemsg_text" width="40%"><?=$Captions_arr['LOGIN_HOME']['LOGIN_HOME_NORMAL_DISC']?></td><td align="left" valign="middle" class="loginwelcomemsg_text">:&nbsp;<?=$customer_discount.'%'?></td>
			 </tr>
			 <?
			  }
			}
			  ?>
		  </table>
		 
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
			