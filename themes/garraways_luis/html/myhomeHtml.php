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
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$ecom_themename;
			$sql_user = "SELECT customer_title,customer_fname,customer_surname,customer_discount,customer_allow_product_discount,customer_bonus FROM customers where customer_id = ".get_session_var("ecom_login_customer")." LIMIT 1";
			$ret_user = $db->query($sql_user);
			if($db->num_rows($ret_user))
			{
				$row_user = $db->fetch_array($ret_user);
				$fullname = stripslashes($row_user['customer_fname']).' '.stripslashes($row_user['customer_surname']);
				$username = stripslashes($row_user['customer_title']).' '.stripslashes($row_user['customer_fname']).' '.stripslashes($row_user['customer_surname']);
				$customer_discount = $row_user['customer_discount'];
				$allow_discount = $row_user['customer_allow_product_discount'];
				$bonus_available = $row_user['customer_bonus'];
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
			echo '<div class="myhome_shlf_inner_cont">';	
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
				// $HTML_lgnhmrmsg .='<div class="my_hm_user_msg">'.stripslash_normal($fullname).'</div>';
				 $HTML_lgnhmrmsg .='<div class="my_hm_user_msg">'.stripslash_normal($str).'</div>';
				}
				echo $HTML_headermsg;
				echo $HTML_lgnhmrmsg;
				$cnt = 0;
				if($bonus_available>0 && $Captions_arr['LOGIN_HOME']['LOGIN_HOME_BONUS']!='')
				{
					$customer_bonus_mess = "<div class=\"my_hm_user\">";
					$customer_bonus_mess .= "<strong>".$Captions_arr['LOGIN_HOME']['LOGIN_HOME_BONUS']."&nbsp;:&nbsp;".$bonus_available."</strong>";	
					$customer_bonus_mess .="</div>";
				}		
				echo $customer_bonus_mess;		
			//$stat_id = 50080;
			$stat_id = 621;
			$_REQUEST['page_id'] = $stat_id;
			$sql_statpage = "SELECT page_id,title,content,pname,page_type,page_link,allow_auto_linker,page_link_newwindow 
                                        FROM 
                                            static_pages	 
                                        WHERE 
                                            page_id=".$stat_id." 
                                            AND sites_site_id=$ecom_siteid 
                                            AND hide = 0 
                                        LIMIT 
                                            1";
		$ret_statpage = $db->query($sql_statpage);
		if($db->num_rows($ret_statpage))
			$row_statpage 	= $db->fetch_array($ret_statpage);			
			  $sr_array  = array('rgb(0, 0, 0)','#000000');
				$rep_array = array('rgb(255,255,255)','#ffffff'); 
				$stat_cont = str_replace($sr_array,$rep_array,stripslashes($row_statpage['content']));
			  echo $stat_cont;
			  echo '</div>';
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
			{   global $shelf_for_inner_myhome;
				while ($row_inline = $db->fetch_array($ret_inline))
				{
					//$modname 			= $row_inline['feature_modulename'];
					$body_dispcompid	= $row_inline['display_component_id'];
					$body_dispid		= $row_inline['display_id'];
					$body_title			= $row_inline['display_title'];
					$shelf_for_inner   =  true;
					$shelf_for_inner_myhome	= true;
					//include ("includes/base_files/shelf.php");
					$shelf_for_inner_myhome	= false;

				}
			}
			/*Section to display particular named shelf in the home page*/
			$this->myhome_display_specialshelf();			
			//$used_val_arr['cats_arr'] = $cats_arr;
			//$used_val_arr['prod_arr'] = $prod_arr;
			//return $used_val_arr;
			
								
		}
		    function myhome_display_specialshelf()
			{
			   global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$ecom_themename;
//SELECT DISTINCT a.product_id,a.product_name FROM product_shelf_product c INNER JOIN products a ON a.product_id=c.products_product_id INNER JOIN product_shelf b ON b.shelf_id=c.product_shelf_shelf_id AND b.shelf_name='MyShelf' AND b.sites_site_id=111 
			$shelf_idname = 'MyShelf_myhome';
			$sql_shelf_details = "SELECT shelf_description FROM product_shelf WHERE shelf_name='$shelf_idname' AND sites_site_id=$ecom_siteid LIMIT 1";
			$ret_shelf_details = $db->query($sql_shelf_details);
			$row_shelf_details = $db->fetch_array($ret_shelf_details);
			$desc = trim($row_shelf_details['shelf_description']);
			if($desc!='' and $desc!='&nbsp;')
			{
				 $desc = stripslashes($desc);
				 $HTML_maindesc = '<div class="normal_shlfB_desc_outr">'.$desc.'</div>';
			}
			$sql_shelf_myhome = "SELECT 
									a.product_id,a.product_name,a.product_shortdesc,
									a.product_default_category_id,a.product_webprice,a.product_variablestock_allowed,
									a.product_show_cartlink,a.product_preorder_allowed,a.product_show_enquirelink,
									a.product_webstock,a.product_bulkdiscount_allowed,a.product_total_preorder_allowed,
									a.product_discount,a.product_discount_enteredasval,a.product_applytax,
									a.product_bonuspoints,a.product_variables_exists,a.product_variablesaddonprice_exists,
									a.product_stock_notification_required,a.product_alloworder_notinstock,product_variables_exists,
									a.product_variablesaddonprice_exists,a.product_variablecomboprice_allowed,
									a.product_variablecombocommon_image_allowed,a.default_comb_id,
									a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix,
									a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
									a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, 
									a.price_yousavesuffix,a.price_noprice,a.product_averagerating, 
									a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
									a.product_freedelivery,b.shelf_description  
								FROM 
									products a ,product_shelf b,product_shelf_product c 
								WHERE 
									b.shelf_name='$shelf_idname' 
								AND 
									b.sites_site_id=$ecom_siteid AND b.shelf_id=c.product_shelf_shelf_id 
								AND 
									a.product_id=c.products_product_id AND a.product_hide='N'";
					$ret_shelf_myhome = $db->query($sql_shelf_myhome);	
					if($db->num_rows($ret_shelf_myhome)>0)
					{   
						echo $HTML_maindesc;										
						?>      <style type="text/css">
								.pdt_list_thumb_outer{
								float:left;
								width:930px !important;

								margin:2px 0;
								}
								</style>                                                                     	
								<div class="shlf_a_bottom">
								<div class="shlf_a_outer">
								<div class="shlf_thumb_outer1">
								<?php
								$max_col = 4;
								$cur_col = 0;
								while($row_prod = $db->fetch_array($ret_shelf_myhome))
								{
								$price_arr1 =  show_Price($row_prod,array(),'compshelf',false,4);
								$prodcur_arr[] = $row_prod;
								$HTML_title = $HTML_image = $HTML_desc = '';
								$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
								$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';

								$HTML_title = '<div class="pdt_list_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal(str_replace('&','&amp;',$row_prod['product_name'])).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';


								$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
								// Calling the function to get the image to be shown
								//$pass_type ='image_thumbcategorypath';
								$pass_type = 'image_thumbpath';
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


								if($cur_col==0)
								{
								echo  '<div class="pdt_list_thumb_outer">';
								}	

								$cls = "pdt_list_pdt";
								?>



								<div class="<?php echo $cls?>">
								<div class="pdt_list_pdt_mid">
								<?php
								if($row_prod['product_saleicon_show']==1)
								{
								$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
								//if($desc!='')
								{
								$HTML_sale = '<div class="pdt_list_sale_3row"></div>';
								}
								}
								if($row_prod['product_newicon_show']==1)
								{
								$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
								//if($desc!='')
								{
								$HTML_new = '<div class="pdt_list_new_3row"></div>';
								}
								}
								echo $HTML_new;
								echo  $HTML_sale;
								?>

								<?php
								if($shelfData['shelf_showrating']==1)
								{
								$module_name = 'mod_product_reviews';
								if(in_array($module_name,$inlineSiteComponents))
								{
								if($row_prod['product_averagerating']>=0)
								{
								$HTML_rating = '<div class="list_d_pdt_rate">'.display_rating($row_prod['product_averagerating'],1).'</div>';
								}
								}
								}
								else
								{
								$HTML_rating = '&nbsp;';
								}
								//echo $HTML_rating;
								?>
								<?=$HTML_title;?>
								<div class="pdt_list_pdt_r"><?=$HTML_image?></div>
								<?php
								if($row_prod['product_shortdesc']!='')// whether title is to be displayed
								{
								echo '<p class="product_descB">'.stripslash_normal($row_prod['product_shortdesc']).'</p>';
								}

								//if ($shelfData['shelf_showprice']==1)
								{
								$price_class_arr['ul_class'] 		= 'shelfBul_lu';
								$price_class_arr['normal_class'] 	= 'shelfBnormalprice_lu';
								$price_class_arr['strike_class'] 	= 'shelfBstrikeprice_lu';
								$price_class_arr['yousave_class'] 	= 'shelfByousaveprice_lu';
								$price_class_arr['discount_class'] 	= 'shelfBdiscountprice_lu';
								//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
								echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
								show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
								}	
								?>											
								<div class="pdt_list_pdt_l">
								<div class="pdt_list_pdt_buy_otr">												
								<?php

								if($row_prod['product_bonuspoints']>0 and $shelfData['shelf_showbonuspoints']==1)
								{
								$HTML_bonus = 'Bonus: '.$row_prod['product_bonuspoints'];
								}
								else
								{
								$HTML_bonus = '&nbsp;';
								}

								$frm_name = uniqid('shelf_');
								?>
								<div class="more_info_lu">			

								<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" class="det_buy_link_more" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['MORE_INFO'])?></a>
								</div>
								<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
								<input type="hidden" name="fpurpose" value="" />
								<input type="hidden" name="fproduct_id" value="" />
								<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
								<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />																
								<input type="hidden" name="qty" id="qty" value="1" />																

								<?php								

								$class_arr                      = array();
								$class_arr['ADD_TO_CART']       = 'cupid-green_bt';
								$class_arr['PREORDER']          = 'cupid-green_bt';
								$class_arr['ENQUIRE']           = 'cupid-green_bt';
								$class_arr['QTY_DIV']           = 'normal_shlfB_pdt_input';
								$class_arr['QTY']               = '';							
								/* Code for ajax setting starts here */
								$class_arr['BTN_CLS']           = 'cupid-green';												
								show_addtocart_v5_ajax_local($frm_name,$row_prod,$class_arr,false,array(),true);							
								?>
								</form> 

								</div>
								<div class="pdt_list_m_otr">
								<?php
								getpacksize_puregusto($row_prod['product_id']);
								?>            
								</div>   
								</div>					

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
								</div>
								<?php
						
					}	
			 
			}
		/* Function to get the list of recently purchased products categories*/
		function Show_Recently_Purchased_Products_With_Categories($used_val_arr)
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
								$width_one_set 	= 200;
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
							$width_one_set 	= 200;
							$min_number_req	= 2;
							$min_width_req 	= $width_one_set * $min_number_req;
							$total_cnt		= $cnts;
							$calc_width		= $total_cnt * $width_one_set;
							if($calc_width < $min_width_req)
								$div_width = $min_width_req;
							else
								$div_width = $calc_width; 
						echo '<div class="my_hm_shlf_inner_cont_right">
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
		{ return;
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
									$width_one_set 	= 200;
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
									$width_one_set 	= 200;
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
		{  return;
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents;
			$prod_compare_enabled = isProductCompareEnabled();
			$pass_type = 'image_thumbpath';
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
					//$no_img = get_noimage('prod',$pass_type); 
					$no_img = url_site_image('no_small_image.png',1);
					if ($no_img)
					{
						$HTML_prodimg .=show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',1);
					}	
				}	
				$HTML_prodimg .='</a>';
				$HTML_prodimg .='</div>';
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
											$width_one_set 	= 200;
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
											$width_one_set 	= 200;
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
		{ return;
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
