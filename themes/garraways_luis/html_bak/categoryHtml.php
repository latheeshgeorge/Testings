<?php
	/*############################################################################
	# Script Name 	: categoryHtml.php
	# Description 	: Page which holds the display logic for category details
	# Coded by 		: Joby
	# Created on	: 30-May-2011
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class category_Html
	{
		// Defining function to show the selected category details
		function Show_CategoryDetails($row_cat)
		{
			global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr,$components,$display_componentid;
			$Captions_arr['CAT_DETAILS'] 	= getCaptions('CAT_DETAILS');
			
			
			global $horizontal_max_cols;
			$horizontal_max_cols	= ($row_cat['grid_column_cnt'])?$row_cat['grid_column_cnt']:12;
			
			// Call function to decide whether grid display is to be used or not.
			$check_arr = is_grid_display_enabled($_REQUEST['category_id']);
			/*if($check_arr['enabled']) // case if grid display is enabled
			{
				// Calling the function to initiate the grid display
				$this->show_grid_display($_REQUEST['category_id'],$check_arr);
			}
			else // case of showing normal display for the category
			{
				*/ 
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
													generate_tree_menu($_REQUEST['category_id'],-1,'','<li>','</li>').'
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
						$cat_desc =   stripslash_normal($row_cat['category_paid_description']);
					}
					elseif (trim($row_cat['category_shortdescription'])!='')
					{
						$cat_desc = nl2br(stripslash_normal($row_cat['category_shortdescription']));
					}
					if ($cat_desc!='')
					{
						$sr_array  = array('rgb(0, 0, 0)','#000000');
						$rep_array = array('rgb(255,255,255)','#ffffff'); 
						$cat_desc = str_replace($sr_array,$rep_array,$cat_desc);
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
								$pass_type = get_default_imagetype('category');	
								// Calling the function to get the image to be shown
								$catimg_arr = get_imagelist('prodcat',$row_cat['category_id'],$pass_type,0,$showonly,1);

								if(count($catimg_arr))
								{
									$exclude_catid 	= $catimg_arr[0]['image_id']; // exclude id in case of multi images for category
									$HTML_image = '<div class="cat_main_image"><a href="'.url_root_image($catimg_arr[0]['image_extralargepath'],1).'" title="'.$row_prod['product_name'].'" rel=\'lightbox[gallery]\' >'.show_image(url_root_image($catimg_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext','',1).'</a></div>';
								    $HTML_image_zoom = '<div class="det_sub_imge_hdr"><a href="'.url_root_image($catimg_arr[0]['image_extralargepath'],1).'" title="'.$row_prod['product_name'].'" rel=\'lightbox[gallery]\' ><img src="'.url_site_image('zoom.gif',1).'" border="0"></a></div>';
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
							$HTML_icons = '<a href="javascript:if(confirm(\''.stripslash_javascript($Captions_arr['CAT_DETAILS']['CAT_FAVOURITE_CONFIRM']).'\')) { document.frm_catedetails.fpurpose.value=\'add_favourite\';document.frm_catedetails.submit();}" title="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_FAVOURITE']).'"><img src="'.url_site_image('fav.gif',1).'" alt="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_FAVOURITE']).'" border="0" /></a>';
						}
						else if($db->num_rows($ret_num_cat)>0)
						{
							$HTML_icons ='<a href="javascript:if(confirm(\''.stripslash_javascript($Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE_CONFIRM']).'\')){ document.frm_catedetails.fpurpose.value=\'rem_favourite\';document.frm_catedetails.submit(); }" title="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE']).'"><img src="'.url_site_image('rem_fav.gif',1).'" alt="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE']).'" border="0" /></a>';
						}
					}
					if ($row_cat['category_turnoff_pdf']==0)
					{
						$HTML_icons .= '<a  href="javascript:download_pdf_stream(\''.$_SERVER['HTTP_HOST'].'\',\''.$_SERVER['REQUEST_URI'].'\')" title="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DOWNLOADPDF']).'"><img src="'.url_site_image('pdf.gif',1).'" alt="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DOWNLOADPDF']).'" border="0" /></a>';
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
						//$HTML_icons .= '<a href="'.url_category_rss($row_cat['category_id'],$row_cat['category_name'],1).'" title="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_RSS']).'"><img src="'.url_site_image('rss.gif',1).'" alt="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_RSS']).'" border="0" /></a>';
					}
					$cat_desc = stripslashes($row_cat['category_bottom_description']);
					if ($cat_desc!='')
					{
						$sr_array  = array('rgb(0, 0, 0)','#000000');
						$rep_array = array('rgb(255,255,255)','#ffffff'); 
						$cat_desc = str_replace($sr_array,$rep_array,$cat_desc);
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
					<div class="middle_top"></div>
					<div class="middle_bg">				
						<div class="middlecontent">						
							<?=$HTML_treemenu?>
					
				   
					<div class="det_cont_con">
			  <form method="post" name="frm_catedetails" id="frm_catedetails" action="" class="frm_cls">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="caturl" value="<? echo $url?>" />
		<input type="hidden" name="type_cat" value="cate_root" />
		<input type='hidden' name='category_id' value="<?=$_REQUEST['category_id']?>"/>
		<?php
		if($HTML_image!='')
		{
			$clsa = 'det_imge_otr';
			$clsb = 'det_det_otr';

	    }
	    else
	    {
			$clsa = 'det_imge_otr_no';
			$clsb = 'det_det_otr_no';

		}
		?>
		
								<div class="<?php echo $clsa?>">

		<?php
		if($HTML_image!='')
		{
		?>
							
							<div id="det_imge">
												 <div id="mainimage_holder">
													 <?php
											echo $HTML_alert;
		
											echo $HTML_image;
											?>
											 </div>
							 
							</div>						
							<div class="det_sub_imge">
								<?php 
							  echo $HTML_image_zoom;
								?>
									<div class="det_sub_imges">
										<div id="moreimage_holder">
										<?php
									   // $return_arr = $this->Show_Image_Normal($row_prod,true);
										// Showing additional images
										$this->show_more_images($row_cat,'',$exclude_catid);
										?>
										</div>
									</div>
							</div>
								<?php
		 }
							?>
							
							<div class="outer_for_all" id="outer_for_all">
							  <div class="outer_for_all_mid">
							 
							<div class="deat_pdt_bookmark_otr">
												</div>
						
							<div class="det_link_otr">
							<div class="det_link_top"></div>
								<div class="det_link_bottom">
								<div class="deat_pdt_iconsa"></div>
								<div class="deat_pdt_iconsb"><?php echo $HTML_icons ?></div>
								<div class="deat_pdt_iconsc"></div>
								<div class="deat_pdt_iconsd"></div> 
								</div>
							</div>
							<div class="det_comp_otr">
							<div class="det_comp_top"></div>
								<div class="det_comp_mid">
								 
								<!--  <img src="">-->
								</div>
							<div class="det_comp_bottom"></div>
							</div>
							</div>
							</div>
						</div>
					<div class="<?php echo $clsb?>">
					
					<div class="det_name_con">
						<div class="det_name_otr">
						   <div class="det_comp_stock">                   
						   <div class="det_name_top"><h1><?php echo stripslashes($row_cat['category_name'])?></h1></div>
							<div class="det_name_bottom"></div>
							</div>
						   
						</div>
						<?php 
									echo $HTML_catdesc;
		 ?>
							<div class="det_price_bottom"></div>
						</div>
					</div>
					
					
						 <?php
					if($subcatshow)
					{
		
							
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
					}
					?>
			</form>
			<?php
					if($prodshow)// Checks whether products to be shown in the middle area
					{
						if($check_arr['enabled']==false) // case if grid display is enabled
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
						
							switch ($prodsort_by)
							{
								case 'custom': // case of order by customer fiekd
								$prodsort_by		= 'b.product_order';
								break;
								case 'product_name': // case of order by product name
								$prodsort_by		= 'a.product_name';
								break;
								case 'price': // case of order by price
								$prodsort_by		= 'a.product_webprice';
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
											a.product_freedelivery                 
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
								
					}
					global $shelf_for_inner;
					include ("includes/base_files/combo_middle.php");
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
							include ("includes/base_files/shelf.php");
							$shelf_for_inner	= false;
						}
					}
					echo $HTML_catbottomdesc;	
					?>
									  
								
								
										
					</div>
					 
					</div>
					
					<div class="rightcontent">
								<?php
						$position = 'right';
							include("Components.php");
						//include(ORG_DOCROOT."/themes/$ecom_themename/modules/mod_shoppingcart.php");
						//include(ORG_DOCROOT."/themes/$ecom_themename/modules/mod_adverts.php");
						//include(ORG_DOCROOT."/themes/$ecom_themename/modules/mod_compare_products.php");
		
					?>
					  </div>
					   <?php
					 if($prodshow)// Checks whether products to be shown in the middle area
					 { 
					  if($check_arr['enabled']==true) // case if grid display is enabled
						{
													// ** Check for handling the case of caching
						
							// Calling the function to initiate the grid display
							$this->show_grid_display($_REQUEST['category_id'],$check_arr);
							
					   }	
					 }	
					  ?>
				</div>
							<div class="middle_bottom"></div>        

			 <?php	
			 //}
			

		}
