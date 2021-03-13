<?php
	/*#################################################################
	# Script Name 	: components.php
	# Description 	: Page which holds the html script for various components of the site
	# Coded by 		: Sny
	# Created on		: 05-Dec-2007
	# Modified by	: Sny
	# Modified On	: 04-Jul-2008
	#################################################################*/
	class components
	{
		// ####################################################################################################
		// Function which holds the display logic for static pages
		// ####################################################################################################
		function mod_staticgroup($grp_array,$title)
		{
			global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
			
			/* Sony Jul 01, 2013 */
			global $discthm_group_static_array;
			$more_conditions = '';
			$more_conditionsa = '';
			if(count($discthm_group_static_array))
			{
				$more_conditions = " AND page_id IN ( ".implode(',',$discthm_group_static_array).") ";
				$more_conditionsa = " AND a.page_id IN ( ".implode(',',$discthm_group_static_array).") ";
			}
			
			/* Sony Jul 01, 2013 */
			
			$Captions_arr['STATIC_PAGES'] 	= getCaptions('STATIC_PAGES');
			$add_statcondition 				= " AND a.pname <> 'Home'";
			// section to be used with caching
			$cache_type = '';
			switch($position)
			{
				case 'top':
					$cache_type		= 'comp_topstatgroup';	
				break;
				case 'topband':
					$cache_type		= 'comp_topbandstatgroup';	
				break;
				case 'bottom':
					$cache_type		= 'comp_bottomstatgroup';	
				break;
				case 'left':
					$cache_type		= 'comp_leftstatgroup';	
				break;
				case 'right':
					$cache_type		= 'comp_rightstatgroup';	
				break;
				case 'seo-section':
					$cache_type		= 'comp_seostatgroup';	
				break;
			}// Cache checking section
				$cache_exists 	= false;
				$cache_required	= false;
				if ($Settings_arr['enable_caching_in_site']==1 and $cache_type!='')
				{
					$cache_required = true;
					if (exists_Cache($cache_type,$grp_array[0]['group_id']))
					{
						$content_cache = getcontent_Cache($cache_type,$grp_array[0]['group_id']);
						if ($content_cache) // if cache exists show it
						{
							echo $content_cache;
							$cache_exists = true;
						}
					}
				} 
				// Do the following only if caching is not enabled or cache does not exists
				if ($cache_exists==false)
				{
					if($cache_required)// if caching is required start recording the output
					{
						ob_start();
					}	
					// ############## Top ##############
					if ($position == 'top') // Case if value of position is top;
					{
							$show_top = $show_bottom = 0;
						?>					
							<div class="static_table_td">
								<ul class="staticlink">  
					<?php
						if(count($grp_array))
						{
							//Iterating through the group array to fetch the pages to be shown.
							foreach ($grp_array as $k=>$grpData)
							{
								$sql_pg = "SELECT a.page_id,a.title,a.content,a.page_type,a.page_link,page_link_newwindow    
											FROM 
												static_pages a,static_pagegroup_static_page_map b 
											WHERE
												a.sites_site_id = $ecom_siteid 
												AND b.static_pagegroup_group_id=".$grpData['group_id']." 
												AND a.page_id=b.static_pages_page_id 
												AND static_pages_hide = 0 
												AND hide = 0 
												$add_statcondition 
												$more_conditionsa 
											ORDER BY 
												static_pages_order ASC";
								
								$ret_pg = $db->query($sql_pg);
								$cnt = $db->num_rows($ret_pg);
								//if($show_bottom==0)
									{
										if ($grpData['group_showhomelink']==1)
										{
								?>
											<li><a href="<?php url_link('')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a></li>
								<?php
										}
										//$show_bottom=1;	
									}
								
									if ($grpData['group_showhelplink']==1 )
									{
					?>		
										<li><a href="<?php url_link('help.html')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></a></li>
					<?php
									}
									if ($grpData['group_showfaqlink']==1 )
									{
					?>		
										<li><a href="<?php url_link('faq.html')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_FAQ']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_FAQ']?></a></li>
					<?php
									}
									if ($grpData['group_showxmlsitemaplink']==1 )
									{
									?>
										<li><a href="<?php url_link('sitemap.xml')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></a></li>
									<?php	
									}
									 
									if ($grpData['group_showsitemaplink']==1 )
									{
					?>							
										<li><a href="<?php url_link('sitemap.html')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></a></li>
					<?php		
									}
									if ($grpData['group_showsavedsearchlink']==1)
									{
					?>							
										<li><a href="<?php url_link('saved-search.html')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></li>
					<?php		
									}
								while ($row_pg = $db->fetch_array($ret_pg))
								{
									$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
								?>
										<li><a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="static" title="<?php echo stripslashes($row_pg['title'])?>" <?php echo $target?>><?php echo stripslashes($row_pg['title']);?></a></li>
								<?php
								}
							}
							
						}		
						?>			
								</ul>  
							</div>
					
						<?php
						
					}// End of top
					if ($position == 'topband') // Case if value of position is topband;
					{
							$show_top = $show_bottom = 0;
						?> 
							<div class="top_st_lnk" align="right">
								<ul class="staticlink_main">
					<?php
						if(count($grp_array))
						{
							
							//Iterating through the group array to fetch the pages to be shown.
							foreach ($grp_array as $k=>$grpData)
							{
								$sql_pg = "SELECT a.page_id,a.title,a.content,a.page_type,a.page_link,page_link_newwindow    
											FROM 
												static_pages a,static_pagegroup_static_page_map b 
											WHERE
												a.sites_site_id = $ecom_siteid 
												AND b.static_pagegroup_group_id=".$grpData['group_id']." 
												AND a.page_id=b.static_pages_page_id 
												AND static_pages_hide = 0 
												AND hide = 0 
												$add_statcondition 
												$more_conditionsa 
											ORDER BY 
												static_pages_order DESC";
								
								$ret_pg = $db->query($sql_pg);
								$cnt = $db->num_rows($ret_pg);
								//if($show_bottom==0)
								{
									if ($grpData['group_showhomelink']==1)
									{
							?>
										<li><a href="<?php url_link('')?>" class="static_main" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a></li>
							<?php
									}
									//$show_bottom=1;	
								}
								while ($row_pg = $db->fetch_array($ret_pg))
								{
									$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
								?>
										<li><a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="static_main" title="<?php echo stripslashes($row_pg['title'])?>" <?php echo $target?>><?php echo stripslashes($row_pg['title']);?></a></li>
								<?php
								}
								
									if ($grpData['group_showhelplink']==1 )
									{
					?>		
										<li><a href="<?php url_link('help.html')?>" class="static_main" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></a></li>
					<?php
									}
									if ($grpData['group_showfaqlink']==1 )
									{
					?>		
										<li><a href="<?php url_link('faq.html')?>" class="static_main" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_FAQ']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_FAQ']?></a></li>
					<?php
									}
									if ($grpData['group_showxmlsitemaplink']==1 )
									{
									?>
										<li><a href="<?php url_link('sitemap.xml')?>" class="static_main" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></a></li>
									<?php	
									}
									 
									if ($grpData['group_showsitemaplink']==1 )
									{
					?>							
										<li><a href="<?php url_link('sitemap.html')?>" class="static_main" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></a></li>
					<?php		
									}
									if ($grpData['group_showsavedsearchlink']==1)
									{
					?>							
										<li><a href="<?php url_link('saved-search.html')?>" class="static_main" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></li>
					<?php		
									}
								
							}
							
						}		
						?>			
								</ul> 
								</div>					
						<?php
						
					}// End of top
					if ($position == 'bottom') // Case if value of position is bottom;
					{
						if(count($grp_array))
						{
						?>
						<div class="static_con_bottom">
							<ul id="nav_bottom">
								<?php
								//Iterating through the group array to fetch the pages to be shown.
								$i=0;
								$show_top = $shop_bottom = 0;
								foreach ($grp_array as $k=>$grpData)
								{
								$sql_pg = "SELECT a.page_id,a.title,a.content,a.page_type,a.page_link,page_link_newwindow  
										FROM 
											static_pages a,static_pagegroup_static_page_map b 
										WHERE
											a.sites_site_id = $ecom_siteid 
											AND b.static_pagegroup_group_id=".$grpData['group_id']." 
											AND a.page_id=b.static_pages_page_id 
											AND static_pages_hide = 0 
											AND hide = 0 
											$add_statcondition 
											$more_conditionsa 
										ORDER BY 
											static_pages_order";
								$ret_pg = $db->query($sql_pg);
								if($show_top==0)
								{
								if ($grpData['group_showhomelink']==1)
								{
									?>
									<li class="item1_bot_non">
									<a href="<?php url_link('')?>" class="Category" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?></a>
									</li>
									<?php			
								}
									$show_top = 0;
								}	
								
								while ($row_pg = $db->fetch_array($ret_pg))
								{
								$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
									?>
									<li class="item1_bot_non">
									<a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="bottomlink" title="<?php echo stripslash_normal($row_pg['title'])?>" <?php echo $target?>><?php echo stripslash_normal($row_pg['title']);?></a>
									</li>
									<?php
								}
								}
								?>
								<?php			
								if ($grpData['group_showsitemaplink']==1)
								{
									?>
									<li class="item1_bot_non">
									<a href="<?php url_link('sitemap.html')?>" class="Category" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SITEMAP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SITEMAP'])?></a>
									</li>
									<?php			
								}
								if ($grpData['group_showxmlsitemaplink']==1 )
								{			
									?>
									<li class="item1_bot_non">
									<a href="<?php url_link('sitemap.xml')?>" class="Category" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP'])?></a>
									</li>
									<?php									
								}
								if ($grpData['group_showfaqlink']==1)
								{
									?>
									<li class="item1_bot_non">
									<a href="<?php url_link('faq.html')?>" class="Category" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?></a>
									</li>
									<?php			
								}
								if ($grpData['group_showhelplink']==1)
								{
									?>
									<li class="item1_bot_non">
									<a href="<?php url_link('help.html')?>" class="Category" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HELP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HELP'])?></a>
									</li>
									<?php			
								}
								if ($grpData['group_showsavedsearchlink']==1)
								{
									?>
									<li class="item1_bot_non">
									<a href="<?php url_link('saved-search.html')?>" class="Category" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH'])?></a></li>
									<?php			
								}
								?>
							</ul>
						</div>
						<?php	
						}
					}// End of bottom
					elseif ($position=='left' or $position =='right') // ############## Left / Right ##############
					{
						$prev_grp = 0;
						if(count($grp_array))
						{
							$show_top = $shop_bottom = 0;
							//Iterating through the group array to fetch the pages to be shown.
							foreach ($grp_array as $k=>$grpData)
							{
								$sql_pg = "SELECT a.page_id,a.title,a.content,a.page_type,a.page_link,page_link_newwindow   
											FROM 
												static_pages a,static_pagegroup_static_page_map b 
											WHERE
												a.sites_site_id = $ecom_siteid 
												AND b.static_pagegroup_group_id=".$grpData['group_id']." 
												AND a.page_id=b.static_pages_page_id 
												AND static_pages_hide = 0 
												AND hide = 0 
												$add_statcondition 
												$more_conditionsa 
											ORDER BY 
												static_pages_order";
								$ret_pg = $db->query($sql_pg);
								$cnt	= $db->num_rows($ret_pg);
								/*if ($cnt>0)
								{*/
								?>
									<ul class="staticleft"> 
								<?php
							/*	}*/
							        if($prev_grp != $grpData['group_id'])
										{
											if ($grpData['group_hidename']==0) // Decide whether or not to show the groupname
											{
									?>
												 <li class="staticleftheader"><?php echo stripslashes($title)?></li>
									<?php
											}
											$prev_grp = $grpData['group_id'];
										}
							   // if($show_top==0)
										{
											if ($grpData['group_showhomelink']==1)
											{
									?>
												<li><a href="<?php url_link('')?>" class="staticleftlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a></li>
									<?php		
											}
											//$show_top = 1;
										}	
								while ($row_pg = $db->fetch_array($ret_pg))
								{
									$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
								?>
										<li><a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="staticleftlink" title="<?php echo stripslashes($row_pg['title'])?>" <?php echo $target?>><?php echo stripslashes($row_pg['title']);?></a></li>
								<?php
								}
								//if($show_bottom==0)
								{
									if ($grpData['group_showsitemaplink']==1)
									{
							?>
										<li><a href="<?php url_link('sitemap.html')?>" class="staticleftlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></a></li>
							<?php		
									}
									if ($grpData['group_showxmlsitemaplink']==1 )
									{	
							?>
										<li><a href="<?php url_link('sitemap.xml')?>" class="staticleftlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></a></li>
							<?php		
									}
									if ($grpData['group_showhelplink']==1)
									{
							?>
										<li><a href="<?php url_link('help.html')?>" class="staticleftlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></a></li>
							<?php		
									}
									if ($grpData['group_showsavedsearchlink']==1)
									{
						?>
										<li><a href="<?php url_link('saved-search.html')?>" class="staticleftlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></li>
						<?php			
									}
									//$show_bottom = 1;
								}	
							}
						}
						/*if ($cnt>0)
						{*/
						?>
							</ul>
					<?php
						/*}*/
					}// End of left
					elseif ($position=='seo-section')
					{
						if(count($grp_array))
						{
						?>
							<div class="footerlink">
						<ul>								
						<?php
							//Iterating through the group array to fetch the pages to be shown.
							$i=0;
							$show_top = $shop_bottom = 0;
							foreach ($grp_array as $k=>$grpData)
							{
									$sql_pg = "SELECT a.page_id,a.title,a.content,a.page_type,a.page_link,page_link_newwindow  
											FROM 
												static_pages a,static_pagegroup_static_page_map b 
											WHERE
												a.sites_site_id = $ecom_siteid 
												AND b.static_pagegroup_group_id=".$grpData['group_id']." 
												AND a.page_id=b.static_pages_page_id 
												AND static_pages_hide = 0 
												AND hide = 0 
												$add_statcondition 
												$more_conditionsa 
											ORDER BY 
												static_pages_order";
								$ret_pg = $db->query($sql_pg);
								if ($grpData['group_hidename']==0) // Decide whether or not to show the groupname
											{
									?>
												 <li><span><?php echo stripslashes($title)?></span></li>
									<?php
											}
								if($show_top==0)
								{
									if ($grpData['group_showhomelink']==1)
									{
						?>
										<li><a href="<?php url_link('')?>" class="bottomlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a></li>
						<?php			
									}
										$show_top = 0;
								}	
								
								while ($row_pg = $db->fetch_array($ret_pg))
								{
									$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
								?>
										<li><a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="bottomlink" title="<?php echo stripslashes($row_pg['title'])?>" <?php echo $target?>><?php echo stripslashes($row_pg['title']);?></a></li>
								<?php
								}
							}
						?>
						<?php			
								//if($show_bottom==0)
								{
									if ($grpData['group_showsitemaplink']==1)
									{
						?>
										<li><a href="<?php url_link('sitemap.html')?>" class="bottomlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></a></li>
						<?php			
									}
									if ($grpData['group_showxmlsitemaplink']==1 )
									{			
									?>
										<li><a href="<?php url_link('sitemap.xml')?>" class="bottomlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></a></li>
									<?php									
									}
									if ($grpData['group_showfaqlink']==1)
									{
						?>
										<li><a href="<?php url_link('faq.html')?>" class="bottomlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_FAQ']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_FAQ']?></a></li>
						<?php			
									}
									if ($grpData['group_showhelplink']==1)
									{
						?>
										<li><a href="<?php url_link('help.html')?>" class="bottomlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></a></li>
						<?php			
									}
									if ($grpData['group_showsavedsearchlink']==1)
									{
						?>
										<li><a href="<?php url_link('saved-search.html')?>" class="bottomlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></li>
						<?php			
									}
									//$show_bottom = 1;
								}	
						?>			
								</ul>
								</div>
							
						<?php	
						}
					}					
					if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
					{
						$content = ob_get_contents();
						ob_end_clean();
						save_Cache($cache_type,$grp_array[0]['group_id'],$content);
						echo $content;
					}
				}	
		}
		
		
		
		// ####################################################################################################
		// Function which holds the display shoppingcart component
		// ####################################################################################################
		function mod_shoppingcart($title)
		{
			global $Captions_arr,$ecom_hostname,$inlineSiteComponents,$Settings_arr,$db,$ecom_siteid,$position;
			$cust_id 					= get_session_var("ecom_login_customer");
			$checkout_link = get_Checkoutlink(1);
			if($_REQUEST['req']=='cart') // if current page is related to cart then call cartcalc function to refresh the cart totals and number of items in cart
			{
				$cartData = cartCalc(); // calling cart calc 
			}	
			$cart_tot = print_price(get_session_var('cart_total'),true);
			$pass_tot = print_price(get_session_var('cart_total'),true,true);	
			if ($position=='topband1') // Best sellers is allowed in left or right panels
			{	
			?>
            <div id="shoppingcart_ajax_container">
			<div class="cartwrap">
			<div class="cart_left_img"><img src="<?PHP echo url_site_image('icon_cart.png')?>" width="63" height="44" alt="cart" /></div>
			<div class="top_cart_txt"><?php echo  (get_session_var('cart_total_items'))?get_session_var('cart_total_items'):0?>&nbsp;&nbsp;<?php echo stripslash_normal($Captions_arr['COMMON']['ITEMS_IN_CART'])?> - <?php echo $cart_tot?></div>
			<div class="buttons_inline">
			<ul><li>
			<a href="#" class="cart_view_link" onclick="handle_checkout_submit('<?php echo $ecom_hostname?>',0,'<?php echo $pass_tot?>')"  title="<?php echo stripslash_normal($Captions_arr['COMMON']['CHECK_OUT'])?>"><img src="<?PHP echo url_site_image('bt_checkout.png')?>"  alt="checkout" width="79" height="27" border="0"/></a>
			<li>
			<a href="#" onclick="gobackto_cart('<?php echo ($protectedUrl)?base64_encode($ecom_hostname):$ecom_hostname?>')" class="cart_view_link" title="<?php echo stripslash_normal($Captions_arr['COMMON']['VIEW_CART'])?>"><img src="<?PHP echo url_site_image('view_cart.png')?>" alt="view cart" width="79" height="27" border="0" /></a>

			</ul>

			</div>

			</div>
			<div class="hotspot-container">
		    <a href="http://<?php echo $ecom_hostname?>/halal-certification-c76929.html" class="hotspot"></a>
			</div>
            </div>		
			<?php
			}
			if($position=='right' || $position=='left')
			{
			?>
			<td align="right" valign="top" class="top_cart_x">				
				<div id="shoppingcart_ajax_sidecontainer"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="top_cart_x_table">
			  <tr>
			  	<td><a href="#" onclick="gobackto_cart('<?php echo ($protectedUrl)?base64_encode($ecom_hostname):$ecom_hostname?>')" class="cart_view_link" title="<?php echo stripslash_normal($Captions_arr['COMMON']['VIEW_CART'])?>"><div class="top_basket_hdr">Your Basket</div></a><div class="top_basket_txt"> <?php echo  (get_session_var('cart_total_items'))?get_session_var('cart_total_items'):0?>&nbsp;&nbsp;<?php echo stripslash_normal($Captions_arr['COMMON']['ITEMS_IN_CART'])?> - <?php echo $cart_tot?></div></td>
				<td><a href="#" class="cart_view_link" onclick="handle_checkout_submit('<?php echo $ecom_hostname?>',0,'<?php echo $pass_tot?>')"  title="<?php echo stripslash_normal($Captions_arr['COMMON']['CHECK_OUT'])?>"><img src="<?php url_site_image('chk-out.gif') ?>"  /></a></td>
			  </tr>
			  </table></div>
			  </td>	
				
			<?php
			}
		}
		
		
		// ####################################################################################################
		// Function which holds the display logic for product category groups
		// ####################################################################################################
		function mod_productcatgroup($grp_array,$title)
		{ 
			global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$ecom_themeid;
			
			/* Sony Jul 01, 2013 */
			global $disgthm_group_cat_array;
			$more_conditions = '';
			$more_conditionsa = '';
			if(count($disgthm_group_cat_array))
			{
				$more_conditions = " AND category_id IN ( ".implode(',',$disgthm_group_cat_array).") ";
				$more_conditionsa = " AND a.category_id IN ( ".implode(',',$disgthm_group_cat_array).") ";
			}
			else
			{
				$more_conditions =  " AND display_to_guest = 1 ";
				$more_conditionsa = " AND a.display_to_guest = 1 ";
				
			}
			
			/* Sony Jul 01, 2013 */
			
			// Getting the required sort by field and sort order
				$sort_by 	=  $Settings_arr['category_orderfield'];
				if($sort_by=='cname') $sort_by = 'category_name';
				$sort_by 	=  ($sort_by=='custom')?'b.category_order':$sort_by;
				$sort_order =  $Settings_arr['category_orderby'];
				$sort_str	= " $sort_by $sort_order ";
				$Captions_arr['STATIC_PAGES'] 	= getCaptions('STATIC_PAGES');

				// Get the parent of selected category
				$req_cat = $_REQUEST['category_id'];
				if ($req_cat)
				{
					$sql_parent = "SELECT parent_id 
									FROM 
										product_categories 
									WHERE 
										category_id=$req_cat 
									LIMIT 
										1";
					$ret_parent = $db->query($sql_parent);
					if ($db->num_rows($ret_parent))
					{
						list($parent) = $db->fetch_array($ret_parent);
					}
				}	
			// section to be used with caching
			switch($position)
			{
				case 'topband':
					$cache_type		= 'comp_topcatgroup';	
				break;
				case 'bottom':
					$cache_type		= 'comp_bottomcatgroup';	
				break;
				case 'left':
					$cache_type		= 'comp_leftcatgroup';	
				break;
				case 'right':
					$cache_type		= 'comp_rightcatgroup';	
				break;
			}// Cache checking section
				$cache_exists 	= false;
				$cache_required	= false;
				if ($Settings_arr['enable_caching_in_site']==1 and !$_REQUEST['category_id'])
				{
					$cache_required = true;
					if (exists_Cache($cache_type,$grp_array[0]['catgroup_id']))
					{
						$content_cache = getcontent_Cache($cache_type,$grp_array[0]['catgroup_id']);
						if ($content_cache) // if cache exists show it
						{
							echo $content_cache;
							$cache_exists = true;
						}
					}
				}
				// Do the following only if caching is not enabled or cache does not exists
				if ($cache_exists==false)
				{
					if($cache_required)// if caching is required start recording the output
					{
						ob_start();
					}	
					// ############## Top Band ##############
					if ($position == 'top') // Case if value of position is bottom;
						{
							$startpnt = 0;
							if(count($grp_array))
							{
								//Iterating through the group array to fetch the pages to be shown.
								foreach ($grp_array as $k=>$grpData)
								{
									$sql_cat = "SELECT a.category_id,a.category_name,parent_id 
												FROM 
													product_categories a,product_categorygroup_category b 
												WHERE
													a.sites_site_id = $ecom_siteid 
													AND b.catgroup_id=".$grpData['catgroup_id']." 
													AND a.category_id=b.category_id 
													AND a.category_hide = 0 
													$more_conditionsa  
												ORDER BY 
													$sort_str";
									$ret_cat = $db->query($sql_cat);
									if ($db->num_rows($ret_cat))
									{
										// Check the listing type for categories in category group
										//if ($grpData['catgroup_listtype']== 'Menu')// case if categories are to be shown in list menu
										{ 
										?>							
											
												<div class="category_con_top">
												<div class="category_left_top"></div>
                                                <div class="category_mid_top">
                                                <?php 
												/*if($ecom_siteid=='76')
												{
												?>
												
                                                 <div class="category_home_button">
                                                 <a href="<?php url_link('')?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?>" ><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?></a>
                                                 </div>
                                                 <?php 
												 }*/
												 ?>
												
<?php
											
											$liouter = '';
											
												while ($row_cat = $db->fetch_array($ret_cat))
												{
													
											        $class = '';
													//$liouter .= '<li class="item1_top"><a href="'.url_category($row_cat['category_id'],$row_cat['category_name'],1).'" class="category_menu_top" title="'.stripslash_normal($row_cat['category_name']).'"><span>'.ucwords(stripslash_normal($row_cat['category_name'])).'</span></a></a>';
													// Check whether there exists first level subcategories for current category
													$sql_subcat = "SELECT category_id,category_name 
																	FROM 
																		product_categories 
																	WHERE 
																		parent_id =".$row_cat['category_id']." 
																		AND sites_site_id = $ecom_siteid 
																		AND category_hide = 0 
																		$more_conditions 
																	ORDER BY 
																		category_order";
													$ret_subcat = $db->query($sql_subcat);
													$liouter_sub1 = '';
													$liouter_sub = '';
													$cnt_sub  = 0;
													if($db->num_rows($ret_subcat))
													{
													?>
														<!-- dropdown or flyout 1 -->
														<?php
														while($row_subcat = $db->fetch_array($ret_subcat))
														{   
															$cnt_sub++;														
															$liouter_sub .='<li><a href="'.url_category($row_subcat['category_id'],$row_subcat['category_name'],1).'">'.stripslashes($row_subcat['category_name']).'</a></li>';
														
														}									
														
													}
													 if($cnt_sub==0)
														{
															$class= "item1_top_non";
														}
														else
														{
														   $class = "item1_top";
														}
													if($row_cat['category_id']==76935) // case of contact us
													{
														$liouter .= '<li class="'.$class.'"><a href="http://blog.kqf-foods.com" class="category_menu_top" title="'.stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_NEWS']).'"><span>'.ucwords(stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_NEWS'])).'</span></a>';	
													}
													$liouter .= '<li class="'.$class.'"><a href="'.url_category($row_cat['category_id'],$row_cat['category_name'],1).'" class="category_menu_top" title="'.stripslash_normal($row_cat['category_name']).'"><span>'.ucwords(stripslash_normal($row_cat['category_name'])).'</span></a>';
													if($liouter_sub!='')
													{
													$liouter .='<ul style="width:'.$row_cat['category_subcat_width'].'px;z-index:999;position:absolute">'.$liouter_sub;

													
													$liouter .='</ul>';
												    }

													//$liouter .=$liouter.$liouter_sub;
													$liouter .='</li>'; //echo $liouter;
												}
												
												
											?>
											    <?php
											    if($liouter!='')
											    {
												 ?>
												 
												 <ul id="nav_top">
											 <li class="item1_top_non"><a href="<?php url_link('')?>" class="toplia" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a></li>
												 <?php
											  echo $liouter;
											    ?>

												</ul>
												<?php /*<ul id="nav_top">
												<li class="item1_top_non"><a href="http://blog.kqf-foods.com" target="_blank" class="category_menu_top" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_NEWS']?></a></li>
												</ul>
												*/ ?> 
												<?php
											}
												?>
												</div>
												<div class="category_right_top"></div>
												</div>
											
										<?php		
										}
									}
								}
							}
						
						}// End of bottom
					if ($position == 'topband') // Case if value of position is topband;
					{
							if(count($grp_array))
							{
								// Check whether catgroup drop down is supported
								$sql_style	= "SELECT theme_top_cat_dropdownmenu_support FROM themes WHERE theme_id=".$ecom_themeid." LIMIT 1";
								$ret_style 	= $db->query($sql_style);
								if ($db->num_rows($ret_style))
								{
									$row_style	= $db->fetch_array($ret_style);
									$subcatdropdownsupport = $row_style['theme_top_cat_dropdownmenu_support'];
								}
								//Iterating through the group array to fetch the pages to be shown.
								foreach ($grp_array as $k=>$grpData)
								{
									$sql_cat = "SELECT a.category_id,a.category_name,parent_id,b.category_subcat_width,a.category_showimageofproduct 
												FROM 
													product_categories a,product_categorygroup_category b 
												WHERE
													a.sites_site_id = $ecom_siteid 
													AND b.catgroup_id=".$grpData['catgroup_id']." 
													AND a.category_id=b.category_id 
													AND a.category_hide = 0 
													$more_conditionsa  
												ORDER BY 
													$sort_str";
									$ret_cat = $db->query($sql_cat);
									if ($db->num_rows($ret_cat))
									{ 
										// Check the listing type for categories in category group
										//if ($grpData['catgroup_listtype']== 'Menu')// case if categories are to be shown in list menu
										//{
										if ($grpData['catgroup_show_subcat_indropdown']== 1 and $subcatdropdownsupport)// case if categories are to be shown in list menu
										{
											if ($grpData['catgroup_show_subcat_indropdown_subcount']== 1)// case if 1 levels of subcategories to be displayed
											{
											?>
												<div class="category_con">
												<div class="category_left"></div>
                                                <div class="category_mid">
                                                <?php 
												/*if($ecom_siteid=='76')
												{
												?>
												
                                                 <div class="category_home_button">
                                                 <a href="<?php url_link('')?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?>" ><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?></a>
                                                 </div>
                                                 <?php 
												 }*/
												 ?>
												<ul id="nav">
											<?php
												while ($row_cat = $db->fetch_array($ret_cat))
												{
											?>
													<li class="item1"><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="category_menu" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><span><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></span></a></a>
													<?php
													// Check whether there exists first level subcategories for current category
													$sql_subcat = "SELECT category_id,category_name 
																	FROM 
																		product_categories 
																	WHERE 
																		parent_id =".$row_cat['category_id']." 
																		AND sites_site_id = $ecom_siteid 
																		AND category_hide = 0 
																		$more_conditions 
																	ORDER BY 
																		category_order";
													$ret_subcat = $db->query($sql_subcat);
													if($db->num_rows($ret_subcat))
													{
													?>
														<!-- dropdown or flyout 1 -->
														<ul style="width:<?php echo $row_cat['category_subcat_width']?>px;z-index:999;position:absolute">
														<?php
														while($row_subcat = $db->fetch_array($ret_subcat))
														{
														?>
															<li><a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1)?>"><?php echo stripslashes($row_subcat['category_name'])?></a></li>
														<?php
														}
														?>
														</ul>
													<?php
													}
													?>
													</li>
											<?php
												}
											?>
												</ul>
												</div>
												<div class="category_right"></div>
												</div>
											<?php	
											}
											elseif ($grpData['catgroup_show_subcat_indropdown_subcount']== 2)// case if 2 levels of subcategories to be displayed
											{ 
										?>
											<div class="category_con">
												<div class="category_left"></div>
													<div class="category_mid">
                                                    <?php 
														/*if($ecom_siteid=='76')
														{
														?>
														 <div class="category_home_button">
                                                 			<a href="<?php url_link('')?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?>" ><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?></a>
														 </div>
														 <?php 
														 }*/
														 ?>
														<ul  id="main_navigation"> 
															<?php
																$center_point =3;
																$cursubcat_cnt = 0;
																$pass_type = 'image_bigpath';
																?>
																<li><a href="<?php url_link('')?>" class="category_home" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><img src="<?php url_site_image('home-icn.png')?>" alt="home"/></a></li>
																<?php
																if($grpData['catgroup_id'] == 353)
																{
																	$cat_idarr  = array(77987,77988,77150);
															    }
															    else
															    {
																    $cat_idarr  = array();
																
																}
																while ($row_cat = $db->fetch_array($ret_cat))
																{
																		$img_url = '';
																		if ($row_cat['category_showimageofproduct']==0) // Case to check for images directly assigned to category
																		{
																			// Calling the function to get the type of image to shown for current 
																			//$pass_type = get_default_imagetype('subcategory');	
																			// Calling the function to get the image to be shown
																			$img_arr = get_imagelist('prodcat',$row_cat['category_id'],$pass_type,0,0,1);
																			if(count($img_arr))
																			{
																				$img_url = url_root_image($img_arr[0][$pass_type],1);
																			}
																		}
																		else // Case of check for the first available image of any of the products under this category
																		{
																			// Calling the function to get the id of products under current category with image assigned to it
																			$cur_prodid = find_AnyProductWithImageUnderCategory($row_cat['category_id']);
																			if ($cur_prodid)// case if any product with image assigned to it under current category exists
																			{
																				// Calling the function to get the type of image to shown for current 
																				//$pass_type = get_default_imagetype('subcategory');
																				// Calling the function to get the image to be shown
																				$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
																				
																				if(count($img_arr))
																				{
																					$img_url = url_root_image($img_arr[0][$pass_type],1);
																				}
																			}
																		}
																		if($img_url!='')
																			$img_str = "background: url(".$img_url.")  right bottom no-repeat;";
																		else
																			$img_str = '';
																		if(!in_array($row_cat['category_id'],$cat_idarr))
																		{	
																?>
																	
								
																	<li>
																	<a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="category_menu" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><span><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></span></a>
																<?php
																	// Check whether there exists first level subcategories for current category
																	$sql_subcat = "SELECT category_id,category_name 
																					FROM 
																						product_categories 
																					WHERE 
																						parent_id =".$row_cat['category_id']." 
																						AND sites_site_id = $ecom_siteid 
																						AND category_hide = 0 
																						$more_conditions 
																					ORDER BY 
																						category_order";
																	$ret_subcat = $db->query($sql_subcat);
																	$num_rows	= $db->num_rows($ret_subcat);
																	if($num_rows)
																	{
																		if($num_rows>=4)
																		{
																			$nav_cls 	= 'nav-c4';
																		}
																		else
																		{
																			$nav_cls 	= 'nav-c4'; //$nav_cls 	= 'nav-c'.$num_rows;
																		}
																		
										
																		if($cursubcat_cnt==4)
																		{
																			$align_cls	= 'align-center4';
																		}
																		else
																		{
																			if($cursubcat_cnt<=$center_point)
																			{
																				$align_cls	= 'align-left';
																			}	
																		}	
																		
																		if(($cursubcat_cnt==$center_point))// or ($cursubcat_cnt==($center_point+1)))
																		{
																			if($num_rows>=4)
																				$align_cls	= 'align-center4';
																			else
																				$align_cls	= 'align-center4';;//$align_cls	= 'align-center3';
																		}
																		elseif($cursubcat_cnt>=$center_point)
																			$align_cls	= 'align-right';
																			
																		$cursubcat_cnt++;
																		$cust_id = get_session_var("ecom_login_customer");
																		if($cust_id)
																		{
																			if($cursubcat_cnt==2 or $cursubcat_cnt==3)
																				$align_cls	= 'align-center4';
																		}	
																		else
																		{
																			if($cursubcat_cnt==3)
																				$align_cls	= 'align-center4';
																		}	
																?>
																		<div class="dropdown <?php echo $align_cls.' '.$nav_cls?>"><?php //echo $cursubcat_cnt?>
																		<div class="dropdown_inner_cls">
																		<div class='mainimage_cls' style=" z-index:999; float:left;<?php echo $img_str?> width:100%; height:100%">
																<?php
																		$subcat_cnt 	= 0;
																		$subcat_maxcnt 	= 3;				
																		while($row_subcat = $db->fetch_array($ret_subcat))
																		{
																			if($subcat_cnt==0)
																				echo '<div class="topsubcat_cont">';
																		?>
																			<ul>
																			<li class="cat_subcat"><a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1)?>"><?php echo stripslashes($row_subcat['category_name'])?></a></li>
																			<?php
																				// Check whether first level subcategories exists for current subcategory
																				$sql_subsubcat = "SELECT category_id,category_name 
																					FROM 
																						product_categories 
																					WHERE 
																						parent_id =".$row_subcat['category_id']." 
																						AND sites_site_id = $ecom_siteid 
																						AND category_hide = 0 
																						$more_conditions 
																					ORDER BY 
																						category_order";
																				$ret_subsubcat = $db->query($sql_subsubcat);
																				$num_subrows	= $db->num_rows($ret_subsubcat);
																				if($num_subrows)
																				{
																					while ($row_subsubcat = $db->fetch_array($ret_subsubcat))
																					{
																			?>			
																						<li><a href="<?php url_category($row_subsubcat['category_id'],$row_subsubcat['category_name'],-1)?>"><?php echo stripslashes($row_subsubcat['category_name'])?></a></li>
																			<?php
																					}
																				}
																			?>
																			</ul>
																<?php	
																			$subcat_cnt++;
																			if($subcat_cnt>=$subcat_maxcnt)
																			{
																				echo '</div>';
																				$subcat_cnt = 0;
																			}
																		}
																		if ($subcat_cnt>0 and $subcat_cnt<$subcat_maxcnt)
																			echo '</div>';
																?>
																		<div class="menulogo_div"><?php $this->Show_shop_image_forcat($row_cat['category_id']); /*?>Shop Logos div<?php */?></div>
																		</div>
																		</div>
																		</div>	
																<?php		
																	}		
																?>	
																	</li>
																<?
																	}
																}
																if($grpData['catgroup_id'] == 353)
																{
																?>
																<span class="top_band_stat_cat">
																   <li>
																	<a href="<?php url_category(77987,"Caterers",-1)?>" class="category_menu" title="<?php echo stripslash_normal("Caterers")?>"><span><?php echo ucwords(stripslash_normal("Caterers"));?></span></a>
																	</li>
																	<li>
																	<a href="<?php url_category(77988,"Retailers",-1)?>" class="category_menu" title="<?php echo stripslash_normal("Retailers")?>"><span><?php echo ucwords(stripslash_normal("Retailers"));?></span></a>
																	</li>
																	<li>
																	<a href="<?php url_category(77150,"Schools",-1)?>" class="category_menu" title="<?php echo stripslash_normal("Schools")?>"><span><?php echo ucwords(stripslash_normal("Schools"));?></span></a>
																	</li>
																	<?php
																	$sql_cat = "SELECT category_hide FROM product_categories WHERE category_id=79271 LIMIT 1";
																	$ret_cat = $db->query($sql_cat);
																	if ($db->num_rows($ret_cat))
																	{
																		$row_cat = $db->fetch_array($ret_cat);
																		if($row_cat['category_hide']==0)
																		{
																	?>
																			<li>
																			<a href="<?php url_category(79271,"London",-1)?>" class="category_menu" title="<?php echo stripslash_normal("London")?>"><span><?php echo ucwords(stripslash_normal("London"));?></span></a>
																			</li>
																	<?php
																	}
																	}
																	?>
																	</span>
																<?php
																}

															?>
															   
														</ul>
													</div>
												<div class="category_right"></div>
											</div>
										<?php	
											}	
										}
										else
										{
											
										?>
											<div class="category_con">
												<div class="category_left"></div>
													<div class="category_mid">
														<ul  id="main_navigation"> 
															<?php
																$center_point = 3;
																$cursubcat_cnt = 1;
																while ($row_cat = $db->fetch_array($ret_cat))
																{
																	?>
																	<li>
																	<a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="category_menu" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><span><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></span></a>
																	</li>
																<?
																}
															?>
														</ul>
													</div>
												<div class="category_right"></div>
											</div> 
										<?php		
										
										}
										//}
									}
								}
							}
					}// End of top
						if ($position == 'bottom') // Case if value of position is bottom;
						{
							$startpnt = 0;
							if(count($grp_array))
							{
								//Iterating through the group array to fetch the pages to be shown.
								foreach ($grp_array as $k=>$grpData)
								{
									$sql_cat = "SELECT a.category_id,a.category_name,parent_id 
												FROM 
													product_categories a,product_categorygroup_category b 
												WHERE
													a.sites_site_id = $ecom_siteid 
													AND b.catgroup_id=".$grpData['catgroup_id']." 
													AND a.category_id=b.category_id 
													AND a.category_hide = 0 
													$more_conditionsa  
												ORDER BY 
													$sort_str";
									$ret_cat = $db->query($sql_cat);
									if ($db->num_rows($ret_cat))
									{
										// Check the listing type for categories in category group
										if ($grpData['catgroup_listtype']== 'Menu')// case if categories are to be shown in list menu
										{
										?>
											<div class="cat_con_bottom">
												<ul >
													<?php
													while ($row_cat = $db->fetch_array($ret_cat))
													{
														$startpnt++;
													?>
													<li>
													<a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="Category" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></a>
													</li>
													<?php
													}
													?>
												</ul>
											</div>
										<?php		
										}
									}
								}
							}
						
						}// End of bottom
						elseif ($position=='left' || $position=='right') // ############## Left ##############
						{
							$prev_grp = 0;
							if(count($grp_array))
							{
								//Iterating through the group array to fetch the pages to be shown.
								foreach ($grp_array as $k=>$grpData)
								{
								$sql_cat = "SELECT a.category_id,a.category_name,parent_id,b.category_displaytype,b.category_islink,a.category_subcatlisttype   
												FROM 
													product_categories a,product_categorygroup_category b 
												WHERE
													a.sites_site_id = $ecom_siteid 
													AND b.catgroup_id=".$grpData['catgroup_id']." 
													AND a.category_id=b.category_id 
													AND a.category_hide = 0 
													$more_conditionsa  
												ORDER BY 
													$sort_str";
									$ret_cat = $db->query($sql_cat);
									if ($db->num_rows($ret_cat))
									{
										// Check the listing type for categories in category group
										if ($grpData['catgroup_listtype']== 'Menu')// case if categories are to be shown in list menu
										{
										?>
										
											<div class="catsidemenu_outer">
											<div class="arrowlistmenu">
										<?php
											while ($row_cat = $db->fetch_array($ret_cat))
											{
												if($prev_grp != $grpData['catgroup_id'])
												{
													if ($grpData['catgroup_hidename']==0) // Decide whether or not to show the groupname
													{
											?>
														<span class="catsideemenu_h5"><?php echo stripslashes($title)?></span>
											<?php
													}
														$prev_grp = $grpData['catgroup_id'];
												}
												// Start:- to check for whether the categories under the group is displayed as a heading with/without a link
											
											
											$sql_child = "SELECT category_id,category_name,default_catgroup_id
																		FROM 
																			product_categories 
																		WHERE 
																			parent_id=".$row_cat['category_id']." 
																			AND sites_site_id=$ecom_siteid 
																			AND category_hide = 0 
																			$more_conditions 
																		ORDER BY category_order";
											$ret_child = $db->query($sql_child);
											if ($db->num_rows($ret_child))
												$maincat_class = 'menuheader expandable';
											else
												$maincat_class = 'menuheader_blank';
											?>
											
											<div class="<?php echo $maincat_class?>"><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" title="<?php echo stripslashes($row_cat['category_name'])?>"><?php echo stripslashes($row_cat['category_name']);?></a></div>
											<?php
											
											// End:- to check for whether the categories under the gorup is displayed as a heading with/without a link
												if ($row_cat['category_subcatlisttype']=='List' or $row_cat['category_subcatlisttype']=='Both')
												{
													//if (($row_cat['category_id']==$_REQUEST['category_id']) or ($row_cat['category_id']==$parent))
													{	
														if ($parent == $row_cat['category_id'])
															$comp_catid = $parent;
														else	
															$comp_catid = $row_cat['category_id'];
														// Check whether any child exists for current category
														/*$sql_child = "SELECT category_id,category_name,default_catgroup_id
																		FROM 
																			product_categories 
																		WHERE 
																			parent_id=".$comp_catid." 
																			AND sites_site_id=$ecom_siteid 
																			AND category_hide = 0 
																		ORDER BY category_order";
														$ret_child = $db->query($sql_child);*/
														if ($db->num_rows($ret_child))
														{
														?>
															<ul class="categoryitems">
														<?php	
															while ($row_child = $db->fetch_array($ret_child))
															{
														?>
																	<li><a href="<?php url_category($row_child['category_id'],$row_child['category_name'],-1)?>" title="<?php echo stripslashes($row_child['category_name'])?>"><?php echo stripslashes($row_child['category_name']);?></a></li>
														<?php		
															}
														?>
															</ul>
														<?php	
														}
													}
												}	
											}
										?>
										</div>
											</div>
										<?php		
										}
										else
										{
											if ($grpData['catgroup_hidename']==0) // Decide whether or not to show the groupname
											{
												echo '<ul class="category"><li class="categoryheader">'.$title.'</li>';
											}
										?>
											<li>
											<select name="prodcatgroup_<?php echo $grpData['catgroup_id']?>" onchange="handle_dropdownval_sel(this.value)">
											<option value="">-- <?php echo $Captions_arr['COMMON']['SELECT']?> -- </option>
										<?php
												// Case if categories are to be shown in dropdown box
												while ($row_cat = $db->fetch_array($ret_cat))
												{
										?>
													<option value="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" <?php echo ($row_cat['category_id']==$_REQUEST['category_id'])?'selected="selected"':''?>><?php echo stripslashes($row_cat['category_name'])?></option>
										<?php		
												}
										?>
											</select>
											</li>
											</ul>
										<?php	
										}
									}	
								}
							}
						}
					if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
					{
						$content = ob_get_contents();
						ob_end_clean();
						save_Cache($cache_type,$grp_array[0]['catgroup_id'],$content);
						echo $content;
					}
				}
		}
		// ####################################################################################################
		// Function which holds the display logic for quick search section
		// ####################################################################################################
		function mod_quicksearch($title)
		{
			global $Captions_arr,$position,$ecom_hostname,$protectedUrl,$enable_auto_search,$Settings_arr;
			$checkout_link = get_Checkoutlink(1);
			if($_REQUEST['req']=='cart') // if current page is related to cart then call cartcalc function to refresh the cart totals and number of items in cart
			{
				$cartData = cartCalc(); // calling cart calc 
			}	
			
			if ($position=='topband1') // show only if position value is top
			{
				$cart_tot = print_price(get_session_var('cart_total'),true);
				$pass_tot = print_price(get_session_var('cart_total'),true,true);
				
				/* Code for search auto complete product list starts here */
				/*
				if($enable_auto_search == 1)
				{
					echo '
							<script type="text/javascript">
							var $acnc = jQuery.noConflict();
							$acnc().ready(function() {
								$acnc("#quick_search").autocomplete("'.url_head_link('includes/autocomplete_product_search.php',1).'", {
									width: 220,
									matchContains: true,
									//mustMatch: true,
									//minChars: 0,
									//multiple: true,
									//highlight: false,
									//multipleSeparator: ",",
									selectFirst: false
								});
							});
							</script>
						 ';
				}
				*/ 
				/* Code for search auto complete product list ends here */
		?>
		<div class="searchwrap">
		<form method="post" name="frm_quicksearch" class="frm_cls" action="<?php url_link('search.html')?>">

		<div class="searchbg">
		<input name="quick_search" type="text" class="inputAB" id="quick_search"  value=""/>
		</div>

		<div id="button">
		<input name="button_submit_search" type="submit" value="Search"  src="<?php echo url_site_image('bt_search.png')?>" value="<?php echo $Captions_arr['COMMON']['SEARCH_GO']?>" onclick="show_wait_button(this,'Please wait...')" width="91" height="38" />
		</div>
		<input name="button_submit_search" type="submit" class="buttongrayB" id="button3" value="<?php echo $Captions_arr['COMMON']['SEARCH_GO']?>" onclick="show_wait_button(this,'Please wait...')" />
		<input type="hidden" name="search_submit" value="search_submit" />
		</form>
		</div>
      <?php
			if($Settings_arr['enable_search_autocomplete'] == 1)
					{ return;
						?>
						<script type="text/javascript">
						jQuery.noConflict();
						var $j = jQuery;
						$j("#quick_search").autocomplete({
						source: "<?php echo url_head_link('includes/autocomplete_product_search.php',1) ?>",
						minLength: 1,
						select: function(event, ui) {
						if(ui.item){
						$j(event.target).val(ui.item.value);
						}
						//submit the form
						//$j(event.target.form).submit();
						}
						});
						</script>
						<?php	
					}
					
				
			}	
		}
		// ####################################################################################################
		// Function which holds the display logic for customer login
		// ####################################################################################################
		function mod_customerlogin($title)
		{
			global $Captions_arr,$ecom_hostname,$inlineSiteComponents,$Settings_arr,$db,$ecom_siteid,$loginUrl,$ecom_fb_enable;
			$Captions_arr['CUST_LOGIN'] = getCaptions('CUST_LOGIN');
			$cust_id 					= get_session_var("ecom_login_customer");
			/* FB login script */
			$cust_fbid 				= get_session_var("ecom_login_customer_fbid");
			//echo $cust_fbid;echo "<br>";
			 
			if (!$cust_id) // case customer is not logged in
			{
				
				
			/*if($Settings_arr['showcustomerlogin_as_banner']==1) // Decide whether customer login is to be displayed as banner or in normal style
			{
			?>
			<div class="login_banner"> 
							  <div class="signup_btn"><a href="<?php url_link('registration.html')?>" title="<?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['NEW_USER'])?>"><img src="<?php url_site_image('signup-btn.gif')?>" border="0" /></a></div>
					          <div class="login_btn"><a href="<?php url_link('custlogin.html')?>"><img src="<?php url_site_image('login-btn.gif')?>"  border="0"/></a></div>
				</div>
                <!--FB Login Button -->
				<?php if($ecom_fb_enable == 1) { ?>
                <div class="fblogin_btn"><a href="<?php echo $loginUrl;?>"><img src="<?php echo url_site_image('facebook_login.png');?>" /></a></div>
                <?php } ?>
			<?php
			}
			*/ 
			//else
			{
				$hide_newuser 		=  $Settings_arr['hide_newuser'];
				$hide_forgotpass 	=  $Settings_arr['hide_forgotpass'];
		?>
				<form name="frm_custlogin" id="frm_custlogin" method="post" action="" onsubmit="return validate_login(this)" class="frm_cls">
				<table border="0" cellpadding="0" cellspacing="0" class="logintable">
		<?php   
		
				if ($title)
				{
		?>
					<tr>
						<td colspan="2" class="logintableheader"><?php echo $title?></td>
					</tr>
		<?php
				}
		?>		
				<tr>
					<td class="logintablecontent"><?php echo $Captions_arr['CUST_LOGIN']['EMAIL']?></td>
					<td align="right" valign="top" class="logintablecontentright"><input name="custlogin_uname" type="text" class="inputA" id="custlogin_uname" size="15" /></td>
				</tr>
				<tr>
					<td class="logintablecontent"><?php echo $Captions_arr['CUST_LOGIN']['PASSWORD']?></td>
					<td align="right" valign="top" class="logintablecontentright"><input name="custlogin_pass" type="password" class="inputA" id="custlogin_pass" size="15" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="right" valign="top" class="logintablecontentright"> <input name="custologin_Submit" type="submit" class="submit" id="custologin_Submit" value="<?php echo $Captions_arr['CUST_LOGIN']['LOGIN']?>" />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="right">
					<?php
						if($hide_newuser==0) // check whether new user link is disabled from main shop settings
						{
					?>
							<a href="<?php url_link('registration.html')?>" class="loginlink" title="<?php echo $Captions_arr['CUST_LOGIN']['NEW_USER']?>"><?php echo $Captions_arr['CUST_LOGIN']['NEW_USER']?></a>
					<?php
						}
						if($hide_forgotpass==0) // check whether the forgot password link is disabled from main shop settings
						{
					?>
							<a href="<?php url_link('forgotpassword.html')?>" class="loginlink" title="<?php echo $Captions_arr['CUST_LOGIN']['FORGOT_PASS']?>"><?php echo $Captions_arr['CUST_LOGIN']['FORGOT_PASS']?></a>
					<?php
						}
					?>		
					</td>
				</tr>
				</table>
				<input type="hidden" name="redirect_back" value="0" /> 
				</form>
                <!--FB Login Button -->
				<?php if($ecom_fb_enable == 1) { ?>
                <div class="fblogin_btn"><a href="javascript:void(0);" onclick="javascript:window.open('<?php echo $loginUrl;?>','Login','width=500,height=300,resizable=no,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=no,copyhistory=no');"><img src="<?php echo url_site_image('facebook_login.png');?>" /></a></div>
                <?php } ?>
		<?php	
		        }
			}	
		}
		
		// ####################################################################################################
		// Function which holds the display logic for call back request 
		// ####################################################################################################
		function mod_callback($title)
		{
		?>
			<div class="gift_use_banner" align="center"><a href="<?php url_link('callback.html')?>"><img src="<?php url_site_image('sidebanner4.png')?>" alt="Call Back" title="Call back request" border="0" /></a></div>
		<?php		
		}
		// ####################################################################################################
		// Function which holds the display logic for best sellers
		// ####################################################################################################
		function mod_bestsellers($ret_main,$title,$display_id)
		{
			global $ecom_siteid,$db,$ecom_hostname,$position,$Captions_arr,$Settings_arr;
			
			if($_REQUEST['category_id']== 77150 or $_REQUEST['category_id']== 77210 or $_REQUEST['category_id']== 77211) // avoiding the display in left or right for schools and trade
			{
					return;
			}
			
			
			if ($position=='left' or $position=='right') // Best sellers is allowed in left or right panels
			{	
			if($position == 'left')
			{
				$scrollup_id	=	"scroll_up_left";
				$scrolldown_id	=	"scroll_down_left";
				$scrollbox_id	=	"scroll_box_left";
				$scroll_id		=	"div_scroll1_left";
			}	
			if($position == 'right')
			{
				$scrollup_id	=	"scroll_up_right";
				$scrolldown_id	=	"scroll_down_right";
				$scrollbox_id	=	"scroll_box_right";
				$scroll_id		=	"div_scroll1_right";
			}
			if ($db->num_rows($ret_main))
			{
								//$pass_type = get_default_imagetype('combshelf');								
$pass_type = 'image_thumbpath';
						
			$width_one_set 	= 170;
			$total_cnt		= $db->num_rows($ret_main);

			$min_number_req	= $total_cnt;
			$min_width_req 	= $width_one_set * $min_number_req;
			$calc_width		= $total_cnt * $width_one_set;
			if($calc_width < $min_width_req)
				$div_width = $min_width_req;
			else
				$div_width = $calc_width; 
			if($title) // check whether title exists
			{
			?>
				<div class="shlf_slr_table_hdrA"><?php echo $title?></div>
			<?php
			}
			?>
<div class="scrolling_wrap">
		<div class="link_pdt_outr">
		<div class="link_pdt_top"></div>
		<div class="link_pdt_conts">
		<div class="det_link_pdt_con">
		<div class="det_link_nav"><a href="#null" onmouseover="scrollDivRight('containerD')" onmouseout="stopMe()"><img src="<?php url_site_image('arrow_left.png')?>" alt="arrow left"></a></div>
		<div id="containerD" class="det_link_pdt_inner">
		<div id="scroller" style="width:<?php echo $div_width?>px">
		<?php
		$cnts = $db->num_rows($ret_prod);
		while($row_prod = $db->fetch_array($ret_main))
			{ 
			?>
			<div class="scrollimg"><ul>
			<li>
			<?php
			//if($shelfData['shelf_showimage']==1) // whether image is to be displayed
			{   
			?>
			<div class="shelf_thump_wrap">
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
			</div>
			<?php 
			}
			?>
			<div class="thump_details">
			<div class="productname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div>
			<?php
			//if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
							{
						?>
						<?php
								$price_class_arr['ul_class'] 		= 'price';
								$price_class_arr['normal_class'] 	= 'productprice';
								$price_class_arr['strike_class'] 	= 'retailprice';
								$price_class_arr['yousave_class'] 	= 'yousaveprice';
								$price_class_arr['discount_class'] 	= 'discountprice';
								echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
								//show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
						?>
						<?php
							}	
							
						?>
                        <?php 
							//if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
							{
						?>
							<!--<div class="prod_list_des"><?php echo stripslashes($row_prod['product_shortdesc'])?><?php show_moreinfo($row_prod,'list_more')?></div>-->
						<?php
							}
							
							
							/*
						?>
                            
                            <div class="moreinfo">
                            <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">More Info</a>
                            </div>
                            */?> 
                            <div class="addtocartWrap">
                                <div class="prod_list_buy">
                                <?php 
								$frm_name = uniqid('catdet_');
								?>
								<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
								<input type="hidden" name="fpurpose" value="" />
								<input type="hidden" name="fproduct_id" value="" />
								<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
								<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
								<?php
								$class_arr['ADD_TO_CART']       = '';
								$class_arr['PREORDER']          = '';
								$class_arr['ENQUIRE']           = '';
								$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
								$class_arr['QTY']               = ' ';
								$class_td['QTY']				= 'prod_list_buy_a';
								$class_td['TXT']				= 'prod_list_buy_b';
								$class_td['BTN']				= 'prod_list_buy_c';
								echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
								?>
                                </form>
                                </div>
                            </div>
			</div>

			</li>
			</ul></div>
			<?php
		}
		?>
		</div>
		</div>
		<div class="det_link_nav"> <a href="#null" onmouseover="scrollDivLeft('containerD','<?php echo $div_width?>')" onmouseout="stopMe()"><img src="<?php url_site_image('arrow_right.png')?>" alt="arrow right" /></a></div>
		</div>
        </div>
        <div align="right" class="newshelfbottom">
							<a href="<? url_link('bestsellers'.$display_id.'.html')?>" class="viewallnewshelf" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a>
							</div>
        <div class="link_pdt_bottom"></div>
	  </div>
	  </div>
							<?php
				}
			}	
		}
		// ####################################################################################################
		// Function which holds the display logic for best sellers
		// ####################################################################################################
		function mod_compare_products($ret_compare_pdts,$title='Compare PrOducts')
		{
			global $ecom_siteid,$db,$ecom_hostname,$position,$Captions_arr,$Settings_arr;
			return;
			if ($position=='left' or $position=='right') // Best sellers is allowed in left or right panels
			{	
				if ($db->num_rows($ret_compare_pdts))
				{
					if ($Settings_arr['product_compare_enable']==1)
					{
				
				?>
						<form name="compare_list" id="compare_list" action="" method="post">	
						<input type="hidden" name="remove_compareid"  value="" />
				<?php 
						if($_REQUEST['disp_id'])
						{
				?>
						<input type="hidden" name="disp_id" id="disp_id" value="<?=$_REQUEST['disp_id']?>">

				<? 
						}
				?>
						<ul class="bestsellers">   
				<?php
							if($title) // check whether title exists
							{
				?>
								<li class="bestsellersheader"><?php echo $title?></li>
				<?php
							}
							while ($row_compare_pdts = $db->fetch_array($ret_compare_pdts))
							{
				?>	
								<li><h1><img src="<?php url_site_image('delete.gif')?>" onclick="document.common_compare_list.remove_compareid.value=<?=$row_compare_pdts['product_id']?>; if(confirm('Are You sure You want to remove the product from the compare list')){ document.common_compare_list.submit()};" alt="Remove" title="Remove" />&nbsp;<a href="<?php url_product($row_compare_pdts['product_id'],$row_compare_pdts['product_name'],-1)?>" class="bestsellerslink" title="<?php echo stripslashes($row_compare_pdts['product_name'])?>"><?php echo stripslashes($row_compare_pdts['product_name'])?></a></h1></li>
		
				<?php
							}
							if(count($_SESSION['compare_products'])>1)
							{	
				?>
								<li><h1 align="right"> <a href="<?php url_link('compare_products.html')?>" class="showall" title="<?php echo $Captions_arr['COMMON']['COMPARE_PRODUCTS']?>" target="_blank"><?php echo $Captions_arr['COMMON']['COMPARE_PRODUCTS']?></a> </h1> </li>
				<? 			
							}
				?>				
							</ul></form>
				<?php	
						}		
				}
			}		
		}
		// ####################################################################################################
		// Function which holds the display logic for combo deals 
		// ####################################################################################################
		function mod_combo($comb_arr,$title)
		{
			global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr,$position,$Settings_arr;
			if ($position=='left' or $position =='right')// Combo deal is allowed only in left or right panel
			{
				// Getting the settings for combo deals from settings table
				// Deciding the sort by field
				$combosort_by			= $Settings_arr['product_orderfield_combo'];
				switch ($combosort_by)
				{
					case 'custom': // case of custom ordering
						$combosort_by		= 'b.comboprod_order';
					break;
					case 'product_name': // case of order by product name
						$combosort_by		= 'a.product_name';
					break;
					case 'price': // case of order by price
						$combosort_by		= 'a.product_webprice';
					break;
					default: // by default order be product name
						$combosort_by		= 'a.product_name';
					break;
				};
				$combosort_order		= $Settings_arr['product_orderby_combo'];
				//Iterating through the combo array to fetch the product to be shown.
				foreach ($comb_arr as $k=>$combData)
				{	
						// Check whether combo_activateperiodchange is set to 1
						$active 	= $combData['combo_activateperiodchange'];
						if($active==1)
						{
							$proceed	= validate_component_dates($combData['combo_displaystartdate'],$combData['combo_displayenddate']);
						}
						else
							$proceed	= true;	
							//section to check whether the comboproducts is in customer group
				/* Sony Jul 01, 2013 */
				global $discthm_group_prod_array;
				if(count($discthm_group_prod_array))
				{
				$sql_prod_chk = "SELECT a.product_id  
											FROM 
												products a,combo_products b 
											WHERE 
												b.combo_combo_id = ".$combData['combo_id']." 
												AND a.product_id = b.products_product_id 
												AND a.product_hide='N' 
											ORDER BY 
												$combosort_by $combosort_order";
					$ret_prod_chk = $db->query($sql_prod_chk);
					$prod_array   = array(); 
					while($row_prod_chk=$db->fetch_array($ret_prod_chk))
					{
					  $prod_array[] = $row_prod_chk['product_id'];
					}
					$inter_array = array();
					$inter_array = array_intersect($discthm_group_prod_array,$prod_array);
					//print_r($inter_array);

					//print_r($prod_array);
					if(!array_diff($prod_array, $inter_array) && !array_diff($inter_array, $prod_array))
					{
					   $proceed_combo1 = true;
					}
					else
					{
					   $proceed = false;
					}
				 }
					//end of customer group check
						if ($proceed)
						{
							switch($position)
							{
								case 'left':
									$cache_type		= 'comp_leftcombo';	
								break;
								case 'right':
									$cache_type		= 'comp_rightcombo';	
								break;
							}	
							$cache_exists 	= false;
							$cache_required	= false;
							if ($Settings_arr['enable_caching_in_site']==1)
							{
								$cache_required = true;
								if (exists_Cache($cache_type,$combData['combo_id']))
								{
									$content_cache = getcontent_Cache($cache_type,$combData['combo_id']);
									if ($content_cache) // if cache exists show it
									{
										echo $content_cache;
										$cache_exists = true;
									}
								}
							}
							// Do the following only if caching is not enabled or cache does not exists
							if ($cache_exists==false)
							{
								if($cache_required)// if caching is required start recording the output
								{
									ob_start();
								}
								// Get the list of products to be shown in current combo deal
								$sql_prod = "SELECT a.product_id,a.product_name,a.product_default_category_id 
											FROM 
												products a,combo_products b 
											WHERE 
												b.combo_combo_id = ".$combData['combo_id']." 
												AND a.product_id = b.products_product_id 
												AND a.product_hide='N' 
											ORDER BY 
												$combosort_by $combosort_order";
								$ret_prod = $db->query($sql_prod);
								if ($db->num_rows($ret_prod))
								{
									if ($position =='right' || $position =='left')
									{
						?>
						<div class="dealwrap">
  <div class="dealtop"><img src="<?php url_site_image('deal_top.png')?>" width="210" height="9" /></div>
  <div class="dealbg">
									<div class="lf_combodeal">
									<?php
										if ($title and $combData['combo_hidename']==0)
										{
									?>			
											<div class="lf_combodeal_top"><?php echo $title?></div>
									<?php
										}
										?>
									<div class="lf_combodeal_middle">
										<?php
											$bundle_price = $combData['combo_bundleprice'];
											$cur_cnt=1;
											$i=0;
											$tot_cnt = $db->num_rows($ret_prod);
											
										while ($row_prod = $db->fetch_array($ret_prod))
										{
										$cur_cnt++;
												if($i!=0 && $i!=$tot_cnt)
												{
											?>
												<div class="lf_combodeal_plus"></div>
											<?php
												}
												$i++;
											?>		
											 <div class="lf_combodeal_img"> 
											  <a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" title="<?php echo $Captions_arr['COMMON']['SHOW_DET']?>"><?php
												// Calling the function to get the type of image to shown for current 
												$pass_type = 'image_iconpath';
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
									<?php
										}
									?>		<div class="lf_combodeal_separation"></div>
										  <div class="lf_combodeal_price">Bundled Price: <?php echo print_price($bundle_price)?></div>
									  </div>
											<div class="lf_combodeal_bottom"> <a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" class="lf-combodeal-showall" title="<?php echo $Captions_arr['COMMON']['SHOW_DET']?>"><?php echo $Captions_arr['COMMON']['SHOW_DET']?></a> </div>
										</div> 
										</div>
  <div class="dealbottom"></div>
  </div>

						<?php	
									}
								}
						
								if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
								{
									$content = ob_get_contents();
									ob_end_clean();
									save_Cache($cache_type,$combData['combo_id'],$content);
									echo $content;
								}
							}	
					}
				}	
			}	
		}
		// ####################################################################################################
		// Function which holds the display logic for shelves 
		// ####################################################################################################
		function mod_shelf($shelf_arr,$title)
		{
			global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr,$position;			
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			
			/* Sony Jul 01, 2013 */
			global $discthm_group_prod_array,$discthm_group_shelf_array;
			$more_conditions = '';
			if(count($discthm_group_prod_array))
			{
				$more_conditions = " AND product_id IN ( ".implode(',',$discthm_group_prod_array).") ";
			}
			
			
			/* Sony Jul 01, 2013 */
			if($position =='left' or $position == 'right')
			{
					
				// Getting the settings for shelves from settings table
				// Deciding the sort by field
				$shelfsort_by			= $Settings_arr['product_orderfield_shelf'];
				
				// Getting the limit of products to be shown in left of right components for the shelf
				$limit					= $Settings_arr['product_maxshelfprod_in_component'];
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
					default: // by default order by product name
					$shelfsort_by		= 'a.product_name';
					break;
				};
				$shelfsort_order	= $Settings_arr['product_orderby_shelf'];
				//Iterating through the shelf array to fetch the product to be shown.
				foreach ($shelf_arr as $k=>$shelfData)
				{
					if($_REQUEST['category_id']== 77150 or $_REQUEST['category_id']== 77210 or $_REQUEST['category_id']== 77211) // avoiding the display of shelf in left or right for schools and trade
					{
						if($shelfData['shelf_id']==598)
							return;
					}	
					// Check whether shelf_activateperiodchange is set to 1
					$active 	= $shelfData['shelf_activateperiodchange'];
					if($active==1)
					{
						$proceed	= validate_component_dates($shelfData['shelf_displaystartdate'],$shelfData['shelf_displayenddate']);
					}
					else
						$proceed	= true;	
					/* Sony Jul 01, 2013 */	
					if(count($discthm_group_shelf_array))
					{
						if(!in_array($shelfData['shelf_id'],$discthm_group_shelf_array))
						{
							$proceed = false;
						}
					}	
					/* Sony Jul 01, 2013 */
					if ($proceed)
					{
						// Get the list of products to be shown in current shelf
						$sql_prod	=	"SELECT a.product_id, a.product_name, a.product_default_category_id, a.product_webprice, a.product_show_cartlink,
												a.product_discount, a.product_bulkdiscount_allowed, a.product_discount_enteredasval, a.product_applytax, 
												a.product_bonuspoints, a.product_variables_exists, a.product_variablesaddonprice_exists, a.product_preorder_allowed, 
												a.product_show_enquirelink, a.product_webstock, a.product_webprice, a.product_shortdesc,
												a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text, 
												a.product_variablestock_allowed,a.product_alloworder_notinstock,a.product_total_preorder_allowed,a.product_variables_exists 
											FROM 
												products a,product_shelf_product b 
											WHERE 
												b.product_shelf_shelf_id = ".$shelfData['shelf_id']." 
											AND a.product_id = b.products_product_id 
											AND a.product_hide = 'N' 
											AND a.sites_site_id =$ecom_siteid 
											$more_conditions 
											ORDER BY 
												$shelfsort_by $shelfsort_order 
											LIMIT 
												$limit";
						
						$ret_prod = $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{	
							if($shelfData['shelf_currentstyle']=='special') // case of christmas layout
							{
								if($position == 'left')
								{
									$scrollup_id	=	"scroll_up_left";
									$scrolldown_id	=	"scroll_down_left";
									$scrollbox_id	=	"scroll_box_left";
									$scroll_id		=	"div_scroll1_left";
								}	
								if($position == 'right')
								{
									$scrollup_id	=	"scroll_up_right";
									$scrolldown_id	=	"scroll_down_right";
									$scrollbox_id	=	"scroll_box_right";
									$scroll_id		=	"div_scroll1_right";
								}
								//$pass_type = get_default_imagetype('combshelf');								
								$pass_type = 'image_thumbpath';
											
								$width_one_set 	= 170;
								$total_cnt		= $db->num_rows($ret_main);
					
								$min_number_req	= $total_cnt;
								$min_width_req 	= $width_one_set * $min_number_req;
								$calc_width		= $total_cnt * $width_one_set;
								if($calc_width < $min_width_req)
									$div_width = $min_width_req;
								else
									$div_width = $calc_width; 
								if ($title)
								{
				?>					<div class="shlf_slr_table_hdrA"><?php echo $title?></div>
				<?php			}
				?>				
                                    <div class="spcl_link_pdt_top"></div>
                                    <div class="link_pdt_conts">
                                    <div class="det_link_pdt_con">
                                    <div class="spcl_link_pdt_inner">
                                    <div class="scrollimgspcl"><ul>
				<?php			$cnts = $db->num_rows($ret_prod);
                                while($row_prod = $db->fetch_array($ret_prod))
								{ 
                ?>
                                      
                                        <li>
                                        <?php
                                        if($shelfData['shelf_showimage']==1) // whether image is to be displayed
                                        {   
                                        ?>
                                        <div class="shelf_thump_wrap">
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
                                        </div>
                                        <?php 
                                        }
                                        ?>
                                        <div class="thump_details">
                                        <div class="productname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div>
                                        <?php
                                        if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
                                                        {
                                                    ?>
                                                    <?php
                                                            $price_class_arr['ul_class'] 		= 'price';
                                                            $price_class_arr['normal_class'] 	= 'productprice';
                                                            $price_class_arr['strike_class'] 	= 'retailprice';
                                                            $price_class_arr['yousave_class'] 	= 'yousaveprice';
                                                            $price_class_arr['discount_class'] 	= 'discountprice';
                                                            echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
                                                            //show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
                                                    ?>
                                                    <?php
                                                        }	
                                                        
                                                    ?>
                                                    <?php 
                                                        if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
                                                        {
                                                    ?>
                                                        <!--<div class="prod_list_des"><?php echo stripslashes($row_prod['product_shortdesc'])?><?php show_moreinfo($row_prod,'list_more')?></div>-->
                                                    <?php
                                                        }
                                                        ?> 
                                                        <div class="addtocartWrap">
                                                            <div class="prod_list_buy">
                                                            <?php 
                                                            $frm_name = uniqid('catdet_');
                                                            ?>
                                                            <form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
                                                            <input type="hidden" name="fpurpose" value="" />
                                                            <input type="hidden" name="fproduct_id" value="" />
                                                            <input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
                                                            <input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
                                                            <?php
                                                            $class_arr['ADD_TO_CART']       = '';
                                                            $class_arr['PREORDER']          = '';
                                                            $class_arr['ENQUIRE']           = '';
                                                            $class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
                                                            $class_arr['QTY']               = ' ';
                                                            $class_td['QTY']				= 'prod_list_buy_a';
                                                            $class_td['TXT']				= 'prod_list_buy_b';
                                                            $class_td['BTN']				= 'prod_list_buy_c';
                                                            echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
                                                            ?>
                                                            </form>
                                                            </div>
                                                        </div>
                                        </div>
                            
                                        </li>
                                        
                                        <?php
                                    }
                                    ?>
                                    </ul></div>
									<div align="right" class="newshelfbottom">
                                    <a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="viewallnewshelf" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a>
                                                        </div>
                                    </div>
                                    
                                    </div>
                                    
                                    
                                    </div>
                                    <div class="spcl_link_pdt_bottom"></div>
                		
					<?php
								
							}
							if($shelfData['shelf_currentstyle']=='nor') // case of christmas layout
							{							

								//echo "<pre>";
								//print_r($shelfData);die();
								
									
								//echo "<pre>";print_r($shelfData);
							?>		
							<?php	if ($title)
							{
							?>		<div class="shlf_slr_table_hdrA"><h2><?php echo $title?></h2></div>
							<?php	
							}
							?>
							<div class="shelf_left">
							<div class="shelf_left_top"><img src="<?php echo url_site_image('box_topcurve.png')?>" width="210" height="11" alt="top_curve" /></div>

							<div class="shelf_left_bg">
							<?php
							while($row_prod = $db->fetch_array($ret_prod))
							{
							?>
							<div class="shelf_best_buy" >
							<?php

							if($shelfData['shelf_showimage']==1) // whether image is to be displayed
							{   
							?>				
							<div class="shelf_thump_wrap">							
							<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
							<?php				// Calling the function to get the type of image to shown for current 

							$pass_type = 'image_thumbcategorypath';								

							// Calling the function to get the image to be shown
							$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);

							if(count($img_arr))
							{
							show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name']);
							}
							else
							{
								$pass_type = 'image_iconpath';
							// calling the function to get the default image
							$no_img = get_noimage('prod',$pass_type); 
							if ($no_img)
							{
								show_image($no_img,$row_prod['product_name'],$row_prod['product_name']);
							}
							}
								
							?>					</a>
							</div>
							<?php		
							}	
														
							?>

							<div class="thump_details">
							<?php
							if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
							{
							?>				 <div class="productname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>
							</div>
							<?php		
							}

							 $price_arr = array();
							if($shelfData['shelf_showprice']==1) // whether price is to be displayed
							{
								
							?>				
							<div class="productprice">
							<?php			
							$price_arr =  show_Price($row_prod,array(),'compshelf',false,4);
							//print_r($price_arr);
							if($price_arr['discounted_price'])
							{
							echo $price_arr['discounted_price'];
							//echo '</br>'. $price_arr['yousave_price'];
							}
							else
							echo $price_arr['base_price'];
							?>				
							</div>
							<?php		}

							$frm_name = uniqid('shelf_');

							?>				 
							<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
							<input type="hidden" name="fpurpose" value="" />
							<input type="hidden" name="fproduct_id" value="" />
							<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
							<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />

							<div class="cartLink">
							<?php			$class_arr['ADD_TO_CART']       = '';
							$class_arr['PREORDER']          = '';
							$class_arr['ENQUIRE']           = '';
							$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
							$class_arr['QTY']               = ' ';
							$class_td['QTY']				= 'bst_slr_pdt_buy_a';
							$class_td['TXT']				= 'bst_slr_pdt_buy_b';
							$class_td['BTN']				= 'bst_slr_pdt_buy_c';
							//echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td,1);
							?>					
							<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">Add to cart</a>
							</div>
							</form> 
							</div> 
							</div>
							<?php
							}
							?>
							<div align="right" class="compshelfproductbottom">
							<a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="viewallshelfprod" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a>
							</div>
							</div>
							
							<div class="shelf_left_bottom"><img src="<?php echo url_site_image('box_bottom.png')?>" alt="bottom_curve" width="210" height="12" /></div>
							</div>
							

							<?php
																	
							}
							else if($shelfData['shelf_currentstyle']=='new')
							{
								?>
								<div class="shelfarea_wrap">
									<?php	if ($title)
							{
							?>		<div class="shlf_slr_table_hdrA"><h2><?php echo $title?></h2></div>
							<?php	
							}
							?>
							<div class="shelfarea_top"><img src="<?php url_site_image('shelf_area_top.png')?>" alt="shelf area top" width="219" height="12" /></div>
							<div class="shelfarea_bg">
							<?php
							$cnts = $db->num_rows($ret_prod);
							while($row_prod = $db->fetch_array($ret_prod))
							{ 
							?>
							<div class="shelf_best_buy">

							<?php
							if($shelfData['shelf_showimage']==1) // whether image is to be displayed
							{   
							?>
							<div class="shelf_thump_wrap">
							<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>">
							<?php
										$pass_type = 'image_iconpath';								

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
							<?php 
							}
							?>
							<div class="thump_details">
							<div class="productname"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></div>
							<?php
							if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
							{
							$price_arr =  show_Price($row_prod,array(),'compshelf',false,3);

							?>
							<div class="productpriceA">								
								<?php echo $price_arr['base_price']; ?>
								</div>	
							
							<?php			
							//print_r($price_arr);
							//print_r($price_arr);
							if($price_arr['discounted_price'])
							{
								?>
								<div class="offerpriceA">								
								<span class="txtgreen">
								<?php
							echo $price_arr['discounted_price'];
							echo '</br>'. $price_arr['yousave_price'];
							?>
							</span></div>
							<?php
							}
							
							?>	
							
							
							<?php
								$price_class_arr['ul_class'] 		= 'price';
								$price_class_arr['normal_class'] 	= 'productprice';
								$price_class_arr['strike_class'] 	= 'retailprice';
								$price_class_arr['yousave_class'] 	= 'yousaveprice';
								$price_class_arr['discount_class'] 	= 'discountprice';
								//echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
								//show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
							?>
							<?php
							}	
							if($shelfData['shelf_showbonuspoints']==1)
							{
								if($row_prod['product_bonuspoints'] > 0)
								{
									/*echo '<div class="prod_list_bonusB">
										<span class="bonus_point_number_a">
											<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSA']).'</span>
										</span>
										<span class="bonus_point_caption_b">'.$row_prod['product_bonuspoints'].'</span>
										<span class="bonus_point_number_c">
											<span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSB']).'</span></span>
										</div>';*/
								}
							}
							?>
							<?php 
							if ($shelfData['shelf_showdescription']==1)// Check whether description is to be displayed
							{
							?>
							<!--<div class="prod_list_des"><?php echo stripslashes($row_prod['product_shortdesc'])?><?php show_moreinfo($row_prod,'list_more')?></div>-->
							<?php
							}
							if($row_prod['product_saleicon_show']==1)
							{
								$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
								if($desc!='')
								{
							?>	
							<!--<div class="prod_list_new"><?php echo $desc?></div>-->
							<?php
								}
							}
							if($row_prod['product_newicon_show']==1)
							{
								$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
								if($desc!='')
								{
							?>	<!--<div class="prod_list_new"><?php echo $desc?></div>-->
							<?php
								}
							}
							/*
							?>

							<div class="moreinfo">
							<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">More Info</a>
							</div>
							*/?> 

							</div>

							</div>
														<div class="shelfdivider"></div>

							<?php
							}
							?>
							<div align="right" class="newshelfbottom">
							<a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="viewallnewshelf" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a>
							</div>
							</div>

							<div class="shelfarea_bottom"><img src="<?php url_site_image('shelf_area_bottom.png')?>" alt="shelf area bottom" width="219" height="11" /></div>

							</div>							<?php
							}
							
						}
					}
				}	
			}
		}
		
		// ####################################################################################################
		// Function which holds the display logic for newsletter subscription
		// ####################################################################################################
		function mod_newsletter($title)
		{
			global $Captions_arr,$ecom_hostname,$vImage,$db,$ecom_siteid,$Settings_arr,$vImage;
			$vimgfield = (!$Settings_arr['imageverification_req_newsletter'])?'':'newsletter_Vimg';
			$Captions_arr['NEWS_LETTER'] = getCaptions('NEWS_LETTER');
			if($Settings_arr['shownewsletter_as_banner']==1) // Decide whether customer login is to be displayed as banner or in normal style
			{
			?>
			<div class="news_letter_banner">
			<a href="<?php url_link('newsletter.html')?>"><img src="<?php url_site_image('neweletter-banner.gif')?>" border="0" /></a>
			</div>
			<?php
			}
			else
			{?>
			<div class="newsletterwrap">
			<form name="frm_newsletter" method="post" action="" class="frm_cls" onsubmit="return newsletter_validation(this)">

			<div class="newstop"><img src="<?php url_site_image('top_news.png')?>" width="219" height="10" alt="news top" /></div>
			<div class="newsbg">

			<div class="newscontent">
			<div class="newsicon"><img src="<?php url_site_image('news_icon.png')?>" width="43" height="51" /></div>
			Sign up for special offers
			<div class="signuptext">
			<?php echo $Captions_arr['NEWS_LETTER']['TITLE']?>
			<input name="newsletter_email" type="text" class="newsletterinput" id="newsletter_email" size="12" value="Email Id" onclick="javascript:document.frm_newsletter.newsletter_email.value=''" />

			</div>
			</div>
			<div class="newsletter_btn">
			<input name="newsletter_Submit" type="submit" value="subscribe" class="newsletterbutton" />
			</div>
			</div>

			<div class="newsbottom"><img src="<?php url_site_image('news_bottom.png')?>" width="219" height="7" /></div>

			</form>
			</div>

			<?php
			}
		}
		// ####################################################################################################
		// Function which holds the display logic for Gift Vouchers
		// ####################################################################################################
		function mod_giftvoucher($title)
		{
			global $Captions_arr,$ecom_hostname,$db,$ecom_siteid,$Settings_arr,$vImage,$position;
			$Captions_arr['GIFT_VOUCHER'] = getCaptions('GIFT_VOUCHER');
			$vimgfield = (!$Settings_arr['imageverification_req_voucher'])?'':'buycompgiftvoucher_Vimg';
		   ?>
           <div class="gift_buy_banner"><a href="<?php echo get_buyGiftVoucherURL()?>"  title="<?php echo stripslash_normal($Captions_arr['GIFT_VOUCHER']['BUY_VOUCHER'])?>"><img src=" <?php url_site_image('sidebanner3.png') ?>" border="0" /></a></div>
		<?php	
		}
		// ####################################################################################################
		// Function which holds the display logic for spending giftvoucher or promotional code
		// ####################################################################################################
		function mod_spendvoucher($title)
		{
			global $Captions_arr,$ecom_hostname,$db,$ecom_siteid,$Settings_arr,$vImage,$position;
			$Captions_arr['GIFT_VOUCHER'] = getCaptions('GIFT_VOUCHER');
		    if($position=='left' || $position=='right')
			{
			?>
      <div class="gift_use_banner"> 
			  <a href="http://<?php echo $ecom_hostname?>/spend_voucher.html"  title="<?php echo stripslash_normal($Captions_arr['GIFT_VOUCHER']['SPEND_VOUCHER_CLICK_HERE'])?>"><img src="<?php url_site_image('use_voucher.png')?>" border="0"/></a></div>
			  <?
			  }
			  ?>
		<?php	
		}
		// ####################################################################################################
		// Function which holds the display logic for Gift Vouchers
		// ####################################################################################################
		function mod_giftvoucher_bak($title)
		{
			global $Captions_arr,$ecom_hostname,$db,$ecom_siteid,$Settings_arr,$vImage;
			$Captions_arr['GIFT_VOUCHER'] = getCaptions('GIFT_VOUCHER');
			$vimgfield = (!$Settings_arr['imageverification_req_voucher'])?'':'buycompgiftvoucher_Vimg';
		?>
			<form method="post" name="voucher_frm" id="voucher_frm" action="" class="frm_cls" onsubmit="return validate_smallvoucher(this,'<?php echo $vimgfield?>')">
			<input type='hidden' name='cart_savepromotional' id="cart_savepromotional_comp" value="1" />
			<table border="0" cellpadding="0" cellspacing="0" class="giftvouchertable">
			<tr>
				<td colspan="2" class="giftvoucherheader"><?php echo $Captions_arr['GIFT_VOUCHER']['ENTER_VOUCHER']?></td>
			</tr>
			<tr>
				<td>
					<input name="cart_promotionalcode" type="text" class="inputA" id="cart_promotionalcode_comp" size="15" />
				</td>
				<td>
				<?php 
				if(!$Settings_arr['imageverification_req_voucher']) // if image verification is required
				{
				?>
				<input name="compvoucher_Submit" type="submit" class="buttongray" id="compvoucher_Submit" value="<?php echo $Captions_arr['GIFT_VOUCHER']['VOUCHER_GO']?>" />
				<?php
				}
				?>
				</td>
			</tr>
			<?php 
				if($Settings_arr['imageverification_req_voucher']) // if image verification is required
				{
			?>

			  <tr>
		    	<td align="left" colspan="2" class="giftvouchertext"><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_VERIFICATION_CODE']?>&nbsp;<span class="redtext">*</span></td>
			  </tr>
			  <tr>
			     <td align="left" valign="middle" colspan="2"><img src="<?php url_verification_image('includes/vimg.php?size=4&pass_vname=buycompgiftvoucher_Vimg')?>" border="0" alt="Image Verification"/></td>
			  </tr>
			  <tr>
			    <td align="left">
			      <?php 
					// showing the textbox to enter the image verification code
					$vImage->showCodBox(1,'buycompgiftvoucher_Vimg','class="inputA_imgver"'); 
				?>
				</td>
				<td>
					<input name="compvoucher_Submit" type="submit" class="buttongray" id="compvoucher_Submit" value="<?php echo $Captions_arr['GIFT_VOUCHER']['VOUCHER_GO']?>" />
				</td>
			  </tr>
		  <?php
				}
			?>
			<tr>
				<td colspan="2"><a href="<?php echo get_buyGiftVoucherURL()?>" class="buygiftvoucherheader" title="<?php echo $Captions_arr['GIFT_VOUCHER']['BUY_VOUCHER']?>"><?php echo $Captions_arr['GIFT_VOUCHER']['BUY_VOUCHER']?></a></td>
			</tr>
			</table>
			</form>
		<?php	
		}
		// ####################################################################################################
		// Function which holds the display logic for Survey
		// ####################################################################################################
		function mod_survey($survey_array,$title)
		{
			global $Captions_arr,$ecom_hostname,$db,$ecom_siteid;
			$Captions_arr['SURVEY'] = getCaptions('SURVEY');
		?>
			
			 <?php
				foreach ($survey_array as $k=>$surveyData)
				{
					// Check whether survay_activateperiodchange is set to 1
					$active 	= $shelfData['survay_activateperiodchange'];
					if($active==1)
					{
						$proceed	= validate_component_dates($surveyData['survay_displaystartdate'],$surveyData['survay_displayenddate']);
					}
					else
						$proceed	= true;	
					
					if($proceed)
					{
				?>
						<form name="survey_frm" action="" method="post" onsubmit="return validate_survey(this)">
						<table width="100%" border="0" cellpadding="2" cellspacing="0" class="surveytable">
				<?php	
						if($title)
						{
				?>
						  <tr>
							<td colspan="2" align="left" valign="top" class="surveytableheader"><?php echo $title?></td>
						  </tr>
			 	<?php
			 			}
				?>		
						  <tr>
							<td colspan="2" align="left" valign="top" class="surveytablequst"><?php echo stripslashes($surveyData['survey_question'])?></td>
						  </tr>
				  <?php
						// Get the options for the survey
						$sql_surveyopt = "SELECT option_id,option_text 
											FROM 
												survey_option 
											WHERE 
												survey_id = ".$surveyData['survey_id']. " 
											ORDER BY option_order ";
						$ret_surveyopt = $db->query($sql_surveyopt);
						if ($db->num_rows($ret_surveyopt))
						{
							while ($row_surveyopt = $db->fetch_array($ret_surveyopt))
							{
				  ?>
							  <tr>
								<td width="26%" height="20" align="right" valign="middle" class="surveytabletd"><input name="survey_opt" type="radio" value="<?php echo $row_surveyopt['option_id']?>" /></td>
								<td align="left" valign="middle" class="surveytabletd"><?php echo stripslashes($row_surveyopt['option_text']);?></td>
							  </tr>
			  <?php
							}
						}
			  ?>
				  <tr>
					<td align="right" valign="middle" class="surveytabletd">&nbsp;</td>
					<td align="left" valign="middle" class="surveytabletdbottom">
					<input type="hidden" name="survey_comp_id" value="<?php echo $surveyData['survey_id']?>" />
					<input name="survey_Submit" type="submit" class="buttongray" value="<?php echo $Captions_arr['SURVEY']['VOTE']?>" /></td>
				  </tr>
				   </table>
				   </form>
			 <?php
			 		}
					else
					{
						removefrom_Display_Settings($surveyData['survey_id'],'mod_survey');
					}
				}
			 ?> 
		 
		<?php	
		}
		// ####################################################################################################
		// Function which holds the display logic for product shop by brand
		// ####################################################################################################
		function mod_shopbybrandgroup($grp_array,$title)
		{
			global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
			
			// Getting the required sort by field and sort order
			$sort_by 	=  $Settings_arr['shopbybrand_shops_orderfield'];
			$sort_by 	=  ($sort_by=='custom')?'b.shop_order':$sort_by;
			$sort_order =  $Settings_arr['shopbybrand_shops_orderby'];
			$sort_str	= " $sort_by $sort_order ";
			$req_shop = $_REQUEST['shop_id'];
				if ($req_shop)
				{
					$sql_parent = "SELECT shopbrand_parent_id 
									FROM 
										product_shopbybrand 
									WHERE 
										shopbrand_id=$req_shop 
									LIMIT 
										1";
					$ret_parent = $db->query($sql_parent);
					if ($db->num_rows($ret_parent))
					{
						list($parent) = $db->fetch_array($ret_parent);
					}
				}	
				$prev_grp = 0;
				if(count($grp_array))
				{
					//Iterating through the array to fetch the product shops to be shown.
					foreach ($grp_array as $k=>$groupData)
					{
						// Check whether shelf_activateperiodchange is set to 1
						$active 	= $groupData['shopbrandgroup_activateperiodchange'];
						if($active==1)
						{
							$proceed	= validate_component_dates($groupData['shopbrandgroup_displaystartdate'],$groupData['shopbrandgroup_displayenddate']);
						}
						else
							$proceed	= true;	
						if ($proceed)
						{
							if ($position == 'left' or $position == 'right') // ############## Left ##############
							{
								if ($groupData['shopbrandgroup_listtype'] == 'Menu')// case if shops are to be shown in list menu
								{
									if($position=='left')
									$cache_type		= 'shop_left_menu';	
									else
									$cache_type		= 'shop_right_menu';	
									$cache_exists 	= false;
									$cache_required	= false;
								}
								elseif($groupData['shopbrandgroup_listtype'] =='Dropdown')
								{
								if($position=='left')
									$cache_type		= 'shop_left_dropdown';	
									else
									$cache_type		= 'shop_right_dropdown';
									$cache_exists 	= false;
									$cache_required	= false;	
								}
								elseif($groupData['shopbrandgroup_listtype'] =='Header')
								{
									if($position=='left')
									$cache_type		= 'shop_left_header';	
									else
									$cache_type		= 'shop_right_header';
									$cache_exists 	= false;
									$cache_required	= false;
								}
							}
							elseif($position == 'top')
							{
								$cache_type		= 'shop_top_menu';
								$cache_exists 	= false;
								$cache_required	= false;
							}
							elseif($position == 'bottom')
							{
								$cache_type		= 'shop_bottom_menu';
								$cache_exists 	= false;
								$cache_required	= false;
							}
							if ($Settings_arr['enable_caching_in_site']==1 and !$_REQUEST['shop_id']) // dont pick from cache in case if clicked on any of the shops
							{
								$cache_required = true;
								if (exists_Cache($cache_type,$groupData['shopbrandgroup_id']))
									{
										$content_cache = getcontent_Cache($cache_type,$groupData['shopbrandgroup_id']);
										if ($content_cache) // if cache exists show it
										{
											echo $content_cache;
											$cache_exists = true;
										}
									}
							}
							if($cache_exists!=true)
							{
								$sql_shop = "SELECT a.shopbrand_id,a.shopbrand_name,shopbrand_parent_id,
											shopbrand_default_shopbrandgroup_id ,shopbrand_subshoplisttype
										FROM 
											product_shopbybrand a,product_shopbybrand_group_shop_map b 
										WHERE
											a.sites_site_id = $ecom_siteid 
											AND b.product_shopbybrand_shopbrandgroup_id = ".$groupData['shopbrandgroup_id']." 
											AND a.shopbrand_id = b.product_shopbybrand_shopbrand_id 
											AND a.shopbrand_hide = 0 
										ORDER BY 
											$sort_str";
								$ret_shop = $db->query($sql_shop);
								if ($db->num_rows($ret_shop))
								{ 
									if ($position == 'left') // ############## Left ##############
									{
										// Check the listing type for shop in product shop group
										if ($groupData['shopbrandgroup_listtype'] == 'Menu')// case if shops are to be shown in list menu
										{
											// Do the following only if caching is not enabled or cache does not exists
											if ($cache_exists==false)
											{
												if($cache_required)// if caching is required start recording the output
												{
													ob_start();
												}
												?>
												<ul class="shopleft">
												<?php
												while ($row_shop = $db->fetch_array($ret_shop))
												{
													$showimg_arr[$row_shop['shopbrand_id']] = $row_shop;
													if($prev_grp != $groupData['shopbrandgroup_id'])
													{
														if ($groupData['shopbrandgroup_hidename']==0) // Decide whether or not to show the groupname
														{
													?>
															<li class="shopleftheader"><?php echo stripslashes($title)?></li>
													<?php
														}
															$prev_grp = $groupData['shopbrandgroup_id'];
													}
													?>
													 <li><h1><a href="<?php url_shops($row_shop['shopbrand_id'],$row_shop['shopbrand_name'],-1)?>" class="shopleftlink" title="<?php echo stripslashes($row_shop['shopbrand_name'])?>"><?php echo stripslashes($row_shop['shopbrand_name']);?></a></h1></li>
													<?php
													if ($row_shop['shopbrand_subshoplisttype']=='List')
													{
														if (($row_shop['shopbrand_id']==$_REQUEST['shop_id']) or ($row_shop['shopbrand_id']==$parent))
														{
														  if($row_shop['shopbrand_id']==$parent)
														  {
														   $comp_shop = $parent;
														  }
														  else
														  $comp_shop = $row_shop['shopbrand_id']; 
														  
															// Check whether any child exists for current shop
															$sql_child = "SELECT shopbrand_id,shopbrand_name,shopbrand_default_shopbrandgroup_id 
																			FROM 
																				product_shopbybrand  
																			WHERE 
																				shopbrand_parent_id=".$comp_shop." 
																				AND sites_site_id=$ecom_siteid 
																			ORDER BY shopbrand_order";
															$ret_child = $db->query($sql_child);
															if ($db->num_rows($ret_child))
															{
															?>
																<li>
																<ul class="subshopleft">
															<?php	
																while ($row_child = $db->fetch_array($ret_child))
																{
															?>
																		<li><h1><a href="<?php url_shops($row_child['shopbrand_id'],$row_child['shopbrand_name'],-1)?>" title="<?php echo stripslashes($row_child['shopbrand_name'])?>" class="shopleftlink"><?php echo stripslashes($row_child['shopbrand_name']);?></a></h1></li>
															<?php		
																}
															?>
																</ul>
																</li>
															<?php	
															}
														}
													}	
												}
												?>
												</ul>
												<?php
												if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
												{
													$content = ob_get_contents();
													ob_end_clean();
													save_Cache($cache_type,$groupData['shopbrandgroup_id'],$content);
													echo $content;
												}												
											}
										}
										elseif($groupData['shopbrandgroup_listtype'] =='Dropdown')
										{
											$cache_type		= 'shop_left_dropdown';	
											$cache_exists 	= false;
											$cache_required	= false;
											if ($Settings_arr['enable_caching_in_site']==1)
											{
												$cache_required = true;
												if (exists_Cache($cache_type,$groupData['shopbrandgroup_id']))
												{
													$content_cache = getcontent_Cache($cache_type,$groupData['shopbrandgroup_id']);
													if ($content_cache) // if cache exists show it
													{
														echo $content_cache;
														$cache_exists = true;
													}
												}
											}
											// Do the following only if caching is not enabled or cache does not exists
											if ($cache_exists==false)
											{
												if($cache_required)// if caching is required start recording the output
												{
													ob_start();
												}
											?>
											<table border="0" cellpadding="0" cellspacing="0" class="shopbybrandtable">
											 <?php
												if ($groupData['shopbrandgroup_hidename']==0) // Decide whether or not to show the groupname
												{
												?>
													  <tr>
														<td class="shopbybrandheader"><?php echo $title?></td>
													  </tr>
											  <?php
												}
											  ?>
											  <tr>
												<td class="shopbybrandheader">
												  <label>
													<select name="prodshopgroup_<?php echo $groupData['shopbrandgroup_id']?>" onchange="handle_dropdownval_sel(this.value)">
														<option value="">-- <?php echo $Captions_arr['COMMON']['SELECT']?> -- </option>
											<?php
														// Case if categories are to be shown in dropdown box
														while ($row_shop = $db->fetch_array($ret_shop))
														{
														$showimg_arr[$row_shop['shopbrand_id']] = $row_shop;
											?>
															<option value="<?php url_shops($row_shop['shopbrand_id'],$row_shop['shopbrand_name'],-1,$groupData['shopbrandgroup_id'])?>" <?php echo (($row_shop['shopbrand_id']==$_REQUEST['shop_id']) /*and ($groupData['shopbrandgroup_id']==$_REQUEST['shopgroup_id'])*/)?'selected="selected"':''?>><?php echo stripslashes($row_shop['shopbrand_name'])?></option>
											<?php		
														}
											?>
													</select>
												  </label>
												</td>
											  </tr>
											</table>
											<?php	
												if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
												{
													$content = ob_get_contents();
													ob_end_clean();
													save_Cache($cache_type,$groupData['shopbrandgroup_id'],$content);
													echo $content;
												}
											}
										}
										elseif($groupData['shopbrandgroup_listtype'] =='Header')
										{
											?>
											<div class="shp_brand_banner"><a href="<?php url_link('shopbybrand.html')?>"><img src="<?php url_site_image('shop-brnd-banner.gif')?>" border="0" title="Click Here" /></a>
											</div>
											<?php
										}
									}//pos
									elseif ($position=='right') // ############## Right ##############
									{
										// Check the listing type for shop in product shop group
										if ($groupData['shopbrandgroup_listtype']== 'Menu')// case if shops are to be shown in list menu
										{
											// Do the following only if caching is not enabled or cache does not exists
											if ($cache_exists==false)
											{
											if($cache_required)// if caching is required start recording the output
											{
												ob_start();
											}
											?>
											<ul class="shopright">
											<?php
											while ($row_shop = $db->fetch_array($ret_shop))
											{
												$showimg_arr[$row_shop['shopbrand_id']] = $row_shop;
												if($prev_grp != $groupData['shopbrandgroup_id'])
												{
													if ($groupData['shopbrandgroup_hidename']==0) // Decide whether or not to show the groupname
													{
												?>
														<li class="shopheaderright"><?php echo stripslashes($title)?></li>
												<?php
													}
														$prev_grp = $groupData['shopbrandgroup_id'];
												}
												?>
												 <li><h1><a href="<?php url_shops($row_shop['shopbrand_id'],$row_shop['shopbrand_name'],-1,$groupData['shopbrandgroup_id'])?>" class="shoplinkright" title="<?php echo stripslashes($row_shop['shopbrand_name'])?>"><?php echo stripslashes($row_shop['shopbrand_name']);?></a></h1></li>
												<?php
												if ($row_shop['shopbrand_subshoplisttype']=='List')
												{
													if (($row_shop['shopbrand_id']==$_REQUEST['shop_id']) or ($row_shop['shopbrand_id']==$parent))
													{
														if(($row_shop['shopbrand_id']==$parent))
														{
														$comp_id = $parent;
														}
														else
														{
														$comp_id =$row_shop['shopbrand_id'];
														}
														// Check whether any child exists for current shop
														$sql_child = "SELECT shopbrand_id,shopbrand_name,shopbrand_default_shopbrandgroup_id 
																		FROM 
																			product_shopbybrand  
																		WHERE 
																			shopbrand_parent_id=".$comp_id." 
																			AND sites_site_id=$ecom_siteid 
																		ORDER BY shopbrand_order";
														$ret_child = $db->query($sql_child);
														if ($db->num_rows($ret_child))
														{
														?>
															<li>
															<ul class="subshopright">
														<?php	
															while ($row_child = $db->fetch_array($ret_child))
															{
														?>
																	<li><h1><a href="<?php url_shops($row_child['shopbrand_id'],$row_child['shopbrand_name'],-1,$groupData['shopbrandgroup_id'],$row_shop['shopbrand_id'])?>" title="<?php echo stripslashes($row_child['shopbrand_name'])?>" class="shoplinkright"><?php echo stripslashes($row_child['shopbrand_name']);?></a></h1></li>
														<?php		
															}
														?>
															</ul>
															</li>
														<?php	
														}
													}
												}	
											}
											?>
											</ul>
											<?php
											
											if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
											{
												
												$content = ob_get_contents();
												ob_end_clean();
												save_Cache($cache_type,$groupData['shopbrandgroup_id'],$content);
												echo $content;
											}	
											}	
										}
										elseif($groupData['shopbrandgroup_listtype'] =='Dropdown')
										{
											$cache_type		= 'shop_right_dropdown';	
											$cache_exists 	= false;
											$cache_required	= false;
											if ($Settings_arr['enable_caching_in_site']==1)
											{
												$cache_required = true;
												if (exists_Cache($cache_type,$groupData['shopbrandgroup_id']))
												{
													$content_cache = getcontent_Cache($cache_type,$groupData['shopbrandgroup_id']);
													if ($content_cache) // if cache exists show it
													{
														echo $content_cache;
														$cache_exists = true;
													}
												}
											}
											// Do the following only if caching is not enabled or cache does not exists
											if ($cache_exists==false)
											{
												if($cache_required)// if caching is required start recording the output
												{
													ob_start();
												}
												?>
												<table border="0" cellpadding="0" cellspacing="0" class="shopbybrandtable">
												<?php
												if ($groupData['shopbrandgroup_hidename']==0) // Decide whether or not to show the groupname
												{
												?>
													  <tr>
														<td class="shopbybrandheader"><?php echo $title?></td>
													  </tr>
												<?php
												}
												?>
												<tr>
												<td class="shopbybrandheader">
												  <label>
													<select name="prodshopgroup_<?php echo $groupData['shopbrandgroup_id']?>" onchange="handle_dropdownval_sel(this.value)">
														<option value="">-- <?php echo $Captions_arr['COMMON']['SELECT']?> -- </option>
												<?php
														// Case if categories are to be shown in dropdown box
														while ($row_shop = $db->fetch_array($ret_shop))
														{
														$showimg_arr[$row_shop['shopbrand_id']] = $row_shop;
												?>
															<option value="<?php url_shops($row_shop['shopbrand_id'],$row_shop['shopbrand_name'],-1,$groupData['shopbrandgroup_id'])?>" <?php echo (($row_shop['shopbrand_id']==$_REQUEST['shop_id']) /*and ($groupData['shopbrandgroup_id']==$_REQUEST['shopgroup_id'])*/)?'selected="selected"':''?>><?php echo stripslashes($row_shop['shopbrand_name'])?></option>
												<?php		
														}
												?>
													</select>
												  </label>
												</td>
												</tr>
												</table>
												<?php	
												if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
												{
													$content = ob_get_contents();
													ob_end_clean();
													save_Cache($cache_type,$groupData['shopbrandgroup_id'],$content);
													echo $content;
												}	
											}
										}
										elseif($groupData['shopbrandgroup_listtype'] =='Header')
										{
										?>
										<div class="shp_brand_banner"><a href="<?php url_link('shopbybrand.html')?>"><img src="<?php url_site_image('shop-brnd-banner.gif')?>" border="0" title="Click Here" /></a>
										</div>
										<?php
										}
									}// End of right
									elseif($position == 'bottom')
									{
										$HTML_Content = '';
										$pass_type = 'image_thumbpath';
										$cnts = 0;
										while ($row_shop = $db->fetch_array($ret_shop))
										{
											$show_noimage = false;
											$HTML_image = '';
											if ($row_shop['shopbrand_showimageofproduct']==0) // Case to check for images directly assigned to shop
											{
												
												// Calling the function to get the image to be shown
												$shopimg_arr = get_imagelist('prodshop',$row_shop['shopbrand_id'],$pass_type,0,0,1); 
												if(count($shopimg_arr))
												{
													$exclude_catid 	= $shopimg_arr[0]['image_id']; // exclude id in case of multi images for category
													$HTML_image 	= show_image(url_root_image($shopimg_arr[0][$pass_type],1),$row_shop['shopbrand_name'],$row_shop['shopbrand_name'],'','',1);
													$show_noimage 	= false;
												}
												else
													$show_noimage = true;
											}
											else // Case of check for the first available image of any of the products under this category
											{
												// Calling the function to get the id of products under current category with image assigned to it
												$cur_prodid = find_AnyProductWithImageUnderShop($row_shop['shopbrand_id']);
												if ($cur_prodid)// case if any product with image assigned to it under current category exists
												{
													// Calling the function to get the image to be shown
													$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
													if(count($img_arr))
													{
														$HTML_image = show_image(url_root_image($img_arr[0][$pass_type],1),$row_shop['shopbrand_name'],$row_shop['shopbrand_name'],'','',1);
														$show_noimage = false;
													}
													else// case if no products exists under current shop with image assigned to it
													$show_noimage = true;
												}
												else// case if no products exists under current shop with image assigned to it
													$show_noimage = true;
											}
											if($show_noimage==false)
											{
												$HTML_Content .= '
																	<a href="'.url_shops($row_shop['shopbrand_id'],$row_shop['shopbrand_name'],'').'" title="'.stripslashes($row_shop['shopbrand_name']).'">
																		'.$HTML_image.'
																	</a>
																';
												$cnts++;
											}				
										}
										$divid = uniqid('shopbrand');	
										?>
										<tr>
										<td colspan="3" align="left" valign="top" class="shopbottomlinkstd">
										<div class="footerBrandsB" >
										<?=$HTML_Content?>
										</div>
										</td>
										</tr>
										<?php
									}
								}	
							}
						}
				}
			}
		}
		// Function to support the image rotate in shop by brand menu
		function show_shop_rotator($showimg_arr)
		{
			$shop_ul_id = uniqid('shopmenu_');
			// get the list of rotating images
			$HTML_Content .= '<script type="text/javascript">
								jQuery.noConflict();
								var $j = jQuery;
								$j(document).ready(
									function(){
									$j(\'ul#'.$shop_ul_id.'\').innerfade({ 
										speed: 1000,
										timeout: '.(4*1000).',
										type: \'sequence\',
										containerheight: \'60px\'
									});
								});
								</script>';
			$HTML_Content .= '<ul id="'.$shop_ul_id.'" >';
			$pass_type = 'image_gallerythumbpath';	
			foreach ($showimg_arr as $k=>$row_shop)
			{
				$show_noimage = false;
				if ($row_shop['shopbrand_showimageofproduct']==0) // Case to check for images directly assigned to shop
				{
					// Calling the function to get the image to be shown
					$shopimg_arr = get_imagelist('prodshop',$row_shop['shopbrand_id'],$pass_type,0,0,1); 
					if(count($shopimg_arr))
					{
						$exclude_catid 	= $shopimg_arr[0]['image_id']; // exclude id in case of multi images for category
						$HTML_image 	= show_image(url_root_image($shopimg_arr[0][$pass_type],1),$row_shop['shopbrand_name'],$row_shop['shopbrand_name'],'','',1);
						$show_noimage 	= false;
					}
					else
						$show_noimage = true;
				}
				else // Case of check for the first available image of any of the products under this category
				{
					// Calling the function to get the id of products under current category with image assigned to it
					$cur_prodid = find_AnyProductWithImageUnderShop($row_shop['shopbrand_id']);
					if ($cur_prodid)// case if any product with image assigned to it under current category exists
					{
						// Calling the function to get the image to be shown
						$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
						if(count($img_arr))
						{
							$HTML_image = show_image(url_root_image($img_arr[0][$pass_type],1),$row_shop['shopbrand_name'],$row_shop['shopbrand_name'],'','',1);
							$show_noimage = false;
						}
						else// case if no products exists under current shop with image assigned to it
						$show_noimage = true;
					}
					else// case if no products exists under current shop with image assigned to it
						$show_noimage = true;
				}
				if($show_noimage==false)
				{
					$link = url_shops($row_shop['shopbrand_id'],$row_shop['shopbrand_name'],'');
					$link_start = $link_end = '';
					if($link!='')
					{
						$link_start     = '<a href="'.$link.'" title="'.$title.'">';
						$link_end       = '</a>';
					}
					$HTML_Content 	.= '<li>'.$link_start.$HTML_image.$link_end.'</li>';
				}						
			}
			$HTML_Content .='</ul>';
			return $HTML_Content;
		}		// ####################################################################################################
		// Function which holds the display logic for Recently Viewed Products
		// ####################################################################################################
		function mod_recentlyviewedproduct($cookval,$title)
		{
			global $Captions_arr,$ecom_siteid,$db,$Settings_arr;
		   	$frm_name = uniqid('recent_');

		?>
				<form name="<? echo $frm_name?>" id="<? echo $frm_name?>" action="" method="post">
				<input type="hidden" name="remove_recent" id="remove_recent" value="remove_recent" />	
				<table border="0" cellpadding="0" cellspacing="0" class="recentviwedtable">
			<?php
				if($title)
				{
			?>
				<tr>
					<td  class="recentviewheader"><?php echo $title?></td>
				</tr>
			<?php
				}
			?>	
			<tr>
				<td>
				<?php
					$cookarray 	= explode(",",$cookval);
					foreach ($cookarray as $k=>$v)
					{
						$sql_prod = "SELECT product_id, product_name,product_default_category_id  
									FROM 
										products 
									WHERE 
										sites_site_id = $ecom_siteid 
										AND product_hide ='N' 
										AND product_id =".$v;
						$ret_prod = $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{
							while ($row_prod = $db->fetch_array($ret_prod))
							{
							?>
								<ul class="recentprod">  
									<li  class="proimage">
									<?php 
										if (!$Settings_arr['recentlyviewed_hide_image'])
										{
									?>	
										<a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
										<?php
											// Calling the function to get the type of image to shown for current 
											$pass_type = get_default_imagetype('recent');
											// Calling the function to get the image to be shown
											$img_arr = get_imagelist('prod',$row_prod['product_id'],$pass_type,0,0,1);
											if(count($img_arr))
											{
												show_image(url_root_image($img_arr[0][$pass_type],1),$row_prod['product_name'],$row_prod['product_name'],'recent_img');
											}
											else
											{
												// calling the function to get the default image
												$no_img = get_noimage('prod',$pass_type); 
												if ($no_img)
												{
													show_image($no_img,$row_prod['product_name'],$row_prod['product_name'],'recent_img');
												}	
											}	
										?>
										</a>	
									<?php
										}
									?>	
										<a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="recentprodlink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>
									</li>
									<?php /*?><li class="prodes"><a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="recentprodlink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></li><?php */?>
								</ul> 
							<?php
							}		
						}					
					}
				?>					    
					</td>
				</tr>
				<tr>
				<td align="right" >
				<ul class="compshelflist">
				<li><h1 align="right"><a href="#" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>" onclick="if(confirm('Are You sure You want to clear the recent product list')){ document.<?=$frm_name?>.submit()};"><?php echo $Captions_arr['COMMON']['COMON_RECENT']?></a></h1></li>
				</ul>
				</td>
				</tr>
			</table>
			</form>
		<?php		
		}
		// ####################################################################################################
		// Function which holds the display logic for adverts
		// ####################################################################################################
		function mod_adverts($advert_arr,$title)
		{
			global $ecom_siteid,$db,$position,$Settings_arr;
			if ($position=='left' or $position=='right') // show the advert only if position is left or right
			{
				if (count($advert_arr))
				{
					foreach ($advert_arr as $d=>$k)
					{
						switch($position)
						{
							case 'left':
								$cache_type		= 'comp_leftadvert';	
							break;
							case 'right':
								$cache_type		= 'comp_rightadvert';	
							break;
						}	
						$cache_exists 	= false;
						$cache_required	= false;
						if ($Settings_arr['enable_caching_in_site']==1) // dont pick from cache in case if clicked on any of the shops
						{
							$cache_required = true;
							if (exists_Cache($cache_type,$k['advert_id']))
							{
								$content_cache = getcontent_Cache($cache_type,$k['advert_id']);
								if ($content_cache) // if cache exists show it
								{
									echo $content_cache;
									$cache_exists = true;
								}
							}
						}
						// Do the following only if caching is not enabled or cache does not exists
						if ($cache_exists==false)
						{
							if($cache_required)// if caching is required start recording the output
							{
								ob_start();
							}
						
							if($title)
							{
					?>							 
								<?php echo $title?>
					<?php
							}
					?>
				  <?php
						switch ($k['advert_type'])
						{
							case 'IMG':
							?>
<div class="gift_use_banner">
							<?php
								$path = url_root_image('nor/img/'.$k['advert_source'],1);
								$link = $k['advert_link'];
								if ($link!='')
								{
						?>
									<a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">	
						<?php
								}
						?>
									<img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo $title?>" border="0" />
						<?php		
								if ($link!='')
								{
						?>
									</a>
						<?php		
								}
								?>
							</div>
							<?php
							break;
							case 'PATH':
							?>
							<div class="bannerwrap">
							<?php
								$path = $k['advert_source'];
								$link = $k['advert_link'];
								if ($link!='')
								{
						?>
									<a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">	
						<?php
								}
						?>
									<img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo $title?>" border="0" />
						<?php		
								if ($link!='')
								{
						?>
									</a>
						<?php		
								}
								?>
							</div>
							<?php
							break;
							case 'TXT':
							?>
							<div class="bannerwrap">
							<?php
								$path = $k['advert_source'];
								echo stripslashes($path);
							?>
							</div>
							<?php
							break;
							
							case 'SWF'://for  flash file
							?>
							<?php
							$path = url_root_image('nor/img/'.$k['advert_source'],1);
							$link = $k['advert_link'];
							$flash_path =  '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="200" height="95">
							<param name="movie" value='.$path.'  >
    						<param name="quality" value="high" >
							<param name="BGCOLOR" value="#D6D8CB"><embed src='.$path.' type=application/x-shockwave-flash width = 200 height=95> </object>';
							$img_link=  '';
							echo  $flash_path ;
							?>
							</div>
							<?php
						break;
						};
						?>
							
						<?php
							if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
							{
								$content = ob_get_contents();
								ob_end_clean();
								save_Cache($cache_type,$k['advert_id'],$content);
								echo $content;
							}	
						}
					}			
				}
			}
			else if($position=='topband')
			{
				if (count($advert_arr))
				{
					foreach ($advert_arr as $d=>$k)
					{
						
						switch($position)
						{
							case 'left':
								$cache_type		= 'comp_leftadvert';	
							break;
							case 'right':
								$cache_type		= 'comp_rightadvert';	
							break;
						}	
						$cache_exists 	= false;
						$cache_required	= false;
						if ($Settings_arr['enable_caching_in_site']==1) // dont pick from cache in case if clicked on any of the shops
						{
							$cache_required = true;
							if (exists_Cache($cache_type,$k['advert_id']))
							{
								$content_cache = getcontent_Cache($cache_type,$k['advert_id']);
								if ($content_cache) // if cache exists show it
								{
									echo $content_cache;
									$cache_exists = true;
								}
							}
						}
						// Do the following only if caching is not enabled or cache does not exists
						if ($cache_exists==false)
						{
							if($cache_required)// if caching is required start recording the output
							{
								ob_start();
							}
						
/*
							if($title)
							{
					?>							 
								<div align="left" valign="top" class="advert_comp_header"><?php echo $title?></div>
					<?php
							}
*/
switch ($k['advert_type'])
						{
							case 'ROTATE':
							?>				
							<div class="advert_rotate">
    <?php
								$advert_ul_id = uniqid('advert_');
								// get the list of rotating images
								$sql_rotate = "SELECT rotate_image,rotate_link, rotate_alttext   
													FROM 
														advert_rotate 
													WHERE 
														adverts_advert_id = ".$k['advert_id']." 
													ORDER BY 
														rotate_order ASC";
								$ret_rotate = $db->query($sql_rotate);
								if($db->num_rows($ret_rotate))
								{
								   $HTML_Content .= '<script type="text/javascript">
													jQuery.noConflict();
													var $j = jQuery;
													$j(document).ready(
														function(){
														$j(\'ul#'.$advert_ul_id.'\').innerfade({ 
															speed: 1000,
															timeout: '.($k['advert_rotate_speed']*1000).',
															type: \'sequence\',
															runningclass: \'innerfade_middle\',
															containerheight: \''.$k['advert_rotate_height'].'px\'
														});
													});
													</script>';
									$HTML_Content .= '<ul id="'.$advert_ul_id.'">';
									while ($row_rotate = $db->fetch_array($ret_rotate))
									{
										if($row_rotate['rotate_alttext']!='')
										{
										  $alt_text = $row_rotate['rotate_alttext']; 
										}
										else
										{
										   $alt_text = $k['advert_title']; 
										}
										$link = trim($row_rotate['rotate_link']);
										$link_start = $link_end = '';
										if($link!='')
										{
											$link_start     = '<a href="'.$link.'" title="'.stripslashes($k['advert_title']).'">';
											$link_end       = '</a>';
										}
										$HTML_Content .= '
															<li>'.$link_start.'
															<img src="'.url_root_image('rot/img/'.$row_rotate['rotate_image'],1).'" alt="'.stripslashes($alt_text).'" title="'.stripslashes($alt_text).'" />'.
															$link_end.'</li>';
									}
									$HTML_Content .='</ul>';
									echo $HTML_Content;
									}
									else
									{
										$path = url_root_image('nor/img/'.$k['advert_source'],1);
										$link = $k['advert_link'];
										if ($link!='')
										{
								?>
											<a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">	
								<?php
										}
								?>
											<img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo $title?>" border="0" />
								<?php		
										if ($link!='')
										{
								?>
											</a>
								<?php		
										}
								
									}
								?>
 								</div>
						<?php
						     break;
						     case 'TXT':
						     ?>
						     
						     <div class="adv_txt_alert">
								 <div class="adv_txt_title"><?php echo $title;?></div>
							
							<div class="adv_txt_txt"><?php
								$path = $k['advert_source'];
								echo stripslashes($path);
							?>
							</div>
							</div>
							<?php
						     break;
						 }
							if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
							{
								$content = ob_get_contents();
								ob_end_clean();
								save_Cache($cache_type,$k['advert_id'],$content);
								echo $content;
							}	
						}
					}			
				}
			}
			else if($position=='bottomspecialband')
			{
				if (count($advert_arr))
				{
					foreach ($advert_arr as $d=>$k)
					{
						switch($position)
						{
							case 'left':
								$cache_type		= 'comp_leftadvert';	
							break;
							case 'right':
								$cache_type		= 'comp_rightadvert';	
							break;
						}	
						$cache_exists 	= false;
						$cache_required	= false;
						if ($Settings_arr['enable_caching_in_site']==1) // dont pick from cache in case if clicked on any of the shops
						{
							$cache_required = true;
							if (exists_Cache($cache_type,$k['advert_id']))
							{
								$content_cache = getcontent_Cache($cache_type,$k['advert_id']);
								if ($content_cache) // if cache exists show it
								{
									echo $content_cache;
									$cache_exists = true;
								}
							}
						}
						// Do the following only if caching is not enabled or cache does not exists
						if ($cache_exists==false)
						{
							if($cache_required)// if caching is required start recording the output
							{
								ob_start();
							}
						
/*
							if($title)
							{
					?>							 
								<div align="left" valign="top" class="advert_comp_header"><?php echo $title?></div>
					<?php
							}
*/						
						if ($k['advert_type']!='TXT')
						{
							return;			
						}
							?>				
							<div class="bannerbottomspecialband">
							<?php
								$path = $k['advert_source'];
								echo stripslashes($path);
							?>
							</div>
						<?php
							if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
							{
								$content = ob_get_contents();
								ob_end_clean();
								save_Cache($cache_type,$k['advert_id'],$content);
								echo $content;
							}	
						}
					}			
				}
			}	
		}
		
		// ####################################################################################################
		// Function which holds the display logic for sitereviews
		// ####################################################################################################
		function mod_sitereviews($title)
		{
			global $ecom_siteid,$db,$position,$Settings_arr;
				//print_r($title);
		?>	
			<div class="gift_use_banner" align="left">
				<input class="sitereviewleft" onclick="window.location='<?php url_link('sitereview.html');?>'" type="button">
			</div>	
		<?php	
		}
		
		// ####################################################################################################
		// Function which holds the display logic for Recently Viewed Products
		// ####################################################################################################
		function mod_productlist($ret_prod,$title)
		{
			global $Captions_arr,$ecom_siteid,$db,$Settings_arr;
			
		?>
				<table border="0" cellpadding="0" cellspacing="0" class="catprod_table">
			<?php
				if($title)
				{
			?>
				<tr>
					<td  class="catprod_header"><?php echo $title?></td>
				</tr>
			<?php
				}
			?>	
			<tr>
				<td>
				<?php
					while ($row_prod = $db->fetch_array($ret_prod))
					{
				?>
						<ul class="catprod_prod">  
							<li class="catprod_prodes"><a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="catprod_prodlink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></li>
						</ul> 
				<?php
					}
				?>					    
					</td>
				</tr>
			</table>
		<?php		
		}
		function mod_statistics($title,$stat_query)
		{
		global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
		if($db->num_rows($stat_query)){
		$row_query = $db->fetch_array($stat_query);
		}
		//print_r($row_query);
		?>
			<table border="0" cellpadding="0" cellspacing="2" class="webstatisticstable">
			<?php
				if($title)
				{
			?>
				<tr>
					<td  class="webstatisticsheader" align="left"><?php echo $title?></td>
				</tr>
				
			<?php
				}
			?>
			<tr>
				    <td align="center" class="webstatistics"><span class="webstatisticsA">"<?=$row_query['site_hits']?>"</span><br /><?php echo $Captions_arr['COMMON']['WEB_STATISTICS']?></td>
				</tr>	
			</table>
		<?
		}
		function mod_ssl($title)
                {
                    global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$image_path;
					if($position=='left' or $position =='right')
					{
						if(file_exists("$image_path/site_images/ssl_main_image.gif"))
						{
                ?>
							<table border="0" cellpadding="0" cellspacing="2" class="webstatisticstable">
							<?php
							if($title)
							{
							?>
								<tr>
									<td  class="sslheader" align="left"><?php echo $title?></td>
								</tr>
										
							<?php
							}
							?>	
								<tr>
									<td align="center" >
									<div class="ssl_side_div">
										<img src="<? url_site_image('ssl_main_image.gif')?>" alt="Secure Payment" title="<?php echo $title?>" border="0" />
									</div>	
									</td>
								</tr>
							</table>
                <?
						}
				}
				elseif ($position =='bottom')
				{
					 if(file_exists("$image_path/site_images/ssl_main_image_bottom.gif"))
                     {
					 	?>
						<tr>
							<td colspan="3" valign="top" align="center">
							<div class="ssl_bottom_div">
							<img src="<? url_site_image('ssl_main_image_bottom.gif')?>" alt="Secure Payment" title="<?php echo $title?>" border="0" />
							</div>
							</td>
						</tr>	
						<?php
					 }
				}
		}
		// Function to show the top menu item
		function mod_topmenu($title)
		{ //LOGIN_TOPMENU;
			global $db,$ecom_siteid,$ecom_hostname,$inlineSiteComponents,$Captions_arr,$logoutUrl;
			 $Captions_arr['LOGIN_MENU'] 	= getCaptions('LOGIN_MENU');
			 $cust_id 					= get_session_var("ecom_login_customer");
			 /* FB login script */
			 $cust_fbid 				= get_session_var("ecom_login_customer_fbid");
			 //echo $cust_fbid;echo "<br>";
		?>
			<tr>
				<td colspan="3" class="userloginmenuytop" >
				<ul class="userloginmenuytopul"> 
                <?php /* FB login script */
					if($cust_fbid > 0)
					{
				?>
				<li><a href="<?php echo $logoutUrl; ?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['LOGOUT']?></a></li>
				<?php
					}
					else
					{
				?>
				<li><a href="<?php url_link('logout.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['LOGOUT']?></a></li>
				<?php
					}
				?>
			
				<?php
				/*
				// check whether payon account link is to be displayed for current user
				$sql_usr = "SELECT customer_payonaccount_status 
									FROM 
										customers 
									WHERE 
										customer_id = $cust_id 
										AND sites_site_id =$ecom_siteid 
										AND customer_payonaccount_status IN ('ACTIVE','INACTIVE')
									LIMIT 
										1";
				$ret_usr = $db->query($sql_usr);
				if ($db->num_rows($ret_usr))
				{
				?>
 					<li><a href="<?php url_link('mypayonaccountpayment.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_PAYONACCDETAILS']?></a></li>
				<?php
				}
					$sql_download	= "SELECT ord_down_id 
													FROM 
														order_product_downloadable_products  
													WHERE
														sites_site_id = $ecom_siteid 
														AND customers_customer_id = $cust_id 
													LIMIT 
														1";
					$ret_download	= $db->query($sql_download);
					if ($db->num_rows($ret_download))
					{
				?>	
					<li><a href="<?php url_link('mydownloads.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_DOWNLOADS']?></a></li>
				<?php
					}
				?>
				<?php /*?><li><h1><a href="<?php url_link('mypricepromise.html')?>" class="userloginmenuytoplink">
					  <?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_PRICE_PROMISE'])?>
					</a>
					</h1>
				</li><?php *//*?>	
				<li><a href="<?php url_link('myorders.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_ORDERS']?></a></li>
				<? $myaddr_module = 'mod_myaddressbook';
					if(in_array($myaddr_module,$inlineSiteComponents))
					{
				?>
					<li><a href="<?php url_link('myaddressbook.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_ADDRESSBOOK']?></a></li>
				<?php
					}
				?>
				<?php	
					$myfav_module = 'mod_myfavorites';
					if(in_array($myfav_module,$inlineSiteComponents))
					{
				?>
					<li><a href="<?php url_link('myfavorites.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_FAVOURITE']?></a></li>
				<?php
					}
					?>
				<li><a href="<?php url_link('wishlist.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_WISHLIST']?></a></li>
				<li><a href="<?php url_link('myenquiries.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_ENQUIRIES']?></a></li>
                <?php /* FB login script */
					if($cust_fbid <= 0)
					{
				?>
				<li><a href="<?php url_link('myprofile.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_PROFILE']?></a></li>
				<?php
					}
				?>
				 <?php /* FB login script */
					//if($cust_id == 190741 || $cust_id == 67300 || $cust_id ==189235)
					{
				?>
				<li><a href="<?php url_link('gdproptin.html')?>" class="userloginmenuytoplink">GDPR Opt-In Form</a></li>
				<?php
					}
				?>
				<li><a href="<?php url_link('login_home.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_HOME']?></a></li>
				</ul>          
				</td>
			</tr>
		<?php
		}
		
		/* Function to show the currency selector */
		function mod_currencyselector($title)
		{
			global $db,$ecom_siteid,$sitesel_curr;
		  	// get the list of currencies to be used with the site
			$curr_arr = get_currency_list();
		    $comp_uniqid = uniqid('');

	  	?>
	  		<form method="post" name="frm_maincurrency_<?=$comp_uniqid?>" enctype="multipart/form-data" class="frm_cls" action="">
	  		<table border="0" cellpadding="0" cellspacing="2" class="currencyselectortable">
			<?php
				if($title)
				{
			?>
				<tr>
					<td  class="currencyselectoryheader" align="left"><?php echo $title?></td>
				</tr>
				
			<?php
				}
			?>	
			<tr>
				 <td align="center">
		<?php
					//showing the currency selection drop down
					echo generateselectbox('cbo_selcurrency',$curr_arr,$sitesel_curr,'','document.frm_maincurrency_'.$comp_uniqid.'.submit()',0,'currencyselectordropdown');
		?>
				</td>
			</tr>
			</table>
			</form>	
		<?php
		}
	
	
		/* Function to show the currency selector */
		function mod_header($header_arr)
		{
			global $db,$ecom_siteid,$sitesel_curr;
			$ret_array  = array();
			if (count($header_arr)) 
			{
				$random = array_rand($header_arr);
				$header_image = trim($header_arr[$random]['header_filename']);
				if($header_image=='')
					$header_image = url_site_image('main_header.jpg',1);
				else
					$header_image = url_root_image($header_image,1);
				$header_text = trim($header_arr[$random]['header_caption']);
				$ret_array['img']  = $header_image;
				$ret_array['text']  = $header_text;
				//$header_image = "<img src=\"$header_image\" alt=\"\" width=\"244\" height=\"45\" border=\"0\" />";
					return $ret_array;
			} else {
				$header_image = url_site_image('main_header.jpg',1);
				$ret_array['img']  = $header_image;
				$ret_array['text']  = '';
				//$header_image = "<img src=\"$header_image\" alt=\"\" width=\"244\" height=\"45\" border=\"0\" />";
					return $ret_array;
			}			
		}
		
		// ####################################################################################################
		// Function which holds the display logic for best sellers
		// ####################################################################################################
		function mod_preorder($ret_main,$title,$display_id)
		{
			global $ecom_siteid,$db,$ecom_hostname,$position,$Captions_arr,$Settings_arr;
			if ($position=='left' or $position=='right') // Best sellers is allowed in left or right panels
			{	
				if ($db->num_rows($ret_main))
				{
					
						if ($position=='left') // display login for left hand side
						{
				?>
							<ul class="preorder">   
				<?php
							if($title) // check whether title exists
							{
				?>
								<li class="preorderheader"><?php echo $title?></li>
				<?php
							}
							while ($row_main = $db->fetch_array($ret_main))
							{
				?>	
								<li><h1><a href="<?php url_product($row_main['product_id'],$row_main['product_name'],-1)?>" class="preorderlink" title="<?php echo stripslashes($row_main['product_name'])?>"><?php echo stripslashes($row_main['product_name'])?></a></h1></li>
		
				<?php
						
							}		
				?>
								<li><h1 align="right"> <a href="<? url_link('preorder'.$display_id.'.html')?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>" <?php /*onclick="document.bestseller_component_left<?=$display_id?>.submit();"*/?>><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a> </h1> </li>
							</ul>
							<?php /*<form name="bestseller_component_left<?=$display_id?>" action="<? url_link('bestsellers.html')?>" method="post"><input type="hidden" name="disp_id" value="<?php echo $display_id?>"/></form>*/?>
				<?php	
						}
						elseif($position == 'right') // display logic for right hand side
						{
				?>
							<ul class="preorder">   
				<?php
							if($title) // check whether title exists
							{
				?>
								<li class="preorderheader"><?php echo $title?></li>
				<?php
							}
							while ($row_main = $db->fetch_array($ret_main))
							{
				?>	
								<li><h1><a href="<?php url_product($row_main['product_id'],$row_main['product_name'],-1)?>" class="preorderlink" title="<?php echo stripslashes($row_main['product_name'])?>"><?php echo stripslashes($row_main['product_name'])?></a></h1></li>
				
				<?php
							}		
				?>
								<li><h1 align="right"><a href="<? url_link('preorder'.$display_id.'.html')?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>" <?php /*onclick="document.bestseller_component_right<?=$display_id?>.submit();"*/?>><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a> </h1> </li>
							</ul>
							<?php /*<form name="bestseller_component_right<?=$display_id?>" action="<? url_link('bestsellers.html')?>" method="post"> <input type="hidden" name="disp_id" value="<?php echo $display_id?>"/></form>*/?>
				<?php	
						}
						
				}	
			}	
		}
		function mod_payonaccount($title)   // Function to show the payonaccount banner
		 {
			 global $db,$ecom_siteid,$sitesel_curr;
		?>
		  <div class="payonaccountcon" align="left"><a href="<?php url_link('payonaccount.html')?>"><img src="<?php url_site_image('payonaccount.gif')?>" alt="PayonAccount" title="PayonAccount" border="0" /></a></div>
		<?
		}
		function Show_shop_image_forcat($cat_id)
		{
		 global $db,$ecom_siteid,$sitesel_curr;
         if($cat_id)
         {
              $sql_shop = "SELECT a.shopbrand_id,a.shopbrand_name,a.shopbrand_hide,b.shop_order  
					FROM product_shopbybrand a,category_shop_map b 
					WHERE a.sites_site_id=$ecom_siteid AND 
					b.shopbybrand_category_id = $cat_id 
					AND a.shopbrand_hide = 0 
					AND a.shopbrand_id = b.shopbybrand_shopbybrand_id 	  
					ORDER BY b.shop_order";
					$ret_shop = $db->query($sql_shop);
									
										$HTML_Content = '';
										$pass_type = 'image_bigpath';
										//$pass_type = 'image_gallerythumbpath';

										$cnts = 0;
										while ($row_shop = $db->fetch_array($ret_shop))
										{
											$show_noimage = false;
											$HTML_image = '';
											//if ($row_shop['shopbrand_showimageofproduct']==0) // Case to check for images directly assigned to shop
											{
												
												// Calling the function to get the image to be shown
												$shopimg_arr = get_imagelist('prodshop',$row_shop['shopbrand_id'],$pass_type,0,0,1); 
												if(count($shopimg_arr))
												{
													$exclude_catid 	= $shopimg_arr[0]['image_id']; // exclude id in case of multi images for category
													$HTML_image 	= show_image(url_root_image($shopimg_arr[0][$pass_type],1),$row_shop['shopbrand_name'],$row_shop['shopbrand_name'],'','',1);
													$show_noimage 	= false;
												}
												else
													$show_noimage = true;
											}
											/*
											else // Case of check for the first available image of any of the products under this category
											{
												// Calling the function to get the id of products under current category with image assigned to it
												$cur_prodid = find_AnyProductWithImageUnderShop($row_shop['shopbrand_id']);
												if ($cur_prodid)// case if any product with image assigned to it under current category exists
												{
													// Calling the function to get the image to be shown
													$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
													if(count($img_arr))
													{
														$HTML_image = show_image(url_root_image($img_arr[0][$pass_type],1),$row_shop['shopbrand_name'],$row_shop['shopbrand_name'],'','',1);
														$show_noimage = false;
													}
													else// case if no products exists under current shop with image assigned to it
													$show_noimage = true;
												}
												else// case if no products exists under current shop with image assigned to it
													$show_noimage = true;
											}
											*/ 
											if($show_noimage==false)
											{
												$HTML_Content .= '
																	<a href="'.url_shops($row_shop['shopbrand_id'],$row_shop['shopbrand_name'],'').'" title="'.stripslashes($row_shop['shopbrand_name']).'">
																		'.$HTML_image.'
																	</a>
																';
												$cnts++;
											}				
										}
										$divid = uniqid('shopbrand');	
										?>									
										<?=$HTML_Content?>	
										<?php
			}									
		}
		function mod_searchfilter($title)
		{	return;	
			global $ecom_siteid,$db,$ecom_hostname,$position,$Captions_arr,$Settings_arr,$sql_filter_search,$image_path;
			$rightImg	=	"http://".$ecom_hostname."/images/".$ecom_hostname."/site_images/arrow-side.gif";
			$downImg	=	"http://".$ecom_hostname."/images/".$ecom_hostname."/site_images/arrow-down.gif";
			if($_REQUEST['req']=='search')
			{   
				 $page_read = 'search.php';
				 $res_div   = 'result_filter';
			}
			if($_REQUEST['req']=='categories')
			{   
				 $page_read = 'categories.php';
				 $res_div   = 'result_prod_div';
			}
			//echo $sql_filter_search;
			if($sql_filter_search!='')
			{ 
				$res_search_filter = $db->query($sql_filter_search);
				$tot_num = $db->num_rows($res_search_filter);
				if($tot_num > 0 )
				{
				$count_id = 0;
				$max_price= 0;
				$min_price= 0;
				while($row_filter_serach = $db->fetch_array($res_search_filter))
				{
				  $prod_ids[]	=	$row_filter_serach['product_id']; 
				  $count_id++;
				  //echo "<pre>";print_r($row_filter_serach);echo "<br>";
				  if($count_id==$tot_num)
				  {
				   	$max_price = $row_filter_serach[1];
				  }
				  elseif($count_id==1)
				  {
				   	$min_price = $row_filter_serach[1];
				  }
				}
				if($Settings_arr['adv_showcharacteristics']==1)
				{			
					$variables = array();
					//To get all products under this site.
					if(count($prod_ids)>0)
					{ 
						$prod_str = implode(',',$prod_ids);
						//For the variable name under this site
						$AdvSearchVariables = "SELECT 
														DISTINCT var_name,var_id
												  FROM 
												  		product_variables 
												  WHERE products_product_id IN ($prod_str) 
												  AND var_value_exists=1 GROUP BY var_name ORDER BY var_name LIMIT 0,6";
						$rstAdvSearchVariables=$db->query($AdvSearchVariables);
						while ($variable = $db->fetch_array($rstAdvSearchVariables))
						{
							$variables[$variable['var_id']] = $variable[var_name];
						}
					 }
				}	
				?>
				<!--<link type="text/css" href="<?php echo url_head_link("images/".$ecom_hostname."/css/searchfilter/ui-lightness/jquery-ui-1.10.1.custom.css",1)?>" rel="stylesheet" />	
				<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/searchfilter/jquery-1.9.1.js",1)?>"></script>
				<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/searchfilter/jquery-ui-1.10.1.custom.js",1)?>"></script>-->
				<!--<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/searchfilter/jquery-1.3.2.min.js",1)?>"></script>
				<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/searchfilter/jquery_slider.js",1)?>"></script>-->

				
			   										<script type="text/javascript">
														jQuery.noConflict();
                                                        var $j = jQuery;
											$j(document).ready(function(){
											var start ;
											var end ;
											var qsearch ;
											var fromajax;
										    $j('#loader_img').hide();

											qsearch = '<?=$_REQUEST['quick_search']?>';
											//alert(<?=$min_price?>);
											//alert(<?=$max_price?>);
											$j("#frmProductSearch_filter input:checkbox").click(function( e ) {   SearchProducts('<?=$res_div?>')  });
											$j(function() { 
												$j("#slider-range").slider({
													range: true,
													min: <?=$min_price?>,
													max: <?=$max_price?>,
													values: [<?=$min_price?>, <?=$max_price?>],
													stop: function(event, ui) {
													$j("#amount").val('$' + ui.values[0] + ' - $' + ui.values[1]);
													start = ui.values[0];
													end = ui.values[1];
													$j("#Min_price").val(start);
													$j("#Max_price").val(end);
													$j('#clear_pricerange').show();
													SearchProducts("<?=$res_div?>" /* Id of the DIV where we need to show the results */);	
												}
												});
												//alert(<?=$min_price?>)
												//alert(<?=$max_price?>);
												var a = $j("#slider-range").slider("values", 0);
												$j("#amount").val('$' + $j("#slider-range").slider("values", 0) + ' - $' + $j("#slider-range").slider("values", 1));
												//alert(<?=$min_price?>);
												//alert(<?=$max_price?>);
											});
										});
										// Function for searchng products using text format
										function SearchProducts(container)
										{ 
										   //alert(container);
										   window.scrollTo(0,$j("#"+container).position().top);
										   $j('#loader_img').show();
										   
										    // $("#" + container).html('loading');
											var myfrmval = $j("#frmProductSearch_filter" /* Id of the form where the search controls are placed*/).serialize();
											$j.ajaxSetup(
											{
												 type: "POST",
												timeout:100000,
												dataType:"text",
												error:function(xhr)
												{
													$j("#" + container).html("<div>"+xhr.status+" : "+xhr.statusText+"</div>");
												}
											});
											      $j.post("../../../includes/base_files/<?=$page_read?>",
													myfrmval,
													function(data)
													{	
														$j("#" + container).html(data);
														$j('#loader_img').hide();
														//$j.scrollTo('#' + container);
													});		
													
										}
										function hideDiv(divID)
										{
											 $j('#'+divID).hide();
	   										 $j('#click_'+divID).attr('class','refine_inactive');
											 $j('#click_'+divID).html('<img src="<?php echo $rightImg;?>" width="20" height="20" alt="right">');
											 $j('#click_'+divID).removeAttr('onclick');
											 //$('#click_'+divID).setAttr('onclick','javascript:showDiv('+divID+');');
											 $j('#click_'+divID).click( function() { showDiv(divID); } );
										}
										function showDiv(divID)
										{
											 $j('#'+divID).show();
	   										 $j('#click_'+divID).attr('class','refine_active');
											 $j('#click_'+divID).html('<img src="<?php echo $downImg;?>" width="20" height="20" alt="down">');
											 $j('#click_'+divID).removeAttr('onclick');
											 //$('#click_'+divID).setAttr('onclick','javascript:hideDiv('+divID+');');
											 $j('#click_'+divID).click( function() { hideDiv(divID); } );
										}
										function clearCheck(divID)
										{
											if(divID != "clear_pricerange")
											{
												$j('#'+divID).find('input[type=checkbox]:checked').removeAttr('checked');
												$j('#clear_'+divID).hide();
												if($j('#content_container :checkbox:checked').length > 0)
												{
													$j('#clear_all').show();
												}
												else
												{
													$j('#clear_all').hide();
												}
											}
											else
											{
												$j('#clear_pricerange').hide();
												var $slider = $j("#slider-range");
												//alert($('#minprice').val());
												//alert($('#maxprice').val());
												$slider.slider("values", 0, $j('#minprice').val());
												$slider.slider("values", 1, $j('#maxprice').val());
												$j("#Min_price").val($j('#minprice').val());
												$j("#Max_price").val($j('#maxprice').val());
												var a = $slider.slider("values", 0);
												$j("#amount").val('$' + $slider.slider("values", 0) + ' - $' + $slider.slider("values", 1));
												SearchProducts("<?=$res_div?>" /* Id of the DIV where we need to show the results */);	
												/*$(function() { 
													$("#slider-range").slider({
														range: true,
														min: $('#minprice').val(),
														max: $('#maxprice').val(),
														values: [$('#minprice').val(), $('#maxprice').val()],
														stop: function(event, ui) {
														$("#amount").val('$' + ui.values[0] + ' - $' + ui.values[1]);
														start = ui.values[0];
														end = ui.values[1];
														$("#Min_price").val(start);
														$("#Max_price").val(end);
														SearchProducts("<?=$res_div?>" /* Id of the DIV where we need to show the results *);	
													}
													});
													var a = $("#slider-range").slider("values", 0);
													$("#amount").val('$' + $("#slider-range").slider("values", 0) + ' - $' + $("#slider-range").slider("values", 1));
												});*/
											}
											
											SearchProducts('<?=$res_div?>')
										}
										function clearAllCheck(divID)
										{
											$j('#'+divID).find('input[type=checkbox]:checked').removeAttr('checked');
											//$('#clear_all').find('[href]').css('display','none');
											$j('a.refine_sub_clear').each(function() { $j(this).hide(); });
											$j('#clear_all').hide();
											$j('#clear_pricerange').hide();
											var $slider = $j("#slider-range");
											//alert($('#minprice').val());
											//alert($('#maxprice').val());
											$slider.slider("values", 0, $j('#minprice').val());
											$slider.slider("values", 1, $j('#maxprice').val());
											$j("#Min_price").val($j('#minprice').val());
											$j("#Max_price").val($j('#maxprice').val());
											var a = $slider.slider("values", 0);
											$j("#amount").val('$' + $slider.slider("values", 0) + ' - $' + $slider.slider("values", 1));
											SearchProducts("<?=$res_div?>" /* Id of the DIV where we need to show the results */);
												
											SearchProducts('<?=$res_div?>')
										}
										function manageClear(divID)
										{
											if($j('#'+divID+' :checkbox:checked').length > 0)
											{
												if($j('#clear_'+divID).css('display') == 'none')
												{
													//$('#clear_'+divID).attr('style', 'display:block;');
													$j('#clear_'+divID).show();
												}
											}
											else
											{
												//$('#clear_'+divID).attr('style', 'display:none;');
												$j('#clear_'+divID).hide();
											}
											if($j('#content_container :checkbox:checked').length > 0)
											{
												$j('#clear_all').show();
											}
											else
											{
												$j('#clear_all').hide();
											}
										}
										function sortAction(positionVal,pageVal)
										{
											var sortBy,sortOrder,cntPerPage;
											var sortByVal,sortOrderVal,cntPerPageVal;
											if(positionVal == 'top')
											{
												if(pageVal == 'category')
												{
													sortBy		=	'catdet_sortbytop';
													sortOrder	=	'catdet_sortordertop';
													cntPerPage	=	'catdet_prodperpagetop';
												}
												else if(pageVal == 'search')
												{
													sortBy		=	'searchprod_sortbytop';
													sortOrder	=	'searchprod_sortordertop';
													cntPerPage	=	'searchprod_prodperpagetop';
												}
											}
											else
											{												
												if(pageVal == 'category')
												{
													sortBy		=	'catdet_sortbybottom';
													sortOrder	=	'catdet_sortorderbottom';
													cntPerPage	=	'catdet_prodperpagebottom';
												}
												else if(pageVal == 'search')
												{
													sortBy		=	'searchprod_sortbybottom';
													sortOrder	=	'searchprod_sortorderbottom';
													cntPerPage	=	'searchprod_prodperpagebottom';
												}
											}
											sortByVal = $('#'+sortBy).val();
											sortOrderVal = $('#'+sortOrder).val();
											cntPerPageVal = $('#'+cntPerPage).val();
											//alert(sortByVal);
											//alert(sortOrderVal);
											//alert(cntPerPageVal);
											
											if(pageVal == 'category')
											{
												$j('#catdet_sortby').val(sortByVal);
												$j('#catdet_sortorder').val(sortOrderVal);
												$j('#catdet_prodperpage').val(cntPerPageVal);
											}
											else if(pageVal == 'search')
											{
												$j('#search_sortby').val(sortByVal);
												$j('#search_sortorder').val(sortOrderVal);
												$j('#search_prodperpage').val(cntPerPageVal);
											}
											
											SearchProducts('<?=$res_div?>')
										}
										function pageAction(pageNum,pageVal)
										{
											var sortBy,sortOrder,cntPerPage;
											var sortByVal,sortOrderVal,cntPerpageNum;
											if(pageVal == 1)
											{
												sortBy		=	'catdet_sortbytop';
												sortOrder	=	'catdet_sortordertop';
												cntPerPage	=	'catdet_prodperpagetop';
											}
											else if(pageVal == 2)
											{
												sortBy		=	'searchprod_sortbytop';
												sortOrder	=	'searchprod_sortordertop';
												cntPerPage	=	'searchprod_prodperpagetop';
											}
											
											sortByVal = $j('#'+sortBy).val();
											sortOrderVal = $j('#'+sortOrder).val();
											cntPerpageNum = $j('#'+cntPerPage).val();
											//alert(pageVal);
											if(pageVal == 1)
											{
												$j('#catdet_sortby').val(sortByVal);
												$j('#catdet_sortorder').val(sortOrderVal);
												$j('#catdet_prodperpage').val(cntPerpageNum);
												//alert($('#catdet_prodperpage').val());
											}
											else if(pageVal == 2)
											{
												$j('#search_sortby').val(sortByVal);
												$j('#search_sortorder').val(sortOrderVal);
												$j('#search_prodperpage').val(cntPerpageNum);
												//alert($('#search_prodperpage').val());
											}
											$j('#page_val').val(pageNum);
											SearchProducts('<?=$res_div?>')
										}
										</script>
                                        <table width="100%" cellpadding="0" cellspacing="0" class="refine_container">
                                        <tr>
                                        <td>
                                        <div class="refine_title_container">
                                        	<div class="refine_main_title">Refine By 
                                            	<div id="clear_all" style="display:none;"><a href="javascript: void(0);" onclick="javascript: clearAllCheck('content_container');">Clear All</a></div>
                                        	</div>
                                           
                                        </div>
                                        <div class="refine_slider_container">
                                            <div class="demo">
                                                <input type="hidden" name="minprice" id="minprice" value="<?=ceil($min_price)?>" />
                                                <input type="hidden" name="maxprice" id="maxprice" value="<?=ceil($max_price)?>" />
                                                <span id="clear_pricerange" style="display:none; "><a href="javascript:void(0);"onclick="javascript: clearCheck('clear_pricerange');" class="refine_sub_clear">Clear</a></span>
                                                <p class="refine_price_slider">
                                                    <label for="amount">Price range:</label>
                                                    <input type="text" id="amount" style="border:0; color:#000; margin-bottom:5px;" />
                                                </p>
                                                <div class="refine_slider_display">	<div id="slider-range"></div></div>
                                            </div><!-- End demo -->
                                        </div>
											<form name="frmProductSearch_filter" id="frmProductSearch_filter" action=""  onSubmit="return false">
											<?php
												if(count($variables)>0)
												{
													?>
													<div id="content_container">
														<? 
														$pev_var ='';
														foreach ($variables as $key_var=>$variable)
														{
														 if($variable!=$pev_var)
														 {
															$divIDVal	=	strtolower(str_replace(" ","",str_replace("(","",str_replace(")","",str_replace("-","",str_replace("_","",$variable))))));
														 ?>
														 <table border="0" cellpadding="0" cellspacing="0" width="100%">
														 <tr>
														 <td align="left" class="refine_variable_title" colspan="2">
														 	<div class="refine_active" onclick="javascript:hideDiv('<?php echo $divIDVal;?>');" id="click_<?php echo $divIDVal;?>"><img src="<?php echo $downImg;?>" width="20" height="20" align="down" /></div>
															<span class="refine_sub_title"><?php echo $variable;?></span>
                                                            <a href="javascript:void(0);" style="display:none;" id="clear_<?php echo $divIDVal;?>" onclick="javascript: clearCheck('<?php echo $divIDVal;?>');" class="refine_sub_clear">Clear</a>
														  </td>
														  </tr>
														  <tr>
														  <td colspan="2" class="refine_variable_values">
                                                          <div id="<?php echo $divIDVal;?>" class="refine_variable_container">
														  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="refine_variable_table">
														  <?php
														$AdvSearchVariablesOptions = "SELECT  var_value,var_value_id 
													FROM 
														product_variable_data ,
														product_variables 
													WHERE 
														product_variables.var_id=product_variable_data.product_variables_var_id 
														AND product_variables.products_product_id IN ($prod_str) 
														AND product_variables.var_name='".addslashes($variable)."' 
													GROUP BY 
														var_value
													ORDER BY 
														var_value ASC	
														;";
														$rstAdvSearchVariablesOptions = $db->query($AdvSearchVariablesOptions);
															if ($db->num_rows($rstAdvSearchVariablesOptions))
															{
																while ($rowAdvSearch = $db->fetch_array($rstAdvSearchVariablesOptions))
																{
															?> <tr>
														      	<td width="7%" align="left">
															<input type="checkbox" id="<?php echo stripslashes($rowAdvSearch['var_value'])?>"  name="filtersearchVariableOption_<?=$key_var?>_<?=$rowAdvSearch['var_value_id']?>" onclick="javascript: manageClear('<?php echo $divIDVal;?>');"  value="<?php echo stripslashes($rowAdvSearch['var_value']);?>" />
															</td>
															<td width="80%">
																<span class="refine_label"><?php echo stripslashes($rowAdvSearch['var_value'])?></span>
																</td></tr>
																<?php		
																}
															}
															?>
															
															</table>
                                                            </div>
															</td>
															<?php
														 }	
															$pev_var = $variable;
														}
													?>
													</tr></table>
													</div>												
													<?
												 }
												?>
											<input type="hidden" id="Min_price" name="search_minprice" value="<?=$_REQUEST['search_minprice']?>" />
											<input type="hidden" id="Max_price" name="search_maxprice" value="<?=$_REQUEST['search_maxprice']?>" />
											<input type="hidden" id="fromajax_searchfilter" name="fromajax_searchfilter" value="true" />
											<input type="hidden" id="quick_search" name="quick_search" value="<?=$_REQUEST['quick_search']?>" />
											
											<input type="hidden" id="search_meth" name="search_meth" value="<?=$_REQUEST['search_meth']?>" />
											<input type="hidden" id="search_submit" name="search_submit" value="<?=$_REQUEST['search_submit']?>" />
											<input type="hidden" id="search_pg" name="search_pg" value="<?=$_REQUEST['search_pg']?>" />
											<input type="hidden" id="search_sortby" name="search_sortby" value="<?=$_REQUEST['search_sortby']?>" />
											<input type="hidden" id="search_sortorder" name="search_sortorder" value="<?=$_REQUEST['search_sortorder']?>" />
											
											<input type="hidden" name="search_category_id" value="<?=$_REQUEST['search_category_id']?>" />
											<input type="hidden" name="search_model" value="<?=$_REQUEST['search_model']?>" />
											<input type="hidden" name="search_minstk" value="<?=$_REQUEST['search_minstk']?>" />

											<input type="hidden" name="req" value="<?=$_REQUEST['req']?>" />

											<input type="hidden" id="search_prodperpage" name="search_prodperpage" value="<?=$_REQUEST['search_prodperpage']?>" />
											<input type="hidden" id="searchVariableName" name="searchVariableName" value="<?=$_REQUEST['searchVariableName']?>" />
											<input type="hidden" id="searchVariableOption" name="searchVariableOption" value="<?=$_REQUEST['searchVariableOption']?>"/>
											
											<input type="hidden" id="category_id" name="category_id" value="<?=$_REQUEST['category_id']?>" />
											<input type="hidden" id="catdet_sortby" name="catdet_sortby" value="<?=$_REQUEST['catdet_sortby']?>" />
											<input type="hidden" id="catdet_sortorder" name="catdet_sortorder" value="<?=$_REQUEST['catdet_sortorder']?>" />
											<input type="hidden" id="catdet_prodperpage" name="catdet_prodperpage" value="<?=$_REQUEST['catdet_prodperpage']?>" />
											<input type="hidden" id="catdet_pg" name="catdet_pg" value="<?=$_REQUEST['catdet_pg']?>" />

											<input type="hidden" id="res_div" name="res_div" value="<?=$_REQUEST['res_div']?>" />
                                            <input type="hidden" id="page_val" name="page_val" value="<?=$_REQUEST['page_val']?>" />
											
											</form>
                                            </td>
                                            </tr>
                                            </table>
		<?php
		    }//End of produt number checking	
		  }//ordinary search checking
		}

	};
?>