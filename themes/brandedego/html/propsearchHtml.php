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
		$Captions_arr['CAT_DETAILS'] 	= getCaptions('CAT_DETAILS');

		//Default settings for the search
		$prodsort_by		= ($_REQUEST['search_sortby'])?$_REQUEST['search_sortby']:$Settings_arr['product_orderfield_search'];
		$prodperpage		= ($_REQUEST['search_prodperpage'])?$_REQUEST['search_prodperpage']:$Settings_arr['product_maxcntperpage_search'];// product per page
		$prodsort_order		= ($_REQUEST['search_sortorder'])?$_REQUEST['search_sortorder']:$Settings_arr['product_orderby_search'];
		$showqty			= $Settings_arr['show_qty_box'];// show the qty box
		
		//echo $prodsort_by;
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
			case 'custom': // case of order by price
			$prodsort_bysql		= 'product_name';
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
			  function select_sort(setval,perpage,property_type,property_nobedrooms,property_nobathrooms)
			  {
			  
			            var ord ="ASC";
						var ppage = perpage;
						handle_categorydetailsval_sel("<?php url_link('propsearch.html')?>",setval,ord,ppage,property_type,property_nobedrooms,property_nobathrooms);			  

			  }
			  function handle_categorydetailsval_sel(url,sortby,sortorder,page,property_type,property_nobedrooms,property_nobathrooms){
				sortbyval= sortby;
				sortorderval=sortorder;
				if(page=='All')
				{
				pageval=999;
				}
				else
				{
				pageval=page;
				}
				var loc=url+"?search_pg="+page+"&search_sortby="+sortbyval+"&search_sortorder="+sortorderval+"&search_prodperpage="+pageval+"&property_type="+property_type+"&property_nobedrooms="+property_nobedrooms+"&property_nobathrooms="+property_nobathrooms+"&#sort";
				window.location=loc;
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
	  		//echo $HTML_treemenu;
		$query_string = "&";
		$search_fields = array('property_type','property_nobedrooms','property_nobathrooms');
		foreach($search_fields as $v)
		{
			$query_string .= "$v=".$_REQUEST[$v]."&";#For passing searh terms to javascript for passing to different pages.
		}
		
		$first_querystring=$query_string; //Assigning the initial string to the variable.
		$pg_variable	= 'search_pg';
		if($_REQUEST['top_submit_Page'] || $_REQUEST['bottom_submit_Page'] )
		{
			//$_REQUEST[$pg_variable] = 0;
		}
		if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
		{
			$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
		}
		else
			$Limit = '';
	
		$querystring = ""; // if any additional query string required specify it over here
		if($_REQUEST['search_sortby']=='' || $prodsort_by=='custom')
		{
		  $sortordstring = "product_actualstock DESC,product_webprice ASC" ;
		}
		else
		{
			$sortordstring = "$prodsort_bysql $prodsort_order" ;
		}
		$Limit			= " ORDER BY $sortordstring LIMIT ".$start_var['startrec'].", ".$prodperpage;
		echo $search_sql.$Limit;
		$ret_search = $db->query($search_sql.$Limit);
		$HTML_treemenu = '
				
				<div class="breadcrumbs">
				<div class="container">
        <div class="container-tree">
          <ul>
				<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
									';
									if($_REQUEST['quick_search']!="")
									{
									 $HTML_treemenu .='<li>&nbsp;&#8594;&nbsp;'. $_REQUEST['quick_search']. '</li>';
									}
									else
									{
										$HTML_treemenu .='<li>&nbsp;&#8594;&nbsp; Search </li>';
									}
			$HTML_treemenu .= ' </ul>
    </div>
  </div></div>';
  //echo $tot_cnt  = $db->num_rows($ret_search);
		if ($db->num_rows($ret_search))
		{
			
			
			
			$top_content = '';
				// Getting the top content for searches
				if(trim($search_desc))
				{
					$top_content = trim($search_desc);
				}
				$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
                     $query_string .= "search_sortby=".$prodsort_by."&search_sortorder=".$prodsort_order."&search_prodperpage=".$prodperpage."&pos=top";
			if ($tot_cnt>0)
			{
				$prodperpage	= ($_REQUEST['search_prodperpage'])?$_REQUEST['search_prodperpage']:6;// product per page

				$pg_variable				= 'search_pg';
				$start_var 					= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
				$path 						= '';
				$pageclass_arr['container'] = 'pagenavcontainer';
				$pageclass_arr['navvul']	= 'pagenavul';
				$pageclass_arr['current']	= 'pagenav_current';
				
				$query_string 	= "&amp;search_sortby=".$_REQUEST['search_sortby'].'&amp;search_sortorder='.$_REQUEST['search_sortorder'].'&amp;search_prodperpage='.$prodperpage.'&amp;property_type='.$_REQUEST['property_type'].'&amp;property_nobedrooms='.$_REQUEST['property_nobedrooms'].'&amp;property_nobathrooms='.$_REQUEST['property_nobathrooms'];
				$mod = 'resp';
				$paging 		= paging_footer_advanced_responsive($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr,$mod);
				//print_r($start_var);
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
		$property_type=$_REQUEST['property_type'];
		$property_nobedrooms=$_REQUEST['property_nobedrooms'];
		$property_nobathrooms=$_REQUEST['property_nobathrooms']	;				     
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
            $select_sortby = ($_REQUEST['search_sortby'])?$_REQUEST['search_sortby']:'custom';
           
           $HTML_topcontent .=' <li><a href="#"  onclick="select_sort(\''.$select_sortby.'\',6,\''.$property_type.'\',\''.$property_nobedrooms.'\',\''.$property_nobathrooms.'\')" >'.$selval_arr[$select_sortby].'</a><span class="right-arrow"></span><ul>';
           unset($selval_arr[$select_sortby]);

			foreach($selval_arr as $k=>$v)
			{   $cnt++;
				$HTML_topcontent .='<li><a href="#"  onclick="select_sort(\''.$k.'\',6,\''.$property_type.'\',\''.$property_nobedrooms.'\',\''.$property_nobathrooms.'\')" >'.$v.'</a></li>'; 
            }  
            $HTML_topcontent .='</ul></li></ul>';  
            $HTML_topcontent .='</div>';
                 	
			//echo $onchange;
			$onchange = 'handle_categorydetailsval_sel(\''.url_category($_REQUEST['category_id'],stripslash_javascript($cat_det['category_name']),4,$_REQUEST['catgroup_id'],1).'\',\'product_name\',\'ASC\',999)';
    
			$perpage_arr = array ('6'=>6,'12'=>12,'18'=>18,'24'=>24,'30'=>30);
			 $HTML_topcontent .= '  <div class="pager">
                <div id="limiter">
                  <label>View: </label>';
			//echo $perpage_arr[$prodperpage];
            $HTML_topcontent .=' <ul>';
            $HTML_topcontent .=' <li><a href="#"  onclick="select_sort(\''.$select_sortby.'\','.$prodperpage.',\''.$property_type.'\',\''.$property_nobedrooms.'\',\''.$property_nobathrooms.'\')" >'.$perpage_arr[$prodperpage].'</a><span class="right-arrow"></span><ul>';
           unset($perpage_arr[$prodperpage]);

			foreach($perpage_arr as $k=>$v)
			{   $cnt++;
				$HTML_topcontent .='<li><a href="#"  onclick="select_sort(\''.$select_sortby.'\','.$k.',\''.$property_type.'\',\''.$property_nobedrooms.'\',\''.$property_nobathrooms.'\')" >'.$v.'</a></li>'; 
            }  
            $HTML_topcontent .='</ul></li></ul>';  
            $HTML_topcontent .='</div>';
            $HTML_topcontent .= $HTML_paging;
            $HTML_topcontent .='</div>';
  echo $HTML_treemenu;
			
			
			//********Section for making hidden values labels
				if(count($_REQUEST['search_label_value'])>0)
				{
					foreach($_REQUEST['search_label_value'] as $v)
					{
					$HTML_tophiddenfor  .= '<input type="hidden" name="search_label_value[]" value="'.$v.'"   />';
					}
				}
				//End section
				
			?>
				   <div class="container">
					<div class="toolbar">

			<?php
								if($top_content!='' and $top_content!='&nbsp;')
								{
									 $top_content = stripslashes($top_content);
									 $HTML_maindesc = '<div class="normal_shlfA_desc_outr">'.$top_content.'</div>';
								}
								if($_REQUEST['quick_search']!="")
								{
								?> 
								<h3 class="search_res_head_h1"><? echo strtoupper($_REQUEST['quick_search']); ?></h3>
								<?php
								}
								?>

								<?php
								echo $HTML_maindesc;
								echo $HTML_topcontent;
								//echo $HTML_paging;
								?>
								</div>
								</div>
								<?php
								
								$max_col = 3;
								$cur_col = 0;
								$prodcur_arr = array();
								?>
																			 <div class="shelf-container shelf-containerA container-fluid">  

								<?php
											$pass_type = 'image_bigpath';

					   while($row_prod = $db->fetch_array($ret_search))
						{	
							
									$HTML_title = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a> ';
							
							
							
							/*if($rwCnt==1)
							{
							  echo '<div class="'.CONTAINER_CLASS.'">';
							}*/
							?>
								<div class="grid_1_of_4 images_1_of_4">
									<div class="product-grid">
										
										<?php 
										$rate = $row_prod['product_averagerating'];
										//echo $rating = $this->display_rating_responsive($rate,1,$row_prod['product_id']);
										?>
												<p>
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>

												</p>
											<div class="product-pic bandnew">
												<?php
												$str_arrstr = array();
												$HTML_newAA = '';
												$HTML_newBB ='';
												if($row_prod['product_newicon_show']==1)
													{
														$descstr = stripslash_normal(trim($row_prod['product_newicon_text']));
														if($descstr!='')
														{
															$str_arrstr = explode ("~", $descstr);
															
														}
													}
													        if($str_arrstr[0]!='' AND $str_arrstr[0]=='Brand New For 2020')
															{
															  echo $HTML_newAA = '<div class="normal_shlfAA_pdt_new"><img src="'.url_site_image('brand_new.png',1).'" alt="Brand New"></div>';
														    }
														    else
														    {
															 	echo $HTML_newAA = '<div class="normal_shlfAA_pdt_new_blank">&nbsp;</div>';

															}
														   
												?>
												<?php
											if($row_prod['product_actualstock']==0)
											{
										?>
												<div class="nowletgraph"><img src="<?php echo url_site_image('nowLet.svg',1) ?>" alt="Now Let"></div>
										<?php		
											}
											if($row_prod['product_actualstock']>0)
													$availability_msg = '<span class="availablegraph">'.$Captions_arr['COMMON']['PRODDET_AVAIL_1_YEAR'].'</span>';
												else
													$availability_msg = '<span class="availablegraph">'.$Captions_arr['COMMON']['PRODDET_AVAIL_2_YEAR'].'</span>';
												echo $availability_msg;
										?>
													<a href="javascript:handletap('<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>','shelf<?php echo $row_prod['product_id']?>')" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php //echo getmicrodata_producturl()?> >
														<?php
														// Calling the function to get the image to be shown
														$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
														if(count($img_arr))
														{
															$moreimgarr= array();
															$moreimgstr = '';
															$prodmoreimg_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0);
															if(count($prodmoreimg_arr))
															{
																for($mi=0;$mi<count($prodmoreimg_arr);$mi++)
																{
																	$moreimgarr[]= url_root_image($prodmoreimg_arr[$mi][$pass_type],1);
																}
																$moreimgstr = implode(",",$moreimgarr);
															}
															//$imgpass_arr = array('id'=>$img_arr[0]['image_id'],'typ'=>'big');
															show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'listscrollimg" data-imageid="shelf'.$row_prod['product_id'].'" data-tapped="0" data-immore="'.$moreimgstr,'',0,$imgpass_arr);
															
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
															if($str_arrstr[1]!=''  AND $str_arrstr[1]=='Examples Of Finish')
														    {
															  echo $HTML_newBB = '<div class="normal_shlfBB_pdt_new"><img src="'.url_site_image('example-finish.png',1).'" alt="Example Finish"></div>';
															}
															 else
														    {
															 	echo $HTML_newBB = '<div class="normal_shlfAA_pdt_new_blank">&nbsp;</div>';

															}
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
												$link 		= url_product($row_prod['product_id'],$row_prod['product_name'],1);

												?>
												<div class="moreinfolist"><a href="<?php echo $link?>" title="<?php echo $row_prod['product_name']?>" class="moreinfolist_a">More Info</a></div>
												<?php
								 if($_REQUEST['req']=='')
								 $mod['source'] = "shelf";
								 else
								 $mod['source'] = "list";
								 show_ProductLabels_Unipad($row_prod['product_id'],$mod); ?>
											</div>
<?php                       
                            //confirm message section start here 
                            $frm_name = uniqid('catdet_'); 
							$sql_pp = "SELECT product_actualstock FROM products WHERE product_id =".$row_prod['product_id']." LIMIT 1";
							$ret_pp = $db->query($sql_pp);
							$row_pp = $db->fetch_array($ret_pp);
							if($row_pp['product_actualstock']==0)
							{
							$onclick = "";
							$type ='button';
							$class_arr['BTN_CLS']     = 'btn btn-add-to-cart btn-lg sharp enquire-confirm';
							}
							else
							{
							$type ='submit';
							$class_arr['BTN_CLS']     = 'btn btn-add-to-cart btn-lg sharp';
							$onclick = "return product_enterkey(this,".$row_prod['product_id'].")";
							}
							?>
							
							
							<?php
							 //confirm message section end here
							?>
											<div class="product-info">
													<div class="product-info-cust">
														<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">Details</a>
													</div>
													<div class="product-info-price">
															
													
																	<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="<?php echo $onclick ?>">
																	<input type="hidden" name = "form_name_<?php echo $row_prod['product_id']?>" id = "form_name_<?php echo $row_prod['product_id']?>" value="<?php echo $frm_name; ?>">
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
																	//$class_arr['BTN_CLS']     = 'btn btn-add-to-cart btn-lg sharp';
																	echo show_addtocart_responsive($frm_name,$row_prod,$class_arr,0,'shelf',false,true,'','',$type);?>
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
								
								$rwCnt++;
								//if($rwCnt==4)
								//{
								 // echo '</div>';
								  //$rwCnt=1;
								//}
								
								
					    }
								
								?>
								</div>
								
                    
								<?php
								//echo $HTML_paging;
										
		}
		else
		{
			echo $HTML_treemenu;
		   ?>
			 <div class="shelf-container shelf-containerA container-fluid">  
			  Sorry, your search returned no results. Please try again with different search terms .
	 
			<div>

		   <?php
		   
		}
		
	}
	//Defining the product details function
	
};
?>