function show_more_images($row_cat,$exclude_tabid,$exclude_prodid)
{ 
	global $db,$ecom_hostname,$ecom_themename;
	$show_normalimage = false;
	$prodimg_arr		= array();
									$pass_type = 'image_iconpath';
	
		$show_normalimage = true;
	if ($show_normalimage==true) // the following is to be done only coming for normal image display
	{ 

			if ($exclude_prodid)
					$catimg_arr = get_imagelist('prodcat',$row_cat['category_id'],$pass_type,$exclude_prodid,0);
	} 
	if(count($catimg_arr)>0)// Case if more than one image assigned to current product
	{
				
	?>	
		<div class="deat_pdt_thumbimg">
		<div class="det_link_thumbimg_con">
		<div class="det_thumbimg_nav"><a href="#null" onmouseover="scrollDivRight('containerB')" onmouseout="stopMe()"><img src="<?php url_site_image('shlf-arw-l.png')?>"></a></div>
		<div id="containerB" class="det_thumbimg_inner">

			<div id="scroller_thumb">
			<?php
			foreach ($catimg_arr as $k=>$v)
			{ 
				$title = ($v['image_title'])?stripslash_normal($v['image_title']):$row_cat['product_name'];
			?>
				<div class="det_thumbimg_pdt">
					<div class="det_thumbimg_image">
					<a href="<?php url_root_image($v['image_extralargepath'])?>" rel='lightbox[gallery]' title="<?=$title?>">
					<?php
						 show_image(url_root_image($v['image_thumbpath'],1),$title,$title,'preview');
					?>
					</a>
					</div>
				</div>
			<?php
			}
			?>	
			
            </div>
		</div>
		<div class="det_thumbimg_nav"> <a href="#null" onmouseover="scrollDivLeft('containerB',<?php echo (count($catimg_arr)*150)?>)" onmouseout="stopMe()"><img src="<?php url_site_image('shlf-arw-r.png')?>"></a></div>
		</div>
		</div>	
	<?php
	}
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
				switch ($row_cat['category_subcatlistmethod'])
				{
					case 'normal': // normal 5 in a row
						echo 
								'
								<div class="sub_cate_mid_cont">
								<div class="sub_cate_mid_cont_top">
								<ul class="sub_cate_mid_ul">
								';
						$cnt = 1;
						while ($row_subcat = $db->fetch_array($ret_subcat))
						{
							$HTML_subcatname = $HTML_image = $HTML_short_desc = '';
							if($cur_col ==0)
															
							if($row_cat['category_showname']==1)
							{
								$class = ( $cnt % 2 ? 'sub_cate_mid_ul_a' : 'sub_cate_mid_ul_b' );

						  		$HTML_subcatname= '<span class="product_namespA">'.stripslash_normal($row_subcat['category_name']).'</span>';
							}
							if($row_cat['category_showimage']==1)
							{
								 $HTML_image = '<span class="product_imagespA">';
									//$pass_type = 'image_thumbcategorypath';
									$pass_type = 'image_thumbpath';
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
									{ 	 $no_img = url_site_image('no_small_image.png',1);

										//echo $pass_type;
										// calling the function to get the default no image 
										//$no_img = get_noimage('prodcat',$pass_type); 
										if ($no_img)
										{
											$HTML_image .= show_image($no_img,$row_subcat['category_name'],$row_subcat['category_name'],'','',1);
										}	
									}
								$HTML_image .= '</span>';
							}
							
					?>
							<li><a href="<?php echo url_category($row_subcat['category_id'],$row_subcat['category_name'],1)?>" title="<?php stripslash_normal($row_subcat['category_name'])?>" class="<?php echo $class?>"><?php echo $HTML_image ?><?=$HTML_subcatname?></a></li>

							<?
							$cnt++;
						}
						
						echo '
								</ul>
								</div>
								</div>
						';
					break;
					case '2row': // 2 in a row
						echo 
								'
								<div class="subcat_content">
								<div class="subcat_2row_outr">
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
						  		$HTML_subcatname = '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" title="'.stripslash_normal($row_subcat['category_name']).'">'.stripslash_normal($row_subcat['category_name']).'</a>';
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
							
							<div class="subcat_2row_pdt_outr">
							<div class="subcat_2row_pdt_name"><?=$HTML_subcatname?></div>
							<div class="subcat_2row_pdt_btm">
							<div class="subcat_2row_image"><?=$HTML_image?></div>
							<div class="subcat_2row_des"><?=$HTML_short_desc?></div>
							</div>
							</div>
							<?
							if($cur_col>=2)
							{
								echo '</div>';
								$cur_col = 0;
							}	
						}
						if($cur_col>0 and $cur_col<4)
							echo '</div>';	
						echo '
								</div>
								</div>
						';
					break;
					case '4row': // 4 in a row
						echo 
								'
								<div class="subcat_content">
								<div class="subcat_3row_outr">
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
						  		$HTML_subcatname = '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" title="'.stripslash_normal($row_subcat['category_name']).'">'.stripslash_normal($row_subcat['category_name']).'</a>';
							}
							if($row_cat['category_showimage']==1)
							{
								 $HTML_image = '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" class="subcategoreynamelink" title="'.stripslash_normal($row_subcat['category_name']).'">';
									$pass_type = 'image_iconpath';
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
							
							<div class="subcat_3row_pdt_outr">
							<div class="subcat_3row_pdt_name"><?=$HTML_subcatname?></div>
							<div class="subcat_3row_pdt_btm">
							<div class="subcat_3row_image"><?=$HTML_image?></div>
							<div class="subcat_3row_des"><?=$HTML_short_desc?></div>
							</div>
							</div>
							<?
							if($cur_col>=4)
							{
								echo '</div>';
								$cur_col = 0;
							}	
						}
						if($cur_col>0 and $cur_col<4)
							echo '</div>';	
						echo '
								</div>
								</div>
						';
					break;
				};		
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

			if ($tot_cnt>0)
			{
				$pg_variable				= 'catdet_pg';
				$start_var 					= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
				$path 						= '';
				$pageclass_arr['container'] = 'pagenavcontainer';
				$pageclass_arr['navvul']	= 'pagenavul';
				$pageclass_arr['current']	= 'pagenav_current';
				
				$query_string 	= "&amp;catdet_sortby=".$_REQUEST['catdet_sortby'].'&amp;catdet_sortorder='.$_REQUEST['catdet_sortorder'].'&amp;catdet_prodperpage='.$_REQUEST['catdet_prodperpage'];
				$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Product(s)',$pageclass_arr);
				
				if($start_var['pages']>1)
				{ 
					$HTML_paging	= '<div class="page_nav_content">
												<ul>
												'.$paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'].'
												</ul>
										</div>';
				}					
									
			}
			if($paging['total_cnt'])
				$HTML_totcnt = '<div class="subcat_nav_pdt_no"><div class="sub_cat_hdr">'.$paging['total_cnt'].'</div></div>';
			$HTML_topcontent = 	'<div class="subcat_nav_content" >
								<div class="subcat_nav_top"></div>
								<div class="subcat_nav_bottom">
								'.$HTML_totcnt.'
								<div class=" page_nav_cont">
								<div class="navtxt">'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_SORTBY']).'</div>
								<div class="navselect">';
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
			$HTML_topcontent .=	generateselectbox('catdet_sortordertop',$selord_arr,$prodsort_order,'','',0,'',false,'catdet_sortordertop');
			$HTML_topcontent .=	'								
								</div>
								</div>
								<div class=" page_nav_contA">
								<div  class="navtxt">'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_ITEMSPERPAGE']).'</div>
								<div class="navselect">';
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
			$HTML_topcontent .=	generateselectbox('catdet_prodperpagetop',$perpage_arr,$catdet_prodperpage,'','',0,'',false,'catdet_prodperpagetop');
			$HTML_topcontent .= '
								</div>
								</div>
								<div class=" page_nav_contB">
								<input type="button" name="submit_Page" value="'.stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_GO']).'" class="nav_button" onclick="handle_categorydetailsdropdownval_sel(\''.url_category($_REQUEST['category_id'],stripslash_javascript($cat_det['category_name']),4,$_REQUEST['catgroup_id'],1).'\',\'catdet_sortbytop\',\'catdet_sortordertop\',\'catdet_prodperpagetop\')" />
								</div>
								</div>';
			//$HTML_topcontent .= $HTML_paging;
			$HTML_topcontent .= '</div>';
			echo $HTML_topcontent;
			// ** Showing the products listing either 1 in a row or 3 in a row
			//$pass_type = get_default_imagetype('prodcat');
			$comp_active = isProductCompareEnabled();
			switch($cat_det['product_displaytype'])
			{
				case '1row':
		?>		<div class="detailwrap">
				<?php										
                    while($row_prod = $db->fetch_array($ret_prod))
                    {
						$HTML_new = $HTML_sale = '';
                ?>	<div class="detail_inside">
                        <?php
                        if($cat_det['product_showimage']==1) // whether image is to be displayed
                        {
                        ?>
                        <div class="detail_img">
                        <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>" class="product_image">
                        <?php
                            $pass_type = 'image_thumbpath';
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
                        
                        <div class="detail_content">
                            <div class="product_flags">
                                <?php
								//echo "<pre>";print_r($cat_det);die();
                                if($row_prod['product_saleicon_show']==1)
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
                                    $HTML_new = '<div class="pdt_list_new"></div>';
                                }
                                echo $HTML_new ;
                                echo $HTML_sale;
                                ?>
                                <?php
                                if($cat_det['product_showtitle']==1)// whether title is to be displayed
                                {
                                ?>
                                <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"  title="<?php echo stripslash_normal($row_prod['product_name'])?>" class="product_descr"><h3><span class="grey"><?php echo stripslash_normal($row_prod['product_name'])?></span></h3></a>
                                <?php
                                }
                                if($cat_det['product_showshortdescription']==1)// whether title is to be displayed
                                {
                                    ?>
									
									<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>" class="cupid-green_desca">
									<?php
									echo '<p class="product_desc">'.stripslash_normal($row_prod['product_shortdesc']).'</p>';
									?>
									</a>
									<?php
									
                                }
                                ?>
                                
                            </div>
                        </div>
                        
                        <div class="right_block bordercolor">
                        <?php
                        if($cat_det['product_showprice']==1) // whether price is to be displayed
                        {
                            $price_arr =  show_Price($row_prod,array(),'compshelf',false,4);
                            if($price_arr['discounted_price'])
                            {
                                echo '<span class="discount">Reduced price!</span>';
                                echo '<span class="price">'.$price_arr['discounted_price'].'</span>';
                            }
                            else
                                echo '<span class="price">'.$price_arr['base_price'].'</span>';
                        }
                        ?>
                        
                        <?php										   
                           if(isProductCompareEnabled())
                           {
                                    if(in_array($row_prod['product_id'],$_SESSION['compare_products']))
                                    {
                                     $select = "checked";
                                    }
                                    else
                                    {
                                        $select = "";
                                    }
                                    $compare_button_displayed = true;
                        ?><span class="add_compare"><form name="add_to_compare" id="add_to_compare" action="" method="post" ><input type="hidden" name="submit_Compare_pdts" id="submit_Compare_pdts" value="1"><input type="checkbox" name="compare_products_<?php echo $row_prod['product_id'];?>" value="ADD TO COMPARE" class="buttonred_large" onclick="addtoCompare(<?php echo $row_prod['product_id']?>)" id="compare_products_<?php echo $row_prod['product_id'];?>" <?php echo $select ?> /></form> Select To Compare</span>
                        <?php
                            }
                        ?>
						<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>" class="cupid-green"><?php echo stripslash_normal($Captions_arr['COMMON']['MORE_INFO'])?></a>
                        <?php
                                $frm_name = uniqid('shelf_');
                        ?>
                        <form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
                            <input type="hidden" name="fpurpose" value="" />
                            <input type="hidden" name="fproduct_id" value="" />
                            <input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
                            <input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />		
                            
                            <input type="hidden" name="qty" id="qty" value="1" />														
                        <?php
                            $class_arr                      = array();
                            $class_arr['ADD_TO_CART']       = 'cupid-green';
                            $class_arr['PREORDER']          = 'cupid-green';
                            $class_arr['ENQUIRE']           = 'cupid-green';
                            $class_arr['QTY_DIV']           = 'normal_shlfB_pdt_input';
                            $class_arr['QTY']               = ' ';							
                            /* Code for ajax setting starts here */
                            $class_arr['BTN_CLS']           = 'cupid-green';												
                            //show_addtocart($row_prod,$class_arr,$frm_name,false,'','','normal_shlfB_pdt_buy_btn');
                            show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr,false);
                            /* Code for ajax setting ends here */                                            
                        ?>
                        </form>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    <?php
					}
					?>
                </div>
        <?php
					break;
				case '3row': // case of two in a row for normal
				default;
		?>
				
		<?php	$max_col = 3;
				$cur_col = 0;
				$prodcur_arr = array();
		?>
				<div class="product_list_outer"> 
					<div class="pdt_list_outer">
		<?php	while($row_prod = $db->fetch_array($ret_prod))
				{
					$prodcur_arr[] = $row_prod;
					$HTML_title = $HTML_image = $HTML_desc = '';
					$HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
					$HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
					$HTML_new = $HTML_sale = '';
					
					if($cat_det['product_showtitle']==1)// whether title is to be displayed
						$HTML_title = '<div class="pdt_list_pdt_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
				
					if ($cat_det['product_showimage']==1)// Check whether description is to be displayed
					{
						$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
						// Calling the function to get the image to be shown
						$pass_type = 'image_thumbcategorypath';
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
					}
					if ($cat_det['product_showshortdescription']==1)// Check whether description is to be displayed
					{
						$HTML_desc = stripslash_normal($row_prod['product_shortdesc']);
					}
					$price_class_arr['class_type']          = 'div';
					$price_class_arr['normal_class']        = 'normal_shlfB_pdt_priceA';
					$price_class_arr['strike_class']        = 'normal_shlfB_pdt_priceB';
					$price_class_arr['yousave_class']       = 'normal_shlfB_pdt_priceC';
					$price_class_arr['discount_class']      = 'normal_shlfB_pdt_priceC';
					$price_arr =  show_Price($row_prod,array(),'compshelf',false,4);
					
					if($price_arr['discounted_price'])
						$HTML_price = $price_arr['discounted_price'];
					else
						$HTML_price = $price_arr['base_price'];
				
					//$HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
					if($cur_col==0)
					{
						echo  '<div class="pdt_list_thumb_outer">';
					}
					if($cur_col%2==0 && $cur_col!=0)	
					{
						$cls = "pdt_list_pdt";
					}
					else
					{
						$cls = "pdt_list_pdt";
					}
		?>			<div class="<?php echo $cls?>">
						<div class="pdt_list_pdt_mid">
		<?php		if($row_prod['product_saleicon_show']==1)
					{
						$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
						//if($desc!='')
						{
							$HTML_sale = '<div class="pdt_list_sale_3row"></div>';
						}
					}
					if($row_prod['product_newicon_show']==1)
					{
						$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
						//if($desc!='')
						{
							$HTML_new = '<div class="pdt_list_new_3row"></div>';
						}
					}
					echo $HTML_new;
					echo  $HTML_sale;
					
					if($cat_det['product_showrating']==1)
					{
						$module_name = 'mod_product_reviews';
						if(in_array($module_name,$inlineSiteComponents))
						{
							if($row_prod['product_averagerating']>=0)
							{
								$HTML_rating = '<div class="list_d_pdt_rate">'.display_rating($row_prod['product_averagerating'],1).'</div>';
							}
						}
					}
					//echo $HTML_rating;
		?>					<div class="pdt_list_pdt_r"><?php echo $HTML_image?></div>
							<?=$HTML_title;?>
							<?php
							if($cat_det['product_showshortdescription']==1)// whether title is to be displayed
							{
								?>
								
								<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" class="cupid-green_desca" title="<?php echo stripslashes($row_prod['product_name'])?>">
								<?php
								echo '<p class="product_descB">'.stripslash_normal($row_prod['product_shortdesc']).'</p>';
								?>
								</a>
								<?php
							}
							?>
							<div class="pdt_list_pdt_l">
                            <div class="pdt_list_pdt_buy_otr">
                            <div class="pdt_list_pdt_price">
		<?php		if ($cat_det['product_showprice']==1)
					{
						echo $HTML_price;
					}
		?>					</div>
		
							<div class="pdt_list_pdt_compare">
		<?php		if(isProductCompareEnabled())
					{
						if(is_array($_SESSION['compare_products']))
						{
							if(in_array($row_prod['product_id'],$_SESSION['compare_products']))
							{
								$select = "checked";
							}
							else
							{
								$select = "";
							}
						}
						else
							$select = "";
						$compare_button_displayed = true; ?><form name="add_to_compare" id="add_to_compare" action="" method="post" ><input type="hidden" name="submit_Compare_pdts" id="submit_Compare_pdts" value="1"><input type="checkbox" name="compare_products_<?php echo $row_prod['product_id'];?>" value="ADD TO COMPARE" class="buttonred_large" onclick="addtoCompare(<?php echo $row_prod['product_id']?>)" id="compare_products_<?php echo $row_prod['product_id'];?>" <?php echo $select ?> /></form> Select To Compare
		<?php		}
		?>					</div>
					
		<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" class="det_buy_link" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['MORE_INFO'])?></a>
        <?php		$frm_name = uniqid('shelf_');
		?>
					<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
                        <input type="hidden" name="fpurpose" value="" />
                        <input type="hidden" name="fproduct_id" value="" />
                        <input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
                        <input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
                        <input type="hidden" name="qty" id="qty" value="1" />																
		<?php
                        $class_arr                      = array();
                        $class_arr['ADD_TO_CART']       = 'det_buy_link';
                        $class_arr['PREORDER']          = '';
                        $class_arr['ENQUIRE']           = '';
                        $class_arr['QTY_DIV']           = 'normal_shlfB_pdt_input';
                        $class_arr['QTY']               = ' ';							
                        /* Code for ajax setting starts here */
                        $class_arr['BTN_CLS']           = 'pdt_list_pdt_buy';												
                        //show_addtocart($row_prod,$class_arr,$frm_name,false,'','','normal_shlfB_pdt_buy_btn');
                        show_addtocart_v5_ajax($frm_name,$row_prod,$class_arr,false);
                        /* Code for ajax setting ends here */
                        //echo "product display style - ".$cat_det['product_displaytype'];
		?>
					</form>				
				
				</div>
				</div>
				<div class="pdt_list_m_otr">
				<?php
				if($row_prod['product_bonuspoints']>0 and $cat_det['product_showbonuspoints']==1)
				{
				$HTML_bonus = 'Bonus: '.$row_prod['product_bonuspoints'].'';
				}
				else
				{
				$HTML_bonus = '';
				}
				
				?>
				<div class="pdt_list_bonus">  <?php echo $HTML_bonus;?> </div>             
				<?php
				/*
				<div class="pdt_list_pdt_more"><a class="" title="Love Meter T-Shirt Heart Lights" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>">More Info</a></div>
				*/?> 
				</div>   
				
				<?php
				/*
				if($row_prod['product_bulkdiscount_allowed']=='Y' || $row_prod['product_freedelivery']==1)
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
				}
				*/ 
				/*				  <div class="pdt_list_pdt_des"><?php echo $HTML_desc;?></div>
				*/ 
				
				
				?>
				
				
				</div>
				</div>
				
				<?php
				
				
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
				?>
				</div>
				</div>
				
				<?php
				break;
			}
			echo $HTML_paging;
		}
		function Show_HomeCatgeory($title,$ret_category) 
		{
			return;
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$inlineSiteComponents,$homecatgroup_id;
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
			// Get the display type for current group
			$sql_group = "SELECT catgroup_listtype 
							FROM 
								product_categorygroup 
							WHERE 
								catgroup_id = $homecatgroup_id 
								AND sites_site_id = $ecom_siteid 
							LIMIT 
								1";
			$ret_group = $db->query($sql_group);
			if($db->num_rows($ret_group))
			{
				$row_group = $db->fetch_array($ret_group);
			}
			
			switch ($row_group['catgroup_listtype'])
			{
				case 'Menu':
					echo '<div class="cate_mid_con">';
					if($title!='')
						echo '<div class="cate_mid_hdr">'.$title.'</div>';
					$cur_col = 0;
					while ($row_subcat = $db->fetch_array($ret_category))
					{
						$HTML_subcatname = $HTML_image = $HTML_short_desc = $HTML_more_icon = '';
						if($cur_col==0)
						{
							echo '<div class="cate_mid_con_in">';
							$cat_main_class_name = 'cate_mid_left';
						}
						elseif($cur_col==1)
						{
							$cat_main_class_name = 'cate_mid_right';
						}
						$HTML_subcatname 	= '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" title="'.stripslash_normal($row_subcat['category_name']).'">'.stripslash_normal($row_subcat['category_name']).'</a>';
						$HTML_image 		= '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" class="subcategoreynamelink" title="'.stripslash_normal($row_subcat['category_name']).'">';
						switch($subcategory_showimagetype)
						{
							case 'Default':
								$pass_type = 'image_thumbpath';
							break;
							case 'Icon':
								$pass_type = 'image_iconpath';
							break;
							case 'Thumb':
								$pass_type = 'image_thumbpath';
							break;
							case 'Medium':
								$pass_type = 'image_thumbcategorypath';
							break;
							case 'Big':
								$pass_type = 'image_bigpath';
							break;
							case 'Extra':
								$pass_type = 'image_extralargepath';
							break;
							default:
							  $pass_type = 'image_thumbpath';
							break;
						};
						$pass_type = 'image_iconpath';
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
					$HTML_short_desc = stripslash_normal($row_subcat['category_shortdescription']);
					$HTML_more_icon 		= '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" class="subcategoreynamelink" title="'.stripslash_normal($row_subcat['category_name']).'"><img src="'.url_site_image('cat-more.gif',1).'" border="0"/></a>';
						?>	
						<div class="<?php echo $cat_main_class_name?>">
						<div class="cate_mid_ca_top"></div>
						<div class="cate_mid_ca_mid">
						<div class="cate_mid_ca_name"><?php echo $HTML_subcatname?></div>
						<div class="cate_mid_ca_in">
						<div class="cate_mid_ca_in_top"></div>
						<div class="cate_mid_ca_in_mid">
						<div class="cate_mid_ca_in_img"><?php echo $HTML_image?></div>
						<div class="cate_mid_ca_in_des"><?php echo $HTML_short_desc?></div>
						</div>
						<div class="cate_mid_ca_in_bottom"></div>
						</div>
						<div class="cate_mid_ca_more"><?php echo $HTML_more_icon?></div>
						</div>
						<div class="cate_mid_ca_bottom"></div>
						</div>	
						<?php
						$cur_col++;	
						if($cur_col==2)
						{
							$cur_col=0;
							echo '</div>';
						}
					}
					if($cur_col>0 and $cur_col<2)
						echo '</div>';
					echo '</div>';	
				break;
				default:
					echo '<div class="cate_mid_con_in">';
					if($title!='')
						echo '<div class="cate_mid_hdr">'.$title.'</div>';
					$cur_col = 0;
					while ($row_subcat = $db->fetch_array($ret_category))
					{
						$HTML_subcatname = $HTML_image = $HTML_short_desc = $HTML_more_icon = '';
						if($cur_col==0)
						{
							echo '<div class="cate_mid_con_in">';
							$cat_main_class_name = 'cate_midA_left';
						}
						elseif($cur_col==1)
						{
							$cat_main_class_name = 'cate_midA_right';
						}
						$HTML_subcatname 	= '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" title="'.stripslash_normal($row_subcat['category_name']).'">'.stripslash_normal($row_subcat['category_name']).'</a>';
						$HTML_image 		= '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" class="subcategoreynamelink" title="'.stripslash_normal($row_subcat['category_name']).'">';
						switch($subcategory_showimagetype)
						{
							case 'Default':
								$pass_type = 'image_thumbpath';
							break;
							case 'Icon':
								$pass_type = 'image_iconpath';
							break;
							case 'Thumb':
								$pass_type = 'image_thumbpath';
							break;
							case 'Medium':
								$pass_type = 'image_thumbcategorypath';
							break;
							case 'Big':
								$pass_type = 'image_bigpath';
							break;
							case 'Extra':
								$pass_type = 'image_extralargepath';
							break;
							default:
							  $pass_type = 'image_thumbpath';
							break;
						};
									$pass_type = 'image_iconpath';
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
				
					$HTML_short_desc = stripslash_normal($row_subcat['category_shortdescription']);
					$HTML_more_icon 		= '<a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'" class="subcategoreynamelink" title="'.stripslash_normal($row_subcat['category_name']).'"><img src="'.url_site_image('cat-more.gif',1).'" border="0"/></a>';
						?>	
						<div class="<?php echo $cat_main_class_name?>">
						<div class="cate_midA_ca_top"></div>
						<div class="cate_midA_ca_mid">
						<div class="cate_midA_ca_name"><?php echo $HTML_subcatname?></div>
						<div class="cate_midA_ca_mainimg"><?php echo $HTML_image?></div>
						</div>
						<div class="cate_midA_ca_bottom"></div>
						</div>
						<?php
						$cur_col++;	
						if($cur_col==2)
						{
							$cur_col=0;
							echo '</div>';
						}
					}
					if($cur_col>0 and $cur_col<2)
						echo '</div>';
					echo '</div>';	
				break;
			};
		
		}
		/* Function to do the settings for grid display */
	function show_grid_display($catid,$check_arr)
	{
		global $db,$ecom_siteid,$horizontal_max_cols;
		//echo "here";
		// Check whether grid display is enable for this category 
		
		$groupid 		= $check_arr['groupid'];
		$hori_varid		= $check_arr['hori_varid'];
		$hori_varname	= $check_arr['hori_varname'];
		$vertical_arr 	= array();
		$horizontal_arr = array();
		?>
		<script type="text/javascript">
		function addtocart_grid(prodid,varstr,varvalstr)
		{
			alert('product:'+prodid+'  varstr:'+varstr+'  valstr:'+varvalstr);
		}
		</script>
		<?php
		// Get the details of variables assigned to group other than the horizontal variable
		$sql_vert_var = "SELECT product_variables_id 
							FROM 
								product_variables_group_variables_map a,product_preset_variables b
							WHERE 
								a.sites_site_id = $ecom_siteid 
								AND a.product_variables_id = b.var_id 
								AND a.product_variables_group_id = ".$groupid ."
								AND b.var_hide = 0 
								AND a.product_variables_id NOT IN ($hori_varid) 
							ORDER BY 
								prd_var_group_var_order ASC";
		$ret_vert_var = $db->query($sql_vert_var);
		if($db->num_rows($ret_vert_var))
		{
			while ($row_vert_var = $db->fetch_array($ret_vert_var))
			{
				$vert_varid = $row_vert_var['product_variables_id'];
				// Get the list of values for this variable to the vertical array
				$sql_verval = "SELECT var_value_id,var_value, images_image_id 
								FROM 
									product_preset_variable_data 
								WHERE 
									product_variables_var_id = $vert_varid 
									AND sites_site_id = $ecom_siteid 
								ORDER BY 
									var_order";
				$ret_verval = $db->query($sql_verval);
				if($db->num_rows($ret_verval))
				{
					while ($row_verval = $db->fetch_array($ret_verval))
					{
						$vertical_arr[]= array('id'=>$row_verval['var_value_id'],'val'=>$row_verval['var_value'],'imgid'=>$row_verval['images_image_id']);
					}	
				}
				
			}
		}
		
		// Get the list of values for this variable to the horizontal array
		$sql_verval = "SELECT var_value_id,var_value, images_image_id  
						FROM 
							product_preset_variable_data 
						WHERE 
							product_variables_var_id = $hori_varid  
							AND sites_site_id = $ecom_siteid 
						ORDER BY 
							var_order";
		$ret_verval = $db->query($sql_verval);
		if($db->num_rows($ret_verval))
		{
			while ($row_verval = $db->fetch_array($ret_verval))
			{
				//$horizontal_arr[$hori_varid][]= array('id'=>$row_verval['var_value_id'],'val'=>$row_verval['var_value']);
				$horizontal_arr[]= array('id'=>$row_verval['var_value_id'],'val'=>$row_verval['var_value'],'imgid'=>$row_verval['images_image_id']);
			}	
		}
		
		// Check whether the horizontal array is to be split to multiple arrays
		if(count($horizontal_arr)>$horizontal_max_cols)
			$split_hori_arr = array_chunk($horizontal_arr, $horizontal_max_cols);
		else
			$split_hori_arr[0] = $horizontal_arr;	
		$horizontal_arr_org = $horizontal_arr;
		$catprod_arr = array();
		$sql_prods = "SELECT products_product_id FROM product_category_map WHERE product_categories_category_id = $catid ORDER BY product_order";
		$ret_prods = $db->query($sql_prods);
		if($db->num_rows($ret_prods))
		{
			while ($row_prods = $db->fetch_array($ret_prods))
			{
				$catprod_arr[] = $row_prods['products_product_id'];
			}
		}
		
			for($v=0;$v<count($split_hori_arr);$v++)
			{				
				//echo "<br><br>Start time: ".date('r');	
				$this->draw_grid($catid,$hori_varname,$split_hori_arr[$v],$vertical_arr,$horizontal_arr_org,$catprod_arr);
				//echo "<br><br>End time: ".date('r');	
			}
			
	}
	
	function get_product_list($divid,$catid,$catprod_arr)
	{
		global $db,$ecom_siteid,$horizontal_max_cols,$ecom_hostname;
		$divarr = explode('_',$divid);
		if(array($divarr))
		{
			array_shift($divarr);
		}
		if(count($catprod_arr) and count($divarr))
		{
			
			// Get the list of products to be displayed in the current cell using the preset variable ids from product_preset_variable_grid_map table
			$sql_prds = "SELECT distinct products_product_id 
							FROM 
								product_preset_variable_grid_map 
							WHERE 
							 	horizontal_preset_var_value_id = ".$divarr[0]." 
								AND vertical_preset_var_value_id = ".$divarr[1]." 
								AND products_product_id IN (".implode(',',$catprod_arr).")";
			$ret_prds = $db->query($sql_prds);
			if($db->num_rows($ret_prds))
			{
				while ($row_prds = $db->fetch_array($ret_prds))
				{
					$row_prds['product_id'] = $row_prds['products_product_id'];
					$sql_p = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
											a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
											a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
											product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints,
											a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
											a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id 																
									FROM 
										products a
									WHERE 
										product_id = ".$row_prds['product_id']." 
										AND product_hide ='N' 
										AND sites_site_id = $ecom_siteid 
									LIMIT 
										1";
					$ret_p = $db->query($sql_p);
					if($db->num_rows($ret_p))
					{
						$row_p = $db->fetch_array($ret_p);
						$frm_name = 'frm_cat'.uniqid('').$row_prds['product_id']; ?>
						<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prods['product_id']?>)">
						<input type="hidden" name="fpurpose" value="" />
						<input type="hidden" value="<?php echo $row_prds['product_id']?>" name="fproduct_id">
						<input type="hidden" value="<?php echo $row_prds['product_id']?>" name="product_id">
						<input id="product_id_ajax" type="hidden" value="<?php echo $row_prds['product_id']?>" name="product_id">
						<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
						<input type="hidden" name="fproduct_url" value="<?php url_product($row_prds['product_id'],$row_p['product_name'])?>" />
						<input type="hidden" name="ajax_div_holder" id="ajax_div_holder" value="" />
						<?php
						echo '<span onclick="ajax_addto_cart_fromlist_grid(\'show_prod_det_ajax\',\'\',\''.$frm_name.'\',\''.$ecome_hostname.'\');" style="cursor:pointer" class="gridprod_cls">'.$row_p['product_name'].'</span>';							
						?>
						</form>
						<?php
					}
				}
			}					
		}
		return '';
		
	}
	/* Function which draws the grid using the given horizontal and vertial details */
	function draw_grid($catid,$hori_varname,$horizontal_arr,$vertical_arr,$horizontal_arr_org,$catprod_arr)
	{
		global $db,$ecom_siteid,$horizontal_max_cols,$ecom_hostname;
		$horizontal_rowarr = $vertical_rowarr = array();
		// Horizontal rows building section
		if(count($horizontal_arr)) // check whether horizontal array have values
		{
			if(strtoupper($hori_varname)=='DL')
			{
				$horizontal_rowarr[0][] = 'ISO � 1/10 mm';//'Diameter';
				$horizontal_rowarr[1][] = 'L mm';//'Length';
			}
			elseif(strtoupper($hori_varname)=='DLR')
			{
				$horizontal_rowarr[0][] = 'ISO � 1/10 mm';//'Diameter';
				$horizontal_rowarr[1][] = 'L mm';//'Length';
				$horizontal_rowarr[2][] = 'Radius';
			}
			else // case if name is not DL or DLR, then split the name using "/" and find the number of rows
			{
				$horizontal_vararr = explode("/",$hori_varname);
				if (count($horizontal_vararr))
				{
					$sr_arr = array('Diameter','Length');
					$rp_arr = array('ISO � 1/10 mm','L mm');
					for($i=0;$i<count($horizontal_vararr);$i++)
					{
						$horizontal_rowarr[$i][] = str_replace($sr_arr,$rp_arr,$horizontal_vararr[$i]); 
					}
				}
			}	
			// Building the values to be displayed for each of the columns in the horizontal area
			for($i=0;$i<count($horizontal_arr);$i++)
			{
				$value 		= $horizontal_arr[$i]['val'];
				$value_arr 	= explode('/',$value);
				for($j=0;$j<count($value_arr);$j++)
				{
					$horizontal_rowarr[$j][$i+1] = $value_arr[$j];
				}
			}
		}	
		   
								 $HTML_image = '<span class="product_imagespA">';
									//$pass_type = 'image_thumbcategorypath';
								$pass_type = 'image_thumbpath';
									$row_subcat['category_showimageofproduct'] = 0;
									if ($row_subcat['category_showimageofproduct']==0) // Case to check for images directly assigned to category
									{
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prodcat',$catid,$pass_type,0,0,1);
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
										$cur_prodid = find_AnyProductWithImageUnderCategory($catid);
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
									{ 	 $no_img = url_site_image('no_small_image.png',1);

										//echo $pass_type;
										// calling the function to get the default no image 
										//$no_img = get_noimage('prodcat',$pass_type); 
										if ($no_img)
										{
											$HTML_image .= show_image($no_img,'','','','',1);
										}	
									}
								$HTML_image .= '</span>';
							
		?>		
			<div class="grid_maindiv">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
			<td class="category_td"><?php echo $HTML_image?></td>
			<?php
			$sql_cdet = "SELECT category_name FROM product_categories WHERE category_id = $catid AND sites_site_id = $ecom_siteid LIMIT 1";
				$ret_cdet = $db->query($sql_cdet);
				if($db->num_rows($ret_cdet))
				{
					$row_cdet = $db->fetch_array($ret_cdet);
					$cname = stripslashes($row_cdet['category_name']);
				}
				/*if($horizontal_max_cols<count($horizontal_arr_org))
					$tot_horicol_cnt = $horizontal_max_cols;
				else
					$tot_horicol_cnt = count($horizontal_arr_org);
				$cur_horicol_cnt = count($horizontal_arr);
				$col_diff = $tot_horicol_cnt - $cur_horicol_cnt;*/
				//if($horizontal_max_cols<count($horizontal_arr_org))
					$tot_horicol_cnt = $horizontal_max_cols;
				//else
				//	$tot_horicol_cnt = count($horizontal_arr_org);
				$cur_horicol_cnt = count($horizontal_arr);
				$col_diff = $tot_horicol_cnt - $cur_horicol_cnt;
				for($j=0;$j<count($horizontal_arr);$j++)
				{
					$img_src = '';
					if($horizontal_arr[$j]['imgid'])
					{	
						$sql_img = "SELECT image_thumbpath 
										FROM 
											images 
										WHERE 
											image_id = ".$horizontal_arr[$j]['imgid']." 
											AND sites_site_id = $ecom_siteid 
										LIMIT 
											1";
						$ret_img = $db->query($sql_img);
						if($db->num_rows($ret_img))
						{
							$row_img = $db->fetch_array($ret_img);
							$img_src = "<img src = 'http://$ecom_hostname/images/$ecom_hostname/".$row_img['image_thumbpath']."'/>";
						}
					}	
					$z = $j+1;
					if($z==count($horizontal_arr))
						$cls_name = 'category_size_td_top_border';
					else
						$cls_name = 'category_size_td_top';
					
			?>
					<td class="<?php echo $cls_name?>"><?php echo $img_src ?></td>
			<?php
				}
				
				if($col_diff>0)
				{
				?>
					<td colspan="<?php echo $col_diff?>" class="category_size_td_blank"></td>
				<?php	
				}
			?>		
			</tr>
			<?php
			
			// Showing the horizontal rows
			for($i=0;$i<count($horizontal_rowarr);$i++)
			{
			?>
				<tr>
			<?php
				for($j=0;$j<count($horizontal_rowarr[$i]);$j++)
				{
					$z = $j+1;
					if($j==0)
					{
						if($z==count($horizontal_rowarr[$i]))
							$tdclass = 'category_td_border';
						else
							$tdclass = 'category_td';
					}	
					else	
					{
						if($z==count($horizontal_rowarr[$i]))
							$tdclass = ($j%2==0)?'category_size_td_grey_border':'category_size_td_border';
						else
							$tdclass = ($j%2==0)?'category_size_td_grey':'category_size_td';
					}
					//if($j==0)	
						$disp = str_replace('('.ucwords($cname).')','',ucwords($horizontal_rowarr[$i][$j]));
						$disp = str_replace('Mm','mm',$disp);
					//else
					//	$disp = $horizontal_rowarr[$i][$j];	
						//$disp = $horizontal_rowarr[$i][$j];
					
					 	
			?>
					<td class="<?php echo $tdclass?>"><?php echo $disp?></td>
			<?php	
				}
				if($col_diff>0)
				{
				?>
					<td colspan="<?php echo $col_diff?>" class="category_size_td_blank"></td>
				<?php	
				}
					
			?>	
				</tr>
			<?php				
			}
			// Completed display of horizontal top rows
			
			// Starting the vertical and product display grids section
			$prevval = '';
			for($i=0;$i<count($vertical_arr);$i++)
			{
				// split the vertical value to extract the header row
				$vertical_split = explode('/',$vertical_arr[$i]['val']);
				
				$colourbox = '';
				if(count($vertical_split)>1)
				{
				
					// splitting the vertical value to check whether color box is to be displayed
					$verticalcolor_split = explode(' ',$vertical_arr[$i]['val']);
					
					$color_value = $verticalcolor_split[count($verticalcolor_split)-1];
					
					//$color_value = htmlspecialchars($color_value);
					// Check whether the colour value exists in the color code table
					$sql_colour = "SELECT color_hexcode 
									FROM 
										general_settings_site_colors 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND color_name = '".$color_value."'
									LIMIT 
										1";
					$ret_colour = $db->query($sql_colour);
					if($db->num_rows($ret_colour))
					{
						$row_colour = $db->fetch_array($ret_colour);
						$colorhexcode = $row_colour['color_hexcode'];
						$colourbox = '<div style="width:10px;height:10px;background-color:'.$colorhexcode.';border:1px solid #000000;float:right"></div>';
					}
					else
						$colourbox = '';
				}	
				else
				{
					$vertical_split[1] = $vertical_split[0];
					
					// splitting the vertical value to check whether color box is to be displayed
					$verticalcolor_split = explode(' ',$vertical_arr[$i]['val']);
					
					$color_value = $verticalcolor_split[count($verticalcolor_split)-1];
					
					//$color_value = htmlspecialchars($color_value);
					// Check whether the colour value exists in the color code table
					$sql_colour = "SELECT color_hexcode 
									FROM 
										general_settings_site_colors 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND color_name = '".$color_value."'
									LIMIT 
										1";
					$ret_colour = $db->query($sql_colour);
					if($db->num_rows($ret_colour))
					{
						$row_colour = $db->fetch_array($ret_colour);
						$colorhexcode = $row_colour['color_hexcode'];
						$colourbox = '<div style="width:10px;height:10px;background-color:'.$colorhexcode.';border:1px solid #000000;float:right"></div>';
					}
					else
						$colourbox = '';
					
				}	
				
				if($vertical_split[1] != $vertical_split[0])
				{
					if($prevval!= $vertical_split[0])
					{
						$prevval= $vertical_split[0];
						
						$img_src = '';
						if($vertical_arr[$i]['imgid'])
						{	
							$sql_img = "SELECT image_thumbpath 
											FROM 
												images 
											WHERE 
												image_id = ".$vertical_arr[$i]['imgid']." 
												AND sites_site_id = $ecom_siteid 
											LIMIT 
												1";
							$ret_img = $db->query($sql_img);
							if($db->num_rows($ret_img))
							{
								$row_img = $db->fetch_array($ret_img);
								$img_src = "<img src = 'http://$ecom_hostname/images/$ecom_hostname/".$row_img['image_thumbpath']."'/>";
							}
							
						}	
					?>
						<tr>
						<td colspan="<?php echo count($horizontal_arr)+1?>" class="category_td_row"><strong><?php if($img_src=='') echo $vertical_split[0]?></strong>
						<?php if($img_src!='')
								echo '<br />'.$img_src;?>					
						</td>
						</tr>
					<?php
					}
				}
			?>
			<tr>
			<td class="category_td"><?php echo $vertical_split[1].' '.$colourbox?></td>
				<?php
					for($j=0;$j<count($horizontal_arr);$j++)
					{
						$divid = 'gridcol_'.$horizontal_arr[$j]['id'].'_'.$vertical_arr[$i]['id'];
						$z = $j+1;
						if($z==count($horizontal_arr))
							$tdclass = ($j%2==0)?'category_size_td_border':'category_size_td_grey_border';
						else
							$tdclass = ($j%2==0)?'category_size_td':'category_size_td_grey';
				?>		
						<td class="<?php echo $tdclass?>"><?php /*?><div id="<?php echo $divid?>"><?php */?><?php 	echo $this->get_product_list($divid,$catid,$catprod_arr);?><?php /*?></div><?php */?></td>
				<?php	
					}
					if($col_diff>0)
					{
					?>
						<td colspan="<?php echo $col_diff?>" class="category_size_td_blank"></td>
					<?php	
					}
					?>
					
			</tr>
			
			<?php	
			}
			?>
			</table>
			</div>
		<?php	
		}
	};	
?>