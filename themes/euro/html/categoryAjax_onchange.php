<?php
	include("../../../config.php");
	include("../../../includes/price_display.php");
	include("../../../functions/functions.php");
	require("../../../includes/session.php");
	require("../../../includes/urls.php");
	require("../../../includes/cartCalc_ajax.php");
	
	if(file_exists($image_path.'/settings_cache/general_settings.php'))
		include "$image_path/settings_cache/general_settings.php";
	
	// Including the price display settings array file
		if(file_exists($image_path.'/settings_cache/price_display_settings.php'))
			include "$image_path/settings_cache/price_display_settings.php";	

		// Calling the function to get all the captions set in the COMMON section
		$Captions_arr['COMMON']	= getCaptions('COMMON');
	
		// Calling the function to get the details of default currency
		$default_Currency_arr	= get_default_currency();
	
		// Assigning the current currency to the variable
		$sitesel_curr			= get_session_var('SEL_CURR');
		// If sitesel_curr have no value then set it as the default currency
		if (!$sitesel_curr)
		{
			$sitesel_curr		= $default_Currency_arr['currency_id'];// setting the default currency value
		}
		// Get details of current currency
		$current_currency_details = get_current_currency_details();
	
if($_REQUEST['fpurpose']=='ajax_show_variable_price')
{
	$prod_id 	= $_REQUEST['prodid'];
	$val_id_arr	= explode('~',$_REQUEST['pass_var']);
	$var_arrs	= explode('~',$_REQUEST['pass_varid']);
	$cur_qty   = $_REQUEST['cur_qty'];
	for($i=0;$i<count($var_arrs);$i++)
	{
		$var_arr[$var_arrs[$i]] = $val_id_arr[$i];
	}
	show_Variable_price_detail($prod_id,$var_arr,$cur_qty);
}
elseif ($_REQUEST['fpurpose']=='ajax_show_bulk_discount')
{
	$prod_id 	= $_REQUEST['prodid'];
	$val_id_arr	= explode('~',$_REQUEST['pass_var']);
	$var_arrs	= explode('~',$_REQUEST['pass_varid']);
	for($i=0;$i<count($var_arrs);$i++)
	{
		$var_arr[$var_arrs[$i]] = $val_id_arr[$i];
	}
	show_BulkDiscounts_prod_detail($prod_id,$var_arr);
}
/*elseif ($_REQUEST['fpurpose']=='ajax_show_bulk_discount')
{
	$prod_id 	= $_REQUEST['prodid'];
	$val_id_arr	= explode('~',$_REQUEST['pass_var']);
	$var_arrs	= explode('~',$_REQUEST['pass_varid']);
	for($i=0;$i<count($var_arrs);$i++)
	{
		$var_arr[$var_arrs[$i]] = $val_id_arr[$i];
	}
	show_BulkDiscounts_prod_detail($prod_id,$var_arr);
}*/
elseif ($_REQUEST['fpurpose']=='ajax_show_main_image')
{
	$prod_id 	= $_REQUEST['prodid'];
	$val_id_arr	= explode('~',$_REQUEST['pass_var']);
	$var_arrs	= explode('~',$_REQUEST['pass_varid']);
	for($i=0;$i<count($var_arrs);$i++)
	{
		$var_arr[$var_arrs[$i]] = $val_id_arr[$i];
	}
	show_Variable_main_image($prod_id,$var_arr);
}
elseif ($_REQUEST['fpurpose']=='ajax_show_more_image')
{
	$prod_id 	= $_REQUEST['prodid'];
	$val_id_arr	= explode('~',$_REQUEST['pass_var']);
	$var_arrs	= explode('~',$_REQUEST['pass_varid']);
	$exclude_id		= $_REQUEST['exclude_id'];
	for($i=0;$i<count($var_arrs);$i++)
	{
		$var_arr[$var_arrs[$i]] = $val_id_arr[$i];
	}
	show_Variable_more_images($prod_id,$exclude_id,$var_arr);
}
if ($_REQUEST['curval'])
{
	if($_REQUEST['page_type']=='cont_change') // handling case to change tab content using ajax
	{
		$tab_id 		= $_REQUEST['tabid'];
		$prod_id	= $_REQUEST['prodid'];
		if($prod_id)
		{
			if($tab_id>0)
			{
				$sql_tab = "SELECT tab_content 
									FROM 
										product_tabs 
									WHERE 
										tab_id = $tab_id 
										AND products_product_id = $prod_id 
									LIMIT 
										1";
				$ret_tab = $db->query($sql_tab);
				if($db->num_rows($ret_tab))
				{
					$row_tab = $db->fetch_array($ret_tab);
					echo nl2br(stripslashes($row_tab['tab_content']));
				}						
			}
			elseif($tab_id==0)
			{
				$sql_prod = "SELECT product_longdesc,product_shortdesc 
										FROM 
											products 
										WHERE 
											product_id =$prod_id  
											AND product_hide = 'N' 
										LIMIT 
											1";
				$ret_prod = $db->query($sql_prod);
				if ($db->num_rows($ret_prod))
				{
					$row_prod = $db->fetch_array($ret_prod);
					if($row_prod['product_longdesc'])
						$content	= stripslashes($row_prod['product_longdesc']);
					elseif ($row_prod['product_shortdesc'])
						$content	= stripslashes($row_prod['product_shortdesc']);
					echo nl2br(stripslashes($content));	
				}		
			}
		}	
	}
	 elseif($_REQUEST['page_type'] == 'cat') // case of showing subcategories
	 {

		// Check whether subcategories exists under current product
		$sql_subcat = "SELECT category_id ,category_name 
								FROM 
									product_categories 
								WHERE 
									sites_site_id = $ecom_siteid 
									AND parent_id = ".$_REQUEST['curval']." 
									AND category_hide = 'N'  
								ORDER BY 
									category_order ASC";
		$ret_subcat = $db->query($sql_subcat);
		if ($db->num_rows($ret_subcat))
		{
			$t = $_REQUEST['counter']; $t++; 
			$show_desc = '';
			//Get the short description of parent category
			$sql_pcat = "SELECT category_shortdescription 
									FROM 
										product_categories 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND category_id =".$_REQUEST['curval']."  
										AND category_hide = 'N' 
									LIMIT 
										1";
			$ret_pcat = $db->query($sql_pcat);
			if($db->num_rows($ret_pcat))
			{
				$row_pcat = $db->fetch_array($ret_pcat);
				$show_desc_arr = explode('~',stripslashes($row_pcat['category_shortdescription']));
				if(count($show_desc_arr)>1)
				{
					$show_desc = $show_desc_arr[1];
				}
				else
					$show_desc = $show_desc_arr[0];
			}							
		?>
			<div class="pro_ajx" >
			<div class="pro_ajx_hdr">
			<div class="pro_ajx_stplt">Step <?php echo $t?></div>
			<div class="pro_ajx_stptxt"><?php echo $show_desc?></div>
			</div>
			<div class="pro_ajx_cont">
				<select name="first_cat_<?=$_REQUEST['counter']?>" id="first_cat_<?=$_REQUEST['counter']?>" onChange="handle_div(this,<?=$_REQUEST['counter']?>,'cat')" >
					<option value="">--Select --</option>
				<?php
				while($row_subcat = $db->fetch_array($ret_subcat)) 
				{
				?>
					<option value="<?=$row_subcat['category_id']?>"><?=$row_subcat['category_name']?></option>
				<?
				}
				?>
				</select>
			</div>
			</div>
		<div id="<?=$t?>" class="pro_ajx_cont_sub"></div>
	
		<?php
	}
	 else // case of showing products select box
	 {
	 		$show_desc = '';
			//Get the short description of parent category
			$sql_pcat = "SELECT category_shortdescription,product_orderfield ,product_orderby
									FROM 
										product_categories 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND category_id =".$_REQUEST['curval']." 
										AND category_hide = 'N' 
									LIMIT 
										1";
			$ret_pcat = $db->query($sql_pcat);
			if($db->num_rows($ret_pcat))
			{
				$row_pcat = $db->fetch_array($ret_pcat);
				$show_desc_arr = explode('~',stripslashes($row_pcat['category_shortdescription']));
				if(count($show_desc_arr)>1)
				{
					$show_desc = $show_desc_arr[1];
				}
				else
					$show_desc = $show_desc_arr[0];
				
				if(trim($row_pcat['product_orderfield'])!='')
				{
					$def_orderfield 	= $row_pcat['product_orderfield'];
					$def_orderby		= $row_pcat['product_orderby'];
				}
				else
				{
					$def_orderfield 	= 'product_name';
					$def_orderby		= 'ASC';
				}	
				switch ($def_orderfield)
				{
					case 'custom': // case of order by customer fiekd
					$def_orderfield		= 'b.product_order';
					break;
					case 'product_name': // case of order by product name
					$def_orderfield		= 'a.product_name';
					break;
					case 'price': // case of order by price
					$def_orderfield		= 'a.product_webprice';
					break;
					case 'product_id': // case of order by price
					$def_orderfield		= 'a.product_id';
					break;
					default: // by default order by product name
					$def_orderfield		= 'a.product_name';
					break;
				};
			}	
		
		// Check whether products exists under current category
		$sql_prod = "SELECT a.product_id,a.product_name 
								FROM 
									products a, product_category_map b 
								WHERE 
									a.sites_site_id = $ecom_siteid 
									AND a.product_id = b.products_product_id 
									AND b.product_categories_category_id = ".$_REQUEST['curval']." 
									AND a.product_hide = 'N' 
								ORDER BY 
									$def_orderfield $def_orderby ";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			
			$t = $_REQUEST['counter'];
			$t++; 
		?>
		
			<div class="pro_ajx" >
			<div class="pro_ajx_hdr">
			<div class="pro_ajx_stplt">Step <?php echo $t?></div>
			<div class="pro_ajx_stptxt"><?php echo $show_desc?></div>
			</div>
			<div class="pro_ajx_cont">
				<select name="product_id" id="product_id" onchange="handle_div(this,<?=$_REQUEST['counter']?>,'pdt')">
					<option value="">--Select --</option>
				<?php
				while($row_prod = $db->fetch_array($ret_prod)) 
				{
				?>
					<option value="<?=$row_prod['product_id']?>"><?=$row_prod['product_name']?></option>
				<?
				}
				?>
				</select>
			</div>
			</div>
			<div id="<?=$t?>" class="pro_ajx_cont_sub"></div>
	<?php
		}
		else // case if no products exists
		{
		?>
		<div class="pro_ajx" >
		<div class="pro_ajx_hdr">
			<div class="pro_ajx_noprods">
			Sorry no products under this category
			</div>
		</div>
		</div>
		<?php	
		}
	}
	} 
	else
	{
		$pass_product_id = $_REQUEST['curval'];
		$sql_prod = "SELECT * 
								FROM 
									products 
								WHERE 
									product_id =$pass_product_id 
									AND product_hide = 'N' 
								LIMIT 
									1";
		$ret_prod = $db->query($sql_prod);		
		$row_prod = $db->fetch_array($ret_prod);	
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		// Including the general settings array file
		if(file_exists($image_path.'/settings_cache/general_settings.php'))
			include "$image_path/settings_cache/general_settings.php";
			
		// Including the price display settings array file
		if(file_exists($image_path.'/settings_cache/price_display_settings.php'))
			include "$image_path/settings_cache/price_display_settings.php";	
	
		$ecom_common_settings 			= get_Common_Settings();
		$ecom_tax_total_arr 					= $ecom_common_settings['tax'];// // Calling the function to get the tax for the current site
		$Captions_arr['COMMON']				= getCaptions('COMMON');
		// Get the captions for price
		$Captions_arr['PRICE_DISPLAY']	= getCaptions('PRICE_DISPLAY');
	
		// Calling the function to get the details of default currency
		$default_Currency_arr					= get_default_currency();
	
		// Assigning the current currency to the variable
		$sitesel_curr								= get_session_var('SITE_CURR');
		
		if($_REQUEST['cbo_selcurrency'])
		{
			$sitesel_curr = $_REQUEST['cbo_selcurrency'];
			set_session_var('SITE_CURR',$sitesel_curr);
			//Finding the symbol for current currency
			$sql_curr  	= "SELECT curr_sign_char FROM general_settings_site_currency WHERE currency_id = $sitesel_curr";
			$ret_curr	= $db->query($sql_curr);
			if($db->num_rows($ret_curr))
			{
				$row_curr  			= $db->fetch_array($ret_curr);
				$sitesel_currsign 		= $row_curr['curr_sign_char'];
			}
			set_session_var('SITE_CURR_SIGN',$sitesel_currsign);
		}
		// If sitesel_curr have no value then set it as the default currency
		if (!$sitesel_curr)
		{
			$sitesel_curr		= $default_Currency_arr['currency_id'];// setting the default currency value
		}
		// Get details of current currency
		$current_currency_details = get_current_currency_details();
		echo '
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="fproduct_id" value="'.$pass_product_id.'" />
			<input type="hidden" name="pricepromise_url" value="'.url_link('pricepromise'.$pass_product_id.'.html',1).'" />
			<input type="hidden" name="pass_url" value="'.$_SERVER['HTTP_REFERER'].'" />
			<input type="hidden" name="pass_combid" value="" />
			<input type="hidden" name="comb_img_allowed" id="comb_img_allowed" value="'.$row_prod['product_variablecombocommon_image_allowed'].'" />
			<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
			<input type="hidden" name="ajax_qty_change" id="ajax_qty_change" value="" />
			<input type="hidden" name="pagetype" id="pagetype" value="" />
		';
		?>
		<div class="ajx_det_main" >
			<div class="ajx_det_main_tp"></div>
		   <div class="ajx_det_main_mid">
		  
		   <div class="ajx_det_image">
		    <?php
			
						echo '<div class="pro_det_image" id="mainimage_holder" style="height:300px">';
						$ret_arr 				=	Show_Image_Normal($row_prod);
						echo '</div>';
						$exclude_tabid		= $ret_arr['exclude_tabid'];
						$exclude_prodid		= $ret_arr['exclude_prodid'];
						$imgs_main_arr = explode('big/',$ret_arr['img_det']);
						if ($ret_arr['img_det']=='')
							$price_det_class = 'ajx_det_price_no_img';
						else
							$price_det_class = 'ajx_det_price';
						echo '<div id="moreimage_holder">';
					   show_more_images($row_prod,$exclude_tabid,$exclude_prodid);
					   echo '</div>';
			?>
			</div>
			<div class="<?php echo $price_det_class?>">
			<?php
					
					show_ProductVariables($row_prod,'column');
					if ($Settings_arr['bonus_points_instock'] and $row_prod['product_bonuspoints']>0)
					{ 
						 $sql 				= "SELECT bonus_point_details_content FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid." LIMIT 1";
						$res_admin 		= $db->query($sql);
						$fetch_arr_admin 	= $db->fetch_array($res_admin);
						$HTML_content_bonus ='';
						if($fetch_arr_admin['bonus_point_details_content']!='')
						{
						$HTML_content_bonus = '<div class="deat_bonusC"><a href="'.url_link('bonuspoint_content.html',1).'" title=""><img src="'.url_site_image('bonusmoreinfo.gif',1).'" border="0" /></a></div>';
						}
						$HTML_bonus = '<div class="deat_bonus_ajx">
										<div class="deat_bonusA">'.$row_prod['product_bonuspoints'].'</div>
										<div class="deat_bonusB">'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTS']).'</div>'.$HTML_content_bonus.
										'</div>';
					}
					
					echo '<div id="price_holder">';
					$row_prod['combination_id'] 		= $row_prod['default_comb_id']; // this done to handle the case of showing the variables price in show price
					$row_prod['check_comb_price'] 	= 'YES';// this done to handle the case of showing the variables price in show price
					$price_class_arr['ul_class'] 			= 'ajx_picr_ul';
					$price_class_arr['normal_class'] 		= 'ajx_normalprice';
					$price_class_arr['strike_class'] 		= 'ajx_strikeprice';
					$price_class_arr['yousave_class'] 	= 'ajx_yousaveprice';
					$price_class_arr['discount_class'] 	= 'ajx_discountprice';				
					//echo show_Price($row_prod,$price_class_arr,'prod_detail');	
					$ret_data =  cartCalc_ajax($row_prod['product_id'],array(),0);
					echo prepare_price_Ajax($ret_data,$price_class_arr,1);
					echo '</div>';
					if($row_prod['product_flv_filename']!='')
						{
							echo $HTML_video = '<div class="deat_pdt_button_ajx">
											<a href="javascript:show_normal_video()"><img src="'.url_site_image('video-but.gif',1).'" border="0" /></a>
											</div>
											<div id="div_defaultFlash_outer" class="flashvideo_outer" style="display:none"></div>
											<div style="display: none;" id="div_defaultFlash" class="content_default_flash">
											<div id="flash_close_div" align="right"><a href="javascript:close_video()">Close</a></div>
											<div id="flash_player_div">
											<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://macromedia.com/cabs/swflash.cab#version=6,0,0,0" ID=flaMovie WIDTH=500 HEIGHT=350>
											<param NAME=movie VALUE="http://'.$ecom_hostname.'/images/'.$ecom_hostname.'/swf/player.swf">
											<param NAME=FlashVars VALUE="file=http://'.$ecom_hostname.'/images/'.$ecom_hostname.'/product_flv/'.$row_prod['product_flv_filename'].'">
											<param NAME=quality VALUE=medium>
											<param NAME=bgcolor VALUE=#99CC33>
											<embed src="http://'.$ecom_hostname.'/images/'.$ecom_hostname.'/swf/player.swf" FlashVars="file=http://'.$ecom_hostname.'/images/'.$ecom_hostname.'/product_flv/'.$row_prod['product_flv_filename'].'" bgcolor=#99CC33 WIDTH=500 HEIGHT=350 TYPE="application/x-shockwave-flash">
											</embed>
											</object>
											</div>
											</div>';
						}
					echo '<div id="bulkdisc_holder" style="display:block; clear:both">';
					show_BulkDiscounts($row_prod['product_id']);
					echo '</div>';
					show_buttons($row_prod);
					echo $HTML_bonus;
			?>
			</div>
			
		   </div>
		   

		   <div class="ajx_det_main_btm"></div>
		   </div>
		   <?php
		   show_Downloads($row_prod['product_id']);
		   	//Check whether tab exists for current product
			$sql_tab = "SELECT tab_id,tab_title 
								FROM 
									product_tabs 
								WHERE 
									products_product_id = ".$pass_product_id."
									AND tab_hide=0 
								ORDER BY 
									tab_order";
			$ret_tab = $db->query($sql_tab);
			if($row_prod['product_longdesc'])
				$content	= stripslashes($row_prod['product_longdesc']);
			elseif ($row_prod['product_shortdesc'])
				$content	= stripslashes($row_prod['product_shortdesc']);
			if((trim($content)!='' and trim($content)!='<br>') or $db->num_rows($ret_tab)>0)
			{
		   ?>
			<div class="ajx_det_main" >
			<div class="ajx_det_main_tp"></div>
				<div class="ajx_des_mid">
					<div class="ajx_det_tab" >
						<div class="protabcontainer">
						<ul class="protab">
						<li class="selectedtab" id="tabid_0" onclick="handle_ajax_desc('0','<?php echo $row_prod['product_id']?>')">Overview</li>
						<?php
						$cur_tab_ids = 0;
							if($db->num_rows($ret_tab))
							{
								while ($row_tab = $db->fetch_array($ret_tab))
								{
									$cur_tab_ids .= '~'.$row_tab['tab_id'];
						?>	
									<li id="tabid_<?php echo $row_tab['tab_id']?>" onclick="handle_ajax_desc('<?php echo $row_tab['tab_id']?>','<?php echo $row_prod['product_id']?>')"><?php echo stripslashes($row_tab['tab_title'])?></li>
						<?php
								}
							}
						?>
						</ul>
						</div>
						<div class="ajx_det_cont" id="proddet_maincontent"><?php echo nl2br(stripslashes($content))?>
						</div>
						<input type="hidden" name="hold_tab_ids" id="hold_tab_ids" value="<?php echo $cur_tab_ids?>" />
					</div>
				</div>
			<div class="ajx_det_main_btm"></div>
			</div>
			<input type="hidden" name="ajax_pass_prod_id" id="ajax_pass_prod_id" value="<?php echo $row_prod['product_id']?>" />
	<?php	
		}
		$in_combo = is_product_in_any_valid_combo($row_prod);
		if($in_combo==1 or $row_prod['product_freedelivery']==1 or $row_prod['product_show_pricepromise']==1)
		{
		?>
			<div class="details_btn">
		<?php 
				 if($in_combo==1)
				 {
			?>
				<a href="<?php url_link('showallbundle'.$row_prod['product_id'].'.html')?>" title=""><img src="<? url_site_image('combo-offer.gif')?>"  border="0"/></a>
			<?
				 }
				 if($row_prod['product_freedelivery']==1)
				 {
			?>	
				<a href="<?php url_link('freedelivery'.$_REQUEST['product_id'].'.html')?>" class="productdetailslink" title=""><img  src="<? url_site_image('free-btn.gif')?>" border="0" /></a>
				<?php
				 }
				if($row_prod['product_show_pricepromise']==1)
				{
			?>
				<a href="javascript:handle_price_promise()" class="productdetailslink" title=""><img src="<? url_site_image('price-promise.gif')?>" border="0" /></a>
			<?
				}
			?>
			</div>
		 <?
		 }
	 // ** Check whether any linked products exists for current product
		if($row_prod['product_id']!='')
		{
			$sql_linked = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
									a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
									a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
									a.product_total_preorder_allowed,a.product_applytax,product_shortdesc,
									a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
									a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
									a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
									a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
									a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
									a.product_freedelivery,a.product_bonuspoints               
							FROM 
								products a,product_linkedproducts b 
							WHERE 
								b.link_parent_id=".$row_prod['product_id']." 
								AND a.sites_site_id=$ecom_siteid 
								AND a.product_id = b.link_product_id 
								AND a.product_hide = 'N' 
								AND b.link_hide=0
							ORDER BY 
								b.link_order";
			$ret_linked = $db->query($sql_linked);
			if ($db->num_rows($ret_linked))
			{
				Show_Linked_Product($ret_linked);
			}
		}
	}
	
}
// ** Function to show the details of products which are linked with current product.
		function Show_Linked_Product($ret_prod)
		{
			global $ecom_siteid,$db,$Captions_arr,$Settings_arr,$inlineSiteComponents;
			// Calling the function to get the type of image to shown for current 
			$pass_type = get_default_imagetype('link_prod');
			//$comp_active = isProductCompareEnabled();
			$tot_cnt = $db->num_rows($ret_prod);
			switch($Settings_arr['linked_prodlisting'])
			{
				case '1row':
				case '2row':
					$width_one_set 	= 166;
					$min_number_req	= 4;
					$min_width_req 	= $width_one_set * $min_number_req;
					$total_cnt		= $db->num_rows($ret_prod);
					$calc_width		= $total_cnt * $width_one_set;
					if($calc_width < $min_width_req)
						$div_width = $min_width_req;
					else
						$div_width = $calc_width; 
		?>
				<div class="link_pdt_outr_ajax">
				<div class="link_pdt_top"></div>
				<div class="link_pdt_conts">
				<div class="link_pdt_hdr_outr"><div class="link_pdt_hdr"><span><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_PEOPLE_INTERESTED']);?></span></div></div>
				<div class="det_link_pdt_con">
				<div class="det_link_nav"><a href="#null" onmouseover="scrollDivRight('containerA')" onmouseout="stopMe()"><img src="<?php url_site_image('/arrow-left.gif')?>" border="0"></a></div>
				<div id="containerA" class="det_link_pdt_inner">
				<div id="scroller" style="width:<?php echo $div_width?>px">
				<?php
				$cnts = $db->num_rows($ret_prod);
				while($row_prod = $db->fetch_array($ret_prod))
				{
				?>
					<div class="det_link_pdt">
						<div class="det_link_image">
						<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>">
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
						<div class="det_link_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div>
					</div>
				<?php
				}
				?>
				</div>
				</div>
				<div class="det_link_nav"> <a href="#null" onmouseover="scrollDivLeft('containerA','<?php echo $div_width?>')" onmouseout="stopMe()"><img src="<?php url_site_image('arrow-right.gif')?>"  border="0"/></a></div>
				</div>
				</div>
				<div class="link_pdt_bottom"></div>
			  </div>
		<?php
			break;
			}	
		}
