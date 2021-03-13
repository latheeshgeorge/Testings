<?php
	/*############################################################################
	# Script Name 	: shelfHtml.php
	# Description 	: Page which holds the display logic for middle shelves
	# Coded by 		: Sny
	# Created on	: 29-Dec-2007
	# Modified by	: Sny
	# Modified On	: 01-Feb-2008
	##########################################################################*/
	class shelf_Html
	{
		// Defining function to show the shelf details
		function Show_Shelf($cur_title,$shelf_arr)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
			$ajax_return_function = 'ajax_return_productdetailscontents';
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			$shelf_included = 0;
			
			if(!$_REQUEST['shelf_id'])
			{
				$shelf_included = 1;
			}
			include_once "ajax/ajax.php";
			if (count($shelf_arr))
			{
				$shelfsort_by			= $Settings_arr['product_orderfield_shelf'];
				$prodperpage			= $Settings_arr['product_maxcntperpage_shelf'];// product per page
			
				switch ($shelfsort_by)
				{
					case 'custom': // case of order by customer fiekd
					$shelfsort_by		= 'b.product_order';
					break;
					case 'product_name': // case of order by product name
					$shelfsort_by		= 'a.product_name';
					break;
					case 'price': // case of order by price
					$shelfsort_by		= 'calc_disc_price';
					break;
					case 'product_id': // case of order by price
					$prodsort_by		= 'a.product_id';
					break;	
					default: // by default order by product name
					$shelfsort_by		= 'a.product_name';
					break;
				};
				$shelfsort_order		= $Settings_arr['product_orderby_shelf'];
				$prev_shelf				= 0;
				//Iterating through the shelf array to fetch the product to be shown.
				foreach ($shelf_arr as $k=>$shelfData)
				{
					// Check whether shelf_activateperiodchange is set to 1
					$active 	= $shelfData['shelf_activateperiodchange'];
					if($active==1)
					{
						$proceed	= validate_component_dates($shelfData['shelf_displaystartdate'],$shelfData['shelf_displayenddate']);
					}
					else
						$proceed	= true;	
					if ($proceed)
					{
						if($_REQUEST['req']=='')
						{
						$cls = "shelfBtable_home";	
						}
						else
						{
						 $cls = "shelfBtable";
						}
						?>
						<script type="text/javascript">
						function showmask_shelf(id)
						{
							objs = eval('document.getElementById("'+id+'")');
							objs.style.display = 'block';
						}
						function hidemask_shelf(id)
						{
							objs = eval('document.getElementById("'+id+'")');
							objs.style.display = 'none';
						}
						</script>
						<table border="0" cellpadding="0" cellspacing="0" class="<?php echo $cls;?>">
	<?php 				if($cur_title)
						{
	?>					<tr><td colspan="3" class="shelfBheader" align="left"><?php echo $cur_title?></td></tr>
	<?php				}
						$desc = trim($shelfData['shelf_description']);
						if($desc!='' and $desc!='&nbsp;')
						{
	?>						<tr><td colspan="3" class="shelfBproddes" align="left"><?php echo $desc;?></td></tr>
						<?php
						}
						?>	
						<tr><td colspan="3">
							<?php
							if($shelf_included==0) // case of showall of shelf
							{
							?>
								<input type="hidden" name="donotproceed_shelf" id="donotproceed_shelf" value="">
								<div id="result_prod_div">
								<div id="pgng1_1"  align="left" class="message_box" style="display:none" ></div>
								<div id="last_msg_loader"></div>

								<script type="text/javascript">
								jQuery.noConflict();
								var $j = jQuery;
								$j(document).ready(function(){
									window.last_msg_funtion = function (showheader) 
									{ 
									   var dataString = '';
									   var sortby		= '<?php echo $shelfsort_by?>';
									   var sortorder 	= '<?php echo $shelfsort_order?>';
									   
									   if($j('#proddispstyle_type').val())
									   {
											var liststyle = $j('#proddispstyle_type').val();
									   }
									   else
									   {
											var liststyle 	= 'grid';
									   }
									   var prodperpage  = '<?php echo $prodperpage?>';
									   if(showheader==2)
									   {
											showheader=1;
											document.getElementById('ajax_paging_prevcntr').value = '';
											$j('#result_prod_div').html('<div id="pgng1_1"  align="left" class="message_box" style="display:none" ></div><div id="last_msg_loader"></div>');
											$j('div#last_msg_loader').html('<img src="<?php url_site_image('bigLoader.gif')?>">');
										}
									   var ID	=	$j(".message_box:last").attr("id");
									   sp1 		= ID.split('_');
									   sp2 		= sp1[0].replace('pgng','');
									   if (document.getElementById('ajax_paging_prevcntr'))
									   {
										   var cntr_vals = document.getElementById('ajax_paging_prevcntr').value;
										   if(cntr_vals!=sp2)
										   {
											   document.getElementById('ajax_paging_prevcntr').value = sp2;
										   }
										   else
										   {
											   return; // done to avoid repetation.
										   }
									   }
									   var qrystr = "&req=prod_shelf&shelf_sortorder="+sortorder+"&shelf_sortby="+sortby+"&shelf_prodperpage="+prodperpage+'&showheader='+showheader+'&liststyle='+liststyle+"&shelf_included="+"<?php echo $shelf_included?>";
									   $j('div#last_msg_loader').html('<img src="<?php url_site_image('bigLoader.gif')?>">');
									   $j.post("../../../includes/base_files/shelf.php?shelf_id="+<?php echo $_REQUEST['shelf_id']?>+"&fromajax_prodautoload=true&shelfdet_pg="+sp2+qrystr,
										function(data)
										{
											if (data != "") 
											{
												if(document.getElementById('paging_ended'))
												{
												}
												else
												{
													$j(".message_box:last").after(data);
												}	
											}
											$j('div#last_msg_loader').empty();
										});
									};  
									$j(window).scroll(function()
									{
										if  ($j(window).scrollTop() >= ($j(document).height() - $j(window).height()-500))
										{
											if(document.getElementById('paging_ended'))
											{
											}
											else
											{
												/*if($j("#donotproceed_shelf").val()==1)
												{
													
												}
												else
												{*/
													var wintop 	= $j(window).scrollTop();
													var docht 	= $j(document).height();
													var winht 	= $j(window).height();
													last_msg_funtion(0);
												/*}	*/
											}	
										}
									}); 
								if(document.getElementById('paging_ended'))
								{
								}
								else
								{
									last_msg_funtion(1);
								}
								});
								function display_view(typ)
								{
								if($j('#proddispstyle_type').val()!=typ)
								{
									$j('#proddispstyle_type').val(typ);
									last_msg_funtion(2);
								}	
								}	
								</script>	
								</div>
						
						<?php
						}
						else // case of shelf is included from any inner page
						{
								 $sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
									a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
									a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
									product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints ,
									a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
									a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
									a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
									a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
									a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
									a.product_show_pricepromise,a.product_freedelivery,
													IF(a.product_discount >0, 
													case a.product_discount_enteredasval
													WHEN 0 THEN (a.product_webprice-a.product_webprice*a.product_discount/100) 
													WHEN 1 THEN (IF((a.product_webprice-a.product_discount)>0,(a.product_webprice-a.product_discount),0)) 
													WHEN 2 THEN (a.product_discount) 
													END
													,a.product_webprice) calc_disc_price             
										FROM 
											products a,product_shelf_product b 
										WHERE 
											b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
											AND a.product_id = b.products_product_id 
											AND a.product_hide = 'N' 
										ORDER BY 
											$shelfsort_by $shelfsort_order 					
										LIMIT 
											0,8";
								$ret_prod = $db->query($sql_prod);
								$row_cat = $shelfData;
								$row_cat['product_displaytype']='grid';
								$row_cat['shelf_included']=1;
								$this->Show_Products($ret_prod,$tot_cnt,$start_var,$row_cat,$base_sort_by,$prodsort_order,$from_filter=false);
								//$this->Show_NomoreProducts();
						}
						?>
						</td>
						</tr>
						</table>
						<?php
					}	
				}
			}	
		}
		
		function Show_Products($ret_prod,$tot_cnt,$start_var,$shop_det,$def_orderfield,$def_orderby,$from_filter=false)
		{
			global $db,$Settings_arr,$Captions_arr,$inlineSiteComponents,$compare_button_displayed,$inlineSiteComponents;
			global $globalnxtpg;
			
			$Captions_arr['SHOP_DETAILS'] 	= getCaptions('SHOP_DETAILS');
			$showqty	= $Settings_arr['show_qty_box'];// show the qty box
		    $prodsort_by			= ($_REQUEST['shopdet_sortby'])?$_REQUEST['shopdet_sortby']:$def_orderfield;
			$prodperpage			= ($_REQUEST['shopdet_prodperpage'])?$_REQUEST['shopdet_prodperpage']:$Settings_arr['product_maxcntperpage'];// product per page
			$prodsort_order	= ($_REQUEST['shopdet_sortorder'])?$_REQUEST['shopdet_sortorder']:$def_orderby;
			?>
			 
            <div id="loader_img" class="refine_loading_div_ajax" style="height:15px;display:none;padding:5px;" alt="Loading, please wait.." ><img src="<?php url_site_image('ajax-loader_cart.gif')?>" alt="Loading, please wait.."/></div>
		
            <table width="100%" cellpadding="0" cellspacing="0">
			<?php
			if($_REQUEST['showheader']==1)// show this only in first page in autoscroll
			{
				if ($shop_det['shelf_included']==0) // this avoids the grid and list selection section on included shelves
				{
			?>	
					<tr>
						<td colspan="4" align="left" valign="top" class="shelfAheader_A">
							<table width="100%" border="0" cellpadding="0" cellspacing="0" class="productpagenavtable">
							<tr>
								<td height="30px" align="center" valign="middle" class="productpagenavtext">
									<?php //echo $Captions_arr['SHOP_DETAILS']['CATDET_ITEMSPERPAGE']?>
								<?php
								if(!$_REQUEST['shelf_prodperpage'])
								{
									$shopdet_prodperpage = $Settings_arr['product_maxcntperpage'];
								}
								else
								{
									$shopdet_prodperpage = $_REQUEST['shelf_prodperpage'];
								}
								
								$list_style = ($_REQUEST['liststyle'])?$_REQUEST['liststyle']:'grid';
								?>
								<div class="prod_1row_icon"><img src="<?php ($list_style=='list')? url_site_image('prod_list_active.png'):url_site_image('prod_list.png')?>" alt="List View" onclick="display_view('list')" title="List View" id="listviewimg"></div>
								<div class="prod_grid_icon"><img src="<?php ($list_style=='grid')? url_site_image('prod_grid_active.png'):url_site_image('prod_grid.png')?>" alt="Grid View" onclick="display_view('grid')" title="Grid View" id="gridviewimg"></div>
							
								<input type="hidden" name="proddispstyle_type" id="proddispstyle_type" value="<?php echo $list_style;?>"/>
								<input type="hidden" name="shelf_prodperpagetop" id="shelf_prodperpagetop" value="<?php echo $Settings_arr['product_maxcntperpage'] ?>"/>
								</td>
							</tr>
							</table>
						</td>
					</tr>
			<?php
			}
		}
		
			?>
			<tr>
				<td colspan="4">
				<?php
				// ** Showing the products listing 3 in a row 
				$pg_variable = 'shopdet_pg';
				$pass_type = get_default_imagetype('prodcat');
				$prod_compare_enabled = isProductCompareEnabled();
				if($shop_det['shelf_included']==1)
				{
					
				}
				else
				{
					if($_REQUEST['liststyle'])
					{
						$shop_det['product_displaytype'] = $_REQUEST['liststyle'];
					}
				}	
				
				switch($shop_det['product_displaytype'])
				{
				case 'grid': // case of three in a row
				if($_REQUEST['req']=='')
				{
				$cls = "shelfBtable_home";	
				}
				else
				{
				 $cls = "shelfBtable";
				}
				?>
				<table border="0" cellpadding="0" cellspacing="0" class="<?php echo $cls; ?>">
					<tr>
							<td colspan="3" class="" align="center">
								
					<?php
					
					// Calling the function to get the type of image to shown for current 
					$cur_row = 1 ;
					$max_col = 2;
					$col_cnts = 0;
					
					while($row_prod = $db->fetch_array($ret_prod))
					{
						$prodcur_arr[] = $row_prod;
						$col_cnts++;
						$wrapmainclass = ($col_cnts==4)?'newShelfWrapRight':'newShelfWrap';
						if($col_cnts==3)
						{
							$col_cnts =0;
						}
						
						if($shop_det['shelf_included']==1)
						{
						?>
							<div align="left" class="message_box_normal" >
						<?php	
						}
						else
						{		
					?>
						<div id="pgng<?php echo $globalnxtpg; ?>_<?php echo $row_prod['product_id']; ?>" align="left" class="message_box" >
						<?php
						}
						$unqid = uniqid('');
						$curmaskid = "catprodmask_$unqid";
						?>
						
						<div class="<?php echo $wrapmainclass?>"   style="position:relative" onmouseover="showmask_shelf('<?php echo $curmaskid ?>')" onmouseout="hidemask_shelf('<?php echo $curmaskid ?>')">
						<div id="<?php echo $curmaskid?>" class="overlay-box">
						
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
							$frm_name = uniqid('addtocartshelf_');
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
							<?php
								$cur_row ++;
							}
								?>
                                </td>
                                </tr>
                                <?php
                                if($shop_det['shelf_included']==1)
                                {
									if($_REQUEST['req']=='')
									{
										$shal_class = 'showallshelf_include_a';
									}
									else
									{
										$shal_class = 'showallshelf_include_a_inner';
									}
                                ?>
									<tr>
									<td class="showallshelf_include">
									<a href="<?php url_shelf_all($shop_det['shelf_id'],$shop_det['shelf_name'])?>" class="<?php echo $shal_class?>">Show All</a>
									</td>
									</tr>
                                <?php
							}
                                ?>
								</table>
						<?php
						break;	
						case 'list': // case of one in a row
						?>
						<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
                            <tr>
								<td colspan="3" class="" align="center">
								<div class="one-row-detail-wrap">	
                            <?php
							// Calling the function to get the type of image to shown for current 
							while($row_prod = $db->fetch_array($ret_prod))
							{
							?>
							<div id="pgng<?php echo $globalnxtpg; ?>_<?php echo $row_prod['product_id']; ?>" align="left" class="message_box" >
							<div class="one-row-list">
							<div class="one-row-img">
							<?php
							$pass_type = 'image_bigcategorypath';
							//if ($shop_det['shopbrand_product_showimage']==1)// Check whether description is to be displayed
							{	
							?>	
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
									$no_img = get_noimage('prod','image_thumbcategorypath'); 
									if ($no_img)
									{
										show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
									}
								}
								?>							
								</a>
							<?php
							}
							?>	
							<div class="list-ico-wrap">
								<?php
								if($row_prod['product_saleicon_show']==1)
								{
								?>
									<div class="icon-list"><img src="<?php url_site_image('small-sale.png')?>" alt="Sale"></div>
								<?php				
								}
								if($row_prod['product_newicon_show']==1)
								{
								?>
									<div class="icon-list"><img src="<?php url_site_image('small-new.png')?>" alt="New"></div>
								<?php				
								}
								if($row_prod['product_discount']>0)
								{
								?>
									<div class="icon-list"><img src="<?php url_site_image('small-offer.png')?>" alt="offer"></div>
								<?php				
								}
								if($row_prod['product_bulkdiscount_allowed']=='Y')
								{
								?>
									<div class="icon-list"><img src="<?php url_site_image('small-bulk-discount.png')?>" alt="Bulk Discount"></div>
								<?php				
								}
								if($row_prod['product_show_pricepromise']==1)
								{
								?>
									<div class="icon-list"><img src="<?php url_site_image('small-price-promise.png')?>" alt="Price Promise"></div>
								<?php				
								}
								?>
							 </div>

							</div>
							<span class="one-row-list1"></span>
							<div class="one-row-detail">
							<?php
							//if($shop_det['shopbrand_product_showtitle']==1)// whether title is to be displayed
							{
							?>
								<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><span class="recently-viewed-title"><?php echo stripslashes($row_prod['product_name'])?></span></a>
							<?php
							}
							?>
							<?php echo $row_prod['product_shortdesc']?><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"><img src="<?php url_site_image('small-more.png')?>" width="57" height="22" border="0" align="absmiddle" /></a>
							  <div class="bt-detail-wrap">
							  <?php
								$frm_name = uniqid('addtocart_');
								?>
								<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
								<input type="hidden" name="fpurpose" value="" />
								<input type="hidden" name="fproduct_id" value="" />
								<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
								<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
								<?php
								$class_arr['ADD_TO_CART']       = '';
								$class_arr['PREORDER']          = '';
								$class_arr['ENQUIRE']           = '';
								$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
								$class_arr['QTY']               = ' ';
								$class_td['QTY']				= 'prod_list_buy_a2';
								$class_td['TXT']				= 'prod_list_buy_b2';
								$class_td['BTN']				= 'prod_list_buy_c2';
								echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
								?>
								</form>	
							</div>
						   </div>
							<div class="one-row-price">
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
							<?php
								$cur_row ++;
							}
								?>
								</div>
                                </td>
                                </tr>
								</table>
						<?php
						break;							
						};
				?>
				</td>
			</tr>
            </table>
		<?php
		}
		// ** Function to show the no products message
		function Show_NomoreProducts()
		{
			global $Captions_arr;
			$Captions_arr['SHOP_DETAILS'] 	= getCaptions('SHOP_DETAILS');
		?>
			<div class="nomoreproducts"><?php echo $Captions_arr['SHOP_DETAILS']['NOMORE_PROD_MSG']?></div>
			 	
		<?php	
		}
		
	};	
?>
