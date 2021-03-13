
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
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
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
											ORDER BY 
												static_pages_order DESC";
								
								$ret_pg = $db->query($sql_pg);
								$cnt = $db->num_rows($ret_pg);
								//if($show_bottom==0)
								{
									if ($grpData['group_showhomelink']==1)
									{
							?>
										<li><h1> <a href="<?php url_link('')?>" class="static_main" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a> </h1></li>
							<?php
									}
									//$show_bottom=1;	
								}
								while ($row_pg = $db->fetch_array($ret_pg))
								{
									$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
								?>
										<li><h1> <a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="static_main" title="<?php echo stripslashes($row_pg['title'])?>" <?php echo $target?>><?php echo stripslashes($row_pg['title']);?></a></h1></li>
								<?php
								}
								
									if ($grpData['group_showhelplink']==1 )
									{
					?>		
										<li><h1> <a href="<?php url_link('help.html')?>" class="static_main" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></a></h1></li>
					<?php
									}
									if ($grpData['group_showfaqlink']==1 )
									{
					?>		
										<li><h1> <a href="<?php url_link('faq.html')?>" class="static_main" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_FAQ']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_FAQ']?></a></h1></li>
					<?php
									}
									if ($grpData['group_showxmlsitemaplink']==1 )
									{
									?>
										<li><h1> <a href="<?php url_link('sitemap.xml')?>" class="static_main" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></a></h1></li>
									<?php	
									}
									 
									if ($grpData['group_showsitemaplink']==1 )
									{
					?>							
										<li><h1> <a href="<?php url_link('sitemap.html')?>" class="static_main" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></a></h1></li>
					<?php		
									}
									if ($grpData['group_showsavedsearchlink']==1)
									{
					?>							
										<li><h1> <a href="<?php url_link('saved-search.html')?>" class="static_main" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></h1></li>
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
							<tr>
								<td <?php echo ($_REQUEST['req']=='')?'colspan="2"':'colspan="2"'?> style="text-align:left;vertical-align:top" class="categorybottomlinkstd">
									<ul class="bottomlinks">
								
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
											ORDER BY 
												static_pages_order";
								$ret_pg = $db->query($sql_pg);
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
							</td>
						</tr>
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
												<li><h1><a href="<?php url_link('')?>" class="staticleftlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a></h1></li>
									<?php		
											}
											//$show_top = 1;
										}	
								while ($row_pg = $db->fetch_array($ret_pg))
								{
									$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
								?>
										<li><h1><a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="staticleftlink" title="<?php echo stripslashes($row_pg['title'])?>" <?php echo $target?>><?php echo stripslashes($row_pg['title']);?></a></h1></li>
								<?php
								}
								//if($show_bottom==0)
								{
									if ($grpData['group_showsitemaplink']==1)
									{
							?>
										<li><h1><a href="<?php url_link('sitemap.html')?>" class="staticleftlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></a></h1></li>
							<?php		
									}
									if ($grpData['group_showxmlsitemaplink']==1 )
									{	
							?>
										<li><h1><a href="<?php url_link('sitemap.xml')?>" class="staticleftlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></a></h1></li>
							<?php		
									}
									if ($grpData['group_showhelplink']==1)
									{
							?>
										<li><h1><a href="<?php url_link('help.html')?>" class="staticleftlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></a></h1></li>
							<?php		
									}
									if ($grpData['group_showsavedsearchlink']==1)
									{
						?>
										<li><h1><a href="<?php url_link('saved-search.html')?>" class="staticleftlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></h1></li>
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
											ORDER BY 
												static_pages_order";
								$ret_pg = $db->query($sql_pg);
								$cnt	= $db->num_rows($ret_pg);
								
					?>
	                        <table style="width:100%;border:0;border-spacing: 0;border-collapse: collapse;" class="general_links_table">	
		  					<tr>
		    					<td class="general_links_hdr">
	                <?php 
	                        if($prev_grp != $grpData['group_id'])
							{
								if ($grpData['group_hidename']==0) // Decide whether or not to show the groupname
								{
									echo stripslashes($title);
								}
								$prev_grp = $grpData['group_id'];
							}	
		  			?>			</td>
		  					</tr>
	  						<tr>
    							<td class="general_links_lnk">
    							<div class="general_links_div">
                                  <ul class="general_links_divul">
                                  <?php 
                                  	if ($grpData['group_showhomelink']==1)
									{
									?>
										<li><a href="<?php url_link('')?>" class="general_links_div_link" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a></li>
									<?php		
									}
                                	while ($row_pg = $db->fetch_array($ret_pg))
									{
										$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
                                  
                                  ?>
                                  		<li><a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="general_links_div_link" title="<?php echo stripslashes($row_pg['title'])?>" <?php echo $target?>><?php echo stripslashes($row_pg['title']);?></a></li>
                                  <?php 
									}
									if ($grpData['group_showsitemaplink']==1)
									{
							?>
										<li><a href="<?php url_link('sitemap.html')?>" class="general_links_div_link" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></a></li>
							<?php		
									}
									if ($grpData['group_showxmlsitemaplink']==1 )
									{	
							?>
										<li><a href="<?php url_link('sitemap.xml')?>" class="general_links_div_link" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></a></li>
							<?php		
									}
									if ($grpData['group_showhelplink']==1)
									{
							?>
										<li><a href="<?php url_link('help.html')?>" class="general_links_div_link" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></a></li>
							<?php		
									}
									if ($grpData['group_showsavedsearchlink']==1)
									{
						?>
										<li><a href="<?php url_link('saved-search.html')?>" class="general_links_div_link" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></li>
						<?php			
									}
                                  ?>
                                  </ul> 
                                  </div></td>
  							</tr>
							</table>
					<?php 
					}
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
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
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
            <tr>
            <td style="text-align:right;vertical-align:top" class="top_cart_x">
				<div id="shoppingcart_ajax_container">
				<table style="width:100%;border:0;padding:0;border-spacing:0;" class="top_cart_x_table">
			  <tr>
			  	<td class="topcarttd_1"><a href="#" onclick="gobackto_cart('<?php echo ($protectedUrl)?base64_encode($ecom_hostname):$ecom_hostname?>')" class="cart_view_link" title="<?php echo stripslash_normal($Captions_arr['COMMON']['VIEW_CART'])?>"><div class="top_basket_hdr">Your Basket</div></a><div class="top_basket_txt"> <?php echo  (get_session_var('cart_total_items'))?get_session_var('cart_total_items'):0?>&nbsp;&nbsp;<?php echo stripslash_normal($Captions_arr['COMMON']['ITEMS_IN_CART'])?> - <?php echo $cart_tot?></div></td>
			  </tr>
			  <tr>
			  	<td class="topcarttd_2"><a href="#" class="cart_view_link" onclick="handle_checkout_submit('<?php echo $ecom_hostname?>',0,'<?php echo $pass_tot?>')"  title="<?php echo stripslash_normal($Captions_arr['COMMON']['CHECK_OUT'])?>"><img src="<?php url_site_image('chk-out.png') ?>" alt="checkout"  /></a></td>

			  </tr>
			  </table></div>
			  <div class="cart_icon_top" onclick="gobackto_cart('<?php echo ($protectedUrl)?base64_encode($ecom_hostname):$ecom_hostname?>')" style="cursor:pointer"></div>
			  </td>
              </tr>
			<?php
			}
			if($position=='right' || $position=='left')
			{
			?>
				
			<?php
			}
		}
		
		
		// ####################################################################################################
		// Function which holds the display logic for product category groups
		// ####################################################################################################
		function mod_productcatgroup($grp_array,$title)
		{
			global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$ecom_themeid;
					if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
			
			// Getting the required sort by field and sort order
				$sort_by 	=  $Settings_arr['category_orderfield'];
				if($sort_by=='cname') $sort_by = 'category_name';
				$sort_by 	=  ($sort_by=='custom')?'b.category_order':$sort_by;
				$sort_order =  $Settings_arr['category_orderby'];
				$sort_str	= " $sort_by $sort_order ";
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
												ORDER BY 
													b.category_order";
													//$sort_str";
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
																$center_point =2;
																$cursubcat_cnt = 0;
																$pass_type = 'image_thumbpath';
																?>
																<li><a href="<?php url_link('')?>" class="category_home category_home_icon" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><img src="<?php url_site_image('home-icn.png')?>" alt="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"/></a></li>
																<?php
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
																			$img_str = "background: url(".$img_url.")  100% 50% no-repeat;";
																		else
																			$img_str = '';
																			
																			
																			$link_req	= true;
											
																		global $load_mobile_theme_arr;
																		
																			// Check whether there exists first level subcategories for current category
																		$sql_subcat = "SELECT category_id,category_name 
																						FROM 
																							product_categories 
																						WHERE 
																							parent_id =".$row_cat['category_id']." 
																							AND sites_site_id = $ecom_siteid 
																							AND category_hide = 0 
																						ORDER BY 
																							category_order";
																		$ret_subcat = $db->query($sql_subcat);
																		$num_rows	= $db->num_rows($ret_subcat);
																		
																		
																		if($load_mobile_theme_arr[0]==1 and $num_rows>0) 
																		{
																					$link_req = false;
																		}	
																?>
																	
								
																	<li>
																	<?php
																	if($link_req==true)
																	{
																	?>	
																		<a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="category_menu" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><span><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></span></a>
																	<?php
																	}
																	else
																	{
																	?>
																	<span><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></span>
																	<?php
																	}
																	?>
																<?php
																	
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
																			$align_cls	= 'align-center4';
																		$cursubcat_cnt++;
																?>
																		<div class="dropdown <?php echo $align_cls.' '.$nav_cls?>">
																		<div class="dropdown_inner_cls">
																		<div style="float:left; width: 700px;border: 0 solid #e80bac;">	
																		<div class='mainimage_cls' style=" z-index:999; float:left;<?php echo $img_str?> width:780px; height:100%">
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
																		<div class="menuadvert_div"><div>&nbsp;<img src="<?php url_site_image('banners.png')?>" alt="Advert" /></div></div>
																		</div>
																		</div>
																		</div>	
																<?php		
																	}		
																?>	
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
												ORDER BY 
													$sort_str";
									$ret_cat = $db->query($sql_cat);
									if ($db->num_rows($ret_cat))
									{
										// Check the listing type for categories in category group
										if ($grpData['catgroup_listtype']== 'Menu')// case if categories are to be shown in list menu
										{
										?>
											<tr>
												<td colspan="3" align="right" valign="top" class="categorybottomstd">
													<ul class="categorybottom">
										<?php
											while ($row_cat = $db->fetch_array($ret_cat))
											{
										?>
														<li><h1><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="categorybottom" title="<?php echo stripslashes($row_cat['category_name'])?>"><?php echo stripslashes($row_cat['category_name']);?></a></h1></li>
										<?php
											}
										?>
													</ul>
												</td>
											</tr>
										<?php		
										}
									}
								}
							}
						
						}// End of bottom
						elseif ($position=='left' || $position=='right') // ############## Left ##############
						{
							$prev_grp = 0;
							global $refine_displayed;
							
							if(count($grp_array) and !$refine_displayed)
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
											/*if($row_cat['category_displaytype']=='Normal' && $row_cat['category_islink'])
											{
											?>
												 <li><h1><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="catelink" title="<?php echo stripslashes($row_cat['category_name'])?>"><?php echo stripslashes($row_cat['category_name']);?></a></h1></li>
											<?php
											}
											elseif($row_cat['category_displaytype']=='Normal' && !$row_cat['category_islink'])
											{
											?>
												 <li><h1><?php echo stripslashes($row_cat['category_name']);?></h1></li>
											<?											
											}
											elseif($row_cat['category_displaytype']=='Heading' && $row_cat['category_islink'])
											{
											?>
												 <li><h1><span class="subcategoryheader"><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="subcategoryheaderlink" title="<?php echo stripslashes($row_cat['category_name'])?>"><?php echo stripslashes($row_cat['category_name']);?></a></span></h1></li>
											<?
											}
											elseif($row_cat['category_displaytype']=='Heading' && !$row_cat['category_islink'])
											{
											?>
												 <li><h1><span class="subcategoryheader"><?php echo stripslashes($row_cat['category_name']);?></span></h1></li>
											<?
											}*/
											
											$sql_child = "SELECT category_id,category_name,default_catgroup_id
																		FROM 
																			product_categories 
																		WHERE 
																			parent_id=".$row_cat['category_id']." 
																			AND sites_site_id=$ecom_siteid 
																			AND category_hide = 0 
																		ORDER BY category_order";
											$ret_child = $db->query($sql_child);
											if ($db->num_rows($ret_child))
												$maincat_class = 'menuheader expandable';
											else
												$maincat_class = 'menuheader_blank fade';
											
												
											$link_req	= true;
											
											global $load_mobile_theme_arr;
											
											if($load_mobile_theme_arr[0]==1) 
											{
												if ($row_cat['category_subcatlisttype']=='List' or $row_cat['category_subcatlisttype']=='Both')
												{	
													if($maincat_class == 'menuheader expandable')
													{
														$link_req = false;
													}
												}
											}	
											if($link_req==true)
											{
											?>
											<div class="<?php echo $maincat_class?>"><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" title="<?php echo stripslashes($row_cat['category_name'])?>"><?php echo stripslashes($row_cat['category_name']);?></a></div>
											<?php
											}
											else
											{
											?>
												<div class="<?php echo $maincat_class?>"><?php echo stripslashes($row_cat['category_name']);?></div>
											<?php
											}
											
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
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
			$checkout_link = get_Checkoutlink(1);
			if($_REQUEST['req']=='cart') // if current page is related to cart then call cartcalc function to refresh the cart totals and number of items in cart
			{
			
				$cartData = cartCalc(); // calling cart calc 
				return;
			}	
			
			if ($position=='topband1') // show only if position value is top
			{
				$cart_tot = print_price(get_session_var('cart_total'),true);
				$pass_tot = print_price(get_session_var('cart_total'),true,true);
				
				/* Code for search auto complete product list starts here */
				//if($enable_auto_search == 1)
				//{
					
					?>
					
					<?php
					/*

					echo '
							<script type="text/javascript">
							var $acnc = jQuery.noConflict();
							$acnc().ready(function() {
								$acnc("#quick_search").autocomplete(source: "'.url_head_link('includes/autocomplete_product_search.php',1).'", {
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
						 */ 
						  
				//}
				/* Code for search auto complete product list ends here */
		?>				
					<div class="topsearch" style="text-align:left">
					<form method="post" name="frm_quicksearch" class="frm_cls" action="<?php url_link('search.html')?>">
						<table style="border:0;float:right;border-spacing: 0;border-collapse: collapse;">
						<tbody>
						<tr>
							<td class="inputAB"><input name="quick_search" type="text" class="inputAB" id="quick_search"  value="Search Discount Mobility" onFocus="if (this.value=='Search Discount Mobility') this.value='';" onBlur="if (this.value=='') this.value='Search Discount Mobility';"/>
							<input name="button_submit_search" type="submit" class="buttongrayB" id="button3" value="<?php echo $Captions_arr['COMMON']['SEARCH_GO']?>" onclick="show_wait_button(this,'Please wait...')" /></td>
							<!--<td class="searchfont"><a href="<?php url_link('advancedsearch.html')?>" class="advancedsearch" title="<?php echo $Captions_arr['COMMON']['ADVANCED_SEARCH']?>"><?php echo $Captions_arr['COMMON']['ADVANCED_SEARCH']?></a></td>-->
						</tr>
						</tbody>
						</table>
						<input type="hidden" name="search_submit" value="search_submit" />
					</form>
					</div>
					<?php
					if($Settings_arr['enable_search_autocomplete'] == 1)
					{
						?>
						<script type="text/javascript">
						jQuery.noConflict();
						var $j = jQuery;
						$j("#quick_search").autocomplete({
						source: "<?php echo url_head_link('includes/autocomplete_product_search_new.php',1) ?>",
						minLength: 1,
						select: function(event, ui) {
						if(ui.item){
						$j(event.target).val(ui.item.value);
						}
						//submit the form
						$j(event.target.form).submit();
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
			global $Captions_arr,$ecom_hostname,$inlineSiteComponents,$Settings_arr,$db,$ecom_siteid,$loginUrl,$ecom_fb_enable,$position;
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
			$Captions_arr['CUST_LOGIN'] = getCaptions('CUST_LOGIN');
			$cust_id 					= get_session_var("ecom_login_customer");
			/* FB login script */
			$cust_fbid 				= get_session_var("ecom_login_customer_fbid");
			//echo $cust_fbid;echo "<br>";
			?>
			<script type="text/javascript">
				jQuery.noConflict();
		var $ajax_j = jQuery; 
		$ajax_j(document).ready(function() {
			$ajax_j('#password-clear1').show();
			$ajax_j('#custlogin_pass1').hide();
			$ajax_j('#password-clear1').focus(function() {
				$ajax_j('#password-clear1').hide();
				$ajax_j('#custlogin_pass1').show();
				$ajax_j('#custlogin_pass1').focus();
			});
			$ajax_j('#custlogin_pass1').blur(function() {
				if($ajax_j('#custlogin_pass1').val() == '') {
					$ajax_j('#password-clear1').show();
					$ajax_j('#custlogin_pass1').hide();
				}
			});
			$ajax_j('#custlogin_uname1').each(function() {
				var default_value = this.value;
				$ajax_j(this).focus(function() {
					if(this.value == default_value) {
						this.value = '';
					}
				});
				$ajax_j(this).blur(function() {
					if(this.value == '') {
						this.value = default_value;
					}
				});
			});
		
		});
		</script>
		<?php
			if (!$cust_id) // case customer is not logged in
			{
			if($position=='right' || $position=='left')
				{
	
				
			if($Settings_arr['showcustomerlogin_as_banner']==1) // Decide whether customer login is to be displayed as banner or in normal style
			{
			?>
			<div class="login_banner"> 
							  <div class="signup_btn"><a href="<?php url_link('registration.html')?>" title="<?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['NEW_USER'])?>"><img src="<?php url_site_image('signup-btn.png')?>" border="0" /></a></div>
					          <div class="login_btn"><a href="<?php url_link('custlogin.html')?>"><img src="<?php url_site_image('login-btn.png')?>"  border="0"/></a></div>
				</div>
                <!--FB Login Button -->
				<?php if($ecom_fb_enable == 1) { ?>
                <div class="fblogin_btn_sml"><a href="javascript:void(0);" onclick="javascript:window.open('<?php echo $loginUrl;?>','Login','width=500,height=300,resizable=no,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=no,copyhistory=no');"><img src="<?php echo url_site_image('facebook_logins.png');?>" /></a></div>
                <?php } ?>
			<?php
			}
			else
			{
				$hide_newuser 		=  $Settings_arr['hide_newuser'];
				$hide_forgotpass 	=  $Settings_arr['hide_forgotpass'];
		?>
				<form name="frm_custlogin" id="frm_custlogin" method="post" action="<?php echo $ecom_selfhttp.$ecom_hostname.$_SERVER['REQUEST_URI']?>" onsubmit="return validate_login(this)" class="frm_cls">
				<table style="border:0;border-spacing: 0;border-collapse: collapse;" class="logintable">
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
					<td style="text-align:right;vertical-align:top" class="logintablecontentright" colspan="2">
						<input name="custlogin_uname" type="text" class="inputA" id="custlogin_uname1" value="Email" />
					</td>
				</tr>
				<tr>
					<td style="text-align:right;vertical-align:top" class="logintablecontentright" colspan="2">
						<input id="password-clear1" type="text" value="Password" autocomplete="off"  class="login_inner_inputA"/>
						<input name="custlogin_pass" type="password" class="inputA" id="custlogin_pass1" />
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td style="text-align:right;vertical-align:top" class="logintablesubmit"> <input name="custologin_Submit" type="submit" class="buttongray" id="custologin_Submit" value="<?php echo $Captions_arr['CUST_LOGIN']['LOGIN']?>" />
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:right">
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
		}
		
		// ####################################################################################################
		// Function which holds the display logic for call back request 
		// ####################################################################################################
		function mod_callback($title)
		{
		?>
		<div class="Banner_wrap">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="width:15px;vertical-align:top" class="bannerLeft">
    </td>
    <td  style="vertical-align:top" class="bgbanner"><a href="<?php url_link('callback.html')?>"><img src="<?php url_site_image('request_call.png')?>" alt="Call Back" title="Call back request" border="0"  /></a></td>
    <td  style="width:15px;vertical-align:top;text-align:top"  class="bannerRight">
    
    </td>
  </tr>
</table></div>
		
		<?php		
		}
		// ####################################################################################################
		// Function which holds the display logic for best sellers
		// ####################################################################################################
		function mod_bestsellers($ret_main,$title,$display_id)
		{
			global $ecom_siteid,$db,$ecom_hostname,$position,$Captions_arr,$Settings_arr;
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
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
			?>		
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bst_slr_table_x">
			<?php		
			if($title) // check whether title exists
			{
			?>		
			<tr><td class="bst_slr_table_hdr"><?php echo $title?></td></tr>
			<?php		
			}
			?>		
			<tr>
			<td>
			<div class="left_comp">  
			<div id="<?php echo $scrollup_id;?>"></div> 
			<div id="<?php echo $scrollbox_id;?>">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" >
			<?php		while ($row_main = $db->fetch_array($ret_main))
			{
			?>				
			<tr>
			<td class="bst_slr_table_pdt">
			<div class="bst_slr_pdt_otr">
			<div class="bst_slr_pdt_img" >
			<a href="<?php echo url_product($row_main['product_id'],$row_main['product_name'],-1)?>" title="<?php echo stripslash_normal($row_main['product_name'])?>">
			<?php			// Calling the function to get the type of image to shown for current 
			//$pass_type = 'image_thumbcategorypath';
			$pass_type = get_default_imagetype('combshelf');
			// Calling the function to get the image to be shown
			$img_arr = get_imagelist('prod',$row_main['product_id'],$pass_type,0,0,1);
			if(count($img_arr))
			{
			show_image(url_root_image($img_arr[0][$pass_type],1),$row_main['product_name'],$row_main['product_name'],'');
			}
			else
			{
			// calling the function to get the default image
			$no_img = get_noimage('prod',$pass_type); 
			if ($no_img)
			{
			show_image($no_img,$row_main['product_name'],$row_main['product_name'],'');
			}
			}
			?>								
			</a>
			</div>
			<div class="bst_slr_pdt_name">
			<a href="<?php url_product($row_main['product_id'],$row_main['product_name'],-1)?>" class="bst_slrprolink" title="<?php echo stripslashes($row_main['product_name'])?>"><?php echo stripslashes($row_main['product_name'])?></a>
			</div>
			<div class="bst_slr_pdt_price_a">
			<?php						
			$price_arr =  show_Price($row_main,array(),'compshelf',false,4);
			
				if($price_arr['discounted_price'])
				{
					echo $price_arr['discounted_price'];
					if($price_arr['emi'])
						echo '<div class="emi_price_default">'.$price_arr['emi'].'</div>';
				}	
				else
				{
					echo $price_arr['base_price'];
					if($price_arr['emi'])
						echo '<div class="emi_price_default">'.$price_arr['emi'].'</div>';
				}		
			$frm_name = uniqid('best_');
			?>							</div>
			<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_main['product_id']?>)">
			<input type="hidden" name="fpurpose" value="" />
			<input type="hidden" name="fproduct_id" value="" />
			<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
			<input type="hidden" name="fproduct_url" value="<?php url_product($row_main['product_id'],$row_main['product_name'])?>" />
			<div class="bst_slr_pdt_buy">
			<?php				
			$class_arr['ADD_TO_CART']       = '';
					$class_arr['PREORDER']          = '';
					$class_arr['ENQUIRE']           = '';
					$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
					$class_arr['QTY']               = ' ';
					$class_td['QTY']				= 'bst_slr_pdt_buy_a';
					$class_td['TXT']				= 'bst_slr_pdt_buy_b';
					$class_td['BTN']				= 'bst_slr_pdt_buy_c';
					echo show_addtocart_v5($row_main,$class_arr,$frm_name,false,'','',true,$class_td);										?>
			</div>
			</form>
			</div>
			</td>
			</tr>
			<?php		}
			?>				
			</table>
			</div>
			<div id="<?php echo $scrolldown_id;?>"></div>
			</div>
			</td>
			</tr>
			<!--<tr><td align="right"><a href="<? url_link('bestsellers'.$display_id.'.html')?>" class="viewallshelfprod" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a></td></tr>-->
			</table>
			<script type="text/javascript">
			var <?php echo $scroll_id; ?>= new TextScroll('<?php echo $scroll_id; ?>', '<?php echo $scrollbox_id;?>', '<?php echo $scrollup_id;?>', '<?php echo $scrolldown_id;?>');
			</script>

			<?php	}
			}	
		}
		// ####################################################################################################
		// Function which holds the display logic for best sellers
		// ####################################################################################################
		function mod_compare_products($ret_compare_pdts,$title='Compare PrOducts')
		{
			global $ecom_siteid,$db,$ecom_hostname,$position,$Captions_arr,$Settings_arr;
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
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
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
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
									?>	
										  <div class="lf_combodeal_price">Bundled Price: <?php echo print_price($bundle_price)?></div>
									  </div>
											<div class="lf_combodeal_bottom"> <a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" class="lf-combodeal-showall" title="<?php echo $Captions_arr['COMMON']['SHOW_DET']?>"><?php echo $Captions_arr['COMMON']['SHOW_DET']?></a> </div>
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
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}		
			$Captions_arr['PROD_DETAILS'] 	= getCaptions('PROD_DETAILS');
			if($_REQUEST['req']=='cart')
				return;
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
											ORDER BY 
												$shelfsort_by $shelfsort_order 
											LIMIT 
												$limit";
						
						$ret_prod = $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{
								$sql_gtparam = "SELECT * FROM finance_paymentgateway_details WHERE sites_site_id = $ecom_siteid LIMIT 1";
											$ret_gtparam = $db->query($sql_gtparam);
											if($db->num_rows($ret_gtparam))
											{
												$row_gtparam = $db->fetch_array($ret_gtparam);
												$API_key = trim($row_gtparam['finpay_apikey']);
												$INST_Id = trim($row_gtparam['finpay_installationid']);
											}
											$sql_getc = "SELECT finance_id,finance_rate,finance_code FROM finance_details WHERE sites_site_id = $ecom_siteid and finance_code='ONIB48-15.9' LIMIT 1";
											$ret_getc = $db->query($sql_getc);
											if($db->num_rows($ret_getc))
											{
											$row_getc = $db->fetch_array($ret_getc);
											$fin_code = $row_getc['finance_code'];
											}
							//echo "shelf style - ".$shelfData['shelf_currentstyle'];echo "<br>";
							/*if($shelfData['shelf_currentstyle']=='nor' || $shelfData['shelf_currentstyle']=='new') // case of normal design layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case '2row': // case of one in a row
									case '3row': // case of three in a row
								?>
									<table border="0" cellpadding="0" cellspacing="0" class="bst_slr_table_x">
								<?php	if ($title)
										{
								?>	<tr><td class="bst_slr_table_hdr"><?php echo $title?></td></tr>
								<?php	}
								?>	<tr><td>
									<!--<div class="bst_slr_table_nav">asdasdasfasfasfasfa</div>-->
									<div class="bst_slr_table_pdts">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" >
								<?php	while($row_prod = $db->fetch_array($ret_prod))
										{
								?>		<tr>
											<td class="bst_slr_table_pdt">
												<div class="bst_slr_pdt_otr">
								<?php		if($shelfData['shelf_showimage']==1) // whether image is to be displayed
											{
								?>					<div class="bst_slr_pdt_img" >
								
														<?php			if($row_prod['product_bulkdiscount_allowed'] == 'Y')
											{
							?>					<div class="prod_lista_bulk"><img src="<?php url_site_image('bulk.png')?>"></div>
							<?php			}
											else
											{
							?>					<div class="prod_lista_bulk_no"></div>
							<?php			}
							?>
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
								<?php			// Calling the function to get the type of image to shown for current 
												$pass_type = get_default_imagetype('combshelf');
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
								?>					</a></div>
								<?php		}
											if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
											{
								?>					<div class="bst_slr_pdt_name">		  
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>
													</div>
								<?php		}
											if($shelfData['shelf_showprice']==1) // whether price is to be displayed
											{
								?>					<div class="bst_slr_pdt_price_a">
								<?php			$price_arr =  show_Price($row_prod,array(),'compshelf',false,4);
												if($price_arr['discounted_price'])
													echo $price_arr['discounted_price'];
												else
													echo $price_arr['base_price'];
								?>					</div>
								<?php		}
											//global $showqty;
											//if($showqty==1)// this decision is made in the main shop settings
											//{
										$frm_name = uniqid('shelf_');

								?>					
								 <form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
											<input type="hidden" name="fpurpose" value="" />
											<input type="hidden" name="fproduct_id" value="" />
											<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
											<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
										
								      <div class="bst_slr_pdt_buy">
								<?php			$class_arr['ADD_TO_CART']       = '';
												$class_arr['PREORDER']          = '';
												$class_arr['ENQUIRE']           = '';
												$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
												$class_arr['QTY']               = ' ';
												$class_td['QTY']				= 'bst_slr_pdt_buy_a';
												$class_td['TXT']				= 'bst_slr_pdt_buy_b';
												$class_td['BTN']				= 'bst_slr_pdt_buy_c';
												echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
								?>					</div>
								</form>
								<?php
											//}
								?>				</div>
											</td>
										</tr>
								<?php	}
								?>		</table>
									</div>									
									<!--<div class="bst_slr_table_nav">asdasdasfasfasfasfa</div>-->
									</td>
								</tr>
								<tr>
									<td align="right" class="compshelfproductbottom">
										<a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="viewallshelfprod" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a>
									</td>
								</tr>
								</table>
								<?php	
									break;									
								};	
							}
							else*/	
							if($shelfData['shelf_currentstyle']=='nor' || $shelfData['shelf_currentstyle']=='new') // case of christmas layout
							{
								//echo "<pre>";
								//print_r($shelfData);die();
								switch($shelfData['shelf_displaytype'])
								{
									case '2row': // case of one in a row
									case '3row':
									//echo "<pre>";print_r($shelfData);
								?>		
								<script  type="text/javascript" src="https://test.dekopay.com/js/libraries/jquery/jquery-3.3.1.min.js"></script>
										<script type="text/javascript" src="https://secure.dekopay.com/js_api/FinanceDetails.js.php?api_key=<?php echo $API_key ?>"></script>
								<table style="padding:0;border-spacing:0; border:0;width:100%" class="shlf_slr_table">
								<?php	if ($title)
								{
								?>		<tr><td class="shlf_slr_table_hdr"><?php echo $title?></td></tr>
								<?php	}
								while($row_prod = $db->fetch_array($ret_prod))
								{
								?>		<tr>
								<td class="shlf_slr_table_pdt">
								<div class="shlf_slr_pdt_otr">
								<?php
								if($row_prod['product_bulkdiscount_allowed'] == 'Y')
								{
								?>					
								<div class="prod_lista_bulk"><img src="<?php  url_site_image('bulk.png')?>"></div>
								<?php			
								}
								else
								{
								?>					
								<div class="prod_lista_bulk_no"></div>
								<?php			
								}

								if($shelfData['shelf_showimage']==1) // whether image is to be displayed
								{
								?>				<div class="shlf_slr_pdt_img">							
								<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
								<?php				// Calling the function to get the type of image to shown for current 
								$pass_type = get_default_imagetype('combshelf');
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
								?>					</a>
								</div>
								
								<?php		}
								if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
								{
								?>
								<div class="bst_slr_pdt_name">
									<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="bst_slrprolink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>
								</div>
								<?php
								}
								if($shelfData['shelf_showdescription']==1) // whether price is to be displayed
								{
								?>				<div class="shlf_slr_pdt_des">
								<?php				echo $row_prod['product_shortdesc'];
								?>				</div>
								<?php		}
                                $price_arr = array();
								if($shelfData['shelf_showprice']==1) // whether price is to be displayed
								{
									
								?>				<div class="shlf_slr_pdt_price">
								<?php			
								$price_class_arr['link_capt'] 	= 'appr_cls';

								$price_arr =  show_Price($row_prod,$price_class_arr,'compshelf',false,3);
								//print_r($price_arr);
								if($price_arr['discounted_price'])
								{
								echo '<div class="shlf_discount_price">'.$price_arr['discounted_price'].'</div>';
							    echo '<div class="shlf_save_price">'.$price_arr['yousave_price'].'</div>';
							    if($price_arr['emi'])
									echo '<div class="emi_price_default">'.$price_arr['emi'].'</div>';
							    }
								else
								{
									echo '<div class="shlf_discount_baseprice">'.$price_arr['base_price'].'</div>';
									if($price_arr['emi'])
										echo '<div class="emi_price_default">'.$price_arr['emi'].'</div>';
								}	
								?>				</div>
								<?php		}
								
								if($row_prod['product_saleicon_show']==1)
								{
								$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
								if($desc!='')
								{
								echo '<div class="shlf_slr_pdt_new">'.$desc.'</div>';
								}
								}
								if($row_prod['product_newicon_show']==1)
								{
								$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
								if($desc!='')
								{
								echo '<div class="shlf_slr_pdt_new">'.$desc.'</div>';
								}
								}
								//echo "<pre>";print_r($row_prod);print_r($shelfData);die();
								if($row_prod['product_bonuspoints']>0 and $shelfData['shelf_showbonuspoints']==1)
								{
								//echo '<div class="shlf_slr_pdt_bonus">'.$row_prod['product_bonuspoints'].' <span>bonus</span></div>';
								/*echo '<div class="prod_details_bonus">';
								$pass_arr['main_cls'] 		= 'prod_list_bonus';
								$pass_arr['caption_cls'] 	= 'bonus_point_caption';
								$pass_arr['point_cls'] 		= 'bonus_point_number';
								show_bonus_points_msg_multicolor($row_prod,$pass_arr);
								echo '</div>';*/
								echo '<div class="prod_list_bonusA">
									<span class="bonus_point_number_a"><span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSA']).'</span></span>
									<span class="bonus_point_caption_b">'.$row_prod['product_bonuspoints'].'</span>
									<span class="bonus_point_number_c"><span>'.stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_BONUSPOINTSB']).'</span></span>
									</div>';
								}
								$frm_name = uniqid('shelf_');
  $price_bal2 = show_Price($row_prod,$price_class_arr,'other_3',false,5);
										if($price_bal2['prince_without_captions']['discounted_price'])
										{
											$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['discounted_price']);
											$calcprice  = $calcpricearr[1];
										}
										else
										{
											$calcpricearr = explode('&pound;',$price_bal2['prince_without_captions']['base_price']);
											$calcprice  = $calcpricearr[1];
										}
									   //print_r($price_bal2);		   
											$calcprice = $calcprice + $calcprice*.05;
												   ?>
												   <script type="text/javascript">
							var my_fd_obj = new FinanceDetails("<?php echo $fin_code; ?>", <?php echo $calcprice;?>, 10, 0);
							/*alert('here');*/
								$("#finpermonth_<?php echo $row_prod['product_id']?>").html("&pound;"+(my_fd_obj.m_inst).toFixed(2));
								
							</script> 
											
								
								
								 <form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
								<input type="hidden" name="fpurpose" value="" />
								<input type="hidden" name="fproduct_id" value="" />
								<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
								<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />

								<div class="bst_slr_pdt_buy">
								<?php			$class_arr['ADD_TO_CART']       = '';
								$class_arr['PREORDER']          = '';
								$class_arr['ENQUIRE']           = '';
								$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
								$class_arr['QTY']               = ' ';
								$class_td['QTY']				= 'bst_slr_pdt_buy_a';
								$class_td['TXT']				= 'bst_slr_pdt_buy_b';
								$class_td['BTN']				= 'prod_list_buy_c';
								echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
								?>					</div>
								</form>
								</div>
								</td>
								</tr>
								<?php	}
								?>		
								<tr>
								<td style="text-align:right" class="compshelfproductbottom">
								<a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="viewallshelfprod" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a>
								</td>
								</tr>
								</table>
								<?php	
								break;
								};	
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
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
			$vimgfield = (!$Settings_arr['imageverification_req_newsletter'])?'':'newsletter_Vimg';
			$Captions_arr['NEWS_LETTER'] = getCaptions('NEWS_LETTER');
			if($_REQUEST['req']=='cart')
				return;
			if($Settings_arr['shownewsletter_as_banner']==1) // Decide whether customer login is to be displayed as banner or in normal style
			{
				?>
				<div class="news_letter_banner">
				<a href="<?php url_link('newsletter.html')?>"><img src="<?php url_site_image('neweletter-banner.gif')?>" style="border:0" alt="Newsletter" /></a>
				</div>
				<?php
			}
			else
			{
		?>
			<form name="frm_newsletter" method="post" action="" class="frm_cls" onsubmit="return newsletter_validation(this)">
			<table  style="border:0;border-spacing: 0;border-collapse: collapse;" class="newslettertable">
			<?php
			if ($title)
			{
			?>		
				<tr>
				<td colspan="2" class="newsletterheader"><?php echo $title?></td>
				</tr>
			<?php
			}
			if($Settings_arr['newsletter_title_req']==1)
			{
			?>	
			<tr>
				<td class="newslettertd"><?php echo $Captions_arr['NEWS_LETTER']['TITLE']?></td>
				<td align="left" valign="top" class="newsletterinput">
				<select name="newsletter_title" class="regiinput" id="newsletter_title" >
				<option value="">Select</option>
				<option value="Mr.">Mr.</option>
				<option value="Mrs.">Mrs.</option>
				<option value="Miss.">Miss.</option>  
				<option value="M/S.">M/S.</option>
				</select>
				</td>
			</tr>	
			<?php
			}
			if($Settings_arr['newsletter_name_req']==1)
			{
			?>
			<tr>
				<td class="newslettertd"><?php echo $Captions_arr['NEWS_LETTER']['NAME']?></td>
				<td align="left" valign="top" class="newsletterinput">
				<input name="newsletter_name" type="text" class="inputA" id="newsletter_name" size="15" />				</td>
			</tr>
			<?php
			}
			?>				 
			<tr>
				<td class="newslettertd"><?php echo $Captions_arr['NEWS_LETTER']['EMAIL']?></td>
				<td align="left" valign="top" class="newsletterinput">
				<input name="newsletter_email" type="text" class="inputA" id="newsletter_email" size="15" />				</td>
			</tr>
			<?php
			if($Settings_arr['newsletter_phone_req']==1)
			{
			?>
			<tr>
				<td class="newslettertd"><?php echo $Captions_arr['NEWS_LETTER']['PHONE']?></td>
				<td align="left" valign="top" class="newsletterinput">
				<input name="newsletter_phone" type="text" class="inputA" id="newsletter_phone" size="15" />				</td>
			</tr>
			<?php
			}
			if($Settings_arr['newsletter_group_req']==1)
			{
				// Check whether any customer groups exists
				$sql_groups = "SELECT custgroup_id,custgroup_name 
								FROM 
									customer_newsletter_group 
								WHERE 
									sites_site_id = $ecom_siteid AND custgroup_active='1'
								ORDER BY custgroup_name ";
				$ret_groups = $db->query($sql_groups);
				if ($db->num_rows($ret_groups))
				{			
				$cust_group_arr = array();
				while ($row_groups = $db->fetch_array($ret_groups))
				{
				$cst_id 					= $row_groups['custgroup_id'];
				$cust_group_arr[$cst_id]	= stripslashes($row_groups['custgroup_name']);
				}						
				?>
				<tr>
					<td colspan="2" valign="top" class="newslettertd"><?php echo $Captions_arr['NEWS_LETTER']['GROUP']?></td>
				</tr>
				<tr>
					<td colspan="2" align="left" valign="top" class="newslettertd">
				<?php
				if (count($cust_group_arr))
				{ 
					echo generateselectbox('newsletter_group[]',$cust_group_arr,0,'','',5,'',false,'sel_newsletter_group');
				}	
				?>				
					</td>
					</tr>
				<?php
				}
			}
			?>
			<?php 
				if($Settings_arr['imageverification_req_newsletter']) // if image verification is required
				{
			?>
			  <tr>
		    	<td align="left" colspan="2" class="newslettertd"><?=$Captions_arr['NEWS_LETTER']['ENTER_CODE']?>&nbsp;<span class="redtext">*</span></td>
			  </tr>
			  <tr>
			     <td align="left" valign="middle" colspan="2" class="newslettertd"><img src="<?php url_verification_image('includes/vimg.php?size=4&pass_vname=newsletter_Vimg')?>" border="0" alt="Image Verification"/></td>
			  </tr>
			  <tr>
			    <td  colspan="2" align="left" class="newslettertd"><span class="newsletterinput">
			      <?php 
					// showing the textbox to enter the image verification code
					$vImage->showCodBox(1,'newsletter_Vimg','class="inputA_imgver"'); 
				?>			</span>	</td>
			 
							  </tr>
		  <?php
				}
			?>
			<tr>
			<td class="newslettertd" align="left" colspan="2" >
				<input name="newsletter_Submit" type="submit" class="buttongray" id="newsletter_Submit" value="<?php echo $Captions_arr['NEWS_LETTER']['SUBSCRIBE']?>" />				</td>

			</tr>
			<tr>
				<td colspan="2" align="right">&nbsp;</td>
			</tr>
			</table>
			</form>
		<?php
		    }
		}
		// ####################################################################################################
		// Function which holds the display logic for Gift Vouchers
		// ####################################################################################################
		function mod_giftvoucher($title)
		{
			global $Captions_arr,$ecom_hostname,$db,$ecom_siteid,$Settings_arr,$vImage,$position;
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
			$Captions_arr['GIFT_VOUCHER'] = getCaptions('GIFT_VOUCHER');
			$vimgfield = (!$Settings_arr['imageverification_req_voucher'])?'':'buycompgiftvoucher_Vimg';
		   ?>
           <div class="gift_buy_banner"><a href="<?php echo get_buyGiftVoucherURL()?>"  title="<?php echo stripslash_normal($Captions_arr['GIFT_VOUCHER']['BUY_VOUCHER'])?>"><img src=" <?php url_site_image('buy-gift.gif') ?>" border="0"  /></a></div>
		<?php	
		}
		// ####################################################################################################
		// Function which holds the display logic for spending giftvoucher or promotional code
		// ####################################################################################################
		function mod_spendvoucher($title)
		{
			global $Captions_arr,$ecom_hostname,$db,$ecom_siteid,$Settings_arr,$vImage,$position;
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
			$Captions_arr['GIFT_VOUCHER'] = getCaptions('GIFT_VOUCHER');
		    if($position=='left' || $position=='right')
			{
			?>
			<div class="UseGift_wrap">
            	<a href="<?php echo $ecom_selfhttp.$ecom_hostname?>/spend_voucher.html"  title="<?php echo stripslash_normal($Captions_arr['GIFT_VOUCHER']['SPEND_VOUCHER_CLICK_HERE'])?>"><img src="<?php url_site_image('use-gift.png')?>" border="0" /></a>
            </div> 
			  
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
			      if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
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
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
			if($_REQUEST['req']=='cart')
				return;
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
											<table  style="border:0;border-spacing: 0;border-collapse: collapse;" class="shopbybrandtable">
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
		{ return;
			global $Captions_arr,$ecom_siteid,$db,$Settings_arr;
					if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
		   	$frm_name = uniqid('recent_');
		   	if($_REQUEST['product_id']>0)
		   {
			   $title = "Related Products";
		   }
		    if($_REQUEST['product_id']>0)
				    {
							$sql_linked = "SELECT 
							a.product_id,a.product_name,a.product_variablestock_allowed,a.product_show_cartlink,
							a.product_preorder_allowed,a.product_show_enquirelink,a.product_webstock,a.product_webprice,
							a.product_discount,a.product_discount_enteredasval,a.product_bulkdiscount_allowed,
							a.product_total_preorder_allowed,a.product_applytax,product_shortdesc,product_bonuspoints,
							a.product_stock_notification_required,a.product_alloworder_notinstock,a.product_variables_exists,a.product_variablesaddonprice_exists,
							a.product_variablecomboprice_allowed,a.product_variablecombocommon_image_allowed,a.default_comb_id,
							a.price_normalprefix,a.price_normalsuffix, a.price_fromprefix, a.price_fromsuffix,price_specialofferprefix, a.price_specialoffersuffix, 
							a.price_discountprefix, a.price_discountsuffix, a.price_yousaveprefix, a.price_yousavesuffix,a.price_noprice,
							a.product_averagerating,a.product_saleicon_show,a.product_saleicon_text,a.product_newicon_show,a.product_newicon_text,
							a.product_freedelivery                    
						FROM 
							products a,product_linkedproducts b 
						WHERE 
							b.link_parent_id=".$_REQUEST['product_id']." 
						AND (b.show_in='P' OR b.show_in='CP') 	
						AND a.sites_site_id=$ecom_siteid 
						AND a.product_id = b.link_product_id 
						AND a.product_hide = 'N' 
						AND b.link_hide=0
						ORDER BY 
							b.link_order";
		              $ret_linked = $db->query($sql_linked);
		              if ($db->num_rows($ret_linked))
						{
  ?>
		   <form method="post" action="" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" >
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
				   
								    $pass_type = get_default_imagetype('midshelf');

							        $cur_row = 1 ;
							        $max_col = 3;
									$col_cnts = 0;
									while($row_prod = $db->fetch_array($ret_linked))
									{
										$col_cnts++;
										if($col_cnts==3)
										{
											$col_cnts =0;
											$maincls = 'catBoxWrap_threecolumn_rt';	
										}
										else
											$maincls = 'catBoxWrap_threecolumn';
										?>
										<div class="<?php echo $maincls?>">
<?php
												show_finanacebanner($row_prod);
												//if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
												{
													?>
																<div class="featureimgqrap_three_column"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php echo getmicrodata_producturl()?> class="product_image" >
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
																</a> </div>
																<?php
												}
																//if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
																{
																?>
																<div class="prod_list_name_link_three_column">
																<span class="shelfBprodname_three_column">
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><span <?php echo getmicrodata_productname()?>><?php echo stripslashes($row_prod['product_name'])?></span></a>
																</span>

																</div>
																<?php
																}
												//if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
												?>										
													<div class="prod_list_price_three">
										<?php			
										            $price_class_arr['ul_class'] 		= 'shelfBul_three_column';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													$price_class_arr['link_capt'] 	= 'appr_cls';

													//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
													echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
												  }
																										
													?>																			
														
										</div>
										<div class="det_all_outer">

										<?php
										//if($shelfData['shelf_showrating']==1)
													{
														$module_name = 'mod_product_reviews';
														if(in_array($module_name,$inlineSiteComponents))
														{
															if($row_prod['product_averagerating']>=0)
															{
																$HTML_rating = display_rating($row_prod['product_averagerating'],1,'star-green.gif','star-white.gif',$row_prod['product_id']);
																?>
																	<div class="starBlock">
																	<div class="ratingStars">
																	<?php
																	echo $HTML_rating;
																	?>
																	</div>
																	</div>
																<?php
															}
														}
													}
										?>
										<?php			
										if($row_prod['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
											//if($desc!='')
											{
						?>						<div class="prod_list_sale_three"><?php //echo $desc?></div>
						<?php				}
										}
										if($row_prod['product_newicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
											//if($desc!='')
											{
						?>						<div class="prod_list_new_three"><?php //echo $desc?></div>
						<?php				}
										}
										?>
										<div class="list_more_div">
												<?php show_moreinfo($row_prod,'list_more')?>
												</div>
												
										<?php
										//if($row_prod['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
											//if($desc!='')
											{
						?>						<div class="prod_list_sale_desc"><?php echo $desc?></div>
						<?php				}
										}
										?>
										</div>
										<?php
																	
													?>
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
																	<div class="prod_list_buy">
													<?php			$class_arr['ADD_TO_CART']       = '';
																	$class_arr['PREORDER']          = '';
																	$class_arr['ENQUIRE']           = '';
																	$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
																	$class_arr['QTY']               = ' ';
																	$class_td['QTY']				= 'prod_list_buy_a';
																	$class_td['TXT']				= 'prod_list_buy_b';
																	$class_td['BTN']				= 'prod_list_buy_c';
																	echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
													?>						</div>
										</div>
										<?php
									}		
						}
					}
				    else
				    {
						?>
						<form method="post" action="" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" >
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
						<?php
					$cookarray 	= explode(",",$cookval);
					foreach ($cookarray as $k=>$v)
					{
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
										products a 
									WHERE 
										a.sites_site_id = $ecom_siteid 
										AND a.product_hide ='N' 
										AND a.product_id =".$v;
						$ret_prod = $db->query($sql_prod);
						if ($db->num_rows($ret_prod))
						{
														$pass_type = get_default_imagetype('midshelf');

							        $cur_row = 1 ;
							        $max_col = 3;
									$col_cnts = 0;
									while($row_prod = $db->fetch_array($ret_prod))
									{
										$col_cnts++;
										if($col_cnts==3)
										{
											$col_cnts =0;
											$maincls = 'catBoxWrap_threecolumn_rt';	
										}
										else
											$maincls = 'catBoxWrap_threecolumn';
										?>
										<div class="<?php echo $maincls?>">
<?php
												show_finanacebanner($row_prod);
												//if ($shelfData['shelf_showimage']==1)// Check whether description is to be displayed
												{
													?>
																<div class="featureimgqrap_three_column"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php echo getmicrodata_producturl()?> class="product_image" >
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
																</a> </div>
																<?php
												}
																//if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
																{
																?>
																<div class="prod_list_name_link_three_column">
																<span class="shelfBprodname_three_column">
																<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><span <?php echo getmicrodata_productname()?>><?php echo stripslashes($row_prod['product_name'])?></span></a>
																</span>

																</div>
																<?php
																}
												//if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
												{
												?>										
													<div class="prod_list_price_three">
										<?php			
										            $price_class_arr['ul_class'] 		= 'shelfBul_three_column';
													$price_class_arr['normal_class'] 	= 'shelfBnormalprice';
													$price_class_arr['strike_class'] 	= 'shelfBstrikeprice';
													$price_class_arr['yousave_class'] 	= 'shelfByousaveprice';
													$price_class_arr['discount_class'] 	= 'shelfBdiscountprice';
													$price_class_arr['link_capt'] 	= 'appr_cls';

													//	echo show_Price($row_prod,$price_class_arr,'shelfcenter_1'); price_categorydetails_1_reqbreak
													echo show_Price($row_prod,$price_class_arr,'cat_detail_1');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
												  }
																										
													?>																			
														
										</div>
										<div class="det_all_outer">

										<?php
										//if($shelfData['shelf_showrating']==1)
													{
														$module_name = 'mod_product_reviews';
														if(in_array($module_name,$inlineSiteComponents))
														{
															if($row_prod['product_averagerating']>=0)
															{
																$HTML_rating = display_rating($row_prod['product_averagerating'],1,'star-green.gif','star-white.gif',$row_prod['product_id']);
																?>
																	<div class="starBlock">
																	<div class="ratingStars">
																	<?php
																	echo $HTML_rating;
																	?>
																	</div>
																	</div>
																<?php
															}
														}
													}
										?>
										<?php			
										if($row_prod['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
											//if($desc!='')
											{
						?>						<div class="prod_list_sale_three"><?php //echo $desc?></div>
						<?php				}
										}
										if($row_prod['product_newicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_newicon_text']));
											//if($desc!='')
											{
						?>						<div class="prod_list_new_three"><?php //echo $desc?></div>
						<?php				}
										}
										?>
										<div class="list_more_div">
												<?php show_moreinfo($row_prod,'list_more')?>
												</div>
												
										<?php
										//if($row_prod['product_saleicon_show']==1)
										{
											$desc = stripslash_normal(trim($row_prod['product_saleicon_text']));
											//if($desc!='')
											{
						?>						<div class="prod_list_sale_desc"><?php echo $desc?></div>
						<?php				}
										}
										?>
										</div>
										<?php
																	
													?>
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
																	<div class="prod_list_buy">
													<?php			$class_arr['ADD_TO_CART']       = '';
																	$class_arr['PREORDER']          = '';
																	$class_arr['ENQUIRE']           = '';
																	$class_arr['QTY_DIV']           = 'normal_shlfA_pdt_input';
																	$class_arr['QTY']               = ' ';
																	$class_td['QTY']				= 'prod_list_buy_a';
																	$class_td['TXT']				= 'prod_list_buy_b';
																	$class_td['BTN']				= 'prod_list_buy_c';
																	echo show_addtocart_v5($row_prod,$class_arr,$frm_name,false,'','',true,$class_td);
													?>						</div>
										</div>
										<?php
									}		
						}					
					}
				}
				?>					    
					</td>
				</tr>
				<?php
				if(!$_REQUEST['product_id'])
				{
					?>
				<tr>
				<td align="right" >
				<ul class="compshelflist">
				<li><h1 align="right"><a href="#" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>" onclick="if(confirm('Are You sure You want to clear the recent product list')){ document.<?=$frm_name?>.submit()};"><?php echo $Captions_arr['COMMON']['COMON_RECENT']?></a></h1></li>
				</ul>
				</td>
				</tr>
				<?php
			    }
				?>
			</table>
			</form>
		<?php		
		}
		// ####################################################################################################
		// Function which holds the display logic for adverts
		// ####################################################################################################
		function mod_adverts($advert_arr,$title)
		{ 
			global $ecom_siteid,$db,$position,$Settings_arr,$ecom_hostname;
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
			if($position == 'middle')
			{
				if (count($advert_arr))
				{
					echo "<div class='middleadvert'>";
					foreach ($advert_arr as $d=>$k)
					{
						$cache_type		= 'comp_middleadvert';	
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
							switch ($k['advert_type'])
							{
								case 'IMG':
									$path	=	url_root_image('nor/img/'.$k['advert_source'],1);
									$link	=	$k['advert_link'];
									if ($link!='')
									{
					?>				<a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">	
					<?php			}
					?>				<img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo $title?>" style="border:0" />
					<?php			if ($link!='')
									{
					?>				</a>
					<?php			}
									break;
									case 'TXT':
										$path = $k['advert_source'];
							?>
							<div class='advert_text_middle_live'><?php echo stripslashes($path);?></div>							
							<?php
						break;		
						case 'ROTATE':   // case if ad rotate images are set
						?>
<!--<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/jquery-1.9.1.js",1)?>"></script>-->
<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/cycle/cycle.js",1)?>"></script>
						

						<?php
                                    $advert_ul_id = uniqid('advert_');
                                    ?>
                                    <script type="text/javascript">
							jQuery.noConflict();
													var $j = jQuery;
$j('#<?php echo $advert_ul_id?>').cycle({ 
    fx:    'fade', 
    speed:  500 
 });
 </script>
                                    <?php
                                    $HTML_Content1 .= "<script type=\"text/javascript\">
$('#".$advert_ul_id."').cycle({ 
    fx:    'fade', 
    speed:  500 
 });
</script>";
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
                                        $HTML_Content .= '<div class="pics" id="'.$advert_ul_id.'">';
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
                                                                '.$link_start.'
                                                                <img src="'.url_root_image('rot/img/'.$row_rotate['rotate_image'],1).'" alt="'.stripslashes($alt_text).'" title="'.stripslashes($alt_text).'" />'.
                                                                $link_end.'';
                                        }
                                        $HTML_Content .='</div>';
                                    }
                                    echo $HTML_Content;
                                break;				
							};
							if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
							{
								$content = ob_get_contents();
								ob_end_clean();
								save_Cache($cache_type,$k['advert_id'],$content);
								echo $content;
							}	
						}
					}
					echo "</div>";
				}
			}
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
					?>
							<table style="padding:2px;width:100%;border:0;border-spacing: 0;border-collapse: collapse;" class="advert_comp_table">
					<?php	
							if($title)
							{
					?>
							  <tr>
								<td style="text-align:left;vertical-align:top" class="advert_comp_header"><?php echo $title?></td>
							  </tr>
					<?php
							}
					?>
					<tr>
				  <?php
						switch ($k['advert_type'])
						{
							case 'IMG':
							?>
    <td  style="vertical-align:top" class="bgbanner_img"><?php
								$path = url_root_image('nor/img/'.$k['advert_source'],1);
								$link = $k['advert_link'];
								if ($link!='')
								{
						?>
									<a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">	
						<?php
								}
						?>
									<img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo $title?>" style="border:0" />
						<?php		
								if ($link!='')
								{
						?>
									</a>
						<?php		
								}
								?></td>
							<?php
							break;
							case 'PATH':
							?>
							
							<td  style="width:29px;vertical-align:top" class="bannerLeft">
    </td>
    <td  style="vertical-align:top" class="bgbanner"><?php
								$path = $k['advert_source'];
								$link = $k['advert_link'];
								if ($link!='')
								{
						?>
									<a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">	
						<?php
								}
						?>
									<img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo $title?>" style="border:0" />
						<?php		
								if ($link!='')
								{
						?>
									</a>
						<?php		
								}
								?></td>
    <td style="width:26px;text-align:right;vertical-align:top" class="bannerRight">
    
    </td>
							
							<?php
							break;
							case 'TXT':
							?>
							
							
							<td  style="width:26px;text-align:right;vertical-align:top" class="bannerLeft">
    </td>
    <td style="vertical-align:top" class="bgbanner"><?php
								$path = $k['advert_source'];
								echo stripslashes($path);
							?></td>
    <td  style="width:26px;text-align:right;vertical-align:top" class="bannerRight">
    
    </td>
							
							<?php
							break;
							
							case 'SWF'://for  flash file
							?>
							<td  style="width:26px;text-align:right;vertical-align:top" class="bannerLeft">
    </td>
    <td style="vertical-align:top" class="bgbanner"><?php
							$path = url_root_image('nor/img/'.$k['advert_source'],1);
							$link = $k['advert_link'];
							$flash_path =  '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="200" height="95">
							<param name="movie" value='.$path.'  >
    						<param name="quality" value="high" >
							<param name="BGCOLOR" value="#D6D8CB"><embed src='.$path.' type=application/x-shockwave-flash width = 200 height=95> </object>';
							$img_link=  '';
							echo  $flash_path ;
							?></td>
    <td  style="width:26px;text-align:right;vertical-align:top" class="bannerRight">
    
    </td>
							
							<?php
						break;						
						};
						?>
						</tr>
						</table>
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
			if($position=="newtop")
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
					?>
					<?php	
					/*
							if($title)
							{
					?>
							  <tr>
								<td align="left" valign="top" class="advert_comp_header"><?php echo $title?></td>
							  </tr>
					<?php
							}
							*/
					?>
					
				  <?php
						switch ($k['advert_type'])
						{
							case 'ROTATE':   // case if ad rotate images are set
							/*
						?>
<!--<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/cycle/cycle.js",1)?>"></script>-->
<script type="text/javascript" async src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/rotate/jquery-min.js",1)?>"></script>

<script type="text/javascript" async src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/rotate/jquery_002.js",1)?>"></script>
*/
/*
?> 
	<link href="<?php echo url_head_link("images/".$ecom_hostname."/css/skitter/styles.css",1)?>"  type="text/css" media="all" rel="stylesheet" />

	<script type="text/javascript" language="javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/skitter/jquery-2.1.1.min.js",1)?>" ></script>
	<script type="text/javascript" language="javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/skitter/jquery.easing.1.3.js",1)?>" ></script>
	*/
	?> 
		
		
		<link href="<?php echo url_head_link("images/".$ecom_hostname."/css/skitter/skitter.styles.css",1)?>"  type="text/css" media="all" rel="stylesheet" />

	<script type="text/javascript" language="javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/skitter/jquery.skitter.min.js",1)?>" ></script>


						

						<?php
							$advert_ul_id = uniqid('advert_');
							/*
							?>
							<script type="text/javascript">
							jQuery.noConflict();
										var $j = jQuery;
							//$j('#<?php echo $advert_ul_id?>').cycle({ 
							//fx:    'fade', 
							//speed:  500 
							//});
							</script>
							

							<?php
							*/
								?>
								<script type="text/javascript">
								jQuery.noConflict();
								var $j = jQuery;
								$j(document).ready(function() {
								$j('.box_skitter_large').skitter({
								theme: 'clean',
								numbers_align: 'center',
								progressbar: false, 
								dots: true, 
								preview: false,
								animation:'swapBarsBack',
								enable_navigation_keys: false,
								interval:5000,
								label: false,
								navigation: false,
								thumbs: false,
								hideTools: true

								});
								});
								</script>
 <?php
							/*
							$HTML_Content1 .= "<script type=\"text/javascript\">
							$('#".$advert_ul_id."').cycle({ 
							fx:    'fade', 
							speed:  500 
							});
							</script>";
							*/ 
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
							$HTML_Content .= "
								<div id=\"page\" style=\"float:left;\">

							<div id=\"content\">
											  <div class=\"border_box\">"	;                                   
							$HTML_Content .= "<div  class=\"box_skitter box_skitter_large\" >" ;

							$HTML_Content .= '<ul>';
							$cnt_rot = 0;
							while ($row_rotate = $db->fetch_array($ret_rotate))
							{
								$cnt_rot ++;
								$HTML_Content .= "<li>";
								if($row_rotate['rotate_alttext']!='')
								{
									$alt_text = $row_rotate['rotate_alttext']; 
								}
								else
								{
									$alt_text = $k['advert_title']; 
								}
								          if($cnt_rot==7)
                                            {
												$new_tab = "target='_blank'";
										    }
										    else
										    {
											    $new_tab = '';

											} 
								$link = trim($row_rotate['rotate_link']);
								$link_start = $link_end = '';
								if($link!='')
								{
									$link_start     = '<a href="'.$link.'" '.$new_tab.' title="'.stripslashes($k['advert_title']).'">';
									$link_end       = '</a>';
								}
								$HTML_Content .= '
													'.$link_start.'
													<img src="'.url_root_image('rot/img/'.$row_rotate['rotate_image'],1).'" alt="'.stripslashes($alt_text).'" title="'.stripslashes($alt_text).'" />'.
													$link_end.'';
								 $HTML_Content .= "</li>";
							}
							$HTML_Content .='</ul>';
							$HTML_Content .='</div>';
							$HTML_Content .='</div></div></div>';
							
							}
							echo $HTML_Content;
							/*
							?>
							<script type="text/javascript">
							$j(function() 
							{

							$j("#banner_compnew").carouFredSel({
							circular: true,
							infinite: true,
							auto:
							{
							timeoutDuration:8000
							},
							scroll: 
							{
							duration:600
							},
							prev	: 
							{	
							button	: "#banner_prev",
							key		: "left"
							},
							next	: 
							{ 
							button	: "#banner_next",
							key		: "right"
							},
							pagination	: "#banner_pag",
							width:"100%",
							responsive:true
							});
							});
							</script>
							<?php
							*/ 
							
							
							
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
		}
		
		// ####################################################################################################
		// Function which holds the display logic for sitereviews
		// ####################################################################################################
		function mod_sitereviews($title)
		{
			global $ecom_siteid,$db,$position,$Settings_arr;
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
				//print_r($title);
				if($_REQUEST['req']=='cart')
				return;
		?>	
        <div class="Banner_wrap">
            <table style="width:100%;border:0;border-spacing: 0;border-collapse: collapse;">
            <tr>
            	<td  style="width:15px;vertical-align:top"  class="bannerLeft"></td>
                <td   style="vertical-align:top" class="bgbanner">
                    <a href="<?php url_link('sitereview.html')?>">
                        <img src="<?php url_site_image('site_review.png')?>" alt="Site Review" title="Site Review" style="border:0"  />
                    </a>
                </td>
                <td  style="width:15px;vertical-align:top;text-align:right" class="bannerRight"></td>
            </tr>
            </table>
		</div>
            <!--<div class="sitereviewconleft" align="left">
				<input class="sitereviewleft" value="Site Reviews" onclick="window.location='<?php url_link('sitereview.html');?>'" type="button">
			</div>-->	
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
					if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
		if($db->num_rows($stat_query)){
		$row_query = $db->fetch_array($stat_query);
		}
		//print_r($row_query);
		?>
        <div class="Banner_wrap">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            	<td style="width:15px;vertical-align:top"  class="bannerLeft"></td>
                <td style="vertical-align:top" class="bgbanner">
                    <table border="0" cellpadding="0" cellspacing="0" class="webstatisticstable">
                    <tr>
                        <td class="webstatisticgraph"></td>
                        <td class="webstatisticdata">
                        <?php
                            if($title)
                            {
                        ?><div class="webstatisticsheader" style="text-align:left"><?php echo $title?></div>					
                        <?php
                            }
                        ?>
                        <div class="webstatistichits"><span class="webstatisticsA">"<?=$row_query['site_hits']?>"</span><br /><?php echo $Captions_arr['COMMON']['WEB_STATISTICS']?></div>
                        </td>
                    </tr>	
                    </table>
                </td>
                <td  style="width:15px;vertical-align:top;text-align:right" class="bannerRight"></td>
            </tr>
            </table>
        </div>
		<?php
		}
		function mod_ssl($title)
                {
                    global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$image_path;
                     if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
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
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
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
				<li><h1><a href="<?php echo $logoutUrl; ?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['LOGOUT']?></a></h1></li>
				<?php
					}
					else
					{
				?>
				<li><h1><a href="<?php url_link('logout.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['LOGOUT']?></a></h1></li>
				<?php
					}
				?>
				<?php
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
 					<li><h1><a href="<?php url_link('mypayonaccountpayment.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_PAYONACCDETAILS']?></a></h1></li>
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
					<li><h1><a href="<?php url_link('mydownloads.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_DOWNLOADS']?></a></h1></li>
				<?php
					}
				?>
				<li><h1><a href="<?php url_link('mypricepromise.html')?>" class="userloginmenuytoplink">
					  <?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_PRICE_PROMISE'])?>
					</a>
					</h1>
				</li>	
				<li><h1><a href="<?php url_link('myorders.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_ORDERS']?></a></h1></li>
				<? $myaddr_module = 'mod_myaddressbook';
				/*
					if(in_array($myaddr_module,$inlineSiteComponents))
					{
				?>
					<li><h1><a href="<?php url_link('myaddressbook.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_ADDRESSBOOK']?></a></h1></li>
				<?php
					}
					*/ 
				?>
				<?php	
					$myfav_module = 'mod_myfavorites';
					if(in_array($myfav_module,$inlineSiteComponents))
					{
				?>
					<li><h1><a href="<?php url_link('myfavorites.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_FAVOURITE']?></a></h1></li>
				<?php
					}
					?>
				<li><h1><a href="<?php url_link('wishlist.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_WISHLIST']?></a></h1></li>
				<li><h1><a href="<?php url_link('myenquiries.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_ENQUIRIES']?></a></h1></li>
                <?php /* FB login script */
					if($cust_fbid <= 0)
					{
				?>
				<li><h1><a href="<?php url_link('myprofile.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_PROFILE']?></a></h1></li>
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
				<li><h1><a href="<?php url_link('login_home.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_HOME']?></a></h1></li>
				</ul>          
				</td>
			</tr>
		<?php
		}
		
		/* Function to show the currency selector */
		function mod_currencyselector($title)
		{
			global $db,$ecom_siteid,$sitesel_curr;
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
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
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
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
			 if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
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
		  if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
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
		function mod_searchrefinecategory($title)
		{		
			global $ecom_siteid,$db,$ecom_hostname,$position,$Captions_arr,$Settings_arr;
					if(check_IndividualSslActive())
					{
						$ecom_selfhttp = "https://";
					}
					else
					{
						$ecom_selfhttp = "http://";
					}
			global $refine_displayed;
			$sql_sites = "SELECT enable_searchrefine_category FROM sites WHERE site_id=$ecom_siteid LIMIT 1";
			$ret_sites =$db->query($sql_sites);
			$row_sites = $db->fetch_array($ret_sites);
			if($row_sites['enable_searchrefine_category']==1)
			{
			if($_REQUEST['category_id']>0)
			{
				
				$sql_search_refine = "SELECT enable_searchrefine FROM product_categories WHERE sites_site_id=$ecom_siteid AND category_id=".$_REQUEST['category_id']." LIMIT 1"; 
				$ret_cat_refine    = $db->query($sql_search_refine);
				if($db->num_rows($ret_cat_refine)>0)
				{
				$page_read   ="refine_search_category.php";
				$row_cat_refine    = $db->fetch_array($ret_cat_refine);
				if($row_cat_refine['enable_searchrefine']==1)
				{
					$refine_displayed=true;
			   /*
			   ?>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
*/
?> 

<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/sticky/jquery.plugin.js",1)?>"></script>
<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/sticky/jquery.sticky.js",1)?>"></script>

<script>
	 jQuery.noConflict();
     var $j = jQuery;
$j(function() {
	$j('#sticky').sticky();
});
</script>
 <script>
	
     $j(document).ready(function(){		
     $j("#refine_search_cat #clearall").click(function( e ) { 
	 $j('#container').find('input[type=checkbox]:checked').removeAttr('checked');
      $j('.clear_checkbox').hide();
      $j('.clear_slider').hide();
      $j('.clear_all').hide();
      $j('.clear_color').hide();
      $j('.clear_down_img').show();
      $j('.variable_face_new').hide();
 $j(".div_clearcheck").children().each(function(n, i) {
		   var id = this.id;
		   $j('.facetContainer>div').find('.color_div_refine_sel').removeClass('color_div_refine_sel').addClass('color_div_refine');
			 var text = id.split('_');
		  id1 = text[1];	
		  id2 = text[2];
		  if(!isNaN(id2))
		   {	  
			    $j("#refineval_"+id1+"_"+id2).val(0);
		   }
		   
	  });
	  SearchProducts();
      $j(".slider>div").children().each(function(n, i) {		 		  
		    var id = this.id;	
		    clearallslider(id);
	  });
	 
		 });  
     $j("#refine_search_cat input:checkbox").click(function( e ) { 
		 SearchProducts()  }) 
     var id2='';
     var id1 ='';
     $j("#refine_search_cat .color_div_refine").click(function( e ) { 
		 var id = this.id;
		 var text = id.split('_');
		  id1 = text[1];	
		  id2 = text[2];
		  if(!isNaN(id2))
		   {	  
		  $j("#div_clearcheck_"+id1+" input[type=hidden]").val(0);
		  $j('#div_clearcheck_'+id1).find('.color_div_refine_sel').removeClass('color_div_refine_sel').addClass('color_div_refine');
		  $j('#clear_'+id1).show();
		  $j('#cleardown_'+id1).hide();
		  
		  $j('.clear_all').show();
			$j("#refineval_"+id1+"_"+id2).val(id2);
			$j("#divrefineval_"+id1+"_"+id2).removeClass('color_div_refine').addClass('color_div_refine_sel');
			SearchProducts() }
			 });
			 

     /*
      var start;
      var end;
    var options = 
        {
            range: true,
            min: <?php echo $min_refine_cat;?>,
            max: <?php echo $max_refine_cat;?>,
            values: [ <?php echo $min_refine_cat;?>, <?php echo $max_refine_cat;?> ],
            slide: function( event, ui ) {
                $j( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
            },
            stop: function( event, ui ) {
				    start = ui.values[0];
					end = ui.values[1];
					$j("#Min_price").val(start);
					$j("#Max_price").val(end);
					SearchProducts();
        
          }
        };

        $j( "#slider-range" ).slider(
            options
        );
        $j( "#amount" ).val( "$" + $j( "#slider-range" ).slider( "values", 0 ) +
            " - $" + $j( "#slider-range" ).slider( "values", 1 ) );

    $j("#clear_slider").click(function()
       {
           $j("#slider-range").slider("values", 0, options.values[0]);  
           $j("#slider-range").slider("values", 1, options.values[1] );           
           $j( "#amount" ).val( "$" + options.values[0] + " - $" + options.values[1] );  
           $j("#Min_price").val(options.values[0]);
		   $j("#Max_price").val(options.values[1]);
           SearchProducts();         
       });
       */
  $j(".slider>div").children().each(function(n, i) {
	  
	  var id = this.id;	
  var text = id.split('_');
  var ids = parseInt(text[1]);
  if(!isNaN(ids))
		{
  var starta;
  var enda;
  var minv = parseInt($j("#min_"+ids).val());
  var maxv = parseInt($j("#max_"+ids).val());
  var interval = parseFloat($j("#Interval_"+ids).val());
  var prefix   =  $j("#Prefix_"+ids).val();
  var suffix   =  $j("#Suffix_"+ids).val();
    var optionsa = 
        {
            range: true,
            min: minv,	
            max: maxv,                        
            values: [ minv, maxv ],
            slide: function( event, ui ) { 
                $j( "#amount_"+ids ).val(prefix+ui.values[ 0 ]+suffix + "- "+prefix+ ui.values[ 1 ]+suffix );
                $j("#clear_"+ids).show();
                $j('.clear_all').show();
            },
            step:interval,
            stop: function( event, ui ) {
				    starta = ui.values[0];
					enda = ui.values[1];
					$j("#Minrange_"+ids).val(starta);
					$j("#Maxrange_"+ids).val(enda);
					SearchProducts();
        
          }
        };

        $j( "#slidervrange_"+ids ).slider(
            optionsa
        );
        $j( "#amount_"+ids ).val(prefix+ $j( "#slidervrange_"+ids ).slider( "values", 0 )+suffix +
            " - "+prefix+ $j( "#slidervrange_"+ids ).slider( "values", 1 )+suffix );
            
      $j("#clearslider_"+ids).click(function()
       {
           $j("#slidervrange_"+ids).slider("values", 0, optionsa.values[0]);  
           $j("#slidervrange_"+ids).slider("values", 1, optionsa.values[1] );           
           $j( "#amount_"+ids ).val(prefix+optionsa.values[0]+suffix + " - " +prefix+ optionsa.values[1]+suffix );  
           //$j("#Minrange_"+ids).val(optionsa.values[0]);
		   //$j("#Maxrange_"+ids).val(optionsa.values[1]);
		   $j("#Minrange_"+ids).val(0);
		   $j("#Maxrange_"+ids).val(0);
		   $j("#clear_"+ids).hide();
		   
		   clearallclear(ids,0);
           SearchProducts();         
       });
 
   }
  
       });   

$j(".clearbut").click(function() {
	var chid = this.id;
	clearbut(chid);
	if (chid=='clearall')
	{
		$j(".clear_down_img").children().each(function(n, i) {
			var mytxtarr = i.id.split('_');
			var passid = mytxtarr[1];
			changeimage(passid,'plus');
			/*if(!isNaN(idsn))
			{
				if($j('#clear_'+idsn).css('display') == 'none')
				{ 
					show =1;
				}
				else
				{ 
					show =0;
					cnt = cnt+1;
				}
				changeimage(idsn,'plus');
			}*/
		});
	}
});
$j(".facetoption").click(function() {	
		var id = this.id;	
		var text = id.split('_');
		var ids = parseInt(text[1]);
		if(!isNaN(ids))
		{ 
			if($j('#div_clearcheck_'+ids+' :checkbox:checked').length > 0)
			{
				$j('#clear_'+ids).show();
				$j('#cleardown_'+ids).hide();
				clearallshow(ids);
			}
			else
			{ 
				$j('#clear_'+ids).hide();
				$j('#cleardown_'+ids).show();
				clearallclear(ids,0);
				changeimage(ids,'minus');
			}
		}
		SearchProducts();
		});

    
});
function clearbut(chid)
{			   
    $j('#div_'+chid).find('input[type=checkbox]:checked').removeAttr('checked');
    $j('#div_'+chid).find('.color_div_refine_sel').removeClass('color_div_refine_sel').addClass('color_div_refine');
    $j('#div_'+chid+' input[type="hidden"]').val(0);
		var text = chid.split('_');
		var ids = parseInt(text[1]);
		if(!isNaN(ids))
		{
		$j('#clear_'+ids).hide();
		$j('#clear_checkboxulclr_'+ids).hide();
		$j('#cleardown_'+ids).show();
		clearallclear(ids,1);
		
		}
	    SearchProducts();  
}
function clearallslider(id)
{
            var text = id.split('_');
			var ids = parseInt(text[1]);
			if(!isNaN(ids))
				{
			  var prefix   =  $j("#Prefix_"+ids).val();
              var suffix   =  $j("#Suffix_"+ids).val();
               $j("#slidervrange_"+ids).slider("values", 0, $j('#min_'+ids).val());
			   $j("#slidervrange_"+ids).slider("values", 1, $j('#max_'+ids).val());
			   //$j("#Minrange_"+ids).val($j('#min_'+ids).val());
				//$j("#Maxrange_"+ids).val($j('#max_'+ids).val());
				$j("#Minrange_"+ids).val(0);
				$j("#Maxrange_"+ids).val(0);
				$j( "#amount_"+ids ).val(prefix+ $j( "#slidervrange_"+ids ).slider( "values", 0 )+suffix +
            " - "+prefix+ $j( "#slidervrange_"+ids ).slider( "values", 1 )+suffix );
		   $j("#clear_"+ids).hide();
				}
           SearchProducts();
}
    function clearallshow(ids)
    {		
		
		if($j('#container :checkbox:checked').length>0)
		{ 
		 $j('.clear_all').show();
		}
	}
    function clearallclear(ids,srcs)
    {
		if(!isNaN(ids) && srcs==1)
		{
			changeimage(ids,'plus');
		}
		var show =1;
		var cnt = 0;
		$j(".facetContainer>div").children().each(function(n, i) {
			var id = this.id;	
		var text = id.split('_');
		var idsn = parseInt(text[1]);
		if(idsn!='')
		{
			if(!isNaN(idsn))
			{
				if($j('#clear_'+idsn).css('display') == 'none')
				{ 
					show =1;
					//changeimage(idsn,'plus');
				}
				else
				{ 
					show =0;
					cnt = cnt+1;
					//changeimage(idsn,'minus');
				}
				
			}
		}
	});
		if($j('#container :checkbox:checked').length==0 && cnt==0)
		{ 
			
		    $j('.clear_all').hide();
		}
		if(ids!='')
		{
			if(!isNaN(ids))
			{
				if($j('#clear_down'+ids).css('display')=='none')
				{
					changeimage(ids,'plus');
				}
			}	
		}	
		
	}
    function SearchProducts()
    { 
		
		var dataString = $j("#refine_search_cat").serialize();
		window.scroll(0,450);		 
	     $j('#loader_img').show();
	     $j.ajax({
			   type: 'post',			   
                        url: "../../../includes/base_files/<?=$page_read?>",
                        data: dataString,
                        success: function(fromphp) {
                            $j('#result_prod_div').html(fromphp);
						},
						 complete: function(){
						$j('#loader_img').hide();
						
      }                   
           });
	}
	function handle_colorbox(curid)
	{
		 myobjs = eval ('document.getElementById("srimg_'+curid+'")');
		 myclrobjs = eval ('document.getElementById("clear_checkboxulclr_'+curid+'")');
		 if(myclrobjs.style.display=='')
		 {
			 changeimage(curid,'plus');
			 myclrobjs.style.display = 'none';
		 }
		 else
		 {
			 changeimage(curid,'minus');
			 myclrobjs.style.display = '';
		 }
	}
	function changeimage(curid,name)
	{
		 myobjs = eval ('document.getElementById("srimg_'+curid+'")');
		if(myobjs)
		{
			if(name=='plus')
			{
				myobjs.src ='<?php echo url_site_image('plus.png',1)?>';
			}
			else
			{
				myobjs.src ='<?php echo url_site_image('minus.png',1)?>';
			}	
		}	
	}
</script>
<form id="refine_search_cat" method="post" name="refine_search_cat">	   
	    
	<input type="hidden" id="category_id" name="category_id" value="<?php echo $_REQUEST['category_id']?>">
         <?php
	    /*
        <input type="hidden" id="Min_price" name="min" value="" />
        <input type="hidden" id="Max_price" name="max" value="" />
<div class="slider"> 
<div class="clear_slider" ><input type="button" id="clear_slider" name="clear_slider" value="Clear Price"></div>
<p>
<label for="amount">Price range:</label>
<input type="text" id="amount" style="border:0; color:#f6931f; font-weight:bold;">
</p>
<div id="slider-range"></div>
</div>
*/?>
<div class="aside">
	<div id="sticky"  style="background-color:#fff" > 
<div id="container">
	<div class="div_shlf_slr_table_hdr">Refine Your Search</div>
<?php 
 $sql_keyword = "  SELECT 
						*	
					FROM 
					product_category_searchrefine_keyword 
			    WHERE 
					sites_site_id=$ecom_siteid AND refine_hidden=0 
					AND product_categories_category_id=".$_REQUEST['category_id']." ORDER BY refine_order";
					$ret_keyword = $db->query($sql_keyword);
					if($db->num_rows($ret_keyword)>0)
					{
						?>
						   <div class="clear_all" style="display:none;" ><input class="clearbut" type="button" id="clearall" name="clearall" value="Clear All"></div>

						<?php
					while($row_keyword=$db->fetch_array($ret_keyword))
					{
			             $rf_id = $row_keyword['refine_id'];
                        if($row_keyword['refine_display_style']=='CHECKBOX') 
						{
	
							$sql_kw_val = "SELECT refine_id,refineval_value,refineval_id FROM product_category_searchrefine_keyword_values WHERE refine_id=".$row_keyword['refine_id']." AND sites_site_id=$ecom_siteid AND product_categories_category_id=".$_REQUEST['category_id']." ORDER BY refineval_order";
							$ret_kw_val = $db->query($sql_kw_val);
							?>
							<?php 
							if($db->num_rows($ret_kw_val)>0)
							{
							?>	
							<div class="facetContainer" > 
						<div class="facet-title" style="" title="Click to Collapse">
						<h2 class="unitline"><?php echo $row_keyword['refine_caption']?></h2>
						
						</div>						
							<div class="check" id="div_clearcheck_<?php echo $rf_id ?>">
							<div class="clear_checkbox" id="clear_<?php echo $rf_id ?>" style="display:none;"><input class="clearbut" type="button" id="clearcheck_<?php echo $rf_id ?>" name="clearcheck_<?php echo $rf_id ?>" value="Clear"></div>
							<div class="clear_down_img" id="cleardown_<?php echo $rf_id ?>" onclick="handle_colorbox('<?php echo $rf_id?>')"><img id="srimg_<?php echo $rf_id ?>" src="<?php echo url_site_image('plus.png',1)?>"/></div>
							<ul class="variable_face_new" id="clear_checkboxulclr_<?php echo $rf_id ?>" style="display:none">
							<?php
							while($row_kw_val=$db->fetch_array($ret_kw_val))
							{
							?>
							<li >
							<a class="active ">
							<input class="facetoption" type="checkbox" name="refineval_<?php echo $row_keyword['refine_id']?>_<?php echo $row_kw_val['refineval_id'];?>" id="refineval_<?php echo $row_keyword['refine_id']?>_<?php echo $row_kw_val['refineval_id'];?>" value="<?php echo $row_kw_val['refineval_id'];?>">
							<span class="val_captionR" ><?php echo $row_kw_val['refineval_value'];?></span>
							</a>
							</li>
							<?php
							}
							?>
							</ul>
							</div>
							<div class="clearfix"></div>
						</div>
							<?php
							}
						}
						elseif($row_keyword['refine_display_style']=='RANGE')
						{
							$rf_id = $row_keyword['refine_id'];
							$lowval = $row_keyword['refine_lowval'];
							$highval = $row_keyword['refine_highval'];
							$prefix = $row_keyword['refine_prefix'];
							$suffix = $row_keyword['refine_suffix'];
							$interval = $row_keyword['refine_interval'];
							if($lowval>0 and $highval>0)
							{
							?>
							<div class="facetContainer" > 
						<div class="facet-title" style="" title="Click to Collapse">
						<h2 class="unitline"><?php echo $row_keyword['refine_caption']?></h2>
						
						</div>
						    <input type="hidden" id="min_<?php echo $rf_id ?>" name="min_<?php echo $rf_id ?>" value="<?php echo $lowval?>" />
							<input type="hidden" id="max_<?php echo $rf_id ?>" name="max_<?php echo $rf_id ?>" value="<?php echo $highval?>" />

							<input type="hidden" id="Minrange_<?php echo $rf_id ?>" name="Minrange_<?php echo $rf_id ?>" value="0" />
							<input type="hidden" id="Maxrange_<?php echo $rf_id ?>" name="Maxrange_<?php echo $rf_id ?>" value="0" />
							<input type="hidden" id="Prefix_<?php echo $rf_id ?>" name="Prefix_<?php echo $rf_id ?>" value="<?php echo $prefix?>" />
							<input type="hidden" id="Suffix_<?php echo $rf_id ?>" name="Suffix_<?php echo $rf_id ?>" value="<?php echo $suffix?>" />
							<input type="hidden" id="Interval_<?php echo $rf_id ?>" name="Interval_<?php echo $rf_id ?>" value="<?php echo $interval?>" />
							<div class="slider"> 
							<div class="clear_slider" id="clear_<?php echo $rf_id ?>" style="display:none;"><input type="button" id="clearslider_<?php echo $rf_id ?>" name="clearslider_<?php echo $rf_id ?>" value="Clear" class="clear_checkbox clearbut"></div>
							<p>
							<label for="amount_<?php echo $rf_id ?>">Range:</label>
							<input type="text" id="amount_<?php echo $rf_id ?>" style="border:0; color:#f6931f; font-weight:bold;">
							</p>
							<div id="slidervrange_<?php echo $rf_id ?>"></div>
							
							</div>
							<div class="clearfix"></div>
						</div>
							<?php
							}
					    }
					    else if($row_keyword['refine_display_style']=='BOX')
					    {
							$sql_kw_val = "SELECT refine_id,refineval_value,refineval_id,refineval_color_code FROM product_category_searchrefine_keyword_values WHERE refine_id=".$row_keyword['refine_id']." AND sites_site_id=$ecom_siteid AND product_categories_category_id=".$_REQUEST['category_id']." ORDER BY refineval_order";
							$ret_kw_val = $db->query($sql_kw_val);
							?>
							<?php 
							if($db->num_rows($ret_kw_val)>0)
							{
							?>	
							<div class="facetContainer" > 
						<div class="facet-title" style="" title="Click to Collapse">
						<h2 class="unitline"><?php echo $row_keyword['refine_caption']?></h2>
						
						</div>						
							<div class="check" id="div_clearcheck_<?php echo $rf_id ?>">
							<div class="clear_checkbox" id="clear_<?php echo $rf_id ?>" style="display:none;"><input class="clearbut" type="button" id="clearcheck_<?php echo $rf_id ?>" name="clearcheck_<?php echo $rf_id ?>" value="Clear"></div>
							<div class="clear_down_img" id="cleardown_<?php echo $rf_id ?>" onclick="handle_colorbox('<?php echo $rf_id?>')"><img id="srimg_<?php echo $rf_id ?>" src="<?php echo url_site_image('plus.png',1)?>"/></div>
							<ul class="variable_face_new" id="clear_checkboxulclr_<?php echo $rf_id ?>" style="display:none">
							<?php
							while($row_kw_val=$db->fetch_array($ret_kw_val))
							{
							?>
							<li >
							<input class="facetoption" type="checkbox" name="refineval_<?php echo $row_keyword['refine_id']?>_<?php echo $row_kw_val['refineval_id'];?>" id="refineval_<?php echo $row_keyword['refine_id']?>_<?php echo $row_kw_val['refineval_id'];?>" value="<?php echo $row_kw_val['refineval_id'];?>">
							<?php if($row_kw_val['refineval_color_code']!="")
							{
							  $style_bk = "background:".$row_kw_val['refineval_color_code']."";
							  $cls = "color_span_refine_new";
							}
							else
							{
							   $style_bk =  "";
							     $cls = "color_span_refine_new color_span_refine_new_nill";
							}
							?>
							<span  class="<?php echo $cls?>" style="<?php echo $style_bk;?>"  title="<?php  echo $row_kw_val['refineval_value']?>" >
							</span>
							
							<span class="val_captionR" ><?php echo $row_kw_val['refineval_value'];?></span>
							</li>
							<?php
							}
							?>
							</ul>
							</div>
							<div class="clearfix"></div>
						</div>
							<?php
							}
							/*
							$sql_kw_boxval = "SELECT refine_id,refineval_value,refineval_id,refineval_color_code FROM product_category_searchrefine_keyword_values WHERE refine_id=".$row_keyword['refine_id']." AND sites_site_id=$ecom_siteid AND product_categories_category_id=".$_REQUEST['category_id']." ORDER BY refineval_order";
							$ret_kw_boxval = $db->query($sql_kw_boxval);
							if($db->num_rows($ret_kw_boxval)>0)
							{
						?>
						<div class="facetContainer" > 
						<div class="facet-title" style="" title="Click to Collapse">
						<h2 class="unitline"><?php echo $row_keyword['refine_caption']?></h2>
						
						</div>

							<div style="height:55px" id="div_clearcheck_<?php echo $rf_id ?>" class="div_clearcheck">
						    <div class="clear_color" id="clear_<?php echo $rf_id ?>" style="display:none;"><input class="clearbut" type="button" id="clearcheck_<?php echo $rf_id ?>" name="clearcheck_<?php echo $rf_id ?>" value="Clear"></div>

							<?php
							while($row_kw_boxval=$db->fetch_array($ret_kw_boxval))
							{
							?>
							<div name="divrefineval_<?php echo $row_keyword['refine_id']?>_<?php echo $row_kw_boxval['refineval_id'];?>" id="divrefineval_<?php echo $row_keyword['refine_id']?>_<?php echo $row_kw_boxval['refineval_id'];?>" class="color_div_refine" style="background:<?php echo $row_kw_boxval['refineval_color_code']?>"  title="<?php  echo $row_kw_boxval['refineval_value']?>" >
							</div>
				             <input type="hidden" class="refinevalhide" id="refineval_<?php echo $row_keyword['refine_id']?>_<?php echo $row_kw_boxval['refineval_id'];?>" name="refineval_<?php echo $row_keyword['refine_id']?>_<?php echo $row_kw_boxval['refineval_id'];?>" value="" />
							<?php
						    }
						
							?>
							</div>
							<div class="clearfix"></div>
						</div>
						<?php
						   }
						   */ 	
						  }
					  }					 
				    }
?>
</div>
</div>
</div>
</form>
			   <?php
			}
		    }
		   }
		 }
		}

	};
?>
