<?php
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
				<div class="deat_bulk_outr">
				<div class="deat_bulk_top"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_DISC_HEAD'])?></div>
				<div class="deat_bulk_bottom">
				<div class="deat_bulk_conts">
				<?php
					for($i=0;$i<count($bulkdisc_details['qty']);$i++)
					{
						echo '<span>'.$bulkdisc_details['qty'][$i].' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_FOR']).' ';
						//echo print_price($bulkdisc_details['price'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);
						echo product_BulkDiscount_Build_Price($bulkdisc_details['price'][$i],$bulkdisc_details['price_without'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);
						echo '</span>';
					}
				?>
				</div>
				</div>
				</div>
			<?php
			}
	}
	function show_Variable_price_detail($product_id,$var_arr=array())
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
								product_variablecomboprice_allowed,product_det_qty_type,product_det_qty_caption,product_det_qty_drop_values,
								product_det_qty_drop_prefix,product_det_qty_drop_suffix,product_variablecombocommon_image_allowed,default_comb_id,
								price_normalprefix, price_normalsuffix, price_fromprefix, price_fromsuffix,price_specialofferprefix, price_specialoffersuffix, 
								price_discountprefix, price_discountsuffix, price_yousaveprefix, price_yousavesuffix, price_noprice,product_freedelivery,product_show_pricepromise,
								product_hide,product_saleicon_show,product_saleicon_text,product_newicon_show,product_newicon_text  
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
			$row_prod['combination_id'] 			= $comb_arr['combid']; // this done to handle the case of showing the variables price in show price
			$row_prod['check_comb_price'] 			= 'YES';// this done to handle the case of showing the variables price in show price
			$was_price = $cur_price = $sav_price = '';
			$price_arr = show_Price($row_prod,$price_class_arr,'prod_detail',false,5);
			if($price_arr['price_with_captions']['discounted_price'])
			{
				$was_price = $price_arr['price_with_captions']['base_price'];
				$cur_price = $price_arr['price_with_captions']['discounted_price'];
				$sav_price = $price_arr['price_with_captions']['disc_percent'];
			}
			else
			{
				$was_price = '';
				$cur_price = $price_arr['price_with_captions']['base_price'];
				$sav_price = '';
			}
			$HTML_price = '<div class="deat_price">';
			if($was_price)
				$HTML_price .= '<div class="priceb">'.$was_price.'</div>';
			if($cur_price)
				$HTML_price .= '<div class="pricea">'.$cur_price.'</div>';	
			if($sav_price)
				$HTML_price .= '<div class="pricec">
				 				'.$sav_price.'
			 					</div>';
			$HTML_price .= '</div>';
			echo $HTML_price;
			
		}
		function show_Variable_main_image($product_id,$var_arr=array())
		{
			global $db,$ecom_siteid,$Captions_arr;
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			$comb_arr = get_combination_id($product_id,$var_arr);
			if ($comb_arr['combid']>0)
			{
				$sql_prod = "SELECT product_name  
							FROM 
								products 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND product_id = $product_id 
							LIMIT 
								1";
				$ret_prod = $db->query($sql_prod);
				if($db->num_rows($ret_prod))
				{
					$row_prod = $db->fetch_array($ret_prod);
				}
				// Calling the function to get the type of image to shown for current 
		        $pass_type = 'image_iconpath';
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
				else
				{
					$no_img = get_noimage('prod','big'); 
					if ($no_img)
					{
						show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
					}
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
			$pass_type 		= 'image_thumbpath';
			$split_type		= 'icon';
			$show_normalimage = true;
			 if ($show_normalimage==true) // the following is to be done only coming for normal image display
			 {
				if ($exclude_prodid)
					$prodimg_arr = get_imagelist_combination($comb_arr['combid'],$pass_type.',image_thumbpath',$exclude_prodid,0);
			 } 

				if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
				{
			?>	
					<div class="deat_pdt_thumbimg">
					<div class="det_link_thumbimg_con">
					<div class="det_thumbimg_nav"><a href="#null" onmouseover="scrollDivRight('containerB')" onmouseout="stopMe()"><img src="<?php url_site_image('thmbarwl.gif')?>"></a></div>
					<div id="containerB" class="det_thumbimg_inner">
						<div id="scroller_thumb">
						<?php
						$curimg_col = 0;
						$img_det = '';
						$i=1;
						$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
						foreach ($prodimg_arr as $k=>$v)
						{ 
							$title = ($v['image_title'])?stripslash_normal($v['image_title']):$row_prod['product_name'];
							if ($img_det!='')
								$img_det .= '~';
								$imgs_arr = explode("$split_type/",$v[$pass_type]);
							$img_det .= $imgs_arr[1];
							$cur_moreimg_id = 'moreid_'.$i;
						?>
							<div class="det_thumbimg_pdt">
								<div class="det_thumbimg_image">
								<a href="javascript:handle_image_swap('<?php echo $i?>','<?php echo $ecom_hostname?>')" title="<?=$title?>" id="<?php $imid?>">
								<?php
									 show_image(url_root_image($v[$pass_type],1),$title,$title,'preview',$cur_moreimg_id);
									 $i++;
								?>
								</a>
								</div>
							</div>
						<?php
						}
						?>
						<input type="hidden" name="more_img_hold_var" id="more_img_hold_var" value="<?php echo $img_det?>" />	
						</div>
					</div>
					<div class="det_thumbimg_nav"> <a href="#null" onmouseover="scrollDivLeft('containerB',<?php echo (count($prodimg_arr)*150)?>)" onmouseout="stopMe()"><img src="<?php url_site_image('thmbarwr.gif')?>"></a></div>
					</div>
					</div>
		  <?php
				}
		}
		function show_size_chart($prod_id)
		{
			global $db,$ecom_siteid;
			$sql = "SELECT heading_title, product_sizechart_heading.heading_id
					FROM 
						product_sizechart_heading, product_sizechart_heading_product_map 
					WHERE 
						product_sizechart_heading.sites_site_id = '".$ecom_siteid."' 
						AND product_sizechart_heading.heading_id=product_sizechart_heading_product_map.heading_id 
						AND product_sizechart_heading_product_map.products_product_id = '".$prod_id."' 
					ORDER BY 
						product_sizechart_heading_product_map.map_order" ;
		 $res = $db->query($sql);
		 while(list($heading_title, $heading_id) = $db->fetch_array($res))
		 {
			
			$heading[] = $heading_title;
			$charsql = "SELECT size_value 
						 FROM 
							product_sizechart_values 
						 WHERE 
							heading_id='".$heading_id."' 
							AND products_product_id = '".$prod_id."' 
							AND sites_site_id  ='".$ecom_siteid."' 
						 ORDER BY 
							size_sortorder ";
					   
			$charres = $db->query($charsql);
			while(list($size_value) = $db->fetch_array($charres))
			{
				$sizevalue[$heading_id][] = $size_value;
			}
		 }

	   $cnt =   count($sizevalue);
	   $sql_prods = "SELECT product_sizechart_mainheading 
						FROM 
							products 
						WHERE 
							product_id = '".$prod_id."'
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
		$ret_prods = $db->query($sql_prods);
		if ($db->num_rows($ret_prods))
		{
			$row_prods 				= $db->fetch_array($ret_prods);
			$sizechartmain_title 	= stripslash_normal($row_prods['product_sizechart_mainheading']); 
		}
		if($sizechartmain_title == '')
		{
			$sizechartmain_title 	= stripslash_normal($Settings_arr['product_sizechart_default_mainheading']);
		}
			
		if(count($sizevalue))
		{
			foreach($sizevalue as $k=>$v)
			{
				$cnt_hd = count($v);
			}
		}
		?>
		<div class="deat_conts_outr">
		<table width="100%" border="0" cellspacing="1" cellpadding="0" class="productsizecharttableA">
		<tr>
		<?php 
		foreach($heading AS $val)
		{ 
		?>
			<td class="productsizechartheading" ><?PHP echo $val; ?></td>
		<?php
		} 
		?>
		</tr>
		 <?php 
		for($i=0; $i<$cnt_hd; $i++)
		{
			$cls = ($cls=='productsizechartvalueA')?'productsizechartvalueB':'productsizechartvalueA';
		?>
			<tr>
				<?php
				foreach($sizevalue as $k=>$v)
				{
					$disp_val = ($sizevalue[$k][$i])?$sizevalue[$k][$i]:'-'; 
				?>
				  <td class="<?php echo $cls; ?>"><?PHP echo stripslashes($disp_val); ?></td>
				<?php
				} 
				?>
			</tr>
		<? 
		}
		?>  
		</table>
		</div>
		<?php
		}
		function show_product_reviews($prod_id)
		{
			global $db,$ecom_siteid;
			$Captions_arr['PRODUCT_REVIEWS'] = getCaptions('PRODUCT_REVIEWS');
			$sql_prodreview	= "SELECT review_id,DATE_FORMAT(review_date,'%e-%b-%Y @ %r') as reviewed_on,
									review_author,review_rating,review_details 
								FROM  
									 product_reviews 
								WHERE  
									sites_site_id = $ecom_siteid
									AND products_product_id  =  $prod_id
									AND review_status = 'APPROVED'  
									AND review_hide=0 
								ORDER BY  
									review_rating DESC, review_date DESC  
								LIMIT 5";
			$ret_prodreview	= $db->query($sql_prodreview);
			?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
			<td align="right" colspan="2">
			<?php
			if($db->num_rows($ret_prodreview))
			{
				while ($row_prodreview = $db->fetch_array($ret_prodreview))
				{
					$rating 	= $row_prodreview['review_rating'];
					$date_arr 	= array();
					$date_arr 	= explode('@',$row_prodreview['reviewed_on']);
					echo 
					'<div class="review_page_div">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="review_table" >
							<tr>
								<td class="review_table_left" valign="middle">
									<div class="review_user">
											<div><span>'.stripslashes($row_prodreview['review_author']).'</span></div>
										<div>';
										for($i=1;$i<=$rating;$i++){
										?>
											<img src="<? url_site_image('star.gif')?>" />
										<?
										}
										echo
										'</div>
									</div>
								</td>
								<td class="review_table_right" valign="middle">    
								<div>
									'.stripslashes($row_prodreview['review_details']).'
								</div>
								<div class="review_date" >'.$date_arr[0].'</div>
								</td>
							</tr>
						</table>
					</div>';
				}	
			}
			else
			{
			?>
				** No Reviews **
			<?php
			}
			?>
			</td>
			</tr>
			<tr>
				<td align="right" colspan="2">
				<div class="review_namebtn">
					<div class="review_btn"><div><a href="javascript:window.location='<?php url_link('writeproductreview'.$prod_id.'.html')?>'"><?=stripslash_normal($Captions_arr['PRODUCT_REVIEWS']['READ_PRODUCT_ADDREVIEW_LINK'])?></a></div></div>
				</div>
			</tr>
			</table>
			<?php
		}
		function show_ajax_product_downloads($prod_id)
		{
			global $db,$ecom_siteid,$Captions_arr,$ecom_hostname;
			
			$sql_attach = "SELECT * 
								FROM 
									product_attachments 
								WHERE 
									products_product_id = ".$prod_id." 
									AND attachment_hide=0  
								ORDER BY 
									attachment_order";
									
			$ret_attach = $db->query($sql_attach);
			if ($db->num_rows($ret_attach))
			{
		?>
				<div class="deat_conts_outr">
				<div class="deat_conts_conts">
				<ul class="donloads_ul">
				<?php
				$cnts = 1;
				while ($row_attach = $db->fetch_array($ret_attach))
				{
				?>
				
					<li><div class="donloads_no"><?php echo $cnts?></div>
					<div class="donloadsleft"><span><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_attach['attachment_id']?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo stripslashes($row_attach['attachment_title'])?></a></span></div>
					</li>
				<?php
				}
				?>
				</ul>
				</div>
				<div class="deat_conts_bottom"></div>
				</div>
			<?php
			}
		}		
?>
