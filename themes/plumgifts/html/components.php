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
			$Captions_arr['STATIC_PAGES'] 	= getCaptions('STATIC_PAGES');
			$add_statcondition 				= " AND a.pname <> 'Home'";
			// section to be used with caching
			switch($position)
			{
				case 'topband':
					$cache_type		= 'comp_topstatgroup';	
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
					// ############## Top ##############
					if ($position == 'top') // Case if value of position is top;
					{
					  $show_top = $show_bottom = 0;
						?>
				<tr>
					<td colspan="2" align="center" valign="top" class="maintoptd_links">
						<div class=" static_con">
							<ul>
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
												static_pages_order";
									$ret_pg = $db->query($sql_pg);
									$cnt = $db->num_rows($ret_pg);
									
									if ($grpData['group_showhomelink']==1)
									{
										$cls = ($_REQUEST['req']=='')?'static_menu_selected':'static_menu';
									?>
										<li>
										<h2> <a href="<?php url_link('')?>" class="<?php echo $cls?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?>"><span><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?></span></a></h2>
										</li>
									<?php
									}
									while ($row_pg = $db->fetch_array($ret_pg))
									{
										$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
										$cls = ($row_pg['page_id']==$_REQUEST['page_id'])?'static_menu_selected':'static_menu';
										
									?>
										<li>
										<h2> <a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="<?php echo $cls?>" title="<?php echo stripslash_normal($row_pg['title'])?>" <?php echo $target?>><span><?php echo stripslash_normal($row_pg['title']);?></span></a></h2>
										</li>
									<?php
									}
									if ($grpData['group_showxmlsitemaplink']==1 )
									{
										$cls = ($_REQUEST['req']=='sitemap')?'static_menu_selected':'static_menu';
									?>
										<li>
										<h2> <a href="<?php url_link('sitemap.xml')?>" class="<?php echo $cls?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP'])?>"><span><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP'])?></span></a></h2>
										</li>
									<?php	
									}
									if ($grpData['group_showsitemaplink']==1 ) 
									{
										$cls = ($_REQUEST['req']=='sitemap')?'static_menu_selected':'static_menu';
									?>
										<li>
										<h2> <a href="<?php url_link('sitemap.html')?>" class="<?php echo $cls?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SITEMAP'])?>"><span><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SITEMAP'])?></span></a></h2>
										</li>
									<?php		
									}
									if ($grpData['group_showsavedsearchlink']==1)
									{
										$cls = ($_REQUEST['req']=='savedsearch')?'static_menu_selected':'static_menu';
									?>
										<li>
										<h2> <a href="<?php url_link('saved-search.html')?>" class="<?php echo $cls?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH'])?>"><span><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH'])?></span></a></h2>
										</li>
									<?php		
									}
									if ($grpData['group_showhelplink']==1 )
									{
										$cls = ($_REQUEST['req']=='site_help')?'static_menu_selected':'static_menu';
									?>
										<li>
										<h2> <a href="<?php url_link('help.html')?>" class="<?php echo $cls?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HELP'])?>"><span><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HELP'])?></span></a></h2>
										</li>
									<?php
									}
									if ($grpData['group_showfaqlink']==1 )
									{
										$cls = ($_REQUEST['req']=='site_faq')?'static_menu_selected':'static_menu';
									?>
										<li>
										<h2> <a href="<?php url_link('faq.html')?>" class="static_menu" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?>"><span><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?></span></a></h2>
										</li>
									<?php
									}
									}
								}		
								?>
								</ul>
							</div>
						</td>
					</tr>