function show_ProductVariables($row_prod,$pos='column')
{
	global $db,$ecom_siteid,$Captions_arr,$Settings_arr;
	$showqty	= $Settings_arr['show_qty_box'];// show the qty box
	$i = 0;
	// ######################################################
	// Check whether any variables exists for current product
	// ######################################################
	$sql_var = "SELECT var_id,var_name,var_value_exists, var_price,var_value_display_dropdown 
						FROM 
							product_variables 
						WHERE 
							products_product_id = ".$row_prod['product_id']." 
							AND var_hide= 0
						ORDER BY 
							var_order";
	$ret_var = $db->query($sql_var);
	$var_cnt = $db->num_rows($ret_var);
	// ##############################################################################
	//  Check whether variable message exists for the product
	// ##############################################################################
	$sql_msg = "SELECT message_id,message_title,message_type 
					FROM 
						product_variable_messages 
					WHERE 
						products_product_id = ".$row_prod['product_id']." 
						AND message_hide= 0
					ORDER BY 
						message_order";
	$ret_msg = $db->query($sql_msg);
	// Check whether total number of variables is 1 or more than 1
	if($var_cnt==1)
	{
		$vardisp_type = $row_prod['product_variable_display_type']; // take the display type from settings for current product
	}
	else 
		$vardisp_type = 'ADD'; // if the variable count is > 1 then by default the Add option will be displayed
	
	if ($db->num_rows($ret_var) or $db->num_rows($ret_msg) or $showqty==1)
	{
	?>
	 <div class="ajx_var_tp"></div>
	<?php
				// Case of variables
				if ($db->num_rows($ret_var))
				{
					while ($row_var = $db->fetch_array($ret_var))
					{
						if ($row_var['var_value_exists']==1)
						{
							// check whether values exists current variable
							$sql_vals = "SELECT var_value_id, var_addprice,var_value,var_colorcode, images_image_id   
											FROM 
												product_variable_data 
											WHERE 
												product_variables_var_id =".$row_var['var_id']." 
											ORDER BY 
												var_order";
							$ret_vals = $db->query($sql_vals);
							if ($db->num_rows($ret_vals))
							{
								$var_Proceed = true;
							}
						}
						else
							$var_Proceed = true;
						if ($var_Proceed)// Show the variable if it is valid to show
						{
							$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
							$var_exists = true;
				?>
						<div class="ajx_var_mid">	
						  <div class="ajax_varname"><?php echo stripslashes($row_var['var_name'])?></div>
						  <div class="ajax_varval">
								<?php
								if ($row_var['var_value_exists']==1)
								{
									if($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
									{
										$onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"price\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\")' ";
										$onclick_var            = "price";
									}
									elseif($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='N')
									{
										$onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"price\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\")' ";
										$onclick_var            = "price";
									}
									elseif($row_prod['product_variablecomboprice_allowed']=='N' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
									{
										$onchange_function      = $onchange_function      = " onchange ='handle_show_prod_det_bulk_disc(\"main_img\",\"".SITE_URL."\",\"".$_REQUEST['product_id']."\",\"".url_site_image('loading.gif',1)."\")' ";
										$onclick_var            = "main_img";
									}
									else
									{
										$onchange_function      = '';
										$onclick_var       		= '';
									}
											$var_disp_type      	= 'DROPDOWN';// Default settings
												if($row_var['var_value_display_dropdown']==1)
													$var_disp_type 		= 'DROPDOWN';
												else
													$var_disp_type 		= 'OTHER';
												$color_type				= false;
												if($var_disp_type == 'OTHER')
												{
													$clr_arr            = array ('color','colour','colors','colours');
													if(in_array(strtolower($row_var['var_name']),$clr_arr))  //if variable name is there in above said array
													{
														$color_type 	= true;  
													}
												}	
										if($var_disp_type=='DROPDOWN')
										  {		
								?>
										<select name="var_<?php echo $row_var['var_id']?>" <?php echo $onchange_function?>>
										<?php 
											while ($row_vals = $db->fetch_array($ret_vals))
											{
										?>
												<option value="<?php echo $row_vals['var_value_id']?>" <?php echo ($_REQUEST['var_'.$row_var['var_id']]==$row_vals['var_value_id'])?'selected':''?>><?php echo stripslashes($row_vals['var_value'])?><?php echo Show_Variable_Additional_Price($row_prod,$row_vals['var_addprice'],$vardisp_type)?></option>
										<?php
											}
										?>
										</select>							
										<?php
										 }
										else
										  {
												$first_val	 	= '';
												$first_one 		= 1;
												$docroot 		= SITE_URL; 
												$prodid			= $_REQUEST['product_id'];
												$loading_gif 	= url_site_image('loading.gif',1);
												while ($row_vals = $db->fetch_array($ret_vals))
												{
													if($first_val=='')
														$first_val = $row_vals['var_value_id'];
													$ret_arr = handle_variable_color_section($row_vals,$first_val,$color_type);	
											
													$show_value			= $ret_arr['show_value'];
													$clr_val 			= $ret_arr['clr_val'];
													$normal_cls 		= $ret_arr['normal_cls'];
													$special_cls 		= $ret_arr['special_cls'];
													
													$normal_cls_sz 		= "size_var_div";
													$special_cls_sz 	= "size_var_div_sel";
													$normal_cls_clrimg 	= "colorimg_div";
													$special_cls_clrimg	= "colorimg_div_sel";
													$normal_cls_clr 	= "color_div";
													$special_cls_clr	= "color_div_sel";	
													$varvaldivid 		= "valdiv_var_".$row_var['var_id']."_".$row_vals['var_value_id'];
													//$onclick_function   = " onclick ='var_onclick(\"".$onclick_var."\",\"var_".$row_var['var_id']."\",".$row_vals['var_value_id'].",\"".$normal_cls."\",\"".$special_cls."\",\"".$docroot."\",\"".$prodid."\",\"".$loading_gif."\")' ";
													$onclick_function   = " onclick ='var_onclick(\"".$onclick_var."\",\"var_".$row_var['var_id']."\",".$row_vals['var_value_id'].",\"".$normal_cls_clr."\",\"".$special_cls_clr."\",\"".$normal_cls_clrimg."\",\"".$special_cls_clrimg."\",\"".$normal_cls_sz."\",\"".$special_cls_sz."\",\"".$docroot."\",\"".$prodid."\",\"".$loading_gif."\")' ";
												?>
												 <div id='<?php echo $varvaldivid?>' class="<?php echo ($first_one==1)?$special_cls:$normal_cls?>" <?php echo $clr_val. ' '.$onclick_function?> title="<?php echo $row_vals['var_value']?>" >
												 <?
												 
												 if($show_value)
													echo stripslashes($row_vals['var_value']);
												?>
												 </div>
												<?php
												   $first_one=2;
												}
									 ?>
											<input type='hidden' name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" value="<?php echo $first_val?>" />
									<?php
										  }
								}
								else
								{
								?>
									<input type="checkbox" name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" value="1" <?php echo ($_REQUEST['var_'.$row_var['var_id']])?'checked="checked"':''?>/><?php echo Show_Variable_Additional_Price($row_prod,$row_var['var_price'],$vardisp_type)?>
								<?php
								}
								?>
							</div>
							</div>
				<?php
							$i++;
						}
						
					}
				}
				// ######################################################
				// End of variables section
				// ######################################################
				
				// ##############################################################################
				//  Case of variable messages
				// ##############################################################################
				
				if ($db->num_rows($ret_msg))
				{
				?>
					<?php
						while ($row_msg = $db->fetch_array($ret_msg))
						{
							$clss = ($i%2==0)?'productvariabletdA':'productvariabletdB';
							$var_exists = true;
					?>
								<div class="ajx_var_mid">
							  <div class="ajax_varname"><?php echo stripslashes($row_msg['message_title'])?></div>
								<div class="ajax_varval">
									<?php
									if ($row_msg['message_type']=='TXTBX')
									{
									?>
										<input type="text" name="varmsg_<?php echo $row_msg['message_id']?>" id="varmsg_<?php echo $row_msg['message_id']?>" value="<?php echo $_REQUEST['varmsg_'.$row_msg['message_id']]?>" />
									<?php
									}
									else
									{
									?>
										<textarea name="varmsg_<?php echo $row_msg['message_id']?>" id="varmsg_<?php echo $row_msg['message_id']?>" rows="3" cols="15"><?php echo $_REQUEST['varmsg_'.$row_msg['message_id']]?></textarea>
									<?php
									}
									?>
								</div>
								</div>
								
					<?php
							$i++;
						}
				}
				// ######################################################
				// End of variable messages
				// ######################################################
			$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslashes($row_prod['product_det_qty_caption']):$Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY'];
			if($showqty==1)// this decision is made in the main shop settings
			{
			?>
					<div class="ajx_var_mid">
					<div class="ajax_varname"><?php echo $cur_qty_caption?></div>
					<div class="ajax_varval">
			<?php	
				if($row_prod['product_variablecomboprice_allowed']=='Y')
				{
					$onchange_function = " onchange ='handle_show_prod_det_bulk_disc(\"price_qty\")' ";
				}
				else
				{
					$onchange_function = '';
				}
				if($row_prod['product_det_qty_type']=='NOR')
				{
		?>
				<div class="ajax_varvalA"><input type="text" class="quainput_det" name="qty"  value="<?php echo ($_REQUEST['qty'])?$_REQUEST['qty']:1?>" <?php echo $onchange_function?> /></div>
		<?php
				}
				elseif($row_prod['product_det_qty_type']=='DROP')
				{
					$dropdown_values = explode(',',stripslashes($row_prod['product_det_qty_drop_values']));
					if (count($dropdown_values) and stripslashes($row_prod['product_det_qty_drop_values'])!='')
					{
		?>
						<div class="ajax_varvalA">
						<select name="qty" <?php echo $onchange_function?>>
						<?php 
							$qty_prefix = stripslashes($row_prod['product_det_qty_drop_prefix']);
							$qty_suffix = stripslashes($row_prod['product_det_qty_drop_suffix']);
							foreach ($dropdown_values as $k=>$v)
							{
								if(is_numeric(trim($v)))
								{
						?>
								<option value="<?php echo trim($v)?>"><?php echo $qty_prefix.' '.trim($v).' '.$qty_suffix?></option>
						<?php
								}	
							}
						?>
						</select>
						</div>
					<?php	
					}				
				}
				?>
				</div>
				</div>
				<?php
			}
		?>
		 <div class="ajx_var_btm">
		</div>		
		<?php
	}
	return $var_exists;
}

function show_buttons($row_prod)
{
	global $Captions_arr,$showqty,$Settings_arr,$ecom_hostname;
	$cust_id 	= get_session_var("ecom_login_customer");
	//$showqty	= $Settings_arr['show_qty_box'];// show the qty box
	//$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslashes($row_prod['product_det_qty_caption']):$Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY'];
	?>
	<div class="ajx_buy">
	<?php
	// Get the caption to be show in the button
	$caption_key = show_addtocart($row_prod,array(0),'',true);
	if ($caption_key!='') // Check whether the buynow or preorder button is to be displayed
	{
?>
		<input name="Submit_buy" type="submit" class="ajx_buy_btn" id="Submit_buy" value="<?php echo $Captions_arr['PROD_DETAILS'][$caption_key];?>" onClick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addcart'" />
<?php
	}
	// Check whether the enquire link is to be displayed
	if ($row_prod['product_show_enquirelink']==1)
	{
?>			
		<input name="Submit_enq" type="submit" class="ajx_buy_btn" id="Submit_enq" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?>" onClick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Enquire'" />
<?php
	}
if ($Settings_arr['proddet_showwishlist'])
{	
	if($cust_id) // ** Show the wishlist button only if logged in 
	{
	?>
		<input name="submit_wishlist" type="submit" class="ajx_buy_btn" id="submit_wishlist" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>"  onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addwishlist'"  />
	<?php
	}	
	else
	{
		$wishlist_onclick = 'window.location=\'http://'.$ecom_hostname.'/wishlistcustlogin.html\'';
	?>
		<input name="submit_wishlist" type="button" class="ajx_buy_btn" id="submit_wishlist" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>" onclick="<?php echo $wishlist_onclick?>" />
	
	<?php
	}	
}	
?>
</div>
<?php	
	return true;
}
function Show_Image_Normal($row_prod)
{
	global $db,$ecomn_siteid,$ecom_hostname,$ecom_themename;	
	  $show_normalimage = false;
	  if ($_REQUEST['prod_curtab'] and $row_prod['product_variablecombocommon_image_allowed']=='N')
	  {
		if ($_REQUEST['prodimgdet'])	
			$showonly = $_REQUEST['prodimgdet'];
		else
			$showonly = 0;
		// Calling the function to get the type of image to shown for current 
		$pass_type = get_default_imagetype('proddet');
		// Calling the function to get the image to be shown
		$tabimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type,0,$showonly,1);
		if(count($tabimg_arr))
		{
			$exclude_tabid = $tabimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
			?>
			<a href="<?php url_root_image($tabimg_arr[0]['image_extralargepath'])?>" title="<?php echo $row_prod['product_name']?>" rel='lightbox[gallery]' >
			<?php
			show_image(url_root_image($tabimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
			?>
			</a>
			<?php
			$show_noimage 	= false;
		}
		else
			$show_normalimage = true;
	  }
	  else
		$show_normalimage = true;
		
		if ($show_normalimage)
		{
			if ($_REQUEST['prodimgdet'])	
				$showonly = $_REQUEST['prodimgdet'];
			else
				$showonly = 0;
			// Calling the function to get the type of image to shown for current 
			$pass_type = get_default_imagetype('proddet');
			
			// Check whether combination image option is set for current product
			if($row_prod['product_variablecombocommon_image_allowed']=='Y')
				$prodimg_arr = get_imagelist_combination($row_prod['default_comb_id'],$pass_type,0,1);
			else
				$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,$showonly,1);
				
			if(count($prodimg_arr))
			{
				$exclude_prodid = $prodimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
				/*<a href="javascript:showImagePopup('<?php echo $prodimg_arr[0]['image_extralargepath']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')"  title="Click to Zoom">*/
				?>
				<a href="<?php url_root_image($prodimg_arr[0]['image_extralargepath'])?>" rel='lightbox[gallery]' title="<?=$row_prod['product_name']?>">
				<?php
				show_image(url_root_image($prodimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','main_det_img');
				?>
				</a>
				<?php
				$imgs_main_arr = explode('big/',$prodimg_arr[0][$pass_type]);
			?>	
				<input type="hidden" name="main_img_hold_var" id="main_img_hold_var" value="<?php echo $imgs_main_arr[1]?>" />
				<?php
				$show_noimage 	= false;
			}
			else
			{	
				// calling the function to get the default no image 
				$no_img = get_noimage('prod','big'); 
				if ($no_img)
				{
					//show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
				}	
			}
		}
		$ret_arr['exclude_tabid']		= $exclude_tabid;
		$ret_arr['exclude_prodid'] 	= $exclude_prodid;
		$ret_arr['img_det']				= $prodimg_arr[0]['image_bigpath'];
		return $ret_arr;
}
function show_more_images($row_prod,$exclude_tabid,$exclude_prodid)
{
		global $db,$ecom_hostname,$ecom_themename;
		$show_normalimage = false;
		$prodimg_arr		= array();
		$pass_type = 'image_iconpath';
		$show_normalimage = true;
		 if ($show_normalimage==true) // the following is to be done only coming for normal image display
		 {
			if($row_prod['product_variablecombocommon_image_allowed']=='Y')
			{
				if ($exclude_prodid)
					$prodimg_arr = get_imagelist_combination($row_prod['default_comb_id'],'image_thumbpath',$exclude_prodid,0);
			}		
			else
			{
				if ($exclude_prodid)
					$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type.',image_thumbpath',$exclude_prodid,0);
			}	
		 } 
			if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
			{
		?>	
		<div class="ajx_det_main_midA">
			  <table width="100%" border="0" cellpadding="0" cellspacing="3" class="productdethumbtable">
				<tr>
				<td>
				<ul class="hoverbox">
				<?php
				if ($pass_type=='image_thumbpath') // If the more image type is Thumb then show 3 in a row otherwise show 2 in a row
				{
					$maximg_col 	=1;
					$width				= '100%';
				}	
				else
				{
					$maximg_col = 3;
					$width			= '45px';
				}	
				$curimg_col = 0;
				$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
				$img_det = '';
				$i=1;
				foreach ($prodimg_arr as $k=>$v)
				{
					$title = ($v['image_title'])?stripslashes($v['image_title']):$row_prod['product_name'];
					if ($img_det!='')
						$img_det .= '~';
						$imgs_arr = explode('icon/',$v['image_iconpath']);
					$img_det .= $imgs_arr[1];
					$cur_moreimg_id = 'moreid_'.$i;
				?>
					<li>
					<a href="javascript:handle_image_swap('<?php echo $i?>')" title="<?=$title?>" id="<?php $imid?>">
					<?php
						 show_image(url_root_image($v['image_iconpath'],1),$title,$title,'preview',$cur_moreimg_id);
	 					$i++;
					?>
					</a>
					</li>
				<?php
				}
				?>	
				</ul>
				<input type="hidden" name="more_img_hold_var" id="more_img_hold_var" value="<?php echo $img_det?>" />
				</td>
				</tr>
			  </table>
			  </div>
	  <?php
		}
	}
	
	function show_BulkDiscounts($prod_id)
	{
		global $db,$ecom_siteid,$Captions_arr;
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		$sql_prod = "SELECT product_id, product_variablecomboprice_allowed,default_comb_id  
							FROM 
								products 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND product_id = $prod_id 
							LIMIT 
								1";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
		}					
		$var_arr = array();	
		$comb_arr['combid'] = 0;
		if($row_prod['product_variablecomboprice_allowed']=='Y')
		{
			if (count($var_arr)==0) // case if variable combination price is allowed and also if var arr is null
			{
				$comb_arr['combid'] = $row_prod['default_comb_id'];
			}
			else
				$comb_arr = get_combination_id($row_prod['product_id'],$var_arr);
		}	
			$bulkdisc_details = product_BulkDiscount_Details($row_prod['product_id'],$comb_arr['combid']);
		if (count($bulkdisc_details['qty']))
		{
		?>	
			<div class="bulk_discount_button_div">
				<input type="button" name="bulkdisc" value="View Bulk Discount" onclick="handle_bulk_disc('bulk_discount_deta_div')" class="ajx_bulk_btn" />
			</div>
			<div class="bulk_discount_detail_div" id="bulk_discount_deta_div" style="display:none; overflow:auto">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bulkdiscounttable">
			  <tr>
				<td align="left" class="bulkdiscountdetailheader"><?php echo $Captions_arr['PROD_DETAILS']['BULK_DISC_HEAD']?></td>
			  </tr>
			  <?php
				for($i=0;$i<count($bulkdisc_details['qty']);$i++)
				{
				?>	
				   <tr>
					<td class="bulkdiscountdetailcontent" align="left"><?php echo $bulkdisc_details['qty'][$i].' '.$Captions_arr['PROD_DETAILS']['BULK_FOR'].' '?> 
						<?php echo print_price($bulkdisc_details['price'][$i]).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH']?>
					</td>
				  </tr>
			  <?php
				}
			  ?>
			</table>
			</div>
		<?php
		}
	}
	function show_Downloads($prod_id)
	{
		global $db,$ecom_siteid,$Captions_arr,$ecom_hostname;
		// Check whether any downloads exists for current product
				$sql_attach = "SELECT * 
								FROM 
									product_attachments 
								WHERE 
									products_product_id = ".$prod_id."
									AND attachment_hide=0 
								LIMIT 
									1";
				$ret_attach = $db->query($sql_attach);
				if ($db->num_rows($ret_attach))
				{
					$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			?>
           <div class="pro_det_dwn" >
	       <div class="pro_det_dwn_tp"></div>
			<div class="pro_det_dwn_hdr">
	   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="productdownloadtable">
                <tr>
                  <td class="productdownloadheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADS'];?></td>
                </tr>
                <tr>
                  <td><ul class="downloadul">
                      <?php
								// Get the list of video attachments
								$sql_video = "SELECT * 
												FROM 
													product_attachments 
												WHERE 
													products_product_id = ".$prod_id."
													AND attachment_hide=0 
												ORDER BY 
													attachment_order";
								$ret_video = $db->query($sql_video);
								if ($db->num_rows($ret_video))
								{
								?>
                      <li class="video">
                        <?php //echo $Captions_arr['PROD_DETAILS']['PRODDET_VIDEO'];?>
                        <ul  class="sub">
                          <?php	
								$cnts = 1;
								while ($row_video = $db->fetch_array($ret_video))
								{
									if($row_video['attachment_icon_img']!='')
										$download_icon = "http://$ecom_hostname/images/$ecom_hostname/attachments/icons/".$row_video['attachment_icon_img'];
									else
										$download_icon = url_site_image('download-icon.gif',1);
								?>
                          				<li><span><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_video['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><img src="<?php echo $download_icon?>" border="0" /></a></span><span><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_video['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php //echo $cnts++. ?><?php echo stripslashes($row_video['attachment_title'])?></a></span></li>
                          <?php		
								}
								?>
                        </ul>
                      </li>
                    <?php
								}
								?>
                  </ul></td>
                </tr>
            </table>
			 </div>
	       <div class="pro_det_dwn_btm"></div>
    	   </div>
          <?php
				}
	}
	function show_Variable_price_detail($product_id,$var_arr=array(),$qty=1)
	{
		global $db,$ecom_siteid,$Captions_arr;
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		$sql_prod = "SELECT product_id,product_name,product_variablestock_allowed,product_show_cartlink,product_show_enquirelink,
											product_preorder_allowed,product_show_enquirelink,product_webstock,product_webprice,
											product_discount,product_discount_enteredasval,product_bulkdiscount_allowed,
											product_total_preorder_allowed,product_applytax,product_shortdesc,product_longdesc,
											product_averagerating,product_deposit,product_deposit_message,product_bonuspoints,
											product_variable_display_type,product_variable_in_newrow,productdetail_moreimages_showimagetype,
											product_default_category_id,product_details_image_type,product_flv_filename,product_stock_notification_required,
											product_alloworder_notinstock,product_variables_exists,product_variablesaddonprice_exists,
											product_variablecomboprice_allowed,default_comb_id,price_normalprefix, price_normalsuffix, price_fromprefix,
											price_fromsuffix, price_specialofferprefix, price_specialoffersuffix, price_discountprefix, price_discountsuffix, 
											price_yousaveprefix,price_yousavesuffix, price_noprice  
							FROM 
								products 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND product_id = $product_id 
							LIMIT 
								1";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
		}						
			$comb_arr['combid'] =0;
			if($row_prod['product_variablecomboprice_allowed']=='Y')
			{
				if (count($var_arr)==0) // case if variable combination price is allowed and also if var arr is null
				{
					$comb_arr['combid'] = $row_prod['default_comb_id'];
				}
				else
				{
					$comb_arr = get_combination_id($row_prod['product_id'],$var_arr);
				}	
			}
			
			$row_prod['combination_id'] 		= $comb_arr['combid']; // this done to handle the case of showing the variables price in show price
			$row_prod['check_comb_price'] 	= 'YES';// this done to handle the case of showing the variables price in show price
			$price_class_arr['ul_class'] 			= 'ajx_picr_ul';
			$price_class_arr['normal_class'] 		= 'ajx_normalprice';
			$price_class_arr['strike_class'] 		= 'ajx_strikeprice';
			$price_class_arr['yousave_class'] 	= 'ajx_yousaveprice';
			$price_class_arr['discount_class'] 	= 'ajx_discountprice';		
			//echo show_Price($row_prod,$price_class_arr,'prod_detail');
			$ret_data =  cartCalc_ajax($row_prod['product_id'],$var_arr,$qty);
			echo prepare_price_Ajax($ret_data,$price_class_arr,1);
		}
	function show_BulkDiscounts_prod_detail($product_id,$var_arr=array())
	{
		global $db,$ecom_siteid,$Captions_arr;
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		$sql_prod = "SELECT product_id, product_variablecomboprice_allowed 
							FROM 
								products 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND product_id = $product_id 
							LIMIT 
								1";
		$ret_prod = $db->query($sql_prod);
		if ($db->num_rows($ret_prod))
		{
			$row_prod = $db->fetch_array($ret_prod);
		}						
			if (count($var_arr)==0 and $row_prod['product_variablecomboprice_allowed']=='Y') // case if variable combination price is allowed and also if var arr is null
			{
				$sql_var = "SELECT var_id,var_name  
								FROM 
									product_variables 
								WHERE 
									products_product_id = ".$row_prod['product_id']." 
									AND var_hide= 0 
									AND var_value_exists = 1 
								ORDER BY 
									var_order";
				$ret_var = $db->query($sql_var);
				if($db->num_rows($ret_var))
				{
					while ($row_var = $db->fetch_array($ret_var))
					{
						$curvar_id= $row_var['var_id'];
						// Get the value id of first value for this variable
						$sql_data = "SELECT var_value_id 
												FROM 
													product_variable_data 
												WHERE 
													product_variables_var_id = ".$curvar_id." 
												ORDER BY var_order  
												LIMIT 
													1";
						$ret_data = $db->query($sql_data);
						if ($db->num_rows($ret_data))
						{
							$row_data = $db->fetch_array($ret_data);
						}							
						$var_arr[$curvar_id] = $row_data['var_value_id'];
					}
				}
			}
			// Section to show the bulk discount details
			$comb_arr = get_combination_id($row_prod['product_id'],$var_arr);
			$bulkdisc_details = product_BulkDiscount_Details($row_prod['product_id'],$comb_arr['combid']);
			if (count($bulkdisc_details['qty']))
			{
			?>	
				<div class="bulk_discount_button_div">
				<input type="button" name="bulkdisc" value="View Bulk Discount" onclick="handle_bulk_disc('bulk_discount_deta_div')" class="ajx_bulk_btn" />
				</div>
				<div class="bulk_discount_detail_div" id="bulk_discount_deta_div" style="display:none; overflow:auto">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bulkdiscounttable">
					<tr>
					<td align="left" class="bulkdiscountdetailheader"><?php echo $Captions_arr['PROD_DETAILS']['BULK_DISC_HEAD']?></td>
					</tr>
					<?php
					for($i=0;$i<count($bulkdisc_details['qty']);$i++)
					{
					?>	
					<tr>
					<td class="bulkdiscountdetailcontent" align="left"><?php echo $bulkdisc_details['qty'][$i].' '.$Captions_arr['PROD_DETAILS']['BULK_FOR'].' '?> 
						<?php echo print_price($bulkdisc_details['price'][$i]).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH']?>
					</td>
					</tr>
					<?php
					}
					?>
					</table>
			</div>
			<?php
			}
	}
	
	function show_Variable_main_image($product_id,$var_arr=array())
	{
		global $db,$ecom_siteid,$Captions_arr;
		$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		$comb_arr = get_combination_id($product_id,$var_arr);
		if ($comb_arr['combid']>0)
		{
			// Calling the function to get the type of image to shown for current 
			$pass_type = get_default_imagetype('proddet');
			$prodimg_arr = get_imagelist_combination($comb_arr['combid'],$pass_type,0,1);
			if(count($prodimg_arr))
			{
				$exclude_prodid = $prodimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
				/*<a href="javascript:showImagePopup('<?php echo $prodimg_arr[0]['image_extralargepath']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')"  title="Click to Zoom">*/
				show_image(url_root_image($prodimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','main_det_img');
				$imgs_main_arr = explode('big/',$prodimg_arr[0][$pass_type]);
				?>	
				<input type="hidden" name="main_img_hold_var" id="main_img_hold_var" value="<?php echo $imgs_main_arr[1]?>" />
				<input type="hidden" name="main_img_hold_id" id="main_img_hold_id" value="<?php echo $exclude_prodid?>" />
			<?php
			}
		}
		$ret_arr['exclude_prodid'] 	= $exclude_prodid;
		return $ret_arr;
	}
	function show_Variable_more_images($product_id,$exclude_prodid,$var_arr=array())
	{
			global $db,$ecom_hostname,$ecom_themename;
			$comb_arr 		= get_combination_id($product_id,$var_arr);
			$prodimg_arr	= array();
			$pass_type 	= 'image_iconpath';
			$show_normalimage = true;
			 if ($show_normalimage==true) // the following is to be done only coming for normal image display
			 {
				if ($exclude_prodid)
					$prodimg_arr = get_imagelist_combination($comb_arr['combid'],$pass_type.',image_thumbpath',$exclude_prodid,0);
			 } 
				if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
				{
			?>	
			<div class="ajx_det_main_midA">
				  <table width="100%" border="0" cellpadding="0" cellspacing="3" class="productdethumbtable">
					<tr>
					<td>
					<ul class="hoverbox">
					<?php
					if ($pass_type=='image_thumbpath') // If the more image type is Thumb then show 3 in a row otherwise show 2 in a row
					{
						$maximg_col 	=1;
						$width				= '100%';
					}	
					else
					{
						$maximg_col = 3;
						$width			= '45px';
					}	
					$curimg_col = 0;
					$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
					$img_det = '';
					$i=1;
					foreach ($prodimg_arr as $k=>$v)
					{
						$title = ($v['image_title'])?stripslashes($v['image_title']):$row_prod['product_name'];
						if ($img_det!='')
							$img_det .= '~';
							$imgs_arr = explode('icon/',$v['image_iconpath']);
						$img_det .= $imgs_arr[1];
						$cur_moreimg_id = 'moreid_'.$i;
					?>
						<li>
						<a href="javascript:handle_image_swap('<?php echo $i?>')" title="<?=$title?>" id="<?php $imid?>">
						<?php
							 show_image(url_root_image($v['image_iconpath'],1),$title,$title,'preview',$cur_moreimg_id);
							$i++;
						?>
						</a>
						</li>
					<?php
					}
					?>	
					</ul>
					<input type="hidden" name="more_img_hold_var" id="more_img_hold_var" value="<?php echo $img_det?>" />
					</td>
					</tr>
				  </table>
				  </div>
		  <?php
			}
		}
?>