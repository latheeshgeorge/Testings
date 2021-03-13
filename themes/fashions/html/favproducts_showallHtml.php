<?php
	/*############################################################################
	# Script Name 	: favcategory_showallHtml.php
	# Description 	: Page which holds the display logic for all products under fav category
	# Coded by 		: LSH
	# Created on	: 14-oct-2008
	# Modified by	: LH
	# Modified On	: 
	##########################################################################*/
	class favprodshowall_Html
	{
		// Defining function to show the shelf details
		function Show_favproducts($cust_id)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
			$customer_id 					= get_session_var("ecom_login_customer");

			$sql_last_login = "SELECT customer_last_login_date FROM customers WHERE sites_site_id=$ecom_siteid AND customer_id=$customer_id";
			$ret_last_login = $db->query($sql_last_login);
			list($row_last_login) = $db->fetch_array($ret_last_login);
		    $Captions_arr['LOGIN_HOME'] = getCaptions('LOGIN_HOME'); // to get values for the captions from the general settings site captions
			$prodcur_arr =array();
			$prodperpage			= ($Settings_arr['product_maxcnt_fav_category']>0)?$Settings_arr['product_maxcnt_fav_category']:10;//Hardcoded at the moment. Need to change to a variable that can be set in the console.
			//$limit = $Settings_arr['product_maxcnt_fav_category'];
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
			if ($_REQUEST['req']!='')// LIMIT for Favorites is applied only if not displayed in home page
			{
				$start_varprod 		= prepare_paging($_REQUEST[$pg_variableprod],$prodperpage,$tot_cntprod);
				$Limitprod			= " LIMIT ".$start_varprod['startrec'].", ".$prodperpage;
			}	
			else
				$Limitprod = '';
			$pg_variableprod		= 'prod_pg';
		  	$sql_fav_products = "SELECT id,a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
							a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
							a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
							product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints,
							a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
							a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
							a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
							a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
							a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
							a.product_freedelivery   	
								FROM 
									products a,customer_fav_products cfp
								WHERE
									 a.product_id = cfp.products_product_id AND a.product_hide='N'  AND
							cfp.sites_site_id = $ecom_siteid  AND cfp.customer_customer_id = $cust_id
								ORDER BY $prodsort_order $favsort_by $Limitprod	";
			$ret_fav_products = $db->query($sql_fav_products);
			$prod_compare_enabled = isProductCompareEnabled();
			?>
			<div class="best_seller_heading">
			<?php
			if ($db->num_rows($ret_fav_products)==1)
				echo stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCT_HEADER']);
			else
				echo stripslash_normal($Captions_arr['LOGIN_HOME']['LOGIN_HOME_FAV_PRODUCTS_HEADER']);
			?>
			</div>
			<?php
			while($row_prod = $db->fetch_array($ret_fav_products))
			{
			?>
				<div class="product_list_main">
				<ul>
				<li><h1 class="pro_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li>
					<li><h1>
					<div class="list_img" align="center">
					<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
					<?php
						// Calling the function to get the type of image to shown for current 
						$pass_type = get_default_imagetype('favprod');
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
					<?php
						$prefix = '<li>';
							$suffix = '</li>';
						if($comp_active)
						{
							dislplayCompareButton($row_prod['product_id'],$prefix,$suffix);
						}
						$price_class_arr['ul_class'] 		= '';
						$price_class_arr['normal_class'] 	= 'pro_price_offer';
						$price_class_arr['strike_class'] 	= 'pro_price';
						$price_class_arr['yousave_class'] 	= 'pro_price_offer';
						$price_class_arr['discount_class'] 	= 'pro_price_dis';
						echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
						$frm_name = uniqid('myhome_');
					?>	
						<li>
						<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
						<input type="hidden" name="fpurpose" value="" />
						<input type="hidden" name="fproduct_id" value="" />
						<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
						<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
						
						<label><?php show_moreinfo($row_prod,'product_info')?></label>
						<?php
							$prefix 					= "<div class='product_list_button_list'><label>"; 
							$suffix 					= "</label> </div>";
							$class_arr 					= array();
							$class_arr['ADD_TO_CART']	= 'product_list_button';
							$class_arr['PREORDER']		= 'product_list_button';
							$class_arr['ENQUIRE']		= 'product_list_button';
							show_addtocart($row_prod,$class_arr,$frm_name,false,$prefix,$suffix)
						?>
						</form>
						</li>
						</ul>
						</div>
			<? 
			}
		}	
	};	
?>