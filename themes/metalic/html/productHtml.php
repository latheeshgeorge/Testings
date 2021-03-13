<?php
	/*############################################################################
	# Script Name 	: productHtml.php
	# Description 		: Page which holds the display logic for product details
	# Coded by 		: Sny
	# Created on		: 22-Jan-2008
	# Modified by		: Sny
	# Modified On		: 04-Aug-2008
	
	##########################################################################*/
	class product_Html
	{
		// Defining function to show the selected product details
		function Show_ProductDetails($row_prod)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					 $ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			// ** Fetch any captions for product details page
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			
			// ** Fetch the product details
			//$row_prod	= $db->fetch_array($ret_prod);
			// ** Check to see whether current user is logged in 
			$cust_id 	= get_session_var("ecom_login_customer");
			
			// ** Check whether qty box is to be shown
			$showqty	= $Settings_arr['show_qty_box'];// show the qty box
			if($_REQUEST['result']=='exists')
			{
			  //$Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVADDED']
			  $alert = $Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVEXISTS'];
			}
			else if($_REQUEST['result']=='added')
			{
			//$Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVEXISTS']
			 $alert = $Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVADDED'];
			}
			else if($_REQUEST['result']=='removed')
			{
			 $alert = $Captions_arr['PROD_DETAILS']['PRODDET_ADDFAVREMOVED'];
			}
			
			$prod_img_show_type =$row_prod['product_details_image_type'];
		?>

				          <td align="left" valign="top" class="detail_table_left">

		<form method="post" name="frm_proddetails" id="frm_proddetails" action="<?php url_link('manage_products.html')?>" class="frm_cls" onsubmit="return prod_detail_submit(this)">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
		<input type="hidden" name="pricepromise_url" value="<?php url_link('pricepromise'.$row_prod['product_id'].'.html')?>" />
		<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
		<input type="hidden" name="pass_combid" value="<?php echo $_REQUEST['pass_combid']?>" />
		<input type="hidden" name="comb_img_allowed" id="comb_img_allowed" value="<?php echo $row_prod['product_variablecombocommon_image_allowed']?>" />
		<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
		<input type="hidden" name="pagetype" id="pagetype" value="" />
		<script type="text/javascript">
		function ajax_return_productdetailscontents() 
		{
			var ret_val = '';
			var disp 	= 'no';
			if(req.readyState==4)
			{
				if(req.status==200)
				{
					ret_val 		= req.responseText;
					targetobj 	= eval("document.getElementById('"+document.getElementById('ajax_div_holder').value+"')");
					targetobj.innerHTML = ret_val; /* Setting the output to required div */
					if(document.getElementById('ajax_div_holder').value=='price_holder')
					{
						handle_show_prod_det_bulk_disc('bulk');
					}
					else if(document.getElementById('ajax_div_holder').value=='bulkdisc_holder')
					{
						if(document.getElementById('comb_img_allowed').value=='Y' ) /* Do the following only if variable combination image option is set for current product */ 
							handle_show_prod_det_bulk_disc('main_img');
					}
					else if(document.getElementById('ajax_div_holder').value=='mainimage_holder')
					{
						if(document.getElementById('comb_img_allowed').value=='Y') /* Do the following only if variable combination image option is set for current product */ 
							handle_show_prod_det_bulk_disc('more_img');
					}
				}
				else
				{
					 /*alert(req.status);*/
				}
			}
		}
		function handle_image_swap(src_id)
		{
			imglocal_arr = new Array();
			var img_path = '<?php echo "http://$ecom_hostname/images/$ecom_hostname/"?>';
			var destindex = 0;
			if(document.getElementById('main_img_hold_var'))
			{
				var main_img = document.getElementById('main_img_hold_var').value
				if(main_img!='')
				{
					imglocal_arr[0]  = main_img;
				}	
			}
			if(document.getElementById('more_img_hold_var'))
			{	
				var more_img = document.getElementById('more_img_hold_var').value;
				if(more_img!='')
				{
					more_img_arr = more_img.split('~');
					for(i=0;i<more_img_arr.length;i++)
					{
						imglocal_arr[i+1] = more_img_arr[i];
					}
				}
			}
			if (src_id)
			{
				document.getElementById('main_det_img').src = img_path + 'big/'+imglocal_arr[src_id];
				
				srcobj = eval ("document.getElementById('moreid_"+src_id+"')");
				srcobj.src =  img_path + 'icon/'+imglocal_arr[destindex];
				
				var tempval 			= imglocal_arr[destindex];
				imglocal_arr[destindex] 	= imglocal_arr[src_id];
				imglocal_arr[src_id] 		= tempval;
				
				document.getElementById('main_img_hold_var').value = imglocal_arr[destindex];
				var temp_hold = '';
				if (imglocal_arr.length>1)
				{
					for(i=1;i<imglocal_arr.length;i++)
					{
						if(temp_hold!='')
							temp_hold += '~';
						temp_hold += imglocal_arr[i];
					}
				}
				document.getElementById('more_img_hold_var').value = temp_hold;
			}
		}
		function handle_price_promise()
		{
			var url 	= '<?php echo url_link("custlogin.html",1)?>';
			var cust_id = '<?php echo $cust_id?>';
			if(cust_id)
			{
				document.frm_proddetails.fpurpose.value = 'price_promise';
				document.frm_proddetails.submit();
			}
			else
			{
				document.frm_proddetails.action = url;
				document.frm_proddetails.pagetype.value = 'prodhtml';
				document.frm_proddetails.submit();
			}
		}
		function handle_show_prod_det_bulk_disc(opt)
		{
			var varstr 	= '';
			var varidstr	= '';
			for(i=0;i<document.frm_proddetails.elements.length;i++)
			{
				if (document.frm_proddetails.elements[i].name.substr(0,4)=='var_')
				{
					splt_arr = 	document.frm_proddetails.elements[i].name.split('_');				
					if (varstr!='')
						varstr += '~';
					if (varidstr!='')
						varidstr += '~';	
					varstr 	+= document.frm_proddetails.elements[i].value;	
					varidstr 	+= splt_arr[1];	
				}
			}					
			var fpurpose									= '';
			switch (opt)
			{
				case 'price':
					var retdivid										= 'price_holder';
					var qrystr										= '';
					fpurpose										= 'ajax_show_variable_price';	
				break;
				case 'bulk':
					var retdivid									= 'bulkdisc_holder';
					var qrystr										= '';
					fpurpose										= 'ajax_show_bulk_discount';
				break;	
				case 'main_img':
					var retdivid									= 'mainimage_holder';
					obj = document.getElementById('mainimage_holder');
					if (!obj)
						return;
					var qrystr										= '';
					fpurpose										= 'ajax_show_main_image';
				break;	
				case 'more_img':
					var retdivid									= 'moreimage_holder';
					obj = document.getElementById('moreimage_holder');
					if (!obj)
						return;
					var qrystr										= '';
					if (document.getElementById('main_img_hold_id'))
					{
						qrystr = 'exclude_id='+document.getElementById('main_img_hold_id').value;
						
					}
					fpurpose										= 'ajax_show_more_image';
				break;		
			};	
				document.getElementById('ajax_div_holder').value = retdivid;
				retobj 											= eval("document.getElementById('"+retdivid+"')");
				if(opt=='price')
				{
					retobj.innerHTML 							= "<div align='center'><img src ='<?php echo url_site_image('loading.gif',1)?>' border='0'></div>";		
				}
				else
				{
					retobj.innerHTML 							= "";	
				}	
																
			/* Calling the ajax function */
			Handlewith_Ajax('<?php echo $ORG_DOCROOT?>/includes/base_files/products.php','ajax_fpurpose='+fpurpose+'&'+qrystr+'&prod_id='+<?php echo $_REQUEST['product_id']?>+'&pass_var='+varstr+'&pass_varid='+varidstr);
		}
		</script>
 <div class="treemenu" align="left">
    <?php echo generate_tree(-1,$_REQUEST['product_id'])?></div>
            <div class="prdt_header"><?php echo stripslashes($row_prod['product_name'])?></div>
         <?php
		  		
				if($alert)
				{
			?>
				 <div class="red_msg"> - <?php echo $alert?> - </div>
			<?php
				}
				elseif($_REQUEST['stockalert'])
				{
			?>
					<div class="red_msg"> - <?php echo $Captions_arr['PROD_DETAILS'][$_REQUEST['stockalert']]	?> - </div>
			<?php	
				}
			?>
         <div class="pro_det_name" >
			<?php
				if($_REQUEST['for_notification']==1) // make the decision whether the instock message is to be displayed here or in div
				{
					$this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
				}	
				else // case if displaying the instock notification message here itself
					$this->show_Instock_msg($row_prod,$_REQUEST['stockalert']);
			?>
			</div>
		 <?php
		  // Section which decides which all section to be displayed 
					 
			$email_show 		= 0;
			$favourite_show		= 0;
			$writereview_show	= 0;
			$readreview_show	= 0;
			$pdf_show			= 0;
			$compare_show		= 0;
			if(isProductCompareEnabledInProductDetails())
			{
				$def_cat_id = $row_prod['product_default_category_id'];
				$sql_comp = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
									a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
									a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
									a.product_total_preorder_allowed,a.product_applytax,product_shortdesc,
									a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
									a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
									a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
									a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
									a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
									a.product_freedelivery                                
								FROM 
									products a,product_category_map b 
								WHERE 
									b.product_categories_category_id=".$def_cat_id." 
									AND a.product_id <>".$_REQUEST['product_id']." 		
									AND a.sites_site_id=$ecom_siteid 
									AND a.product_id = b.products_product_id  	
								ORDER BY 
									b.product_order 
								LIMIT 
									1";
				$ret_comp_prod = $db->query($sql_comp);
				if($db->num_rows($ret_comp_prod))
				{
					$compare_show = 1; // compare link
				}	
			}	
			if(in_array('mod_emailafriend',$inlineSiteComponents) and $Settings_arr['proddet_showemailfriend']==1)
				$email_show = 1; // email a friend link
			if($Settings_arr['proddet_showwritereview']==1)
				$writereview_show = 1;	// write review link
			if($Settings_arr['proddet_showreadreview']==1)
				$readreview_show = 1; // read review link
			if(in_array('mod_downloadpdf',$inlineSiteComponents) and $Settings_arr['proddet_showpdf']==1)
				$pdf_show				= 1;	 // pdf download link
			if($cust_id)
			{
				if($Settings_arr['proddet_showfavourite']==1)
					$favourite_show = 1;
			}
			?>
           <div class="prdt_content">
           
           <div class="<?php echo ($prod_img_show_type=='JAVA')?'prdt_image_special':'prdt_image'?>"> <?php
					if($prod_img_show_type=='FLASH') // case of showing images in a flash container
					{
						// pass the ids thru query string
						$prod_tab_id = $_REQUEST['product_id'].'~'.$_REQUEST['prod_curtab']; ?>
						<script type="text/javascript">
							document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="480" height="300">');
							<?php /*?>document.write('<param name="movie" value="http://<?php echo $ecom_hostname?>/themes/<?php echo $ecom_themename?>/swf/xml4.swf?filepath=http://<?php echo $ecom_hostname?>/getflashimage_images.php?prod_tab_id=<?=$prod_tab_id?>" />');<?php */?>
							document.write('<param name="movie" value="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/swf/xml4.swf?filepath=http://<?php echo $ecom_hostname?>/getflashimage_images.php?prod_tab_id=<?=$prod_tab_id?>" />');
							document.write('<param name="quality" value="high" />');
							document.write('<param name="wmode" value="opaque">');
							document.write('<embed src="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/swf/xml4.swf?filepath=http://<?php echo $ecom_hostname?>/getflashimage_images.php?prod_tab_id=<?=$prod_tab_id?>" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="480" height="300" wmode="opaque"></embed>');
							document.write('</object>');
						</script>
					<?php
					}
					elseif($prod_img_show_type=='FLASH_ROTATE') // Case of showing flash which rotates the given images
					{
					?>
							<script type="text/javascript">
								document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="480" height="500">');
								<?php /*?>document.write('<param name="movie" value="http://<?php echo $ecom_hostname?>/themes/<?php echo $ecom_themename?>/swf/rotate_flash.swf?filepath=http://<?php echo $ecom_hostname?>/getflashrotate_images.php?p_id=<?php echo $_REQUEST['product_id']?>" />');<?php */?>
								document.write('<param name="movie" value="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/swf/rotate_flash.swf?filepath=http://<?php echo $ecom_hostname?>/getflashrotate_images.php?p_id=<?php echo $_REQUEST['product_id']?>');
								document.write('<param name="quality" value="high" />');
								document.write('<embed src="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/swf/rotate_flash.swf?filepath=http://<?php echo $ecom_hostname?>/getflashrotate_images.php?p_id=<?php echo $_REQUEST['product_id']?>" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="480" height="500"></embed>');
								document.write('</object>');
							</script>
					<?php
					}
					elseif($prod_img_show_type=='JAVA') // case of showing javascript swap
					{
						$this->Show_Javascript_Image_Swapper($row_prod);
					}
					else // case of showing normal image display
					{
						?>
						<div class="pro_det_image" id="mainimage_holder">
					<?php
						$ret_arr = $this->Show_Image_Normal($row_prod);
					?>
						</div>
					<?php
						
						$exclude_tabid			= $ret_arr['exclude_tabid'];
						$exclude_prodid			= $ret_arr['exclude_prodid'];
					}	
					?></div>
					<div id="moreimage_holder">
					<?php
						// Showing additional images
						$this->show_more_images($row_prod,$exclude_tabid,$exclude_prodid);
					?>
					</div>
           
           </div>
           <div class="pro_det_price">
		   <? 
   			echo '<div id="price_holder">';
   			$price_class_arr['ul_class'] 		= 'prodeulprice';
			$price_class_arr['normal_class'] 	= 'productdetnormalprice';
			$price_class_arr['strike_class'] 	= 'productdetstrikeprice';
			$price_class_arr['yousave_class'] 	= 'productdetyousaveprice';
			$price_class_arr['discount_class'] 	= 'productdetdiscountprice';
			
			echo show_Price($row_prod,$price_class_arr,'prod_detail');
			echo '</div>';
			show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
			?>
			</div>
		    <div class="pro_stock_div">
		    <ul>
	<?php
			// Check whether the product review module is active for the site
			$module_name = 'mod_product_reviews';
			if(in_array($module_name,$inlineSiteComponents))
			{
			?>
                       <li><?php
					echo $Captions_arr['PROD_DETAILS']['PRODDET_REVIEW_CAPTION'];
					for ($i=0;$i<$row_prod['product_averagerating'];$i++)
					{
						echo '<img src="'.url_site_image('reviewstar_on.gif',1).'" border="0" alt="revscoreimg" />&nbsp;'; 
					}
					for ($i=$row_prod['product_averagerating'];$i<5;$i++)
					{
						echo '<img src="'.url_site_image('reviewstar_off.gif',1).'" border="0"  alt="revscoreimg"/>&nbsp;'; 
					}
					?></li>
			 <?
			 }
			if ($Settings_arr['product_show_instock'])
			{
			?>
			<li><?php echo get_stockdetails($_REQUEST['product_id'])?></li>
		<? 	}
			if ($Settings_arr['bonus_points_instock'] and $row_prod['product_bonuspoints']>0)
			{
	?>
			<li><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTS'].' '.$row_prod['product_bonuspoints']?></li>
	<?
			}
			if ($row_prod['product_deposit']>0 and $row_prod['product_deposit_message']!='')
			{
			?>
				<li><? echo $Captions_arr['PROD_DETAILS']['PRODDET_DEPOSIT_REQ'] ?> <?php echo ($row_prod['product_deposit']).' '.$Captions_arr['PROD_DETAILS']['DEP_PERCENTAGE']?><br />
				<?php echo nl2br(stripslashes($row_prod['product_deposit_message']))?></li>
			<?
			}
			?>
    </ul>    
    </div>
   <?php	$var_listed = $this->show_ProductVariables($row_prod,'row');?>
     <?php // decide whether to display the button
			if(!$button_displayed)
				$button_displayed=$this->show_buttons($row_prod);
		 				$tabs_arr		= $tabs_cont_arr	= array();
			if($row_prod['product_longdesc'])
				$tabs_content_arr[0]	= stripslashes($row_prod['product_longdesc']);
			elseif ($row_prod['product_shortdesc'])
				$tabs_content_arr[0]	= stripslashes($row_prod['product_shortdesc']);
			if (count($tabs_content_arr))
				$tabs_arr 		= array ('PRODDET_OVERVIEW'=>'Overview');
									
				// Get the list of tabs for current product
				$sql_tab = "SELECT tab_id,tab_title,tab_content 
								FROM 
									product_tabs 
								WHERE 
									products_product_id = ".$_REQUEST['product_id']."
									AND tab_hide=0 
								ORDER BY 
									tab_order"
							;
				$ret_tab = $db->query($sql_tab);
				if($db->num_rows($ret_tab))
				{
					while ($row_tab = $db->fetch_array($ret_tab))
					{
						$tabs_arr[$row_tab['tab_id']]			= stripslashes($row_tab['tab_title']);
						$tabs_content_arr[$row_tab['tab_id']]	= stripslashes($row_tab['tab_content']);
					}
				}
				// Special Buttons	
				// Check whether this product is linked with any of the combo deals which is currently active
				$in_combo = is_product_in_any_valid_combo($row_prod);
				if($in_combo==1 or $row_prod['product_freedelivery']==1 or $row_prod['product_show_pricepromise']==1)
				{
		?>
					<div class="det_pdt_middle_btns">
					<?php
					if($in_combo==1)
					{
					?>
						<a href="<?php url_link('showallbundle'.$row_prod['product_id'].'.html')?>" title=""><img src="<?php url_site_image('combo-offer.gif')?>" border="0"/></a>
					<?php
					}
					if($row_prod['product_freedelivery']==1)
					{
					?>	
						<a href="<?php url_link('freedelivery'.$row_prod['product_id'].'.html')?>" title="Free Delivery"><img src="<?php url_site_image('fre-del-det.gif')?>" border="0" />freedelivery</a>
					<?php
					}
					if($row_prod['product_show_pricepromise']==1)
					{
					?>	
						<a href="javascript:handle_price_promise()" title="Price Promise"><img src="<?php url_site_image('price-promise.gif')?>" border="0"/>pricepromise</a>
					<?php
					}
					?>
					</div>
		<?php
				}
				if (count($tabs_arr))
				{
					?>
					<div class="pro_details_div"><a href="#" name="protabs"></a>
					<div class="pro_det_header" >
					<ul class="pro_details_header">
					 <?php
						
								$curtab 		= ($_REQUEST['prod_curtab'])?$_REQUEST['prod_curtab']:0;
								$prodimgdetid 	= (!$_REQUEST['prodimgdet'])?0:$_REQUEST['prodimgdet'];
								foreach($tabs_arr as $k_tabid=>$v_tabtitle)
								{
									$sel = ($k_tabid == $curtab)?' class="selected_link"':'';
									if ($k_tabid=='PRODDET_OVERVIEW')
									{
										$k_tabid = 0;
										if ($_REQUEST['prodimgdet']=='')
										{
											$pass_url		= url_product($row_prod['product_id'],$row_prod['product_name'],1);
										}
										elseif ($prodimgdetid!=0)
										{
											//$pass_url		= 'http://'.$ecom_hostname.'/p'.$row_prod['product_id'].'-'.$prodimgdetid.'-'.$k_tabid.'/'.strip_url($row_prod['product_name']).'/'.strip_url($v_tabtitle).'.html#protabs';
											$pass_url		= url_product_tabs($row_prod['product_id'],$row_prod['product_name'],$prodimgdetid,$k_tabid,'#protabs',1);
										}
										else
										{
											$pass_url		= url_product($row_prod['product_id'],$row_prod['product_name'],1);
										}
									}
									else
									{
										//$pass_url		= 'http://'.$ecom_hostname.'/p'.$row_prod['product_id'].'-'.$prodimgdetid.'-'.$k_tabid.'/'.strip_url($row_prod['product_name']).'/'.strip_url($v_tabtitle).'.html#protabs';
										 $pass_url		= url_product_tabs($row_prod['product_id'],$row_prod['product_name'],$prodimgdetid,$k_tabid,'#protabs',1);
									}	
									if(!$sel)
									{
							 ?>
                    					<li  class="link_details" onclick = "link_submit('<?php echo $k_tabid?>','<?php echo $_REQUEST['prodimgdet']?>','<?php echo $pass_url?>',0)"><a href="<?php echo $pass_url?>" class="tablink" title="<?php echo $v_tabtitle?>"><?php echo $v_tabtitle?></a></li>
                    <?php
									}
									else
									{
							?>
                    					<li <?php echo $sel?>><?php echo $v_tabtitle?></li>
                    <?php		
									}
								}
							?>
					</ul>
					</div>
					<div class="pro_details_content">
					<?php
						echo $tabs_content_arr[$curtab];
					  ?></div>
					</div>
					<? }?>
					<?php
			// Size chart section
			 $sql = "SELECT heading_title, product_sizechart_heading.heading_id
					    FROM 
					    	product_sizechart_heading, product_sizechart_heading_product_map 
			        	WHERE 
			        		product_sizechart_heading.sites_site_id = '".$ecom_siteid."' 
						    AND product_sizechart_heading.heading_id=product_sizechart_heading_product_map.heading_id 
						  	AND product_sizechart_heading_product_map.products_product_id = '".$_REQUEST['product_id']."' 
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
							   	AND products_product_id = '".$_REQUEST['product_id']."' 
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
									product_id = '".$_REQUEST['product_id']."'
									AND sites_site_id = $ecom_siteid 
								LIMIT 
									1";
				$ret_prods = $db->query($sql_prods);
				if ($db->num_rows($ret_prods))
				{
					$row_prods 	= $db->fetch_array($ret_prods);
					$main_title = stripslashes($row_prods['product_sizechart_mainheading']); 
				}
				if($main_title == '')
				{
					/*$sql_set = "SELECT product_sizechart_default_mainheading 
									FROM 
										general_settings_sites_common 
									WHERE 
										sites_site_id = $ecom_siteid 
									LIMIT 
										1";
					$ret_set = $db->query($sql_set);
					$row_set = $db->fetch_array($ret_set);
					$main_title = stripslashes($row_set['product_sizechart_default_mainheading']); 
					*/
					$main_title = $Settings_arr['product_sizechart_default_mainheading'];
				}
				
			if(count($sizevalue))
			{
				foreach($sizevalue as $k=>$v)
				{
					$cnt_hd = count($v);
				}
				?>
<div class="pro_sizechart_div">
     <table width="100%" border="0" cellpadding="2" cellspacing="0">
                <?PHP if(is_array($heading))
				  { 
				  ?>
                <tr>
                  <td align="left"  class="productchartheader" colspan="<?php echo count($heading)?>" ><?PHP echo $main_title; ?></td>
                </tr>
                <tr>
                  <?PHP 
					  foreach($heading AS $val)
					  { ?>
                  <td align="center"  class="productsizechartheading" ><?PHP echo $val; ?></td>
                  <?PHP
					  } ?>
                </tr>
                <?PHP 
							for($i=0; $i<$cnt_hd; $i++)
							{
								$cls = ($cls=='productsizechartvalueA')?'productsizechartvalueB':'productsizechartvalueA';
						?>
                <tr>
                  <?PHP
						foreach($sizevalue as $k=>$v)
						{
					    ?>
                  <td class="<?PHP echo $cls; ?>" align="center" ><?PHP echo ($sizevalue[$k][$i])?$sizevalue[$k][$i]:'-'; ?></td>
                  <?PHP
						} 
						  ?>
                </tr>
                <? 
						}
				  } 
				  ?>
            </table>

    </div>
<? }?>

		</form>
		<?php	
			// ** Check whether any linked products exists for current product
			$sql_linked = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
								a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
								a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
								a.product_total_preorder_allowed,a.product_applytax,product_shortdesc,a.product_bonuspoints,
								a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
								a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
								a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
								a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
								a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
								a.product_freedelivery                                
							FROM 
								products a,product_linkedproducts b 
							WHERE 
								b.link_parent_id=".$_REQUEST['product_id']." 
								AND a.sites_site_id=$ecom_siteid 
								AND a.product_id = b.link_product_id 
								AND b.link_hide=0
							ORDER BY 
								b.link_order";
			$ret_linked = $db->query($sql_linked);
			if ($db->num_rows($ret_linked))
			{
				$this->Show_Linked_Product($ret_linked);
			}
			
			// Check whether compare products section to be displayed
			 //	if ($db->num_rows($ret_comp_prod) and $_REQUEST['after_comp']==1  and isProductCompareEnabledInProductDetails())
			//		$this->Show_Compare_Product($ret_comp_prod);
			?>
			<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
			<tr>
				<td align="center">
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
					while ($row_inline = $db->fetch_array($ret_inline))
					{
						//$modname 			= $row_inline['feature_modulename'];
						$body_dispcompid	= $row_inline['display_component_id'];
						$body_dispid			= $row_inline['display_id'];
						$body_title				= $row_inline['display_title'];
						include ("includes/base_files/shelf.php");
					}
				}
		
					
				?>
				</td>
			</tr>	
			</table></td>
			 <td align="left" valign="top" class="content_table_middle">
			<table  border="0" cellspacing="0" cellpadding="0" class="commonright1_table">
			<tr>
               	<td class="commonright1table_top">&nbsp;</td>
			</tr>
			<tr><td class="commonright1table_content">
			<?
				if($var_listed)
							$mtd = 'row';
						else
							$mtd = 'col';
						// Show the product label details
						$this->show_ProductLabels($_REQUEST['product_id'],$mtd);
				?>
			<?php
			// Check whether the bar to show the features to be displayed
			echo '<div id="bulkdisc_holder">';
			$this->show_BulkDiscounts($row_prod);
			echo '</div>';
		 if(($email_show==1) or ($favourite_show==1) or ($writereview_show==1) or ($readreview_show==1) or ($pdf_show==1) or ($compare_show==1)) 
		 {
		 ?>
          <div class="pro_links_div">
			   <ul>
			   <?php
				// Check whether the compare feature is enabled in the product details page 	
				if( $compare_show==1)
				{
					$def_cat_id = $row_prod['product_default_category_id'];
				
				?>
				<li><a href="<?php url_productcompare($_REQUEST['product_id'],$row_prod['product_name'])?>" class="productdetailslink" ><? echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_LINK'] ?></a></li>
				
				<? 
				}
				
				if($favourite_show==1) // ** Show the add to favourite only if logged in 
				{
					$sql_prod = "SELECT products_product_id FROM customer_fav_products WHERE sites_site_id=$ecom_siteid AND products_product_id=".$_REQUEST['product_id']." AND customer_customer_id=$cust_id LIMIT 1";
					$ret_num= $db->query($sql_prod);
					if($db->num_rows($ret_num)==0) 
					{ 
					?>
				  <li><a href="#" class="productdetailslink" onclick="if(confirm('<? echo $Captions_arr['PROD_DETAILS']['PRODDET_ADD_CONFIRM'] ?>')){document.frm_proddetails.fpurpose.value='add_favourite';document.frm_proddetails.submit();}"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_FAVOURITE'];?></a></li>
					<? 
					}
					else
					{
					?>
						<li><a href="#" class="productdetailslink" onclick="if(confirm('<? echo $Captions_arr['PROD_DETAILS']['PRODDET_REM_CONFIRM'] ?>')){document.frm_proddetails.fpurpose.value='remove_favourite';document.frm_proddetails.submit();}"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_REMFAVOURITE'];?></a></li>
					<?php
					}
				 }
				
				if($email_show==1) // Check whether the email a friend module is there for current site
				{
				?>
				<li><a href="<?php url_link('emailafriend'.$_REQUEST['product_id'].'.html')?>" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_EMAILFRIEND'];?></a></li>
				<? 
				 }
				
				if(in_array('mod_product_reviews',$inlineSiteComponents)) // Check whether the product review module is there for current site
				{ 
					if($writereview_show==1)
					{
					?>
						<li><a href="<?php url_link('writeproductreview'.$_REQUEST['product_id'].'.html')?>" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_WRITEREVIEW'];?></a></li>
					<?php
					}
					if($readreview_show==1)
					{
					?>
						<li><a href="<?php url_link('readproductreview'.$_REQUEST['product_id'].'.html')?>" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_READREVIEW'];?></a></li>	
					<?
					 }
				 }	
				if($pdf_show==1) // Check whether the download pdf module is there for current site
				{  
				?>
					<li><a href="<?=url_Prod_PDF($_REQUEST['product_id'],$_REQUEST['category_id'],$row_prod['product_name'])?>" class="productdetailslink"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_DOWNLOADPDF'];?></a></li>
				<?php
				}
				?>		
				</ul>
			</div>
			<? 
			 }
			 ?>
			 
			  <?php
				// Check whether any downloads exists for current product
				$sql_attach = "SELECT * 
								FROM 
									product_attachments 
								WHERE 
									products_product_id = ".$_REQUEST['product_id']."
									AND attachment_hide=0 
								LIMIT 
									1";
				$ret_attach = $db->query($sql_attach);
				if ($db->num_rows($ret_attach))
				{
			?>
					<div class="download_class">
               		 <ul class="downloadul">
                      <?php
								// Get the list of video attachments
								$sql_video = "SELECT * 
												FROM 
													product_attachments 
												WHERE 
													products_product_id = ".$_REQUEST['product_id']."
													AND attachment_hide=0 
													AND attachment_type='Video' 
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
								?>
                          <li><a class="downloadlink" href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_video['attachment_id']?>" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_video['attachment_title'])?></a></li>
                          <?php		
								}
								?>
                        </ul>
                      </li>
                    <?php
								}
								// Get the list of audio attachments
								$sql_audio = "SELECT * 
												FROM 
													product_attachments 
												WHERE 
													products_product_id = ".$_REQUEST['product_id']."
													AND attachment_hide=0 
													AND attachment_type='Audio' 
												ORDER BY 
													attachment_order";
								$ret_audio = $db->query($sql_audio);
								if ($db->num_rows($ret_audio))
								{
								?>
                      <li class="audio">
                        <?php //echo $Captions_arr['PROD_DETAILS']['PRODDET_AUDIO'];?>
                        <ul  class="sub">
                          <?php	
									$cnts = 1;
								while ($row_audio = $db->fetch_array($ret_audio))
								{
								?>
                          <li><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_audio['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_audio['attachment_title'])?></a></li>
                          <?php		
								}
								?>
                        </ul>
                      </li>
                    <?php
								}
								// Get the list of pdf attachments
								$sql_pdf = "SELECT * 
												FROM 
													product_attachments 
												WHERE 
													products_product_id = ".$_REQUEST['product_id']."
													AND attachment_hide=0 
													AND attachment_type='Pdf' 
												ORDER BY 
													attachment_order";
								$ret_pdf = $db->query($sql_pdf);
								if ($db->num_rows($ret_pdf))
								{
								?>
                      <li class="pdf">
                        <?php //echo $Captions_arr['PROD_DETAILS']['PRODDET_PDF'];?>
                        <ul  class="sub">
                          <?php	
									$cnts = 1;
								while ($row_pdf = $db->fetch_array($ret_pdf))
								{
								?>
                          <li><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_pdf['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. </a><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_pdf['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo stripslashes($row_pdf['attachment_title'])?></a></li>
                          <?php		
								}
								?>
                        </ul>
                      </li>
                    <?php
								}
								// Get the list of other attachments
								$sql_other = "SELECT * 
												FROM 
													product_attachments 
												WHERE 
													products_product_id = ".$_REQUEST['product_id']."
													AND attachment_hide=0 
													AND attachment_type='Other' 
												ORDER BY 
													attachment_order";
								$ret_other = $db->query($sql_other);
								if ($db->num_rows($ret_other))
								{
								?>
                      <li class="others">
                        <?php //echo $Captions_arr['PROD_DETAILS']['PRODDET_OTHER'];?>
                        <ul  class="sub">
                          <?php	
									$cnts = 1;
								while ($row_other = $db->fetch_array($ret_other))
								{
								?>
                          <li><a href="http://<?php echo $ecom_hostname?>/download.php?attach_id=<?php echo $row_other['attachment_id']?>" class="downloadlink" title="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_CLICKDOWNLOAD'];?>"><?php echo $cnts++?>. <?php echo stripslashes($row_other['attachment_title'])?></a></li>
                          <?php		
								}
								?>
                        </ul>
                      </li>
                    <?php
								}
								?>
                  </ul>
				 </div> 
          <?php
				}
			?>
			</td></tr>
			<tr>
                <td class="commonright1_table_bottom">&nbsp;</td>
              </tr></table></td>
			
	<?php	
		}
		// ** Function to show the details of products which are linked with current product.
		function Show_Linked_Product($ret_prod)
		{
			global $ecom_siteid,$db,$Captions_arr,$Settings_arr;
			// Calling the function to get the type of image to shown for current 
			$pass_type = get_default_imagetype('link_prod');
			$comp_active = isProductCompareEnabled();

			switch($Settings_arr['linked_prodlisting'])
			{
			case '1row':
		?>
				<div class="shelf_1row">
				<div class="pro_de_shelfBheader" align="left"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_PEOPLE_INTERESTED'];?></div>
			<?php
				while($row_prod = $db->fetch_array($ret_prod))
				{
			?>
					<div class="shelf_main">
					<div class="shelf_1row_img"> 
					<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
					<?php
						// Calling the function to get the type of image to shown for current 
						//$pass_type = get_default_imagetype('midshelf');
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
					?></a>
					<?php
						if($comp_active)  
						{
							dislplayCompareButton($row_prod['product_id']);
						}
					?>
					
					</div>	
					<div class="shelf_1row_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
					<div class="shelf_1row_content"><?php echo stripslashes($row_prod['product_shortdesc'])?> </div>
					<div class="shelf_1row_price">
					 <?php
						$price_class_arr['ul_class'] 		= 'shelf_price_ul';
						$price_class_arr['normal_class'] 	= 'shelf_normal';
						$price_class_arr['strike_class'] 	= 'shelf_strike';
						$price_class_arr['yousave_class'] 	= 'shelf_normal';
						$price_class_arr['discount_class'] 	= 'shelf_normal';
						echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
						show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
						$frm_name = uniqid('linked_');
					?>	
					<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
					<input type="hidden" name="fpurpose" value="" />
					<input type="hidden" name="fproduct_id" value="" />
					<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
					<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />							
					  <ul class="shelf_button">
						<li class="shelf_button_li">
						<div class="more_div">
						 <?php show_moreinfo($row_prod,'button_yellow')?>
						</div>
						 <?php
							$class_arr 					= array();
							$class_arr['ADD_TO_CART']	= 'button_yellow';
							$class_arr['PREORDER']		= 'button_yellow';
							$class_arr['ENQUIRE']		= 'button_yellow';
							$class_div                  = 'button_div';
						    show_addtocart($row_prod,$class_arr,$frm_name,'','','',$class_div);
						  ?>
						</li>
					  </ul>
					</form>
					<?php
						show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
		             ?>
					</div>
				</div>
			<?php
				}
			?>	
</div>
		<?php
		break;
     	 }	
		}
		// ** Function to show the list of products to be compared with current product.
		function Show_Compare_Product($ret_prod)
		{
				global $ecom_siteid,$db,$Captions_arr,$Settings_arr;
				$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
		?>
			<form method="post" action="" name="frm_proddet_comp" id="frm_proddet_comp" target="_blank">
			<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
			<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
			<input type="hidden" name="fpurpose" value="proddet_compare" />
			<input type="hidden" name="detcomp_prods" id="detcomp_prods" value=""/>
			</form>
			<script type="text/javascript">
				function handle_proddet_compare()
				{
					var def_prodid 		= '<?php echo $_REQUEST['product_id']?>';
					var comp_chkbox 	= document.getElementsByTagName('input');
					var checked_comp	= '';
					var totcnt				= 0;
					var maxcnt		= '<?php echo $Settings_arr['no_of_products_to_compare']?>';
					for(i=0;i<comp_chkbox.length;i++)
					{
						if(comp_chkbox[i].name.substr(0,15)=='chkproddet_comp')
						{
							if(comp_chkbox[i].checked)
							{
								if(checked_comp!='')
									checked_comp = checked_comp + ',';
								checked_comp = checked_comp + comp_chkbox[i].value;
								totcnt++;
							}	
						}
					}
					if (checked_comp=='')
						alert('<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPARING'];?>'); 
					else if(totcnt>maxcnt) 
						alert('<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_SORRY_COMPARING'];?>'+(maxcnt)+' <?php echo $Captions_arr['PROD_DETAILS']['PRODDET_SORRY_COMPARING_TIME'];?>'); 
					else
					{
						checked_comp = def_prodid+','+checked_comp;
						document.frm_proddet_comp.detcomp_prods.value = checked_comp;
						document.frm_proddet_comp.submit();
					}
				}
			</script>
		<td align="left" valign="top" class="cart_table_left">

				<div class="shelf_compare">
				<div class="pro_de_shelfBheader" align="left"><?php echo $Captions_arr['PROD_DETAILS']['PRODDET_COMPARE_HEAD'];?></div>
				<div class="productdetd_main" align="left"><?php  $msg = $Captions_arr['PROD_DETAILS']['PRODDET_MAX_COMP_PROD']; $msg = str_replace('[prodno]',$Settings_arr['no_of_products_to_compare'],$msg); echo $msg?></div>
			<div class="com_backbtn" align="right"><ul>
			<li ><input type="button" name="prodet_backprod" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_BACK_TO_DETAIL'];?>" class="buttonred_cart" onclick="window.location='<?php url_product($_REQUEST['product_id'],'',-1)?>'"/>
            </li>
			<li ><input type="button" name="prodet_comparebutton" value="<?php echo $Captions_arr['PROD_DETAILS']['COMPARE_PRODUCTS'];?>" class="buttonred_cart" onclick="handle_proddet_compare()"/></li>
			</ul></div>
			<?php
				// Calling the function to get the type of image to shown for current 
				$pass_type = get_default_imagetype('link_prod');
				while($row_prod = $db->fetch_array($ret_prod))
				{
					$compare_checked = '';
					if(is_array($_SESSION['compare_products']))
					{
						if(in_array($row_prod['product_id'],$_SESSION['compare_products']))
						{
							$compare_checked = 'checked="checkeed"';
						}
					}	
?>
						<div class="shelf_main">
						<h1 class="shelfBprodname"><input type="checkbox" name="chkproddet_comp_<?php echo $row_prod['product_id']?>" id="chkproddet_comp_<?php echo $row_prod['product_id']?>" value="<?php echo $row_prod['product_id']?>" <?php echo $compare_checked?>/><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
						<div class="shelf_1row_img"> 
						<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
						<?php
							// Calling the function to get the type of image to shown for current 
							//$pass_type = get_default_imagetype('midshelf');
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
						?></a>
						<?php
						if($comp_active)  
						{
						 dislplayCompareButton($row_prod['product_id']);
						}?>
						</div>	
						<div class="shelf_1row_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
						<div class="shelf_1row_content"><?php echo stripslashes($row_prod['product_shortdesc'])?> </div>
						<div class="shelf_1row_price">
						<?php
						$price_class_arr['ul_class'] 		= 'shelf_price_ul';
						$price_class_arr['normal_class'] 	= 'shelf_normal';
						$price_class_arr['strike_class'] 	= 'shelf_strike';
						$price_class_arr['yousave_class'] 	= 'shelf_normal';
						$price_class_arr['discount_class'] 	= 'shelf_normal';
						echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
						show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
						show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
						$frm_name = uniqid('linked_');
						?>	
						<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
						<input type="hidden" name="fpurpose" value="" />
						<input type="hidden" name="fproduct_id" value="" />
						<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
						<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />							
						  <ul class="shelf_button">
							<li class="shelf_button_li">
							<div class="more_div">
							 <?php show_moreinfo($row_prod,'button_yellow')?>
							</div>
							 <?php
								$class_arr 					= array();
								$class_arr['ADD_TO_CART']	= 'button_yellow';
								$class_arr['PREORDER']		= 'button_yellow';
								$class_arr['ENQUIRE']		= 'button_yellow';
								$class_div                  = 'button_div';
							    show_addtocart($row_prod,$class_arr,$frm_name,'','','',$class_div)
							  ?>
							</li>
						  </ul>
						</form>
						</div>
						</div>
			<?php
				}
			?>	
		<div class="com_backbtn" align="right"><input type="button" name="prodet_comparebutton" value="<?php echo $Captions_arr['COMMON']['COMPARE_PRODUCTS'];?>" class="buttonred_cart" onclick="handle_proddet_compare()"/></div>
		</div>
		</td>
<?php
	}
		
		// ** Function to display the variables. This is written as function to avoid code repetation in various if conditions
		function show_ProductVariables($row_prod,$pos='column')
		{
			global $db,$ecom_siteid,$Captions_arr;
			$i = 0;
			// ######################################################
			// Check whether any variables exists for current product
			// ######################################################
			$sql_var = "SELECT var_id,var_name,var_value_exists, var_price 
								FROM 
									product_variables 
								WHERE 
									products_product_id = ".$_REQUEST['product_id']." 
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
								products_product_id = ".$_REQUEST['product_id']." 
								AND message_hide= 0
							ORDER BY 
								message_order";
			$ret_msg = $db->query($sql_msg);
			
			if ($pos!='column') // Check the case of labels only if value of pos is not 'column'
			{
				// Check whether any labels to be displayed for current product
				$sql_labels = "SELECT a.label_id,a.label_name,b.label_value,a.is_textbox,product_site_labels_values_label_value_id  
								FROM
									product_site_labels a,product_labels b 
								WHERE 
									b.products_product_id = ".$_REQUEST['product_id']." 
									AND a.label_hide = 0 
									AND a.label_id = b.product_site_labels_label_id";
				$ret_labels = $db->query($sql_labels);
				$label_exists = false;
				while ($row_labels = $db->fetch_array($ret_labels) and $label_exists==false) // added the AND condition to avoid the case of exiting the loop if atleast one label have value
				{
					$vals = '';
					if ($row_labels['is_textbox']==1)
						$vals = stripslashes($row_labels['label_value']);
					else
					{
						$sql_labelval = "SELECT label_value 
											FROM 
												product_site_labels_values  
											WHERE 
												product_site_labels_label_id=".$row_labels['label_id']." 
												AND label_value_id = ".$row_labels['product_site_labels_values_label_value_id'];
						$ret_labelval = $db->query($sql_labelval);
						if ($db->num_rows($ret_labelval))
						{
							$row_labelval = $db->fetch_array($ret_labelval);
							$vals = stripslashes($row_labelval['label_value']);
						}
												
					}
					if (trim($vals))
					{
						$label_exists = true;	
					}
				}	
			}
			// Check whether total number of variables is 1 or more than 1
			if($var_cnt==1)
			{
				$vardisp_type = $row_prod['product_variable_display_type']; // take the display type from settings for current product
			}
			else 
				$vardisp_type = 'ADD'; // if the variable count is > 1 then by default the Add option will be displayed
			
			if ($db->num_rows($ret_var) or $db->num_rows($ret_msg))
			{
		  ?>
   <div class="pro_variable_div">
						<?php
						// Case of variables
						if ($db->num_rows($ret_var) or $db->num_rows($ret_msg))
						{
							while ($row_var = $db->fetch_array($ret_var))
							{
								if ($row_var['var_value_exists']==1)
								{
									// check whether values exists current variable
									$sql_vals = "SELECT var_value_id, var_addprice,var_value 
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
						         <ul>

								  <li><?php echo stripslashes($row_var['var_name'])?></li>
									<li>
										<?php
										if ($row_var['var_value_exists']==1)
										{
											if($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
											{
												$onchange_function = " onchange ='handle_show_prod_det_bulk_disc(\"price\")' ";
											}
											elseif($row_prod['product_variablecomboprice_allowed']=='Y' and $row_prod['product_variablecombocommon_image_allowed']=='N')
											{
												$onchange_function = " onchange ='handle_show_prod_det_bulk_disc(\"price\")' ";
											}
											elseif($row_prod['product_variablecomboprice_allowed']=='N' and $row_prod['product_variablecombocommon_image_allowed']=='Y')
											{
												$onchange_function = " onchange ='handle_show_prod_det_bulk_disc(\"main_img\")' ";
											}
											else
											{
												$onchange_function = '';
											}
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
										?>
											<input type="checkbox" name="var_<?php echo $row_var['var_id']?>" id="var_<?php echo $row_var['var_id']?>" value="1" <?php echo ($_REQUEST['var_'.$row_var['var_id']])?'checked="checked"':''?>/><?php echo Show_Variable_Additional_Price($row_prod,$row_var['var_price'],$vardisp_type)?>
										<?php
										}
										?>
									</li>
									</ul>
						<?php
									$i++;
								}
								
							}
						?>  
						<?php
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
									 <ul>
										<li><?php echo stripslashes($row_msg['message_title'])?></li>
										<li>
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
										</li>
									</ul>
							<?php
									$i++;
								}
							?>  
						<?php		
						}
						// ######################################################
						// End of variable messages
						// ######################################################
						?>
      </div>			<?php
			}
			return $var_exists;
		}
		/* Function to show the bulk discount*/
		function show_BulkDiscounts($row_prod,$var_arr=array())
		{
			global $db,$ecom_siteid,$Captions_arr;
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
				
				<div class="pro_bulk_div"><ul>
				<li><?php echo $Captions_arr['PROD_DETAILS']['BULK_DISC_HEAD']?></li>
				  <?php
					for($i=0;$i<count($bulkdisc_details['qty']);$i++)
					{
					?>	
					   
                        <li><?php echo $bulkdisc_details['qty'][$i].' '.$Captions_arr['PROD_DETAILS']['BULK_FOR'].' '?> 
							<?php echo print_price($bulkdisc_details['price'][$i]).' '.$Captions_arr['PROD_DETAILS']['BULK_EACH']?>
							</li>
				  <?php
					}
				  ?></ul>
				</div>
			<?php
			}
		}
		/* Function to show the instock notification */
		/* Function to show the bulk discount*/
		function show_Instock_msg($row_prod, $stockalert='')
		{  
			global $db,$ecom_siteid,$Captions_arr,$ecom_hostname,$Captions_arr;
			$show_notify = false;
			if ($row_prod['product_stock_notification_required']=='Y' and $row_prod['product_alloworder_notinstock']=='N')
			{
				// Check whether variable stock is managed
				if ($row_prod['product_variablestock_allowed']=='N' and $row_prod['product_webstock']==0)
				{
					if ($row_prod['product_preorder_allowed']=='Y' and $row_prod['product_total_preorder_allowed']>0)
					{
						$show_notify = false;
					}
					else
					{
						$show_notify = true;
					}	
				}
				elseif($row_prod['product_variablestock_allowed']=='Y')
				{
					if($_REQUEST['for_notification']==1)
						$show_notify = true;
					else
					{
						if ($row_prod['product_preorder_allowed']=='Y' and $row_prod['product_total_preorder_allowed']>0)
						{
							$show_notify = false;
						}
						else
						{
							
						}		
					}
				}
				if($show_notify) // Check whether notificataion link is to be shown
				{
					if($_REQUEST['for_notification']==1)// case if coming back to the product details page after validation
					{
					?>
							<script type="text/javascript">
								if(document.getElementById('alert_main_div'))
									document.getElementById('alert_main_div').style.display='';
							</script>
						<div  class="div_alert" id="instockmsg_div">
						<div align="right" class="instockmsg_span" ><a href="javascript: hide_instockmsg_div()"><img src="<?php url_site_image('close.gif')?>" border="0" /></a></div>
							<?php echo $Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK']?>.
						 <br />
						<span class="instockmsg_out_stock">
						<?php echo $Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK_EMAIL']?></span>
						<input type="text"   name="stock_email" />
						<input type="hidden" name="prod_mod" value="stock_notify" />
						<input type="hidden" name="hid_notify" value="stock" /> 
						<input type="button" name="stocknotif_submit" value=" Send Request " class="buttongray" onclick=" validate_stocknotify(document.frm_proddetails)"  />
						<input type="hidden" name="product_id" value="<?PHP echo $_REQUEST['product_id']; ?>" />
						</div>
					<?php	
						}
						else
						{
					?>	
						<div  class="alert_inner"><?php echo $Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK']?>.
						<br />
						<span style="font-size:12px;font-weight:normal;color:#000000;"><?php echo $Captions_arr['PROD_DETAILS']['PRODUCT_OUT_STOCK_EMAIL']?><br />

							<input type="text"   name="stock_email" />
							<input type="hidden" name="prod_mod" value="stock_notify" />
						 	<input type="hidden" name="hid_notify" value="stock" /> 
						  	<input type="button" name="stocknotif_submit" value=" Send Request " class="buttongray" onclick=" validate_stocknotify(document.frm_proddetails)"  />
				         	<input type="hidden" name="product_id" value="<?PHP echo $_REQUEST['product_id']; ?>" />
						</span>
						</div>
			<?php
					}		
				}
			}
		}
		
		/* Function to show the lables set for the product */
		function show_ProductLabels($prod_id,$pos='row')
		{
			global $db,$ecom_siteid,$Captions_arr;
			$display_ok = false;
			$ret_val = '';
			// Check whether labels exists for current product
			$cats_arr = $grp_arr = array();
			// Get the categories that area linked with current product
			$sql_cats = "SELECT product_categories_category_id 
							FROM 
								product_category_map 
							WHERE 
								products_product_id = $prod_id";
			$ret_cats = $db->query($sql_cats);
			if($db->num_rows($ret_cats))
			{
				while ($row_cats = $db->fetch_array($ret_cats))
				{
					$cats_arr[] = $row_cats['product_categories_category_id'];
				}
				$sql_grps = "SELECT DISTINCT product_labels_group_group_id  
								FROM 
									product_category_product_labels_group_map a, product_labels_group b 
								WHERE 
									a.product_labels_group_group_id = b.group_id 
									AND b.group_hide = 0 
									AND product_categories_category_id IN (".implode(',',$cats_arr).") ";
				$ret_grps = $db->query($sql_grps);
				if($db->num_rows($ret_grps))
				{
					while ($row_grps = $db->fetch_array($ret_grps))
					{
						$grp_arr[] = $row_grps['product_labels_group_group_id'];
					}	
					// Check whether there exists atleast one label to display
					$sql_lblcheck = "SELECT a.map_id 
										FROM 
											product_labels_group_label_map a , product_labels_group b
										WHERE 
											product_labels_group_group_id IN (".implode(',',$grp_arr).") 
											AND a.product_labels_group_group_id=b.group_id 
											AND b.group_hide = 0 
										LIMIT 
											2";
					$ret_lblcheck 	= $db->query($sql_lblcheck);
					$grp_nos		= $db->num_rows($ret_lblcheck);
					if($grp_nos)
					{
						// Get the product label group details in order
						$sql_grp = "SELECT group_id,group_name,group_name_hide  
										FROM 
											product_labels_group 
										WHERE 
											group_id IN (".implode(',',$grp_arr).") 
										ORDER BY 
											group_order";
						$ret_grp = $db->query($sql_grp);
						if($db->num_rows($ret_grp))
						{
							$ret_val = '<div class="pro_overview_div">
										<ul>
										<li class="overview_header">'.$Captions_arr['PROD_DETAILS']['PRODDET_KEY_FEATURES'].'</li>';
						
							$i=1;
							$grp_cnt = 0;
							$label_arr = array();
							while ($row_grp = $db->fetch_array($ret_grp))
							{
								// Check whether there exists atleast one label under this group to display
								$sql_labels = "SELECT a.label_id,a.label_name,a.in_search,a.is_textbox,c.product_site_labels_values_label_value_id,c.label_value 
													FROM 
														product_site_labels a,product_labels_group_label_map b,product_labels c
													WHERE 
														b.product_labels_group_group_id = ".$row_grp['group_id']." 
														AND c.products_product_id = $prod_id
														AND a.label_id = b.product_site_labels_label_id 
														AND a.label_id = c.product_site_labels_label_id 
														AND a.label_hide = 0 
														AND (c.product_site_labels_values_label_value_id>0 OR  label_value <> '')
													ORDER BY 
														b.map_order";
								$ret_labels = $db->query($sql_labels);
								if($db->num_rows($ret_labels))
								{
									$grp_cnt++;
									$label_arr[$row_grp['group_name'].'~'.$row_grp['group_name_hide']] = array();
									while ($row_labels = $db->fetch_array($ret_labels))
									{
										$vals = '';
										if ($row_labels['is_textbox']==1)
											$vals = stripslash_normal($row_labels['label_value']);
										else
										{
											$sql_labelval = "SELECT label_value 
																FROM 
																	product_site_labels_values  
																WHERE 
																	product_site_labels_label_id=".$row_labels['label_id']." 
																	AND label_value_id = ".$row_labels['product_site_labels_values_label_value_id'];
											$ret_labelval = $db->query($sql_labelval);
											if ($db->num_rows($ret_labelval))
											{
												$row_labelval = $db->fetch_array($ret_labelval);
												$vals = stripslash_normal($row_labelval['label_value']);
											}
																	
										}
										if ($vals)
										{
											$label_arr[$row_grp['group_name'].'~'.$row_grp['group_name_hide']][] = array('name'=>stripslash_normal($row_labels['label_name']),'val'=>$vals);
										}
									}	
								}
							}
							if(count($label_arr))
							{
								$display_ok = true;
								$prev_grp = '';
								foreach ($label_arr as $k=>$v)
								{
									if($prev_grp!=$k)
									{
										$gname_arr = explode('~',$k);
										// Show the name only if it is not made hidden
										if($gname_arr[1]==0)
										{
											$ret_val .='<li class="keyfeatureHeading" align="left" colspan="2">'.$gname_arr[0].'</li>';
										}			
									}	
									if(is_array($v))
									{
										if(count($v))
										{
											for($i=0;$i<count($v);$i++)
											{
												$ret_val .= '<li><span>'.stripslashes($v[$i]['name']).'</span>: <span>'.$v[$i]['val'].'</span></li>';	
											}
										}
									}
								}
							}	
							$ret_val .= '</ul>
										</div>';	
						}
					}
				}	
			}
			if($display_ok==false)
				$ret_val = '';
			echo $ret_val ;	
		}
		function show_buttons($row_prod)
		{
			global $Captions_arr,$showqty,$Settings_arr;
			$cust_id 	= get_session_var("ecom_login_customer");
			$showqty	= $Settings_arr['show_qty_box'];// show the qty box
			$cur_qty_caption = ($row_prod['product_det_qty_caption']!='')?stripslashes($row_prod['product_det_qty_caption']):$Captions_arr['PROD_DETAILS']['PRODDET_QUANTITY'];
		?>
	  <div class="pro_buy_div">
       <ul>
		<?php
		// Get the caption to be show in the button
			$caption_key = show_addtocart($row_prod,array(0),'',true);
			if ($caption_key!='') // Check whether the buynow or preorder button is to be displayed
			{
		  if($showqty==1)// this decision is made in the main shop settings
			{
				if($row_prod['product_det_qty_type']=='NOR')
				{
	?>
			 <li><div class="quantity_details"> <?php echo $cur_qty_caption?><input type="text" class="quainput" name="qty"  value="<?php echo ($_REQUEST['qty'])?$_REQUEST['qty']:1?>" maxlength="2" /></div></li>
	<?php
				}
				elseif($row_prod['product_det_qty_type']=='DROP')
				{
					$dropdown_values = explode(',',stripslashes($row_prod['product_det_qty_drop_values']));
					if (count($dropdown_values) and stripslashes($row_prod['product_det_qty_drop_values'])!='')
					{
					?>
						<li><div class="quantity_details"><?php echo $cur_qty_caption ?>
						<select name="qty">
						<?php 
							$qty_prefix = stripslashes($row_prod['product_det_qty_drop_prefix']);
							$qty_suffix = stripslashes($row_prod['product_det_qty_drop_suffix']);
							foreach ($dropdown_values as $k=>$v)
							{
								$show_val = trim($v);
								if (is_numeric($show_val))
								{
						?>
								<option value="<?php echo $show_val?>"><?php echo $qty_prefix.' '.$show_val.' '.$qty_suffix?></option>
						<?php
								}	
							}
						?>
						</select>
						</div>
						</li>
					<?php	
					}				
				}
	
			}
			
	 ?>
				<li><input name="Submit_buy" type="submit" class="buttonblackbuy" id="Submit_buy" value="<?php echo $Captions_arr['PROD_DETAILS'][$caption_key];?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addcart'" /></li>
		<?php
			}
			// Check whether the enquire link is to be displayed
			if ($row_prod['product_show_enquirelink']==1)
			{
	 ?>			
				<li><input name="Submit_enq" type="submit" class="buttonblackbuy" id="Submit_enq" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ENQUIRY'];?>" onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Enquire'" /></li>
	<?php
			}
		if ($Settings_arr['proddet_showwishlist'])
		{	
			if($cust_id) // ** Show the wishlist button only if logged in 
			{
	  ?>
				<li><input name="submit_wishlist" type="submit" class="buttonblackbuy" id="submit_wishlist" value="<?php echo $Captions_arr['PROD_DETAILS']['PRODDET_ADDWISHLIST'];?>"  onclick="show_wait_button(this,'Please Wait...');document.frm_proddetails.fpurpose.value='Prod_Addwishlist'"  /></li>
	  <?php
			}
		}	
		?>
		</ul>
		</div>
		<?php	
			return true;
		}
		function show_more_images($row_prod,$exclude_tabid,$exclude_prodid)
		{
				global $db,$ecom_hostname,$ecom_themename;
				$show_normalimage = false;
				$prodimg_arr		= array();
				$pass_type = 'image_iconpath';
				
				if ($_REQUEST['prod_curtab'] and $row_prod['product_variablecombocommon_image_allowed']=='N')
				{
					if ($exclude_tabid)
						$prodimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type.',image_thumbpath',$exclude_tabid,0);	
					if (count($prodimg_arr)==0) // case if no more tab images exists
					{
						$show_normalimage = true;
					}
				}
				else // case of coming with out tab id, so show the normal image list if any
				{
					$show_normalimage = true;
				}	
				 
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
              <div class="prdt_subimage">
						
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
						foreach ($prodimg_arr as $k=>$v)
						{
							$title = ($v['image_title'])?stripslashes($v['image_title']):$row_prod['product_name'];
						?>
							 <ul>
								<li>
							<?php /*?><a href="#" onclick="link_submit('<?php echo $_REQUEST['prod_curtab']?>','<?php echo $v['image_id']?>','<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1)?>',0)" title="<?php echo $title?>"><?php 
							<a href="javascript:showImagePopup('<?php echo $v['image_extralargepath']?>','<?php echo $ecom_hostname?>','<?php echo $ecom_themename?>');"  title="<?php echo $title?>">
							*/?>
							<a href="<?php url_root_image($v['image_extralargepath'])?>" rel='lightbox[gallery]' title="<?=$title?>">
							<?php
								 show_image(url_root_image($v['image_iconpath'],1),$title,$title,'preview');
							?>
							</a>
							   </li>
							  </ul>

						<?php
						}
						?>	
						</div>
			  <?php
				}
			}	
			// Function which holds the logic for image swapping using javascript
			function Show_Javascript_Image_Swapper($row_prod)
			{
				global $ecom_hostname,$ecom_themename,$db;
			?>
			<script type="text/javascript">
			
				// JavaScript Document
     			// Globals
				// Major version of Flash
				var installedMajorVersion = 1;
				// Minor version of Flash
				var installedMinorVersion = 0;
				// Minor version of Flash
				var installedRevision = 0;
				// Major version of Flash required
				var requiredMajorVersion = 9;
				// Minor version of Flash required
				var requiredMinorVersion = 0;
				// Minor version of Flash required
				var requiredRevision = 0;
				// Version check based upon the values entered above in "Globals"
				var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
				var hasAnyVersion = DetectFlashVer(installedMajorVersion, installedMinorVersion, installedRevision);
				var oLoader = new Image();
				oLoader.src = '<?php url_site_image('product_loading_spinner.gif')?>';			
				var oZoomImage = new Image();
				var tZoomLoaded;
				var	arrMainImage = new Array;
				var	arrThumbImage = new Array;
				var	arrBigImage = new Array;
				var tmpHolder = new Array
				var tmpHolderBig = new Array
				var firstTime360 = true
			</script>
			<?php
				$show_normalimage = false;
				if ($_REQUEST['prod_curtab'])
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
						$exclude_tabid 	= $tabimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
						$main_img			=  url_root_image($tabimg_arr[0]['image_bigpath'],1);
						$large_img			=  url_root_image($tabimg_arr[0]['image_extralargepath'],1);
						$icon_img			=  url_root_image($tabimg_arr[0]['image_iconpath'],1);
						?>
						<script type="text/javascript">
							arrMainImage[0] = new Array("<?php echo $icon_img?>", "<?php echo $main_img?>", "<?php echo $large_img?>","<?php echo $main_img?>");
						</script>
						<?php
						//show_image(url_root_image($tabimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
						?>
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
					// Calling the function to get the image to be shown
					$prodimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,$showonly,1);
					if(count($prodimg_arr))
					{
						$exclude_prodid = $prodimg_arr[0]['image_id']; 		// exclude id in case of multi images for products
						$main_img			=  url_root_image($prodimg_arr[0]['image_bigpath'],1);
						$large_img			=  url_root_image($prodimg_arr[0]['image_extralargepath'],1);
						$icon_img			=  url_root_image($prodimg_arr[0]['image_iconpath'],1);
						?>
						<script type="text/javascript">
							arrMainImage[0] = new Array("<?php echo $icon_img?>", "<?php echo $main_img?>", "<?php echo $large_img	?>", "<?php echo $main_img	?>");
						</script>
						<?php
						//show_image(url_root_image($prodimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
						$show_noimage 	= false;
					}
					else
					{	
						// calling the function to get the default no image 
						$no_img = get_noimage('prod','big'); 
						if ($no_img)
						{
						?>
							<script type="text/javascript">
								arrMainImage[0] = new Array("<?php echo $no_img?>", "<?php echo $no_img?>", "<?php echo $no_img?>" , "<?php echo $no_img?>");
							</script>
						<?php
							//show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
							
						}	
					}
				}
				// Finding the thumb images
				$pass_type = 'image_iconpath';
				
				if ($_REQUEST['prod_curtab'])// case if came by clicking the tab
				{
					if ($exclude_tabid)
						$prodthumbimg_arr = get_imagelist('prodtab',$_REQUEST['prod_curtab'],$pass_type.',image_thumbpath',$exclude_tabid,0,0,'rand()');	
					if (count($prodthumbimg_arr)==0) // case if no more tab images exists
					{
						$show_normalimage = true;
					}
				}
				else // case of coming with out tab id, so show the normal image list if any
				{
					$show_normalimage = true;
				}	
				 
				 if ($show_normalimage==true) // the following is to be done only coming for normal image display
				 {
					if ($exclude_prodid)
						$prodthumbimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type.',image_thumbpath',$exclude_prodid,0,0,'rand()');
	
				 }
				 if (count($prodthumbimg_arr))
				 {
				 		$i = 0;
				 		foreach ($prodthumbimg_arr as $k=>$v)
						{
							$icnimg			= url_root_image($v['image_iconpath'],1);
							$icon_arr[$i] 	= $icnimg;
							$bgimg			= url_root_image($v['image_bigpath'],1);
							$large_img		=  url_root_image($v['image_extralargepath'],1);
				?>
								<script type="text/javascript">
									arrThumbImage[<?php echo $i?>] = new Array("<?php echo $icnimg?>", "<?php echo $bgimg?>", "<?php echo $large_img?>", "<?php echo $bgimg?>");
								</script>
				<?php		
							$i++;
						}
				 }
			?>
				<script type="text/javascript">
					for (i=0; i < arrThumbImage.length; i++) 
					{
						var preload = new Image();
						preload.src = arrThumbImage[i][1];
						preload.src = arrThumbImage[i][2];
					}
					for (i=0; i < arrMainImage.length; i++)
					{
						var preload = new Image();
						preload.src = arrMainImage[i][1];
					}	
				</script>
				<table class="swapper_table_cls" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="left" valign="top" style="overflow:hidden; background-color:#FFFFFF">
					<div class="content_product_container" style="width:380px;">
						<div class="content_product_images"  style="width:380px;">
							<div>
								<div style="display: block;" id="pnlMainImage">
								<div id="dvMainImageZoom" class="content_product_images_mainimage_zoom" style="width: 380px; height: 370px; display: none;" align="left" title="Click and drag image"> <img id="imgMainImageZoom" src="" alt="" style='border:0'> </div>
								<a id="hypMainImage" href="javascript:ShowZoomImage();" style="display: block;clear:both;" onmouseover="window.status='Click on images to enlarge them'; return true;" onmouseout="window.status=''; return true;" title="<?php echo $row_prod['product_name']?>"> <img id="imgMainImage" class="content_product_images_mainimage" src="<?php echo $main_img?>" alt="<?php echo $row_prod['product_name']?>" style="border-width: 0px; display: block;" > </a>
								<a  style="display: block;float:left;" id="hypZoomPlus" href="javascript:ShowZoomImage();"> <img src="<?php url_site_image('product_ZoomPlus.gif')?>" alt="Zoom In" border="0"></a>
								 <a style="display: none;float:left; " id="hypZoomMinus" href="javascript:HideZoomImage();"> <img src="<?php url_site_image('product_ZoomMinus.gif')?>" alt="Zoom Out" border="0"></a>
								<a id="hypDragImage" style="border-width: 0px;float:left;clear:right;width:78%;display: block;"> <img src="<?php echo url_site_image('product_clickndrag.gif')?>" alt="Click and drag the zoomed image" style="border-width: 0px;"> </a>
								<?php 
									if($row_prod['product_flv_filename']!='') // make the 
									{
								?>
									<a id="hypVideoImage" href="javascript:ShowVideo();" style="border-width: 0px;float:left;width:78%; overflow:hidden"> <img src="<?php url_site_image('product_ViewCatwalk.gif')?>" alt="Click here to view the video" style="border-width: 0px; "></a> 
								<?php
									}
									else
										echo '<div style="float:left;width:78%; overflow:hidden"><img src="'.url_site_image('blank.gif',1).'" border="0" id="img_blank"/></div>';
								?>	
								</div>
								<?php 
									if($row_prod['product_flv_filename']!='')
									{
								?>
										<div style="display: none; height:370px" id="divFlash" class="content_product_flash">
<script type="text/javascript" src="http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/scripts/swfobject.js"></script>
										<script type="text/javascript">
		var s1 = new SWFObject("http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/swf/player.swf","ply","380","370","9","#FFFFFF");
		s1.addParam("allowfullscreen","true");
		s1.addParam("allowscriptaccess","always");
		s1.addParam("flashvars","file=http://<?php echo $ecom_hostname?>/images/<?php echo $ecom_hostname?>/product_flv/<?php echo $row_prod['product_flv_filename']?>");
		s1.write("divFlash");
	</script>
										</div>
									
										<div class="content_product_images_video_control">
										<div id="pnlVideoControl"><img id="blank_zoom" src="<?php url_site_image('zoom_blank.gif')?>" alt="" style='display: none;float:left;'> <a id="hypPhotoImage" href="javascript:HideVideo();" style="display: none;float:left; width:78%"> <img src="<?php url_site_image('product_ViewPictures.gif')?>" alt="Click here to go back to images" style="border-width: 0px;"> </a>
										</div>
								 <?php
									}
								?>
							  </div>
								<div id="pnlThumbImages" class="content_product_images_thumbimages">
								<ul class="hoverbox" >
								<?php
									for($i=0;$i<count($icon_arr);$i++)
									{
								?>	
									 <li><a href="javascript:ReplaceImageFromThumb(<?php echo ($i+1)?>);" onmouseover="window.status='Click on images to enlarge them'; return true;" onmouseout="window.status=''; return true;" title="Click on images to enlarge them"> <img id="imgThumb<?php echo ($i+2)?>" src="<?php echo $icon_arr[$i]?>" alt="Click on images to enlarge them" style="border-width: 1px;"></a>
									 </li>
								<?php
									}
								?> 
								</ul>
							  </div>
							</div>
					  </div>
					  </div>
					</td>
				</tr>
				</table>
				<span id="content_product_loading" style="display:none;"><span id="container"><img src="<?php url_site_image('product_loading_spinner.gif')?>" alt="Loading..." style="margin-left:50%; margin-top:50%;"></span></span>	
				<script language="JavaScript">LoadTmpHolder()</script>
			<?php
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
						// Calling the function to get the image to be shown
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
							show_image(url_root_image($prodimg_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
							?>
							</a>
							<?php
							$show_noimage 	= false;
						}
						else
						{	
							// calling the function to get the default no image 
							$no_img = get_noimage('prod','big'); 
							if ($no_img)
							{
								show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
							}	
						}
					}
					$ret_arr['exclude_tabid']		= $exclude_tabid;
					$ret_arr['exclude_prodid'] 	= $exclude_prodid;
					return $ret_arr;
			}
	};	
?>
