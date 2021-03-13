<?php
	/*############################################################################
	# Script Name 	: shelfHtml.php
	# Description 	: Page which holds the display logic for middle shelves
	# Coded by 		: Sny
	# Created on	: 10-Aug-2009
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class shelf_Html
	{
		// Defining function to show the shelf details
		function Show_Shelf($cur_title,$shelf_arr)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$shelf_for_inner,$ecom_allpricewithtax,$PriceSettings_arr;
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
					$shelfsort_by		= 'a.product_webprice';
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
				$show_max               =0;
				
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
						if($_REQUEST['req']!='') // If coming to show the details in middle area other than from the home page then show the details in normal shelf style
						{
							if($shelf_for_inner==true) /* Case if call is to display shelf at the bottom on inner pages*/
							{
								if($shelfData['shelf_currentstyle']!='gallery')
									$shelfData['shelf_currentstyle']='nor';
									$shelfData['shelf_currentstyle']='gallery';
							}		
							else
								$shelfData['shelf_currentstyle']='nor';
						}	
						// Get the total number of product in current shelf
						$sql_totprod = "SELECT count(b.products_product_id) 
									FROM 
										products a,product_shelf_product b 
									WHERE 
										b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
										AND a.product_id = b.products_product_id 
										AND a.product_hide = 'N' ";
						$ret_totprod 	= $db->query($sql_totprod);
						list($tot_cnt) 	= $db->fetch_array($ret_totprod); 
						
						// Call the function which prepares variables to implement paging
						$ret_arr 		= array();
						$pg_variable	= 'shelf_'.$shelfData['shelf_id'].'_pg';
						if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
						{
							$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$tot_cnt);
							$Limit			= " LIMIT ".$start_var['startrec'].", ".$prodperpage;
						}	
						// Get the list of products to be shown in current shelf
						 $sql_prod = "SELECT a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
											a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
											a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
											product_total_preorder_allowed,a.product_applytax,a.product_shortdesc,a.product_bonuspoints,
											a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
											a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
											a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,a.price_specialofferprefix, a.price_specialoffersuffix, 
											a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
											a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
											a.product_freedelivery,a.product_actualstock           
										FROM 
											products a,product_shelf_product b 
										WHERE 
											b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
											AND a.product_id = b.products_product_id 
											AND a.product_hide = 'N' 
										ORDER BY 
											a.product_webstock DESC,a.product_webprice ASC 
										$Limit	";
						$ret_prod = $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{ 
							$comp_active = isProductCompareEnabled();
							$pass_type = 'image_bigpath';
							// Number of result to display on the page, will be in the LIMIT of the sql query also
							$querystring = ""; // if any additional query string required specify it over here
							$HTML_comptitle = $HTML_maindesc = $HTML_paging = $HTML_showall = '';
								
							//if ($_REQUEST['req']=='')// LIMIT for products is applied only if not displayed in home page
							{
								
							if ($tot_cnt>0 and ($_REQUEST['req']!='') and $tot_cnt>$prodperpage)
								{
									$paging 		= paging_footer_advanced($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr);
									$HTML_paging 	='
									<div class="subcat_nav_pdt_no_shelf"><span>'.$paging['total_cnt'].'</span></div></td></tr>
<tr>
<td class="pagingtd" colspan="2">
<div class="page_nav_content"><ul>';//.'';
									$HTML_paging 	.= $paging['navigation']['start_nav'].$paging['navigation']['page_no'].$paging['navigation']['end_nav'];
									$HTML_paging 	.= ' 
														</ul></div>
													
														
														
														';
								}
								if($_REQUEST['req']=='' and  $tot_cnt>0) // case of show all link
								{
								   $HTML_showall = "
								   
								   <div class='normal_mid_showall'><a href='".url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)."' title=''>".$Captions_arr['COMMON']['SHOW_ALL']."</a></div>
								  ";
								} 
								if($shelfData['shelf_currentstyle']=='nor')
								{ 
									if($_REQUEST['req']=='')
									{
									 $cont_cls = 'container';

									}
									else
								    $cont_cls = 'row';
								  if($_REQUEST['req']=='')
								  {
								   $cls_title = "titlehead";
								   $cls_home = "homeshelfcls";
								  }  
								  else
								  {
								   $cls_title = "titlehead_new";
								   $cls_home ="";
								  }

								?>
			<div class="<?php echo $cont_cls;?>">
	<div class="recent-title"><?php echo utf8_encode($cur_title);?></div>
								<div class="panel-group <?php echo $cls_home ?>" id="accordion">

	<?php 
	$rwCnt	=	1;
	$HTML_price = '';
	$HTML_title = '';
					    while($row_prod = $db->fetch_array($ret_prod))
						{	
							
									$HTML_title = '<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a> ';
							
							
							?>
							<div class="<?php echo $cls_title ?>"><?php  echo utf8_encode($HTML_title);?></div>
							
							<div class="img-container">
							<div class="newicons_container_outer">
							
							<?php
								
								if($row_prod['product_actualstock']==0)
								{
								?>	
									
									<div class="nowlet_cls_newcls"><?php echo $Captions_arr['COMMON']['NOW_LET']?></div>
								<?php	
								}
								$availability_msg = '';
								if($row_prod['product_actualstock']>0)
								{
									$availability_msg = '<div class="red_availableA_newcls">'.$Captions_arr['COMMON']['PRODDET_AVAIL_1_YEAR'].'</div>';
								}	
								else
								{
									$availability_msg = '<div class="red_availableA_newcls">'.$Captions_arr['COMMON']['PRODDET_AVAIL_2_YEAR'].'</div>';
								}
								if($availability_msg)
								{
									echo $availability_msg;
								}	
								?>
							</div>	

								
							<?php
							/*
								if($row_prod['product_actualstock']==0)
								{
									?>
									<div class="nowlet_cls_inner"><img src="<?php echo url_site_image('nowLet.png',1) ?>" alt="Now Let"></div>
							<?php		
								}
								*/ 
							?>
							<div class="single-products">
							<div class="productinfo-homeA text-center ">
								<?php							
									$pass_type	=	'image_bigpath';
									$img_arr	=	get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
									//$tabimg_arr	=	get_imagelist('prod',$row_prod['product_id'],'image_bigpath',0,0,1);
									
									$HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'" class="image_linkA">';
									?>
									<?php
								/*if($row_prod['product_actualstock']==0)
								{
									
									$HTML_image .='<div class="nowlet_cls_innerA"><img src="'.url_site_image('nowLet.png',1).'" alt="Now Let"></div>';
									
								}*/
							?>
							<?php
								/*if($row_prod['product_actualstock']>0)
													$availability_msg = '<span class="red_availableA">'.$Captions_arr['COMMON']['PRODDET_AVAIL_1_YEAR'].'</span>';
												else
													$availability_msg = '<span class="red_availableA">'.$Captions_arr['COMMON']['PRODDET_AVAIL_2_YEAR'].'</span>';
												 //$availability_msg;	
												$HTML_image .= $availability_msg;*/
								?>
								<?php
									$curimgid = $shelfData['shelf_id'].'_'.$row_prod['product_id'];
									global $def_mainimg_id;
									$def_mainimg_id = $curimgid;
									// Calling the function to get the image to be shown
									if(count($img_arr))
									{
										//$HTML_image .= show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',1);
										$HTML_image .= '<img src="'.url_root_image($img_arr[0][$pass_type],1).'" id="'.$curimgid.'" alt="'.$row_prod['product_name'].'">';														}
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
									echo $HTML_image;
									$price_arr =  show_Price($row_prod,array(),'compshelf',false,3);
									if($price_arr['discounted_price'])
										$HTML_price = $price_arr['discounted_price'];
									else
										$HTML_price = $price_arr['base_price'];
									?>

							</div>

							</div>
							<?php
							/*
								if($row_prod['product_actualstock']>0)
													$availability_msg = '<span class="green_available">'.$Captions_arr['COMMON']['PRODDET_AVAIL_1_YEAR'].'</span>';
												else
													$availability_msg = '<span class="red_available">'.$Captions_arr['COMMON']['PRODDET_AVAIL_2_YEAR'].'</span>';
												echo $availability_msg;	
												*/ 
								?>
							</div>
							<?php 
							if($HTML_price!='')
							{
								?>
							<p class="rent-title"><?php echo utf8_encode($HTML_price);?></p>
                             <?php 
                             } ?> 
								<?php
								 if($_REQUEST['req']=='')
								 $mod['source'] = "shelf";
								 else
								 $mod['source'] = "list";
								 show_ProductLabels_Unipad($row_prod['product_id'],$mod); ?>
							<?php 
										/*
										if($row_prod['product_actualstock']>0)
										{
										?>
										<a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>" class="mainbtn btn-default book-now"><i class="fa fa-shopping-cart"></i>book now</a>
										<?php
									    }
									    else
									    */ 
									    {
										?>
									     <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="btn btn-default add-to-cart">More Info</a>

										<?php
										}
										?>
							<?php
							$rwCnt++;
							echo "<div class='sep_div'></div>";
					    }?>
					              </div>

					    </div>
												
								<?php
								
							}
							else if($shelfData['shelf_currentstyle']=='gallery')
							{
							echo  "<link href=\"".url_head_link("images/".$ecom_hostname."/mobile_photoswype/css/photoswipe.css",1)."\" media=\"screen\" type=\"text/css\" rel=\"stylesheet\" />";
							echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/mobile_photoswype/js/klass.min.js",1)."\"></script>";
							echo "<script type=\"text/javascript\" src=\"".url_head_link("images/".$ecom_hostname."/mobile_photoswype/js/code.photoswipe-3.0.5.min.js",1)."\"></script>";
							?>

							
							<div class="row">
								<?php
								
											$prev_id = 0;							  
											$imghold_arr = array();
											$cnt_cls = 0 ;
											$js_repeatfunction ='var myPhotoSwipe;';
											while($row_prod = $db->fetch_array($ret_prod))
											{
												$cnt_cls++;
												//$pass_type ='image_bigpath';
												$pass_type ='image_bigpath';
												$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,0);
												
												if(count($img_arr))
												{
												?>
													<div class="gallery_propertyouter">
														<div class="gallery_propertyname"><a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1)?>"><?php echo stripslashes($row_prod['product_name'])?></a></div>
														<ul id="Gallery<?php echo $row_prod['product_id'];?>" class="Gallery" >
														<?php
														for($im_i=0;$im_i<count($img_arr);$im_i++)
														{ 

														?>

														<li><div class="det_thumbimg_pdt">
														<div class="det_thumbimg_image">
														<a href="<?php url_root_image($img_arr[$im_i]['image_bigpath'])?>"  title="<?=$title?>">
														<?php
														show_image(url_root_image($img_arr[$im_i][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'','',0);
														?>
														</a>
														</div>
														</div>
														</li>


														<?php
														}
														?>
														</ul>
													</div>
												<?php	
												}	
												$js_repeatfunction .= "myPhotoSwipe = Code.PhotoSwipe.attach( window.document.querySelectorAll('#Gallery".$row_prod['product_id']." a'), { enableMouseWheel: false , enableKeyboard: false } );";
											}
									
									?>
								</div>
								<script type="text/javascript">
								// Set up PhotoSwipe with all anchor tags in the Gallery container
								document.addEventListener('DOMContentLoaded', function(){
								<?php echo $js_repeatfunction ;?>

								}, false);
								</script>
	       <?php /*
								<script type="text/javascript">
								$(document).ready(function(){        
	$('li img').on('click',function(){
		
		elm = $(this).parent('li');
		
		par = elm.parent('ul').attr('class');
		
		var src = $(this).attr('src');
		var img = '<img src="' + src + '" class="img-responsive"/>';
		
		//start of new code new code
		var index = $(this).parent('li').index();   
		
		var tot = ($('ul.'+par+' li').length);
		
		var html = '';
		html += img;                
		var nextdisp = prevdisp = '';
		html += '<div style="height:25px;clear:both;display:block;">';
		if(tot>1)
		{
			if(tot==(index+1))
			{
				nextdisp = 'style = "display:none"';
				/*html += '<a class="controls next" href="'+ (index+2) + '" data-row="'+par+'" style="display:none">next &raquo;</a>';
				html += '<a class="controls previous" href="' + (index) + '" data-row="'+par+'">&laquo; prev</a>';*//*
			}
			else if(index==0)
			{
				prevdisp = 'style = "display:none"';
				/*html += '<a class="controls next" href="'+ (index+2) + '" data-row="'+par+'">next &raquo;</a>';
				html += '<a class="controls previous" href="' + (index) + '" data-row="'+par+'" style="display:none">&laquo; prev</a>';*//*
			}
			else
			{
				/*html += '<a class="controls next" href="'+ (index+2) + '" data-row="'+par+'">next &raquo;</a>';
				html += '<a class="controls previous" href="' + (index) + '" data-row="'+par+'">&laquo; prev</a>';	*//*
			}	
			html += '<a class="controls next" href="'+ (index+2) + '" data-row="'+par+'" '+nextdisp+'>next &raquo;</a>';
			html += '<a class="controls previous" href="' + (index) + '" data-row="'+par+'"'+prevdisp+'>&laquo; prev</a>';
			
		}	
		html += '</div>';
		
		$('#myModal').modal();
		$('#myModal').on('shown.bs.modal', function(){
			$('#myModal .modal-body').html(html);
			//new code
			$('a.controls').trigger('click');
		})
		$('#myModal').on('hidden.bs.modal', function(){
			$('#myModal .modal-body').html('');
		});
		
		
		
		
   });	
})
        
         
$(document).on('click', 'a.controls', function(){
	
	var index = $(this).attr('href');
	var rowname = $(this).attr('data-row');
	var src = $('ul.'+rowname+' li:nth-child('+ index +') img').attr('src');             
			
	$('.modal-body img').attr('src', src);
	
	var newPrevIndex = parseInt(index) - 1; 
	var newNextIndex = parseInt(newPrevIndex) + 2; 
	
	if($(this).hasClass('previous')){               
		$(this).attr('href', newPrevIndex); 
		$('a.next').attr('href', newNextIndex);
	}else{
		$(this).attr('href', newNextIndex); 
		$('a.previous').attr('href', newPrevIndex);
	}
	
	var total = $('ul.'+rowname+' li').length + 1; 
	//hide next button
	
	if(total === newNextIndex){
		$('a.next').hide();
	}else{
		$('a.next').show()
	}            
	//hide previous button
	if(newPrevIndex === 0){
		$('a.previous').hide();
	}else{
		$('a.previous').show()
	}
	
	
	return false;
});
								</script>
								*/ ?> 
							<?php
							   }
							}
						}
					}
				}
			}	
		}
	};	
?>
