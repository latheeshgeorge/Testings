<?php
	/*############################################################################
	# Script Name 	: bestsellerHtml.php
	# Description 	: Page which holds the display logic for middle Bestsellers
	# Coded by 		: Sny
	# Created on	: 11-Aug-2009
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class bulkdiscount_Html
	{
		// Defining function to show the shelf details
		function Show_Bulkdiscount()
		{
		global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
		$Captions_arr['BULKDISC_PROD'] = getCaptions('BULKDISC_PROD');
		$bottom_content = '';
		$sql_bottom = "SELECT general_multibuy_bottomcontent 
							FROM 
								general_settings_sites_common 
							WHERE 
								sites_site_id = $ecom_siteid 
							LIMIT 
								1";
		$ret_bottom = $db->query($sql_bottom);
		if($db->num_rows($ret_bottom))
		{
			$row_bottom = $db->fetch_array($ret_bottom);
			$bottom_content = stripslashes($row_bottom['general_multibuy_bottomcontent']);
		}
		// query for display 	title
		$query_disp	= "SELECT 
					 display_title 
				FROM 
					display_settings 
				WHERE 
					sites_site_id='$ecom_siteid' 
					AND display_id ='$display_id'
					AND layout_code='$default_layout' ";
		$result_disp = $db->query($query_disp);
		list($cur_title) = $db->fetch_array($result_disp);
		$prodperpage			= $Settings_arr['product_maxcntperpage_bestseller'];
		$bestsort_order		= $Settings_arr['product_orderby_bestseller'];
		
		// ##############################################################################################################
		// Building the query for bestseller
		// ##############################################################################################################
		// Deciding the sort by field
		$prodsort_by			= ($_REQUEST['bulkdet_sortby'])?$_REQUEST['bulkdet_sortby']:'product_name';
		$prodperpage			= ($_REQUEST['bulkdet_prodperpage'])?$_REQUEST['bulkdet_prodperpage']:$Settings_arr['product_maxcntperpage_bestseller'];// product per page
		$prodsort_order			= ($_REQUEST['bulkdet_sortorder'])?$_REQUEST['bulkdet_sortorder']:$Settings_arr['product_orderby_bestseller'];
		$sql_tot	=	"SELECT count(a.product_id)  
				FROM 
					products a
				WHERE 
					a.sites_site_id = $ecom_siteid 
					AND a.product_hide ='N' 
					AND a.product_bulkdiscount_allowed='Y'";
		$ret_tot 	= $db->query($sql_tot);
		list($tot_cnt)		= 	$db->fetch_array($ret_tot);		
		// Building the sql 
		$sql_best			= '';
		// Call the function which prepares variables to implement paging
		$ret_arr 			= array();
		$pg_variable		= 'bulk_pg';
		$start_var 			= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
		$Limit				= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
		switch ($prodsort_by)
		{
			case 'product_name': // case of order by product name
			$prodsort_bysql		= 'a.product_name';
			break;
			case 'price': // case of order by price
			$prodsort_bysql		= 'a.product_webprice';
			break;
			case 'product_id': // case of order by price
			$prodsort_bysql		= 'a.product_id';
			break;
			default: // by default order by product name
			$prodsort_bysql		= 'a.product_name';
			break;
		};
		$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
					a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
					a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
					product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints,
					a.product_stock_notification_required,a.product_alloworder_notinstock,product_variables_exists,product_variablesaddonprice_exists,
					a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
					a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
					a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
					a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
					a.product_freedelivery        
				FROM 
					products a
				WHERE 
					a.product_hide = 'N' 
					AND a.sites_site_id = $ecom_siteid
					AND a.product_bulkdiscount_allowed='Y' 
					ORDER BY 
							$prodsort_bysql $prodsort_order 
						$Limit ";
		$ret_prod = $db->query($sql_prod);
		$HTML_tophead = $HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
		$comp_active = isProductCompareEnabled();
		// Calling the function to get the type of image to shown for current 
		$pass_type = get_default_imagetype('midshelf');
		$prod_compare_enabled = isProductCompareEnabled();
		// Number of result to display on the page, will be in the LIMIT of the sql query also
		$querystring = ""; // if any additional query string required specify it over here
$HTML_treemenu = '			<div class="tree_menu_con_list">
								<div class="tree_menu_top_list"></div>
								<div class="tree_menu_mid_list">
								<div class="tree_menu_content_list">
								  <ul class="tree_menu">
								<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
								 <li>'.stripslash_normal($Captions_arr['BULKDISC_PROD']['BULKDISC_PROD']).'</li>
								</ul>
								   </div>
								</div>
								<div class="tree_menu_bottom_list"></div>
								</div>';
												
		if ($db->num_rows($ret_prod))
		{
					$max_col = 2;
					$cur_col = 0;
					$prodcur_arr = array();
					while($row_prod = $db->fetch_array($ret_prod))
					{ 
					$prodcur_arr[] = $row_prod;
					$HTML_title = $HTML_image = $HTML_desc = '';
					$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
					$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
						$HTML_title = '<div class="list_d_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
						$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
						// Calling the function to get the image to be shown
						$pass_type ='image_thumbpath';
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
					if($row_prod['product_saleicon_show']==1)
					{
						$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
						if($desc!='')
						{
							  $HTML_sale = '<div class="list_d_sale"></div>';
						}
					}
					if($row_prod['product_newicon_show']==1)
					{
						$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
						if($desc!='')
						{
							  $HTML_new = '<div class="list_d_new"></div>';
						}
					}
						$module_name = 'mod_product_reviews';
						if(in_array($module_name,$inlineSiteComponents))
						{
							if($row_prod['product_averagerating']>=0)
							{
								$HTML_rating = '<div class="normal_shlfA_pdt_rate">'.display_rating($row_prod['product_averagerating'],1).'</div>';
							}
						}
						$price_class_arr['class_type']          = 'div';
						$price_class_arr['normal_class']        = 'normal_shlfA_pdt_priceA';
						$price_class_arr['strike_class']        = 'normal_shlfA_pdt_priceB';
						$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
						$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
						$HTML_price_arry = show_Price($row_prod,$price_class_arr,'shelfcenter_3','',3);
						if($HTML_price_arry['discounted_price'])
						{
							$HTML_price=$HTML_price_arry['discounted_price'];
						}
						else
						{
							$HTML_price=$HTML_price_arry['base_price'];
						}
						if($row_prod['product_bulkdiscount_allowed']=='Y')
						{
							$HTML_bulk = '<img src="'.url_site_image('bulk-d.gif',1).'" title="Multi Buy" />';
						}
							
						if($row_prod['product_bonuspoints']>0 and $cat_det['product_showbonuspoints']==1)
						{
							$HTML_bonus = '<div class="normal_shlfA_pdt_bonus">Bonus: '.$row_prod['product_bonuspoints'].'</div>';
						}
						else
						{
							$HTML_bonus = '';
						}	
						if($row_prod['product_freedelivery']==1)
						{
							$HTML_freedel = ' <img src="'.url_site_image('freedel.gif',1).'" title="Free Delivery"/>';
						}
						$frm_name = uniqid('shelf_');
						if($cur_col==0)
						{
							echo  '<div class="list_d_pdt_otr_row">';
						}
						?>
                        <div class="list_d_pdt_otr">
                        <?php
                            echo $HTML_new;
                            echo $HTML_sale;
                        ?>	
                        <div class="list_d_pdt_name_otr">
                        <?=$HTML_title;?>
                        <div class="list_d_pdt_des"><?=$HTML_desc?></div>
                        </div>
                        <div class="list_d_pdt_img"><?=$HTML_image?></div>
                        <div class="list_d_pdt_buy">
                        
                        <div class="list_d_pdt_buy_ba"> 
                        <div class="list_d_pdt_price"> <?php echo $HTML_price; ?></div>
                        <div class="list_d_pdt_buy_in"><a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']); ?>">BUY NOW</a></div>
                        </div>
                        </div>
                        <?=$HTML_rating;?>	
                         <div class="list_d_pdt_offer"><?=$HTML_bulk?><?=$HTML_freedel?></div>
                        </div> 
					<?
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
		}
		else
		{
					echo $HTML_treemenu;	
						$desc = stripslash_normal($Captions_arr['BULKDISC_PROD']['BULKDISC_NOPROD']);
						if($desc!='' and $desc!='&nbsp;')
						{
							 $desc = stripslashes($desc);
							 $HTML_maindesc = '<div class="normal_shlfB_desc_outr">'.$desc.'</div>';
						}	
						?>
							<div class="normal_shlf_list">
						<div class="normal_shlf_list_top"></div>
						<div class="normal_shlf_list_mid">
								<?php
								echo $HTML_maindesc;
								?>
							<div class="normal_shlf_list_bottom"></div> 
						</div>   
						</div>
						<?php		
		
		}
		}
	};	
?>