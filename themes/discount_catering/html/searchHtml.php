<?php
	/*############################################################################
	# Script Name 	: searchHtml.php
	# Description 		: Page which holds the display logic for search
	# Coded by 		: LSH
	# Created on		: 01-Feb-2008
	# Modified on		: 27-Nov-2008
	# Modified by		: Sny
	##########################################################################*/
	class search_Html
	{
		//Defining the product details function
		function Show_Search($search_sql,$tot_cnt,$sql_relate,$from_filter=false)
		{
		  	global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$tot_cnt,$ecom_themeid,$default_layout,$Captions_arr,$prodperpage,$quick_search,$head_keywords,$row_desc,$inlineSiteComponents;
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			$Captions_arr['SEARCH'] 	= getCaptions('SEARCH');
			//Default settings for the search
			$Settings_arr['product_maxcntperpage_search'] = 9999;
			$prodsort_by			= ($_REQUEST['search_sortby'])?$_REQUEST['search_sortby']:$Settings_arr['product_orderfield_search'];
		  	$prodperpage			= ($_REQUEST['search_prodperpage'])?$_REQUEST['search_prodperpage']:$Settings_arr['product_maxcntperpage_search'];// product per page
			$prodsort_order		= ($_REQUEST['search_sortorder'])?$_REQUEST['search_sortorder']:$Settings_arr['product_orderby_search'];
			$showqty				= $Settings_arr['show_qty_box'];// show the qty box
			if($_REQUEST['search_id']>0) {

					$sql = "SELECT search_desc FROM saved_search WHERE search_id='".$_REQUEST['search_id']."' AND sites_site_id=$ecom_siteid";
					$res = $db->query($sql);
					$row = $db->fetch_array($res);
					$search_desc = stripslashes($row['search_desc']);

			}
			 switch ($prodsort_by)
				{
					case 'product_name': // case of order by product name
					$prodsort_by		= 'product_name';
					break;
					case 'price': // case of order by price
					$prodsort_by		= 'calc_disc_price';
					break;
					case 'product_id': // case of order by price
					$prodsort_by		= 'product_id';
					break;	
				};
			    switch ($prodsort_order)
				{
					case 'ASC': // case of order by product name
					$prodsort_order		= 'ASC';
					break;
					case 'DESC': // case of order by price
					$prodsort_order		= 'DESC';
					break;
				};
			 $query_string = "&";
		     $search_fields = array('quick_search','search_category_id','search_model','search_minstk','search_minprice','search_maxprice','cbo_keyword_look_option','rdo_mainoption','rdo_suboption');
				foreach($search_fields as $v) {
					$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
				}
			if($_REQUEST['search_label_value']){
			foreach($_REQUEST['search_label_value'] as $lab_val) {
					$query_string .= "search_label_value[]=$lab_val&";#For passing searh labels to javascript for passing to different pages.
				}
			}	
			$first_querystring=$query_string; //Assigning the initial string to the variable.
						$pg_variable	= 'search_pg';
						if($_REQUEST['top_submit_Page'] || $_REQUEST['bottom_submit_Page'] )
							{
							 $_REQUEST[$pg_variable] = 0;
							}
				        if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
						{
							if($_REQUEST[$pg_variable] == "")
							{
								$pg_variable_val	=	$_REQUEST['page_val'];
							}
							else
							{
								$pg_variable_val	=	$_REQUEST[$pg_variable];
							}
							$start_var 		= prepare_paging($pg_variable_val,$prodperpage,$tot_cnt);
							//echo "hai - ".$_REQUEST['req'];
						}	
						else
							$Limit = '';
							$sql_meta = "SELECT search_content FROM se_meta_description WHERE sites_site_id=$ecom_siteid";
							$res_meta = $db->query($sql_meta);
							$row_desc = $db->fetch_array($res_meta);
							$querystring = ""; // if any additional query string required specify it over here
							if($from_filter == false)
							{
								$Limit			= " ORDER BY $prodsort_by $prodsort_order LIMIT ".$start_var['startrec'].", ".$prodperpage;
							}
							//echo $search_sql.$Limit;
							$ret_search = $db->query($search_sql.$Limit);
							if ($db->num_rows($ret_search))
							{							
								$comp_active = isProductCompareEnabled();
	
								// Calling the function to get the type of image to shown for current 
								$pass_type = get_default_imagetype('search');
								$prod_compare_enabled = isProductCompareEnabled();
								echo $Settings_arr['search_prodlisting'];
								switch($Settings_arr['search_prodlisting'])
									{
									  case '3row':
								  ?>
										<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
                                        <?php
											if($from_filter == false)
											{
										?>
										<tr>
												<td colspan="3" class="treemenu" align="left"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a><? if($_REQUEST['quick_search']!=""){?> >> <? echo $_REQUEST['quick_search']; } ?> </td>
										</tr>
										  <?php
							if(trim($search_desc)) {
									$top_content = $search_desc;
							} else if($row_desc['search_content'] && $_REQUEST['quick_search'])
											{
												$srch_arr 			= array('[title]','[keywords]','[first_keyword]');
												$rp_arr				= array($ecom_title,$head_keywords,$_REQUEST['quick_search']);
												$top_content		= str_replace($srch_arr,$rp_arr,$row_desc['search_content']);
											?>
											<? } 
											
											if($_REQUEST['quick_search']!="")
											{
											?> 
											<tr>
											<td colspan="3" align="left">	
											<span class="search_res_head_h1"><? echo strtoupper($_REQUEST['quick_search']); ?></span>
											</td>
											</tr>
											<?php
											}
												if($top_content!='')
												{
										?>
										<tr>
												<td colspan="3" class="search_topcontent" align="left"><?php echo stripslashes($top_content)?></td>
										</tr>
                                        <?php
												}
											}
										?>
                                        <tr>
                                        <td colspan="3">
                                        <div id="result_filter">
                                        <table cellpadding="0" cellspacing="0" width="100%">
										<tr>
				<td colspan="3" class="shelfAheader_A" >
					<?php echo paging_show_totalcount($tot_cnt,'Product(s)',$start_var['pg'],$start_var['pages'])?></td>
				</tr>
			<tr>
										<tr>
										<td colspan="3" align="left" valign="top" class="shelfAheader_A">
										
										<table width="100%" border="0" cellpadding="0" cellspacing="0" class="productpagenavtable">
										<tr>
												<td width="63%" height="30" align="right" valign="middle" class="productpagenavtext" colspan="2"><?php echo $Captions_arr['SEARCH']['SEARCH_SORT']?>
												  <select name="searchprod_sortbytop" id="searchprod_sortbytop">
													<option value="product_name" <?php echo ($prodsort_by=='product_name')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SEARCH_PROD_NAME']?></option>
													<option value="price" <?php echo ($prodsort_by=='calc_disc_price')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SEARCH_PROD_PRICE']?></option>
											  <option value="product_id" <?php echo ($prodsort_by=='product_id')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SHOPDET_DATEADDED']?></option>		
												  </select>
												  <select name="searchprod_sortordertop" id="searchprod_sortordertop">
													<option value="ASC" <?php echo ($prodsort_order=='ASC')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SEARCH_LOW2HIGH']?></option>
													<option value="DESC" <?php echo ($prodsort_order=='DESC')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SEARCH_HIGH2LOW']?></option>
												  </select></td>
												<td width="37%" align="right" valign="middle" class="productpagenavtext"><?php echo $Captions_arr['SEARCH']['SEARCH_ITEMS'] ?>
												<select name="searchprod_prodperpagetop" id="searchprod_prodperpagetop">
												<?php
													for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
													{
												?>
														<option value="<?php echo $ii?>" <?php echo ($prodperpage==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
												<?php	
													}
												?>
																		<option value="9999" <?php echo ($prodperpage==9999)?'selected="selected"':''?>>display all</option>

												</select>
                                                <?php
													if($from_filter == true)
													{
												?>	<input type="button" name="submit_Page" value="<?php echo $Captions_arr['SEARCH']['SEARCH_GO']?>" class="buttonred" onclick="javascript: sortAction('top','search');" />
												<?php
                                                    }
                                                    else
                                                    {
                                                ?>
												<input type="submit" name="top_submit_Page" value="<?php echo $Captions_arr['SEARCH']['SEARCH_GO'];?>" class="buttonred" onclick="handle_searchdropdownval_sel('<?php echo $ecom_hostname?>','searchprod_sortbytop','searchprod_sortordertop','searchprod_prodperpagetop')" />
                                                <?php
													}
												?>
												</td>
										</tr>
										</table>
										<input type="hidden" name="pos" value="top" />
										<input type="hidden" name="quick_search" value="<?=$quick_search?>" />
										<input type="hidden" name="search_category_id" value="<?=$_REQUEST['search_category_id']?>" />
										<input type="hidden" name="search_model" value="<?=$_REQUEST['search_model']?>" />
										<input type="hidden" name="search_minstk" value="<?=$_REQUEST['search_minstk']?>" />
										<input type="hidden" name="search_minprice" value="<?=$_REQUEST['search_minprice']?>" />
										<input type="hidden" name="search_maxprice" value="<?=$_REQUEST['search_maxprice']?>" />
										<input type="hidden" name="searchVariableName" value="<?=$_REQUEST['searchVariableName']?>" />
										<input type="hidden" name="searchVariableOption" value="<?=$_REQUEST['searchVariableOption']?>" />
										

										<?php 
									   //Section for making hidden values labels
										if(count($_REQUEST['search_label_value'])>0){
											foreach($_REQUEST['search_label_value'] as $v)
											{
												?>
											<input type="hidden" name="search_label_value[]" value="<?=$v?>"   />
												<?
											}	
										}
										//End 
									   ?>
										</td>
										</tr>
										<?php
										if($cur_title)
										{
										?>
											<tr>
												<td colspan="3" class="shelfAheader" align="left"><?php echo $cur_title?></td>
											</tr>
										<?php
										}
											if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{ 
												if($prodperpage!=9999)
												{
											?>
												<tr>
													<td colspan="3" class="pagingcontainertd" align="center">
													<?php
													 $query_string =$first_querystring;
														$path = '';
														$query_string .= "search_sortby=".$prodsort_by."&search_sortorder=".$prodsort_order."&search_prodperpage=".$prodperpage."&pos=top";
														if($from_filter == true)
														{
															paging_refine_search($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
														}
														else
														{
															paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0);
														}
													?>	
													</td>
												</tr>
											<?php
												}
											}
											?>
                                            <tr>
									<td colspan="3" class="" align="center">
                                            <?php
											$cur_row = 1 ;
									    $max_col = 3;
									    $col_cnts = 0;

								while ($row_search = $db->fetch_array($ret_search))
								{
									$col_cnts++;
									$prodcur_arr[] = $row_search;
										/*if($cur_row==0)
										{
										  echo "<tr>";
										}
										if($cur_row!=0 && $cur_row%2==0)
										{
											$cls = "prod_list_td";
											$clsTble = "prod_list_td_tbl";
									    }
									    else
									    {
										   $cls = "prod_list_td_r";
										   $clsTble = "prod_list_td_tbl_r";
										}*/
										if($col_cnts==3)
										{
											$col_cnts =0;
											$maincls = 'catBoxWrap_threecolumn_rt';	
										}
										else
											$maincls = 'catBoxWrap_threecolumn';
								     ?>
								     	<div class="<?php echo $maincls;?>">
<?php
												show_finanacebanner($row_search);

												//if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
												{
													?>
																<div class="featureimgqrap_three_column">
																<div>
																<a href="<?php url_product($row_search['product_id'],$row_search['product_name'],-1)?>" title="<?php echo stripslashes($row_search['product_name'])?>" <?php echo getmicrodata_producturl()?> class="product_image" >
																	<?php
																	// Calling the function to get the image to be shown
																	$img_arr = get_imagelist('prod',$row_search['product_id'],$pass_type,0,0,1);
																	if(count($img_arr))
																	{
																		show_image(url_root_image($img_arr[0][$pass_type],1),$row_search['product_name'],$row_search['product_name']);
																	}
																	else
																	{
																		// calling the function to get the default image
																		$no_img = get_noimage('prod',$pass_type); 
																		if ($no_img)
																		{
																			show_image($no_img,$row_search['product_name'],$row_search['product_name']);
																		}
																	}
																	?>							
																</a> 
																</div>
																</div>
																<?php
												}
																//if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
																{
																?>
																<div class="prod_list_name_link_three_column">
																<span class="shelfBprodname_three_column">
																<a href="<?php url_product($row_search['product_id'],$row_search['product_name'],-1)?>" title="<?php echo stripslashes($row_search['product_name'])?>"><span <?php echo getmicrodata_productname()?>><?php echo stripslashes($row_search['product_name'])?></span></a>
																</span>

																</div>
																<?php
																}
												//if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
												?>										
													<div class="prod_list_price_three">
										<?php			
										            $price_class_arr['ul_class'] 		= 'shelfBul_three_column';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													//	echo show_Price($row_search,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
													echo show_Price($row_search,$price_class_arr,'cat_detail_1');
													show_excluding_vat_msg($row_search,'vat_div');// show excluding VAT msg
												  }
																										
													?>																			
														
										</div>
										<div class="det_all_outer">

										<?php
										//if($shelfData['shelf_showrating']==1)
													{
														$module_name = 'mod_product_reviews';
														if(in_array($module_name,$inlineSiteComponents))
														{
															if($row_search['product_averagerating']>=0)
															{
																$HTML_rating = display_rating($row_search['product_averagerating'],1,'star-green.gif','star-white.gif',$row_search['product_id']);
																?>
																	<div class="starBlock">
																	<div class="ratingStars">
																	<?php
																	echo $HTML_rating;
																	?>
																	</div>
																	</div>
																<?php
															}
														}
													}
										?>
										
										<div class="list_compare_div">
												<?php if($comp_active)  { // to display Compare icon
															dislplayCompareButton($row_search['product_id']);
														}?>
												</div>	
												<div class="list_more_div">
												<?php show_moreinfo($row_search,'list_more')?>
												</div>
										<?php			
										if($row_search['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_search['product_saleicon_text']));
											if($desc!='')
											{
						?>						<div class="prod_list_sale_desc"><?php echo $desc?></div>
						<?php				}
										}
										if($row_search['product_newicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_search['product_newicon_text']));
											//if($desc!='')
											{
						?>						<div class="prod_list_sale_desc"><?php //echo $desc?></div>
						<?php				}
										}
										?>
									
												
										<?php
										//if($row_search['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_search['product_saleicon_text']));
											//if($desc!='')
											{
						?>						<div class="prod_list_sale_desc"><?php echo $desc?></div>
						<?php				}
										}
										?>
										</div>
										<?php
																	$frm_name = uniqid('catdet_');
													?>
																	<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_search['product_id']?>)">
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_search['product_id'],$row_search['product_name'])?>" />
																	<div class="prod_list_buy">
													<?php			$class_arr['ADD_TO_CART']       = '';
																	$class_arr['PREORDER']          = '';
																	$class_arr['ENQUIRE']           = '';
																	$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
																	$class_arr['QTY']               = ' ';
																	$class_td['QTY']				= 'prod_list_buy_a';
																	$class_td['TXT']				= 'prod_list_buy_b';
																	$class_td['BTN']				= 'prod_list_buy_c';
																	echo show_addtocart_v5($row_search,$class_arr,$frm_name,false,'','',true,$class_td);
													?>						</div>
												</form>
										</div>									
										<?php
										/*if($cur_row>=$max_col)
										{
										echo "</tr>";
										$cur_row = 0;
										}*/
										$cur_row ++;
													
											}
										?>
                                        </td>
                                        </tr>
                                        <?php
											if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{
												if($prodperpage!=9999)
												{
											?>
												<tr>
													<td colspan="3" class="pagingcontainertd" align="center">
													<?php 
														$path = '';
														 $query_string =$first_querystring;
														$query_string .= "search_sortby=".$prodsort_by."&search_sortorder=".$prodsort_order."&search_prodperpage=".$prodperpage."&pos=top";
														if($from_filter == true)
														{
															paging_refine_search($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0); 	
														}
														else
														{
															paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,0);
														}
													?>	
													</td>
												</tr>
											<?php
											 }
											}
										?>	
										<tr>
										<td colspan="3" align="left" valign="top" class="shelfAheader_A">
										<table width="100%" border="0" cellpadding="0" cellspacing="0" class="productpagenavtable">
										<tr>
												<td width="63%" height="30" align="right" valign="middle" class="productpagenavtext" colspan="2"><?php echo $Captions_arr['SEARCH']['SEARCH_SORT']?>
												  <select name="searchprod_sortbybottom" id="searchprod_sortbybottom">
													<option value="product_name" <?php echo ($prodsort_by=='product_name')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SEARCH_PROD_NAME']?></option>
													<option value="price" <?php echo ($prodsort_by=='calc_disc_price')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SEARCH_PROD_PRICE']?></option>
												  <option value="product_id" <?php echo ($prodsort_by=='product_id')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SHOPDET_DATEADDED']?></option>
												  </select>
												  <select name="searchprod_sortorderbottom" id="searchprod_sortorderbottom">
													<option value="ASC" <?php echo ($prodsort_order=='ASC')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SEARCH_LOW2HIGH']?></option>
													<option value="DESC" <?php echo ($prodsort_order=='DESC')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SEARCH_HIGH2LOW']?></option>
												  </select></td>
												<td width="37%" align="right" valign="middle" class="productpagenavtext"><?php echo $Captions_arr['SEARCH']['SEARCH_ITEMS'] ?>
												<select name="searchprod_prodperpagebottom" id="searchprod_prodperpagebottom">
												<?php
													for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
													{
												?>
														<option value="<?php echo $ii?>" <?php echo ($prodperpage==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
												<?php	
													}
												?>
												<option value="9999" <?php echo ($prodperpage==9999)?'selected="selected"':''?>>display all</option>

												</select>
                                                <?php
													if($from_filter == true)
													{
												?>	<input type="button" name="submit_Page" value="<?php echo $Captions_arr['SEARCH']['SEARCH_GO']?>" class="buttonred" onclick="javascript: sortAction('top','search');" />
												<?php
                                                    }
                                                    else
                                                    {
                                                ?>
												<input type="submit" name="bottom_submit_Page" value="<?php echo $Captions_arr['SEARCH']['SEARCH_GO']?>" class="buttonred" onclick="handle_searchdropdownval_sel('<?php echo $ecom_hostname?>','searchprod_sortbybottom','searchprod_sortorderbottom','searchprod_prodperpagebottom')"  />
                                                <?php
													}
												?>
												</td>
										</tr>
										</table>
										<input type="hidden" name="pos" value="bottom" />
										<input type="hidden" name="quick_search" value="<?=$quick_search?>" />
										<input type="hidden" name="search_category_id" value="<?=$_REQUEST['search_category_id']?>" />
										<input type="hidden" name="search_model" value="<?=$_REQUEST['search_model']?>" />
										<input type="hidden" name="search_minstk" value="<?=$_REQUEST['search_minstk']?>" />
										<input type="hidden" name="search_minprice" value="<?=$_REQUEST['search_minprice']?>" />
										<input type="hidden" name="search_maxprice" value="<?=$_REQUEST['search_maxprice']?>" />
										<input type="hidden" name="searchVariableName" value="<?=$_REQUEST['searchVariableName']?>" />
										<input type="hidden" name="searchVariableOption" value="<?=$_REQUEST['searchVariableOption']?>" />

										<?php 
									   //Section for making hidden values labels
										
										//echo $_REQUEST['count_label']; 
										if(count($_REQUEST['search_label_value'])>0){
											foreach($_REQUEST['search_label_value'] as $v)
											{
												?>
											<input type="hidden" name="search_label_value[]" value="<?=$v?>"   />
												<?
											}	
										}
										//End section 
									   ?>
										</td>
										</tr>
										</table>
                                        </div>
                                        </td>
                                        </tr>
                                        </table>
										<?
										break;
									
							
								}
							}
							if($from_filter == false)
							{
					//Related search keyword section.		
					if($sql_relate) //If the related search query exists
					{
					$ret_rel = $db->query($sql_relate);
										 while($row_rel = $db->fetch_array($ret_rel))
										 { 
											  if($row_rel['search_keyword']!=$quick_search)
											  {
											  $search_rel[]=$row_rel;
											  }
										 }
					if(count($search_rel))
						{
							 $val=0;
							?>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td colspan="4" class="pro_de_shelfBheader" align="left">Related searches</td>
									</tr>
									<tr><td colspan="4">&nbsp;</td></tr>
									<tr>
										<?
										foreach ($search_rel as $k=>$search_values)
										{
											 $val++;
											 ?>
												<td align="center"  ><a href="<? url_link('s'.$search_values['search_id'].'/'.strip_url($search_values['search_keyword']).'.html')?>" class="link">
											 <? 
											 echo $search_values['search_keyword'];
											 ?>
											 </a></td>
											 <?
											if($val>3)
											{ 
												 echo "</tr><tr><td colspan=4>&nbsp;</td> </tr><tr>";
												 $val=0;
											}
										}
										?>
									</tr>
							</table>
							<?
					   }
					}
				   }			
		}
				//Defining the product details function
		function Show_Search_Category($search_sql,$tot_cnt)
		{
		  	global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$search_prodperpage,$quick_search,$head_keywords,$row_desc,$inlineSiteComponents;
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
			
			$Captions_arr['SEARCH'] 	= getCaptions('SEARCH');
			//Default settings for the search
			$catsort_by					= ($_REQUEST['searchcat_sortby'])?$_REQUEST['searchcat_sortby']:'category_name';
		  	$catperpage					= ($_REQUEST['searchcat_perpage'])?$_REQUEST['searchcat_perpage']:$Settings_arr['product_maxcntperpage_search'];// product per page
			$catsort_order				= ($_REQUEST['searchcat_sortorder'])?$_REQUEST['searchcat_sortorder']:$Settings_arr['product_orderby_search'];
			
			 $query_string = "&";
		     $search_fields = array('quick_search');
				foreach($search_fields as $v) {
					$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
				}
			$first_querystring=$query_string; //Assigning the initial string to the variable.
						$pg_variable	= 'search_pg';
						if($_REQUEST['top_submit_Page'] || $_REQUEST['bottom_submit_Page'] )
							{
							 $_REQUEST[$pg_variable] = 0;
							}
				        if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
						{
							$start_var 		= prepare_paging($_REQUEST[$pg_variable],$catperpage,$tot_cnt);
						}	
						else
							$Limit = '';
							$querystring = ""; // if any additional query string required specify it over here
							$Limit			= " ORDER BY $catsort_by $catsort_order LIMIT ".$start_var['startrec'].", ".$catperpage;
							//echo $search_sql.$Limit;
							$ret_search = $db->query($search_sql.$Limit);
							if ($db->num_rows($ret_search))
							{
						  ?>
										<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
										<tr>
												<td colspan="3" class="treemenu" align="left"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a><? if($_REQUEST['quick_search']!=""){?> >> <? echo $_REQUEST['quick_search']; } ?> </td>
										</tr>
										<tr>
										<td colspan="3" align="left" valign="top" class="shelfAheader_A">
										<table width="100%" border="0" cellpadding="0" cellspacing="0" class="productpagenavtable">
										<tr>
												<td width="63%" height="30" align="right" valign="middle" class="productpagenavtext" colspan="2"><?php echo $Captions_arr['SEARCH']['SEARCH_SORT']?>
												  <select name="searchcat_sortbytop" id="searchcat_sortbytop">
													<option value="category_name" <?php echo ($catsort_by=='category_name')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SEARCH_CAT_NAME']?></option>
												  </select>
												  <select name="searchcat_sortordertop" id="searchcat_sortordertop">
													<option value="ASC" <?php echo ($catsort_order=='ASC')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SEARCH_LOW2HIGH']?></option>
													<option value="DESC" <?php echo ($catsort_order=='DESC')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SEARCH_HIGH2LOW']?></option>
												  </select></td>
												<td width="37%" align="right" valign="middle" class="productpagenavtext"><?php echo $Captions_arr['SEARCH']['SEARCH_ITEMS'] ?>
												<select name="searchcat_prodperpagetop" id="searchcat_prodperpagetop">
												<?php
													for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
													{
												?>
														<option value="<?php echo $ii?>" <?php echo ($catperpage==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
												<?php	
													}
												?>
												</select>
												<input type="submit" name="top_submit_Page" value="<?php echo $Captions_arr['SEARCH']['SEARCH_GO'];?>" class="buttonred" onclick="handle_searchcatdropdownval_sel('<?php echo $ecom_hostname?>','searchcat_sortbytop','searchcat_sortordertop','searchcat_prodperpagetop')" />
												</td>
										</tr>
										</table>
										</td>
										</tr>
										<?php
										if($cur_title)
										{
										?>
											<tr>
												<td colspan="3" class="shelfAheader" align="left"><?php echo $cur_title?></td>
											</tr>
										<?php
										}
											if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{
												if($_REQUEST['search_prodperpage']!=9999)
												{
											?>
												<tr>
													<td colspan="3" class="pagingcontainertd" align="center">
													<?php
													 	$query_string =$first_querystring;
														$path = '';
													
														$query_string .= "searchcat_sortby=".$catsort_by."&searchcat_sortorder=".$catsort_order."&searchcat_perpage=".$catperpage."&pos=top&rdo_mainoption=cat&cbo_keyword_look_option=".$_REQUEST['cbo_keyword_look_option'].'&rdo_suboption='.$_REQUEST['rdo_suboption'];
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Categories',$pageclass_arr); 	
													?>	
													</td>
												</tr>
											<?php
												}
											}
											$pass_type = 'image_iconpath';
											while ($row_search = $db->fetch_array($ret_search))
											{
												?>
														<tr onmouseover="this.className='normalshelf_hover'" onmouseout="this.className='shelfBtabletd'">
														<td align="left" valign="middle" class="shelfBtabletd">
														<?php 
															if($row_search['category_showimagetype']!='None' && ($img_support)) // ** Show sub category image only if catimage module is there for the site subcategoreynamelink
															{
														  ?>
															<a href="<?php url_category($row_search['category_id'],$row_search['category_name'],-1)?>" title="<?php echo stripslashes($row_search['category_name'])?>">
															 <?php
																	if ($row_search['category_showimageofproduct']==0) // Case to check for images directly assigned to category
																	{
																		// Calling the function to get the image to be shown
																		$img_arr = get_imagelist('prodcat',$row_search['category_id'],$pass_type,0,0,1);
																		if(count($img_arr))
																		{
																			show_image(url_root_image($img_arr[0][$pass_type],1),$row_search['category_name'],$row_search['category_name']);
																			$show_noimage = false;
																		}
																		else
																			$show_noimage = true;
																	}
																	else // Case of check for the first available image of any of the products under this category
																	{
																		// Calling the function to get the id of products under current category with image assigned to it
																		$cur_prodid = find_AnyProductWithImageUnderCategory($row_search['category_id']);
																		if ($cur_prodid)// case if any product with image assigned to it under current category exists
																		{
																			// Calling the function to get the image to be shown
																			$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
																			
																			if(count($img_arr))
																			{
																				show_image(url_root_image($img_arr[0][$pass_type],1),$row_search['category_name'],$row_search['category_name']);
																				$show_noimage = false;
																			}
																			else 
																				$show_noimage = true;
																			
																		}
																		else// case if no products exists under current category with image assigned to it
																			$show_noimage = true;
																	}
																	
																	// ** Following section makes the decision whether the no image is to be displayed
																	if ($show_noimage)
																	{
																		// calling the function to get the default no image 
																		$no_img = get_noimage('prodcat',$pass_type); 
																		if ($no_img)
																		{
																			show_image($no_img,$row_search['category_name'],$row_search['category_name']);
																		}	
																	}
																?>
																	</a>
															  <?php
																}
															  ?>
														<span class="shelfBprodname"><a href="<?php url_category($row_search['category_id'],$row_search['category_name'],-1)?>" title="<?php echo stripslashes($row_search['category_name'])?>"><?php echo stripslashes($row_search['category_name'])?></a></span>
													  </td>
													  <td colspan="2">
													  <h6 class="shelfBproddes"><?php echo stripslashes($row_search['category_shortdescription'])?></h6>
													  </td>
												</tr>
												<?php
													
											}
											if ($tot_cnt>0 and ($_REQUEST['req']!=''))
											{
											?>
												<tr>
													<td colspan="3" class="pagingcontainertd" align="center">
													<?php 
														$path = '';
														 $query_string =$first_querystring;
														$query_string .= "searchcat_sortby=".$catsort_by."&searchcat_sortorder=".$catsort_order."&searchcat_perpage=".$catperpage."&pos=top&rdo_mainoption=cat&cbo_keyword_look_option=".$_REQUEST['cbo_keyword_look_option'].'&rdo_suboption='.$_REQUEST['rdo_suboption'];
														paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Categories',$pageclass_arr); 	
													?>	
													</td>
												</tr>
											<?php
											}
										?>	
										<tr>
										<td colspan="3" align="left" valign="top" class="shelfAheader_A">
										<table width="100%" border="0" cellpadding="0" cellspacing="0" class="productpagenavtable">
										<tr>
												<td width="63%" height="30" align="right" valign="middle" class="productpagenavtext" colspan="2"><?php echo $Captions_arr['SEARCH']['SEARCH_SORT']?>
												  <select name="searchcat_sortbybottom" id="searchcat_sortbybottom">
													<option value="category_name" <?php echo ($catsort_by=='category_name')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SEARCH_CAT_NAME']?></option>
												  </select>
												  <select name="searchcat_sortorderbottom" id="searchcat_sortorderbottom">
													<option value="ASC" <?php echo ($catsort_order=='ASC')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SEARCH_LOW2HIGH']?></option>
													<option value="DESC" <?php echo ($catsort_order=='DESC')?'selected="selected"':''?>><?php echo $Captions_arr['SEARCH']['SEARCH_HIGH2LOW']?></option>
												  </select></td>
												<td width="37%" align="right" valign="middle" class="productpagenavtext"><?php echo $Captions_arr['SEARCH']['SEARCH_ITEMS'] ?>
												<select name="searchcat_prodperpagebottom" id="searchcat_prodperpagebottom">
												<?php
													for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
													{
												?>
														<option value="<?php echo $ii?>" <?php echo ($catperpage==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
												<?php	
													}
												?>
												</select>
												<input type="button" name="bottom_submit_Page" value="<?php echo $Captions_arr['SEARCH']['SEARCH_GO']?>" class="buttonred" onclick="handle_searchcatdropdownval_sel('<?php echo $ecom_hostname?>','searchcat_sortbybottom','searchcat_sortorderbottom','searchcat_prodperpagebottom')"  />
												</td>
										</tr>
										</table>
										</td>
										</tr>
										</table>
										<?
							}
		}
		function advancedSearch()
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
			$Captions_arr['SEARCH'] 	= getCaptions('SEARCH');
			$Captions_arr['CAT_DETAILS'] 	= getCaptions('CAT_DETAILS');
            $ajax_return_function = 'ajax_return_advsearchcontents';
					include "ajax/ajax.php";			?>
			<script language="JavaScript" type="text/javascript">
			function handle_search_options(opt)
			{
				var cattr_cnt = 4;
				var prodtr_cnt = 20;
				if(opt=='prod')
				{
					for(i=1;i<=cattr_cnt;i++)
					{
						obj = eval("document.getElementById('searchcat_tr"+ i +"')");
						if (obj)
							obj.style.display = 'none';
					}
					for(i=1;i<=prodtr_cnt;i++)
					{
						obj = eval("document.getElementById('searchprod_tr"+ i +"')");
						if (obj)
							obj.style.display = '';
					}
				}
				else if (opt =='cat')
				{
					for(i=1;i<=prodtr_cnt;i++)
					{
						obj = eval("document.getElementById('searchprod_tr"+ i +"')");
						if (obj)
							obj.style.display = 'none';
					}
					for(i=1;i<=cattr_cnt;i++)
					{
						obj = eval("document.getElementById('searchcat_tr"+ i +"')");
						if (obj)
							obj.style.display = '';
					}
				}		
						
			}
			<?php
			if($Settings_arr['adv_showcharacteristics']==1)
			{			
					$variables = array();
					//To get all products under this site.
					$prod_sql = "SELECT product_id FROM products WHERE sites_site_id=$ecom_siteid AND product_hide='N'";
					$ret_prod= $db->query($prod_sql);
					while($row_prod= $db->fetch_array($ret_prod))
					{
						$prod_ids[]=$row_prod['product_id']; 
					}
					if(count($prod_ids)>0)
					{ 
						$prod_str = implode(',',$prod_ids);
						//For the variable name under this site
						$AdvSearchVariables = "SELECT DISTINCT var_name FROM product_variables WHERE products_product_id IN ($prod_str) AND var_value_exists=1 ORDER BY var_name";
						$rstAdvSearchVariables=$db->query($AdvSearchVariables);
						while ($variable = $db->fetch_array($rstAdvSearchVariables))
						{
							array_push($variables, $variable[var_name]);
				 		}
					}
				?>
			function ajax_return_advsearchcontents() 
			{
				var ret_val = '';
				var disp 	= 'no';
				if(req.readyState==4)
				{
					if(req.status==200)
					{
						ret_val 		= req.responseText;
						targetdiv 	= document.getElementById('adv_retdiv_id').value;
						targetobj 	= eval("document.getElementById('"+targetdiv+"')");
						targetobj.innerHTML = ret_val; /* Setting the output to required div */
					}
					else
					{
						 alert(req.status);
					}
				}
			}
			function call_ajax_advancesearch(mod,var_name)
				{
					var fpurpose									= '';
					var retdivid										= '';
					var qrystr										= '';
					switch(mod)
					{
						case 'adv_characteristics': // Case of product variables
							retdivid   	= 'searchVariableOption_div';
							fpurpose	= 'adv_characteristics';
						break;
					};
					document.getElementById('adv_retdiv_id').value 		= retdivid;/* Name of div to show the result */	
					retobj 																= eval("document.getElementById('"+retdivid+"')");
					retobj.innerHTML 												= 'loading ...';															
					/* Calling the ajax function */
					Handlewith_Ajax('includes/base_files/search.php','ajax_fpurpose='+fpurpose+'&'+qrystr+'&cur_varname='+var_name);
				}
				<?php
				}
				?>
			</script>
			<?php
				$prod_ids = $cat_ids = 1;
				
			?>
			<form method="post" name="frm_quicksearch" class="frm_cls" action="<?php url_link('search.html')?>">
			<input type="hidden" name="search_src" id="search_src" value='advanced'/>
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
			<tr>
			  <td colspan="3" align="left" valign="top" ><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['SEARCH']['SEARCH_ADVANCED_SEARCH'];?></div></td>
			</tr>
			<?php
			if($Settings_arr['adv_showkeyword']==1)
			{	
			?>
			<tr>
				<td width="24%" align="left" valign="middle" class="searchfont_header">Enter search keyword </td>
				<td width="76%" colspan="2" align="left" valign="middle" class="searchfont"><input name="quick_search" type="text"  id="adv_quick_search"  value="<?=$_REQUEST['quick_search']?>" size="15"/>
				<select name="cbo_keyword_look_option" id="cbo_keyword_look_option">
				<option value="exact_phrase">Exact Phrase</option>
				<option value="all_word">All of these words</option>
				<option value="any_word">Any of these words</option>
				</select>
				<input name="search_submit2" type="submit" class="buttongray" id="search_submit" value="<?php echo $Captions_arr['SEARCH']['SEARCH_GO']?>" onclick="show_wait_button(this,'Please wait...')" /></td>
			</tr>
			<?php
			}
			if($Settings_arr['adv_showsearchfor']==1)
			{	
			  ?>
					<tr>
						<td align="left" class="searchfont_header">Search for </td>
						<td colspan="2" align="left" class="searchfont"><input name="rdo_mainoption" type="radio" value="prod" checked="checked" onclick="handle_search_options('prod')"/>
						Products <input name="rdo_mainoption" type="radio" value="cat" onclick="handle_search_options('cat')" />	Categories</td>
					</tr>
			 <?php
			 }
			 else // to handle the case if searchfor option is made hidden from console area
			 {
			 ?>
			 	<input type="hidden" name="rdo_mainoption" value="prod" id="rdo_mainoption" />
			 <?php
			 }
		  	if($Settings_arr['adv_showsearchincluding']==1)
			{
			  ?>
			<tr>
			  <td colspan="3" align="left" class="searchfont_header_border">Search including </td>
			  </tr>
			<tr>
			  <td colspan="3" align="left"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr>
                  <td width="3%" class="searchfont"><input name="rdo_suboption" type="radio" value="title" checked="checked"/></td>
                  <td class="searchfont">Search title only </td>
                </tr>
                <tr>
                  <td class="searchfont"><input name="rdo_suboption" type="radio" value="title_desc"   /></td>
                  <td class="searchfont">Search title &amp; descriptions</td>
                </tr>
              </table></td>
			  </tr>
			 <?php
			 }
			 else // handle the case if search including option is disabled from console area
			 {
			 ?>
			 <input type="hidden" name="rdo_suboption" id="rdo_suboption" value="title_desc" />
			 <?php
			 }
			 if($Settings_arr['adv_showcategory']==1)
			{
			 ?> 
			<tr id="searchprod_tr<?php echo $prod_ids++?>">
				<td colspan="3" align="left" class="searchfont_header_border">In this category </td>
			  </tr>
			<tr id="searchprod_tr<?php echo $prod_ids++?>">
			  <td colspan="3" align="left" class="searchfont">
			  <?php
						$parent_arr = generate_category_tree(0,0,true);
						if(is_array($parent_arr))
						{
							echo generateselectbox('search_category_id',$parent_arr,$_REQUEST['search_category_id']);
						}
					?></td>
			  </tr>
			  <?php
			  }
			if($Settings_arr['adv_showproductmodel']==1)
			{
					$sql_model  ="SELECT DISTINCT product_model FROM products WHERE sites_site_id = $ecom_siteid AND product_model<>'' AND product_hide='N' ORDER BY product_model";
					$ret_model  = $db->query($sql_model);
			?>
					<tr  id="searchprod_tr<?php echo $prod_ids++?>">
						<td colspan="3" align="left" class="searchfont_header_border">Model</td>
					</tr>
					<tr id="searchprod_tr<?php echo $prod_ids++?>">
						<td align="left" class="searchfont">Product Model </td>
						<td colspan="2" align="left">
						<select name="search_model" id="search_model" >
						<option value="">Select Model</option>
						<?php 
						while($modelname = $db->fetch_array($ret_model))
						{
						?>
						<option value="<?=$modelname['product_model']?>">
						<?=$modelname['product_model']?>
						</option>
						<?php
						}					  
						?></select>
						</td>
					</tr>
			<?php
			}
			if($Settings_arr['adv_showstocklevel']==1)
			{
			?>
					<tr id="searchprod_tr<?php echo $prod_ids++?>">
					<td colspan="3" align="left" class="searchfont_header_border">Stock</td>
					</tr>
					<tr id="searchprod_tr<?php echo $prod_ids++?>">
					<td align="left" class="searchfont">Minumum stock level </td>
					<td align="left" colspan="2"><input name="search_minstk" id="search_minstk" type="text" size="8" /></td>
					</tr>
			<?php
			}
			if($Settings_arr['adv_showpricerange']==1)
			{
		    ?> 
			 <tr id="searchprod_tr<?php echo $prod_ids++?>">
			   <td colspan="3" align="left" class="searchfont_header_border">Price</td>
		      </tr>
			 <tr  id="searchprod_tr<?php echo $prod_ids++?>">
			  <td align="left" class="searchfont">Show items prices from		      </td>
			  <td align="left" colspan="2"><input name="search_minprice" id="search_minprice" type="text" size="6" /> 
		      <span class="searchfont">to </span>
		      <input name="search_maxprice" id="search_maxprice" type="text" size="6" /></td>
			  </tr>
			 <?php
			 }
			if($Settings_arr['adv_showlabel']==1)
			{
				$sql_label_chk  ="SELECT  DISTINCT a.label_id,a.label_name,a.is_textbox FROM product_site_labels a ,product_labels b   WHERE a.sites_site_id = $ecom_siteid AND a.in_search = 1 AND a.label_hide=0  AND a.label_id=b.product_site_labels_label_id";
				$ret_label_chk  = $db->query($sql_label_chk);
				if($db->num_rows($ret_label_chk)>0)
				{
					$cnt_chk = 0;
					while($row_label_chk = $db->fetch_array($ret_label_chk))
					{
					$count_lab_chk=1;
					if($row_label_chk['is_textbox']==1){
					  $sql_lab_val_chk = "SELECT count(*) as cunt FROM product_labels WHERE product_site_labels_label_id =".$row_label_chk['label_id']." AND label_value!='' AND is_textbox=".$row_label_chk['is_textbox']."";
					  //echo $sql_lab_val;
					  $ret_lab_val_chk = $db->query($sql_lab_val_chk);
					  $row_lab_val_chk = $db->fetch_array($ret_lab_val_chk );
					  //echo $row_lab_val['cunt'];
					  $count_lab_chk= $row_lab_val_chk['cunt'];
					  }
					}
					if($count_lab_chk>0)
					{
						$sql_label  ="SELECT  DISTINCT a.label_id,a.label_name,a.is_textbox FROM product_site_labels a ,product_labels b   WHERE a.sites_site_id = $ecom_siteid AND a.in_search = 1 AND a.label_hide=0  AND a.label_id=b.product_site_labels_label_id";
						$ret_label  = $db->query($sql_label);
						$cnt = 0;
						?>
						<tr  id="searchprod_tr<?php echo $prod_ids++?>">
							   <td  class="searchfont_header_border" >Attributes</td>
							   <td class="searchfont_header_border" colspan="2">&nbsp;</td>
							</tr>
						<?php
						while($row_label = $db->fetch_array($ret_label))
						{
						  $label_id[] = $row_label['label_id'];
						  $count_lab = 1;
						  if($row_label['is_textbox']==1)
						  {
							  $sql_lab_val = "SELECT count(*) as cunt FROM product_labels WHERE product_site_labels_label_id =".$row_label['label_id']." AND label_value!='' AND is_textbox=".$row_label['is_textbox']."";
							  $ret_lab_val = $db->query($sql_lab_val);
							  $row_lab_val = $db->fetch_array($ret_lab_val );
							  $count_lab= $row_lab_val['cunt'];
						  }
						  if($count_lab>0)
						  {
							 $cnt++;
						  ?>
							 
							 <tr  id="searchprod_tr<?php echo $prod_ids++?>">
							  <td  class="searchfont" ><?=$row_label['label_name']?></td>
							   <td colspan="2">
							   <select name="search_label_value[]" class="select" ><option value="" >Select label value</option>
							  <?php  
								  $sql_label_val = "SELECT DISTINCT label_value FROM product_labels WHERE product_site_labels_label_id =".$row_label['label_id'] ." AND label_value!='' AND is_textbox=1 ";
								  $ret_label_val = $db->query($sql_label_val);
								   while($row_label_val=$db->fetch_array($ret_label_val))
								   {
								   ?>
									 <option value="<?=$row_label_val['label_value']?>"  ><?=$row_label_val['label_value']?></option>
								   <?
								   }
								   $sql_dropdown = "SELECT DISTINCT a.label_value,a.label_value_id FROM product_site_labels_values a,product_labels b WHERE a.product_site_labels_label_id=".$row_label['label_id'] ." AND a.label_value_id=b.product_site_labels_values_label_value_id ";   
								   $ret_dropdown = $db->query($sql_dropdown); 
								   while($row_dropdown=$db->fetch_array($ret_dropdown))
								   {
								   ?>
									 <option value="<?=$row_dropdown['label_value_id']?>" ><?=$row_dropdown['label_value']?></option>
								   <?
								   }
							  ?>
							  </select>									  </td>
							  </tr>
						  <?
						 }
						}
					}
				}
			}	 
				if($Settings_arr['adv_showcharacteristics']==1)
				{
					if(count($variables)>0)
					{
				?>
						<tr id="searchprod_tr<?php echo $prod_ids++?>"><td align="left"  class="searchfont_header_border"><?php echo $Captions_arr['SEARCH']['ADSEARCH_VARCHARISTICS']?></td>
						<td colspan="2" align="left" class="searchfont_header_border">&nbsp;</td>
						</tr>
						<tr id="searchprod_tr<?php echo $prod_ids++?>">
						  <td colspan="3" align="left"  class="searchfont"><select name="searchVariableName" id="searchVariableName" size="1" onchange="call_ajax_advancesearch('adv_characteristics',this.value);">
							<option value=" ">Any available Characteristic</option>
							<? 
							foreach ($variables as $variable)
							{
						?>
							<option value="<? print $variable; ?>"><? print $variable; ?></option>
							<?
							}
						?>
						  </select> <div id="searchVariableOption_div" style="text-align:left; display:inline">							</div></td>
						</tr>
						<tr id="searchprod_tr<?php echo $prod_ids++?>"><td colspan="3" align="center" class="searchfont">
						  </td>
						</tr>
			<?
					 }
				}
				if($Settings_arr['adv_shosearchsortby']==1)
				{
				?>
				<tr  id="searchprod_tr<?php echo $prod_ids++?>">
				<td colspan="3" class="searchfont_header_border">Sort By </td>
				</tr>
				<tr  id="searchprod_tr<?php echo $prod_ids++?>">
				<td colspan="3" class="searchfont">
				<?php 	$prodsort_by				= $Settings_arr['product_orderfield_search'];?>
				<select name="search_sortby" id="search_sortby">
				<option value="product_name" <?php echo ($prodsort_by=='product_name')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_PRODNAME']?></option>
				<option value="price" <?php echo ($prodsort_by=='price')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_PRICE']?></option>
				<option value="product_id" <?php echo ($prodsort_by=='product_id')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['SHOPDET_DATEADDED']?></option>
				</select>
				<select name="search_sortorder" id="search_sortorder">
				<option value="ASC" <?php echo ($prodsort_order=='ASC')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_LOW2HIGH']?></option>
				<option value="DESC" <?php echo ($prodsort_order=='DESC')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_HIGH2LOW']?></option>
				</select>					</td>
				</tr>
				<?php
				}
				if($Settings_arr['adv_showsearchperpage']==1)
				{
				?>
				<tr  id="searchprod_tr<?php echo $prod_ids++?>">
				  <td colspan="3" class="searchfont_header_border">Results per page </td>
			  </tr>
				<tr  id="searchprod_tr<?php echo $prod_ids++?>">
				  <td colspan="3" class="searchfont">
				  <?php
						$catdet_prodperpage = $Settings_arr['product_maxcntperpage_search'];
					
					?>
						<select name="search_prodperpage" id="search_prodperpage">
						<?php
							for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
							{
						?>
								<option value="<?php echo $ii?>" <?php echo ($catdet_prodperpage==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
							}
						?>
						</select>					</td>
			  </tr>
			  <?php
			  }
				if($Settings_arr['adv_shosearchsortby']==1)
				{
			  ?>
				<tr id="searchcat_tr<?php echo $cat_ids++?>" style="display:none">
					<td colspan="3" class="searchfont_header_border">Sort By</td>
				</tr>
				<tr id="searchcat_tr<?php echo $cat_ids++?>" style="display:none">
					<td colspan="3" class="searchfont">
					<select name="searchcat_sortby" id="searchcat_sortby">
					<option value="category_name" <?php echo ($catsort_by=='product_name')?'selected="selected"':''?>>Category Name</option>
					</select>
					<select name="searchcat_sortorder" id="searchcat_sortorder">
					<option value="ASC" <?php echo ($search_sortorder=='ASC')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_LOW2HIGH']?></option>
					<option value="DESC" <?php echo ($search_sortorder=='DESC')?'selected="selected"':''?>><?php echo $Captions_arr['CAT_DETAILS']['CATDET_HIGH2LOW']?></option>
					</select>
					</td>
				</tr>
				<?php
				}
				if($Settings_arr['adv_showsearchperpage']==1)
				{
				?>
					<tr id="searchcat_tr<?php echo $cat_ids++?>" style="display:none">
						<td colspan="3" class="searchfont_header_border">Results per page </td>
					</tr>
					<tr id="searchcat_tr<?php echo $cat_ids++?>" style="display:none">
					<td colspan="3" class="searchfont">
						<?php
						$catdet_prodperpage = $Settings_arr['product_maxcntperpage_search'];
						
						?>
						<select name="searchcat_perpage" id="searchcat_perpage">
						<?php
						for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
						{
						?>
						<option value="<?php echo $ii?>" <?php echo ($catdet_prodperpage==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
						<?php	
						}
						?>
					</select>	
					</td>
				</tr>
			  <?php
			  }
			  ?>
				<tr>
				  <td colspan="3" align="center" class="searchfont"><input name="search_submit2" type="submit" class="buttongray" id="search_submit" value="<?php echo $Captions_arr['SEARCH']['SEARCH_GO']?>" onclick="show_wait_button(this,'Please wait...')" /></td>
			  </tr>
			</table>
			<input type="hidden" name="count_label" value="<?=$cnt?>" />
			<?php if($_REQUEST['search_label_value'])
			{
				   //Section for making hidden values labels
					foreach($_REQUEST['search_label_value'] as $v)
					{
						?>
					<input type="hidden" name="search_label_value[]" value="<?=$v?>"   />
						<?
					}	
				}
			   ?>
			  <input type="hidden" name="adv_retdiv_id" id="adv_retdiv_id" value="" />
		</form> 
		<?php
		}	
		// Function to show the list of categories in alphabetic order as blocks
		function ShowAtoZCategories()
		{ 
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
			$Captions_arr['SEARCH'] 	= getCaptions('SEARCH'); 
			$alpha_str 		= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$alpha_len 		= strlen($alpha_str);
			$atleast_one	= 0;
			for($i=0;$i<$alpha_len;$i++)
			{
				$cur_char 					= substr($alpha_str,$i,1);
				$alpha_arr[$cur_char] 	= array();
			}
			$block_cnt = 0;
			$block_arr = array();
			// Get the list of active categories in site
			$sql_cat = "SELECT category_id,category_name 
								FROM 
									product_categories 
								WHERE 
									sites_site_id=$ecom_siteid 
									AND category_hide =0 
								ORDER BY 
									category_name ASC";
			$ret_cat = $db->query($sql_cat);
			if ($db->num_rows($ret_cat))
			{
				while ($row_cat = $db->fetch_array($ret_cat))
				{
					$first_char = strtoupper(substr(stripslashes($row_cat['category_name']),0,1));
					//echo 'first'.$first_char.'<br/>';
					if(array_key_exists($first_char,$alpha_arr))
					{
						if(count($alpha_arr[$first_char])==0)
						{
							$block_cnt++;
							$block_arr[] = $first_char;
						}	
						$alpha_arr[$first_char][] = stripslashes($row_cat['category_name']).'~~'.$row_cat['category_id'];
						$atleast_one++;
					}
				}
			}
			$start 			= 0; // start index
			$col_limit		= 4; // how many columns in each row
			$block_cnt		= count($block_arr);
			$per_col_limit	= floor($block_cnt/$col_limit); // how many character heading in each column
			$td_width		= round(100/$col_limit);
			$rem_blk		=$block_cnt%$col_limit; 
			$disp_blk_cnt	= 1;
			if ($rem_blk>0)
			{
				$extra_blk = 1;
				$extra_cnt	= $rem_blk;
			}
			else
				$extra_blk = 0;
			//if($rem_blk<$col_limit and $rem_blk>0)
			//	$extra_blk = 1;
		//	$per_col_limit += $extra_blk;
			if ($atleast_one>0)
			{
		?>
				<table align = "center" width="98%" border="0" cellspacing="0" cellpadding="3" class="">
					<tr>
						<td class="search_noresult_td">
						<?php
						if(trim($_REQUEST['quick_search'])!='')
						{
							$caption =  $Captions_arr['SEARCH']['SEARCH_NO_PRODUCTS_WITH_KEYWORD'];
							$caption = str_replace('[keyword]','<strong>'.$_REQUEST['quick_search'].'</strong>',$caption);
							echo stripslashes($caption);
						}
						else
						{
							echo stripslashes($Captions_arr['SEARCH']['SEARCH_NO_PRODUCTS_WITH_NO_KEYWORD']);
						}	
						
					?>
						</td>
					</tr>
</table>
				<table width="100%" cellpadding="0" cellspacing="8" border="0">
					<tr>
					<?php 
						$alpha_index = 0;
						for($cols=0;$cols<$col_limit;$cols++) // loop to handle the columns in each row
						{
							
					?>
							<td align="left" style="width:<?php echo $td_width	?>%" valign="top">
							<?php
								if($extra_blk and $extra_cnt>0)
								{
									$cur_col_limit = $per_col_limit + $extra_blk;
									--$extra_cnt;
								}
								else
									$cur_col_limit = $per_col_limit;
								/*if($cols==($col_limit-2))
								{
									if($disp_blk_cnt==($block_cnt-1))
									{
										$cur_col_limit = $cur_col_limit - $extra_blk;
									}
								}	*/
								for($i=0;$i<$cur_col_limit;$i++)
								{
									$cur_char 		= $block_arr[$alpha_index];
									$alpha_index++;
									if (count($alpha_arr[$cur_char])>0)
									{
										$disp_blk_cnt++;
								?>
										<table width="100%" cellpadding="0" cellspacing="2" border="0">
										<tr>
											<td class="searchspecial_header"><?php echo $cur_char?>
										</td>
										</tr>	
								<?php	
										if (count($alpha_arr[$cur_char]))
										{
											foreach($alpha_arr[$cur_char] as $k=>$v) // loop to handle the display of categories
											{
													$cat_arr = explode('~~',$v);
									?>
													<tr onmouseover="this.className='searchspecial_content_special'" onmouseout="this.className='searchspecial_content_normal'" class="searchspecial_content_normal" onclick="window.location='<?php echo url_category($cat_arr[1],$cat_arr[0],1)?>'">
														<td class="searchspecial_td">
														<a href="<?php url_category($cat_arr[1],$cat_arr[0])?>" title="<?php echo $cat_arr[0]?>" class="searchspecial_link"><?php echo $cat_arr[0]?></a>
														</td>
													</tr>		
									<?php	
											}
										}
								?>	
										</table>
									<?php
									}
								}
							?>
							</td>
					<?php
						}				
					?>
					</tr>
				</table>
		<?php	
			}
		}
	};	
?>