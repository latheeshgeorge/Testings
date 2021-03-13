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
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
							 global $shelf_for_inner;
							 $HTML_treemenu=$HTML_headermsg=$HTML_lgnhmrmsg=$HTML_Dischead=$HTML_tophomemsg=$HTML_topinnerdiv=$HTML_tophomeinnermsg='';
		
			
			$HTML_treemenu = '
				
				<div class="row breadcrumbs">
				<div class="container">
        <div class="container-tree">
          <ul>
				<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
				 <li> → '.stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_TREE_MENU']).'</li>

			 </ul>
    </div>
  </div></div>';	
  echo $HTML_treemenu;	
			  $sql_statid = "SELECT page_id,content FROM static_pages WHERE sites_site_id=$ecom_siteid AND pname='myhome_content' LIMIT 1" ;
			 $ret_statid = $db->query($sql_statid);
			 if($db->num_rows($ret_statid)>0)
			 {
				 ?>
				 <div class="container">
				 <?php
			 $row_statid = $db->fetch_array($ret_statid);
			 $_REQUEST['page_id'] = $row_statid['page_id'];
			 ?>
			 <div class="container">
				 <?php echo $row_statid['content'];?>
				 </div>
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
				?>
			   <div class="container">
				<?php
				while ($row_inline = $db->fetch_array($ret_inline))
				{
					//$modname 			= $row_inline['feature_modulename'];
					$body_dispcompid	= $row_inline['display_component_id'];
					$body_dispid		= $row_inline['display_id'];
					$body_title			= $row_inline['display_title'];
					$shelf_for_inner	= true;
					include ("includes/base_files/shelf.php");
					$shelf_for_inner	= false;

				}
				?>
				</div>
				<?php
			}
			?>
			</div>
			<?php
		  }
				
		}
		/* Function to get the list of recently purchased products categories*/
		function Show_Recently_Purchased_Products_With_Categories($used_val_arr)
		{		return;	
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
			/*echo
				'	<div class="my_hm_shlf_hdrA">'.$Captions_arr['LOGIN_HOME']['LOGIN_HOME_RECOMM_PROD_TITLE'].'</div>	
					';
					*/ 
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
							'<div class="my_hm_shlf_hdrA_outr container"><div class="my_hm_shlf_hdrA_in"><span>'.str_replace('[category]',stripslash_normal($row_cat['category_name']),stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_RECENT_RECOMM_PROD'])).'</span></div></div>
								';
									//*********************************************************
									// Show the list of products which have offers
									//*********************************************************
									if($db->num_rows($ret_prods_offers))
									{
										$cats_arr[] = $cur_cat_arr[$i]; // assigning the used category id to the array
										$prod_arr	= $this->Show_Products($ret_prods_offers,$prod_arr);
									}
										
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
						echo '<div class="my_hm_shlf_hdrA_inA"><span>'.str_replace('[category]',stripslash_normal($row_cat['category_name']),stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_RECENT_NEW_PROD'])).'</span></div></div>
									';
									//*******************************************************************
									// Show the list of new products since last login in current category
									//*******************************************************************
									if($db->num_rows($ret_prods_new))
									{
										$cats_arr[] = $cur_cat_arr[$i]; // assigning the used category id to the array
										$prod_arr	= $this->Show_Products($ret_prods_new,$prod_arr);
									}										
						} 				
					}
			}
			$used_val_arr['cats_arr'] = $cats_arr;
			$used_val_arr['prod_arr'] = $prod_arr;
			return $used_val_arr;	
		}
		function Show_Favourite_Categories($used_val_arr)
		{ 
			return;
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
							
						echo '
							<div class="my_hm_shlf_hdrA_inA"><span>Your Favourite Category: '.stripslash_normal($row_favcat['category_name']).'</span></div>	
							';
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
								
											//*********************************************************
											// Show the list of products which have offers
											//*********************************************************
											if($db->num_rows($ret_prods_offers))
											{
												$cats_arr[] = $row_favcat['category_id']; // assigning the used category id to the array
												$prod_arr	= $this->Show_Products($ret_prods_offers,$prod_arr);
											}											
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
									
											//*******************************************************************
											// Show the list of new products since last login in current category
											//*******************************************************************
											if($db->num_rows($ret_prods_new))
											{
												$cats_arr[] = $row_favcat['category_id']; // assigning the used category id to the array
												$prod_arr	= $this->Show_Products($ret_prods_new,$prod_arr);
											}	
											
								} 					
							}
						}
			}	
				$used_val_arr['cats_arr'] = $cats_arr;
				$used_val_arr['prod_arr'] = $prod_arr;
				return $used_val_arr;	
		}
		
		//function Show_Products($cat_id,$ret_prods,$prod_arr)
		function Show_Products($ret_prods,$prod_arr)
		{  return;
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents;
			$prod_compare_enabled = isProductCompareEnabled();
			$pass_type = 'image_gallerythumbpath';
			$Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME');
			?>
<div class="container">
	<div class="recent-title"><?php echo $cur_title;?></div>
								<div class="panel-group" id="accordion"><?php
	$rwCnt	=	1;
	while($row_prod = $db->fetch_array($ret_prods))
	{	
							
									$HTML_title = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a> ';
							
							
							?>
							<div class="titlehead"><?php  echo $HTML_title;?></div>
							<div class="img-container">
							<div class="single-products">
							<div class="productinfo-home text-center">
								<?php							
									$pass_type	=	'image_thumbpath';
									$img_arr	=	get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
									$tabimg_arr	=	get_imagelist('prod',$row_prod['product_id'],'image_bigpath',0,0,1);
									
									$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'" >';
									$curimgid = $shelfData['shelf_id'].'_'.$row_prod['product_id'];
									global $def_mainimg_id;
									$def_mainimg_id = $curimgid;
									// Calling the function to get the image to be shown
									if(count($img_arr))
									{
										//$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
										$HTML_image .= '<img src="'.url_root_image($img_arr[0][$pass_type],1).'" id="'.$curimgid.'">';														}
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
									echo $HTML_image;
									$price_arr =  show_Price($row_prod,array(),'compshelf',false,3);
									if($price_arr['discounted_price'])
										$HTML_price = $price_arr['discounted_price'];
									else
										$HTML_price = $price_arr['base_price'];
									?>

							</div>

							</div>
							</div>
							<?php 
							if($HTML_price!='')
							{
								?>
							<p class="rent-title"><?php echo $HTML_price;?></p>
                             <?php 
                             } ?> 
								<?php show_ProductLabels_Unipad($row_prod['product_id']); ?>
							
							<a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>" class="mainbtn btn-default book-now">book now</a>
							<?php
							$rwCnt++;
	}
?>
</div>

					    </div>
										<?php
			return $prod_arr;
		}
		
		function Show_Favourite_Products($used_val_arr)
		{ return;			
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
	echo
							'<div class="my_hm_shlf_hdrA_outr"><div class="my_hm_shlf_hdrA_in"><span>'.stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PROD']).'</span></div></div>
								';
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="shlf_table_1row">
<?php
	$rwCnt	=	1;
	while($row_prod = $db->fetch_array($ret_fav_products))
	{
		if($rwCnt % 2 == 0)
		{	$trCls	=	"shlf_table_1row_td_b";	}
		else
		{	$trCls	=	"shlf_table_1row_td_a";	}
		$rwCnt++;
?>
<tr>
	<td class="<?php echo $trCls;?>">
		<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shlf_1row_link">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="shlf_td_1row_img">
				<div class="shlf_table_1row_img">
				<?php
					$fld_mode	=	IMG_MODE;
					$fld_size	=	IMG_SIZE;
					//$fld_mode	=	'image_extralargepath';
					// Calling the function to get the image to be shown
					$pass_type = get_default_imagetype('midshelf');
					$img_arr = get_imagelist('prod',$row_prod['product_id'],$fld_mode,0,0,1);
					if(count($img_arr))
					{
						$imgPath	=	url_root_image($img_arr[0][$fld_mode],1);
						$imgProperty=	image_property($imgPath);
						//echo "<pre>";print_r($imgProperty);
						if($imgProperty['width'] > 0 && $imgProperty['height'] > 0 )
						{
							$newWidth	=	ceil($imgProperty['width']/$fld_size);//echo $newWidth;echo "<br>";
							$newHeight	=	ceil($imgProperty['height']/$fld_size);//echo $newHeight;echo "<br>";
							show_image_mobile($imgPath,$row_prod['product_name'],$row_prod['product_name'],'','',0,$newWidth,$newHeight);
						}
						else
						{
							show_image($imgPath,$row_prod['product_name'],$row_prod['product_name']);
						}
					}
					else
					{
						// calling the function to get the default image
						$no_img = get_noimage('prod',$pass_type); 
						if ($no_img)
						{
							$imgProperty	=	image_property($no_img);
						
							if($imgProperty['width'] > 0 && $imgProperty['height'] > 0 )
							{
								$newWidth	=	$imgProperty['width']/$fld_size;
								$newHeight	=	$imgProperty['height']/$fld_size;
								show_image_mobile($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',0,$newWidth,$newHeight);
							}
							else
							{
								show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
							}
						}
					}
				?>
				</div>
			</td>
			
			<td class="shlf_td_1row_desc">
				<div class="shlf_table_1row_otr"> 
					<div class="shlf_table_1row_name"><?php echo stripslash_normal($row_prod['product_name']);?></div> 
					<div class="shlf_table_1row_des"><?php echo stripslashes($row_prod['product_shortdesc'])?></div>
				<?php
					$price_arr =  show_Price($row_prod,array(),'cat_detail_1',false,6);
					if($price_arr['discounted_price'] != "" || $price_arr['base_price'] != "")
					{
				?>	<div class="shlf_table_1row_price">
				<?php	$price_class_arr['ul_class'] 		= 'row1_price';
						$price_class_arr['normal_class'] 	= 'row1_price_a';
						$price_class_arr['strike_class'] 	= 'row1_price_a';
						$price_class_arr['yousave_class'] 	= 'row1_price_b';
						$price_class_arr['discount_class'] 	= 'row1_price_b';
						//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
						//echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
						//show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
						if($price_arr['discounted_price'])
						   echo "<div class='row2_price_a'>".$Captions_arr['COMMON']['LIST_PRICE']." ".print_price($price_arr['discounted_price'])."</div>";
						else
							echo "<div class='row2_price_a'>".$Captions_arr['COMMON']['LIST_PRICE']." ".print_price($price_arr['base_price'])."</div>";
						if($price_arr['disc_percent'])
							echo "<br><div class='row2_price_b'>".$price_arr['disc_percent']."% ".$Captions_arr['COMMON']['FEAT_OFF']."</div>";
				?>	</div>
				<?php
					}
				?>
				</div>
			</td>
		</tr>
		</table>
		</a>
	</td>
</tr>
<?php
	}
?>
</table>
					<?php
				}
			$used_val_arr['cats_arr'] = $cats_arr;
			$used_val_arr['prod_arr'] = $prod_arr;
			return $used_val_arr;	
		}
		function Show_Highest_Hit_Categories($used_val_arr)
		{ return;
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
													/*
													?>
													<div class="my_hm_shlf_pdt_con_in">
													<div class="myhme_link_nav"><a href="#null" onmouseover="scrollDivRight('containerHF<?=$row_cats['category_id']?>')" onmouseout="stopMe()"><img src="<?php url_site_image('arrowq.gif')?>"></a></div>
													<div id="containerHF<?=$row_cats['category_id']?>" class="my_hm_shlf_pdt_innerA">
													<div id="scroller" style="width:<?php echo $div_width?>px">
													<?php
													*/	
													//*********************************************************
													// Show the list of products which have offers
													//*********************************************************
													if($db->num_rows($ret_prods_offers))
													{
														$cats_arr[] = $row_cats['category_id']; // assigning the used category id to the array
														$prod_arr	= $this->Show_Products($ret_prods_offers,$prod_arr);
													}
													/*	
													?>
													</div>
													</div>
													<div class="myhme_link_nav"> <a href="#null" onmouseover="scrollDivLeft('containerHF<?=$row_cats['category_id']?>',<?php echo ($div_width)?>)" onmouseout="stopMe()"><img src="<?php url_site_image('arrowqr.gif')?>"></a></div>
													</div>
													<?php 
													*/?>
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
													/*
													?>
													<div class="my_hm_shlf_pdt_con_in">
													<div class="myhme_link_nav"><a href="#null" onmouseover="scrollDivRight('containerHN<?=$row_cats['category_id']?>')" onmouseout="stopMe()"><img src="<?php url_site_image('arrowq.gif')?>"></a></div>
													<div id="containerHN<?=$row_cats['category_id']?>" class="my_hm_shlf_pdt_innerA">
													<div id="scroller" style="width:<?php echo $div_width?>px">
													<?php
													*/ 		
													//*******************************************************************
													// Show the list of new products since last login in current category
													//*******************************************************************
													if($db->num_rows($ret_prods_new))
													{
														$cats_arr[] = $row_cats['category_id']; // assigning the used category id to the array
														$prod_arr	= $this->Show_Products($ret_prods_new,$prod_arr);
													}
													/*		
													?>
													</div>
													</div>
													<div class="myhme_link_nav"> <a href="#null" onmouseover="scrollDivLeft('containerHN<?=$row_cats['category_id']?>',<?php echo ($div_width)?>)" onmouseout="stopMe()"><img src="<?php url_site_image('arrowqr.gif')?>"></a></div>
													</div>
												<?php
												*/ 	
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
		<div class="container">
			<div class="message_outer">
		    <div  class="message_header" > 
				<?php echo $mesgHeader;?>
			</div>
		   <div class="message"><?php echo $Message; ?></div>
		  </div>
		 </div> 
		<?php	
		}
		
	};	
?>