<?php
					}// End of top
					elseif ($position == 'bottom') // Case if value of position is bottom;
					{
						if(count($grp_array))
						{
						?>
						<div class="bottom_main_links">
								<div class="cat_con_bottom">
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
										ORDER BY 
											static_pages_order";
								$ret_pg = $db->query($sql_pg);
								if($show_top==0)
								{
								if ($grpData['group_showhomelink']==1)
								{
									$cls = ($_REQUEST['req']=='')?'categorybt_selected':'category_bt';
									?>
									<li>
									<h3><a href="<?php url_link('')?>" class="<?php echo $cls?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?></a></h3>
									</li>
									<?php			
								}
									$show_top = 0;
								}	
								
								while ($row_pg = $db->fetch_array($ret_pg))
								{
									$cls = ($row_pg['page_id']==$_REQUEST['page_id'])?'categorybt_selected':'category_bt';
								$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
									?>
									<li>
									<h3><a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="<?php echo $cls?>" title="<?php echo stripslash_normal($row_pg['title'])?>" <?php echo $target?>><?php echo stripslash_normal($row_pg['title']);?></a></h3>
									</li>
									<?php
								}
								}
								?>
								<?php			
								if ($grpData['group_showsitemaplink']==1)
								{	
									$cls = ($_REQUEST['req']=='sitemap')?'categorybt_selected':'category_bt';
									?>
									<li>
									<h3><a href="<?php url_link('sitemap.html')?>" class="<?php echo $cls?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SITEMAP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SITEMAP'])?></a></h3>
									</li>
									<?php			
								}
								if ($grpData['group_showxmlsitemaplink']==1 )
								{	
									$cls = ($_REQUEST['req']=='sitemap')?'categorybt_selected':'category_bt';	
									?>
									<li>
									<h3><a href="<?php url_link('sitemap.xml')?>" class="<?php echo $cls?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP'])?></a></h3>
									</li>
									<?php									
								}
								if ($grpData['group_showfaqlink']==1)
								{
									$cls = ($_REQUEST['req']=='site_faq')?'categorybt_selected':'category_bt';
									?>
									<li>
									<h3><a href="<?php url_link('faq.html')?>" class="<?php echo $cls?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?></a></h3>
									</li>
									<?php			
								}
								if ($grpData['group_showhelplink']==1)
								{
									$cls = ($_REQUEST['req']=='site_help')?'categorybt_selected':'category_bt';
									?>
									<li>
									<h3><a href="<?php url_link('help.html')?>" class="<?php echo $cls?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HELP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HELP'])?></a></h3>
									</li>
									<?php			
								}
								if ($grpData['group_showsavedsearchlink']==1)
								{
									$cls = ($_REQUEST['req']=='savedsearch')?'categorybt_selected':'category_bt';
									?>
									<li>
									<h3><a href="<?php url_link('saved-search.html')?>" class="<?php echo $cls?>" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH'])?></a></h3>
									</li>
									<?php			
								}
								?>
								</ul>
							</div>
							</div>
						<?php	
						}
					}// End of bottom
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
							<td><ul class="bottomnav">
							<?php 
							if($prev_grp != $grpData['group_id'])
							{
							if ($grpData['group_hidename']==0) // Decide whether or not to show the groupname
							{
							?>
							<li class="bottomnavheader"><?php echo stripslashes($title)?></li>
							<?php
							}
							$prev_grp = $grpData['group_id'];
							}	
								if ($grpData['group_showhomelink']==1)
								{
								?>
								<li>
								<h6><a href="<?php url_link('')?>" class="general_links_div_link" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a></h6>
								</li>
							<?php		
								}
								while ($row_pg = $db->fetch_array($ret_pg))
								{
									$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
							  
							  ?>
								<li>
								<h6><a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="general_links_div_link" title="<?php echo stripslashes($row_pg['title'])?>" <?php echo $target?>><?php echo stripslashes($row_pg['title']);?></a></h6>
								</li>
							<?php 
								}
								if ($grpData['group_showsitemaplink']==1)
								{
							?>
								<li>
								<h6><a href="<?php url_link('sitemap.html')?>" class="general_links_div_link" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></a></h6>
								</li>
							<?php		
								}
								if ($grpData['group_showxmlsitemaplink']==1 )
								{	
							?>
								<li>
								<h6><a href="<?php url_link('sitemap.xml')?>" class="general_links_div_link" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></a></h6>
								</li>
							<?php		
								}
								if ($grpData['group_showhelplink']==1)
								{
							?>
								<li>
								<h6><a href="<?php url_link('help.html')?>" class="general_links_div_link" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></a></h6>
								</li>
							<?php		
								}
								if ($grpData['group_showsavedsearchlink']==1)
								{
								?>
									<li>
									<h6><a href="<?php url_link('saved-search.html')?>" class="general_links_div_link" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></h6>
									</li>
								<?php			
								}
							  ?>
							</ul></td>
