<?php
	/*############################################################################
	# Script Name 	: categoryHtml.php
	# Description 	: Page which holds the display logic for category details
	# Coded by 		: Sny
	# Created on	: 07-Sep-2010
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class category_Html
	{
		// Defining function to show the selected category details
		function Show_CategoryDetails($row_cat)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			$Captions_arr['CAT_DETAILS'] 	= getCaptions('CAT_DETAILS');
			// ** Check whether category image module is there for current site
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
		   	$custom_id = get_session_var('ecom_login_customer');
			if($_REQUEST['type_cat']=='cate_root')
			{
			   if($_REQUEST['resultcat']=='added')
			   {
			   $alert = stripslash_normal($Captions_arr['CAT_DETAILS']['ADD_MSG']);
			   }
			   else if($_REQUEST['resultcat']=='removed')
			   {
			   $alert = stripslash_normal($Captions_arr['CAT_DETAILS']['REM_MSG']);
			   }
			} 
			$HTML_treemenu = $HTML_catdesc = $HTML_alert = $HTML_icons = '' ;
			if ($row_cat['category_turnoff_treemenu']==0)
			{
				$HTML_treemenu = '	<div class="tree_menu_con">
									<div class="tree_menu_top_list"></div>
									<div class="tree_menu_mid_list">
									<div class="tree_menu_content_list">
										<ul class="tree_menu">'.
											generate_tree_menu($_REQUEST['category_id'],-1,'','<li>',' | </li>').'
										</ul>
									</div>
									</div>
									<div class="tree_menu_bottom_list"></div>
									</div>';
	  		}
			if($alert)
			{
				$HTML_alert = '<div class="red_msg">
								- '.$alert.' -
								</div>';
			}
			if ($row_cat['category_paid_for_longdescription']=='Y' and trim($row_cat['category_paid_description'])!='' and trim($row_cat['category_paid_description'])!='<br>')
			{
				$cat_desc =   utf8_encode(stripslash_normal($row_cat['category_paid_description']));
			}
			elseif (trim($row_cat['category_shortdescription'])!='')
			{
				$cat_desc = nl2br(utf8_encode(stripslash_normal($row_cat['category_shortdescription'])));
			}
			if ($cat_desc!='')
			{
				$HTML_catdesc = '<div class="cate_content_mid">'.$cat_desc.'</div>';
			}
			if($row_cat['category_turnoff_mainimage']==0)
			{
				if ($img_support) // ** Support Category Image
				{
					if ($row_cat['category_showimageofproduct']==0) // Case to check for images directly assigned to category
					{
						if ($_REQUEST['catthumb_id'])	
							$showonly = $_REQUEST['catthumb_id'];
						else
							$showonly = 0;
						// Calling the function to get the type of image to shown for current 
						$pass_type = 'image_iconpath';
						// Calling the function to get the image to be shown
						$catimg_arr = get_imagelist('prodcat',$row_cat['category_id'],$pass_type,0,$showonly,1);
						if(count($catimg_arr))
						{
							$exclude_catid 	= $catimg_arr[0]['image_id']; // exclude id in case of multi images for category
							$HTML_image = '<div class="cat_main_image">'.show_image(url_root_image($catimg_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext','',1).'</div>';
						}
					}
					else // Case of check for the first available image of any of the products under this category
					{
						// Calling the function to get the id of products under current category with image assigned to it
						$cur_prodid = find_AnyProductWithImageUnderCategory($_REQUEST['category_id']);
						if ($cur_prodid)// case if any product with image assigned to it under current category exists
						{
							// Calling the function to get the type of image to shown for current 
							$pass_type = get_default_imagetype('category');	
							// Calling the function to get the image to be shown
							$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
							
							if(count($img_arr))
							{
								$HTML_image = '<div class="cat_main_image">'.show_image(url_root_image($img_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext','',1).'</div>';
							}
						}
					}	
				}
			}
			$url= url_category($_REQUEST['category_id'],$row_cat['category_name'],'');
			if($custom_id)
			{
				$sql_cat_det = "SELECT customer_customer_id FROM customer_fav_categories WHERE sites_site_id=$ecom_siteid AND categories_categories_id=".$_REQUEST['category_id'] ." AND customer_customer_id=$custom_id LIMIT 1";
				$ret_num_cat= $db->query($sql_cat_det);
			 	if($db->num_rows($ret_num_cat)==0) 
				{ 
					$HTML_icons = '<a href="javascript:if(confirm(\''.stripslash_javascript($Captions_arr['CAT_DETAILS']['CAT_FAVOURITE_CONFIRM']).'\')) { document.frm_catedetails.fpurpose.value=\'add_favourite\';document.frm_catedetails.submit();}" title="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_FAVOURITE']).'"><img src="'.url_site_image('favico.gif',1).'" alt="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_FAVOURITE']).'" border="0" /></a>';
				}
			   	else if($db->num_rows($ret_num_cat)>0)
			   	{
					$HTML_icons ='<a href="javascript:if(confirm(\''.stripslash_javascript($Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE_CONFIRM']).'\')){ document.frm_catedetails.fpurpose.value=\'rem_favourite\';document.frm_catedetails.submit(); }" title="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE']).'"><img src="'.url_site_image('rem_favico.gif',1).'" alt="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE']).'" border="0" /></a>';
			   	}
			}
			if ($row_cat['category_turnoff_pdf']==0)
			{
				$HTML_icons .= '<a  href="javascript:download_pdf()" title="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DOWNLOADPDF']).'"><img src="'.url_site_image('pdfico.gif',1).'" alt="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DOWNLOADPDF']).'" border="0" /></a>';
			}
			$sql_prods 		= "SELECT count(a.products_product_id) as cnt 
										FROM 
											product_category_map a,products b
										WHERE 
											a.product_categories_category_id = ".$_REQUEST['category_id']." 
											AND a.products_product_id=b.product_id 
											AND b.product_hide='N'";
			$ret_prods		= $db->query($sql_prods);
			$row_prods      = $db->fetch_array($ret_prods);
			$num_prods      = $row_prods['cnt'];
			if($num_prods>0)
			{	
				$HTML_icons .= '<a href="'.url_category_rss($row_cat['category_id'],$row_cat['category_name'],1).'" title="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_RSS']).'"><img src="'.url_site_image('rssico.gif',1).'" alt="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_RSS']).'" border="0" /></a>';
			}
			$cat_desc = stripslashes($row_cat['category_bottom_description']);
			if ($cat_desc!='')
			{
				$HTML_catbottomdesc = '<div class="cate_content_bottom">'.
										$cat_desc.'</div>';
			}
			$subcatshow 	= false;
			$prodshow		= false;
			$subcat_exists 	= false;
			if ($row_cat['category_subcatlisttype']=='Middle' or $row_cat['category_subcatlisttype']=='Both')
				$subcatshow = true;
			// Show the products in middle only or in both.
			if ($row_cat['product_displaywhere'] == 'middle' or $row_cat['product_displaywhere'] == 'both')
				$prodshow = true;
	  ?>
	   <script type="text/javascript" language="javascript">
	  function download_pdf() 
	  {
	  	document.getElementById('bw').innerHTML = '<iframe src="http://www.web2pdfconvert.com/convert.aspx?cURL=http://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>&outputmode=stream&allowactivex=yes&ref=form">';
		show_processing();
		setTimeout('hide_processing()', 20000);
	  }
	  </script>
		<form method="post" name="frm_catedetails" id="frm_catedetails" action="" class="frm_cls">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="caturl" value="<? echo $url?>" />
		<input type="hidden" name="type_cat" value="cate_root" />
		<input type='hidden' name='category_id' value="<?=$_REQUEST['category_id']?>"/>
		<?=$HTML_treemenu?>
	  	<div class="sub_cate_con">
		<div class="sub_cate_top"></div>
		<div class="sub_cate_mid">
		<div class="sub_cate_content">
		<div class="sub_cate_hdr"><?php echo utf8_encode(stripslashes($row_cat['category_name']))?></div>
		</div>
		</div>
		</div>
	  	<?php
			echo $HTML_alert;
			//echo $HTML_image;
			//echo $HTML_catdesc;
			if($subcatshow)
			{
				// ** Check for handling the case of caching
				$cache_exists 	= false;
				$cache_required	= false;
				$cache_type		= 'category';	
				if ($Settings_arr['enable_caching_in_site']==1)
				{
					$cache_required = true;
					if ($_REQUEST['category_id'])// Look for cache only if category id is there
					{
						$passid 		= $_REQUEST['category_id'];
						if (exists_Cache($cache_type,$passid))
						{
							$content_cache 	= getcontent_Cache($cache_type,$passid);
							if ($content_cache) // if cache exists show it
							{
								echo $content_cache;
								$cache_exists = true;
							}
						}
					}	
				}

				if ($cache_exists==false)
				{
					if($cache_required)// if caching is required start recording the output
					{
						ob_start();
					}
					$sql_subcat 	= "SELECT category_id,category_name,category_showimageofproduct,default_catgroup_id,subcategory_showimagetype,category_shortdescription   
								FROM
									product_categories 
								WHERE 
									parent_id = ".$_REQUEST['category_id']." 
									AND sites_site_id = $ecom_siteid 
									AND category_hide=0
								ORDER BY
									category_order		
									";
					$ret_subcat = $db->query($sql_subcat);
					if ($db->num_rows($ret_subcat))
					{
						$this->Show_Subcategories($ret_subcat,$url,$row_cat); // ** Calling the function to show the subcategories
						$subcat_exists = true;
					}
					if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
					{
						$content = ob_get_contents();
						ob_end_clean();
						save_Cache($cache_type,$passid,$content);
						echo $content;
					}
				}
			}
			?>
			</form>
			<?php
			if($prodshow)// Checks whether products to be shown in the middle area
			{
				$tot_cnt = $num_prods;
				if(trim($row_cat['product_orderfield'])!='')
				{
					$def_orderfield 	= $row_cat['product_orderfield'];
					$def_orderby		= $row_cat['product_orderby'];
				}
				else
				{
					$def_orderfield 	= $Settings_arr['product_orderfield'];
					$def_orderby		=  $Settings_arr['product_orderby'];
				}
				if ($tot_cnt>0)
				{ 
					$base_sort_by = $prodsort_by			= ($_REQUEST['catdet_sortby'])?$_REQUEST['catdet_sortby']:$def_orderfield;
					$prodperpage			= ($_REQUEST['catdet_prodperpage'])?$_REQUEST['catdet_prodperpage']:$Settings_arr['product_maxcntperpage'];// product per page
				   $prodperpage = 5;
					switch ($prodsort_by)
					{
						case 'custom': // case of order by customer fiekd
						$prodsort_by		= 'b.product_order';
						break;
						case 'product_name': // case of order by product name
						$prodsort_by		= 'a.product_name';
						break;
						case 'price': // case of order by price
						$prodsort_by		= 'calc_disc_price';
						break;
						case 'product_id': // case of order by price
						$prodsort_by		= 'a.product_id';
						break;
						default: // by default order by product name
						$prodsort_by		= 'a.product_name';
						break;
					};
					$prodsort_order	= ($_REQUEST['catdet_sortorder'])?$_REQUEST['catdet_sortorder']:$def_orderby;
					$start_var 		= prepare_paging($_REQUEST['catdet_pg'],$prodperpage,$tot_cnt);
					// Get the list of products to be shown in current shelf
					$sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
									a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
									a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
									product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,product_bonuspoints,
									a.product_stock_notification_required,a.product_alloworder_notinstock,product_variables_exists,product_variablesaddonprice_exists,
									a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
									a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
									a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
									a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
									a.product_freedelivery,
												IF(a.product_discount >0, 
												case a.product_discount_enteredasval
												WHEN 0 THEN (a.product_webprice-a.product_webprice*a.product_discount/100) 
												WHEN 1 THEN (IF((a.product_webprice-a.product_discount)>0,(a.product_webprice-a.product_discount),0)) 
												WHEN 2 THEN (a.product_discount) 
												END
												,a.product_webprice) calc_disc_price                  
								FROM 
									products a,product_category_map b 
								WHERE 
									b.product_categories_category_id = ".$_REQUEST['category_id']." 
									AND a.product_id = b.products_product_id 
									AND a.product_hide = 'N' 
								ORDER BY 
									$prodsort_by $prodsort_order 
								LIMIT 
									".$start_var['startrec'].", ".$prodperpage;
							
					$ret_prod = $db->query($sql_prod);
					//$this->Show_Products($ret_prod,$tot_cnt,$start_var,$row_cat['category_name'],$row_cat['default_catgroup_id'],$row_cat['product_displaytype'],$row_cat['product_showimage'],$row_cat['product_showtitle'],$row_cat['product_showshortdescription'],$row_cat['product_showprice'],$row_cat['sdf'],,$base_sort_by,$prodsort_order); // ** Calling function to show the products under current category
					$this->Show_Products($ret_prod,$tot_cnt,$start_var,$row_cat,$base_sort_by,$prodsort_order); // ** Calling function to show the products under current category
				}
				else
				{
				}		
			}
			global $shelf_for_inner;
			//include ("includes/base_files/combo_middle.php");
		    // Including the shelf to show the shelves assigned to current category.
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
			$ret_inline = $db->query($sql_inline); // to dispplay the shelf for the category page
			if ($db->num_rows($ret_inline))
			{
				while ($row_inline = $db->fetch_array($ret_inline))
				{
					//$modname 			= $row_inline['feature_modulename'];
					$body_dispcompid	= $row_inline['display_component_id'];
					$body_dispid		= $row_inline['display_id'];
					$body_title			= $row_inline['display_title'];
					$shelf_for_inner	= true;
					//include ("includes/base_files/shelf.php");
					$shelf_for_inner	= false;
				}
			}
			//echo $HTML_catbottomdesc;
		}
		// ** Function to show the subcategories
		function Show_Subcategories($ret_subcat,$url,$row_cat)
		{
			global $db,$inlineSiteComponents,$Captions_arr,$Settings_arr,$ecom_siteid;
			$heading = $HTML_subcat_header = $HTML_alert = '';
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
			$custom_id = get_session_var('ecom_login_customer');
			$subcategory_showimagetype = $row_cat['subcategory_showimagetype'];
			if ($db->num_rows($ret_subcat)==1)
			{
				if($Captions_arr['CAT_DETAILS']['CATDET_SUBCAT']!='')
				{
					$heading =  stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_SUBCAT']);
				}
			}
			else
			{
				if($Captions_arr['CAT_DETAILS']['CATDET_SUBCATS']!='')
				{
					$heading =  stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_SUBCATS']);
				}
			}
			if($heading!='')
			{
				$HTML_subcat_header = '<div class="subcat_header">'.$heading.'</div>';
			}	
		    if($_REQUEST['type_cat']=='sub_cat')
			{
			   if($_REQUEST['resultcat']=='added')
			   {
			   $alert = stripslash_normal($Captions_arr['CAT_DETAILS']['ADD_MSG']);
			   }
			   else if($_REQUEST['resultcat']=='removed')
			   {
			   $alert = stripslash_normal($Captions_arr['CAT_DETAILS']['REM_MSG']);
			   }
			 } 
			if($alert)
			{
				echo $HTML_alert = '<div class="red_msg">- '.$alert.' -</div>';
			}
					echo 
								'
								<table width="100%" border="0" cellspacing="0" cellpadding="0" class="cat_table">

								';
						$cur_col = 0;
						while ($row_subcat = $db->fetch_array($ret_subcat))
						{
							$HTML_subcatname = $HTML_image = $HTML_short_desc = '';
							if($cur_col ==0)
								echo '<div class="horizontal_container">';
							$cur_col++;
							if($row_cat['category_showname']==1)
							{
						  		$HTML_subcatname = '<a class="cate_link" href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" title="'.stripslash_normal($row_subcat['category_name']).'">'.stripslash_normal($row_subcat['category_name']).'</a>';
							}
							if($row_cat['category_showimage']==1)
							{
								 $HTML_image = '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" class="subcategoreynamelink" title="'.stripslash_normal($row_subcat['category_name']).'">';
									$pass_type = 'image_thumbcategorypath';
									if ($row_subcat['category_showimageofproduct']==0) // Case to check for images directly assigned to category
									{
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prodcat',$row_subcat['category_id'],$pass_type,0,0,1);
										if(count($img_arr))
										{
											$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_subcat['category_name'],'','',1);
											$show_noimage = false;
										}
										else
											$show_noimage = true;
									}
									else // Case of check for the first available image of any of the products under this category
									{
										// Calling the function to get the id of products under current category with image assigned to it
										$cur_prodid = find_AnyProductWithImageUnderCategory($row_subcat['category_id']);
										if ($cur_prodid)// case if any product with image assigned to it under current category exists
										{
											// Calling the function to get the image to be shown
											$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
											
											if(count($img_arr))
											{
												$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_cat['category_name'],'','',1);
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
											$HTML_image .= show_image($no_img,$row_subcat['category_name'],$row_subcat['category_name'],'','',1);
										}	
									}
								$HTML_image .= '</a>';
							}
							if($row_cat['category_showshortdesc']==1)
							{
								$HTML_short_desc = stripslash_normal($row_subcat['category_shortdescription']);
							}
					?>
							
							<tr>
						<td class="cat_table_td_a"><? echo utf8_encode($HTML_subcatname)?></td>
							</tr>
							<?
							
						}
						?>
						</table>
						<?php					
					
						
		}
		// ** Function to list the products
		function Show_Products($ret_prod,$tot_cnt,$start_var,$cat_det,$def_orderfield,$def_orderby)
		{
			global $db,$Settings_arr,$Captions_arr,$inlineSiteComponents,$compare_button_displayed;
			$showqty		= $Settings_arr['show_qty_box'];// show the qty box
		    $prodsort_by	= ($_REQUEST['catdet_sortby'])?$_REQUEST['catdet_sortby']:$def_orderfield;
			$prodperpage	= ($_REQUEST['catdet_prodperpage'])?$_REQUEST['catdet_prodperpage']:$Settings_arr['product_maxcntperpage'];// product per page
			$prodsort_order	= ($_REQUEST['catdet_sortorder'])?$_REQUEST['catdet_sortorder']:$def_orderby;
			$HTML_paging	= '';
			$prodperpage = 5;
			$prodsort_order   = 'ASC';
			$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';


            ?>
                 <table width="100%" border="0" class="htable" cellspacing="0" cellpadding="0">
                           
            <?php
			if ($tot_cnt>0)
			{
				$pg_variable				= 'catdet_pg';
				$start_var 					= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
				$path 						= '';
				$pageclass_arr['container'] = 'pagenavcontainer';
				$pageclass_arr['navvul']	= 'pagenavul';
				$pageclass_arr['current']	= 'pagenav_current';
				
				$query_string 	= "&amp;catdet_sortby=".$_REQUEST['catdet_sortby'].'&amp;catdet_sortorder='.$_REQUEST['catdet_sortorder'].'&amp;catdet_prodperpage='.$_REQUEST['catdet_prodperpage'];
				$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
				
				if($start_var['pages']>1)
				{
					$HTML_paging	= '	  <tr>
                                <td class="pagingtd" colspan="2">
                                 <div class="page_nav_content">
                                <ul>
												
												'.$paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'].'
												 </ul>
                                </div>
                                </td>
                            </tr>';
				}					
									
			}
			if($paging['total_cnt'])
			{
				$HTML_totcnt = '<tr>
				<td class="ttcnt" colspan="2"><div class="subcat_nav_pdt_no"><span>'.$paging['total_cnt'].'</span></div></td>
				</tr>';				
			}
			$HTML_topcontent = 	$HTML_totcnt.'
								 <tr>
                                <td align="right" class="sorttd_a" >';
								$selval_arr = array (
														'custom'		=> stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DEFAULT']),
														'product_name'	=> stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_PRODNAME']),
														'price'			=> stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_PRICE']),
														'product_id'	=> stripslash_normal($Captions_arr['CAT_DETAILS']['SHOPDET_DATEADDED']));
			
			$HTML_topcontent .=	generateselectbox('catdet_sortbytop',$selval_arr,$prodsort_by,'','',0,'',false,'catdet_sortbytop');
								$selord_arr = array (
														'ASC'	=> stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_LOW2HIGH']),
														'DESC'	=> stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_HIGH2LOW']) 
													);
         $HTML_topcontent .= '
			<input type="button" name="submit_Page" value="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_GO']).'" class="nav_button" onclick="handle_categorydetailsdropdownval_sel(\''.url_category($_REQUEST['category_id'],stripslash_javascript($cat_det['category_name']),4,$_REQUEST['catgroup_id'],1).'\',\'catdet_sortbytop\',\'catdet_prodperpagetop\')" />
								';
			//$HTML_topcontent .=	generateselectbox('catdet_sortordertop',$selord_arr,$prodsort_order,'','',0,'',false,'catdet_sortordertop');
			$HTML_topcontent .=	'								
								</td></tr>';
			if(!$_REQUEST['catdet_prodperpage'])
			{
				$catdet_prodperpage = $Settings_arr['product_maxcntperpage'];
			}
			else
			{
				$catdet_prodperpage = $_REQUEST['catdet_prodperpage'];
			}
			$perpage_arr = array();
			for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
				$perpage_arr[$ii] = $ii;
			//$HTML_topcontent .=	generateselectbox('catdet_prodperpagetop',$perpage_arr,$catdet_prodperpage,'','',0,'',false,'catdet_prodperpagetop');
		
		
		
			echo $HTML_topcontent;echo $HTML_paging;	
			// ** Showing the products listing either 1 in a row or 3 in a row
			$pass_type = get_default_imagetype('prodcat');
			$comp_active = isProductCompareEnabled();
				?>
				
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="shlf_table_1row">
				<?php	
						if($cur_title)
						{
				?>	<tr>
						<td class="curvea_top_z"><div class="curvea_div"><?php echo $cur_title; ?></div></td>
					</tr>
				<?php	}
						$rwCnt	=	1;
						while($row_prod = $db->fetch_array($ret_prod))
						{
							if($rwCnt % 2 == 0)
							{	$trCls	=	"shlf_table_1row_td_b";	}
							else
							{	$trCls	=	"shlf_table_1row_td_a";	}
							$rwCnt++;
				?>
					<tr>
						<td class="<?php echo $trCls;?>">
							<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shlf_1row_link">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
				<?php		//if ($shelfData['shelf_showimage']==1)
							//{
				?>				<td class="shlf_td_1row_img">
								<div class="shlf_table_1row_img">
										
				<?php									
										$fld_mode	=	IMG_MODE;
										$fld_size	=	IMG_SIZE;
										//$fld_name	=	'image_extralargepath';
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prod',$row_prod['product_id'],$fld_mode,0,0,1);
										if(count($img_arr))
										{
											$imgPath	=	url_root_image($img_arr[0][$fld_mode],1);
											$imgProperty=	image_property($imgPath);
											//echo "<pre>";print_r($imgProperty);
											if($imgProperty['width'] > 0 && $imgProperty['height'] > 0 )
											{
												$newWidth	=	ceil($imgProperty['width']/$fld_size);//echo $newWidth;echo "<br>";
												$newHeight	=	ceil($imgProperty['height']/$fld_size);//echo $newHeight;echo "<br>";
												show_image_mobile($imgPath,$row_prod['product_name'],$row_prod['product_name'],'','',0,$newWidth,$newHeight);
											}
											else
											{
												show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
											}
										}
										else
										{
											// calling the function to get the default image
											$no_img = get_noimage('prod',$fld_mode); 
											if ($no_img)
											{
												$imgProperty	=	image_property($no_img);
						
												if($imgProperty['width'] > 0 && $imgProperty['height'] > 0 )
												{
													$newWidth	=	$imgProperty['width']/$fld_size;
													$newHeight	=	$imgProperty['height']/$fld_size;
													show_image_mobile($no_img,$row_prod['product_name'],$row_prod['product_name'],'','',0,$newWidth,$newHeight);
												}
												else
												{
													show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
												}
											}
										}
				?>						
									</div>
				<?php			//if($row_prod['product_newicon_show']==1)
								//{
				?>					<!--<div class="shlf_table_1row_offer"><img src="<?php url_site_image('new.png')?>" /></div>-->
				<?php 			//}
								//if($row_prod['product_saleicon_show']==1)
								//{
				?>					<!--<div class="shlf_table_1row_offer"><img src="<?php url_site_image('sale.png')?>" /></div>-->
				<?php 			//}
				?>				</td>
				<?php
							//}
				?>				
								<td class="shlf_td_1row_desc">
								<div class="shlf_table_1row_otr"> 
				<?php			//if($shelfData['shelf_showtitle']==1)
								//{
				?>					<div class="shlf_table_1row_name">
										<a><?php echo stripslashes($row_prod['product_name'])?></a>
									</div> 
				<?php			//}
								//if ($shelfData['shelf_showdescription']==1)
								//{
				?>
									<div class="shlf_table_1row_des">
										<?php echo stripslashes($row_prod['product_shortdesc'])?><?php //show_moreinfo($row_prod,'list_more')?>
									</div>
				<?php			//}
								//if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
								//{
								
									$price_class_arr['ul_class'] 		= 'shelfBul_three_column';
									$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
									$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
									$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
									$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
									echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
								
				?>				</div>
								</td>
							</tr>
							</table></a>
						</td>
					</tr>
				<?php		}
				//echo $HTML_topcontent;
				echo $HTML_paging;
				?>
					</table>
			<?php
							

		}
		function Show_HomeCatgeory($title,$ret_category) 
		{
		  
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$inlineSiteComponents;
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
			//$row_category = $db->fetch_array($ret_category);
	
		?>
			
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="cat_table">
				
				
				<?php	
					$max_col = 3;
					$cur_col = 0;
					while ($row_category = $db->fetch_array($ret_category))
					{
						 if($cur_col%2 == 0)
						 {	
							$class= "cat_table_td_a";
						 }
						 else
						 {
						   $class= "cat_table_td_b";
						 }
				?>
				        <tr>
							<td width="33%" align="" valign="" class="<?php echo $class ?>" >							  
								<a href="<?php url_category($row_category['category_id'],$row_category['category_name'],-1)?>" class="cate_link" title="<?php echo stripslashes($row_category['category_name'])?>"><?php echo stripslashes($row_category['category_name'])?></a>
							</td>
						</tr>
				<?php						
					$cur_col++;
					}
				?>	
				</tr>
			  </table>				
	<?PHP	
		}
	};	
?>