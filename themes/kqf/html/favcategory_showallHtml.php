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
			switch($Settings_arr['favoritecategory_prodlisting'])
			{ 
				case '3row':
					$max_col = 3;
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
							<div class="centerwrap">
							<div class="productlist">
<?php				foreach( $prodcur_arr as $k=>$product_array)
					{
						$prodcurtd_arr[] = $product_array;
						/*if($cur_row==0)
						{
						  echo "<tr>";
						}
						if($cur_row!=0 && $cur_row%2==0)
						{
							$cls = "prod_list_td";
						}
						else
						{
						   $cls = "prod_list_td_r";
						}*/
?>							<div class="productlist_item">
                        	<div class="product_container">
<?php
						//if ($cat_det['product_showimage']==1)// Check whether description is to be displayed
						//{
							if($product_array['product_newicon_show']==1)
							{
			?>		<div class="new"><img src="<?php url_site_image('icon_new.png')?>" width="39" height="19" alt="icon new" /></div>
			<?php			}
							if($product_array['product_saleicon_show']==1)
							{
			?>		<div class="new"><img src="<?php url_site_image('icon_sale.png')?>" width="50" height="51" alt="icon sale" /></div>
			<?php			}
			?>		<a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>">
			<?php			$pass_type = get_default_imagetype('midshelf');
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
			?>		</a>
			<?php		//}
			?>				</div>
			<?php	//if($cat_det['product_showtitle']==1)// whether title is to be displayed
						//{
			?>		<div class="product_name"><a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>"><?php echo stripslashes($product_array['product_name'])?></a></div>
			<?php
						//}
						if($product_array['product_bulkdiscount_allowed']=='Y')
						{
			?>		<div class="prod_list_bulk"><img src="<?php url_site_image('bulk.png')?>" /></div>
			<?php 
						}
						//if ($cat_det['product_showprice']==1)// Check whether description is to be displayed
						//{
							$price_class_arr['ul_class'] 		= 'price';
							$price_class_arr['normal_class'] 	= 'productprice';
							$price_class_arr['strike_class'] 	= 'retailprice';
							$price_class_arr['yousave_class'] 	= 'yousaveprice';
							$price_class_arr['discount_class'] 	= 'discountprice';
							echo show_Price($product_array,$price_class_arr,'cat_detail_1');
							//show_excluding_vat_msg($product_array,'vat_div');// show excluding VAT msg
						//}	
						/*if($cat_det['product_showbonuspoints']==1)
						{
							if($product_array['product_bonuspoints'] > 0)
							{
								echo '<div class="prod_list_bonusB">
									<span class="bonus_point_number_a">
										<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSA']).'</span>
									</span>
									<span class="bonus_point_caption_b">'.$product_array['product_bonuspoints'].'</span>
									<span class="bonus_point_number_c">
										<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSB']).'</span></span>
									</div>';
							}
						}*/
			?>
			<?php 
						if ($cat_det['product_showshortdescription']==1)// Check whether description is to be displayed
						{
			?>	<!--<div class="prod_list_des">
					<?php echo stripslashes($product_array['product_shortdesc'])?><?php show_moreinfo($product_array,'list_more')?>
				</div>-->
			<?php
						}
						if($product_array['product_saleicon_show']==1)
						{
							$desc = stripslash_normal(trim($product_array['product_saleicon_text']));
							if($desc!='')
							{
			?>	<!--<div class="prod_list_new"><?php echo $desc?></div>-->
			<?php			}
						}
						if($product_array['product_newicon_show']==1)
						{
							$desc = stripslash_normal(trim($product_array['product_newicon_text']));
							if($desc!='')
							{
			?>	<div class="prod_list_new"><?php echo $desc?></div>-->
			<?php			}
						}
			?>
				<div class="moreinfo">
				<a href="<?php url_product($product_array['product_id'],$product_array['product_name'],-1)?>" title="<?php echo stripslashes($product_array['product_name'])?>">More Info</a>
				</div>
				<div class="addtocartWrap">
					<div class="prod_list_buy">
			<?php		$frm_name = uniqid('catdet_');
			?>
					<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $product_array['product_id']?>)">
                    <input type="hidden" name="fpurpose" value="" />
                    <input type="hidden" name="fproduct_id" value="" />
                    <input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
                    <input type="hidden" name="fproduct_url" value="<?php url_product($product_array['product_id'],$product_array['product_name'])?>" />
			<?php
						$class_arr['ADD_TO_CART']       = '';
						$class_arr['PREORDER']          = '';
						$class_arr['ENQUIRE']           = '';
						$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
						$class_arr['QTY']               = ' ';
						$class_td['QTY']				= 'prod_list_buy_a';
						$class_td['TXT']				= 'prod_list_buy_b';
						$class_td['BTN']				= 'prod_list_buy_c';
						echo show_addtocart_v5($product_array,$class_arr,$frm_name,false,'','',true,$class_td);
			?>
					</form>
					</div>
				</div>
												
			<?php
						if($cur_row>=$max_col)
						{
							$cur_row = 0;
						}
						$cur_row ++;
			?>			
						</div>
			<?php
					}
			?>
				</div>
				</div>
					</td>
				</tr>
<?php				}
?>				</table>
<?php				
					break;
				
				}//end of switchcase
		}
	};	
?>
