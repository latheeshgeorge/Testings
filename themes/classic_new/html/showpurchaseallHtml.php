<?php
	/*############################################################################
	# Script Name 	: favcategory_showallHtml.php
	# Description 	: Page which holds the display logic for all products under fav category
	# Coded by 		: LSH
	# Created on	: 14-oct-2008
	# Modified by	: LH
	# Modified On	: 
	##########################################################################*/
	class purchaseshowall_Html
	{
		// Defining function to show the shelf details
		function Show_purchasedproductsall($ret_purchase)
		{
		global $Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,$Settings_arr,$ecom_themeid,$default_layout,$inlineSiteComponents,$customer_id,$ids;

			if($db->num_rows($ret_purchase)>0)
			{
				$pass_type = get_default_imagetype('midshelf');
				$prod_compare_enabled = isProductCompareEnabled();
			switch($Settings_arr['recentpurchased_prodlisting'])
			{ 
				case '1row':
				?>
					<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></div>
					<div class="shelfBtable">
					<?php
					if($tot_cnt>0)
					{
					?>
						<div class="pagingcontainertd">
						<?php 
							$path = '';
							$query_string .= "disp_id=".$_REQUEST['disp_id'];
							paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
						?>								
						</div>	
					<?php
					}
					while($row_prod = $db->fetch_array($ret_purchase))
					{
					?>
							<div class="shelfBtabletd">
							<div class="shelfBtabletdinner">
							<div class="shelfBprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" class="shelfBprodnamelink"><?php echo stripslashes($row_prod['product_name'])?></a></div>
							<div class="shelfBleft">
							<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
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
							<div class="compare_li">
							<?php
							if($prod_compare_enabled)
							{
								dislplayCompareButton($row_prod['product_id']);
							}
							?>	
							</div> 
							</div>
							<div class="shelfBmid">	
							<h6 class="shelfBproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6>
							<?php
							   $price_class_arr['ul_class'] 		= 'shelfBpriceul';
								$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
								$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
								$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
								$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
								echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
								if($row_prod['product_saleicon_show']==1)
								{
									$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
									if($desc!='')
									{
							?>
										<div class="shelfB_sale"><?php echo $desc?></div>
							<?php
									}
								}	
								if($row_prod['product_newicon_show']==1)
								{
									$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
									if($desc!='')
									{
							?>
										<div class="shelfB_newsale"><?php echo $desc?></div>		
							<?php
									}
								}	
								$module_name = 'mod_product_reviews';
								if(in_array($module_name,$inlineSiteComponents))
								{
									if($row_prod['product_averagerating']>=0)
									{
									?>
										<div class="shelfB_rate">
										<?php
											display_rating($row_prod['product_averagerating']);
										?>
										</div>
									<?php
									}
								}	
							?>							</div>
							<div class="shelfBright"> 
							<?php 
							if($row_prod['product_freedelivery']==1)
							{	
							?>
								<div class="shelfB_free"></div>
							<?php
							}
							if($row_prod['product_bulkdiscount_allowed']=='Y')
							{
							?>
								<div class="shelfB_bulk"></div>
							<?php
							}
							$frm_name = uniqid('purchaseall_');
							?>
							<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
							<input type="hidden" name="fpurpose" value="" />
							<input type="hidden" name="fproduct_id" value="" />
							<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
							<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
							<div class="infodivB">
							<div class="infodivBleft">
							<?php show_moreinfo($row_prod,'infolink')?></div>
							<div class="infodivBright">
							<?php
								$class_arr 					= array();
								$class_arr['ADD_TO_CART']	= 'infolinkB';
								$class_arr['PREORDER']		= 'infolinkB';
								$class_arr['ENQUIRE']		= 'infolinkB';
								show_addtocart($row_prod,$class_arr,$frm_name)
							?>
							</div>		
							</div>
							</form>
							</div>
							</div>
							</div>	
							</div>		
					<?php
						}
					?>	
					</div>	
					
				<?
				break;
				case '3row':
				?>
					<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['LOGIN_HOME']['LOGIN_HOME_PURCHASE_HEADING'];?></div>
					<div class="shelfAtable" >
					<?php
					if ($tot_cnt>0 and $tot_cnt>$prodperpage)
					{
					?>
						<div class="pagingcontainertd">
						<?php 
							$path = '';
							$query_string .= "";
							paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Products',$pageclass_arr); 	
						?>
						</div>
					<?php
					}
					$max_col = 3;
					$cur_col = 0;
					$prodcur_arr = array();	
					while($row_prod = $db->fetch_array($ret_purchase))
					{
						if($cur_col == 0)
						{ 
							echo '<div class="mid_shlf2_con_main">';
						}
						$cur_col ++;
					?>		
												
					<div class="shelfAtabletd">
					<div class="shelfAtabletdinner">
					<ul class="shelfAul">
					<li><h2 class="shelfAprodname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h2></li>
					<li class="compare_li">
					<?php
					if($prod_compare_enabled)
					{
						dislplayCompareButton($row_prod['product_id']);
					}?>
					</li>														
					<li class="shelfimg">
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
					?>
					</a>
					<?php
					if($row_prod['product_freedelivery']==1)
					{
					?>
					<div class="shelfA_free"></div>
					<?php
					}
					if($row_prod['product_bulkdiscount_allowed']=='Y')
					{
					?>
					<div class="shelfA_bulk"></div>
					<?php
					}
					?>
					</li>
					<?
					if($shelfData['shelf_showrating']==1)
					{
						$module_name = 'mod_product_reviews';
						if(in_array($module_name,$inlineSiteComponents))
						{
							if($row_prod['product_averagerating']>=0)
							{
							?>
								<li><div class="shelfB_rate">
							<?php
								display_rating($row_prod['product_averagerating']);
							?>
								</div></li>
							<?php
							}
						}	
					}	
					?>
						<li>
							<?php
							$price_class_arr['ul_class'] 		= 'shelfApriceul';
							$price_class_arr['normal_class'] 	= 'shelfAnormalprice';
							$price_class_arr['strike_class'] 	= 'shelfAstrikeprice';
							$price_class_arr['yousave_class'] 	= 'shelfAyousaveprice';
							$price_class_arr['discount_class'] 	= 'shelfAdiscountprice';
							echo show_Price($row_prod,$price_class_arr,'shelfcenter_3');
							show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
							?>	
						</li>
						<li><h6 class="shelfAproddes"><?php echo stripslashes($row_prod['product_shortdesc'])?></h6></li>
					</ul>
					<?
					if($row_prod['product_saleicon_show']==1)
					{
						$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
						if($desc!='')
						{
					?>	
						<div class="shelfA_sale"><?php echo $desc?></div>
					<?php
						}
					}
					if($row_prod['product_newicon_show']==1)
					{
						$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
						if($desc!='')
						{
					?>
						<div class="shelfA_newsale"><?php echo $desc?></div>
					<?php
						}
					}
					$frm_name = uniqid('best_');
					
					?>
					<div class="bonus_point"><?php show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points?>
					</div>
					<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
					<input type="hidden" name="fpurpose" value="" />
					<input type="hidden" name="fproduct_id" value="" />
					<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
					<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
					<div class="infodiv">
					<div class="infodivleft">
					<?php show_moreinfo($row_prod,'infolink')?></div>
					<div class="infodivright">
					<?php
					$class_arr 					= array();
					$class_arr['ADD_TO_CART']	= 'infolink';
					$class_arr['PREORDER']		= 'infolink';
					$class_arr['ENQUIRE']		= 'infolink';
					show_addtocart($row_prod,$class_arr,$frm_name)
					?> 
					</div>		
					</div>
					</form>
					</div>
					</div>
					<?
					if($cur_col>=$max_col)
						{
							$cur_col =0;
							echo "</div>";
						}
					}
					// If in case total product is less than the max allowed per row then handle that situation
					if($cur_col<$max_col)
					{
						if($cur_col!=0)
						{ 
							echo "</div>";
						} 
					}
					?>
					</div>
				<?
			    break;	
			 }	//endof switch					
			
			 }//end Checking

		}
	};	
?>