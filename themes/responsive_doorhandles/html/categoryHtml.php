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
				$HTML_treemenu = '
				
				<div class="row breadcrumbs">
				<div class="container">
        <div class="container-tree">
          <ul>
				'.generate_tree_menu($_REQUEST['category_id'],-1,'→','<li>',' </li>').
				'
			 </ul>
    </div>
  </div></div>';				
					
	  		}
	  		echo $HTML_treemenu;
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
					$HTML_alert = '<div class="red_msg">
								- '.$alert.' -
								</div>';

			
			if ($cat_desc!='')
			{
				$HTML_catdesc = '<div class="col-md-9">'.$cat_desc.'</div>';
			}
			//$row_cat['category_turnoff_mainimage'] =1;
							
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
							$HTML_image = '			<div class="product-grid">
							<div class="product-pic">'.show_image(url_root_image($catimg_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext','',1).'<ul class="price-avl">
								
								
								<div class="clear"> </div>
							</ul>
							</div></div>';
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
								$HTML_image = '			<div class="product-grid">
							<div class="product-pic">'.show_image(url_root_image($img_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext','',1).'<ul class="price-avl">
								
								
								<div class="clear"> </div>
							</ul>
							</div></div>';
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
			//$subcatshow 	= false;
			$prodshow		= false;
			$subcat_exists 	= false;
			if ($row_cat['category_subcatlisttype']=='Middle' or $row_cat['category_subcatlisttype']=='Both')
				$subcatshow = true;
			// Show the products in middle only or in both.
			if ($row_cat['product_displaywhere'] == 'middle' or $row_cat['product_displaywhere'] == 'both')
				$prodshow = true;
						?>
	
		<div class="container list-item-cart">
					<div class="container">

		<div class="new_title center">
        <h2><?php echo stripslashes($row_cat['category_name']);?></h2>
      </div>
		
		<div class="col-md-3 colouter">
			
				<?php
				/*
							<div class="product-grid-head">
								
								<div class="block">
									 
								</div>
							</div>
							*/
							?> 
							<?php 
							echo $HTML_image;
							?>							
						</div>
						<?php
			echo $HTML_catdesc;	 
			?>
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
				   //$prodperpage = 5;
					/*switch ($prodsort_by)
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
					*/ 
					$prodsort_order	= ($_REQUEST['catdet_sortorder'])?$_REQUEST['catdet_sortorder']:$def_orderby;
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
									a.product_freedelivery,a.product_show_pricepromise,a.product_actualstock , 
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
					?>
					<div class="container"><div class="col-md-12 listitems">    <h2></h2>
					<?php
						$cur_col = 0;
						while ($row_subcat = $db->fetch_array($ret_subcat))
						{
							$HTML_subcatname = $HTML_image = $HTML_short_desc = '';
							echo '<div class="list-product clearfix">

    
    
';
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
											$HTML_image .= '<figure>'.show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_subcat['category_name'],'','',1).'</figure>';
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
							 $HTML_image .='<h5><a href="#">'.utf8_encode($HTML_subcatname).'</a></h5></div>';
							if($row_cat['category_showshortdesc']==1)
							{
								$HTML_short_desc = stripslash_normal($row_subcat['category_shortdescription']);
							}
							echo $HTML_image;
							
						}
						?>
						    </div></div>

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
			//$prodperpage = 4;
			$prodsort_order   = 'ASC';
			$prodsort_capt = "Default";
			$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
             switch($prodsort_by){
				 case 'product_name_lowtohigh':
				  $prodsort_by = 'product_name';
				  $prodsort_capt = "Product Name [ A - Z ]";
				  break;
				  case 'product_name_hightolow':
				  $prodsort_by = 'product_name';
				  $prodsort_capt = "Product Name [ Z - A ]";
				  break;
				  case 'custom':
				  $prodsort_capt = "Default";
				  break;
				  case 'price_lowtohigh':
				  $prodsort_by = 'price';
				  $prodsort_capt = "Price [ Low To High ]";
				  break;
				  case 'price_hightolow':
				  $prodsort_by = 'price';
				  $prodsort_capt = "Price [ High To Low ]";
				  break;
				  case 'product_id':
				  $prodsort_capt = "Date Added";
				  break;
				  
				 }

            ?>
            <script type="text/javascript">
				var $ajax_jj = jQuery; 
             function handle_pads_listing(passid)
			  {
				  objs = eval('document.getElementById("'+passid+'")');
					if(objs)
					{
							$ajax_jj(objs).slideToggle(800);
					}
			  }
			  function open_menu(id)
			  { 
				   if(document.getElementById(id).style.display=='none')
				   {
						document.getElementById(id).style.display='block';					
				   }
				   else
				   {
						document.getElementById(id).style.display='none';
				   }			   
			  }
			  function select_sort(setval,perpage)
			  {
			  
			            var ord ="ASC";
						var ppage = perpage;
						handle_categorydetailsval_sel("<?php echo url_category($_REQUEST['category_id'],stripslash_javascript($cat_det['category_name']),4,$_REQUEST['catgroup_id'],1)?>",setval,ord,ppage);			  

			  }
			 /*
				 $(document).ready(function() {
					 $('.aSortBy').on('click', function(e) {
						  var setval ='';
						 setval = $(this).attr('data-value');
						var ord ="ASC";
						var ppage = 6;
						handle_categorydetailsval_sel("<?php echo url_category($_REQUEST['category_id'],stripslash_javascript($cat_det['category_name']),4,$_REQUEST['catgroup_id'],1)?>",setval,ord,ppage);			  
						});					
				});
				*/
			
            </script>
                           
            <?php
			if ($tot_cnt>0)
			{
				$prodperpage	= ($_REQUEST['catdet_prodperpage'])?$_REQUEST['catdet_prodperpage']:6;// product per page

				$pg_variable				= 'catdet_pg';
				$start_var 					= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
				$path 						= '';
				$pageclass_arr['container'] = 'pagenavcontainer';
				$pageclass_arr['navvul']	= 'pagenavul';
				$pageclass_arr['current']	= 'pagenav_current';
				
				$query_string 	= "&amp;catdet_sortby=".$_REQUEST['catdet_sortby'].'&amp;catdet_sortorder='.$_REQUEST['catdet_sortorder'].'&amp;catdet_prodperpage='.$_REQUEST['catdet_prodperpage'];
				$mod = 'resp';
				$paging 		= paging_footer_advanced_responsive($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,$mod);
				
				if($start_var['pages']>1)
				{
									
					$HTML_paging	= '	 
					 <div class="pages">
                  <ul class="pagination">
												
												'.$paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'].'
												 </ul>
												</div> 
                                ';
				}					
									
			}			
							     
       $HTML_topcontent = ' <div id="sort-by">
                <label class="left">Sort By: </label>';
			
            $HTML_topcontent .=' <ul>';
                    	$selval_arr =array();
								$selval_arr = array (
														'custom'		=> stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DEFAULT']),
														'product_name'	=> stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_PRODNAME']),
														'price'			=> stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_PRICE']),
														'product_id'	=> stripslash_normal($Captions_arr['CAT_DETAILS']['SHOPDET_DATEADDED']));
																$cnt=0;	
            $select_sortby = ($_REQUEST['catdet_sortby'])?$_REQUEST['catdet_sortby']:'custom';
           
           $HTML_topcontent .=' <li><a href="#"  onclick="select_sort(\''.$select_sortby.'\',6)" >'.$selval_arr[$select_sortby].'</a><span class="right-arrow"></span><ul>';
           unset($selval_arr[$select_sortby]);

			foreach($selval_arr as $k=>$v)
			{   $cnt++;
				$HTML_topcontent .='<li><a href="#"  onclick="select_sort(\''.$k.'\',6)" >'.$v.'</a></li>'; 
            }  
            $HTML_topcontent .='</ul></li></ul>';  
            $HTML_topcontent .='</div>';
                 	
			//echo $onchange;
			$onchange = 'handle_categorydetailsval_sel(\''.url_category($_REQUEST['category_id'],stripslash_javascript($cat_det['category_name']),4,$_REQUEST['catgroup_id'],1).'\',\'product_name\',\'ASC\',999)';
    
			$perpage_arr = array ('6'=>6,'12'=>12,'18'=>18,'24'=>24,'30'=>30);
			 $HTML_topcontent .= '  <div class="pager">
                <div id="limiter">
                  <label>View: </label>';
			
            $HTML_topcontent .=' <ul>';
            $HTML_topcontent .=' <li><a href="#"  onclick="select_sort(\''.$select_sortby.'\','.$prodperpage.')" >'.$perpage_arr[$prodperpage].'</a><span class="right-arrow"></span><ul>';
           unset($perpage_arr[$prodperpage]);

			foreach($perpage_arr as $k=>$v)
			{   $cnt++;
				$HTML_topcontent .='<li><a href="#"  onclick="select_sort(\''.$select_sortby.'\','.$k.')" >'.$v.'</a></li>'; 
            }  
            $HTML_topcontent .='</ul></li></ul>';  
            $HTML_topcontent .='</div>';
            $HTML_topcontent .= $HTML_paging;
            $HTML_topcontent .='</div>';

			
			
			//$HTML_topcontent .=	generateselectbox('catdet_prodperpagetop',$perpage_arr,$catdet_prodperpage,'','',0,'',false,'catdet_prodperpagetop');
		
		
		
			//echo $HTML_topcontent;echo $HTML_paging;	
			// ** Showing the products listing either 1 in a row or 3 in a row
			$pass_type = 'image_extralargepath';
			$comp_active = isProductCompareEnabled();
				?>
				
  
				<div class="container">
					<div class="toolbar">
						<?php
						/*
              <div class="sorter">
                <div class="view-mode"> <span title="Grid" class="button button-active button-grid">&nbsp;</span><a href="list.html" title="List" class="button-list">&nbsp;</a> </div>
              </div>
              */
              ?> 
             
			
						
              <?php echo $HTML_topcontent;?>
              
             <?php //echo $HTML_paging;?>
               
                <?php
                /*
                <div class="pages">
                  <label>Page:</label>
                  <ul class="pagination">
                    <li><a href="#">«</a></li>
                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#">»</a></li>
                  </ul>
                </div>
                */
                ?> 
              
            </div>
            </div>
                                 <?php
                                 //echo $HTML_topcontent2;
                                 //echo $HTML_topcontent; 
                                 ?>                       
                            
                            
                    
			<div class="container containerC">  
 <h3><?php echo utf8_encode($cur_title);?></h3>


	<?php 
	$rwCnt	=	1;
	$HTML_price = '';
	$HTML_title = '';
	?>
	<div class="container"> 
	<?php
					    while($row_prod = $db->fetch_array($ret_prod))
						{	
							
									$HTML_title = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a> ';
							
							
							?>
								<div class="grid_1_of_4 images_1_of_4">
									<div class="product-grid">
										
										<?php 
										$rate = $row_prod['product_averagerating'];
										echo $rating = $this->display_rating_responsive($rate,1,$row_prod['product_id']);
										?>
											
											<div class="product-pic">
												
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php //echo getmicrodata_producturl()?> >
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
												<p>
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>

												</p>
												<?php
												if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
													?>
													<?php			
													$price_class_arr['ul_class'] 		= 'price-avl';
													$price_class_arr['normal_class'] 	= '';
													$price_class_arr['strike_class'] 	= 'price';
													$price_class_arr['yousave_class'] 	= '';
													$price_class_arr['discount_class'] 	= '';
													//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
													$price_array =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,3);
													//print_r($price_array);
													?>
													 <ul class="price-avl">
								<li class="price"><span>
													<?php
													$disc =  $price_array['discounted_price'];
													$base =  $price_array['base_price'];
													if($disc>0)
													echo $disc;
													else
													echo $base;
													//show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													?>
													</span>
													</li>
													</ul>						
													<div class="clear"> </div>
												   <?php 
												  }
												?>
												
											</div>
											<div class="product-info">
													<div class="product-info-cust">
														<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">Details</a>
													</div>
													<div class="product-info-price">
															<?php $frm_name = uniqid('catdet_'); ?>
													
																	<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="qty" value="1" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<?php			$class_arr['ADD_TO_CART']       = '';
																	$class_arr['PREORDER']          = '';
																	$class_arr['ENQUIRE']           = '';
																	$class_arr['QTY_DIV']           = '';
																	$class_arr['QTY']               = ' ';
																	$class_td['QTY']				= '';
																	$class_td['TXT']				= '';
																	$class_td['BTN']				= '';
																	$class_arr['BTN_CLS']     = 'btn btn-add-to-cart btn-lg sharp';
																	echo show_addtocart_responsive($frm_name,$row_prod,$class_arr,0,'shelf',false,true);?>
																	</form>
													</div>
												<div class="clear"> </div>
											</div>
											<div class="more-product-info">
												<span> </span>
											</div>
											
									</div>
								</div>						
							
								<?php
								
					    }?>
					    </div>
					              </div>
								
												
								
			<?php
							

		}
		function Show_HomeCatgeory($title,$ret_category) 
		{
		  
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$inlineSiteComponents,$body_dispcompid;
			
			$sql_category = "SELECT a.category_id,b.catgroup_id, b.category_showimagetype,b.catgroup_listtype, 
								category_name,category_shortdescription,parent_id,
								category_paid_description, category_paid_for_longdescription,
								category_showimageofproduct,default_catgroup_id, 
								category_subcatlisttype,product_displaytype,product_displaywhere, 
								product_showimage,product_showtitle,
								product_showshortdescription,product_showprice  
							FROM 
								product_categorygroup_category a , product_categorygroup b, product_categories c
							WHERE 
								b.sites_site_id =   $ecom_siteid 
								AND a.catgroup_id = b.catgroup_id
								AND b.catgroup_id = '$body_dispcompid' 
								AND c.category_id = a.category_id 
								AND c.category_hide = 0 
								AND catgroup_hide =0 
								$more_conditions 
							ORDER BY 
								a.category_order";
		$ret_category = $db->query($sql_category);
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
		function display_rating_responsive($rate,$ret=0,$prod_id=0)
{ 
	global $ecom_siteid,$Settings_arr;
	if($Settings_arr['proddet_showwritereview']==1 or $Settings_arr['proddet_showreadreview']==1)
	{
		$retn ='<div class="container-star"><p>';
		$rate = ceil($rate);
		for ($i=0;$i<$rate;$i++)
		{
					if($ret==0)
						echo '<span class="glyphicon glyphicon-star"></span>'; 
					elseif($ret==1)
						$retn .= '<span class="glyphicon glyphicon-star"></span>';
		}
		if($rate<5)
		{
			$rem = ceil(5-$rate);
			for ($i=0;$i<$rem;$i++)
			{
						if($ret==0)
							echo '<span class="glyphicon glyphicon-star-empty"></span>'; 
						elseif($ret==1)
							$retn .= '<span class="glyphicon glyphicon-star-empty"></span>';    
			}
		}
		if($ecom_siteid==104 or $ecom_siteid==106)
		{  
			global $db;
			$cnt = 0;
		       if($prod_id>0)
		       {
		          $sql_prodreview	= "SELECT count(review_id) as cnt
									FROM  
										 product_reviews 
									WHERE  
										sites_site_id = $ecom_siteid
										AND products_product_id  =  ".$prod_id."
										AND review_status = 'APPROVED'  
										AND review_hide=0 
									LIMIT 10";
				 $ret_prodreview = $db->query($sql_prodreview);
				    if($db->num_rows($ret_prodreview))
					{
						$row_prodreview = $db->fetch_array($ret_prodreview);
				        $cnt = $row_prodreview['cnt']; 
					
					}
					if($cnt>0)
					{
					   $retn .= '<a href="'.url_product($prod_id,'',1).'?prod_curtab=-4#review" title="'.stripslashes($row_prod['product_name']).'"><div class="rev_cnt">	'.$cnt.' Review(s)</div></a>';
					}					
				}
		 }
		 $retn .='</p>
			    </div>';	
			if($ret==1)
				return $retn;
	}
}
	};	
?>
