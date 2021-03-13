<?php
	/*############################################################################
	# Script Name 	: categoryHtml.php
	# Description 	: Page which holds the display logic for category details
	# Coded by 		: Sny
	# Created on	: 11-Aug-2009
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
			// ** Fetch the category details
			//$row_cat	= $db->fetch_array($ret_cat);
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
			if ($row_cat['category_turnoff_treemenu']==0)
			{
		?>
				<div class="treemenu">
				  <ul>
					<li><?php echo generate_tree($_REQUEST['category_id'],-1)?></li>
				  </ul>
				</div>
      <?php
	  		}
	  ?>
	   <script type="text/javascript" language="javascript">
	  function download_pdf() {
	  	document.getElementById('bw').innerHTML = '<iframe src="http://www.web2pdfconvert.com/convert.aspx?cURL=http://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>&outputmode=stream&allowactivex=yes&ref=form">';
		show_processing();
		setTimeout('hide_processing()', 20000);
	  }
	  </script>
        <div class="cat_list_desptn">
		<?php
			if($alert)
			{
		?>
				<div class="red_msg">
					- <?php echo $alert?> -
				</div>
		<?php
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
							show_image(url_root_image($catimg_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext');
							//$show_noimage 	= false;
						}
						/*else
							$show_noimage = true;*/
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
								show_image(url_root_image($img_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext');
								//$show_noimage = false;
							}
						}
					}	
				
			}
		}
			// ** Showing the category description if it exists
			if ($row_cat['category_paid_for_longdescription']=='Y' and trim($row_cat['category_paid_description'])!='' and trim($row_cat['category_paid_description'])!='<br>')
			{
				$cat_desc =  stripslash_normal($row_cat['category_paid_description']);
			}
			elseif (trim($row_cat['category_shortdescription'])!='')
			{
				$cat_desc = nl2br(stripslash_normal($row_cat['category_shortdescription']));
			}
			if ($cat_desc!='')
			{
					echo $cat_desc;
			}
			?>	
		</div>
		<div class="subcat_hdr">
		 <?php
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
			if($db->num_rows($ret_subcat) and ($row_cat['category_subcatlisttype']=='Middle' or $row_cat['category_subcatlisttype']=='Both'))
			{
		?>	
		 		<div class="subcat_hdr_nanme">
		 <?php
				if ($db->num_rows($ret_subcat)==1)
				{
					if($Captions_arr['CAT_DETAILS']['CATDET_SUBCAT']!='')
					{
						echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_SUBCAT']);
					}
				}
				else
				{
					if($Captions_arr['CAT_DETAILS']['CATDET_SUBCATS']!='')
					{
						echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_SUBCATS']);
					}
				}
		 ?>
		 		</div>
		 <?php
		 	}	
		 ?>
		<div class="subcat_hdr_icons"> 
		<?php 
		$url= url_category($_REQUEST['category_id'],$row_cat['category_name'],'');
		?>
		<form method="post" name="frm_catedetails" id="frm_catedetails" action="" class="frm_cls">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="caturl" value="<? echo $url?>" />
		<input type="hidden" name="type_cat" value="cate_root" />
		<input type='hidden' name='category_id' value="<?=$_REQUEST['category_id']?>"/>
	   <? 
	   /*
		if($custom_id)
		{
			$sql_cat_det = "SELECT customer_customer_id FROM customer_fav_categories WHERE sites_site_id=$ecom_siteid AND categories_categories_id=".$_REQUEST['category_id'] ." AND customer_customer_id=$custom_id LIMIT 1";
			$ret_num_cat= $db->query($sql_cat_det);
		  if($db->num_rows($ret_num_cat)==0) 
		  { 
		   ?>
			<a href="javascript:if(confirm('<?php echo stripslash_javascript($Captions_arr['CAT_DETAILS']['CAT_FAVOURITE_CONFIRM'])?>')) { document.frm_catedetails.fpurpose.value='add_favourite';document.frm_catedetails.submit();}" title="<?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_FAVOURITE'])?>"><img src="<?php url_site_image('list-fav.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_FAVOURITE'])?>" border="0" /></a>
		   <? 
		   }
		   else if($db->num_rows($ret_num_cat)>0)
		   {
		   ?>
			<a href="javascript:if(confirm('<?php echo stripslash_javascript($Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE_CONFIRM'])?>')){ document.frm_catedetails.fpurpose.value='rem_favourite';document.frm_catedetails.submit(); }" title="<?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE'])?>"><img src="<?php url_site_image('rem_from_fav_icon.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_REM_FAVOURITE'])?>" border="0" /></a>
		   <?
		   }
		}
		if ($row_cat['category_turnoff_pdf']==0)
		{
	?>
			<a  href="javascript:download_pdf_stream('<?=$_SERVER['HTTP_HOST']?>','<?=$_SERVER['REQUEST_URI']?>')" title="<?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DOWNLOADPDF'])?>"><img src="<?php url_site_image('list-pdf.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DOWNLOADPDF'])?>" border="0" /></a>


	<?php	 
		}
		*/ 
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
		/*
	if($num_prods>0)
	{	
	?>
		<a href="<?php url_category_rss($row_cat['category_id'],$row_cat['category_name'])?>" title="<?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_RSS'])?>"><img src="<?php url_site_image('list-rss.gif')?>" alt="<?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CAT_RSS'])?>" border="0" /></a>
	<? 
	} 
	*/ 
	?> 
	</form>
		</div>
		</div>
			<?php
				$subcatshow 	= false;
				$prodshow		= false;
				$subcat_exists 	= false;
				if ($row_cat['category_subcatlisttype']=='Middle' or $row_cat['category_subcatlisttype']=='Both')
					$subcatshow = true;
				// Show the products in middle only or in both.
				if ($row_cat['product_displaywhere'] == 'middle' or $row_cat['product_displaywhere'] == 'both')
					$prodshow = true;
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
					if ($subcat_exists==false and $row_cat['category_turnoff_noproducts']==0)// ** Show the no products only if there exists no subcategories for current product
					{
					?>
						<?php
							$this->Show_NoProducts(); // ** Calling function to show the no products message
						?>
					<?php
					}	
				}		
			}				
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
		$ret_inline = $db->query($sql_inline); // to dispplay the advert for the category page
		if ($db->num_rows($ret_inline))
		{
			while ($row_inline = $db->fetch_array($ret_inline))
			{
				//$modname 			= $row_inline['feature_modulename'];
				$body_dispcompid	= $row_inline['display_component_id'];
				$body_dispid		= $row_inline['display_id'];
				$body_title			= $row_inline['display_title'];
				include ("includes/base_files/shelf.php");
			}
		}
			$cat_desc = stripslashes($row_cat['category_bottom_description']);
			if ($cat_desc!='')
			{
			?>
				<div class="cat_list_desptn">
				<?php echo $cat_desc?>
				</div>
			<?php 	
			}
		}
		// ** Function to show the subcategories
		function Show_Subcategories($ret_subcat,$url,$row_cat)
		{
			global $db,$inlineSiteComponents,$Captions_arr,$Settings_arr,$ecom_siteid;
			if(in_array('mod_catimage',$inlineSiteComponents))
			{
				$img_support = true;
			}
			else
				$img_support = false;
			$custom_id = get_session_var('ecom_login_customer');
			$subcategory_showimagetype = $row_cat['subcategory_showimagetype'];
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
		?>
				 <div class="shlf_otr">

				<?php
				if($alert)
				{
				?>
					<div class="red_msg">- <?php echo $alert?> -</div>
				<?php
				}
					$cnt=0;
					while ($row_subcat = $db->fetch_array($ret_subcat))
					{    
						$cnt++;  
						if($cnt==3)
						{  
					    ?>
						 <div class="sub_cat_otr_in_a">
						 <?php
						 }
						 else
						 {
						?>
						 <div class="sub_cat_otr_in">
						 <?php
						 }
							 ?>
						<div class="sub_cat_otr_img">  <a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1)?>" class="subcategoreynamelink" title="<?php echo stripslash_normal($row_subcat['category_name'])?>">
								 <?php
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
									if ($row_subcat['category_showimageofproduct']==0) // Case to check for images directly assigned to category
									{
										// Calling the function to get the image to be shown
										$img_arr = get_imagelist('prodcat',$row_subcat['category_id'],$pass_type,0,0,1);
										if(count($img_arr))
										{
											show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_subcat['category_name']);
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
												show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_cat['category_name']);
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
											show_image($no_img,$row_subcat['category_name'],$row_subcat['category_name']);
										}	
									}
								?>
								</a></div>
         <div class="sub_cat_otr_name"><a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1)?>" title="<?php echo stripslash_normal($row_subcat['category_name'])?>"><?php echo stripslash_normal($row_subcat['category_name'])?></a></div>
         </div>
         <?php							
			}
			?>
			 </div>
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
		   $pg_variable				= 'catdet_pg';
		?>
		<div class="subcat_nav_content">
		<div class="subcat_nav_bottom">

		<div class="subcat_nav_pdt_no"><span></span><?php echo paging_show_totalcount($tot_cnt,'Product(s)',$start_var['pg'],$start_var['pages'])?></span></div>
		<div class=" page_nav_cont">
		<div class="navtxt"><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_SORTBY'])?></div>
		<div class="navselect"><select name="catdet_sortbytop" id="catdet_sortbytop">
		<option value="custom" <?php echo ($prodsort_by=='custom')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_DEFAULT'])?></option>
		<option value="product_name" <?php echo ($prodsort_by=='product_name')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_PRODNAME'])?></option>
		<option value="price" <?php echo ($prodsort_by=='price')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_PRICE'])?></option>
		<option value="product_id" <?php echo ($prodsort_by=='product_id')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['SHOPDET_DATEADDED'])?></option>
		</select>
		<select name="catdet_sortordertop" id="catdet_sortordertop">
		<option value="ASC" <?php echo ($prodsort_order=='ASC')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_LOW2HIGH'])?></option>
		<option value="DESC" <?php echo ($prodsort_order=='DESC')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_HIGH2LOW'])?></option>
		</select>							
		</div>
		</div>
		<div class=" page_nav_contA">
		<div class="navtxt"><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_ITEMSPERPAGE'])?></div>
		<div class="navselect"><?php
		if(!$_REQUEST['catdet_prodperpage']){
		$catdet_prodperpage = $Settings_arr['product_maxcntperpage'];
		}else{
		$catdet_prodperpage = $_REQUEST['catdet_prodperpage'];
		}
		?>
		<select name="catdet_prodperpagetop" id="catdet_prodperpagetop">
		<?php
		for ($ii=$Settings_arr["productlist_interval"];$ii<=$Settings_arr["productlist_maxval"];$ii+=$Settings_arr["productlist_interval"])
		{
		?>
		<option value="<?php echo $ii?>" <?php echo ($catdet_prodperpage==$ii)?'selected="selected"':''?>><?php echo $ii?></option>
		<?php	
		}
		?>
		</select>
		</div>
		</div>
		<div class=" page_nav_contB">
		<input type="button" name="submit_Page" value="<?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_GO'])?>" class="button_list_go" onclick="handle_categorydetailsdropdownval_sel('<?php echo url_category($_REQUEST['category_id'],stripslash_javascript($cat_det['category_name']),4,$_REQUEST['catgroup_id'],0)?>','catdet_sortbytop','catdet_sortordertop','catdet_prodperpagetop')" />
		</div>
		</div>
		<div class="page_nav_content">
		<ul>
		<?php 
		$path = '';
		$query_string .= "catdet_sortby=".$prodsort_by."&catdet_sortorder=".$prodsort_order."&catdet_prodperpage=".$prodperpage."&pos=top";

		$paging = paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,3); 	
		echo $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
		?>
		</ul>
		</div>                                
		</div>		
			<?php
			// ** Showing the products listing either 1 in a row or 3 in a row
			$pass_type = get_default_imagetype('prodcat');
			$prod_compare_enabled = isProductCompareEnabled();
					while($row_prod = $db->fetch_array($ret_prod))
					{ 
								?>
								<div class="shlf_pdt_otr">
								<div class="shlf_pdt_l">
								<?php 
								    if($row_prod['product_saleicon_show']==1)
									{
										$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
										//if($desc!='')
										{
									?>	
											<div class="shlf_pdt_l_spcl"><img src="<?php echo url_site_image('new-sale.gif')?>" /></div>
									<?php
										}
									}										
									else if($row_prod['product_newicon_show']==1)
									{
										$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
										//if($desc!='')
										{
									?>
											<div class="shlf_pdt_l_spcl"><img src="<?php echo url_site_image('new-product.gif')?>" /></div>
									<?php
										}
									}
									else if($row_prod['product_discount'] > 0)
									{
										?>
										<div class="shlf_pdt_l_spcl"><img src="<?php echo url_site_image('special-offer.gif')?>" /></div>
										<?php									  
									}
									else 
									{
										?>
										<div class="shlf_pdt_l_spcl_null"></div>
										<?php									  
									}					
									?>
								<div class="shlf_pdt_l_otr">
								<?php		
									if($cat_det['product_showtitle']==1)// whether title is to be displayed
									{
									?>	
										<div class="shlf_pdt_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div> 

									<?php
								}
									if ($cat_det['product_showshortdescription']==1)// Check whether description is to be displayed
									{
										?>
										<div class="shlf_pdt_des"><?php echo stripslash_normal($row_prod['product_shortdesc']);?></div>
										<?php
									}
									?>
										<div class="shlf_pdt_price">

									<?php
									if ($cat_det['product_showprice']==1)// Check whether description is to be displayed
									{ 
										$price_class_arr['class_type'] 		= 'div';

										$price_class_arr['normal_class'] 	= 'normal_shlfA_pdt_priceA';
								$price_class_arr['strike_class'] 	= 'normal_shlfA_pdt_priceB';
								$price_class_arr['yousave_class'] 	= 'normal_shlfA_pdt_priceC';
								$price_class_arr['discount_class'] 	= 'normal_shlfA_pdt_priceC';
										
										echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
									}
									?>

								</div> 
								</div> 
								<div class="shlf_pdt_buy"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="more_info_b">More Info</a></div> 
								</div> 
								<div class="shlf_pdt_r">	<?php
									if ($cat_det['product_showimage']==1)// Check whether description is to be displayed
									{
									?>	
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
								<?php 
									}
									?></div> 
								</div>
								<? 
					}
					?>
					</div>
				<?php			
			if ($tot_cnt>0)
			{
			?>
				<div class="subcat_nav_content">
				<div class="subcat_nav_bottom">
				<div class="subcat_nav_pdt_no"><span></span><?php echo paging_show_totalcount($tot_cnt,'Product(s)',$start_var['pg'],$start_var['pages'])?></span></div>
				</div>
				<div class="page_nav_content">
				<ul>
				<?php 
				$path = '';
				$query_string .= "";
				$paging = paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
				echo $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
				?>
				</ul>
				</div>                                
				</div>			
		<?
			}
		}
		// ** Function to show the no products message
		function Show_NoProducts()
		{ return;
			global $Captions_arr;
		?>
			<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
        	<tr>
	          	<td colspan="3" class="shelfBheader"><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['CATDET_PRODUCTS'])?></td>
          	</tr>
           	<tr>
        	 	<td align="left" valign="middle" class="shelfBtabletd"><h2 class="shelfBprodname" ><?php echo stripslash_normal($Captions_arr['CAT_DETAILS']['NO_PROD_MSG'])?></h2>
			 	</td>
			</tr>	 
			</table>
		<?php	
		}
		function Show_HomeCatgeory($title,$ret_category) 
		{ return;
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
			
					echo ' <div class="cate_mid_con">';
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
						/*switch($subcategory_showimagetype)
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
						};*/
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
		
		}
	};	
?>