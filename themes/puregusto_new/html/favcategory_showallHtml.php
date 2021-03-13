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
			return;
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
			$customer_id 					= get_session_var("ecom_login_customer");
			
			/*if(!$customer_id)
			{
				echo '<script language="javascript">
						window.location.href="'.url_link('registration.html').'";
						</script>';
			}*/
			if($customer_id)
			{
				$sql_last_login = "SELECT customer_last_login_date FROM customers WHERE sites_site_id=$ecom_siteid AND customer_id=$customer_id";
				$ret_last_login = $db->query($sql_last_login);
				list($row_last_login) = $db->fetch_array($ret_last_login);
			}
			
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
										a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
										a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
										a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
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

					$max_col = 2;
					$cur_col = 1;
					$cur_row = 1;
?>					<table border="0" cellpadding="0" cellspacing="0" class="shelfAtable">
<?php				if(count($prodcur_arr))
					{
?>					<tr>
						<td colspan="3" class="shelfAheader" align="left"><a href="<?php url_category($catid,$catname,-1,$_REQUEST['catgroup_id'],0)?>" class="categoreyname_headerlink" title="<?php echo stripslashes($catname)?>"><?php echo stripslashes($catname)?></a></td>
					</tr>
					<tr>
						<td colspan="3">
							<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
                            <tr>
									<td colspan="3" class="" align="center">
<?php					//$prodcur_arr = array();
						$pass_type = get_default_imagetype('midshelf');
						foreach( $prodcur_arr as $k=>$product_array)
						{
							
							$prodcurtd_arr[] = $product_array;
							$row_prod = $product_array;
							$col_cnts++;
							$unqid = uniqid('');
							$curmaskid = "myhome_$unqid";
							$wrapmainclass = ($col_cnts==3)?'newShelfWrapRight':'newShelfWrap';
							if($col_cnts==3)
							{
								$col_cnts =0;
							}
			?>				
							<div align="left" class="myhomeproddisp">
						<div class="<?php echo $wrapmainclass?>" id="catprod_<?php echo $row_prod['product_id']; ?>"  style="position:relative" onmouseover="showmask_myhome('<?php echo $curmaskid; ?>')" onmouseout="hidemask_myhome('<?php echo $curmaskid ?>')">
						<div id="<?php echo $curmaskid; ?>" class="overlay-box">
						
						<?php
							$frm_name = uniqid('quick_');
							?>
							<div id="containers">
							<div class="quickviewclass" onclick="quick_view_prod('<?php echo $row_prod['product_id'] ?>','<?php echo $frm_name?>')">
								
								<img src="<?php url_site_image('quick_view.png')?>" alt="Quick View">
								<form name="<?php echo $frm_name?>" id="<?php echo $frm_name?>">
								<input type="hidden" name="product_id" value="<?php echo $row_prod['product_id'] ?>">
								<input type="hidden" name="ajax_fpurpose" value="Quick_Prod_Show_Details">
								</form>	
								
								</div>
							<div class="moreinfoclass"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>"><img src="<?php url_site_image('more-info.png')?>"></a></div>
							<div class="prod_list_buy">
							<?php
							$frm_name = uniqid('addtocart_');
							?>
							<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
							<input type="hidden" name="fpurpose" value="" />
							<input type="hidden" name="fproduct_id" value="" />
							<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
							<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
							<input type="hidden" name="ajax_fpurpose_quick" id="ajax_fpurpose_quick<?php echo $frm_name ?>" value="">

							<?php
							$class_arr['ADD_TO_CART']       = '';
							$class_arr['PREORDER']          = '';
							$class_arr['ENQUIRE']           = '';
							$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
							$class_arr['QTY']               = ' ';
							$class_td['QTY']				= 'prod_list_buy_a';
							$class_td['TXT']				= 'prod_list_buy_b';
							$class_td['BTN']				= 'prod_list_buy_c';
							
							$class_arr['showquick']         = 1;
							echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
							?>
							</form>	
							</div>
							</div>
							</div>
							<?php
							//if ($shop_det['shopbrand_product_showimage']==1)// Check whether description is to be displayed
							{	
							?>	
								<div class="pro-wrap">
									<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php echo getmicrodata_producturl()?> class="product_image" >
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
								</div>
							<?php
							}
							?>
							<div class="list-ico-wrap">
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
							if($row_prod['product_discount']>0)
							{
							?>
								<div class="icon-list"><img src="<?php url_site_image('big-offer.png')?>" alt="offer"></div>
							<?php				
							}
							if($row_prod['product_bulkdiscount_allowed']=='Y')
							{
							?>
								<div class="icon-list"><img src="<?php url_site_image('big-bulk-discount.png')?>" alt="Bulk Discount"></div>
							<?php				
							}
							if($row_prod['product_show_pricepromise']==1)
							{
							?>
								<div class="icon-list"><img src="<?php url_site_image('big-price-promise.png')?>" alt="Price Promise"></div>
							<?php				
							}
							?>
							</div>
							<div class="newShelfdetails"><span class="shelfBprodname_three_column">
							<?php
							//if($shop_det['shopbrand_product_showtitle']==1)// whether title is to be displayed
							{
							?>
								<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><span><?php echo stripslashes($row_prod['product_name'])?></span></a>
							<?php
							}
							?>
							</span>
							<?php
							//if ($shop_det['shopbrand_product_showprice']==1)// Check whether description is to be displayed
							{
								 $price_class_arr['ul_class'] 		= 'shelfBul_three_column';
								$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
								$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
								$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
								$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
								//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
								echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
							}
							?>
							</div>
							</div>
			</div>	
            
<?php				}
			}
?>				</table>
<?php				
				
		}
	};	
?>