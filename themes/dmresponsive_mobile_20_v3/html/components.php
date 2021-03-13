<?php
	/*#################################################################
	# Script Name 		: components.php
	# Description 		: Page which holds the html script for various components of the site
	# Coded by 			: Sny
	# Created on		: 08-May-2009
	# Modified by		: 
	# Modified On		: 
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
			switch($position)
			{
				case 'bottom':
					$cache_type		= 'comp_bottomstatgroup';	
				break;
			}// Cache checking section
				$cache_exists 	= false;
				$cache_required	= false;
				if ($Settings_arr['enable_caching_in_site']==1 and $cache_type != '')
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
					// End of bottom
					if ($position == 'top') // Case if value of position is bottom;
					{
						if(count($grp_array))
						{
						?>
						
                   
           
   
                                    
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
                                       <li class="nav-item active"> <a class="nav-link" href="<?php url_link('')?>"  title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?>"><i class="fa fa-home"></i></a></li>
                                        
                                        <?php			
                                    }
                                        $show_top = 0;
                                    }	
                                    
                                    while ($row_pg = $db->fetch_array($ret_pg))
                                    {
                                    $target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
                                        ?>
                                        <li class="nav-item"><a class="nav-link" href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>"  title="<?php echo stripslash_normal($row_pg['title'])?>" <?php echo $target?>><?php echo stripslash_normal($row_pg['title']);?></a></li>
                                    
                                        <?php
                                    }
                                    }
                                    ?>
                                    <?php			
                                    if ($grpData['group_showsitemaplink']==1)
                                    {
                                        ?>
                                        <li class="nav-item"><a class="nav-link" href="<?php url_link('sitemap.html')?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SITEMAP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SITEMAP'])?></a></li>
                                        
                                        <?php			
                                    }
                                    if ($grpData['group_showxmlsitemaplink']==1 )
                                    {			
                                        ?>
                                        <li class="nav-item"><a class="nav-link" href="<?php url_link('sitemap.xml')?>"  title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP'])?></a></li>
                                        
                                        <?php									
                                    }
                                    if ($grpData['group_showfaqlink']==1)
                                    {
                                        ?>
                                        <li class="nav-item"><a class="nav-link" href="<?php url_link('faq.html')?>"  title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?></a></li>
                                        
                                        <?php			
                                    }
                                    if ($grpData['group_showhelplink']==1)
                                    {
                                        ?>
                                        <li class="nav-item"><a class="nav-link" href="<?php url_link('help.html')?>"  title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HELP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HELP'])?></a></li>
                                        
                                        <?php			
                                    }
                                    /*if ($grpData['group_showsavedsearchlink']==1)
                                    {
                                        ?>
                                        <a href="<?php url_link('saved-search.html')?>" class="bottombandstatic" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH'])?></a>
                                        
                                        <?php			
                                    }*/
                                    ?>                                   
             

						<?php	
						}
						
					}
					if ($position == 'seo-section') // Case if value of position is bottom;
					{ 
						if(count($grp_array))
						{
					
					?>
					
										<div class="col">

							<h3><?php echo $title ;?></h3>
							<ul>
								<?php
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
                                       <li > <a href="<?php url_link('')?>" class="bottombandstatic" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?></a></li>
                                        
                                        <?php			
                                    }
                                        $show_top = 0;
                                    }	
                                    
                                    while ($row_pg = $db->fetch_array($ret_pg))
                                    {
                                    $target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
                                        ?>
                                        <li><a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="bottombandstatic" title="<?php echo stripslash_normal($row_pg['title'])?>" <?php echo $target?>><?php echo stripslash_normal(utf8_encode($row_pg['title']));?></a></li>
                                    
                                        <?php
                                    }
                                    }
                                    ?>
                                    <?php			
                                    if ($grpData['group_showsitemaplink']==1)
                                    {
                                        ?>
                                        <li><a href="<?php url_link('sitemap.html')?>" class="bottombandstatic" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SITEMAP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SITEMAP'])?></a></li>
                                        
                                        <?php			
                                    }
                                    if ($grpData['group_showxmlsitemaplink']==1 )
                                    {			
                                        ?>
                                        <li><a href="<?php url_link('sitemap.xml')?>" class="bottombandstatic" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP'])?></a></li>
                                        
                                        <?php									
                                    }
                                    if ($grpData['group_showfaqlink']==1)
                                    {
                                        ?>
                                        <li><a href="<?php url_link('faq.html')?>" class="bottombandstatic" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?></a></li>
                                        
                                        <?php			
                                    }
                                    if ($grpData['group_showhelplink']==1)
                                    {
                                        ?>
                                        <li><a href="<?php url_link('help.html')?>" class="bottombandstatic" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HELP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HELP'])?></a></li>
                                        
                                        <?php			
                                    }
                                    /*if ($grpData['group_showsavedsearchlink']==1)
                                    {
                                        ?>
                                        <a href="<?php url_link('saved-search.html')?>" class="bottombandstatic" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH'])?></a>
                                        
                                        <?php			
                                    }*/
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
		// Function which holds the display logic for product category groups
		// ####################################################################################################
		function mod_productcatgroup($grp_array,$title)
		{ 
			global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$ecom_themeid;
			// Getting the required sort by field and sort order
				$sort_by 	=  $Settings_arr['category_orderfield'];
				if($sort_by=='cname') $sort_by = 'category_name';
				$sort_by 	=  ($sort_by=='custom')?'b.category_order':$sort_by;
				
				if($ecom_siteid==70)
					$sort_by 	=  'b.category_order';
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
					// ############## Top ##############
						//if ($position == 'topband') // Case if value of position is top;
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
									$sql_cat = "SELECT a.category_id,a.category_name,parent_id,b.category_subcat_width  
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
										//if ($grpData['catgroup_listtype']== 'Menu')// case if categories are to be shown in list menu
										//{
										if ($grpData['catgroup_show_subcat_indropdown']== 1 and $subcatdropdownsupport)// case if categories are to be shown in list menu
										{
											if($position=='topband')
											{
											if ($grpData['catgroup_show_subcat_indropdown_subcount']== 1)// case if 1 levels of subcategories to be displayed
											{
											?>
												<div class="category_con">
												<div class="category_left"></div>
												<div class="category_mid">
												<ul id="nav">
											<?php
												while ($row_cat = $db->fetch_array($ret_cat))
												{
											?>
													<li class="item1"><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="category_menu" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><span><?php echo ucwords(stripslash_normal($row_cat['category_name']));?>1</span></a></a>
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
														<ul style="width:<?php echo $row_cat['category_subcat_width']?>px;">
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
												echo   " <link href=\"".url_head_link("images/".$ecom_hostname."/css/dropdown-effects/fade-down.css",1)."\" rel=\"stylesheet\">";
												echo   " <link href=\"".url_head_link("images/".$ecom_hostname."/css/webslidemenu/webslidemenu.css",1)."\" rel=\"stylesheet\">";
												echo   " <link href=\"".url_head_link("images/".$ecom_hostname."/css/webslidemenu/color-skins/white-gry.css",1)."\" rel=\"stylesheet\">";
												?>


	<!-- Include Below JS After Your jQuery.min File -->
	<script type="text/javascript" src="<?php echo url_head_link("images/".$ecom_hostname."/scripts/webslidemenu/webslidemenu.js",1) ?>"></script>
												<div class="headerfull">
    <div class="wsmain clearfix">
      <div class="smllogo"><a href="#"><img src="images/sml-logo.png" alt=""/></a></div>
      <nav class="wsmenu clearfix">
        <ul class="wsmenu-list">
          <li aria-haspopup="true"><a href="#" class="navtext"><span>Shop By</span> <span>Department</span></a>
            <div class="wsshoptabing wtsdepartmentmenu clearfix">
              <div class="wsshopwp clearfix">
                <ul class="wstabitem clearfix">
                  <li class="wsshoplink-active"><a href="#"><i class="fa fa-female" ></i> Women's Wear &amp; Items</a>
                    <div class="wstitemright clearfix wstitemrightactive">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-8 col-md-12 clearfix">
                            <div class="wstheading clearfix">Women's Clothing</div>
                            <ul class="wstliststy01 clearfix">
                              <li><a href="#">Sweatshirt </a></li>
                              <li><a href="#">Fashion Hoodies <span class="wstmenutag greentag">New</span></a></li>
                              <li><a href="#">Jeans &amp; Trousers</a></li>
                              <li><a href="#">Capris and Shorts </a></li>
                              <li><a href="#">Leggings</a></li>
                              <li><a href="#">Swimsuits &amp; Cover Ups</a></li>
                              <li><a href="#">Lingerie &amp; Lounge</a></li>
                              <li><a href="#">Nightwear</a> <span class="wstmenutag redtag">Sale</span></li>
                              <li><a href="#">Jumpsuits, Rompers </a></li>
                              <li><a href="#">Jackets &amp; Vests <span class="wstmenutag bluetag">Trending</span></a></li>
                              <li><a href="#">Suiting &amp; Blazers </a></li>
                              <li><a href="#">Socks &amp; Hosiery</a></li>
                            </ul>
                            <div class="wstheading clearfix">Handbags & Wallets</div>
                            <ul class="wstliststy01 clearfix">
                              <li><a href="#">Clutches</a> </li>
                              <li><a href="#">Cross-Body Bags</a> </li>
                              <li><a href="#">Evening Bags</a> </li>
                              <li><a href="#">Shoulder Bags</a> <span class="wstmenutag orangetag">Hot</span></li>
                              <li><a href="#">Top-Handle Bags</a> </li>
                              <li><a href="#">Wristlets</a> </li>
                            </ul>
                            <div class="wstheading clearfix">Accessories</div>
                            <ul class="wstliststy01 clearfix">
                              <li><a href="#">Handbag Accessories</a> </li>
                              <li><a href="#">Sunglasses Accessories</a> </li>
                              <li><a href="#">Eyewear Accessories</a> </li>
                              <li><a href="#">Scarves & Wraps</a> </li>
                              <li><a href="#">Gloves & Mittens</a> </li>
                              <li><a href="#">Hats & Caps</a> </li>
                            </ul>
                          </div>
                          <div class="col-lg-4 col-md-12 clearfix">
                            <div class="wstbootslider clearfix">
                              <div id="demo" class="carousel slide" data-ride="carousel">

                                <!-- The slideshow -->
                                <div class="carousel-inner">
                                  <div class="carousel-item active"><img src="images/woman-ad-img.jpg" alt="" />
                                    <div class="carousel-caption">
                                      <h3>Slider Caption 11</h3>
                                    </div>
                                  </div>
                                  <div class="carousel-item"><img src="images/woman-ad-img02.jpg" alt="" />
                                    <div class="carousel-caption">
                                      <h3>Slider Caption 22</h3>
                                    </div>
                                  </div>
                                </div>

                                <!-- Left and right controls -->
                                <a class="carousel-control-prev" href="#demo" data-slide="prev"> <span class="carousel-control-prev-icon"></span> </a> <a class="carousel-control-next" href="#demo" data-slide="next"> <span class="carousel-control-next-icon"></span> </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li><a href="#"><i class="fa fa-male"></i>  Men's Wear &amp; Items</a>
                    <div class="wstitemright clearfix">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-8 col-md-12 clearfix">
                            <div class="wstheading clearfix">Men's Clothing</div>
                            <ul class="wstliststy01 clearfix">
                              <li><a href="#">Shirts <span class="wstmenutag greentag">New</span></a></li>
                              <li><a href="#">Fashion Hoodies & Sweatshirts </a></li>
                              <li><a href="#">Pants &amp; Trousers</a></li>
                              <li><a href="#">Capris &amp; Shorts </a></li>
                              <li><a href="#">Swim</a></li>
                              <li><a href="#">Suits & Sport Coats</a></li>
                              <li><a href="#">Underwear</a></li>
                              <li><a href="#">Socks</a> </li>
                              <li><a href="#">Sleep & Lounge</a></li>
                              <li><a href="#">T-Shirts & Tanks <span class="wstmenutag redtag">20%</span></a></li>
                              <li><a href="#">Active</a></li>
                              <li><a href="#">Sport Coats <span class="wstmenutag bluetag">Trending</span></a></li>
                            </ul>
                            <div class="wstheading clearfix">Shoes & Wallets</div>
                            <ul class="wstliststy01 clearfix">
                              <li><a href="#">Athletic</a> </li>
                              <li><a href="#">Boots</a> <span class="wstmenutag orangetag">Exclusive</span></li>
                              <li><a href="#">Fashion Sneakers</a> </li>
                              <li><a href="#">Loafers & Slip-Ons</a> </li>
                              <li><a href="#">Mules & Clogs</a> </li>
                              <li><a href="#">Outdoor</a> </li>
                              <li><a href="#">Oxfords</a> </li>
                              <li><a href="#">Sandals</a> </li>
                              <li><a href="#">Slippers</a> </li>
                            </ul>
                            <div class="wstheading clearfix">Accessories</div>
                            <ul class="wstliststy01 clearfix">
                              <li><a href="#">Belts</a> </li>
                              <li><a href="#">Suspenders</a> </li>
                              <li><a href="#">Eyewear Accessories</a> </li>
                              <li><a href="#">Neckties</a> </li>
                              <li><a href="#">Bow Ties & Cummerbunds</a> </li>
                              <li><a href="#">Collar Stays</a> </li>
                            </ul>
                          </div>
                          <div class="col-lg-4 col-md-12 clearfix"><a href="#" class="wstmegamenucolr"><img src="images/man-ad-img.jpg" alt="" ></a></div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li><a href="#"><i class="fa fa-play-circle"></i> Movies, Music &amp; Games</a>
                    <div class="wstitemright clearfix">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-3 col-md-12 clearfix">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Latest Movies</li>
                              <li><a href="#">Action & Adventure <span class="wstmenutag greentag">New</span></a></li>
                              <li><a href="#">Bollywood </a></li>
                              <li><a href="#">Documentary</a></li>
                              <li><a href="#">Educational</a></li>
                              <li><a href="#">Exercise & Fitness </a></li>
                              <li><a href="#">Faith & Spirituality</a></li>
                              <li><a href="#">Fantasy</a></li>
                              <li><a href="#">Romance</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12 clearfix">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Newest Games</li>
                              <li><a href="#">PlayStation 4 </a> </li>
                              <li><a href="#">Xbox 1 </a> <span class="wstmenutag orangetag">Most Viewed</span></li>
                              <li><a href="#">Nintendo DS</a> </li>
                              <li><a href="#">PlayStation Vita </a> </li>
                              <li><a href="#">Retro Gaming</a> </li>
                              <li><a href="#">Digital Games</a> </li>
                              <li><a href="#">Microconsoles</a> </li>
                              <li><a href="#">Kids & Family </a> </li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12 clearfix">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Popular Music Genre</li>
                              <li><a href="#">Alternative & Indie Rock</a> </li>
                              <li><a href="#">Broadway & Vocalists</a> </li>
                              <li><a href="#">Children's Music</a> </li>
                              <li><a href="#">Christian <span class="wstmenutag bluetag">50% off</span></a> </li>
                              <li><a href="#">Classic Rock</a> </li>
                              <li><a href="#">Comedy & Miscellaneous </a> </li>
                              <li><a href="#">Country</a> </li>
                              <li><a href="#">Dance & Electronic</a> </li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12 clearfix">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Popular Music Genre</li>
                              <li><a href="#">Alternative & Indie Rock</a> </li>
                              <li><a href="#">Broadway & Vocalists</a> </li>
                              <li><a href="#">Children's Music <span class="wstmenutag redtag">Discounted</span></a></li>
                              <li><a href="#">Classical</a> </li>
                              <li><a href="#">Classic Rock</a> </li>
                              <li><a href="#">Comedy & Miscellaneous</a> </li>
                              <li><a href="#">Country</a> </li>
                              <li><a href="#">Dance & Electronic</a> </li>
                            </ul>
                          </div>
                          <div class="col-lg-6 wstadsize01 clearfix"><a href="#"><img src="images/ad-size01.jpg" alt="" ></a></div>
                          <div class="col-lg-6 wstadsize02 clearfix"><a href="#"><img src="images/ad-size02.jpg" alt="" ></a></div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li><a href="#"><i class="fa fa-cutlery"></i>Home &amp; Kitchen</a>
                    <div class="wstitemright clearfix kitchenmenuimg">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Home Appliances</li>
                              <li><a href="#">Air Conditioners <span class="wstmenutag greentag">New</span></a></li>
                              <li><a href="#">Air Coolers </a></li>
                              <li><a href="#">Fans</a></li>
                              <li><a href="#">Microwaves</a></li>
                              <li><a href="#">Refrigerators</a></li>
                              <li><a href="#">Washing Machines </a></li>
                              <li><a href="#">Jars & Containers </a></li>
                              <li><a href="#">LED & CFL bulbs </a></li>
                              <li><a href="#">Drying Racks </a></li>
                              <li><a href="#">Laundry Baskets</a> <span class="wstmenutag orangetag">New</span></li>
                              <li><a href="#">Washing Machines </a></li>
                              <li><a href="#">Bedsheets </a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Kitchen Appliances</li>
                              <li><a href="#">Air Fryers </a></li>
                              <li><a href="#">Espresso Machines</a></li>
                              <li><a href="#">Food Processors</a> <span class="wstmenutag bluetag">Popular</span></li>
                              <li><a href="#">Hand Blenders</a></li>
                              <li><a href="#">Induction Cooktops</a></li>
                              <li><a href="#">Microwave Ovens</a></li>
                              <li><a href="#">Mixers & Grinders</a></li>
                              <li><a href="#">Rice Cookers</a></li>
                              <li><a href="#">Stand Mixers</a></li>
                              <li><a href="#">Sandwich Makers</a></li>
                              <li><a href="#">Tandoor & Grills</a></li>
                              <li><a href="#">Toasters</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li><a href="#"><i class="fa fa-television"></i>Electronics Appliances</a>
                    <div class="wstitemright clearfix">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li><img src="images/ele-menu-img01.jpg" alt=" "></li>
                              <li class="wstheading clearfix">TV & Audio</li>
                              <li><a href="#">4K Ultra HD TVs </a></li>
                              <li><a href="#">LED & LCD TVs</a></li>
                              <li><a href="#">OLED TVs</a> <span class="wstmenutag bluetag">Popular</span></li>
                              <li><a href="#">Plasma TVs</a></li>
                              <li><a href="#">Smart TVs</a></li>
                              <li><a href="#">Home Theater</a></li>
                              <li><a href="#">Wireless & streaming</a></li>
                              <li><a href="#">Stereo System</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li><img src="images/ele-menu-img02.jpg" alt=" "></li>
                              <li class="wstheading clearfix">Camera, Photo & Video</li>
                              <li><a href="#">Accessories <span class="wstcount">(1145)</span></a></li>
                              <li><a href="#">Bags & Cases <span class="wstcount">(445)</span></a></li>
                              <li><a href="#">Binoculars & Scopes <span class="wstcount">(45)</span></a></li>
                              <li><a href="#">Digital Cameras <span class="wstcount">(845)</span></a> </li>
                              <li><a href="#">Film Photography <span class="wstcount">(245)</span></a> </li>
                              <li><a href="#">Flashes <span class="wstcount">(105)</span></a></li>
                              <li><a href="#">Lenses <span class="wstcount">(445)</span></a></li>
                              <li><a href="#">Video <span class="wstcount">(145)</span> <span class="wstmenutag bluetag">Popular</span></a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li><img src="images/ele-menu-img03.jpg" alt=" "></li>
                              <li class="wstheading clearfix">Phones & Accessories</li>
                              <li><a href="#">Unlocked Cell Phones </a></li>
                              <li><a href="#">Smartwatches </a></li>
                              <li><a href="#">Carrier Phones</a></li>
                              <li><a href="#">Cell Phone Cases</a> <span class="wstmenutag orangetag">Hot</span></li>
                              <li><a href="#">Bluetooth Headsets</a></li>
                              <li><a href="#">Cell Phone Accessories</a></li>
                              <li><a href="#">Fashion Tech</a></li>
                              <li><a href="#">Headphone</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li><img src="images/ele-menu-img04.jpg" alt=" "></li>
                              <li class="wstheading clearfix">Wearable Device</li>
                              <li><a href="#">Activity Trackers </a></li>
                              <li><a href="#">Sports & GPS Watches</a></li>
                              <li><a href="#">Smart Watches</a> <span class="wstmenutag greentag">New</span></li>
                              <li><a href="#">Virtual Reality Headsets</a></li>
                              <li><a href="#">Wearable Cameras</a></li>
                              <li><a href="#">Smart Glasses</a></li>
                              <li><a href="#">Kids & Pets</a></li>
                              <li><a href="#">Smart Sport Accessories</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li><a href="#"><i class="fa fa-laptop"></i>Computers &amp; Game</a>
                    <div class="wstitemright clearfix computermenubg">
                      <div class="wstmegamenucoll01 clearfix">
                        <div class="container-fluid">
                          <div class="row">
                            <div class="col-lg-8">
                              <div class="wstheading clearfix">Monitors <a href="#" class="wstmorebtn">View All</a></div>
                              <ul class="wstliststy03 clearfix">
                                <li><a href="#">50 Inches <span class="wstmenutag greentag">New</span></a></li>
                                <li><a href="#">40 to 49.9 Inches </a></li>
                                <li><a href="#">30 to 39.9 Inches</a></li>
                                <li><a href="#">26 to 29.9 Inches</a></li>
                                <li><a href="#">18 to 19.9 Inches</a></li>
                                <li><a href="#">16 to 17.9 Inches</a></li>
                                <li><a href="#">26 to 29.9 Inches</a></li>
                                <li><a href="#">18 to 19.9 Inches</a></li>
                                <li><a href="#">16 to 17.9 Inches</a></li>
                              </ul>
                            </div>
                            <div class="col-lg-8">
                              <div class="wstheading clearfix">Software <a href="#" class="wstmorebtn">View All</a></div>
                              <ul class="wstliststy03 clearfix">
                                <li><a href="#">Antivirus & Security</a> </li>
                                <li><a href="#">Business </a> <span class="wstmenutag orangetag">Exclusive</span></li>
                                <li><a href="#">Web Design</a> </li>
                                <li><a href="#">Digital Software</a> </li>
                                <li><a href="#">Education & Reference</a> </li>
                                <li><a href="#">Lifestyle & Hobbies</a> </li>
                                <li><a href="#">Digital Software</a> </li>
                                <li><a href="#">Education & Reference</a> </li>
                                <li><a href="#">Lifestyle & Hobbies</a> </li>
                              </ul>

                            </div>
                            <div class="col-lg-8">
                              <div class="wstheading clearfix">Accessories <a href="#" class="wstmorebtn">View All</a></div>
                              <ul class="wstliststy03 clearfix">
                                <li><a href="#">Audio & Video Accessories</a> </li>
                                <li><a href="#">Cable Security Devices</a> </li>
                                <li><a href="#">Input Devices </a> </li>
                                <li><a href="#">Memory Cards</a> </li>
                                <li><a href="#">Monitor Accessories</a> </li>
                                <li><a href="#">USB Gadgets</a> </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li><a href="#"><i class="fa fa-car"></i>Auto accessories</a>
                    <div class="wstitemright clearfix wstpngsml">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy04 clearfix">
                              <li><img src="images/auto-menu-img01.jpg" alt=" "></li>
                              <li class="wstheading clearfix"><a href="#">Interior</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy04 clearfix">
                              <li><img src="images/auto-menu-img02.jpg" alt=" "></li>
                              <li class="wstheading clearfix"><a href="#">Styling</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy04 clearfix">
                              <li><img src="images/auto-menu-img03.jpg" alt=" "></li>
                              <li class="wstheading clearfix"><a href="#">Utility</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy04 clearfix">
                              <li><img src="images/auto-menu-img04.jpg" alt=" "></li>
                              <li class="wstheading clearfix"><a href="#">Spare Parts</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy04 clearfix">
                              <li><img src="images/auto-menu-img05.jpg" alt=" "></li>
                              <li class="wstheading clearfix"><a href="#">Protection</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy04 clearfix">
                              <li><img src="images/auto-menu-img06.jpg" alt=" "></li>
                              <li class="wstheading clearfix"><a href="#">Cleaning</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy04 clearfix">
                              <li><img src="images/auto-menu-img07.jpg" alt=" "></li>
                              <li class="wstheading clearfix"><a href="#">Car Audio</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy04 clearfix">
                              <li><img src="images/auto-menu-img08.jpg" alt=" "></li>
                              <li class="wstheading clearfix"><a href="#">Gear & Accessories</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li><a href="#"><i class="fa fa-heartbeat"></i>Health Care Products</a>
                    <div class="wstitemright clearfix wstpngsml">
                      <div class="container-fluid">


                        <div class="row">
                          <div class="col-lg-4 col-md-12 wstmegamenucolr03 clearfix"><a href=""><img src="images/health-menu-img01.jpg" alt=""></a></div>
                          <div class="col-lg-8 col-md-12 clearfix">
                            <div class="container-fluid">
                              <div class="row">
                                <div class="col-lg-4 col-md-12">
                                  <ul class="wstliststy05 clearfix">
                                    <li><img src="images/health-menu-img02.jpg" alt=" "></li>
                                    <li class="wstheading clearfix">Health Care</li>
                                    <li><a href="#">Diabetes </a></li>
                                    <li><a href="#">Incontinence </a></li>
                                    <li><a href="#">Cough & Cold</a></li>
                                    <li><a href="#">Baby Care</a> <span class="wstmenutag orangetag">Hot</span></li>
                                    <li><a href="#">Women's Health</a></li>
                                    <li><a href="#">First Aid</a></li>
                                    <li><a href="#">Smoking Cessation</a></li>
                                    <li><a href="#">Sleep & Snoring</a></li>
                                  </ul>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                  <ul class="wstliststy05 clearfix">
                                    <li><img src="images/health-menu-img03.jpg" alt=" "></li>
                                    <li class="wstheading clearfix">Personal Care</li>
                                    <li><a href="#">Hair Removal</a></li>
                                    <li><a href="#">Feminine Hygiene</a></li>
                                    <li><a href="#">Oral <span class="wstmenutag bluetag">Popular</span></a></li>
                                    <li><a href="#">Foot Care</a> </li>
                                    <li><a href="#">Hand Care</a></li>
                                    <li><a href="#">Personal Care Appliances</a></li>
                                    <li><a href="#">Foams & Creams</a></li>
                                    <li><a href="#">Hair Removal Creams</a></li>
                                  </ul>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                  <ul class="wstliststy05 clearfix">
                                    <li><img src="images/health-menu-img04.jpg" alt=" "></li>
                                    <li class="wstheading clearfix">Medical Equipment</li>
                                    <li><a href="#">Tapes <span class="wstmenutag redtag">Sale</span></a></li>
                                    <li><a href="#">Neck Supports</a></li>
                                    <li><a href="#">Arm Supports</a> </li>
                                    <li><a href="#">Elbow Braces</a></li>
                                    <li><a href="#">Knee & Leg Braces</a></li>
                                    <li><a href="#">Ankle Braces</a></li>
                                    <li><a href="#">Foot Supports</a></li>
                                    <li><a href="#">Crepe Bandages</a></li>

                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </li>

          <li aria-haspopup="true"><a href="#" class="navtext"><span>Shop By</span> <span>Brand Name</span></a>
            <div class="wsshoptabing wtsbrandmenu clearfix">
              <div class="wsshoptabingwp clearfix">
                <ul class="wstabitem02 clearfix">
                  <li class="wsshoplink-active"><a href="#"><i class="fa fa-apple brandcolor01" aria-hidden="true"></i>Apple</a>
                    <div class="wsshoptab-active wstbrandbottom clearfix">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Apple Products </li>
                              <li><a href="#">List Products 01 </a> <span class="wstmenutag redtag">Popular</span></li>
                              <li><a href="#">List Products 02</a></li>
                              <li><a href="#">List Products 03</a></li>
                              <li><a href="#">List Products 04</a> </li>
                              <li><a href="#">List Products 05</a> </li>
                              <li><a href="#">List Products 06</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Apple More Products</li>
                              <li><a href="#">List Products 07 </a></li>
                              <li><a href="#">List Products 08</a></li>
                              <li><a href="#">List Products 09</a></li>
                              <li><a href="#">List Products 10</a> </li>
                              <li><a href="#">List Products 11 </a></li>
                              <li><a href="#">List Products 12</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Apple More Products</li>
                              <li><a href="#">List Products 13 </a> <span class="wstmenutag orangetag">20% off</span></li>
                              <li><a href="#">List Products 14</a></li>
                              <li><a href="#">List Products 15</a></li>
                              <li><a href="#">List Products 16</a> </li>
                              <li><a href="#">List Products 17</a></li>
                              <li><a href="#">List Products 18</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Apple More Products</li>
                              <li><a href="#">List Products 19 </a> <span class="wstmenutag bluetag">Winter Offer</span></li>
                              <li><a href="#">List Products 20</a></li>
                              <li><a href="#">List Products 21</a></li>
                              <li><a href="#">List Products 22</a> </li>
                              <li><a href="#">List Products 23</a></li>
                              <li><a href="#">List Products 24</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li><a href="#"><i class="fa fa-windows brandcolor02" aria-hidden="true"></i> Windows</a>
                    <div class="wstbrandbottom clearfix">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Windows Products </li>
                              <li><a href="#">List Products 25 <span class="wstmenutag bluetag">Popular</span></a></li>
                              <li><a href="#">List Products 26</a></li>
                              <li><a href="#">List Products 27</a> <span class="wstmenutag greentag">20% off</span></li>
                              <li><a href="#">List Products 28</a> </li>
                              <li><a href="#">List Products 29</a></li>
                              <li><a href="#">List Products 30</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Windows More Products</li>
                              <li><a href="#">List Products 31 </a></li>
                              <li><a href="#">List Products 32</a></li>
                              <li><a href="#">List Products 33</a></li>
                              <li><a href="#">List Products 34</a> </li>
                              <li><a href="#">List Products 35 </a></li>
                              <li><a href="#">List Products 36</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Windows More Products</li>
                              <li><a href="#">List Products 37 </a></li>
                              <li><a href="#">List Products 38</a></li>
                              <li><a href="#">List Products 39</a></li>
                              <li><a href="#">List Products 40</a> </li>
                              <li><a href="#">List Products 41</a></li>
                              <li><a href="#">List Products 42</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Windows More Products</li>
                              <li><a href="#">List Products 43 </a></li>
                              <li><a href="#">List Products 44</a></li>
                              <li><a href="#">List Products 45</a></li>
                              <li><a href="#">List Products 46</a> </li>
                              <li><a href="#">List Products 47</a></li>
                              <li><a href="#">List Products 48</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li><a href="#"><i class="fa fa-skype brandcolor03" aria-hidden="true"></i> Skype</a>
                    <div class="wstbrandbottom clearfix">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Skype Products </li>
                              <li><a href="#">List Products 49 </a></li>
                              <li><a href="#">List Products 50</a> <span class="wstmenutag redtag">Popular</span></li>
                              <li><a href="#">List Products 51</a></li>
                              <li><a href="#">List Products 52</a> </li>
                              <li><a href="#">List Products 53</a></li>
                              <li><a href="#">List Products 54</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Skype More Products</li>
                              <li><a href="#">List Products 55 </a></li>
                              <li><a href="#">List Products 56</a></li>
                              <li><a href="#">List Products 57</a></li>
                              <li><a href="#">List Products 58</a> </li>
                              <li><a href="#">List Products 59 </a></li>
                              <li><a href="#">List Products 60</a> <span class="wstmenutag orangetag">20% off</span></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Skype More Products</li>
                              <li><a href="#">List Products 61 </a></li>
                              <li><a href="#">List Products 62</a></li>
                              <li><a href="#">List Products 63</a></li>
                              <li><a href="#">List Products 64</a> </li>
                              <li><a href="#">List Products 65</a></li>
                              <li><a href="#">List Products 66</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Skype More Products</li>
                              <li><a href="#">List Products 67 </a> <span class="wstmenutag bluetag">Winter Offer</span></li>
                              <li><a href="#">List Products 68</a></li>
                              <li><a href="#">List Products 69</a></li>
                              <li><a href="#">List Products 70</a> </li>
                              <li><a href="#">List Products 71</a></li>
                              <li><a href="#">List Products 72</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li><a href="#"><i class="fa fa-paypal brandcolor04" aria-hidden="true"></i>Paypal</a>
                    <div class="wstbrandbottom clearfix">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Paypal Products </li>
                              <li><a href="#">List Products 73 <span class="wstmenutag bluetag">Popular</span></a></li>
                              <li><a href="#">List Products 74</a></li>
                              <li><a href="#">List Products 75</a></li>
                              <li><a href="#">List Products 76</a> </li>
                              <li><a href="#">List Products 77</a></li>
                              <li><a href="#">List Products 78</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Paypal More Products</li>
                              <li><a href="#">List Products 79 </a></li>
                              <li><a href="#">List Products 80</a></li>
                              <li><a href="#">List Products 81</a></li>
                              <li><a href="#">List Products 82</a> </li>
                              <li><a href="#">List Products 83 <span class="wstmenutag greentag">20% off</span></a></li>
                              <li><a href="#">List Products 84</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Paypal More Products</li>
                              <li><a href="#">List Products 85 </a></li>
                              <li><a href="#">List Products 86</a></li>
                              <li><a href="#">List Products 87</a></li>
                              <li><a href="#">List Products 89</a> </li>
                              <li><a href="#">List Products 90</a></li>
                              <li><a href="#">List Products 91</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Paypal More Products</li>
                              <li><a href="#">List Products 92 </a></li>
                              <li><a href="#">List Products 93</a></li>
                              <li><a href="#">List Products 94</a></li>
                              <li><a href="#">List Products 95</a> </li>
                              <li><a href="#">List Products 96</a></li>
                              <li><a href="#">List Products 97</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li><a href="#"><i class="fa fa-quora brandcolor05" aria-hidden="true"></i>Quora</a>
                    <div class="wstbrandbottom clearfix">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Amazon Products </li>
                              <li><a href="#">List Products 98 </a></li>
                              <li><a href="#">List Products 99</a></li>
                              <li><a href="#">List Products 00</a></li>
                              <li><a href="#">List Products 01</a> </li>
                              <li><a href="#">List Products 02</a> <span class="wstmenutag redtag">Popular</span></li>
                              <li><a href="#">List Products 03</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Amazon More Products</li>
                              <li><a href="#">List Products 04 <span class="wstmenutag orangetag">20% off</span></a></li>
                              <li><a href="#">List Products 05</a></li>
                              <li><a href="#">List Products 06</a></li>
                              <li><a href="#">List Products 07</a> </li>
                              <li><a href="#">List Products 08 </a></li>
                              <li><a href="#">List Products 09</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Amazon More Products</li>
                              <li><a href="#">List Products 10 </a></li>
                              <li><a href="#">List Products 11</a></li>
                              <li><a href="#">List Products 12</a></li>
                              <li><a href="#">List Products 13</a> </li>
                              <li><a href="#">List Products 14</a></li>
                              <li><a href="#">List Products 15</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Amazon More Products</li>
                              <li><a href="#">List Products 16 </a> <span class="wstmenutag bluetag">Winter Offer</span></li>
                              <li><a href="#">List Products 17</a></li>
                              <li><a href="#">List Products 18</a></li>
                              <li><a href="#">List Products 19</a> </li>
                              <li><a href="#">List Products 20</a></li>
                              <li><a href="#">List Products 21</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li><a href="#"><i class="fa fa-soundcloud brandcolor06"></i>Sound Cloud</a>
                    <div class="wstbrandbottom clearfix">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">SoundCloud Products </li>
                              <li><a href="#">List Products 01 <span class="wstmenutag bluetag">Popular</span></a></li>
                              <li><a href="#">List Products 01</a></li>
                              <li><a href="#">List Products 01</a></li>
                              <li><a href="#">List Products 01</a> </li>
                              <li><a href="#">List Products 01</a></li>
                              <li><a href="#">List Products 01</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">SoundCloud More Products</li>
                              <li><a href="#">List Products 07 </a></li>
                              <li><a href="#">List Products 08</a></li>
                              <li><a href="#">List Products 09</a></li>
                              <li><a href="#">List Products 10</a> </li>
                              <li><a href="#">List Products 11 <span class="wstmenutag greentag">20% off</span></a></li>
                              <li><a href="#">List Products 12</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">SoundCloud More Products</li>
                              <li><a href="#">List Products 13 </a></li>
                              <li><a href="#">List Products 14</a></li>
                              <li><a href="#">List Products 15</a></li>
                              <li><a href="#">List Products 16</a> </li>
                              <li><a href="#">List Products 17</a></li>
                              <li><a href="#">List Products 18</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">SoundCloud More Products</li>
                              <li><a href="#">List Products 19 </a></li>
                              <li><a href="#">List Products 20</a></li>
                              <li><a href="#">List Products 21</a></li>
                              <li><a href="#">List Products 22</a> </li>
                              <li><a href="#">List Products 23</a></li>
                              <li><a href="#">List Products 24</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li><a href="#"><i class="fa fa-houzz brandcolor07"></i>Houzz</a>
                    <div class="wstbrandbottom clearfix">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Houzz Products </li>
                              <li><a href="#">List Products 01 </a></li>
                              <li><a href="#">List Products 01</a></li>
                              <li><a href="#">List Products 01</a></li>
                              <li><a href="#">List Products 01</a> </li>
                              <li><a href="#">List Products 01</a> <span class="wstmenutag redtag">Popular</span></li>
                              <li><a href="#">List Products 01</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Houzz More Products</li>
                              <li><a href="#">List Products 07 <span class="wstmenutag orangetag">20% off</span></a></li>
                              <li><a href="#">List Products 08</a></li>
                              <li><a href="#">List Products 09</a></li>
                              <li><a href="#">List Products 10</a> </li>
                              <li><a href="#">List Products 11 </a></li>
                              <li><a href="#">List Products 12</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Houzz More Products</li>
                              <li><a href="#">List Products 13 </a></li>
                              <li><a href="#">List Products 14</a></li>
                              <li><a href="#">List Products 15</a></li>
                              <li><a href="#">List Products 16</a> </li>
                              <li><a href="#">List Products 17</a></li>
                              <li><a href="#">List Products 18</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Houzz More Products</li>
                              <li><a href="#">List Products 19 </a> <span class="wstmenutag bluetag">Winter Offer</span></li>
                              <li><a href="#">List Products 20</a></li>
                              <li><a href="#">List Products 21</a></li>
                              <li><a href="#">List Products 22</a> </li>
                              <li><a href="#">List Products 23</a></li>
                              <li><a href="#">List Products 24</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li><a href="#"><i class="fa fa-get-pocket brandcolor08"></i>Get Pocket</a>
                    <div class="wstbrandbottom clearfix">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Get Pocket Products </li>
                              <li><a href="#">List Products 01 <span class="wstmenutag bluetag">Popular</span></a></li>
                              <li><a href="#">List Products 01</a></li>
                              <li><a href="#">List Products 01</a></li>
                              <li><a href="#">List Products 01</a> </li>
                              <li><a href="#">List Products 01</a></li>
                              <li><a href="#">List Products 01</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Get Pocket More Products</li>
                              <li><a href="#">List Products 07 </a></li>
                              <li><a href="#">List Products 08</a></li>
                              <li><a href="#">List Products 09</a></li>
                              <li><a href="#">List Products 10</a> </li>
                              <li><a href="#">List Products 11 <span class="wstmenutag greentag">20% off</span></a></li>
                              <li><a href="#">List Products 12</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Get Pocket More Products</li>
                              <li><a href="#">List Products 13 </a></li>
                              <li><a href="#">List Products 14</a></li>
                              <li><a href="#">List Products 15</a></li>
                              <li><a href="#">List Products 16</a> </li>
                              <li><a href="#">List Products 17</a></li>
                              <li><a href="#">List Products 18</a></li>
                            </ul>
                          </div>
                          <div class="col-lg-3 col-md-12">
                            <ul class="wstliststy02 clearfix">
                              <li class="wstheading clearfix">Get Pocket More Products</li>
                              <li><a href="#">List Products 19 </a></li>
                              <li><a href="#">List Products 20</a></li>
                              <li><a href="#">List Products 21</a></li>
                              <li><a href="#">List Products 22</a> </li>
                              <li><a href="#">List Products 23</a></li>
                              <li><a href="#">List Products 24</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </li>


          <li aria-haspopup="true"><a href="#" class="navtext"><span>Shop By</span> <span>Mega Menu</span></a>
            <div class="wsmegamenu clearfix">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-lg-3 col-md-12">
                    <ul class="wstliststy02 clearfix">
                      <li class="wstheading clearfix"> Skype Products </li>
                      <li><a href="#">List Products 01 </a> <span class="wstmenutag redtag">Popular</span></li>
                      <li><a href="#">List Products 02</a></li>
                      <li><a href="#">List Products 03</a></li>
                      <li><a href="#">List Products 04</a> </li>
                      <li><a href="#">List Products 05</a> </li>
                      <li><a href="#">List Products 06</a></li>
                    </ul>
                  </div>
                  <div class="col-lg-3 col-md-12">
                    <ul class="wstliststy02 clearfix">
                      <li class="wstheading clearfix">Paypal Products</li>
                      <li><a href="#">List Products 07 </a></li>
                      <li><a href="#">List Products 08</a></li>
                      <li><a href="#">List Products 09</a></li>
                      <li><a href="#">List Products 10</a> </li>
                      <li><a href="#">List Products 11 </a></li>
                      <li><a href="#">List Products 12</a></li>
                    </ul>
                  </div>
                  <div class="col-lg-3 col-md-12">
                    <ul class="wstliststy02 clearfix">
                      <li class="wstheading clearfix">Sound Cloud Products</li>
                      <li><a href="#">List Products 13 </a> <span class="wstmenutag orangetag">20% off</span></li>
                      <li><a href="#">List Products 14</a></li>
                      <li><a href="#">List Products 15</a></li>
                      <li><a href="#">List Products 16</a> </li>
                      <li><a href="#">List Products 17</a></li>
                      <li><a href="#">List Products 18</a></li>
                    </ul>
                  </div>
                  <div class="col-lg-3 col-md-12">
                    <ul class="wstliststy02 clearfix">
                      <li class="wstheading clearfix">Get Pocket Products</li>
                      <li><a href="#">List Products 19 </a> <span class="wstmenutag bluetag">Winter Offer</span></li>
                      <li><a href="#">List Products 20</a></li>
                      <li><a href="#">List Products 21</a></li>
                      <li><a href="#">List Products 22</a> </li>
                      <li><a href="#">List Products 23</a></li>
                      <li><a href="#">List Products 24</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </li>

          <li aria-haspopup="true"><a href="#" class="navtext"><span>Shop By</span> <span>Half Menu</span></a>
            <div class="wsmegamenu clearfix halfmenu">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-lg-6 col-md-12">
                    <ul class="wstliststy06 clearfix">
                      <li class="wstheading clearfix">Windows Products</li>
                      <li><a href="#">List Products 01 </a> <span class="wstmenutag redtag">Popular</span></li>
                      <li><a href="#">List Products 02</a></li>
                      <li><a href="#">List Products 03</a></li>
                      <li><a href="#">List Products 04</a> </li>
                      <li><a href="#">List Products 05</a> </li>
                      <li><a href="#">List Products 06</a></li>
                    </ul>
                  </div>
                  <div class="col-lg-6 col-md-12">
                    <ul class="wstliststy06 clearfix">
                      <li class="wstheading clearfix">Apple More Products</li>
                      <li><a href="#">List Products 07 </a></li>
                      <li><a href="#">List Products 08</a></li>
                      <li><a href="#">List Products 09</a></li>
                      <li><a href="#">List Products 10</a> </li>
                      <li><a href="#">List Products 11 </a></li>
                      <li><a href="#">List Products 12</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </li>


          <li class="wssearchbar clearfix">
            <form class="topmenusearch">
              <input placeholder="Search Product By Name, Category...">
              <button class="btnstyle"><i class="searchicon fa fa-search" aria-hidden="true"></i></button>
            </form>
          </li>

          <li class="wscarticon clearfix">
            <a href="#"><i class="fa fa-shopping-basket"></i> <em class="roundpoint">8</em><span class="hidetxt">Shopping Cart</span></a>
          </li>


          <li aria-haspopup="true" class="wsshopmyaccount"><a href="#"><i class="fa fa-align-justify"></i>My&nbsp;Account</a>
            <ul class="sub-menu">
              <li><a href="#"><i class="fa fa-black-tie"></i>View Profile</a></li>
              <li><a href="#"><i class="fa fa-heart"></i>My Wishlist</a></li>
              <li><a href="#"><i class="fa fa-bell"></i>Notification</a></li>
              <li><a href="#"><i class="fa fa-question-circle"></i>Help Center</a></li>
              <li><a href="#"><i class="fa fa-sign-out"></i>Logout</a></li>
            </ul>
          </li>


        </ul>
      </nav>
    </div>
  </div>
												<?php
											}
											elseif ($grpData['catgroup_show_subcat_indropdown_subcount']== 21)// case if 2 levels of subcategories to be displayed
											{
												?>
		<ul id="cd-primary-nav" class="cd-primary-nav is-fixed">
	
	   <?php
					$center_point = 2;
															$cursubcat_cnt = 0;
															while ($row_cat = $db->fetch_array($ret_cat))
															{
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
														   if($num_rows==0)
															{
															?>
															<li><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><?php echo ucwords(stripslash_normal(utf8_encode($row_cat['category_name'])));?></a></li>
															<?php
															}
								if($num_rows)
								 {
									?>
											<li class="has-children">
										

												<a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><?php echo ucwords(stripslash_normal(utf8_encode($row_cat['category_name'])));?></a>

												<ul class="cd-secondary-nav is-hidden">
													<li class="go-back"><a href="#0">Back</a></li>

													<li class="see-all"><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><?php echo ucwords(stripslash_normal(utf8_encode($row_cat['category_name'])));?></a></li>
													<?php
													$cnt=0;
																										while($row_subcat = $db->fetch_array($ret_subcat))
																										{
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
																										
													?>
													<li class="col-sm-3 has-children">														
														<a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1)?>" ><?php echo stripslash_normal(utf8_encode($row_subcat['category_name']))?></a>
															  <?php
															  if($num_subrows)
																{
																  ?>
													<ul class="is-hidden">																
														<li class="go-back"><a href="#0">Back</a></li>
																<?php
																/*
																<li class="see-all"><a href="#">See All</a></li>
																*/
																?> 
																<?php
																while ($row_subsubcat = $db->fetch_array($ret_subsubcat))
																											{
																											$sql_subsubsubcat = "SELECT category_id,category_name 
																											FROM 
																											product_categories 
																											WHERE 
																											parent_id =".$row_subsubcat['category_id']." 
																											AND sites_site_id = $ecom_siteid 
																											AND category_hide = 0 
																											ORDER BY 
																											category_order";
																											$ret_subsubsubcat = $db->query($sql_subsubsubcat);
																											$num_subsubrows	= $db->num_rows($ret_subsubsubcat);
																												?>
																											<li><a href="<?php url_category($row_subsubcat['category_id'],$row_subsubcat['category_name'],-1)?>"><?php echo stripslash_normal(utf8_encode($row_subsubcat['category_name']))?></a></li>
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
											</li>

									

											
												<?php
											}
																							
										}
					?>
			
		</ul>   
<?php /*
													<div class="collapse navbar-collapse" id="navbar-menu">
															<ul class="nav navbar-nav navbar-left" data-in="fadeInDown" data-out="fadeOutUp">
                                                              <li class="dropdown megamenu-fw">
               <a href="<?PHP echo url_link('') ?>" title="Home" data-toggle="" class="dropdown-toggle1"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
                
               </li>
															<?php
															$center_point = 2;
															$cursubcat_cnt = 0;
															while ($row_cat = $db->fetch_array($ret_cat))
															{
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
															if($num_rows==1)
															{
															 $cols_md = "col-md-12";
															}
															else
															{
															  $cols_md = "col-md-3"; 	
															}
															if($num_rows==0)
															{
															?>
															<li><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></a></li>
															<?php
															}
															if($num_rows)
															{
															?>
															<li class="dropdown megamenu-fw">
															<a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="dropdown-toggle" data-toggle="dropdown" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></a>
																<ul style="display: none; opacity: 1;" class="dropdown-menu megamenu-content animated fadeOutUp" role="menu">
																	<li>
																		<?php
																		$cnt=0;
																		while($row_subcat = $db->fetch_array($ret_subcat))
																		{
																			if($cnt==0)
																			{
																				?>
																				<div class="row">
																				<?php
																			}
																			$cnt++;
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
																		if($num_subrows==0)
																		{
																			$clsarrow = "title_none";
																		}
																		else
																		{
																			$clsarrow = "title";
																		}
																		?>
																		<div class="col-menu <?php echo $cols_md;?>" style="margin-bottom:20px;">
																		<h6 class="<?php echo $clsarrow;?>"><a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1)?>" class="title-a"><?php echo stripslashes($row_subcat['category_name'])?></a></h6>
																		<?php
																		/*
																		<div class="category-image pull-left hidden-xs">
																		<a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1)?>">
																		<?php
																		$pass_type = 'image_thumbpath';
																		if ($row_subcat['category_showimageofproduct']==0) // Case to check for images directly assigned to category
																		{
																		// Calling the function to get the image to be shown
																		$img_arr = get_imagelist('prodcat',$row_subcat['category_id'],$pass_type,0,0,1);
																		if(count($img_arr))
																		{
																		$HTML_image = show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_subcat['category_name'],'','',1);
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
																		$HTML_image = show_image(url_root_image($img_arr[0][$pass_type],1),$row_subcat['category_name'],$row_cat['category_name'],'','',1);
																		$show_noimage = false;
																		}
																		else 
																		$show_noimage = true;
																		}
																		else// case if no products exists under current category with image assigned to it
																		$show_noimage = true;
																		}
																		echo $HTML_image ;

																		?>
																		</a>
																		</div>
																		*/
																		/*
																		?> 
																		<div class="content">
																		<ul class="menu-col">
																		<?php
																		
																		if($num_subrows)
																		{
																		while ($row_subsubcat = $db->fetch_array($ret_subsubcat))
																		{
																		$sql_subsubsubcat = "SELECT category_id,category_name 
																		FROM 
																		product_categories 
																		WHERE 
																		parent_id =".$row_subsubcat['category_id']." 
																		AND sites_site_id = $ecom_siteid 
																		AND category_hide = 0 
																		ORDER BY 
																		category_order";
																		$ret_subsubsubcat = $db->query($sql_subsubsubcat);
																		$num_subsubrows	= $db->num_rows($ret_subsubsubcat);
																		
																		?>
																		<li><a href="<?php url_category($row_subsubcat['category_id'],$row_subsubcat['category_name'],-1)?>"><?php echo stripslashes($row_subsubcat['category_name'])?></a></li>

																		<?php
																		}
																		}
																		?>			

																		</ul>
																		</div>
																		</div>
																		<?php
																		if($cnt==6)
																		{
																			?>
																			</div>
																			<?php
																			$cnt =0;
																		}
																		}
																		if($cnt<6)
																		{
																			?>
																			</div>
																			<?php
																		}
																		/*
																		if($cnt<4)
																		{
																			for($i=0;$i<(4-$cnt);$i++)
																			{
																			?>
																			<div class="col-menu col-md-3" style="margin-bottom:20px;">
																		<h6 class="title_blank"><a href="#" class="title-ablank">&nbsp;</a></h6>
																		
																		
																		</div>

																			<?php
																			}
																		?>
																		<?php
																		}
																		*//* 
																		?>							                                

																		

																	</li>
																</ul>
															</li>

															<?php
															}
															}	
															?>
															</ul>
													</div><!-- /.navbar-collapse -->
														
														<?php
														*/ 
												}	
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
																	<h2><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="category_menu" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><span><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></span></a></h2>
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
						{ return;
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
												<ul>
													<?php
													while ($row_cat = $db->fetch_array($ret_cat))
													{
														$startpnt++;
													?>
													<li>
														<h3><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="Category" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></a></h3>
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
						{ return;
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
												ORDER BY 
													$sort_str";
									$ret_cat = $db->query($sql_cat);
									if ($db->num_rows($ret_cat))
									{
										// Check the listing type for categories in category group
										if ($grpData['catgroup_listtype']== 'Menu')// case if categories are to be shown in list menu
										{
										?>
											<div class="category_lf_con">
											<div class="category_lf_top"></div>
											<div class="category_lf_mid">
										<?php
										if($prev_grp != $grpData['catgroup_id'])
										{
											if ($grpData['catgroup_hidename']==0) // Decide whether or not to show the groupname
											{
										?>
												<div class="category_header">
                                                    <div class="category_header_top"></div>
													<div class="category_header_bottom"><?php echo stripslash_normal($title)?></div>
												</div>
										<?php
											}
												$prev_grp = $grpData['catgroup_id'];
										}
										?>
											<ul class="category">
										<?php
										while ($row_cat = $db->fetch_array($ret_cat))
										{
											$entered = 0;
											// Start:- to check for whether the categories under the group is displayed as a heading with/without a link
											if($row_cat['category_displaytype']=='Normal' && $row_cat['category_islink'])
											{
											?>
												<li>
												<h2><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="catelink" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></a></h2>
												</li>
											<?php
											}
											elseif($row_cat['category_displaytype']=='Normal' && !$row_cat['category_islink'])
											{
											?>
												<li>
												<h2><span class="subcategoryheader"><?php echo stripslash_normal($row_cat['category_name']);?></span></h2>
												</li>
											<?											
											}
											elseif($row_cat['category_displaytype']=='Heading' && $row_cat['category_islink'])
											{
											?>
												<li>
												<h2><span class="subcategoryheader"><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="subcategoryheaderlink" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></a></span></h2>
											<?
												$entered = 1;
											}
											elseif($row_cat['category_displaytype']=='Heading' && !$row_cat['category_islink'])
											{
												$entered = 1;
											?>
												<li>
												<h2><span class="subcategoryheader"><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></span></h2>
											<?
											}
											// End:- to check for whether the categories under the gorup is displayed as a heading with/without a link
											if ($row_cat['category_subcatlisttype']=='List' or $row_cat['category_subcatlisttype']=='Both')
											{
												if (($row_cat['category_id']==$_REQUEST['category_id']) or ($row_cat['category_id']==$parent))
												{	
												?>
													<ul class="subcategory" >
											<?php
													if ($parent == $row_cat['category_id'])
														$comp_catid = $parent;
													else	
														$comp_catid = $row_cat['category_id'];
													// Check whether any child exists for current category
													$sql_child = "SELECT category_id,category_name,default_catgroup_id
																	FROM 
																		product_categories 
																	WHERE 
																		parent_id=".$comp_catid." 
																		AND sites_site_id=$ecom_siteid 
																		AND category_hide = 0 
																	ORDER BY category_order";
													$ret_child = $db->query($sql_child);
													if ($db->num_rows($ret_child))
													{
														while ($row_child = $db->fetch_array($ret_child))
														{
													?>
													<li>
													<h2><a href="<?php url_category($row_child['category_id'],$row_child['category_name'],-1)?>" title="<?php echo stripslash_normal($row_child['category_name'])?>" class="subcatelink"><?php echo ucwords(stripslash_normal($row_child['category_name']));?></a></h2>
													</li>
											<?php		
														}
													}
													?>
													</ul>
											<?php
												}
											}
											if($entered==1)
											{
											?>
												</li>
											<?php
											}	
										}
										?>
										</ul>
										</div>
										<div class="category_lf_bottom"></div>
										</div>
<?php		
										}
										else
										{
										?>
											<div class="shp_brnd_lf_con">
											<div class="shp_brnd_lf_top">
											<div class="shp_brnd_header">
											<?php
											if ($grpData['catgroup_hidename']==0) // Decide whether or not to show the groupname
											{
											?>
												<div class="shp_brnd_header_top"><?php echo stripslash_normal($title)?></div>
											<?php
											}
											?>
											<div class="shp_brnd_header_bottom"></div>
											</div>
											<div class="shp_brnd_select">
											<select name="prodcatgroup_<?php echo $grpData['catgroup_id']?>" onchange="handle_dropdownval_sel(this.value)">
											<option value="">-- <?php echo $Captions_arr['COMMON']['SELECT']?> -- </option>
											<?php
											while ($row_cat = $db->fetch_array($ret_cat))
											{
											?>
											<option value="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" <?php echo ($row_cat['category_id']==$_REQUEST['category_id'])?'selected="selected"':''?>><?php echo stripslash_normal($row_cat['category_name'])?></option>
											<?php		
											}
											?>
											</select>
											</div>
											</div>
											<div class="shp_brnd_lf_bottom"></div>
											</div>
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
			global $Captions_arr,$position,$ecom_hostname,$protectedUrl,$ecom_siteid,$Settings_arr,$enable_auto_search;
			if($position=='topband')
			{ 
			?>
						
	                  <form method="post" name="frm_quicksearch" class="frm_cls" action="<?php url_link('search.html')?>">
                   	                        <div class="input-group search-bar">

                   <input type="hidden" name="search_src" id="search_src" value='advanced'/>
                   <input type="hidden" name="rdo_suboption" id="rdo_suboption" value='title_desc'/>
	                        
	                            <input type="text" class="form-control input-search" id="quick_search" name="quick_search" value="SEARCH PRODUCTS" placeholder="SEARCH PRODUCTS" onfocus="if (this.value=='SEARCH PRODUCTS') this.value='';" onblur="if (this.value=='') this.value='SEARCH PRODUCTS';">
									<div class="input-group-append">
								<button class="btn btn-default no-border-left" type="submit"><i class="fa fa-search"></i></button>        
									</div>
								  </div>
	                        
	                       
	                  </form>
	      <?php
	      	       
			}
			else if($position=='topband_mob')
			{
			?>
			<div class="col-sm-12">
						
	                    <form method="post" name="frm_quicksearch" class="frm_cls" action="<?php url_link('search.html')?>">
                   <input type="hidden" name="search_src" id="search_src" value='advanced'/>
                   <input type="hidden" name="rdo_suboption" id="rdo_suboption" value='title_desc'/>
	                        <div class="input-group search-box-pad container" >
	                            <input type="text" class="form-control input-search" name="quick_search" placeholder="Search here">
	                            <span class="input-group-btn">
	                                <button class="btn btn-default no-border-left" type="submit"><i class="fa fa-search"></i></button>
	                            </span>
	                                                <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>

	                        </div>
	                    </form>
	      
					</div>
			
			<?php
			}
		}
		// ####################################################################################################
		// Function which holds the display logic for customer login
		// ####################################################################################################
		function mod_customerlogin($title)
		{
			global $Captions_arr,$ecom_hostname,$inlineSiteComponents,$Settings_arr,$db,$ecom_siteid,$position;
			$Captions_arr['CUST_LOGIN'] = getCaptions('CUST_LOGIN');
			$Captions_arr['LOGIN_MENU'] = getCaptions('LOGIN_MENU');
			$cust_id 					= get_session_var("ecom_login_customer");
			if (!$cust_id) // case customer is not logged in
			{
			
				$hide_newuser 		=  $Settings_arr['hide_newuser'];
				$hide_forgotpass 	=  $Settings_arr['hide_forgotpass'];
				//echo $position;echo "<br>";
				if ($position=='mobiletop') // Best sellers is allowed in left or right panels
				{	
				?>
				
				 <li>
            
           <div class="container">
     <button type="button" class="main-btn btn-uni" data-toggle="collapse" data-target="#demo">
       login
    </button>

  <div id="demo" class="collapse login-main">
	 <form name="frm_custlogin<?=$frm?>" id="frm_custlogin" method="post" action="" onsubmit="return validate_login(this)" class="frm_cls" accept-charset="UTF-8">
              			  <div class="form-group">
<label class="sr-only" for="form-custlogin_uname">Email</label>

  <input name="custlogin_uname" class="form-control" id="custlogin_uname"  type="text"  placeholder="Email" />
  </div>
              			  <div class="form-group">
<label class="sr-only" for="form-custlogin_pass">Password</label>

  <input name="custlogin_pass" class="form-control" id="custlogin_pass"  type="password" placeholder="Password" >
  </div>
 <?php /*?>
  <input id="user_remember_me" style="float: left; margin-right: 10px;" type="checkbox" name="user[remember_me]" value="1" />
  <label class="string optional" for="user_remember_me"> Remember me</label>
  */
  ?>

  <input class="mainbtn btn-default login-now"  type="submit" name="custologin_Submit" id="custologin_Submit" value="Sign In" />
  <div class="sep_panel_small"></div>
  						<input type="hidden" name="redirect_back" value="0" />
  						  <div class="forgotpsw"><a href="<?php url_link('forgotpassword.html')?>" class="loginlink" title="<?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['FORGOT_PASS'])?>"><?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['FORGOT_PASS'])?></a></div>

    <a href="<?php url_link('registration.html')?>" class="loginlink" title="<?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['NEW_USER'])?>">
     <span class="regor">Or</span>  <?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['NEW_USER'])?>
    </a>
</form>
  </div>
</div>
<?php
/*
<script>
$(document).ready(function(){
  $("#demo").on("hide.bs.collapse", function(){
    $(".btn").html('<span class="glyphicon glyphicon-collapse-down"></span> Open');
  });
  $("#demo").on("show.bs.collapse", function(){
    $(".btn").html('<span class="glyphicon glyphicon-collapse-up"></span> Close');
  });
});
</script>
*/
?> 
             
             
             
                  
  

                </li>				
					                           
			<?php
				}
				
			}
			else
			{
				if($position=='bottomband')
				{
			?>		<!--<div>Logged in customer? <a href="<?php url_link('logout.html')?>"><?=stripslash_normal($Captions_arr['LOGIN_MENU']['LOGOUT'])?></a></div>-->
			<?php
				}
				else
				{
			?>
			<li><span class="black"><a href="<?php url_link('login_home.html')?>">My Home</a></li><li><span class="black"><a href="<?php url_link('myprofile.html')?>">Account</a></span></li>
						<li><span class="black">
						<a href="<?php url_link('myorders.html')?>" class="userloginmenuytoplink">
					  Orders
					</a></span></li><li><span class="black"><a href="<?php url_link('myenquiries.html')?>" class="userloginmenuytoplink">
					Enquiries
					</a></span></li>					
					<li><a href="<?php url_link('logout.html')?>"><input type="button" name="button3" id="button3" value="<?=stripslash_normal($Captions_arr['LOGIN_MENU']['LOGOUT'])?>"  class="mainbtn"/></a></li>
			      <?php
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
			//if($_REQUEST['req']=='cart') // if current page is related to cart then call cartcalc function to refresh the cart totals and number of items in cart
			{
				$cartData = cartCalc(); // calling cart calc 
			}
			$totitems = get_session_var('cart_total_items');	
			$cart_tot = print_price(get_session_var('cart_total'),true);
			$pass_tot = print_price(get_session_var('cart_total'),true,true);	
			if($position=='topband1') // Best sellers is allowed in left or right panels
			{	
			?>
			
                        <div  class="cart-lista" data-toggle="modal" data-target="#myModal">
                           <?php /* <i class="fa fa-shopping-cart"></i>
                             <span class="badge"><?php echo  (get_session_var('cart_total_items'))?get_session_var('cart_total_items'):0?></span>
                            */ ?>
                        </div>
                        <?php 
							if (count($cartData['products'])==0) // Done to show the message if no products in cart
							{
							?>
							<div class="modal fade cart-list" id="myModal" role="dialog">
							<div class="modal-dialog ">
							<div class="modal-content">
							<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">My Cart(<?php echo  (get_session_var('cart_total_items'))?get_session_var('cart_total_items'):0?>)</h4>
							</div>
							<div class="modal-body">
							Your Cart is Empty
							</div>

							</div>
							</div>
							</div>
							<?php

							} 
							else
							{
							?>
							<div class="modal fade cart-list" id="myModal" role="dialog">
							<div class="modal-dialog">
							<div class="modal-content">
							<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">My Cart(<?php echo  (get_session_var('cart_total_items'))?get_session_var('cart_total_items'):0?>)</h4>
							</div>
							<div class="alert alert-success fade in red_alert" id="red_alert" style="display:none;" ><span  >Item added to the cart</span></div>
							<div class="modal-body">
							<form method="post" name="frm_cart_drop"  id="frm_cart_drop" class="frm_cls" action="">

							<p>
							<ul>
							<?php
							$cartData 				= cartCalc();
							$cnt_var = 0;
							foreach ($cartData['products'] as $products_arr)
							{
							$cnt_var ++;
							$vars_exists = false;
							if ($products_arr['prod_vars'] or $products_arr['prod_msgs'])  // Check whether variable of messages exists
							{
							$vars_exists 	= true;
							}
							?>
							<li class="drp_licont">
							<div class="cart_drp_img" >
							<a class="image_tda" href="<?php url_product($products_arr['product_id'],$products_arr['product_name'],-1)?>" title="<?php echo stripslash_normal($products_arr['product_name'])?>">
							<?php
							$pass_type = 'image_iconpath';

							// Calling the function to get the image to be shown
							//$pass_type = get_default_imagetype('cart');
							//$img_arr = get_imagelist('prod',$products_arr['product_id'],$pass_type,0,0,1);
							if($products_arr['cart_comb_id'] and $products_arr['product_variablecombocommon_image_allowed']=='Y')
							$img_arr = 	get_imagelist_combination($products_arr['cart_comb_id'],$pass_type,0,1);
							else
							$img_arr = get_imagelist('prod',$products_arr['product_id'],$pass_type,0,0,1);
							if(count($img_arr))
							{
							show_image(url_root_image($img_arr[0][$pass_type],1),$products_arr['product_name'],$products_arr['product_name']);
							}
							else
							{
							// calling the function to get the default image
							$no_img = get_noimage('prod',$pass_type); 
							if ($no_img)
							{
							show_image($no_img,$products_arr['product_name'],$products_arr['product_name']);
							}	
							}	
							?>
							</a>
							</div>
							<div class="cart_drp_outer">
							<div class="cart_drp_name">
							<a class="name_drp" href="<?php url_product($products_arr['product_id'],$products_arr['product_name'],-1)?>" title="<?php echo stripslash_normal($products_arr['product_name'])?>">
							<?php echo stripslash_normal($products_arr['product_name'])?>
							</a>
							<?php
							?>

							</div>

							<?php 
							// If variables exists for current product, show it in the following section
							if ($vars_exists) 
							{
							?>

							<div class="cart_drp_det" id="<?php echo $products_arr['product_id'].$cnt_var; ?>">Details</div>
							<div class="cart_drp_var" id="drpvar<?php echo $products_arr['product_id'].$cnt_var ?>" style="display:none">
							<?php
							// show the variables for the product if any
							if ($products_arr['prod_vars']) 
							{
							foreach($products_arr["prod_vars"] as $productVars)
							{
							if (trim($productVars['var_value'])!='')
							print "<div class='nblack'>".stripslashes($productVars['var_name']).": ". stripslashes($productVars['var_value'])."</div>"; 
							else
							print "<div class='nblack'>".stripslashes($productVars['var_name'])."</div"; 

							}	 
							}
							// Show the product messages if any
							if ($products_arr['prod_msgs']) 
							{	
							foreach($products_arr["prod_msgs"] as $productMsgs)
							{
							print "<div class='nblack'>".stripslashes($productMsgs['message_title']).": ". stripslashes($productMsgs['message_value'])."</div>"; 
							}	
							}
							?>
							</div>

							<?php	
							}
							?>
							<div class="cart_drp_qty">
							<?php
							if($products_arr['product_det_qty_type']=='DROP')
							{
							echo "<label class='label_qty'>&nbsp;Qty:&nbsp";
							if (trim($products_arr['product_det_qty_drop_prefix'])!='')
							echo stripslash_normal($products_arr['product_det_qty_drop_prefix']).' ';
							echo $products_arr['cart_qty'];
							if (trim($products_arr['product_det_qty_drop_suffix'])!='')
							echo ' '.stripslash_normal($products_arr['product_det_qty_drop_suffix']);
							echo "</label>";
							?>
							<input type="hidden" name="cart_qty_<?php echo $products_arr['cart_id']?>" id="cart_qty_<?php echo $products_arr['cart_id']?>" value="<?php echo $products_arr["cart_qty"]?>" />
							<?php	
							}
							else
							{ 
							?>
							<label class='label_qty_text'><span class="qty_tenant">Qty:&nbsp;<?php echo $products_arr["cart_qty"]?></span>
							<?php
							/*
							<input name="cart_qty_<?php echo $products_arr['cart_id']?>" type="text" id="cart_qty_<?php echo $products_arr['cart_id']?>" size="2" maxlength="4" value="<?php echo $products_arr["cart_qty"]?>"  class="det_qty_txt form-control"  />
							*/
							?> 
								   </label>							
							<?php /*<a href="#" onclick="document.frm_cart.remcart_id.value='<?php echo $products_arr['cart_id']?>';handle_form_submit(document.frm_cart,'Update_qty','#upd')" title="<?php echo stripslash_normal($Captions_arr['CART']['CART_UPDATE'])?>"><button  name="button" id="button" value="<?php echo stripslash_normal($Captions_arr['CART']['CART_UPDATE'])?>" class="btn btn-info btn-sm" /><i class="fa fa-refresh"></i></button></a> 
							*/ 
							/*
							?>
							<span class="glyphicon glyphicon-refresh cartupdatedrop"  data-backdrop="false" aria-hidden="true" id="<?php echo $products_arr['cart_id']?>" ></span>


							<?php
							*/ 
							} 
							/*
							?>
							<span class="glyphicon glyphicon-trash cartremovedrop"  data-backdrop="false" aria-hidden="true" id="<?php echo $products_arr['cart_id']?>" ></span>
							*/ ?> 

							</div> 

							<?php
							/*
							<a href="#" class="update_link" onclick="if (confirm_message('<?php echo stripslash_normal($Captions_arr['CART']['CART_ITEM_REM_MSG'])?>')) {document.frm_cart.remcart_id.value='<?php echo $products_arr['cart_id']?>';handle_form_submit(document.frm_cart,'Remove_qty','#rem')}" title="<?php echo stripslash_normal($Captions_arr['CART']['CART_REMOVE'])?>">
							<button class="btn btn-danger btn-sm" name="button3" id="button3" value="<?php echo stripslash_normal($Captions_arr['CART']['CART_REMOVE'])?>"  /><i class="fa fa-trash-o"></i></button>
							</a>
							*/
							?> 
							</div>
							<div class="drp_price"><?php echo print_price($products_arr['subtotal'],true);?></div>
							</li>
							<?php
							}
							?>
							<input type='hidden' name='site_url' value="<?=SITE_URL?>" id="site_url_cart"/>

							</ul>
							</p>
							  <p class="drp_total"> <span class="pull-right"><strong>Total</strong>: <?php echo $cart_tot?></span></p>


							<a href="<?php url_link('cart.html')?>" class="btn btn-add-to-cart btn-lg sharp">View or Edit Cart</a></p>
							</form>
							</div>

							</div>
							</div>
							</div>

			<?php
							}
							}
			if ($position=='topband') // Best sellers is allowed in left or right panels
			{
				if (count($cartData['products'])==0) // Done to show the message if no products in cart
				{
				?>
                <div class="topcrt_arrw" id="topcrt_arrw"></div>
				<h2>Cart</h2>
				<p>Your Cart is Empty</p>

				<?php
				}
				else
				{
				?>
				<form method="post" name="frm_cart_drop"  id="frm_cart_drop" class="frm_cls" action="">
				<input type="hidden" value="<?php echo $totitems; ?>" id="totitemscart"/> 	
				<h2 class="topcrt">Cart</h2>
				<ul class="cd-cart-items">
				<?php
						$cartData 				= cartCalc();
						$cnt_var = 0;
						foreach ($cartData['products'] as $products_arr)
						{
						$cnt_var ++;
						$vars_exists = false;
						if ($products_arr['prod_vars'] or $products_arr['prod_msgs'])  // Check whether variable of messages exists
						{
						$vars_exists 	= true;
						}
						?>
				<li>
				<span class="cd-qty"><?php echo $products_arr["cart_qty"]?></span> <a class="name_drp" href="<?php url_product($products_arr['product_id'],$products_arr['product_name'],-1)?>" title="<?php echo stripslash_normal($products_arr['product_name'])?>">
						<?php echo stripslash_normal($products_arr['product_name'])?>
						</a>
				<div class="cd-price"><?php echo print_price($products_arr['final_price'],true);?></div>
				<?php /*<a href="#0" class="cd-item-remove cd-img-replace">Remove</a> */?>
				<?php 
						// If variables exists for current product, show it in the following section
						if ($vars_exists) 
						{
						?>

						<div class="cart_drp_det" id="<?php echo $products_arr['product_id'].$cnt_var; ?>">Details</div>
						<div class="cart_drp_var" id="drpvar<?php echo $products_arr['product_id'].$cnt_var ?>" style="display:none">
						<?php
						// show the variables for the product if any
						if ($products_arr['prod_vars']) 
						{
						foreach($products_arr["prod_vars"] as $productVars)
						{
						if (trim($productVars['var_value'])!='')
						print "<div class='nblack'>".stripslashes($productVars['var_name']).": ". stripslashes($productVars['var_value'])."</div>"; 
						else
						print "<div class='nblack'>".stripslashes($productVars['var_name'])."</div"; 

						}	 
						}
						// Show the product messages if any
						if ($products_arr['prod_msgs']) 
						{	
						foreach($products_arr["prod_msgs"] as $productMsgs)
						{
						print "<div class='nblack'>".stripslashes($productMsgs['message_title']).": ". stripslashes($productMsgs['message_value'])."</div>"; 
						}	
						}
						?>
						</div>

						<?php	
						}
						?>
				</li>
				<?php
				}
				?>

				</ul> <!-- cd-cart-items -->

				<div class="cd-cart-total">
				<p>Total <span><?php echo print_price($cartData["totals"]["subtotal"],true)?></span></p>
				</div> <!-- cd-cart-total -->

				<a href="<?php url_link('cart.html')?>" class="checkout-btn">Go to cart page</a>
				<input type='hidden' name='site_url' value="<?=SITE_URL?>" id="site_url_cart"/>

				<?php /*<p class="cd-go-to-cart"><a href="<?php url_link('cart.html')?>">Go to cart page</a></p>
				*/ ?>
				
<div class="continue_rightA">	
								
									<a href="JavaScript:void(0);" class="btn btn-warning btn-shop btn-shop-new"><i class="fa fa-angle-left"></i>Continue Shopping</a>
				</div>
								</form>

				<?php
				 
				}
			}
						//$this->upsell_products_display();

						
		}
		// ####################################################################################################
		// Function which holds the display logic for call back request 
		// ####################################################################################################
		function mod_callback($title)
		{
			global $position;
			if($position=='bottomband' )
			{
				?>
				<div class="container-fluid bgdots">
					<a href="<?php url_link('callback.html')?>">
					<div class="icon-phone"></div>
					<div class="container textcenter">request a callback</div></a>
					</div>
				<?php	
			}	
		}
		// ####################################################################################################
		// Function which holds the display logic for best sellers
		// ####################################################################################################
		function mod_bestsellers($ret_main,$title,$display_id)
		{
			global $ecom_siteid,$db,$ecom_hostname,$position,$Captions_arr,$Settings_arr;
			if ($position=='left' or $position=='right') // Best sellers is allowed in left or right panels
			{	
				if ($db->num_rows($ret_main))
				{
				?><p class="row bestsell">
		
					
					<?php
					while ($row_main = $db->fetch_array($ret_main))
					{
					?>
					
					<div class="bestseller-wrap">
		<div class="bestseller">
		<div class="best-img-left">
			<a href="<?php url_product($row_main['product_id'],$row_main['product_name'],-1)?>" title="<?php echo stripslash_normal($row_main['product_name'])?>">
											<?php
														$pass_type = 'image_iconpath';
														// Calling the function to get the image to be shown
														$img_arr = get_imagelist('prod',$row_main['product_id'],$pass_type,0,0,1);
													if(count($img_arr))
													{
														show_image(url_root_image($img_arr[0][$pass_type],1),$row_main['product_name'],$row_main['product_name']);
													}
													else
													{
														// calling the function to get the default image
														$no_img = get_noimage('prod',$pass_type); 
														if ($no_img)
														{
														show_image($no_img,$row_main['product_name'],$row_main['product_name']);
														}	
													}	
													?>
											</a>
		
		</div>
		<div class="best-detail-right"><a href="<?php url_product($row_main['product_id'],$row_main['product_name'],-1)?>" title="<?php echo stripslash_normal($row_main['product_name'])?>" class="best_seller_pdt_namea"><?php echo stripslash_normal($row_main['product_name'])?></a></div>
		
		</div></div>
					
					<? }?>
					</p>
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
			if ($position=='left' or $position=='right') // Best sellers is allowed in left or right panels
			{	
				if ($db->num_rows($ret_compare_pdts))
				{
					if ($Settings_arr['product_compare_enable']==1)
					{
				?>
						<div class="compare_div">
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
						<div class="compare_top"></div>
							<div class="compare_middle">
								<table width="100%" border="0" cellpadding="0" cellspacing="0" class="compare_table">
								<?php
								if($title) // check whether title exists
								{
								?>
									<tr>
										<td colspan="2" class="compare_table_header"><?php echo $title?></td>
									</tr>
								<?php
								}
								while ($row_compare_pdts = $db->fetch_array($ret_compare_pdts))
								{
								?>
									<tr>
										<td width="26" class="compare_table_td" valign="top"><img src="<?php url_site_image('comp-icn.gif')?>" onclick="document.common_compare_list.remove_compareid.value=<?=$row_compare_pdts['product_id']?>; if(confirm('Are You sure You want to remove the product from the compare list')){ document.common_compare_list.submit()};" alt="Remove" title="Remove" /></td>
										<td width="168" class="compare_table_td" valign="top"><a href="<?php url_product($row_compare_pdts['product_id'],$row_compare_pdts['product_name'],-1)?>" class="comparelink" title="<?php echo stripslash_normal($row_compare_pdts['product_name'])?>"><?php echo stripslash_normal($row_compare_pdts['product_name'])?></a> </td>
									</tr>
								<?php
								}
								if(count($_SESSION['compare_products'])>1)
								{	
								?>
									<tr>
										<td colspan="2" align="right" valign="top" class="compare_table_td"><a href="<?php url_link('compare_products.html')?>" class="compare_showall" title="<?php echo stripslash_normal($Captions_arr['COMMON']['COMPARE_PRODUCTS'])?>" target="_blank"><?php echo stripslash_normal($Captions_arr['COMMON']['COMPARE_PRODUCTS'])?></a></td>
									</tr>
								<? 			
								}
								?>
								</table>
							</div>
						<div class="compare_bottom"></div>
						</form>
						</div>
					
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
			$Captions_arr['COMBO']	= getCaptions('COMBO');

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
								$sql_prod = "SELECT a.product_id,a.product_name,a.product_discount_enteredasval,a.product_default_category_id,b.combo_discount,a.product_webprice 
											FROM 
												products a,combo_products b 
											WHERE 
												b.combo_combo_id = ".$combData['combo_id']." 
												AND a.product_id = b.products_product_id 
												AND a.product_hide='N' 
											ORDER BY 
												$combosort_by $combosort_order";
								$ret_prod = $db->query($sql_prod);
								$totcnt = $db->num_rows($ret_prod);

								if ($db->num_rows($ret_prod))
								{
									if($position =='left' || $position =='right')
									{
							?>
										<div class="combo_deal_lf_con">
										<div class="combo_deal_lf_top"></div>
											<div class="combo_deal_lf_mid">
												<div class="combo_deal_lf_outr">
												<?php
												if ($title and $combData['combo_hidename']==0)
												{
												?>
												<div class="combo_deal_lf_hdr"><? echo $title?></div>
												<? }?>
												<div class="combo_deal_lf_hdr_bt"></div>
												</div>
											<?php
											$bundle_price = $combData['combo_bundleprice'];
											$cnt=0;
											?>
												<div class="combo_deal_pdt_outr">
												<?php
												$cnt_cmb =0;
												while ($row_prod = $db->fetch_array($ret_prod))
												{
												$clas ='';
												$cnt_cmb++;
												if($cnt_cmb==1)
												{
													$clas ='combo_deal_pdt_top'; 
													$clasA ='combo_deal_pdt_topA'; 
												}
												else
												{
													$clas ='combo_deal_pdt_topC'; 
													$clasA ='combo_deal_pdt_topD'; 
												}
												?>
												<div class="<?php echo $clas;?>"></div>
													<div class="<?php echo $clasA;?>">
													<?php
														$cnt++;
														$row_prod['product_discount_enteredasval'] 		= 0;
														$row_prod['product_discount'] 					= $row_prod['combo_discount'];
														?>
													<div class="combo_deal_pdt_img"> <a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_DEAL'])?>">
													<?php
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
													</a> </div>
													<div class="combo_deal_pdt_name"> <a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_DEAL'])?>"><?php echo $row_prod['product_name'] ?></a></div>
													</div>
												<? }?>
												<div class="combo_deal_pdt_topE"></div>
												<div class="combo_deal_pdt_topF"><?php echo stripslash_normal($Captions_arr['COMBO']['PRICE_COMP'])?>&nbsp;<?php echo print_price($bundle_price)	?></div>
												</div>
											<div class="combodeal-showall"> <a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" class="lf-combodeal-showall" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_DEAL'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_DEAL'])?></a> </div>
											</div>
										<div class="combo_deal_lf_bottom"></div>
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
						$sql_prod = "SELECT a.product_id,a.product_name,a.product_default_category_id,a.product_webprice,
											a.product_discount,a.product_discount_enteredasval,a.product_applytax,a.product_bonuspoints,a.product_variables_exists,a.product_variablesaddonprice_exists      									  
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
						?>
						
						<?php
						if ($title)
						{
						?>  
						<div class="head-title-blue"><?php  echo $title?></div>
						<?php
						}
						while($row_prod = $db->fetch_array($ret_prod))
						{
						?>
						
						<div class="spotlight-grid product-spot">
							<div class="product-title"><a class="linkbold" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php $row_prod['product_name']?>"><?php echo $row_prod['product_name']?></a></div>
							<div class="pro-img-wrap">
								<a class="linkbold" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php $row_prod['product_name']?>">
								<?php 
								$pass_type = 'image_thumbpath';
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
								  }?>
							    </a>
							</div>
							
							<?php $price_arr =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,5);
							$was_price = str_replace('+ VAT','',$price_arr['prince_without_captions']['base_price']);
							$save_price = str_replace('+ VAT','',$price_arr['prince_without_captions']['yousave_price']);
															//if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
																//{
								$price_class_arr['ul_class'] 		= 'price-avl';
								$price_class_arr['li_class'] 		= 'price';
								$price_class_arr['normal_class'] 	= 'price';
								$price_class_arr['strike_class'] 	= 'price_strike';
							    $price_class_arr['yousave_class'] 	= 'price_yousave';
								$price_class_arr['discount_class'] 	= 'price_offer';
																	
																	//echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																  
																 // }
							    $disc =  $price_arr['prince_without_captions']['discounted_price'];
								$base =  $price_arr['prince_without_captions']['base_price'];
															
								$curprice_tax_cap = curprice_tax($price_arr,$row_prod);
							 ?>
									<?php
									if($disc!='')
									{
										if($was_price!='')
										{
											?>
											<p><span class="price-strike">Was <?php echo $was_price ?>  </span><span class="save-price">(Save <?php echo $save_price;?> )</span></p>
											<?php
										}
									}
									?>									
									<span class="price"><?php
										if($disc!='')
												echo $disc;
										else
												echo $base;
								?> <?php echo $curprice_tax_cap; ?>
									</span>							
						
						</div>
						
						<?php 
						}
						?>
						
						<!--<div class="spcl_shlfA_showall_otr"> <a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="spcl_shlfA_showall" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?></a> </div> -->
						
						
						<?php														
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
			global $Captions_arr,$ecom_hostname,$vImage,$db,$ecom_siteid,$Settings_arr,$vImage,$position;
			$vimgfield = (!$Settings_arr['imageverification_req_newsletter'])?'':'newsletter_Vimg';
			$Captions_arr['NEWS_LETTER'] = getCaptions('NEWS_LETTER');
			if($position=='bottom')
			{
							
					?>
					
					<div class="col-md-6">
											<form name="frm_newsletter" method="post" action="" class="frm_cls newsletter_frm" onsubmit="return newsletter_validation(this)">

<p>Your email address....
<div class="input-group mb-3 emailad">
<input name="newsletter_email" type="text" class="newsletter_input_in form-control input-search" id="newsletter_email" placeholder="Subscribe to the Expert mailing list" value="Subscribe to the Expert mailing list" onfocus="if (this.value=='Subscribe to the Expert mailing list') this.value='';" onblur="if (this.value=='') this.value='Subscribe to the Expert mailing list';"  />	
  <div class="input-group-append">
    <button class="btn btn-outline-secondary subscribe" type="submit">subscribe</button>
  </div>
</div>
</p>
  <input type="hidden" name="newsletter_Submit" value="1" />

</form>
</div>
<?php
/*
			
			<div class="container">
				<div class="row">
					
					<div class="col-sm-12">
                    <div class="row"><p class="subscribe">SUBSCRIBE TO OUR NEWSLETTER</p>

<p class="subscribe_content">Subscribe to the Expert mailing list to receive updates on new arrivals, special offers and other discount information.</p></p>

					<form name="frm_newsletter" method="post" action="" class="frm_cls" onsubmit="return newsletter_validation(this)">
	                        <div class="input-group search-box-pad">
	                            							<input name="newsletter_email" type="text" class="newsletter_input_in form-control input-search" id="newsletter_email" placeholder="Subscribe to the Expert mailing list" onblur="if(document.frm_newsletter.newsletter_email.value=='') {document.frm_newsletter.newsletter_email.value='Enter Email'}" onclick="if(document.frm_newsletter.newsletter_email.value=='Enter Email'){javascript:document.frm_newsletter.newsletter_email.value=''}"  />

	                            <span class="input-group-btn">
	                           							<input name="newsletter_Submit" type="submit" value="GO" type="submit" class="btn btn-default to-log-in no-border-left" />

	                           </span>
	                        </div>
	                    </form>

</div>
                    
						
					
				</div>
			</div>
					
             <?php
             */
            
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
		    if($position=='left' || $position=='right')
			{
			?>
				<div class="web_sta_con"><a href="<?php echo get_buyGiftVoucherURL()?>" class="buygiftvoucherheader" title="<?php echo stripslash_normal($Captions_arr['GIFT_VOUCHER']['BUY_VOUCHER'])?>"><img src=" <?php url_site_image('gift-buy.gif') ?>"  border="0"/></a></div>
			<?php	
		    }
		}
		// ####################################################################################################
		// Function which holds the display logic for spending giftvoucher or promotional code
		// ####################################################################################################
		function mod_spendvoucher($title)
		{
			global $Captions_arr,$ecom_hostname,$db,$ecom_siteid,$Settings_arr,$vImage,$position;
			
			global $disgthm_group_cat_array;
			
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
				<div class="web_sta_con"> <a href="<?php echo $ecom_selfhttp.$ecom_hostname?>/spend_voucher.html" class="buygiftvoucherheader" title="<?php echo stripslash_normal($Captions_arr['GIFT_VOUCHER']['SPEND_VOUCHER_CLICK_HERE'])?>"><img src="<?php url_site_image('gift-use.gif')?>" /></a></div>
			<?
			}
			?>
		<?php	
		}
		// ####################################################################################################
		// Function which holds the display logic for Survey
		// ####################################################################################################
		function mod_survey($survey_array,$title)
		{
			global $Captions_arr,$ecom_hostname,$db,$ecom_siteid,$position;
			$Captions_arr['SURVEY'] = getCaptions('SURVEY');
			 if($position=='left' || $position=='right')
			 {
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
							<div class="survey_lf_con">
								<form name="survey_frm" action="" method="post" onsubmit="return validate_survey(this)">
								<div class="survey_lf_top">
									<div class="survey_outer">
									<?php	
									if($title)
									{
									?>
									<div class="survey_header"> <?php echo $title?></div>
									<?php
									}
									?>
									</div>
									<div class="survey_outer">
									<div class="survey_qst"> <?php echo stripslash_normal($surveyData['survey_question'])?> </div>
									</div>
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
									<div class="survey_outer">
										<div class="survey_opt">
											<div class="survey_opt_but">
												<input name="survey_opt" type="radio" value="<?php echo $row_surveyopt['option_id']?>" />
											</div>
											<div class="survey_opt_ans"><?php echo stripslash_normal($row_surveyopt['option_text']);?></div>
										</div>
									</div>
								<?php
								}
								}
								?>
								<input type="hidden" name="survey_comp_id" value="<?php echo $surveyData['survey_id']?>" />
									<div class="survey_outer">
										<div class="survey_submit">
											<input name="survey_Submit" type="submit" class="survey_submit_btn" value="<?php echo stripslash_normal($Captions_arr['SURVEY']['VOTE'])?>" />
										</div>
									</div>
								</div>
							<div class="survey_lf_bottom"></div>
						      </form>
							</div>
<?php
					}
					else
					{
						removefrom_Display_Settings($surveyData['survey_id'],'mod_survey');
					}
				}
			}	
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
						elseif($position == 'topband')
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
												shopbrand_default_shopbrandgroup_id ,shopbrand_subshoplisttype,a.shopbrand_showimageofproduct 
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
								if ($position == 'left' or $position == 'right') // ############## Left ##############
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
									<div class="shp_brnd_lf_top">
									<?php
									if($prev_grp != $groupData['shopbrandgroup_id'])
									{
										if ($groupData['shopbrandgroup_hidename']==0) // Decide whether or not to show the groupname
										{
										?>
											<div class="shp_brnd_header">
												<div class="shp_brnd_header_top"><?php echo stripslash_normal($title)?> </div>
												<div class="shp_brnd_header_bottom"></div>
											</div>
										<?php
										}
										$prev_grp = $groupData['shopbrandgroup_id'];
									}
									?>
									<div class="shp_brnd_menu">
										<ul class="shop_brand_menu">
										<?php
										while ($row_shop = $db->fetch_array($ret_shop))
										{
											$showimg_arr[$row_shop['shopbrand_id']] = $row_shop;
										?>
											<li>
											<h2><a href="<?php url_shops($row_shop['shopbrand_id'],$row_shop['shopbrand_name'],-1)?>" class="shop_brand_link" title="<?php echo stripslash_normal($row_shop['shopbrand_name'])?>"><?php echo stripslash_normal($row_shop['shopbrand_name']);?></a></h2>
											</li>
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
															<li>
															<h2><a href="<?php url_shops($row_child['shopbrand_id'],$row_child['shopbrand_name'],-1)?>" title="<?php echo stripslash_normal($row_child['shopbrand_name'])?>" class="shopleftlink"><?php echo stripslash_normal($row_child['shopbrand_name']);?></a></h2>
															</li>
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
									</div>
									<?php
									  if($groupData['shopbrandgroup_display_rotator']==1)
									  {
										$HTML_Content = '';
										$HTML_Content = $this->show_shop_rotator($showimg_arr);
										if($HTML_Content != '')
										{
									  ?>
											<div class="shp_brnd_scroll">
											<div class="shp_brnd_scroll_con">
											<div class="shp_brnd_scroll_inner">
											<?=$HTML_Content?>
											</div>
											</div>
											</div>
									<?php
										}
									  }
									  ?>
									<div class="shp_brnd_lf_bottom"></div>
									</div>
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
										// Do the following only if caching is not enabled or cache does not exists
										if ($cache_exists==false)
										{
											if($cache_required)// if caching is required start recording the output
											{
												ob_start();
											}
									?>
									<div class="shp_brnd_lf_con">
										<div class="shp_brnd_lf_top">
										<?php
										if ($groupData['shopbrandgroup_hidename']==0) // Decide whether or not to show the groupname
										{
										?>
										<div class="shp_brnd_header">
											<div class="shp_brnd_header_top"><?php echo $title?></div>
											<div class="shp_brnd_header_bottom"></div>
										</div>
										<?php
										}
										?>
										<div class="shp_brnd_select">
										<select name="prodshopgroup_<?php echo $groupData['shopbrandgroup_id']?>" onchange="handle_dropdownval_sel(this.value)">
										<option value="">-- <?php echo stripslash_normal($Captions_arr['COMMON']['SELECT'])?> -- </option>
										<?php
											// Case if categories are to be shown in dropdown box
											while ($row_shop = $db->fetch_array($ret_shop))
											{
												$showimg_arr[$row_shop['shopbrand_id']] = $row_shop;
											?>
										<option value="<?php url_shops($row_shop['shopbrand_id'],$row_shop['shopbrand_name'],-1,$groupData['shopbrandgroup_id'])?>" <?php echo (($row_shop['shopbrand_id']==$_REQUEST['shop_id']) /*and ($groupData['shopbrandgroup_id']==$_REQUEST['shopgroup_id'])*/)?'selected="selected"':''?>><?php echo stripslash_normal($row_shop['shopbrand_name'])?></option>
										<?php		
											}
										?>
										</select>
										</div>
										<?php
										if($groupData['shopbrandgroup_display_rotator']==1)
										{
										$HTML_Content = '';
										$HTML_Content = $this->show_shop_rotator($showimg_arr);
										if($HTML_Content != '')
										{
										?>
										<div class="shp_brnd_scroll">
											<div class="shp_brnd_scroll_con">
												<div class="shp_brnd_scroll_inner"  align="center">
												
												<?=$HTML_Content?>
												
												</div>
											</div>
										</div>
										<?php
										}
										}
										?>
										</div>
									<div class="shp_brnd_lf_bottom"></div>
									</div>
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
										// Do the following only if caching is not enabled or cache does not exists
										if ($cache_exists==false)
										{
											if($cache_required)// if caching is required start recording the output
											{
												ob_start();
											}
									?>
									<div class="shp_brnd_lf_con">
										<div class="shp_brnd_lf_top"> <a href="<?php url_link('shopbybrand.html')?>"><img src="<?php url_site_image('shop-brnd-banner.gif')?>" border="0" /></a>
											<?php
											if($groupData['shopbrandgroup_display_rotator']==1)
											{
												while ($row_shop = $db->fetch_array($ret_shop))
												{
													$showimg_arr[$row_shop['shopbrand_id']] = $row_shop;
												}	
												$HTML_Content = '';
												$HTML_Content = $this->show_shop_rotator($showimg_arr);
												if($HTML_Content != '')
												{
													?>
													<div class="shp_brnd_scroll">
														<div class="shp_brnd_scroll_con">
															<div class="shp_brnd_scroll_inner">
																
																<?=$HTML_Content?>
																
															</div>
														</div>
													</div>
													<?php
												}
											}
											?>
										</div>
										<div class="shp_brnd_lf_bottom"></div>
									</div>
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
								}
								elseif($position == 'topband')
								{
									// Do the following only if caching is not enabled or cache does not exists
									if ($cache_exists==false)
									{
										if($cache_required)// if caching is required start recording the output
										{
											ob_start();
										}
										$HTML_Content = '';
										$pass_type = 'image_thumbpath';
										$cnts = 0;
										$width_one_set 	= 200;
										$min_number_req	= 5;
										$min_width_req 	= $width_one_set * $min_number_req;
										$total_cnt		= $db->num_rows($ret_shop);
										$calc_width		= $total_cnt * $width_one_set;
										if($calc_width < $min_width_req)
											$div_width = $min_width_req;
										else
											$div_width = $calc_width; 
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
																	<div class="shp_brnd_thumbimg_pdt">
																		<div class="shp_brnd_thumbimg_image">
																		<a href="'.url_shops($row_shop['shopbrand_id'],$row_shop['shopbrand_name'],'').'" title="'.stripslashes($row_shop['shopbrand_name']).'">
																			'.$HTML_image.'
																		</a>
																		</div>
																	</div>';
												$cnts++;
											}				
										}
										$divid = uniqid('shopbrand');	
									?>
									<div class="shp_brnd_con">
									<div class="shp_brnd_left"></div>
									<div class="shp_brnd_mid">
									<div class="shp_brnd_thumbimg_con">
									  <div class="shp_brnd_nav"><a href="#<?php /*url_link('shopbybrand.html')*/?>" onmouseover="scrollDivRight('<?=$divid?>')" onmouseout="stopMe()"><img src="<?php url_site_image('top-shop-arrow-left.gif')?>"></a></div>
									  <div id="<?=$divid?>" class="shp_brnd_thumbimg_inner">
										<div id="shp_brnd_thumb" style="width:<?php echo $div_width?>px">
										  <?=$HTML_Content?>
										</div>
									  </div>
									  <div class="shp_brnd_nav"><a href="#<?php /*url_link('shopbybrand.html')*/?>" onmouseover="scrollDivLeft('<?=$divid?>',<?php echo ($cnts*90)?>)" onmouseout="stopMe()" o><img src="<?php url_site_image('top-shop-arrow-right.gif')?>"></a></div>
									</div>
									</div>
									<div class="shp_brnd_right"></div>
									</div>
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
								elseif($position == 'bottom')
								{
									if ($cache_exists==false)
									{
										if($cache_required)// if caching is required start recording the output
										{
											ob_start();
										}
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
									<div class="footerBrandsB" >
									  <?=$HTML_Content?>
									</div>
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
			$HTML_Content .= '	<script type="text/javascript">
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
		}
		// ####################################################################################################
		// Function which holds the display logic for Recently Viewed Products
		// ####################################################################################################
		function mod_recentlyviewedproduct($cookval,$title)
		{
			global $Captions_arr,$ecom_siteid,$db,$Settings_arr,$position;
		   	$frm_name = uniqid('recent_');
			//if($position=='left' || $position=='right')
			{
				/*
			?>
				<form name="<? echo $frm_name?>" id="<? echo $frm_name?>" action="" method="post">
				<input type="hidden" name="remove_recent" id="remove_recent" value="remove_recent" />
				*/
				?> 
					<div class="col-md-3">    <h2>recently viewed</h2>
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
<div class="related-product clearfix">					<?php 
								if (!$Settings_arr['recentlyviewed_hide_image'])
								{
					?>
									<figure> <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>">
					  <?php
									// Calling the function to get the type of image to shown for current 
									$pass_type = 'image_thumbpath';
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
					  				</a></figure>
									<h5> <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"  title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a> </h5>
					<?
								}
					?>
				  			</div>
				  <?php
							}
						}
					}/*	
					?>
				  	<a href="#"  title="<?php echo stripslash_normal($Captions_arr['COMMON']['COMON_RECENT'])?>"  onclick="if(confirm('Are You sure You want to clear the recent product list')){ document.<?=$frm_name?>.submit()};" class="recent_view-showall"><?php echo stripslash_normal($Captions_arr['COMMON']['COMON_RECENT'])?></a> 
				  	*/
				  	?> 
					</div>
    
					<div class="clearfix"></div>
					<?php
					/*
					</form>					
					*/
					?> 
					
<?php 
				}
		}
		// ####################################################################################################
		// Function which holds the display logic for adverts
		// ####################################################################################################
		function mod_adverts($advert_arr,$title)
		{
			
			global $ecom_siteid,$db,$position,$Settings_arr;
			
			if ($position=='mobiletopband' ) // show the advert only if position is left or right
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
                       <div class="main-container">
  					<?php
						switch ($k['advert_type'])
						{
							case 'IMG':
								$path = url_root_image('adverts/'.$k['advert_source'],1);
								$link = $k['advert_link'];
								if ($link!='')
								{
							?>
  								<a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">
  							<?php
								}
							?>
  								<div class="advert_txt"> <img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo stripslashes($k['advert_title'])?>" border="0" /> </div>
  							<?php		
								if ($link!='')
								{
							?>
 								 </a>
  							<?php		
								}
							break;
							case 'PATH':
								$path = $k['advert_source'];
								$link = $k['advert_link'];
								if ($link!='')
								{
								?>
									  <a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">
									  <?php
								}
								?>
  								<img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo stripslashes($k['advert_title'])?>" border="0" />
  								<?php		
								if ($link!='')
								{
								?>
  									</a>
  								<?php		
								}
							break;
							case 'TXT':
								$path = $k['advert_source'];
							?>
								 <div class="legacy-grid">
								<div class="row"> <?php echo stripslash_normal($path); ?> </div>
									
								</div>
  							<?php
							break;
							
							case 'SWF'://for  flash file
								$path = url_root_image('adverts/'.$k['advert_source'],1);
								$link = $k['advert_link'];
								$flash_path =  '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="200" height="95">
								<param name="movie" value='.$path.'  >
								<param name="quality" value="high" >
								<param name="BGCOLOR" value="#D6D8CB"><embed src='.$path.' type=application/x-shockwave-flash width = 200 height=95> </object>';
								$img_link=  '';
								echo  $flash_path ;
							break;
							case 'ROTATE':   // case if ad rotate images are set
							?>
					
							
								<div id="carousel-example-generic" class="carousel slide">

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
								   $HTML_Content .= "";
									$HTML_Content .= '';
									$adv_arr = array();
									while ($row_rotate = $db->fetch_array($ret_rotate))
									{
										$adv_arr[]    = 	$row_rotate;	
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
									$cnt = 0;
									/*
									if(count($adv_arr)>1)
									{
										?>
									   <ol class="carousel-indicators">			
										<?php
									   for($i=0;$i<count($adv_arr);$i++)
									   {
										   $active =""; 
										   if($i==0)
										   {
										     $active = "active";
										   }
										   ?>
										   <li data-target="#carousel-example-generic" data-slide-to="<?php echo $i;?>" class="<?php echo $active;?>"></li>
										   <?php
										
									   }
									   ?>
									   </ol>
									   <?php									
									}
									*/ 
									?>
									<div class="carousel-inner" role="listbox">

									<?php
									  
									foreach($adv_arr as $k=>$v)
									{ 
											$active ="";
											if($cnt==0)
											{
											 $active = "active";
											}
											$cnt++;
											if($v['rotate_alttext']!='')
											{
											$alt_text = $v['rotate_alttext']; 
											}
											else
											{
											$alt_text = $k['advert_title']; 
											}
											?>
											
											<div class="item <?php echo $active ?> deepskyblue" style="	background-image:url(<?php echo url_root_image('adverts/rotate/'.$v['rotate_image'],1);?>);">
                                            <?php 
                                            if($alt_text!='')
                                            {
                                            ?>
											<div class="carousel-caption">
											<h3 data-animation="animated bounceInLeft">
											<?php //echo $alt_text;?>
											</h3>
											
											</div>
											<?php
											}
											?>
											</div><!-- /.item -->			
				<?php
										
									}
									//$HTML_Content .='';
									//echo $HTML_Content;
									?>
									</div>
									<?php
									}
									/*
								?>
								
								<!-- Controls -->
		<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
		*/
		?> 
 								</div>
  <?php	
							break;
						};
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
			if ($position=='mobiletop' ) // show the advert only if position is left or right
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
							<div class="main-container">
						    <div class="advert_topnav">
  					<?php
						switch ($k['advert_type'])
						{
							
							case 'TXT':
								$path = $k['advert_source'];
							?>
								 <div class="legacy-grid">
								<div class="row"> <?php echo stripslash_normal($path); ?> </div>
									
								</div>
  							<?php
							break;							
							
						};
						?>
							</div>
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
			if ($position=='topband' ) // show the advert only if position is left or right
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
						switch ($k['advert_type'])
						{							
							
							case 'TXT':
								$path = $k['advert_source'];
								$search_s = array('[ao]','[ag]','[ar]','[ac]','[enda]','[i]');
							  	$repl_s   = array('<a class="category-link orange-category" href="http://dmresponsive.bshop4.co.uk" title="" data-aos="fade-left">','<a class="category-link green-category" href="http://dmresponsive.bshop4.co.uk" title="" data-aos="fade-left">','<a class="category-link red-category" href="http://dmresponsive.bshop4.co.uk" title="" data-aos="fade-left">','<a class="category-link coffeeBrown-category" href="http://dmresponsive.bshop4.co.uk" title="" data-aos="fade-left">','</a>','<i class="fas fa-angle-right fa-pad"></i>');
							    $path = str_replace($search_s,$repl_s,$path);
							 
							?>		 <?php echo stripslash_normal($path); ?> 	
  							<?php
							break;	
							case 'ROTATE':   
							global $ecom_hostname;
							?>
								<div class="rotator_newoutermostdiv">
								<div class="rotator_newleftdiv">
								<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">


								<?php
								$advert_ul_id = uniqid('advert_');
								
								
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
								

								$HTML_Content .= ' <div class="carousel-inner">';
								$cnt_cls = 0;
								while ($row_rotate = $db->fetch_array($ret_rotate))
								{
									if($cnt_cls==0)
									{
									 $activecls ='active';
									}
									else
									{
									 $activecls ='';
									}
								$HTML_Content .= '<div class="carousel-item '.$activecls.'">';
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
											<img class="d-block w-100" src="'.url_root_image('rot/img/'.$row_rotate['rotate_image'],1).'" alt="'.stripslashes($alt_text).'" title="'.stripslashes($alt_text).'" />'.
											$link_end.'';
								$HTML_Content .= "</div>";
								$HTML_Content1 .= '<div class="carousel-caption d-none d-md-block">';
    $HTML_Content1 .= '<p>'.stripslashes($alt_text).'</p>';
 $HTML_Content1 .= ' </div>';
								$cnt_cls++;
								}
								$HTML_Content .='</div>';

								}
								echo $HTML_Content;
								?>
 <?php
 /*
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  */
  ?> 
   
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
								</div>
								
									
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
								?>
								
								<?php
							}	
						}
					}			
				}
			}
			if ($position=='top' ) // show the advert only if position is left or right
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
						switch ($k['advert_type'])
						{							
							
							case 'TXT':
								$path = $k['advert_source'];
								$search_s = array('[ao]','[ag]','[ar]','[ac]','[enda]','[i]');
							  	$repl_s   = array('<a class="category-link orange-category" href="https://www.puregusto.co.uk/paper-cups-disposables-c77637.html" title="DISPOSABLE CUPS & ACCESSORIES" data-aos="fade-left">','<a class="category-link green-category" href="https://www.puregusto.co.uk/hot-chocolates-chai-drinks-c77573.html" title="HOT CHOCOLATE & CHAI LATTES" data-aos="fade-left">','<a class="category-link red-category" href="https://www.puregusto.co.uk/flavoured-coffee-syrups-c77582.html" title="ALL NATURAL FLAVOURED COFFEE SYRUPS" data-aos="fade-left">','<a class="category-link coffeeBrown-category" href="https://www.puregusto.co.uk/puregusto-roasted-coffee-range-c77568.html" title="GREAT TASTE AWARD WINNING COFFEES" data-aos="fade-left">','</a>','<i class="fas fa-angle-right fa-pad"></i>');
							    $path = str_replace($search_s,$repl_s,$path);
							 
							?>
								  <?php echo stripslash_normal($path); ?> 
								  		
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
								?>
								
								<?php
							}	
						}
					}			
				}
			}
			if ($position=='mobilemiddleband' ) // show the advert only if position is left or right
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
                       <div class="main-container">
  					<?php
						switch ($k['advert_type'])
						{							
							
							case 'TXT':
								$path = $k['advert_source'];
							?>
								<div class="container">
								  <?php echo stripslash_normal($path); ?> 
									
								</div>
  							<?php
							break;							
						};
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
			if ($position=='bottom' ) // show the advert only if position is left or right
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
						switch ($k['advert_type'])
						{							
							
							case 'TXT':
								$path = $k['advert_source'];
							?>
								  <?php echo stripslash_normal($path); ?> 
									
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
			if ($position=='left' ) // show the advert only if position is left or right
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
						switch ($k['advert_type'])
						{							
							
							case 'TXT':
								$path = $k['advert_source'];
							?>
								  <?php echo stripslash_normal($path); ?> 
									
  							<?php
							break;	
							case 'IMG':
								$path = url_root_image('adverts/'.$k['advert_source'],1);
								$link = $k['advert_link'];
								if ($link!='')
								{
							?>
  								<a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">
  							<?php
								}
							?>
  								<div class="advert_txt"> <img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo stripslashes($k['advert_title'])?>" border="0" /> </div>
  							<?php		
								if ($link!='')
								{
							?>
 								 </a>
  							<?php		
								}
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
			if ($position=='right' ) // show the advert only if position is left or right
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
						switch ($k['advert_type'])
						{							
							
							case 'TXT':
								$path = $k['advert_source'];
							?>
								  <?php echo stripslash_normal($path); ?> 
									
  							<?php
							break;	
							case 'IMG':
								$path = url_root_image('adverts/'.$k['advert_source'],1);
								$link = $k['advert_link'];
								if ($link!='')
								{
							?>
  								<a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">
  							<?php
								}
							?>
  								<div class="advert_txt"> <img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo stripslashes($k['advert_title'])?>" border="0" /> </div>
  							<?php		
								if ($link!='')
								{
							?>
 								 </a>
  							<?php		
								}
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
		?>
			<div class="review_banner"><a href="<?php url_link('sitereview.html');?>"><img src="<?php url_site_image('review-banner.gif')?>" /></a></div>
<?php	
		}
		
		// ####################################################################################################
		// Function which holds the display logic for Recently Viewed Products
		// ####################################################################################################
		function mod_productlist($ret_prod,$title)
		{
			global $Captions_arr,$ecom_siteid,$db,$Settings_arr,$row_cats;
		?>
			<div class="product_lf_con">
			  <div class="product_lf_top"></div>
			  <div class="product_lf_mid">
				<?php
				if($title)
				{
				?>
				<div class="product_header">
				  <div class="product_header_top"></div>
				  <div class="product_header_bottom"><?php echo $title ?></div>
				</div>
				<?php
				}
				?>
				<ul class="productlist">
				<?php
				while ($row_prod = $db->fetch_array($ret_prod))
				{
				?>
				  <li>
					<h2><a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="productlink" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a></h2>
				  </li>
				<?php
				}
				?>
				</ul>
			  </div>
			  <div class="prolist_btnlf"> <a href="<?php  url_category($_REQUEST['category_id'],$row_cats['category_name'],-1)?>" class="prodlist_linklf" title="<?php echo $Captions_arr['COMMON']['SHOW_PROD_ALL']?>"><span><?php echo $Captions_arr['COMMON']['SHOW_PROD_ALL']?></span></a> </div>
			  <div class="product_lf_bottom"></div>
			</div>
<?php		
		}
		function mod_statistics($title,$stat_query)
		{
			global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
			if($db->num_rows($stat_query))
			{
				$row_query = $db->fetch_array($stat_query);
			}
			if($position=='left' || $position=='right')
			{
			?>
				<div class="web_sta_con">
				<?php
				if($title)
				{
				?>
					<div class="web_sta_con_hdr"><?php echo $title?></div>
				<?
				}
				?>
				<div class="web_sta_con_txt"><span><?=$row_query['site_hits']?></span><?php echo stripslash_normal($Captions_arr['COMMON']['WEB_STATISTICS'])?></div>
				</div>
<?
			}
		}
		function mod_ssl($title)
        {
        	global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$image_path;
        ?>
			<div class="ssl">
			<div class="ssl_top"></div>
			<div class="ssl_middle">
			<?php
			if($title)
			{
			?>
				<div class="ssl_headr"><?php echo $title?></div>
			<?php
			}
			if(file_exists("$image_path/site_images/ssl_main_image.gif"))
			{
			?>
				<img src="<? url_site_image('ssl_main_image.gif')?>" alt="Secure Payment" title="<?php echo $title?>" border="0" />
			<?
			}
			?>
			</div>
			<div class="ssl_bottom"></div>
			</div>
<?
		}
		// Function to show the top menu item
		function mod_topmenu($title)
		{ //LOGIN_TOPMENU;
			global $db,$ecom_siteid,$ecom_hostname,$inlineSiteComponents,$Captions_arr;
		
			 $Captions_arr['LOGIN_MENU'] 	= getCaptions('LOGIN_MENU');
			 $cust_id 					= get_session_var("ecom_login_customer");
		?>
		
  <!--<ul class="dropdown-menu">
				 <li>
					<h2><a href="<?php url_link('login_home.html')?>" class="userloginmenuytoplink">
					<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_HOME'])?>
					</a>
					</h2>
				  </li>
			 <li>
					<h2><a href="<?php url_link('myprofile.html')?>" class="userloginmenuytoplink">
					  <?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_PROFILE'])?>
					</a>
					</h2>
				  </li>
				 
				  <li>
					<h2>
					<a href="<?php url_link('myorders.html')?>" class="userloginmenuytoplink">
					  <?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_ORDERS'])?>
					</a>
					</h2>
				  </li>
				  <li>
					<h2>
					<a href="<?php url_link('myenquiries.html')?>" class="userloginmenuytoplink">
					<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_ENQUIRIES'])?>
					</a>
					</h2>
				  </li>
				 
				  
				  <li>
					<h2>
					<a href="<?php url_link('wishlist.html')?>" class="userloginmenuytoplink">
					  <?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_WISHLIST'])?>
					</a>
					</h2>
				  </li>
				  
				 
				</ul>-->
				
		 <div class="dropdown-menu menu_list" aria-labelledby="dropdownMenuLink">
				<a href="<?php url_link('login_home.html')?>" class="userloginmenuytoplink dropdown-item"><?php echo stripslash_normal($Captions_arr['LOGIN_MENU']['MY_HOME'])?></a>
				<a href="<?php url_link('myprofile.html')?>"class=" dropdown-item" ><?php echo stripslash_normal($Captions_arr['LOGIN_MENU']['MY_PROFILE'])?></a>
				<a class="userloginmenuytoplink dropdown-item" href="<?php url_link('myorders.html')?>"><?php echo stripslash_normal($Captions_arr['LOGIN_MENU']['MY_ORDERS'])?></a>
				<a class="userloginmenuytoplink dropdown-item" href="<?php url_link('myfavorites.html')?>"><?php echo stripslash_normal($Captions_arr['LOGIN_MENU']['MY_FAVOURITE'])?></a>
				<a class="userloginmenuytoplink dropdown-item" href="<?php url_link('myenquiries.html')?>"><?php echo stripslash_normal($Captions_arr['LOGIN_MENU']['MY_ENQUIRIES'])?></a>
				<a class="userloginmenuytoplink dropdown-item" href="<?php url_link('wishlist.html')?>"><?php echo stripslash_normal($Captions_arr['LOGIN_MENU']['MY_WISHLIST'])?></a>
		  
		  </div>
				
<?php
		}
		
		/* Function to show the currency selector */
		function mod_currencyselector($title)
		{
			global $db,$ecom_siteid,$sitesel_curr,$position;
		  	// get the list of currencies to be used with the site
			$curr_arr = get_currency_list();
			 $comp_uniqid = uniqid('');
			 if($position == 'left' or $position == 'right')
			 {
	  	?>
				  <div class="rt_curr">
					<form method="post" name="frm_maincurrency_<?=$comp_uniqid?>" enctype="multipart/form-data" class="frm_cls" action="">
					<div class="rt_curr_top"></div>
					<div class="rt_curr_middle">
					<?php
					if($title)
					{
					?>
						<div class="rt_curr_txtA"><?php echo $title?></div>
					<?
					}
					?>
					<div class="rt_curr_txtB">
					<?php
						//showing the currency selection drop down
						echo generateselectbox('cbo_selcurrency',$curr_arr,$sitesel_curr,'','document.frm_maincurrency_'.$comp_uniqid.'.submit()',0,'currencyselectordropdown');
					 ?>
					</div>
					</div>
					<div class="rt_curr_bottom"></div>
					</form>
				  </div>
<?php
			}
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
				return $ret_array;
			} else {
				$header_image = url_site_image('main_header.jpg',1);
				$ret_array['img']  = $header_image;
				$ret_array['text']  = '';
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
				?>
					<div class="pre_odr_lf_con">
					  <div class="pre_odr_lf_top">
						<?php
						if($title)
						{
						?>
							<div class="pre_odr_pdt_header"><?php echo $title?></div>
						<?
						}
								$cookarray 	= explode(",",$cookval);
								
						if ($db->num_rows($ret_main))
						{	
							while ($row_main = $db->fetch_array($ret_main))
							{
					?>
							<div class="pre_odr_pdt_outer">
							  <div class="pre_odr_pdt_top"></div>
							  <div class="pre_odr_pdt_mid"><a href="<?php echo url_product($row_main['product_id'],$row_main['product_name'],-1)?>" class="preordprodlink" title="<?php echo stripslash_normal($row_main['product_name'])?>"><?php echo stripslash_normal($row_main['product_name'])?></a></div>
							  <div class="pre_odr_pdt_bottom"></div>
							</div>
					<?
							}
						}
					?>
						<div class="preorder_show"><a href="<? url_link('preorder'.$display_id.'.html')?>" class="preorder_showall" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?>" ><?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?></a></div>
					  </div>
					  <div class="pre_odr_lf_bottom"></div>
					</div>
<?php	
				}	
			}	
		}
		 function mod_payonaccount($title)   // Function to show the payonaccount banner
		 {
			 global $db,$ecom_siteid,$sitesel_curr,$position,$ecom_common_settings;
			 if($position =='left' or $position=='right' and ($ecom_common_settings['paytypeCode']['pay_on_account']['paytype_code']=='pay_on_account'))
			 {
		?>
				<div class="credit_banner" align="left"><a href="<?php url_link('payonaccount.html')?>"><img src="<?php url_site_image('creditbanner.gif')?>" alt="PayonAccount" title="PayonAccount" border="0" /></a></div>
<?
			}
		 }
		 function upsell_products_display()
		{
			global $db,$ecom_siteid,$ecom_hostname,$inlineSiteComponents;
			global $Captions_arr;
			
			if(check_IndividualSslActive())
			{
				$ecom_selfhttp = "https://";
			}
			else
			{
				$ecom_selfhttp = "http://";
			}
			$Captions_arr['PROD_DETAILS'] = getCaptions('PROD_DETAILS');
			$Captions_arr['CART']	= getCaptions('CART');
			$session_id 	= session_id();
			$avoid_prod_arr	= array(0);
			// Get the list of products existing in the cart.
			$sql_cart = "SELECT products_product_id 
							FROM 
								cart 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND session_id = '$session_id'";
			$ret_cart = $db->query($sql_cart);
			if($db->num_rows($ret_cart))
			{
				while ($row_cart = $db->fetch_array($ret_cart))
				{
					$avoid_prod_arr[] = $row_cart['products_product_id'];
				}
			}
			$cat_arr = $prods_arr = $prod_arr = array();
			
			// Get the list of categories to which each of these products are linked with
			$sql_cat = "SELECT product_categories_category_id FROM product_category_map WHERE products_product_id IN(".implode(',',$avoid_prod_arr).") ";
			$ret_cat = $db->query($sql_cat);
			if($db->num_rows($ret_cat))
			{
				while ($row_cat = $db->fetch_array($ret_cat))
				{
					$cat_arr[] = $row_cat['product_categories_category_id'];
				}
				
				if(count($cat_arr))
				{
					
					/*// Get the list of products which are mapped to obtained categories
					$sql_prods = "SELECT distinct products_product_id FROM product_category_map WHERE product_categories_category_id IN (".implode(',',$cat_arr).")";
					$ret_prods = $db->query($sql_prods);
					if($db->num_rows($ret_prods))
					{
						while ($row_prods = $db->fetch_array($ret_prods))
						{
							if(!in_array($row_prods['products_product_id'],$avoid_prod_arr))
							{
								$prods_arr[] = $row_prods['products_product_id'];
							}
								
						}
					}*/
					
						$start		= date("Y-m-d",mktime(0, 0, 0, date("m")-12  , date("d"), date("Y")));
						$end 		= date("Y-m-d");
						$sql_best 	= "SELECT p.product_id,sum(b.order_orgqty) as totcnt  
										FROM 
											orders a,order_details b,products p 
										WHERE 
											a.order_id=b.orders_order_id 
											AND a.sites_site_id=$ecom_siteid 
											AND b.products_product_id=p.product_id 
											AND p.product_hide ='N' 
											AND a.order_date >= '$start 00:00:00' AND a.order_date <= '$end 23:59:59'
											AND a.order_status NOT IN ('CANCELLED','NOT_AUTH') 
											AND p.product_default_category_id IN (".implode(',',$cat_arr).") 
											AND p.product_id NOT IN (".implode(',',$avoid_prod_arr).") 
										GROUP BY 
											b.products_product_id  
										ORDER BY 
											totcnt DESC 
										LIMIT 
											20";
					$res = $db->query($sql_best);
					if($db->num_rows($res))
					{
						while ($row_prods = $db->fetch_array($res))
						{
							if(!in_array($row_prods['product_id'],$avoid_prod_arr))
							{
								$prods_arr[] = $row_prods['product_id'];
							}
								
						}
					}
				}
			}
			$hold_prods_arr = $prods_arr;
			// Check whether any upsell products are manually set up in the database
			$sql_manupsell = "SELECT products_product_id 
								FROM 
									upsell_products_map 
								WHERE 
									sites_site_id = $ecom_siteid 
								ORDER  BY 
									upsell_order ASC";
			$ret_manupsell = $db->query($sql_manupsell);
			if($db->num_rows($ret_manupsell))
			{
				while ($row_manupsell = $db->fetch_array($ret_manupsell))
				{
					if(!in_array($row_manupsell['products_product_id'],$avoid_prod_arr) and !in_array($row_manupsell['products_product_id'],$hold_prods_arr))
					{
						$prods_arr[] = $row_manupsell['products_product_id'];
					}
				}
			}
			
			if(count($prods_arr))
			{
				$prod_arr = array();
				$sql_prod = "SELECT * FROM products WHERE product_id IN (".implode(',',$prods_arr).") AND product_hide = 'N' and sites_site_id = $ecom_siteid ";
				$ret_prod = $db->query($sql_prod);
				if($db->num_rows($ret_prod))
				{
						$pass_type = get_default_imagetype('link_prod');

			$width_one_set 	= 166;
			$min_number_req	= 4;
			$min_width_req 	= $width_one_set * $min_number_req;
			$total_cnt		= $db->num_rows($ret_prod);
			$calc_width		= $total_cnt * $width_one_set;
			if($calc_width < $min_width_req)
				$div_width = $min_width_req;
			else
				$div_width = $calc_width; 
					while ($row_prod = $db->fetch_array($ret_prod))
					{
						$prod_arr[] = $row_prod;
					}
					//echo "<pre>";var_dump($prod_arr);echo "</pre>";
			?>
					<div class="container-fluid yomaylike">
<div class="headtitle-white"><?php echo stripslash_normal($Captions_arr['PROD_DETAILS']['PRODDET_PEOPLE_INTERESTED']);?></div>
<script type="text/javascript">
			$(function() {

				var $c = $('#carousel1'),
					$w = $(window);

				$c.carouFredSel({
					align: false,
					items:3,
					scroll: {
						items: 2,
						duration: 8000,
						timeoutDuration: 0,
						easing: 'linear',
						pauseOnHover: 'immediate'
					}
				});

			});
		</script>


<div class="container-fluid">
<div id="demo2">
<div class="customNavigation2">
									<a class="prev glyphicon glyphicon-arrow-left  btn-info"><i class="fas fa-angle-left"></i></a>
									<a class="next glyphicon glyphicon-arrow-right  btn-info"><i class="fas fa-angle-right"></i></a>
									</div>

	<div id="owl-demo2" class="owl-carousel">      
									<?php
								$i=0;
								$cnt_rem = count($prod_arr);
								$cur_col=0;
								$max_col=2;
								while($i<$cnt_rem)
								{
									$row_prod = $prod_arr[$i];
					?>
					<div class="item">
					<div class="product-grid">
					<div class="product-title"><a class="product_name_a" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a>
</div>
					<p class="product-info"><?php echo stripslash_normal($row_prod['product_shortdesc']); ?></p>

					<div class="product-img-wrap"><<a class="product_pic_a" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>" <?php //echo getmicrodata_producturl()?> >
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
																		</a></div>  
					<?php $price_arr =  show_Price($row_prod,$price_class_arr,'cat_detail_1',false,5);
															//if ($shelfData['shelf_showprice']==1)// Check whether description is to be displayed
																//{
																	$price_class_arr['ul_class'] 		= 'price-avl';
																	$price_class_arr['li_class'] 		= 'price';
																	$price_class_arr['normal_class'] 	= 'price';
																	$price_class_arr['strike_class'] 	= 'price_strike';
																	$price_class_arr['yousave_class'] 	= 'price_yousave';
																	$price_class_arr['discount_class'] 	= 'price_offer';
																	
																	//echo show_Price($row_prod,$price_class_arr,'shelfcenter_1');
																  
																 // }
																  $disc =  $price_arr['prince_without_captions']['discounted_price'];
																  $base =  $price_arr['prince_without_captions']['base_price'];
															
																	$curprice_tax_cap = curprice_tax($price_arr,$row_prod);
														   ?>
															<p class="price-details">2<span class="price"><?php
															if($disc!='')
															{
																	echo $disc;
																	echo $curprice_tax_cap;
															}
															else if($base!='')
															{
															echo $base;
															echo $curprice_tax_cap;
															}
															?> <?php echo $curprice_tax_cap; ?></span></p><?php show_BulkDiscounts($row_prod,array()); ?>
					<div class="addwrap">
					<?php $frm_name = uniqid('catdet_'); ?>
													
																	<form method="post" action="<?php url_link('manage_products.html')?>" name='<?php echo $frm_name?>' id="<?php echo $frm_name?>" class="frm_cls" onsubmit="return product_enterkey(this,<?php echo $row_prod['product_id']?>)">
																	<input type="hidden" name="fpurpose" value="" />
																	<input type="hidden" name="fproduct_id" value="" />
																	<input type="hidden" name="pass_url" value="<?php echo $_SERVER['REQUEST_URI']?>" />
																	<input type="hidden" name="fproduct_url" value="<?php url_product($row_prod['product_id'],$row_prod['product_name'])?>" />
													<?php			$class_arr['ADD_TO_CART']       = 'btn btn-outline-secondary addbt';
																	$class_arr['PREORDER']          = 'input-group-addon';
																	$class_arr['ENQUIRE']           = 'btn btn-outline-secondary addenq';
																	$class_arr['QTY_DIV']           = '';
																	$class_arr['QTY']               = 'form-control qty_txt';
																	$class_arr['BTN_CLS']     = 'btn btn-outline-secondary addbt';
																	echo show_addtocart_responsive($frm_name,$row_prod,$class_arr,1,'shelf',false,false,'new_v2');?>
															<a class="product_name_a" href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
										<button class="btn btn-outline-secondary detailbt" type="button">Details</button></a>
										
																	</form>
					</div>

					</div>
					</div>
										

					<?php
					$i=$i+1;
					$cur_col++;
				
			}
					?>		
</div>								
</div>

</div>

</div>
			<?php	
				}
			}
		}
	};
?>