<?php 
							}
						}
					}
					elseif ($position=='left' or $position =='right') // ############## Left / Right ##############
					{
					}// End of left
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
			global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
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
				case 'top':
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
												ORDER BY 
													$sort_str";
									$ret_cat = $db->query($sql_cat);
									if ($db->num_rows($ret_cat))
									{
										// Check the listing type for categories in category group
										if ($grpData['catgroup_listtype']== 'Menu')// case if categories are to be shown in list menu
										{
										?>
											    <div class="bottom_main_links">
												<div class="cat_con_bottom">
												<ul>
													<?php
													while ($row_cat = $db->fetch_array($ret_cat))
													{
														$cls = ($row_cat['category_id']==$_REQUEST['category_id'])?'categorybt_selected':'category_bt';
														$startpnt++;
													?>
													<li>
													<h3><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="<?php echo $cls?>" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></a></h3>
													</li>
													<?php
													}
													?>
												</ul>
											</div>
											</div>
										<?php		
										}
									}
								}
							}
						
						}// End of bottom
						elseif ($position=='left') // ############## Left ##############
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
												ORDER BY 
													$sort_str";
									$ret_cat = $db->query($sql_cat);
									if ($db->num_rows($ret_cat))
									{
										// Check the listing type for categories in category group
										//if ($grpData['catgroup_listtype']== 'Menu')// case if categories are to be shown in list menu
										{
										?>
										<div class="category_lf_con">

										<?php
										if($prev_grp != $grpData['catgroup_id'])
										{
											if ($grpData['catgroup_hidename']==0) // Decide whether or not to show the groupname
											{
										?>
												<div class="category_header"><?php echo stripslash_normal($title)?></div>
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
										    if($_REQUEST['category_id']==$row_cat['category_id'])
											{
											 $cat_link_class = 'catelinkselected';
											}
											else
											{
											 $cat_link_class = 'catelink';
											}
											// Start:- to check for whether the categories under the group is displayed as a heading with/without a link
											if($row_cat['category_displaytype']=='Normal' && $row_cat['category_islink'])
											{
											?>
												<li>
												<h2><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="<?=$cat_link_class?>" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></a></h2>
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
														if($_REQUEST['category_id']==$row_child['category_id'])
															{
																 $sbcat_link_class = 'subcatelinkselected';
															}
															else
															{
																 $sbcat_link_class = 'subcatelink';
															}
													?>
													<li>
													<h2><a href="<?php url_category($row_child['category_id'],$row_child['category_name'],-1)?>" title="<?php echo stripslash_normal($row_child['category_name'])?>" class="<?=$sbcat_link_class?>"><?php echo ucwords(stripslash_normal($row_child['category_name']));?></a></h2>
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
			global $Captions_arr,$position,$ecom_hostname,$protectedUrl;
			if($position=='top')
			{
			?>
			<tr>
				<td colspan="2" align="left" valign="top" class="maintoptd">
					<div class="search_main">
					<form method="post" name="frm_quicksearch" class="frm_cls" action="<?php url_link('search.html')?>">
					<div class="search_main_inner_left">
					<input name="quick_search" type="text" class="search_input" id="quick_search"  value=""/>
					</div>
					<div class="search_main_inner_right"> 
					<input name="button_submit_search" type="image" src="<?php url_site_image('search-btn.gif')?>" onclick="javascript:document.frm_quicksearch.submit()" />
					</div>
					</form>
					</div>
					<div class="top_cart_main">
					<a href="<?php url_link('delivery-pg49422.html')?>"><img src="<? url_site_image('delivery-icon.gif')?>"  border="0" /> </a>  
					<a href="<?php url_link('contact-us-pg49421.html')?>"><img src="<? url_site_image('contact-icon.gif')?>"  border="0" /> </a>
					<a href="#" onclick="gobackto_cart('<?php echo ($protectedUrl)?base64_encode($ecom_hostname):$ecom_hostname?>')" class="cart_linklf" title="<?php echo stripslash_normal($Captions_arr['COMMON']['VIEW_CART'])?>"><img src="<? url_site_image('basket-icon.gif')?>"  border="0" /> </a>
					<a href="#" class="cart_view_link" onclick="handle_checkout_submit('<?php echo $ecom_hostname?>',0,'<?php echo $pass_tot?>')"  title="<?php echo stripslash_normal($Captions_arr['COMMON']['CHECK_OUT'])?>"><span><img src="<? url_site_image('checkout-icon.gif')?>"  border="0" /></span></a>
					</div>
				
				</td>
			</tr>
<?php
			}
		}
		// ####################################################################################################
		// Function which holds the display logic for customer login
		// ####################################################################################################
		function mod_customerlogin($title)
		{}
				// ####################################################################################################
		// Function which holds the display shoppingcart component
		// ####################################################################################################
		function mod_shoppingcart($title)
		{}
		// ####################################################################################################
		// Function which holds the display logic for call back request 
		// ####################################################################################################
		function mod_callback($title)
		{}
		// ####################################################################################################
		// Function which holds the display logic for best sellers
		// ####################################################################################################
		function mod_bestsellers($ret_main,$title,$display_id)
		{}
		// ####################################################################################################
		// Function which holds the display logic for best sellers
		// ####################################################################################################
		function mod_compare_products($ret_compare_pdts,$title='Compare PrOducts')
		{}
		// ####################################################################################################
		// Function which holds the display logic for combo deals 
		// ####################################################################################################
		function mod_combo($comb_arr,$title)
		{}
		// ####################################################################################################
		// Function which holds the display logic for shelves 
		// ####################################################################################################
		function mod_shelf($shelf_arr,$title)
		{}
		
		// ####################################################################################################
		// Function which holds the display logic for newsletter subscription
		// ####################################################################################################
		function mod_newsletter($title)
		{}
		// ####################################################################################################
		// Function which holds the display logic for Gift Vouchers
		// ####################################################################################################
		function mod_giftvoucher($title)
		{}
		// ####################################################################################################
		// Function which holds the display logic for spending giftvoucher or promotional code
		// ####################################################################################################
		function mod_spendvoucher($title)
		{}
		// ####################################################################################################
		// Function which holds the display logic for Survey
		// ####################################################################################################
		function mod_survey($survey_array,$title)
		{}
		// ####################################################################################################
		// Function which holds the display logic for product shop by brand
		// ####################################################################################################
		function mod_shopbybrandgroup($grp_array,$title)
		{}
		// Function to support the image rotate in shop by brand menu
		function show_shop_rotator($showimg_arr)
		{}
		// ####################################################################################################
		// Function which holds the display logic for Recently Viewed Products
		// ####################################################################################################
		function mod_recentlyviewedproduct($cookval,$title)
		{}
		// ####################################################################################################
		// Function which holds the display logic for adverts
		// ####################################################################################################
		function mod_adverts($advert_arr,$title)
		{
			global $ecom_siteid,$db,$position,$Settings_arr;
		   if ($position=='middleband') // show the advert only if position is left or right
			{
			?>
									
			<?php
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
							case 'IMG':
								$path = url_root_image('adverts/'.$k['advert_source'],1);
								$link = $k['advert_link'];
								?>
							<div class="det_link_pdt">
							<div class="det_link_image">
							<?php
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
							?>
							</div>
							<div class="det_link_name"><a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>"><?php echo stripslashes($k['advert_title'])?></a></div>
							</div>
															
						<?php
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
				}
				?>
				
				<?php
			}
		}
		
		// ####################################################################################################
		// Function which holds the display logic for sitereviews
		// ####################################################################################################
		function mod_sitereviews($title)
		{}
		
		// ####################################################################################################
		// Function which holds the display logic for Recently Viewed Products
		// ####################################################################################################
		function mod_productlist($ret_prod,$title)
		{}
		function mod_statistics($title,$stat_query)
		{}
		function mod_ssl($title)
        {}
		// Function to show the top menu item
		function mod_topmenu($title)
		{}
		
		/* Function to show the currency selector */
		function mod_currencyselector($title)
		{}
		/* Function to show the currency selector */
		function mod_header($header_arr)
		{}
		// ####################################################################################################
		// Function which holds the display logic for best sellers
		// ####################################################################################################
		function mod_preorder($ret_main,$title,$display_id)
		{}
		 function mod_payonaccount($title)   // Function to show the payonaccount banner
		 {}
	};
?>
