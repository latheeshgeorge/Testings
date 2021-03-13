<?php
/* Start ---- Category array to handle the case of terms and conditions checkbox in cart page */
	
	$tc_cart_arr = array(
							//5955 => 'motor', //http://v4demo41.arys.net/brands-c5955.html/local
							//5953 => 'motor', //http://v4demo41.arys.net/home-accessories-c5953.html/local
							
							78052=> 'motor',
							78053=> 'motor',
							77871=> 'motor',
							77855=> 'motor',
							78010=> 'motor',
							77703=> 'motor',
							78121=> 'motor',
							
							//6001 => 'manual', //http://v4demo41.arys.net/accessory-sets-c6001.html/local
							
							77695=> 'manual',
							77767=> 'manual',
							77821=> 'manual',
							77726=> 'manual',
							77700=> 'manual',
							77737=> 'manual'
							
						);
	/*$tc_motor_arr['motor'] = array(
								'3m'=>'http://rescs.premiercare.info/keyfacts/3MonthScooterKeyFactsVer002_001_01-14.pdf',
								'y'=>'http://rescs.premiercare.info/keyfacts/ScooterInsuranceWarrantyKeyFactsVer002_001_01-14.pdf'
								);
	$tc_motor_arr['manual'] = array(
								'3m'=>'http://rescs.premiercare.info/keyfacts/3MonthWheelchairInsuranceKeyFactsVer002_001_01-14.pdf',
								'y'=>'http://rescs.premiercare.info/keyfacts/WheelchairInsuranceKeyFactsVer002_001_01-14.pdf'
								);		*/				
								
	$tc_motor_arr['motor'] = array(
								'3m'=>'http://dealer.premiercare.info/introducer/12771/TOB12771.pdf',
								'y'=>'http://dealer.premiercare.info/introducer/12771/IDD12771.pdf'
								);
	$tc_motor_arr['manual'] = array(
								'3m'=>'http://dealer.premiercare.info/introducer/12771/TOB12771.pdf',
								'y'=>'http://dealer.premiercare.info/introducer/12771/IDD12771.pdf'
								);														 
	$tc_varcheck_arr['3monthfreeinsurance'] 	= '3m';
	$tc_varcheck_arr['3monthsfreeinsurance']	= '3m';
	$tc_varcheck_arr['1yearinsurance'] 			= 'y';
	$tc_varcheck_arr['2yearinsurance'] 			= 'y';
	$tc_varcheck_arr['2yearsinsurance'] 		= 'y';
	$tc_varcheck_arr['3yearinsurance'] 			= 'y';
	$tc_varcheck_arr['3yearsinsurance'] 		= 'y';
	$tc_varcheck_arr['4yearinsurance'] 			= 'y';
	$tc_varcheck_arr['5yearsinsurance'] 		= 'y';
	
	$tc_varcheck_arr['1yearstandardinsurance'] 		= 'y';
	$tc_varcheck_arr['2yearsstandardinsurance'] 	= 'y';
	$tc_varcheck_arr['3yearsstandardinsurance'] 	= 'y';
	$tc_varcheck_arr['1yearplusinsurance'] 			= 'y';
	$tc_varcheck_arr['2yearsplusinsurance'] 		= 'y';
	$tc_varcheck_arr['3yearsplusinsurance'] 		= 'y';
	
	
	$tc_varcheck_arr['year1'] 		= 'y';
	$tc_varcheck_arr['year2'] 		= 'y';
	$tc_varcheck_arr['year3'] 		= 'y';
	$tc_varcheck_arr['year4'] 		= 'y';

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
				<div class="bulk-wrap">
				<div class="bulkPrice">
	<ul>
				<?php
					for($i=0;$i<count($bulkdisc_details['qty']);$i++)
					{
						echo '<li>'.$bulkdisc_details['qty'][$i].' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_FOR']).' ';
						//echo print_price($bulkdisc_details['price'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);
						?><span class="price_red"><?php echo product_BulkDiscount_Build_Price($bulkdisc_details['price'][$i],$bulkdisc_details['price_without'][$i]).' '.stripslash_normal($Captions_arr['PROD_DETAILS']['BULK_EACH']);?></span><?php
						echo '</li>';
					}
				?>
				</ul>
				</div>
				<div class="bulk-banner"><img src="<?php echo url_site_image('banner_price.png')?>" width="154" height="54" /></div>

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
			if($cur_price)
				$HTML_price .= '<div class="pricea_det">'.$cur_price.'</div>';	
			if($sav_price)
				$HTML_price .= '<div class="pricec_det">
				 				'.$sav_price.'
			 					</div>';
			$HTML_price .= '</div>';
			//echo $HTML_price;
				$price_class_arr['ul_class'] 	= 'shelfBul_three_column';
				$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
				$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
				$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
				$price_class_arr['discount_class'] 	= 'productdetdiscountprice';;
				$price_class_arr['emi_class'] 		= 'emi_price_details';
				echo show_Price($row_prod,$price_class_arr,'prod_detail');
			
		}
		function show_Variable_main_image($product_id,$var_arr=array())
		{
			global $db,$ecom_siteid,$Captions_arr;
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			$comb_arr = get_combination_id($product_id,$var_arr);
			$extralarge_img = '';
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
		        //$pass_type = 'image_iconpath';
				//$pass_type = 'image_thumbpath';
				$pass_type = 'image_bigpath';
				$prodimg_arr = get_imagelist_combination($comb_arr['combid'],$pass_type,0,1);
				if(count($prodimg_arr))
				{
					$exclude_prodid = $prodimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
					/*<a href="javascript:showImagePopup('<?php echo $prodimg_arr[0]['image_extralargepath']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>')"  title="Click to Zoom">*/
					
					list($sml_width,$sml_height) = getimagesize(ORG_DOCROOT.'/images/'.$ecom_hostname.'/'.$prodimg_arr[0]['image_bigcategorypath']);
					list($big_width,$big_height) = getimagesize(ORG_DOCROOT.'/images/'.$ecom_hostname.'/'.$prodimg_arr[0]['image_extralargepath']);
					
										
					if($sml_width and $sml_height and $big_width and $big_height)
					{
						if ($big_width<$sml_width or $big_height<$sml_height)
						{
							$extralarge_img = url_root_image($prodimg_arr[0]['image_bigpath'],1);
						}
						else
							$extralarge_img = url_root_image($prodimg_arr[0]['image_extralargepath'],1);
					}
					else
						$extralarge_img = url_root_image($prodimg_arr[0]['image_extralargepath'],1);
					?>
					
 <div class="clearfix">
        <a href="<?php echo $extralarge_img?>" class="jqzoom" rel='gal1' >
            <img src="<?php echo url_root_image($prodimg_arr[0]['image_bigpath'],1)?>"   style="border: 4px solid #666;">

</a>
</div>
					<?php
					//show_image(url_root_image($prodimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','main_det_img');
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
			$pass_type 		= 'image_thumbcategorypath';
			$split_type		= 'icon';
			$show_normalimage = true;
			$extralarge_img = "";
			$exclude_prodid = 0;
			 if ($show_normalimage==true) // the following is to be done only coming for normal image display
			 {
				//if ($exclude_prodid)
					$prodimg_arr = get_imagelist_combination($comb_arr['combid'],$pass_type.',image_thumbpath',$exclude_prodid,0);
			 } 

				if(count($prodimg_arr)>0)// Case if more than one image assigned to current product
				{
			?>	
							<div class="detail_thumb_wrap">
							<div id="content-2" class="content light">
								
							<div class="clearfix" >
							<ul id="thumblist" class="clearfix" >
						<?php
						$curimg_col = 0;
						$img_det = '';
						$i=1;
						$tabstr		= ($_REQUEST['prod_curtab'])?'&amp;prod_curtab='.$_REQUEST['prod_curtab']:'';
						$cnt=0;
						foreach ($prodimg_arr as $k=>$v)
						{ 
							if($cnt==0)
							$cls = 'class="zoomThumbActive"';
							else
							$cls = "";
							$extralarge_img = "";
							$title = ($v['image_title'])?stripslash_normal($v['image_title']):$row_prod['product_name'];
							if ($img_det!='')
								$img_det .= '~';
								$imgs_arr = explode("$split_type/",$v[$pass_type]);
							$img_det .= $imgs_arr[1];
							$cur_moreimg_id = 'moreid_'.$i;
						?>
							
						<?php
						list($sml_width,$sml_height) = getimagesize(ORG_DOCROOT.'/images/'.$ecom_hostname.'/'.$v['image_bigcategorypath']);
							list($big_width,$big_height) = getimagesize(ORG_DOCROOT.'/images/'.$ecom_hostname.'/'.$v['image_extralargepath']);

							//echo '<br>sml '.$sml_width.'x'.$sml_height;
							//echo '<br>bg '.$big_width.'x'.$big_height;

							if($sml_width and $sml_height and $big_width and $big_height)
							{
							if ($big_width<$sml_width or $big_height<$sml_height)
							{
							$extralarge_img = url_root_image($v['image_bigpath'],1);
							}
							else
							$extralarge_img = url_root_image($v['image_extralargepath'],1);
							}
							else
							$extralarge_img = url_root_image($v['image_extralargepath'],1);
							//echo $extralarge_img;

							?>
							<li>
							<?php /*?><a href="#" onclick="link_submit('<?php echo $_REQUEST['prod_curtab']?>','<?php echo $v['image_id']?>','<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1)?>',0)" title="<?php echo $title?>"><?php 
							<a href="javascript:showImagePopup('<?php echo $v['image_extralargepath']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>');"  title="<?php echo $title?>">
							*/?>
							<a <?php echo $cls;?> href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '<?php echo url_root_image($v['image_bigpath']);?>',largeimage: '<?php echo $extralarge_img?>'}">
							<img id="<?php echo $k; ?>" src="<?php echo url_root_image($v['image_thumbcategorypath'],1);?>" />
							</a>
							</li>
							<?php
							$cnt ++;
						}
						?>
						<input type="hidden" name="more_img_hold_var" id="more_img_hold_var" value="<?php echo $img_det?>" />	
						</ul>
						</div>
						
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
		function show_product_reviews($prod_id,$id='')
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
			<table width="100%" cellpadding="0" cellspacing="0" border="0" id="<?php echo $id?>">
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
											<img src="<? url_site_image('star-green.png')?>" />
										<?
										}
										echo
										'</div>
									</div>
								</td>
								<td class="review_table_right" valign="middle">    
								<div>
									'.utf8_encode(stripslashes($row_prodreview['review_details'])).'
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
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="review_table" >
							<tr>
								<td class="review_table_left" valign="middle" align="center">
				** No Reviews added yet **
                </td>
               </tr>
               </table> 
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
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="productdownloadtable">
			<tr><td class="productdownloadheader"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADS'];?></td></tr>
			<tr>
			<td>
			<ul class="downloadul">
			<?php		// Get the list of video attachments
			$sql_video = "SELECT * FROM product_attachments WHERE products_product_id = ".$prod_id." 
			AND attachment_hide=0 AND attachment_type='Video' ORDER BY attachment_order";
			$ret_video = $db->query($sql_video);
			if ($db->num_rows($ret_video))
			{
				?>				
				<li class="video">
				<?php 			//echo $Captions_arr['PROD_DETAILS']['PRODDET_VIDEO'];?>
				<ul class="sub">
				<?php			$cnts = 1;
				while ($row_video = $db->fetch_array($ret_video))
				{
				?>						<li><a class="downloadlink" href="http://<?php echo $ecom_hostname?>/product_download.php?attach_id=<?php echo $row_video['attachment_id']?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_video['attachment_title'])?></a></li>
				<?php			
				}
				?>					</ul>
				</li>
				<?php		
			}
			// Get the list of audio attachments
			$sql_audio = "SELECT * FROM product_attachments WHERE products_product_id = ".$prod_id."
			AND attachment_hide=0 AND attachment_type='Audio' ORDER BY attachment_order";
			$ret_audio = $db->query($sql_audio);
			if ($db->num_rows($ret_audio))
			{
				?>				<li class="audio">
				<?php 			//echo $Captions_arr['PROD_DETAILS']['PRODDET_AUDIO'];?>
				<ul class="sub">
				<?php			$cnts = 1;
				while ($row_audio = $db->fetch_array($ret_audio))
				{
					?>		<li><a href="http://<?php echo $ecom_hostname?>/product_download.php?attach_id=<?php echo $row_audio['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_audio['attachment_title'])?></a></li>
					<?php			
				}
				?>	</ul>
				</li>
				<?php		
			}
			// Get the list of pdf attachments
			$sql_pdf = "SELECT * FROM product_attachments WHERE products_product_id = ".$prod_id."
			AND attachment_hide=0 AND attachment_type='Pdf' ORDER BY attachment_order";
			$ret_pdf = $db->query($sql_pdf);
			if ($db->num_rows($ret_pdf))
			{
				?>				<li class="pdf">
				<?php 			//echo $Captions_arr['PROD_DETAILS']['PRODDET_PDF'];?>
				<ul class="sub">
				<?php			$cnts = 1;
				while ($row_pdf = $db->fetch_array($ret_pdf))
				{
					?>						<li>
					<a href="http://<?php echo $ecom_hostname?>/product_download.php?attach_id=<?php echo $row_pdf['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. </a>
					<a href="http://<?php echo $ecom_hostname?>/product_download.php?attach_id=<?php echo $row_pdf['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo stripslashes($row_pdf['attachment_title'])?></a>
					</li>
					<?php			
				}
				?>					</ul>
				</li>
				<?php		
			}
			// Get the list of other attachments
			$sql_other = "SELECT * FROM product_attachments WHERE products_product_id = ".$prod_id."
			AND attachment_hide=0 AND attachment_type='Other' ORDER BY attachment_order";
			$ret_other = $db->query($sql_other);
			if ($db->num_rows($ret_other))
			{
				?>				<li class="others">
				<?php 			//echo $Captions_arr['PROD_DETAILS']['PRODDET_OTHER'];?>
				<ul class="sub">
				<?php			$cnts = 1;
				while ($row_other = $db->fetch_array($ret_other))
				{
					?>						<li><a href="http://<?php echo $ecom_hostname?>/product_download.php?attach_id=<?php echo $row_other['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_other['attachment_title'])?></a></li>
					<?php			
				}
				?>					</ul>
				</li>
				<?php		
			}
			?>			</ul>
			</td>
			</tr>
			</table>
			<?php
			}
		}
		function show_link_variable($prod_id,$val_id,$var_id)
		{
			global $db,$ecom_siteid;
			if($val_id>0)
			{
				// get the default product category for this product
				$sql_def_cat = "SELECT product_default_category_id FROM products WHERE product_id = $prod_id LIMIT 1";
				$ret_def_cat = $db->query($sql_def_cat);
				if($db->num_rows($ret_def_cat))
				{
					$row_def_cat = $db->fetch_array($ret_def_cat);
					$tc_default_cat_id = $row_def_cat['product_default_category_id'];
				}
				// Get the value text for current variable value 
				$sql_val  = "SELECT var_value FROM product_variable_data WHERE var_value_id = $val_id AND  	product_variables_var_id = $var_id LIMIT 1";
				$ret_val = $db->query($sql_val);
				if ($db->num_rows($ret_val))
				{
					$row_val = $db->fetch_array($ret_val);
					$tc_vurval = tc_remove_spaces ($row_val['var_value']);
					$tc_return = tc_checkbox_check($tc_vurval,$tc_default_cat_id);
					if($tc_return!='')
					{
						echo "&nbsp;<a class='facts_class' href='javascript:show_pdf_new(\"".$tc_return."\")'>Key Facts</a>";	
					}
				}
			}
		}	
		
		function tc_remove_spaces ($str)
	{
		return str_replace(' ','',$str);
	}
	
	function tc_checkbox_check($str,$defid)
	{
		global $tc_cart_arr, $tc_motor_arr,$tc_varcheck_arr;
		$str = strtolower($str);
		$link = '';
		// Check whether the def cat id exists in the tc_cart_arr array
		if(array_key_exists($defid,$tc_cart_arr))
		{
			// Check whether str have any of the value we are expecting
			if(array_key_exists($str,$tc_varcheck_arr))
			{
				$cat_type = $tc_cart_arr[$defid];
				$ins_type = $tc_varcheck_arr[$str];
				
				$link = $tc_motor_arr[$cat_type][$ins_type];
			}
		}
		return trim($link);
	}	
?>

