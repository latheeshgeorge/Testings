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
		function Display_WelcomeMessage($mesgHeader,$Message)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
			$sql_user = "SELECT customer_title,customer_fname,customer_surname,customer_discount,customer_allow_product_discount FROM customers where customer_id = ".get_session_var("ecom_login_customer")." LIMIT 1";
			$ret_user = $db->query($sql_user);
			if($db->num_rows($ret_user))
			{
				$row_user = $db->fetch_array($ret_user);
				$username = stripslashes($row_user['customer_title']).' '.stripslashes($row_user['customer_fname']).' '.stripslashes($row_user['customer_surname']);
				$customer_discount = $row_user['customer_discount'];
				$allow_discount = $row_user['customer_allow_product_discount'];
			}
			//list($username,$customer_discount,$allow_discount) = $db->fetch_array($ret_user);
			$Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME');
			$cats_arr = $prod_arr = array();
			$prod_compare_enabled = isProductCompareEnabled();
			
			$HTML_treemenu=$HTML_headermsg=$HTML_lgnhmrmsg=$HTML_Dischead=$HTML_tophomemsg=$HTML_topinnerdiv=$HTML_tophomeinnermsg='';
				$HTML_treemenu .=
				'<div class="tree_menu_conA">
				  <div class="tree_menu_topA"></div>
				  <div class="tree_menu_midA">
					<div class="tree_menu_content">
					  <ul class="tree_menu">
					<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
					 <li>'.stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_TREE_MENU']).'</li>
					</ul>
					  </div>
				  </div>
				  <div class="tree_menu_bottomA"></div>
				</div>';
			echo $HTML_treemenu ;	
			$str = str_replace("[username]",$username,$Captions_arr['LOGIN_HOME']['LOGIN_HOME_HEADER_MESG']);	
			$HTML_headermsg .='
			<div class="my_hm_user">
				<div class="my_hm_user_left"></div>
				<div class="my_hm_user_right">'.stripslash_normal($str).'
				 </div>
			</div>';
				if($Captions_arr['LOGIN_HOME']['LOGIN_HOME_MESG_TEXT']!='')
				{
					 $sr_arr = array('Mr.','Mrs.','Miss.','Ms.','M/s.','Dr.','Sir.','Rev.');
                       $rp_arr = array('','','','','','','','');
			      $str = str_replace("[username]",str_replace($sr_arr,$rp_arr,$username),$Captions_arr['LOGIN_HOME']['LOGIN_HOME_MESG_TEXT']);
				 $HTML_lgnhmrmsg .='<div class="my_hm_user_msg">'.stripslash_normal($str).'</div>';
				}
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
				$cnt_user = 0;
				while($row_assigned =$db->fetch_array($ret_assigned))
				{ 
				$cnt_user ++;
					$cnt ++;
					$group_id = $row_assigned['customer_discount_group_cust_disc_grp_id'];
					$HTML_tophomemsg =$HTML_topinnerdiv=$HTML_tophomeinnermsg=$HTML_bottominnerdiv=$HTML_Dischead='';
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
					$disp_head =false;
					$HTML_Dischead .='<div class="my_hm_discount">
							<div class="my_hm_dis_left"></div>';
							if($cnt==1)
							{
								if($flag==2)
								{   
									$disp_head =true;
									$HTML_Dischead .='<div class="my_hm_dis_mid">'.stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_DISC_DETAILS']).'</div>';
								}
							}
							if($db->num_rows($ret_products_id )>0 || !$group_id || $row_discount['cust_disc_grp_discount']==0)
							{
								if($cnt==1)
								{		
									 if($customer_discount>0)
									 {      $disp_head =true;
											$HTML_Dischead .='<div class="my_hm_dis_mida">'.stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_NORMAL_DISC']).'</div>
											<div class="my_hm_dis_right">'.$customer_discount.'%</div>';
									 }
								}
							} elseif($db->num_rows($ret_products_id )==0 && $group_id)	
							 {	
								if($row_discount['cust_disc_grp_discount']>0)
								{
							       $disp_head =true;
								   $HTML_Dischead .='<div class="my_hm_dis_mida">'.stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_DISC']).'</div>
													<div class="my_hm_dis_right">'.$row_discount['cust_disc_grp_discount'].'%</div>';
								 }
							 }	 	 
							
						$HTML_Dischead .='</div>';
						if($cnt_user==1)
						echo $HTML_headermsg;
						if($disp_head==true)
						echo $HTML_Dischead ;
						if($cnt_user==1)
						echo $HTML_lgnhmrmsg;
					if($row_discount['cust_disc_grp_discount']>0)
					{   
						$Cnt_prd=0;
						if($db->num_rows($ret_products_id )>0)
						{
							if($allow_discount==1)
							{
							 if($row_discount['cust_disc_display_category_in_myhome']==1) // case if categories assigned to discount group should be displayed
								{
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
									$HTML_tophomemsg .='<div class="my_hm_shlf_hdr">'.stripslash_normal($homemsg).'</div>';
									$HTML_topinnerdiv .='<div class="my_hm_shlf_inner">
									<div class="my_hm_shlf_inner_top"></div>
									<div class="my_hm_shlf_inner_cont">';
									$HTML_tophomeinnermsg .='<div class="my_hm_shlf_hdr_outr"><div class="my_hm_shlf_hdr_in"><span>'.stripslash_normal($homemsg).'</span></div></div>';
									echo '<div class="my_hm_shlf_outr">';
									echo $HTML_tophomemsg;
									echo $HTML_topinnerdiv;	
									echo $HTML_tophomeinnermsg;
									echo '<div class="my_hm_shlf_cont_div">
									<div class="my_hm_shlf_pdt_con">
									<ul class="my_hme_pdtname">';
									$cnts = $db->num_rows($ret_subcat);
									while ($row_subcat = $db->fetch_array($ret_subcat))
									{
									$cats_arr[] = $row_subcat['category_id'];
									$HTML_cattop = $HTML_catimg=$HTML_catname=$HTML_catprice=$HTML_catbottom='';
									 $HTML_catname .='<li ><span><a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" title="'.stripslash_normal($row_subcat['category_name']).'">'.stripslash_normal($row_subcat['category_name']).'</a></span></li>';
									 echo $HTML_catname;
									}
									$HTML_bottominnerdiv .='</ul></div>
									<div class="my_hm_shlf_inner_bottom"></div>
									</div>';
									echo '
									</div>
									</div>';
									echo $HTML_bottominnerdiv;
									echo '</div>';
									}
								}
								else
								{
								$ids = array();
								$homemsg = str_replace("[value]", '<strong>'.$row_discount['cust_disc_grp_discount'].'</strong>%', $Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_PRODUCTS']);
								$homemsgfirst = $Captions_arr['LOGIN_HOME']['LOGIN_HOME_GORUP_CATEGORIES_FIRST'];
								echo '<div class="my_hm_shlf_outr">';
								$HTML_tophomemsg .='<div class="my_hm_shlf_hdr">'.stripslash_normal($homemsgfirst).'</div>';
							
								$HTML_topinnerdiv .='<div class="my_hm_shlf_inner">
								<div class="my_hm_shlf_inner_top"></div>
								<div class="my_hm_shlf_inner_cont">';
								$HTML_tophomeinnermsg .='<div class="my_hm_shlf_hdr_outr"><div class="my_hm_shlf_hdr_in"><span>'.stripslash_normal($homemsg).'</span></div></div>';
								echo $HTML_tophomemsg;
								echo $HTML_topinnerdiv;
								echo $HTML_tophomeinnermsg;
								$cnts = $db->num_rows($ret_products_id);
								$width_one_set 	= 106;
								$min_number_req	= 6;
								$min_width_req 	= $width_one_set * $min_number_req;
								$total_cnt		= $cnts;
								$calc_width		= $total_cnt * $width_one_set;
								if($calc_width < $min_width_req)
									$div_width = $min_width_req;
								else
									$div_width = $calc_width; 
								echo 
								'<div class="my_hm_shlf_cont_div">
								<div class="my_hm_shlf_pdt_con">
								<div class="det_link_nav"><a href="#null" onmouseover="scrollDivRight(\'containerA\')" onmouseout="stopMe()"><img src="'.url_site_image('arrow-left.gif',1).'"></a></div>
								<div id="containerA" class="my_hm_shlf_pdt_inner">
								<div id="scroller" style="width:'.$div_width.'px">';
								
								while($row_products_id = $db->fetch_array($ret_products_id))
								{
								$HTML_cattop = $HTML_prodimg = $HTML_prodname = $HTML_prodprice = $HTML_prodbottom='';
								$prod_arr[] = $row_products_id['product_id'];
								$HTML_prodimg .='<div class="my_hm_shlf_image">
								<a href="'.url_product($row_products_id['product_id'],$row_products_id['product_name'],1).'" title="'.stripslash_normal($row_products_id['product_name']).'">';
												// Calling the function to get the image to be shown
												$pass_type = get_default_imagetype('fav_prod');
												$img_arr = get_imagelist('prod',$row_products_id['product_id'],$pass_type,0,0,1);
												if(count($img_arr))
												{
													$HTML_prodimg .=show_image(url_root_image($img_arr[0][$pass_type],1),$row_products_id['product_name'],$row_products_id['product_name'],'','',1);
												}
												else
												{
													// calling the function to get the default image
													$no_img = get_noimage('prod',$pass_type); 
													if ($no_img)
													{
														$HTML_prodimg .=show_image($no_img,$row_products_id['product_name'],$row_products_id['product_name'],'','',1);
													}	
												}	
												$HTML_prodimg .='</a>';
								$HTML_prodimg .= '</div>';
								$HTML_prodname .='<div class="my_hm_shlf_name"><a href="'.url_product($row_products_id['product_id'],$row_products_id['product_name'],1).'" title="'.stripslash_normal($row_products_id['product_name']).'">'.stripslash_normal($row_products_id['product_name']).'</a></div>';
								$HTML_prodprice .='<div class="my_hm_shlf_price">';
														$price_arr =  show_Price($row_products_id,array(),'compshelf',false,3);
														if($price_arr['discounted_price'])
															$HTML_prodprice .=$price_arr['discounted_price'];
														else
															$HTML_prodprice .=$price_arr['base_price'];
								$HTML_prodprice .='</div>';
								echo '<div class="my_hm_shlf_pdt">';
								echo $HTML_prodimg;
								echo $HTML_prodname;
								echo $HTML_prodprice;						
								echo '</div>';
								}
								$HTML_bottominnerdiv .='</div>
								<div class="my_hm_shlf_inner_bottom"></div>
								</div>';
								echo '
								</div>
								</div>
								<div class="det_link_nav"> 
								<a href="#null" onmouseover="scrollDivLeft(\'containerA\','.($div_width).')" onmouseout="stopMe()"><img src="'.url_site_image('arrow-right.gif',1).'" /></a>
								</div>
								</div>
								</div>
								';
								echo $HTML_bottominnerdiv ;
								echo '</div>';
								}
							}
						}
					}
				}
			}
			else
			{
			 echo $HTML_headermsg;
			 if($customer_discount>0)
			 {
			 ?>
			 <div class="my_hm_discount">
            <div class="my_hm_dis_left"></div>
            <div class="my_hm_dis_mid"><?php echo stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_DISC_DETAILS']);?></div>
			 <div class="my_hm_dis_mida"> <?=stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_NORMAL_DISC'])?></div>
			 <div class="my_hm_dis_right"><?=$customer_discount.'%'?></div>
			 </div>
			 <?
			  } 
			  echo $HTML_lgnhmrmsg;
			}
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
			$rand_indx = rand(0,(count($cur_cat_arr)-1));			
			if(count($cur_cat_arr))
			{
					$i = $rand_indx;
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
												$prod_limit";
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
					if($db->num_rows($ret_prods_new)==0) // case if no results found
					{
						// requery to find products which have recently added irrespective of whether it is added before or after last login
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
												$exclude_prod_str 
											ORDER BY 
												a.product_adddate DESC, a.product_webprice ASC 
											LIMIT 
												$prod_limit";
						$ret_prods_new = $db->query($sql_prods_new);
					}
					if($db->num_rows($ret_prods_offers) or 	$db->num_rows($ret_prods_new))
					{
			echo
				'<div class="my_hm_shlf_outr">
				<div class="my_hm_shlf_hdrA">'.$Captions_arr['LOGIN_HOME']['LOGIN_HOME_RECOMM_PROD_TITLE'].'</div>	
					<div class="my_hm_shlf_inner">
					<div class="my_hm_shlf_inner_top"></div>
						<div class="my_hm_shlf_inner_cont">';
						if($db->num_rows($ret_prods_offers))
						{
								$cnts = $db->num_rows($ret_prods_offers);
								$width_one_set 	= 143;
								$min_number_req	= 2;
								$min_width_req 	= $width_one_set * $min_number_req;
								$total_cnt		= $cnts;
								$calc_width		= $total_cnt * $width_one_set;
								if($calc_width < $min_width_req)
									$div_width = $min_width_req;
								else
									$div_width = $calc_width; 
						echo
							'<div class="my_hm_shlf_inner_cont_left">
							<div class="my_hm_shlf_hdrA_outr"><div class="my_hm_shlf_hdrA_in"><span>'.str_replace('[category]',stripslash_normal($row_cat['category_name']),stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_RECENT_RECOMM_PROD'])).'</span></div></div>
								<div class="my_hm_shlf_cont_divA">
									<div class="my_hm_shlf_cont_divA_top"> </div>';
									?>
									<div class="my_hm_shlf_pdt_con_in">
									<div class="myhme_link_nav"><a href="#null" onmouseover="scrollDivRight('containerA<?=$cur_cat_arr[$i]?>')" onmouseout="stopMe()"><img src="<?php url_site_image('arrowq.gif')?>"></a></div>
									<div id="containerA<?=$cur_cat_arr[$i]?>" class="my_hm_shlf_pdt_innerA">
									<div id="scroller" style="width:<?php echo $div_width?>px">
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
									</div>
									</div>
									<div class="myhme_link_nav"> <a href="#null" onmouseover="scrollDivLeft('containerA<?=$cur_cat_arr[$i]?>',<?php echo ($div_width)?>)" onmouseout="stopMe()"><img src="<?php url_site_image('arrowqr.gif')?>"></a></div>
							  		</div>
									<?php
							echo  '<div class="my_hm_shlf_cont_divA_bottom"> </div>
								</div>
							</div>';	
						}
						if($db->num_rows($ret_prods_new))
						{
							$cnts = $db->num_rows($ret_prods_new);
							$width_one_set 	= 143;
							$min_number_req	= 2;
							$min_width_req 	= $width_one_set * $min_number_req;
							$total_cnt		= $cnts;
							$calc_width		= $total_cnt * $width_one_set;
							if($calc_width < $min_width_req)
								$div_width = $min_width_req;
							else
								$div_width = $calc_width; 
						echo '<div class="my_hm_shlf_inner_cont_left">
								<div class="my_hm_shlf_hdrA_outrA"><div class="my_hm_shlf_hdrA_inA"><span>'.str_replace('[category]',stripslash_normal($row_cat['category_name']),stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_RECENT_NEW_PROD'])).'</span></div></div>
								<div class="my_hm_shlf_cont_divA">
									<div class="my_hm_shlf_cont_divA_top"> </div>';
									?>
									<div class="my_hm_shlf_pdt_con_in">
									<div class="myhme_link_nav"><a href="#null" onmouseover="scrollDivRight('containerB<?=$cur_cat_arr[$i]?>')" onmouseout="stopMe()"><img src="<?php url_site_image('arrowq.gif')?>"></a></div>
									<div id="containerB<?=$cur_cat_arr[$i]?>" class="my_hm_shlf_pdt_innerA">
									<div id="scroller" style="width:<?php echo $div_width?>px">
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
									</div>
									</div>
									<div class="myhme_link_nav"> <a href="#null" onmouseover="scrollDivLeft('containerB<?=$cur_cat_arr[$i]?>',<?php echo ($div_width)?>)" onmouseout="stopMe()"><img src="<?php url_site_image('arrowqr.gif')?>"></a></div>
							  		</div>
							<?php		
								echo '<div class="my_hm_shlf_cont_divA_bottom"> </div>
								</div>
							</div>';
						} 
				echo  '</div>
					<div class="my_hm_shlf_inner_bottom"></div>
					</div>
				</div>';
					}
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
							if($db->num_rows($ret_prods_new)==0) // case if no results found
							{
								// requery to find products which have recently added irrespective of whether it is added before or after last login
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
															$exclude_prod_str 
														ORDER BY 
															a.product_adddate DESC,a.product_webprice ASC 
														LIMIT 
															$prod_limitcat";
								$ret_prods_new = $db->query($sql_prod_first);
							}
							if($db->num_rows($ret_prods_offers) or 	$db->num_rows($ret_prods_new))
							{
							
						echo '<div class="my_hm_shlf_outr">
							<div class="my_hm_shlf_hdrD">Your Favourite Category: '.stripslash_normal($row_favcat['category_name']).'</div>	
							<div class="my_hm_shlf_inner">
							<div class="my_hm_shlf_inner_top"></div>
								<div class="my_hm_shlf_inner_cont">';
								if($db->num_rows($ret_prods_offers))
								{
									$cnts = $db->num_rows($ret_prods_offers);
									$width_one_set 	= 143;
									$min_number_req	= 2;
									$min_width_req 	= $width_one_set * $min_number_req;
									$total_cnt		= $cnts;
									$calc_width		= $total_cnt * $width_one_set;
									if($calc_width < $min_width_req)
										$div_width = $min_width_req;
									else
										$div_width = $calc_width;
								echo '<div class="my_hm_shlf_inner_cont_left">
									<div class="my_hm_shlf_hdrA_outr"><div class="my_hm_shlf_hdrA_in"><span>'.str_replace('[category]',stripslash_normal($row_cat['category_name']),stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_RECOMM_PROD'])).'</span></div></div>
										<div class="my_hm_shlf_cont_divA">
											<div class="my_hm_shlf_cont_divA_top"> </div>';
											?>
											<div class="my_hm_shlf_pdt_con_in">
											<div class="myhme_link_nav"><a href="#null" onmouseover="scrollDivRight('containerF<?=$row_favcat['category_id']?>')" onmouseout="stopMe()"><img src="<?php url_site_image('arrowq.gif')?>"></a></div>
											<div id="containerF<?=$row_favcat['category_id']?>" class="my_hm_shlf_pdt_innerA">
											<div id="scroller" style="width:<?php echo $div_width?>px">
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
											</div>
											</div>
											<div class="myhme_link_nav"> <a href="#null" onmouseover="scrollDivLeft('containerF<?=$row_favcat['category_id']?>',<?php echo ($div_width)?>)" onmouseout="stopMe()"><img src="<?php url_site_image('arrowqr.gif')?>"></a></div>
											</div>
											<?php
											echo 
											'<div class="my_hm_shlf_cont_divA_bottom"> </div>
										</div>
									</div>';
								}
								if($db->num_rows($ret_prods_new))
								{
									$cnts = $db->num_rows($ret_prods_new);
									$width_one_set 	= 143;
									$min_number_req	= 2;
									$min_width_req 	= $width_one_set * $min_number_req;
									$total_cnt		= $cnts;
									$calc_width		= $total_cnt * $width_one_set;
									if($calc_width < $min_width_req)
										$div_width = $min_width_req;
									else
										$div_width = $calc_width;
								echo '<div class="my_hm_shlf_inner_cont_left">
										<div class="my_hm_shlf_hdrA_outrA"><div class="my_hm_shlf_hdrA_inA"><span>'.str_replace('[category]',stripslash_normal($row_cat['category_name']),stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_RECENT_NEW_PROD'])).'</span></div></div>
										<div class="my_hm_shlf_cont_divA">
											<div class="my_hm_shlf_cont_divA_top"> </div>';
											?>
											<div class="my_hm_shlf_pdt_con_in">
											<div class="myhme_link_nav"><a href="#null" onmouseover="scrollDivRight('containerFN<?=$row_favcat['category_id']?>')" onmouseout="stopMe()"><img src="<?php url_site_image('arrowq.gif')?>"></a></div>
											<div id="containerFN<?=$row_favcat['category_id']?>" class="my_hm_shlf_pdt_innerA">
											<div id="scroller" style="width:<?php echo $div_width?>px">
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
											</div>
											</div>
											<div class="myhme_link_nav"> <a href="#null" onmouseover="scrollDivLeft('containerFN<?=$row_favcat['category_id']?>',<?php echo ($div_width)?>)" onmouseout="stopMe()"><img src="<?php url_site_image('arrowqr.gif')?>"></a></div>
											</div>
										<?php	
										echo '<div class="my_hm_shlf_cont_divA_bottom"> </div>
										</div>
									</div>';
								} 
								
							echo '</div>
							<div class="my_hm_shlf_inner_bottom"></div>
							</div>
							</div>';
							}
						}
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
			$pass_type = 'image_gallerythumbpath';
			$Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME');
		?>
		<?php	
			$max_col = 2;
			$cur_col = 0;
			while ($row_prod = $db->fetch_array($ret_prods))
			{
				$HTML_prodimg =$HTML_prodname =$HTML_prodprice ='';
				$prod_arr[] = $row_prod['product_id'];
		
				
				$HTML_prodimg .='<div class="my_hm_shlf_imageA">
				<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
				// Calling the function to get the image to be shown
				$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
				if(count($img_arr))
				{
					$HTML_prodimg .=show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
				}
				else
				{
					// calling the function to get the default image
					$no_img = get_noimage('prod',$pass_type); 
					if ($no_img)
					{
						$HTML_prodimg .=show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
					}	
				}	
				$HTML_prodimg .='</a>
				</div>';
				$HTML_prodname .='<div class="my_hm_shlf_nameA"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
				$HTML_prodprice .='<div class="my_hm_shlf_priceA">';				
				$price_arr =  show_Price($row_prod,array(),'compshelf',false,3);
				if($price_arr['discounted_price'])
					$HTML_prodprice .=$price_arr['discounted_price'];
				else
					$HTML_prodprice .= $price_arr['base_price'];
				$HTML_prodprice .='</div>';
			    echo '<div class="my_hm_shlf_pdtA">';
				echo $HTML_prodimg;
				echo $HTML_prodname ;
				echo $HTML_prodprice;
				echo '</div>';
			}			
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
					
						echo '<div class="my_hm_shlf_outr">
							<div class="my_hm_shlf_inner">
								<div class="my_hm_rcnt_inner_top"></div>
									<div class="my_hm_rcnt_inner_cont">
									<div class="my_hm_rcnt_hdr_outr"><div class="my_hm_rcnt_hdr_in"><span>'.stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PROD']).'</span></div></div>
										<div class="my_hm_rcnt_cont_div">
											<div class="my_hm_rcnt_pdt_con">
												<ul class="my_hme_rcnt_pdtname">';
												while($row_prod = $db->fetch_array($ret_fav_products))
												{
												    $HTML_prodname =$HTML_prodimg='';
													$cnt++;
													$HTML_prodimg .=
													'<span class="spnimg">
													<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
													$pass_type = 'image_iconpath';
													// Calling the function to get the image to be shown
													$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
													if(count($img_arr))
													{
														$HTML_prodimg .=show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
													}
													else
													{
														// calling the function to get the default image
														$no_img = get_noimage('prod',$pass_type); 
														if ($no_img)
														{
															$HTML_prodimg .=show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
														}	
													}	
													 echo '<li ><span>';
													$HTML_prodimg .='</a></span>';
													$HTML_prodname=' <span class="spntxt"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></span>';
												    echo $HTML_prodimg;
												    echo $HTML_prodname;
													echo '</span></li>';
												}
											echo '	
												</ul>
											</div>
										</div>
									</div>
								<div class="my_hm_rcnt_inner_bottom"></div>
							</div>
						</div>';		
					break;
					case '2row': // case of vertical
					echo '<div class="my_hm_shlf_outr">
						<div class="my_hm_shlf_hdrC">'.stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PROD']).'</div>
							<div class="my_hm_shlf_inner">
							<div class="my_hm_shlf_inner_top"></div>
								<div class="my_hm_shlf_inner_cont">
									<div class="my_hm_shlf_cont_div">
										<div class="my_hm_shlf_pdt_con">';
										?>										
										<div class="det_link_nav"><a href="#null" onmouseover="scrollDivRight('containerFP')" onmouseout="stopMe()"><img src="<? url_site_image('arrow-left.gif')?>"></a></div>
											<div id="containerFP" class="my_hm_shlf_pdt_inner">
												<div id="scroller">
												<?php
												$cnts =$db->num_rows($ret_fav_products);
												while($row_prod = $db->fetch_array($ret_fav_products))
												{
												$prodcur_arr[] = $row_prod;
												$HTML_prodname =$HTML_prodimg=$HTML_prodprice='';
												
													
													$HTML_prodimg .='
													<div class="my_hm_shlf_image">
													<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
													// Calling the function to get the image to be shown
													$pass_type = get_default_imagetype('fav_prod');
													$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
													
													if(count($img_arr))
													{
														$HTML_prodimg .=show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
													}
													else
													{
														// calling the function to get the default image
														$no_img = get_noimage('prod',$pass_type); 
														if ($no_img)
														{
															$HTML_prodimg .=show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
														}	
													}	
													$HTML_prodimg .=' </a>
													</div>';
													$HTML_prodname  .='<div class="my_hm_shlf_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
													$HTML_prodprice .='<div class="my_hm_shlf_price">';
														$price_arr =  show_Price($row_prod,array(),'compshelf',false,3);
														if($price_arr['discounted_price'])
															$HTML_prodprice .=$price_arr['discounted_price'];
														else
															$HTML_prodprice .=$price_arr['base_price'];
														$HTML_prodprice .='</div>';
													echo '<div class="my_hm_shlf_pdt">';
													echo $HTML_prodimg;
													echo $HTML_prodname;
													echo $HTML_prodprice;	
													echo '</div>';
												}
											echo '</div>
											</div>
										<div class="det_link_nav"> <a href="#null" onmouseover="scrollDivLeft(\'containerFP\','.($cnts*65).')" onmouseout="stopMe()"><img src="'.url_site_image('arrow-right.gif',1).'"></a></div>
										</div>
									</div>
								</div>
							<div class="my_hm_shlf_inner_bottom"></div>
							</div>
						</div>';
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
							if($db->num_rows($ret_prods_new)==0) // case if no results found
							{
								// requery to find products which have recently added irrespective of whether it is added before or after last login
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
															$exclude_prod_str 
														ORDER BY 
															a.product_adddate DESC,a.product_webprice ASC 
														LIMIT 
															$prod_limit";
								$ret_prods_new = $db->query($sql_prod_first);
							}
							if($db->num_rows($ret_prods_offers) or 	$db->num_rows($ret_prods_new))
							{
								$totcnt++;
							echo '<div class="my_hm_shlf_outr">
								<div class="my_hm_shlf_hdrB">'.stripslash_normal($row_cats['category_name']).'</div>	
									<div class="my_hm_shlf_inner">
									<div class="my_hm_shlf_inner_top"></div>
										<div class="my_hm_shlf_inner_cont">';
										if($db->num_rows($ret_prods_offers))
										{
											$cnts = $db->num_rows($ret_prods_offers);
											$width_one_set 	= 143;
											$min_number_req	= 2;
											$min_width_req 	= $width_one_set * $min_number_req;
											$total_cnt		= $cnts;
											$calc_width		= $total_cnt * $width_one_set;
											if($calc_width < $min_width_req)
												$div_width = $min_width_req;
											else
												$div_width = $calc_width;
										echo '<div class="my_hm_shlf_inner_cont_left">
											<div class="my_hm_shlf_hdrA_outr"><div class="my_hm_shlf_hdrA_in"><span>'.str_replace('[category]',stripslash_normal($row_cats['category_name']),stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_MOST_HITS_CAT_OFFER'])).'</span></div></div>
												<div class="my_hm_shlf_cont_divA">
													<div class="my_hm_shlf_cont_divA_top"> </div>';
													?>
													<div class="my_hm_shlf_pdt_con_in">
													<div class="myhme_link_nav"><a href="#null" onmouseover="scrollDivRight('containerHF<?=$row_cats['category_id']?>')" onmouseout="stopMe()"><img src="<?php url_site_image('arrowq.gif')?>"></a></div>
													<div id="containerHF<?=$row_cats['category_id']?>" class="my_hm_shlf_pdt_innerA">
													<div id="scroller" style="width:<?php echo $div_width?>px">
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
													</div>
													</div>
													<div class="myhme_link_nav"> <a href="#null" onmouseover="scrollDivLeft('containerHF<?=$row_cats['category_id']?>',<?php echo ($div_width)?>)" onmouseout="stopMe()"><img src="<?php url_site_image('arrowqr.gif')?>"></a></div>
													</div>
													<?php 
													echo '<div class="my_hm_shlf_cont_divA_bottom"></div>
												</div>
											</div>';
										}
										if($db->num_rows($ret_prods_new))
										{
											$cnts = $db->num_rows($ret_prods_new);
											$width_one_set 	= 143;
											$min_number_req	= 2;
											$min_width_req 	= $width_one_set * $min_number_req;
											$total_cnt		= $cnts;
											$calc_width		= $total_cnt * $width_one_set;
											if($calc_width < $min_width_req)
												$div_width = $min_width_req;
											else
												$div_width = $calc_width;
										
										echo  '<div class="my_hm_shlf_inner_cont_left">
												<div class="my_hm_shlf_hdrA_outrA"><div class="my_hm_shlf_hdrA_inA"><span>'.str_replace('[category]', stripslash_normal($row_cats['category_name']),stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_MOST_HITS_CAT_NEW'])).'</span></div></div>
												<div class="my_hm_shlf_cont_divA">
													<div class="my_hm_shlf_cont_divA_top"> </div>';
													?>
													<div class="my_hm_shlf_pdt_con_in">
													<div class="myhme_link_nav"><a href="#null" onmouseover="scrollDivRight('containerHN<?=$row_cats['category_id']?>')" onmouseout="stopMe()"><img src="<?php url_site_image('arrowq.gif')?>"></a></div>
													<div id="containerHN<?=$row_cats['category_id']?>" class="my_hm_shlf_pdt_innerA">
													<div id="scroller" style="width:<?php echo $div_width?>px">
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
													</div>
													</div>
													<div class="myhme_link_nav"> <a href="#null" onmouseover="scrollDivLeft('containerHN<?=$row_cats['category_id']?>',<?php echo ($div_width)?>)" onmouseout="stopMe()"><img src="<?php url_site_image('arrowqr.gif')?>"></a></div>
													</div>
												<?php	
												echo '<div class="my_hm_shlf_cont_divA_bottom"> </div>
												</div>
											</div>';
										} 
										echo 
										'</div>
									<div class="my_hm_shlf_inner_bottom"></div>
									</div>
								</div>';
				         }
						}
			}	
				$used_val_arr['cats_arr'] = $cats_arr;
				$used_val_arr['prod_arr'] = $prod_arr;
				return $used_val_arr;	
		}
		function Display_Message($mesgHeader,$Message)
		{
		?>
			<div class="message_outer">
		    <div  class="message_header" > 
				<?php echo $mesgHeader;?>
			</div>
		   <div class="message"><?php echo $Message; ?></div>
		  </div>
		<?php	
		}
		
	};	
?>
			
