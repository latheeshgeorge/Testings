<?php
	/*############################################################################
	# Script Name 	: shelfgroupHtml.php
	# Description 	: Page which holds the display logic for middle shelves
	# Coded by 		: Joby
	# Created on	: 09-May-2011
	# Modified by	: 
	# Modified On	: 
	##########################################################################*/
	class shelfgroup_Html
	{
		// Defining function to show the shelf details
		function Show_Shelfgroup($cur_title,$shelfgroup_arr)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$shelf_for_inner;
			if (count($shelfgroup_arr))
			{
				//print_r($Settings_arr);
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
				//print_r($shelfgroup_arr);
				
				?>
                <div class="group_shlf_mid_con">
                <div class="group_shlf_mid_top"></div>
                <div class="group_shlf_mid_mid">
                
                <div class="group_shlf_mid_tab">
                        <ul class="group_protab">
                
				<?php

				foreach ($shelfgroup_arr as $k=>$shelfgroupData)
			/*1*/	{
					
					 $shelfgroup_id = $shelfgroupData['id'];
			
									
				   $sql_shelf_tab = "SELECT a.shelf_id,a.shelf_name
								FROM 
									product_shelf a LEFT JOIN shelf_group_shelf b 
									 ON (a.shelf_id = b.shelf_shelf_id ) 
								WHERE
									a.sites_site_id = $ecom_siteid 
									AND b.shelf_group_id  = $shelfgroup_id 
									AND a.shelf_hide = 0
									ORDER BY a.shelf_order 
								    ";
				$ret_shelf_tab = $db->query($sql_shelf_tab);
					if ($db->num_rows($ret_shelf_tab))// Check whether result is there
					{
						$cnt1 =1;
						while ($row_shelf_tab = $db->fetch_array($ret_shelf_tab))
						{
							?>
                            <?php
							 if($cnt1 == 1)
							 {
							 ?>
							 <li><div id="tsghead_<?php echo $row_shelf_tab['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>" onclick="show_current_tab('sgtab_<?php echo $row_shelf_tab['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>','tsghead_<?php echo $row_shelf_tab['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>','<?php echo $shelfgroup_id; ?>')" class="pro_groupseltableft"><span><?php echo stripslashes($row_shelf_tab['shelf_name']); ?></span></div></li>                                        
							<?php
							 }
							 else
							 {
							 ?>
							<li><div id="tsghead_<?php echo $row_shelf_tab['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>" onclick="show_current_tab('sgtab_<?php echo $row_shelf_tab['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>','tsghead_<?php echo $row_shelf_tab['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>','<?php echo $shelfgroup_id; ?>')" class="groupprotableft"><span><?php echo stripslashes($row_shelf_tab['shelf_name']); ?></span></div></li>        
							 <?php
							 }
							
							$cnt1++;
							}
								
						}		
					
					?>

                        </ul>
                 </div> 
                 
                <?php				
									
					$sql_shelf = "SELECT a.shelf_id,a.shelf_name,a.shelf_description,a.shelf_displaytype,shelf_showimage,shelf_showtitle,
										shelf_showdescription,shelf_showprice,shelf_currentstyle,shelf_activateperiodchange,
										shelf_displaystartdate,shelf_displayenddate,a.shelf_showrating,a.shelf_showbonuspoints    
								FROM 
									product_shelf a LEFT JOIN shelf_group_shelf b 
									 ON (a.shelf_id = b.shelf_shelf_id ) 
								WHERE
									a.sites_site_id = $ecom_siteid 
									AND b.shelf_group_id  = $shelfgroup_id 
									AND a.shelf_hide = 0
									ORDER BY a.shelf_order 
								    ";

									
					$ret_shelf = $db->query($sql_shelf);
					if ($db->num_rows($ret_shelf))// Check whether result is there
			/*2*/		{
				$cnt = 1;
						while ($shelfData = $db->fetch_array($ret_shelf))
			/*3*/		{
										
			?>

                 <?php
				 if($cnt == 1)
				 {
				 ?>
                 <div class="group_shlf_mid_outer_first" id="sgtab_<?php echo $shelfData['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>">                                         
                <?php
				 }
				 else
				 {
				 ?>
                 <div class="group_shlf_mid_outer" id="sgtab_<?php echo $shelfData['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>">        
                 <?php
				 }
				 ?>
				
				
				<?php		
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
							if($shelf_for_inner==true) // Case if call is to display shelf at the bottom on inner pages
								$shelfData['shelf_currentstyle']='inner_listing';
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
											a.product_freedelivery         
										FROM 
											products a,product_shelf_product b 
										WHERE 
											b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
											AND a.product_id = b.products_product_id 
											AND a.product_hide = 'N' 
										ORDER BY 
											$shelfsort_by $shelfsort_order 
										$Limit	";
						$ret_prod = $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{
							$comp_active = isProductCompareEnabled();
							$pass_type = get_default_imagetype('midshelf');
							// Number of result to display on the page, will be in the LIMIT of the sql query also

										
										
										?>
										
                                     
                                      <div class="group_shlf_mid_hdr"><?php stripslashes(trim($shelfData['shelf_description'])); ?> </div>
                                       <div class="group_shlf_mid_cont">
                                        
                                        
                                        <div class="group_link_pdt_con">
                                            <div class="group_link_nav"><a onmouseout="stopMe()" onmouseover="scrollDivRight('container<?php echo $shelfData['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>')" href="#null"><img src="     <?php echo url_site_image('arrow-left.gif');?>">
                                            </a></div>
                                            <div class="group_link_pdt_inner" id="container<?php echo $shelfData['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>">
                                            <div style="width: 830px;" id="scroller">    
											 <?php 
                                            $prodcur_arr = array();
                                            while($row_prod = $db->fetch_array($ret_prod))
                                            {
                                                $prodcur_arr[] = $row_prod;
                                                $HTML_title = $HTML_image = $HTML_desc = '';
                                                $HTML_sale = $HTML_new = $HTML_compare = $HTML_rating = '';
                                                $HTML_price = $HTML_bulk= $HTML_bonus = $HTML_compare = $HTML_freedel= $HTML_bonus_bar = '';
                                                
                                                    $HTML_title = '<div class="group_link_name"><a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">'.stripslash_normal($row_prod['product_name']).'</a></div>';
                                                
                                                
                                                    $HTML_image ='<a href="'.url_product($row_prod['product_id'],$row_prod['product_name'],1).'" title="'.stripslash_normal($row_prod['product_name']).'">';
                                                    // Calling the function to get the image to be shown
                                                    $pass_type ='image_thumbcategorypath';
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
                                                
                                                    $price_class_arr['class_type']          = 'div';
                                                    $price_class_arr['normal_class']        = 'normal_group_pdt_priceA';
                                                    $price_class_arr['strike_class']        = 'normal_group_pdt_priceB';
                                                    $price_class_arr['yousave_class']       = 'normal_group_pdt_priceC';
                                                    $price_class_arr['discount_class']      = 'normal_group_pdt_priceC';
                                                    $HTML_price = show_Price($row_prod,$price_class_arr,'shelfcenter_3');
                                                
                                                ?>        
                                                   <div class="group_link_pdt">
                                                       <div class="group_link_image">
                                                         <?php echo $HTML_image; ?>
                                                        </div>
                                                        <?php echo $HTML_title; ?>
                                                        <div class="normal_group_pdt_price">
                                                        <?php echo $HTML_price; ?>
                                                        
                                                        </div>
                                                  </div>
                                                    
                                                    
                                                    
                                                    
                                            <?php
                                            }
                                            ?>       
 
                                             </div>
                                            </div>
                                            <div class="group_link_nav"> <a onmouseout="stopMe()" onmouseover="scrollDivLeft('container<?php echo $shelfData['shelf_id']; ?>_<?php echo $shelfgroup_id; ?>','430')" href="#null"><img src="<?php echo url_site_image('arrow-right.gif');?>"></a></div>
                                            </div>
                                        
                                        
                                     
                                        
                                        </div> 
                                        
                                        
                                       
                                       
                                       
                                       
                                       
                                       
                                      
										
										
										
			
										
					<?php
		
						}
					}
					else
					{
						removefrom_Display_Settings($shelfData['shelf_id'],'mod_shelf');
					}
				
				
				?>
				 
                </div>

                
		
			<?php
				$cnt++;
			/*3*/		}		
			/*2*/	}	
					
			/*1*/ }
				
			?>
            </div> 
                <div class="group_shlf_mid_bottom"></div> 
                </div>
                <?php	
				
				
				
			}	
		}
	};	
?>