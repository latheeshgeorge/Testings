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
	function Show_Search($search_sql,$tot_cnt,$sql_relate)
	{
		global $inlineSiteComponents,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$prodperpage,$quick_search,$head_keywords,$row_desc;
		$Captions_arr['SEARCH'] 	= getCaptions('SEARCH');
		//Default settings for the search
		$prodsort_by		= ($_REQUEST['search_sortby'])?$_REQUEST['search_sortby']:$Settings_arr['product_orderfield_search'];
		$prodperpage		= ($_REQUEST['search_prodperpage'])?$_REQUEST['search_prodperpage']:$Settings_arr['product_maxcntperpage_search'];// product per page
		$prodsort_order		= ($_REQUEST['search_sortorder'])?$_REQUEST['search_sortorder']:$Settings_arr['product_orderby_search'];
		$showqty			= $Settings_arr['show_qty_box'];// show the qty box
		if($_REQUEST['search_id']>0)
		{
			$sql = "SELECT search_desc FROM saved_search WHERE search_id='".$_REQUEST['search_id']."' AND sites_site_id=$ecom_siteid";
			$res = $db->query($sql);
			$row = $db->fetch_array($res);
			$search_desc = $row['search_desc'];
		}
		switch ($prodsort_by)
		{
			case 'product_name': // case of order by product name
			$prodsort_bysql		= 'product_name';
			break;
			case 'price': // case of order by price
			$prodsort_bysql		= 'product_webprice';
			break;
			case 'product_id': // case of order by price
			$prodsort_bysql		= 'product_id';
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
		foreach($search_fields as $v)
		{
			$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
		}
		if($_REQUEST['search_label_value'])
		{
			foreach($_REQUEST['search_label_value'] as $lab_val)
			{
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
			$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
		}
		else
			$Limit = '';
		$sql_meta = "SELECT search_content FROM se_meta_description WHERE sites_site_id=$ecom_siteid";
		$res_meta = $db->query($sql_meta);
		$row_desc = $db->fetch_array($res_meta);
		$querystring = ""; // if any additional query string required specify it over here
		$Limit			= " ORDER BY $prodsort_bysql $prodsort_order LIMIT ".$start_var['startrec'].", ".$prodperpage;
		//echo $search_sql.$Limit;
		$ret_search = $db->query($search_sql.$Limit);
		if ($db->num_rows($ret_search))
		{
			// Calling the function to get the type of image to shown for current
			$pass_type = get_default_imagetype('search');
			$comp_active = isProductCompareEnabled();
			
			
			$top_content = '';
				// Getting the top content for searches
				if(trim($search_desc))
				{
					$top_content = trim($search_desc);
				}
				elseif($row_desc['search_content'] && $_REQUEST['quick_search'])
				{
					$srch_arr 			= array('[title]','[keywords]','[first_keyword]');
					$rp_arr				= array($ecom_title,$head_keywords,$_REQUEST['quick_search']);
					$top_content		= trim(str_replace($srch_arr,$rp_arr,$row_desc['search_content']));
				}
					$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';

					if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
					{
					   $query_string 	.= "&amp;search_sortby=".$_REQUEST['search_sortby'].'&amp;search_sortorder='.$_REQUEST['search_sortorder'].'&amp;search_prodperpage='.$_REQUEST['search_prodperpage'];
						$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Properties',$pageclass_arr);
					$HTML_paging	= '<div class="page_nav_content">
											<ul>
											'.$paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'].'
											</ul>
										</div>';
					}
				$HTML_treemenu = '<div class="treemenu">
									<a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>';
									if($_REQUEST['quick_search']!="")
									{
									 $HTML_treemenu .='<span>&raquo;</span>'. $_REQUEST['quick_search']. '';
									}
				$HTML_treemenu .=	  '</div>';
				echo $HTML_treemenu;
			if($paging['total_cnt'])
				$HTML_totcnt = '<div class="shelfAheader_A">'.$paging['total_cnt'].'</div>';
			$HTML_topcontent = 	$HTML_totcnt;
			$selval_arr = array (
									'custom'		=> stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DEFAULT']),
									'product_name'	=> stripslash_normal($Captions_arr['SEARCH']['SEARCH_PROD_NAME']),
									'price'			=> stripslash_normal($Captions_arr['SEARCH']['SEARCH_PROD_PRICE']),
									'product_id'	=> stripslash_normal($Captions_arr['SEARCH']['SHOPDET_DATEADDED']));
			//$HTML_topcontent .=	generateselectbox('searchprod_sortbytop',$selval_arr,$prodsort_by,'','',0,'',false,'searchprod_sortbytop');
			$selord_arr = array (
									'ASC'	=> stripslash_normal($Captions_arr['SEARCH']['SEARCH_LOW2HIGH']),
									'DESC'	=> stripslash_normal($Captions_arr['SEARCH']['SEARCH_HIGH2LOW'])
								);
			//$HTML_topcontent .=	generateselectbox('searchprod_sortordertop',$selord_arr,$prodsort_order,'','',0,'',false,'searchprod_sortordertop');
			$perpage_arr = array();
			for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
				$perpage_arr[$ii] = $ii;
				
		    $HTML_tophiddenfor  = '';
			//********Section for making hidden values labels
				if(count($_REQUEST['search_label_value'])>0)
				{
					foreach($_REQUEST['search_label_value'] as $v)
					{
					$HTML_tophiddenfor  .= '<input type="hidden" name="search_label_value[]" value="'.$v.'"   />';
					}
				}
				//End section
				$HTML_tophidden ='
				<input type="hidden" name="pos" value="top" />
				<input type="hidden" name="quick_search" value="'.$quick_search.'" />
				<input type="hidden" name="category_id" value="'.$_REQUEST['category_id'].'" />
				<input type="hidden" name="search_model" value="'.$_REQUEST['search_model'].'" />
				<input type="hidden" name="search_minstk" value="'.$_REQUEST['search_minstk'].'" />
				<input type="hidden" name="search_minprice" value="'.$_REQUEST['search_minprice'].'" />
				<input type="hidden" name="search_maxprice" value="'.$_REQUEST['search_maxprice'].'" />
				<input type="hidden" name="searchVariableName" value="'.$_REQUEST['searchVariableName'].'" />
				<input type="hidden" name="searchVariableOption" value="'.$_REQUEST['searchVariableOption'].'" />';
				$HTML_topcontent .= '<table class="productpagenavtable" border="0" cellpadding="0" cellspacing="0" width="100%">
									<tbody>
										<tr>
											<td class="productpagenavtext" align="left"  valign="middle" width="63%">
											'.stripslash_normal($Captions_arr['SEARCH']['SEARCH_SORT']).'
											'.generateselectbox('searchprod_sortbytop',$selval_arr,$prodsort_by,'','',0,'',false,'searchprod_sortbytop').'
											'.generateselectbox('searchprod_sortordertop',$selord_arr,$prodsort_order,'','',0,'',false,'searchprod_sortordertop').'
											</td>
											<td class="productpagenavtext" align="right" valign="middle" width="37%">
											'.stripslash_normal($Captions_arr['SEARCH']['SEARCH_ITEMS']).'
											'.generateselectbox('searchprod_prodperpagetop',$perpage_arr,$prodperpage,'','',0,'',false,'searchprod_prodperpagetop').'
											<input type="button" name="top_submit_Page" value="'.stripslash_normal($Captions_arr['SEARCH']['SEARCH_GO']).'" class="buttonred" onclick="handle_searchdropdownval_sel(\''.$ecom_hostname.'\',\'searchprod_sortbytop\',\'searchprod_sortordertop\',\'searchprod_prodperpagetop\')" />
											</td>
										</tr>
									</tbody>
									</table>';	
			$HTML_bottomcontent	=	$HTML_paging;
			$HTML_bottomcontent .= 	'<table class="productpagenavtable" border="0" cellpadding="0" cellspacing="0" width="100%">
									<tbody>
										<tr>
											<td class="productpagenavtext" align="left"  valign="middle" width="63%">
											'.stripslash_normal($Captions_arr['SEARCH']['SEARCH_SORT']).'
											'.generateselectbox('searchprod_sortbybottom',$selval_arr,$prodsort_by,'','',0,'',false,'searchprod_sortbybottom').'
											'.generateselectbox('searchprod_sortorderbottom',$selord_arr,$prodsort_order,'','',0,'',false,'searchprod_sortorderbottom').'
											</td>
											<td class="productpagenavtext" align="right" valign="middle" width="37%">
											'.stripslash_normal($Captions_arr['SEARCH']['SEARCH_ITEMS']).'
											'.generateselectbox('searchprod_prodperpagebottom',$perpage_arr,$prodperpage,'','',0,'',false,'searchprod_prodperpagebottom').'
											<input type="button" name="top_submit_Page" value="'.stripslash_normal($Captions_arr['SEARCH']['SEARCH_GO']).'" class="buttonred" onclick="handle_searchdropdownval_sel(\''.$ecom_hostname.'\',\'searchprod_sortbybottom\',\'searchprod_sortorderbottom\',\'searchprod_prodperpagebottom\')" />
											</td>
										</tr>
									</tbody>
									</table>';		
			$HTML_topcontent .= $HTML_tophiddenfor.$HTML_tophidden.$HTML_paging;
								if($top_content!='' and $top_content!='&nbsp;')
								{
									 $top_content = stripslashes($top_content);
									 $HTML_maindesc = '<div class="normal_shlfA_desc_outr">'.$top_content.'</div>';
								}
								if($_REQUEST['quick_search']!="")
								{
								?> 
								<h1 class="search_res_head_h1"><? echo strtoupper($_REQUEST['quick_search']); ?></h1>
								<?php
								}
								echo $HTML_maindesc;
								echo $HTML_topcontent;
								//echo $HTML_paging;
								?>
								
								<?php
								
								$max_col = 3;
								$cur_col = 0;
								$prodcur_arr = array();
								?>
								<div class="listdetailwrap">
								<?php
								while($row_prod = $db->fetch_array($ret_search))
								{
									$prodcur_arr[] = $row_prod;
									$HTML_title = $HTML_image = $HTML_desc = '';
									$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
									$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
									$HTML_title = '<h3><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></h3>';									
									
									$pass_type	=	'image_thumbpath';
									$img_arr	=	get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
									$tabimg_arr	=	get_imagelist('prod',$row_prod['product_id'],'image_extralargepath',0,0,1);
									
									$HTML_image ='<div class="slidemainImg"><a href="'.url_root_image($tabimg_arr[0]['image_extralargepath'],1).'" title="'.stripslash_normal($row_prod['product_name']).'" rel="lightbox[gallery-'.$row_prod['product_id'].']">';
									// Calling the function to get the image to be shown
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
									$HTML_image .= '</a></div>';
									
									$HTML_desc = "<div class='slidethumbwrap'>".stripslash_normal($row_prod['product_shortdesc'])."</div>";
									$price_class_arr['class_type']          = 'div';
									$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
									$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
									$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
									$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
									 $price_arr =  show_Price($row_prod,array(),'compshelf',false,3);
									if($price_arr['discounted_price'])
										$HTML_price = $price_arr['discounted_price'];
									else
										$HTML_price = $price_arr['base_price'];
								
									?>

									<div class="list">
										<?=$HTML_title;?>
                                        <div class="list_left">
                                            <?=$HTML_image?>
                                            <?php //echo $HTML_desc;?>
                                            <?php show_MoreImages_Unipad($row_prod,0,$tabimg_arr[0][0]);?>
                                        </div>
                                        
                                        <div class="list_right">
                                            <?php show_ProductVariables_Unipad($row_prod['product_id']); ?>
                                        </div>
                                        
                                        <div class="listicon">
                                            <?php show_ProductLabels_Unipad($row_prod['product_id']); ?>
                                            <div class="viewdetails">
                                                <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['COMMON_FULL_DETAILS'])?></a>                                                     
                                            </div>
                                        </div>
                                    </div>
								<?php
									/*if($row_prod['product_saleicon_show']==1)
									{
										$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
										//if($desc!='')
										{
											  $HTML_sale = '<div class="pdt_list_sale"></div>';
										}
									}
									if($row_prod['product_newicon_show']==1)
									{
										$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
										//if($desc!='')
										{
											  $HTML_new = '<div class="pdt_list_new"></div>';
										}
									}
									echo $HTML_new;
									echo  $HTML_sale;*/
								?>
								
								<?php
									/*$module_name = 'mod_product_reviews';
									if(in_array($module_name,$inlineSiteComponents))
									{
										if($row_prod['product_averagerating']>=0)
										{
											$HTML_rating = '<div class="list_d_pdt_rate">'.display_rating($row_prod['product_averagerating'],1).'</div>';
										}
									}
									echo $HTML_rating;*/
								?>
								
								<?php
									/*if($row_prod['product_bonuspoints']>0)
									{
										$HTML_bonus = 'Bonus: '.$row_prod['product_bonuspoints'].'';
									}
									else
									{
										$HTML_bonus = '';
									}	
									echo $HTML_bonus;*/
								   
								?>   
								
								<?php
									/*if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
									{
										echo "<div class='pdt_list_free_otr'>";
									}
										
									if($row_prod['product_freedelivery']==1)
									{
										echo $HTML_freedel = ' <div class="pdt_list_free_del"> </div>';
									}
									if($row_prod['product_bulkdiscount_allowed']=='Y')
									{
										echo $HTML_bulk = '<div class="pdt_list_free_bulk"> </div>';
									}
									if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
									{
									   echo "</div>";
									}*/
							   
									$cur_col++;
									if($cur_col>=$max_col)
									{
										$cur_col =0;
										//echo "</div>";
									}
									}
									if($cur_col<$max_col)
									{
										if($cur_col!=0)
										{ 
											//echo "</div>";
										} 
									}
								?>
								</div>
								
							<?php
								//echo $HTML_paging;
								echo $HTML_bottomcontent;
										
		}
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
			<div class="sub_cat_hdr_otr">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="related_table">
			<tr>
			<td colspan="4" class="related_header" align="left">
			           <div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span>Related searches</span></div></div>

			</td>
			</tr>
			<tr>
			<td colspan="4">&nbsp;</td>
			</tr>
			<?
			$max_cnt = 3;
			$cur_cnt = 0;
			foreach ($search_rel as $k=>$search_values)
			{
				if($cur_cnt==0)
					echo '<tr>';
				$cur_cnt++;
				?>
				<td align="center"><a href="<? url_link('s'.$search_values['search_id'].'/'.strip_url($search_values['search_keyword']).'.html')?>" class="link">
				<?
				echo $search_values['search_keyword'];
				?>
				</a>
				</td>
			<?
				if($cur_cnt>$max_cnt)
				{
					echo "</tr>";
					$cur_cnt=0;
				}
			}
			if($cur_cnt<$max_cnt and $cur_cnt>0)
				echo '</tr>';
			?>
			</table>
			</div>
			<?
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

		$Captions_arr['SEARCH'] = getCaptions('SEARCH');
		//Default settings for the search
		$catsort_by				= ($_REQUEST['searchcat_sortby'])?$_REQUEST['searchcat_sortby']:'category_name';
		$catperpage				= ($_REQUEST['searchcat_perpage'])?$_REQUEST['searchcat_perpage']:$Settings_arr['product_maxcntperpage_search'];// product per page
		$catsort_order			= ($_REQUEST['searchcat_sortorder'])?$_REQUEST['searchcat_sortorder']:$Settings_arr['product_orderby_search'];

		$query_string 			= "&";
		$search_fields 			= array('quick_search');
		foreach($search_fields as $v) {
		$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
		}
		$first_querystring		= $query_string; //Assigning the initial string to the variable.
		$pg_variable			= 'search_pg';
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
		echo $search_sql.$Limit;
		$ret_search = $db->query($search_sql.$Limit);
		if ($db->num_rows($ret_search))
		{
			$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
										if ($tot_cnt>0 and ($_REQUEST['req']!=''))
										{
										    $query_string =$first_querystring;
												$path = '';
											$query_string .= "searchcat_sortby=".$catsort_by."&searchcat_sortorder=".$catsort_order."&searchcat_perpage=".$catperpage."&pos=top&rdo_mainoption=cat&cbo_keyword_look_option=".$_REQUEST['cbo_keyword_look_option'].'&rdo_suboption='.$_REQUEST['rdo_suboption'];
											$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Categories',$pageclass_arr);
											$HTML_paging	= '	<div class="page_nav_con">
										<div class="page_nav_top"></div>
											<div class="page_nav_mid">
												<div class="page_nav_content">
												<ul>
												'.$paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'].'
												</ul>
												</div>
											</div>
										<div class="page_nav_bottom"></div>
	    							</div>';
										}
									$HTML_treemenu = '<div class="treemenu">
														<a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>
															';
														    if($_REQUEST['quick_search']!="")
															{
															 $HTML_treemenu .='<span>&raquo;</span>'. $_REQUEST['quick_search']. '';
															}
										$HTML_treemenu .=	  '</div>';
										echo $HTML_treemenu;
										echo '<div class="search_advance_cat"> <a href="'.url_link('advancedsearch.html',1).'" class="search_advance_lnk" title="'.$Captions_arr['COMMON']['ADVANCED_SEARCH'].'">'.$Captions_arr['COMMON']['ADVANCED_SEARCH'].'</a> </div>';
										if($paging['total_cnt'])
			$HTML_totcnt = '<div class="shelfAheader_A">'.$paging['total_cnt'].'</div>';
			$HTML_topcontent = 	$HTML_totcnt;
			$selval_arr = array (
									'category_name'	=> stripslash_normal($Captions_arr['SEARCH']['SEARCH_CAT_NAME'])
									);
			$HTML_topcontent .=	generateselectbox('searchcat_sortbytop',$selval_arr,$catsort_by,'','',0,'',false,'searchcat_sortbytop');
			$selord_arr = array (
									'ASC'	=> stripslash_normal($Captions_arr['SEARCH']['SEARCH_LOW2HIGH']),
									'DESC'	=> stripslash_normal($Captions_arr['SEARCH']['SEARCH_HIGH2LOW'])
								);
			$HTML_topcontent .=	generateselectbox('searchcat_sortordertop',$selord_arr,$catsort_order,'','',0,'',false,'searchcat_sortordertop');
			$perpage_arr = array();
			for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
				$perpage_arr[$ii] = $ii;
			$HTML_topcontent .=	generateselectbox('searchcat_prodperpagetop',$perpage_arr,$catperpage,'','',0,'',false,'searchcat_prodperpagetop');
			$HTML_topcontent .= '<table class="productpagenavtable" border="0" cellpadding="0" cellspacing="0" width="100%">
								<tbody>
									<tr>
										<td class="productpagenavtext" align="left"  valign="middle" width="63%">
										'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_SORTBY']).'
										'.generateselectbox('catdet_sortbytop',$selval_arr,$prodsort_by,'','',0,'',false,'catdet_sortbytop').'
										'.generateselectbox('catdet_sortordertop',$selord_arr,$prodsort_order,'','',0,'',false,'catdet_sortordertop').'
										</td>
										<td class="productpagenavtext" align="right" valign="middle" width="37%">
										'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_ITEMSPERPAGE']).'
										'.generateselectbox('catdet_prodperpagetop',$perpage_arr,$catdet_prodperpage,'','',0,'',false,'catdet_prodperpagetop').'
										<input type="button" name="submit_Page" value="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_GO']).'" class="buttonred" onclick="handle_categorydetailsdropdownval_sel(\''.url_category($_REQUEST['category_id'],stripslash_javascript($cat_det['category_name']),4,$_REQUEST['catgroup_id'],1).'\',\'catdet_sortbytop\',\'catdet_sortordertop\',\'catdet_prodperpagetop\')" />
										</td>
									</tr>
								</tbody>
								</table>';
			echo $HTML_topcontent;
			echo $HTML_paging;
			echo '
								 <div class="cate_mid_cont"> 
                                           <div class="cate_mid_cont_top">
								';
						$cnt = 0;
							$max_cnt = 5; 
		              	while ($row_search = $db->fetch_array($ret_search))
						{
															$startpnt++;
															if($cnt==0)
															{
															?>
															  <div class="sub_cate_mid_in">
															<?php
															}
														?>
														<div class="sub_cate_mid_div">
														<div class="sub_cate_mid_name"><a href="<?php url_category($row_search['category_id'],$row_search['category_name'],-1)?>" class="" title="<?php echo stripslash_normal($row_search['category_name'])?>"><span><?php echo ucwords(stripslash_normal($row_search['category_name']));?></span></a></div>
														<div class="sub_cate_mid_img">
														<a href="<?php url_category($row_search['category_id'],$row_search['category_name'],-1)?>" class="" title="<?php echo stripslash_normal($row_search['category_name'])?>">
														<?php
														if ($row_search['category_showimageofproduct']==0) // Case to check for images directly assigned to category
														{
														if ($_REQUEST['catthumb_id'])	
															$showonly = $_REQUEST['catthumb_id'];
														else
															$showonly = 0;
															// Calling the function to get the type of image to shown for current 
															$pass_type = 'image_thumbcategorypath';	
															// Calling the function to get the image to be shown
															$catimg_arr = get_imagelist('prodcat',$row_search['category_id'],$pass_type,0,$showonly,1);
															if(count($catimg_arr))
															{
																$exclude_catid 	= $catimg_arr[0]['image_id']; // exclude id in case of multi images for category
																$HTML_image = '<div class="cat_main_image">'.show_image(url_root_image($catimg_arr[0][$pass_type],1),$row_search['category_name'],$row_search['category_name'],'imgwraptext','',1).'</div>';
															}
															else
															{
																// calling the function to get the default image
																$no_img = get_noimage('prodcat',$pass_type); 
																if ($no_img)
																{
																	$HTML_image = show_image($no_img,$row_search['category_name'],$row_search['category_name'],'','',1);
																}       
															}
														}
														else // Case of check for the first available image of any of the products under this category
														{
															// Calling the function to get the id of products under current category with image assigned to it
															$cur_prodid = find_AnyProductWithImageUnderCategory($row_search['category_id']);
															if ($cur_prodid)// case if any product with image assigned to it under current category exists
															{
																	// Calling the function to get the type of image to shown for current 
																	$pass_type = get_default_imagetype('category');	
																	// Calling the function to get the image to be shown
																	$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
																if(count($img_arr))
																{
																	$HTML_image = '<div class="cat_main_image">'.show_image(url_root_image($img_arr[0][$pass_type],1),$row_search['category_name'],$row_search['category_name'],'imgwraptext','',1).'</div>';
																}
																else
																{
																	// calling the function to get the default image
																	$no_img = get_noimage('prodcat',$pass_type); 
																	if ($no_img)
																	{
																		$HTML_image = show_image($no_img,$row_search['category_name'],$row_search['category_name'],'','',1);
																	}       
																}
															}
														}	
															echo $HTML_image;
														?>
														</a>
														</div>
														<?php
														if ($row_search['category_paid_for_longdescription']=='Y' and trim($row_search['category_paid_description'])!='' and trim($row_search['category_paid_description'])!='<br>')
														{
															$cat_desc =   stripslash_normal($row_search['category_paid_description']);
														}
														elseif (trim($row_search['category_shortdescription'])!='')
														{
															$cat_desc = nl2br(stripslash_normal($row_search['category_shortdescription']));
														}
														if ($cat_desc!='')
														{
															$HTML_catdesc = '<div class="sub_cate_mid_des">'.$cat_desc.'</div>';
														}
															echo $HTML_catdesc;
														?>
														<div class="sub_cate_mid_more"> <a href="<?php url_category($row_search['category_id'],$row_search['category_name'],-1)?>" class="cate_mid_ul_a" title="<?php echo stripslash_normal($row_search['category_name'])?>"><img src="<?php url_site_image('ca-more.gif')?>" width="49" height="12" /></a></div>
														</div>
														<?php
															$cnt++;
														   if($cnt>=$max_cnt)
														   {
															   ?>
															   </div>
															   <?php
															   $cnt=0;
														   }
													}
													if($cnt!=0 && $cnt<$max_cnt)
													{
													?>
                                                    </div>
                                                    <?php
													}
													?>
                                                     </div>
                                          <div class="cate_mid_cont_bottom"></div>
                                         </div>
                                         <?php
		}
	}
	function advancedSearch()
	{
		global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
		$Captions_arr['SEARCH'] 	= getCaptions('SEARCH');
		$Captions_arr['CAT_DETAILS'] 	= getCaptions('CAT_DETAILS');
		$ajax_return_function = 'ajax_return_advsearchcontents';
		include "ajax/ajax.php";
		?>
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
		<?
		//print_r($category_datas[0]);
			$HTML_img = $HTML_alert = $HTML_treemenu='';
				$HTML_treemenu .=
				'<div class="treemenu">
					<a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a>
					<span>&raquo;</span>'.stripslash_normal($Captions_arr['SEARCH']['SEARCH_ADVANCED_SEARCH']).'
				</div>';
				echo $HTML_treemenu;
		?>
		 <div class="reg_shlf_outr">
           <div class="reg_shlf_inner">
            <div class="reg_shlf_inner_top"></div>
           <div class="reg_shlf_inner_cont">
           <div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?php echo stripslash_normal($Captions_arr['SEARCH']['SEARCH_ADVANCED_SEARCH']);?></span></div></div>
           <div class="reg_shlf_cont_div">
           <div class="reg_shlf_pdt_con">
		<table width="100%" border="0" cellspacing="1" cellpadding="1" class="reg_table">
        <tr>
			<td width="100%" align="left" valign="middle" colspan="3" height="20"></td>
		</tr>
		<?php
		if($Settings_arr['adv_showkeyword']==1)
		{
		?>
			<tr>
			<td width="27%" align="left" valign="middle" class="searchfont_header">Enter search keyword </td>
			<td width="73%" colspan="2" align="left" valign="middle" class="searchfont"><input name="quick_search" type="text"  id="adv_quick_search"  value="<?=$_REQUEST['quick_search']?>" size="15"/>
			<select name="cbo_keyword_look_option" id="cbo_keyword_look_option">
			<option value="exact_phrase">Exact Phrase</option>
			<option value="all_word">All of these words</option>
			<option value="any_word">Any of these words</option>
			</select>
			<input name="search_submit2" type="submit" class="buttongray_adv" id="search_submit" value="<?php echo stripslash_normal($Captions_arr['SEARCH']['SEARCH_GO'])?>" onclick="show_wait_button(this,'Please wait...')" /></td>
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
			<td width="3%" class="searchfont"><input name="rdo_suboption" type="radio" value="title" checked="checked" /></td>
			<td class="searchfont">Search title only </td>
			</tr>
			<tr>
			<td class="searchfont"><input name="rdo_suboption" type="radio" value="title_desc" /></td>
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
		<tr id="searchprod_tr<?php echo $prod_ids++?>"><td align="left"  class="searchfont_header_border"><?php echo stripslash_normal($Captions_arr['SEARCH']['ADSEARCH_VARCHARISTICS'])?></td>
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
		<option value="product_name" <?php echo ($prodsort_by=='product_name')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_PRODNAME'])?></option>
		<option value="price" <?php echo ($prodsort_by=='price')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_PRICE'])?></option>
		<option value="product_id" <?php echo ($prodsort_by=='product_id')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['SHOPDET_DATEADDED'])?></option>
		</select>
		<select name="search_sortorder" id="search_sortorder">
		<option value="ASC" <?php echo ($prodsort_order=='ASC')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_LOW2HIGH'])?></option>
		<option value="DESC" <?php echo ($prodsort_order=='DESC')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_HIGH2LOW'])?></option>
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
            <option value="ASC" <?php echo ($search_sortorder=='ASC')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_LOW2HIGH'])?></option>
            <option value="DESC" <?php echo ($search_sortorder=='DESC')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_HIGH2LOW'])?></option>
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
		<td colspan="3" align="center" class="searchfont"><input name="search_submit2" type="submit" class="buttongray_adv" id="search_submit" value="<?php echo stripslash_normal($Captions_arr['SEARCH']['SEARCH_GO'])?>" onclick="show_wait_button(this,'Please wait...')" /></td>
		</tr>
        
        <tr>
			<td width="100%" align="left" valign="middle" colspan="3" height="20"></td>
		</tr>
		</table>
		 </div>
           </div>
           </div>
           <div class="reg_shlf_inner_bottom"></div>
           </div>
           </div>
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
	$first_char = strtoupper(substr(stripslash_normal($row_cat['category_name']),0,1));
	//echo 'first'.$first_char.'<br/>';
	if(array_key_exists($first_char,$alpha_arr))
	{
	if(count($alpha_arr[$first_char])==0)
	{
	$block_cnt++;
	$block_arr[] = $first_char;
	}
	$alpha_arr[$first_char][] = stripslash_normal($row_cat['category_name']).'~~'.$row_cat['category_id'];
	$atleast_one++;
	}
	}
	}
	$start 			= 0; // start index
	$col_limit		= 3; // how many columns in each row
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
	<table align = "center" width="98%" border="0" cellspacing="0" cellpadding="3" class="noResult_class">
	<tr>
	<td class="search_noresult_td">
	<?php
	if(trim($_REQUEST['quick_search'])!='')
	{
	$caption =  stripslash_normal($Captions_arr['SEARCH']['SEARCH_NO_PRODUCTS_WITH_KEYWORD']);
	$caption = str_replace('[keyword]','<strong>'.$_REQUEST['quick_search'].'</strong>',$caption);
	echo $caption;
	}
	else
	{
	echo stripslash_normal($Captions_arr['SEARCH']['SEARCH_NO_PRODUCTS_WITH_NO_KEYWORD']);
	}

	?>
	
	</td>
	</tr>
	</table>
	<?php echo '<div class="search_advance"> <a href="'.url_link('advancedsearch.html',1).'" class="search_advance_lnk" title="'.$Captions_arr['COMMON']['ADVANCED_SEARCH'].'">'.$Captions_arr['COMMON']['ADVANCED_SEARCH'].'</a> </div>';?>
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
