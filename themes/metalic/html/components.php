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
			$Captions_arr['STATIC_PAGES'] 	= getCaptions('STATIC_PAGES');
			$add_statcondition 				= " AND a.pname <> 'Home'";
			// section to be used with caching
			switch($position)
			{
				case 'top':
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
			}// Cache checking section
				$cache_exists 	= false;
				$cache_required	= false;
				if ($Settings_arr['enable_caching_in_site']==1)
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
							<div class="static_div">
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
												static_pages_order DESC";
								
								$ret_pg = $db->query($sql_pg);
								$cnt = $db->num_rows($ret_pg);
								
									if ($grpData['group_showhelplink']==1 )
									{
					?>		
										<li>
            								<h1><span class="img_left"></span><span class="img_bg"> <a href="<?php url_link('help.html')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></a></span><span class="img_right"></span></h1>
         							   </li >
					<?php
									}
									if ($grpData['group_showfaqlink']==1 )
									{
					?>		
										<li>
            								<h1><span class="img_left"></span><span class="img_bg"><a href="<?php url_link('faq.html')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_FAQ']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_FAQ']?></a></span><span class="img_right"></span></h1>
         							   </li >
					<?php
									}
									if ($grpData['group_showxmlsitemaplink']==1 )
									{
									?>
										<li>
            								<h1><span class="img_left"></span><span class="img_bg"><a href="<?php url_link('sitemap.xml')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></a></span><span class="img_right"></span></h1>
         							   </li >
									<?php	
									}
									 
									if ($grpData['group_showsitemaplink']==1 )
									{
					?>							
										<li>
            								<h1><span class="img_left"></span><span class="img_bg"> <a href="<?php url_link('sitemap.html')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></a></span><span class="img_right"></span></h1>
         							   </li >
					<?php		
									}
									if ($grpData['group_showsavedsearchlink']==1)
									{
					?>							
										<li>
            								<h1><span class="img_left"></span><span class="img_bg"> <a href="<?php url_link('saved-search.html')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></span><span class="img_right"></span></h1>
         							   </li >
					<?php		
									}
								while ($row_pg = $db->fetch_array($ret_pg))
								{
									$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
								?>
									   <li>
            								<h1><span class="img_left"></span><span class="img_bg"><a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="static" title="<?php echo stripslashes($row_pg['title'])?>" <?php echo $target?>><?php echo stripslashes($row_pg['title']);?></a></span><span class="img_right"></span></h1>
         							   </li >
								<?php
								}
								
							}
							//if($show_bottom==0)
							{
								if ($grpData['group_showhomelink']==1)
								{
								
						?>
									 <li>
            								<h1><span class="img_left"></span><span class="img_bg"> <a href="<?php url_link('')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a></span><span class="img_right"></span></h1>
         							 </li >
						<?php
								}
								//$show_bottom=1;	
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
							 <div class="bottom_div">
								 <ul class="link_bottomA">
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
										<li><h1><a href="<?php url_link('')?>" class="bottomlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a></h1></li>
						<?php			
									}
										$show_top = 0;
								}	
								
								while ($row_pg = $db->fetch_array($ret_pg))
								{
									$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
								?>
										<li><h1><a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="bottomlink" title="<?php echo stripslashes($row_pg['title'])?>" <?php echo $target?>><?php echo stripslashes($row_pg['title']);?></a></h1></li>
								<?php
								}
							}
						?>
<!--									<li><h1><a href="<?php //url_link('saved-search.html')?>" class="bottomlink" title="<?php //echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php //echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></h1></li>
-->						<?php			
								//if($show_bottom==0)
								{
									if ($grpData['group_showsitemaplink']==1)
									{
						?>
										<li><h1><a href="<?php url_link('sitemap.html')?>" class="bottomlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></a></h1></li>
						<?php			
									}
									if ($grpData['group_showxmlsitemaplink']==1 )
									{			
									?>
										<li><h1><a href="<?php url_link('sitemap.xml')?>" class="bottomlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></a></h1></li>
									<?php									
									}
									if ($grpData['group_showfaqlink']==1)
									{
						?>
										<li><h1><a href="<?php url_link('faq.html')?>" class="bottomlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_FAQ']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_FAQ']?></a></h1></li>
						<?php			
									}
									if ($grpData['group_showhelplink']==1)
									{
						?>
										<li><h1><a href="<?php url_link('help.html')?>" class="bottomlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></a></h1></li>
						<?php			
									}
									if ($grpData['group_showsavedsearchlink']==1)
									{
						?>
										<li><h1><a href="<?php url_link('saved-search.html')?>" class="bottomlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></h1></li>
						<?php			
									}
									//$show_bottom = 1;
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
									<?php
										
										
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
				case 'right_col1':
					$cache_type		= 'comp_leftcatgroup';	
				break;
				case 'right_col2':
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
						if ($position == 'top') // Case if value of position is top;
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
												<td colspan="3" align="right" valign="top" class="categorytoptd">
													<ul class="categorytop">
										<?php
											while ($row_cat = $db->fetch_array($ret_cat))
											{
										?>
												<li><h1><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="categorytoplink" title="<?php echo stripslashes($row_cat['category_name'])?>"><?php echo stripslashes($row_cat['category_name']);?></a></h1></li>
										<?
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
											<div class="bottom_div">
												<ul class="link_bottom">  
										<?php
											while ($row_cat = $db->fetch_array($ret_cat))
											{
										?>
														<li><h1><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="categorybottom" title="<?php echo stripslashes($row_cat['category_name'])?>"><?php echo stripslashes($row_cat['category_name']);?></a></h1></li>
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
						elseif ($position=='right_col1') // ############## Left ##############
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
										?>
											<table  border="0" cellspacing="0" cellpadding="0" width="100%" class="cat_table">
										<?php
										if ($grpData['catgroup_listtype']== 'Menu')// case if categories are to be shown in list menu
										{
										 if($prev_grp != $grpData['catgroup_id'])
												{
													if ($grpData['catgroup_hidename']==0) // Decide whether or not to show the groupname
													{
											?>
														 <tr>
               											 <td class="cat_table_top"><?php echo stripslashes($title)?></td>
														 </tr>
											<?php
													}
														$prev_grp = $grpData['catgroup_id'];
												}
												?>
											<tr>
               							 <td class="cat_table_links"> <ul class="categorey">
										 <?
											while ($row_cat = $db->fetch_array($ret_cat))
											{
											?>	
								    
											<?php	
												// Start:- to check for whether the categories under the group is displayed as a heading with/without a link
											if($row_cat['category_displaytype']=='Normal' && $row_cat['category_islink'])
											{
											?>
										 <li>
                      						<h1><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="catelink" title="<?php echo stripslashes($row_cat['category_name'])?>"><?php echo stripslashes($row_cat['category_name']);?></a></h1></li>
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
											}
											// End:- to check for whether the categories under the gorup is displayed as a heading with/without a link
												if ($row_cat['category_subcatlisttype']=='List' or $row_cat['category_subcatlisttype']=='Both')
												{
													if (($row_cat['category_id']==$_REQUEST['category_id']) or ($row_cat['category_id']==$parent))
													{	
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
														?>
															<li>
															<ul class="subcategory">
														<?php	
															while ($row_child = $db->fetch_array($ret_child))
															{
														?>
																	<li><h1><a href="<?php url_category($row_child['category_id'],$row_child['category_name'],-1)?>" title="<?php echo stripslashes($row_child['category_name'])?>" class="catelink"><?php echo stripslashes($row_child['category_name']);?></a></h1></li>
														<?php		
															}
														?>
															</ul>
															</li>
														<?php	
														}
													}
												}
												?>
												
												<?	
											}
										?>
										</ul>
										</td>
										</tr>
										
										<?php		
										}
										else
										{
											if ($grpData['catgroup_hidename']==0) // Decide whether or not to show the groupname
											{
												echo ' <tr>
               											 <td class="cat_table_top">'.$title.'</td></tr>';
											}
										?>
											<tr>
               								<td class="cat_table_dropdown">
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
											</td></tr>
										<?php	
										}
										?></table>
										<?php
										
									}	
								}
							}
						}
						elseif ($position=='right_col2') // ############## Right ##############
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
										if ($grpData['catgroup_listtype']== 'Menu')// case if categories are to be shown in list menu
										{
										?>
											<ul class="categoryright">
										<?php
											while ($row_cat = $db->fetch_array($ret_cat))
											{
												if($prev_grp != $grpData['catgroup_id'])
												{
													if ($grpData['catgroup_hidename']==0) // Decide whether or not to show the groupname
													{
										?>
														<li class="categoryheaderright"><?php echo stripslashes($title)?></li>
										<?php
													}
														$prev_grp = $grpData['catgroup_id'];
												}
											// Start:- to check for whether the categories under the gorup is displayed as a heading with/without a link
											if($row_cat['category_displaytype']=='Normal' && $row_cat['category_islink'])
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
											}
									// End :-to check for whether the categories under the gorup is displayed as a heading with/without a link
												if ($row_cat['category_subcatlisttype']=='List' or $row_cat['category_subcatlisttype']=='Both')
												{
													if (($row_cat['category_id']==$_REQUEST['category_id']) or ($row_cat['category_id']==$parent))
													{
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
														?>
															<li>
															<ul class="subcategoryright">
														<?php	
															while ($row_child = $db->fetch_array($ret_child))
															{
														?>
																<li><h1><a href="<?php url_category($row_child['category_id'],$row_child['category_name'],-1)?>" title="<?php echo stripslashes($row_child['category_name'])?>" class="catelinkright"><?php echo stripslashes($row_child['category_name']);?></a></h1></li>
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
										}
										else
										{
											if ($grpData['catgroup_hidename']==0) // Decide whether or not to show the groupname
											{
												echo '<ul class="category"><li class="categoryheaderright">'.$title.'</li>';
											}
										?>
											<li style="list-style:none;">
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
						}// End of right
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
			$checkout_link = get_Checkoutlink(1);
			if($_REQUEST['req']=='cart') // if current page is related to cart then call cartcalc function to refresh the cart totals and number of items in cart
			{
				$cartData = cartCalc(); // calling cart calc 
			}	
			
			if ($position=='right_col2' || $position=='right_col1') // show only if position value is top
			{
		?>
				<div class="search_div" align="right">
                 <ul class="search_area">
					<form method="post" name="frm_quicksearch" class="frm_cls" action="<?php url_link('search.html')?>">
					  <li>
						<?php /*?><div align="right"><?php echo $Captions_arr['COMMON']['SEARCH']?></div><?php */?>
						  <div align="right"><input name="quick_search" type="text" class="topsarchtextbox" id="quick_search"  value=""/></div>
						  <div align="left"><input name="button_submit_search" type="submit" class="topsarchbutton" id="button3" value="<?php echo $Captions_arr['COMMON']['SEARCH_GO']?>" onclick="show_wait_button(this,'Please wait...')" /></div>
					  </li>
					  <li><a href="<?php url_link('advancedsearch.html')?>" class="advancedsearch" title="<?php echo $Captions_arr['COMMON']['ADVANCED_SEARCH']?>"><?php echo $Captions_arr['COMMON']['ADVANCED_SEARCH']?></a></li>
					  <input type="hidden" name="search_submit" value="search_submit" />
					</form>
					
				</ul></div>
				
		<?php	
			}	
		}
		// ####################################################################################################
		// Function which holds the display logic for customer login
		// ####################################################################################################
		function mod_customerlogin($title)
		{
			global $Captions_arr,$ecom_hostname,$inlineSiteComponents,$Settings_arr,$db,$ecom_siteid;
			$Captions_arr['CUST_LOGIN'] = getCaptions('CUST_LOGIN');
			$cust_id 					= get_session_var("ecom_login_customer");
			if (!$cust_id) // case customer is not logged in
			{
			if($Settings_arr['showcustomerlogin_as_banner']==1) // Decide whether customer login is to be displayed as banner or in normal style
			{
			?>
				<div class="left_top_login">
					 <div class="signup_btn"><a href="<?php url_link('registration.html')?>" title="<?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['NEW_USER'])?>"><img src="<?php url_site_image('signup-btn.gif')?>" /></a></div>
					 <div class="login_btn"><a href="<?php url_link('custlogin.html')?>"><img src="<?php url_site_image('login-btn.gif')?>" /></a></div>
				   </div>
			<?php
			}
			else
			{
				$hide_newuser 		=  $Settings_arr['hide_newuser'];
				$hide_forgotpass 	=  $Settings_arr['hide_forgotpass'];
		?>
			<form name="frm_custlogin" id="frm_custlogin" method="post" action="" onsubmit="return validate_login(this)" class="frm_cls">
			<div class="login_div" align="right">
              <ul class="login_area">
		<?php   
		
				if ($title)
				{
		?>
					<li><strong><?php echo $title?></strong></li>
		<?php
				}
		?>		
				<li><?php echo $Captions_arr['CUST_LOGIN']['EMAIL']?></li>
			    <li><input name="custlogin_uname" type="text" class="login_textbox" id="custlogin_uname" size="15" /></li>
				<li><?php echo $Captions_arr['CUST_LOGIN']['PASSWORD']?></li>
				<li><input name="custlogin_pass" type="password" class="login_textbox" id="custlogin_pass" size="15" /></li>
				<li> <div align="left" class="loginA">
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
					</div>
					<div align="left" class="loginB"> <input name="custologin_Submit" type="submit" class="login_button" id="custologin_Submit" value="<?php echo $Captions_arr['CUST_LOGIN']['LOGIN']?>" />
					</div>
				 </li>
				</ul>
            </div>
				<input type="hidden" name="redirect_back" value="0" /> 
				</form>
		<?php	
		       }
		 }
			?><?php /*?>else // case of customer is logged in 
			{ 
						$Captions_arr['LOGIN_MENU'] 	= getCaptions('LOGIN_MENU');

			?>
				<ul class="userloginmenu">  
					<li><h1><a href="<?php url_link('login_home.html')?>" class="userloginmenulink"><?=$Captions_arr['LOGIN_MENU']['MY_HOME']?></a></h1></li>
					<li><h1><a href="<?php url_link('myprofile.html')?>" class="userloginmenulink"><?=$Captions_arr['LOGIN_MENU']['MY_PROFILE']?></a></h1></li>
					<li><h1><a href="<?php url_link('myenquiries.html')?>" class="userloginmenulink"><?=$Captions_arr['LOGIN_MENU']['MY_ENQUIRIES']?></a></h1></li>
					<li><h1><a href="<?php url_link('wishlist.html')?>" class="userloginmenulink"><?=$Captions_arr['LOGIN_MENU']['MY_WISHLIST']?></a></h1></li>
				<?php	
					$myfav_module = 'mod_myfavorites';
					if(in_array($myfav_module,$inlineSiteComponents)){
				?>
					<li><h1><a href="<?php url_link('myfavorites.html')?>" class="userloginmenulink"><?=$Captions_arr['LOGIN_MENU']['MY_FAVOURITE']?></a></h1></li>
				<? }	
					$myaddr_module = 'mod_myaddressbook';
					if(in_array($myaddr_module,$inlineSiteComponents)){
				?>
					<li><h1><a href="<?php url_link('myaddressbook.html')?>" class="userloginmenulink"><?=$Captions_arr['LOGIN_MENU']['MY_ADDRESSBOOK']?></a></h1></li>
				<? }?>
					<li><h1><a href="<?php url_link('myorders.html')?>" class="userloginmenulink"><?=$Captions_arr['LOGIN_MENU']['MY_ORDERS']?></a></h1></li>
				<?php
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
						<li><h1><a href="<?php url_link('mydownloads.html')?>" class="userloginmenulink"><?=$Captions_arr['LOGIN_MENU']['MY_DOWNLOADS']?></a></h1></li>
				<?php
					}
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
						<li><h1><a href="<?php url_link('mypayonaccountpayment.html')?>" class="userloginmenulink"><?=$Captions_arr['LOGIN_MENU']['MY_PAYONACCDETAILS']?></a></h1></li>
				<?php
					}
				?>	
					<li><h1><a href="<?php url_link('logout.html')?>" class="userloginmenulink"><?=$Captions_arr['LOGIN_MENU']['LOGOUT']?></a></h1></li>
				</ul> 
			<?	
			}<?php */?>	
			<?
		}
		
		// ####################################################################################################
		// Function which holds the display logic for call back request 
		// ####################################################################################################
		function mod_callback($title)
		{
		?>
			<div class="callbackcon" align="left"><a href="<?php url_link('callback.html')?>"><img src="<?php url_site_image('callback.gif')?>" alt="Call Back" title="Call back request" border="0" /></a></div>
		<?php		
		}
		// ####################################################################################################
		// Function which holds the display logic for best sellers
		// ####################################################################################################
		function mod_bestsellers($ret_main,$title,$display_id)
		{ 
			global $ecom_siteid,$db,$ecom_hostname,$position,$Captions_arr,$Settings_arr;
			if ($position=='right_col1' or $position=='right_col2') // Best sellers is allowed in left or right panels
			{	
				if ($db->num_rows($ret_main))
				{
					/*switch($position)
					{
						case 'right_col1':
							$cache_type		= 'comp_leftbestseller';	
						break;
						case 'right_col2':
							$cache_type		= 'comp_rightbestseller';	
						break;
					}
					// Cache checking section	
					$cache_exists 	= false;
					$cache_required	= false;
					if ($Settings_arr['enable_caching_in_site']==1)
					{
						$cache_required = true;
						if (exists_Cache($cache_type,$ecom_siteid))
						{
							$content_cache = getcontent_Cache($cache_type,$ecom_siteid);
							if ($content_cache) // if cache exists show it
							{
								echo $content_cache;
								$cache_exists = true;
							}
						}
					}*/
					// Do the following only if caching is not enabled or cache does not exists
					/*if ($cache_exists==false)
					{*/
						/*if($cache_required)// if caching is required start recording the output
						{
							ob_start();
						}	*/
						if ($position=='right_col1') // display login for left hand side
						{
				?>
				<table border="0" cellspacing="0" cellpadding="0" width="100%" class="shelf_table">
				<?php 
			  	    $comp_uniqid = uniqid('');
			  		if($title) // check whether title exists
							{
				?>
								<tr>
               						 <td align="left" valign="middle" class="shelf_header"><a href="javascript:handle_expland_comp(document.getElementById('head_<?php echo $comp_uniqid?>'),'<?php echo $comp_uniqid?>')" class="shelf_default" id="head_<?php echo $comp_uniqid?>"><?php echo $title?></a></td>
								</tr>
				<?php
							}
				?>			
					        <tr id="<?php echo $comp_uniqid?>" style="display:none; width:100%;" >
               				 <td align="left" valign="middle" class="shelf_home_td" style="width:100%">
				<?php	
							while ($row_main = $db->fetch_array($ret_main))
							{
				?>	
								<div class="shelf_home" style="width:100%">
								  <div class="shelf_img">
								  <a href="<?php url_product($row_main['product_id'],$row_main['product_name'],-1)?>"  title="<?php echo stripslashes($row_main['product_name'])?>">
								  <?php
								  $pass_type='image_iconpath';
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
																		?></a></div>
									<div class="shelf_content">
									  <h1><a href="<?php url_product($row_main['product_id'],$row_main['product_name'],-1)?>" class="bestsellerslink" title="<?php echo stripslashes($row_main['product_name'])?>"><?php echo stripslashes($row_main['product_name'])?></a></h1>
									  <h2><?php echo print_price($row_main['product_webprice'],false)?></h2>
									  <h3><?php show_moreinfo($row_main,'infolink')?></h3>
									</div>
									</div>
		
				<?php
						
							}		
				?>
								 <div class="shelf_showall" align="right"> <a href="<? url_link('bestsellers'.$display_id.'.html')?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>" <?php /*onclick="document.bestseller_component_left<?=$display_id?>.submit();"*/?>><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a> </div>
							</td></tr>
							</table>
							<?php /*<form name="bestseller_component_left<?=$display_id?>" action="<? url_link('bestsellers.html')?>" method="post"><input type="hidden" name="disp_id" value="<?php echo $display_id?>"/></form>*/?>
				<?php	
						}
						elseif($position == 'right_col2') // display logic for right hand side
						{
						
				?>
				<table border="0" cellspacing="0" cellpadding="0" width="100%" class="shelf_right_table">
				<?php 
			  	    $comp_uniqid = uniqid('');
			  		if($title) // check whether title exists
							{
				?>
								<tr>
               						 <td align="left" valign="middle" class="shelf_header_right"><a href="javascript:handle_expland_comp(document.getElementById('head_<?php echo $comp_uniqid?>'),'<?php echo $comp_uniqid?>')" class="shelf_default" id="head_<?php echo $comp_uniqid?>"><?php echo $title?></a></td>
								</tr>
				<?php
							}
				?>			
					        <tr id="<?php echo $comp_uniqid?>" style="display:none; width:100%;" >
               				 <td align="left" valign="middle" class="shelf_home_right" style="width:100%">
				<?php	
							while ($row_main = $db->fetch_array($ret_main))
							{
				?>	
								<div class="shelf_home" style="width:100%">
								  <div class="shelf_img"><a href="<?php url_product($row_main['product_id'],$row_main['product_name'],-1)?>"  title="<?php echo stripslashes($row_main['product_name'])?>"><?php
								  $pass_type='image_iconpath';
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
																		?></a></div>
									<div class="shelf_content">
									  <h1><a href="<?php url_product($row_main['product_id'],$row_main['product_name'],-1)?>" class="bestsellerslink" title="<?php echo stripslashes($row_main['product_name'])?>"><?php echo stripslashes($row_main['product_name'])?></a></h1>
									  <h2><?php echo print_price($row_main['product_webprice'],false)?></h2>
									  <h3><?php show_moreinfo($row_main,'infolink')?></h3>
									</div>
									</div>
		
				<?php
						
							}		
				?>
								 <div class="shelf_showall" align="right"> <a href="<? url_link('bestsellers'.$display_id.'.html')?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>" <?php /*onclick="document.bestseller_component_left<?=$display_id?>.submit();"*/?>><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a> </div>
							</td></tr>
							</table>
							<?php /*<form name="bestseller_component_left<?=$display_id?>" action="<? url_link('bestsellers.html')?>" method="post"><input type="hidden" name="disp_id" value="<?php echo $display_id?>"/></form>*/?>
				<?php	
						
						}
						/*if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
						{
							$content = ob_get_contents();
							ob_end_clean();
							save_Cache($cache_type,$ecom_siteid,$content);
							echo $content;
						}
					}	*/
				}	
			}	
		}
		// ####################################################################################################
		// Function which holds the display logic for best sellers
		// ####################################################################################################
		function mod_compare_products($ret_compare_pdts,$title='Compare PrOducts')
		{
			global $ecom_siteid,$db,$ecom_hostname,$position,$Captions_arr,$Settings_arr;
			if ($position=='right_col1' or $position=='right_col2') // Best sellers is allowed in left or right panels
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
						<ul class="compare">   
				<?php
							if($title) // check whether title exists
							{
				?>
								<li class="compareheader"><?php echo $title?></li>
				<?php
							}
							while ($row_compare_pdts = $db->fetch_array($ret_compare_pdts))
							{
				?>	
								<li><h1><img src="<?php url_site_image('delete.gif')?>" onclick="document.common_compare_list.remove_compareid.value=<?=$row_compare_pdts['product_id']?>; if(confirm('Are You sure You want to remove the product from the compare list')){ document.common_compare_list.submit()};" alt="Remove" title="Remove" />&nbsp;<a href="<?php url_product($row_compare_pdts['product_id'],$row_compare_pdts['product_name'],-1)?>" class="comparelink" title="<?php echo stripslashes($row_compare_pdts['product_name'])?>"><?php echo stripslashes($row_compare_pdts['product_name'])?></a></h1></li>
		
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
			if ($position=='right_col1' or $position =='right_col2')// Combo deal is allowed only in left or right panel
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
								case 'right_col1':
									$cache_type		= 'comp_leftcombo';	
								break;
								case 'right_col2':
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
								$sql_prod = "SELECT a.product_id,a.product_name,a.product_webprice,a.product_default_category_id 
											FROM 
												products a,combo_products b 
											WHERE 
												b.combo_combo_id = ".$combData['combo_id']." 
												AND a.product_id = b.products_product_id 
												AND a.product_hide='N' 
											ORDER BY 
												$combosort_by $combosort_order";
								$ret_prod = $db->query($sql_prod);
								 $comp_uniqid = uniqid('');
								if ($db->num_rows($ret_prod))
								{
									if($position =='right_col1')
									{
									?>  
									
									<table border="0" cellspacing="0" cellpadding="0" width="100%" class="shelf_table">
									<?php
									if ($title and $combData['combo_hidename']==0)
									{
									?>			
									  <tr>
											 <td align="left" valign="middle" class="shelf_header"><a href="javascript:handle_expland_comp(document.getElementById('head_<?php echo $comp_uniqid?>'),'<?php echo $comp_uniqid?>')" class="shelf_default" id="head_<?php echo $comp_uniqid?>"><?php echo $title?></a></td>  
									  </tr>
									<?php
									}
									?>
									<tr id="<?php echo $comp_uniqid?>" style="display:none; width:100%;" >
										 <td align="left" valign="middle" class="shelf_home_td" style="width:100%">				
												<?
													while ($row_prod = $db->fetch_array($ret_prod))
													{
														?>
																<div class="shelf_home" style="width:100%">
																<div class="shelf_img"><a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" class="combodealslink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php
																  $pass_type='image_iconpath';
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
																										?></a></div>
																	<div class="shelf_content">
																	  <h1><a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" class="bestsellerslink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
																	  <h2><?php echo print_price($row_prod['product_webprice'],false)?></h2>
																	  <h3><a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" class="bestsellerslink" title="<?php echo stripslashes($row_prod['product_name'])?>"><? echo $Captions_arr['COMMON']['MORE_INFO']?></a></h3>
																	</div>
																			
																	</div>
														<?php      
																		}
														?>	
														 <div class="shelf_showall" align="right"> <a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_DET']?>"><?php echo $Captions_arr['COMMON']['SHOW_DET']?></a> </div>
													</td>
											  </tr>
									</table>
									
						<?php	
									}
									elseif($position =='right_col2')
									{
									
									?>  
									
									<table border="0" cellspacing="0" cellpadding="0" width="100%" class="shelf_right_table">
									<?php
									if ($title and $combData['combo_hidename']==0)
									{
									?>			
									  <tr>
											 <td align="left" valign="middle" class="shelf_header_right"><a href="javascript:handle_expland_comp(document.getElementById('head_<?php echo $comp_uniqid?>'),'<?php echo $comp_uniqid?>')" class="shelf_default" id="head_<?php echo $comp_uniqid?>"><?php echo $title?></a></td>  
									  </tr>
									<?php
									}
									?>
									<tr id="<?php echo $comp_uniqid?>" style="display:none; width:100%;" >
										 <td align="left" valign="middle" class="shelf_home_right" style="width:100%">				
												<?
													while ($row_prod = $db->fetch_array($ret_prod))
													{
														?>
																<div class="shelf_home" style="width:100%">
																<div class="shelf_img"><a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" class="combodealslink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php
																  $pass_type='image_iconpath';
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
																										?></a></div>
																	<div class="shelf_content">
																	  <h1><a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" class="bestsellerslink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
																	  <h2><?php echo print_price($row_prod['product_webprice'],false)?></h2>
																	  <h3><a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" class="bestsellerslink" title="<?php echo stripslashes($row_prod['product_name'])?>"><? echo $Captions_arr['COMMON']['MORE_INFO']?></a></h3>
																	</div>
																			
																	</div>
														<?php      
																		}
														?>	
														 <div class="shelf_showall" align="right"> <a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_DET']?>"><?php echo $Captions_arr['COMMON']['SHOW_DET']?></a> </div>
													</td>
											  </tr>
									</table>
									
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
			if($position =='right_col1' or $position == 'right_col2')
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
							if($shelfData['shelf_currentstyle']=='nor') // case of normal design layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case '1row': // case of one in a row
									case '3row': // case of one in a row
									if($position=='right_col1')
									{
									?>
										<table border="0" cellspacing="0" cellpadding="0" width="100%" class="shelf_table">
											<?php
											 $comp_uniqid = uniqid('');
											if ($title)
											{
											?>	
												
								                <tr>
               						               <td align="left" valign="middle" class="shelf_header"><a href="javascript:handle_expland_comp(document.getElementById('head_<?php echo $comp_uniqid?>'),'<?php echo $comp_uniqid?>')" class="shelf_default" id="head_<?php echo $comp_uniqid?>"><?php echo $title?></a></td>
												</tr>
											<?php
											}
											?>
												  <tr id="<?php echo $comp_uniqid?>" style="display:none; width:100%;" >
               				                      <td align="left" valign="middle" class="shelf_home_td" style="width:100%">
												<?
											while($row_prod = $db->fetch_array($ret_prod))
											{
											?>
												<div class="shelf_home" style="width:100%">
												<?
												if($shelfData['shelf_showimage']==1) // whether image is to be displayed
												{
												?>
												<div class="shelf_img"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"  title="<?php echo stripslashes($row_prod['product_name'])?>"><?php
												$pass_type='image_iconpath';
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
																					?></a></div>
												<? }?>									
												<div class="shelf_content">
												  <?
															if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
															{
															?><h1><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="bestsellerslink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
														<?  }
														   if($shelfData['shelf_showprice']==1) // whether price is to be displayed
															{
															?><h2><?php echo print_price($row_prod['product_webprice'],false)?></h2>
															<?
															}
															?>
														  <h3><?php show_moreinfo($row_prod,'infolink')?></h3>
														</div>
													</div>
												<?
												}
											?>				  
												<div class="shelf_showall" align="right"> <a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a></div>
												</td>
												</tr>
											</table>
											
									<?php	
									}
									elseif($position=='right_col2')
									{
									?>
									        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="shelf_right_table">
											<?php
											 $comp_uniqid = uniqid('');
											if ($title)
											{
											?>	
												
								                <tr>
               						               <td align="left" valign="middle" class="shelf_header_right"><a href="javascript:handle_expland_comp(document.getElementById('head_<?php echo $comp_uniqid?>'),'<?php echo $comp_uniqid?>')" class="shelf_default" id="head_<?php echo $comp_uniqid?>"><?php echo $title?></a></td>
												</tr>
											<?php
											}
											?>
												  <tr id="<?php echo $comp_uniqid?>" style="display:none; width:100%;" >
               				                      <td align="left" valign="middle" class="shelf_home_right" style="width:100%">
												<?
											while($row_prod = $db->fetch_array($ret_prod))
											{
											?>
												
												<div class="shelf_home" style="width:100%">
												<?
												if($shelfData['shelf_showimage']==1) // whether image is to be displayed
												{
												?>
												<div class="shelf_img"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"  title="<?php echo stripslashes($row_prod['product_name'])?>"><?php
												$pass_type='image_iconpath';
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
																					?></a></div>
												<? }?>									
												<div class="shelf_content">
												  <?
															if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
															{
															?><h1><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="bestsellerslink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1>
														<?  }
														   if($shelfData['shelf_showprice']==1) // whether price is to be displayed
															{
															?><h2><?php echo print_price($row_prod['product_webprice'],false)?></h2>
															<?
															}
															?>
														  <h3><?php show_moreinfo($row_prod,'infolink')?></h3>
														</div>
													</div>
												<?
												}
											?>				  
												<div class="shelf_showall" align="right"> <a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a></div>
												</td>
												</tr>
											</table>
									<?
									}
									break;
									case 'list': // case of show product as list
									?>
											<ul class="compshelflist">
												<?php
												if($title)
												{
												?>					
													<li class="compshelflistheader"><?php echo stripslashes($title)?></li>
												<?php
												}
											
											while($row_prod = $db->fetch_array($ret_prod))
											{
											?>
												<li><h1><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="shelflistlink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name']);?></a></h1></li>
											<?php
											}
											?>
												<li><h1 align="right"><a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a></h1></li>
											</ul>
									<?php	
									break;
									case 'dropdown': // case of show shelf as drop down box
										$uniq = uniqid('');
										?>
											<table border="0" cellpadding="0" cellspacing="0" class="shopbybrandtable">
											<?php
											if($title)
											{
											?>	
												<tr>
												<td class="shopbybrandheader">
												<?php echo $title?></td>
												</tr>
											<?php
											}
											?> 
											<tr>
												<td class="shopbybrandheader">
												<label>
													<select name="cbo_shelf_<?php echo $uniq."_".$shelfData['shelf_id']?>" id="cbo_shelf_<?php echo $uniq."_".$shelfData['shelf_id']?>" onchange="handle_dropdownval_sel(this.value)">
													<option value="">-- <?php echo $Captions_arr['COMMON']['SELECT']?> --</option>
													<?php
													while($row_prod = $db->fetch_array($ret_prod))
													{
													?>
														<option value="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1)?>" <?php echo ($row_prod['product_id']==$_REQUEST['product_id'])?'selected':''?>><?php echo stripslashes($row_prod['product_name'])?></option>
													<?php
													}
													?>	
														<option value="<?php echo url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)?>">-- <?php echo $Captions_arr['COMMON']['SHOW_ALL']?> --</option>
													</select>
												</label>
												</td>
											</tr>
											</table>
									<?php		
									break;
								};	
							}	
							elseif($shelfData['shelf_currentstyle']=='christ') // case of christmas layout
							{
								switch($shelfData['shelf_displaytype'])
								{
								case '1row': // case of one in a row
								case '3row':
									?>
										<table border="0" cellpadding="0" cellspacing="0" class="christmas_comp_offertable">
										<?php
										if ($title)
										{
										?>	
											<tr>
												<td class="christmas_comp_offertableheader"><?php echo $title?></td>
											</tr>
										<?php
										}
										while($row_prod = $db->fetch_array($ret_prod))
										{
										if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
										{
										?>				  
											<tr>
												<td class="christmas_comp_offertableproductname"><h1><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="christmas_comp_offertableprolink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></td>
											</tr>
										<?php
										}
										if($shelfData['shelf_showimage']==1) // whether image is to be displayed
										{
										?>
											<tr>
												<td>
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
													<?php
														// Calling the function to get the type of image to shown for current 
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
													?>
													</a>
												</td>
											</tr>
										<?php
										}
										if($shelfData['shelf_showprice']==1) // whether price is to be displayed
										{
										?>
											<tr>
												<td>
													<?php
													$price_class_arr['ul_class'] 		= 'christmas_comp_offerul';
													$price_class_arr['normal_class'] 	= 'christmas_comp_offernormalprice';
													$price_class_arr['strike_class'] 	= 'christmas_comp_offerstrikeprice';
													$price_class_arr['yousave_class'] 	= 'christmas_comp_offeryousaveprice';
													$price_class_arr['discount_class'] 	= 'christmas_comp_offerdiscountprice';
													echo show_Price($row_prod,$price_class_arr,'compshelf');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
													?>	
												</td>
											</tr>
										<?php
										}
										}
										?>				  
											<tr>
												<td align="right" class="christmas_comp_offertableproductbottom"><a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="christmas_comp_offertableviewall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a></td>
											</tr>
										</table>
									<?php	
								break;
								case 'list': // case of show product as list
								?>
										<ul class="compshelflist">
											<?php
											if($title)
											{
											?>					
												<li class="compshelflistheader"><?php echo stripslashes($title)?></li>
											<?php
											}
										
											while($row_prod = $db->fetch_array($ret_prod))
											{
											?>
												<li><h1><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="shelflistlink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name']);?></a></h1></li>
											<?php
											}
										?>
												<li><h1 align="right"><a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a></h1></li>
										</ul>
								<?php	
								break;
								case 'dropdown': // case of show shelf as drop down box
									$uniq = uniqid('');
									?>
										<table border="0" cellpadding="0" cellspacing="0" class="shopbybrandtable">
									<?php
									if($title)
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
											<select name="cbo_shelf_<?php echo $uniq."_".$shelfData['shelf_id']?>" id="cbo_shelf_<?php echo $uniq."_".$shelfData['shelf_id']?>" onchange="handle_dropdownval_sel(this.value)">
												<option value="">-- <?php echo $Captions_arr['COMMON']['SELECT']?> --</option>
												<?php
												while($row_prod = $db->fetch_array($ret_prod))
												{
												?>
												<option value="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1)?>" <?php echo ($row_prod['product_id']==$_REQUEST['product_id'])?'selected':''?>><?php echo stripslashes($row_prod['product_name'])?></option>
												<?php
												}
												?>	
												<option value="<?php echo url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)?>">-- <?php echo $Captions_arr['COMMON']['SHOW_ALL']?> --</option>
											</select>
										</label>
										</td>
									</tr>
									</table>
								<?php	
								break;
								};	
							}
							elseif($shelfData['shelf_currentstyle']=='new') // case of new year layout
							{
								switch($shelfData['shelf_displaytype'])
								{
								case '1row': // case of one in a row
								case '3row':
								?>
										<table border="0" cellpadding="0" cellspacing="0" class="newyear_left_tableB">
									<?php
									if ($title)
									{
									?>	
										<tr>
											<td class="newyear_left_tableheaderB"><?php echo $title?></td>
										</tr>
									<?php
									}
									while($row_prod = $db->fetch_array($ret_prod))
									{
										if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
										{
										?>				  
											<tr>
												<td class="newyear_left_tableproductnameB"><h1><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="newyear_left_tableprolinkB" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></td>
											</tr>
										<?php
										}
										if($shelfData['shelf_showimage']==1) // whether image is to be displayed
										{
										?>
											<tr>
												<td>
													<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
													<?php
														// Calling the function to get the type of image to shown for current 
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
													?>
												</a>
											</td>
											</tr>
										<?php
										}
										if($shelfData['shelf_showprice']==1) // whether price is to be displayed
										{
										?>
											<tr>
												<td>
													<?php
													$price_class_arr['ul_class'] 		= 'newyear_left_ulB';
													$price_class_arr['normal_class'] 	= 'newyear_left_normalpriceB';
													$price_class_arr['strike_class'] 	= 'newyear_left_strikepriceB';
													$price_class_arr['yousave_class'] 	= 'newyear_left_yousaveprice';
													$price_class_arr['discount_class'] 	= 'newyear_left_discountprice';
													echo show_Price($row_prod,$price_class_arr,'compshelf');
													show_excluding_vat_msg($row_prod,'vat_div');// show excluding VAT msg
													show_bonus_points_msg($row_prod,'bonus_point'); // Show bonus points
													?>	
												</td>
											</tr>
										<?php
										}
									}
									?>				  
										<tr>
											<td align="right" class="newyear_left_tableproductbottomB"><a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="newyear_left_tableviewallnewprodB" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a></td>
										</tr>
									</table>
								<?php	
								break;
								case 'list': // case of show product as list
								?>
										<ul class="compshelflist">
										<?php
										if($title)
										{
										?>					
											<li class="compshelflistheader"><?php echo stripslashes($title)?></li>
										<?php
										}
										
										while($row_prod = $db->fetch_array($ret_prod))
										{
										?>
											<li><h1><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="shelflistlink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name']);?></a></h1></li>
										<?php
										}
										?>
											<li><h1 align="right"><a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a></h1></li>
									</ul>
								<?php	
								break;
								case 'dropdown': // case of show shelf as drop down box
									$uniq = uniqid('');
									?>
									<table border="0" cellpadding="0" cellspacing="0" class="shopbybrandtable">
									<?php
									if($title)
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
												<select name="cbo_shelf_<?php echo $uniq."_".$shelfData['shelf_id']?>" id="cbo_shelf_<?php echo $uniq."_".$shelfData['shelf_id']?>" onchange="handle_dropdownval_sel(this.value)">
													<option value="">-- <?php echo $Captions_arr['COMMON']['SELECT']?> --</option>
													<?php
														while($row_prod = $db->fetch_array($ret_prod))
														{
														?>
															<option value="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],1)?>" <?php echo ($row_prod['product_id']==$_REQUEST['product_id'])?'selected':''?>><?php echo stripslashes($row_prod['product_name'])?></option>
														<?php
														}
													?>	
													<option value="<?php echo url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],1)?>">-- <?php echo $Captions_arr['COMMON']['SHOW_ALL']?> --</option>
												</select>
											</label>
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
			$vimgfield = (!$Settings_arr['imageverification_req_newsletter'])?'':'newsletter_Vimg';
			$Captions_arr['NEWS_LETTER'] = getCaptions('NEWS_LETTER');
			if($Settings_arr['shownewsletter_as_banner']==1) // Decide whether customer login is to be displayed as banner or in normal style
			{
				?>
				<div class="nws_div">
				<a href="<?php url_link('newsletter.html')?>"><img src="<?php url_site_image('neweletter-banner.gif')?>" border="0" /></a>
				</div>
				<?php
			}
			else
			{
		?>
			<form name="frm_newsletter" method="post" action="" class="frm_cls" onsubmit="return newsletter_validation(this)">
			<div class="news_div" align="right"> 
			<ul class="news_area">
			<?php
			if ($title)
			{
			?>		
				<li class="newsheader"><?php echo $title?></li>
			<?php
			}
			if($Settings_arr['newsletter_title_req']==1)
			{
			?>	
			 <li>
                  <div align="left" class="newsA"><?php echo $Captions_arr['NEWS_LETTER']['TITLE']?></div>
				<div align="left" class="newsB">
				<select name="newsletter_title" class="regiinput" id="newsletter_title" >
				<option value="">Select</option>
				<option value="Mr.">Mr.</option>
				<option value="Mrs.">Mrs.</option>
				<option value="Miss.">Miss.</option>  
				<option value="M/S.">M/S.</option>
				</select>
				</div>
			</li>
			<? 
			 }
			if($Settings_arr['newsletter_name_req']==1)
			{
			?>	
			<li>
				<div align="left" class="newsA"><?php echo $Captions_arr['NEWS_LETTER']['NAME']?></div>
				<div align="left" class="newsB">
				<input name="newsletter_name" type="text" class="news_textbox" id="newsletter_name" size="15" />				</div>
			</li>
			<?
			 }
			?>			 
			<li>
				<div align="left" class="newsA"><?php echo $Captions_arr['NEWS_LETTER']['EMAIL']?></div>
				<div align="left" class="newsB">
				<input name="newsletter_email" type="text" class="news_textbox" id="newsletter_email" size="15" />				</div>
			</li>
			<?
			if($Settings_arr['newsletter_phone_req']==1)
			{	
			?>
			<li>
				<div align="left" class="newsA"><?php echo $Captions_arr['NEWS_LETTER']['PHONE']?></div>
				<div align="left" class="newsB">
				<input name="newsletter_phone" type="text" class="news_textbox" id="newsletter_phone" size="15" />				</div>
			</li>	
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
				<li>
					<div align="left" class="newsC"><?php echo $Captions_arr['NEWS_LETTER']['GROUP']?></div>
				</li>	
				<li>
					<div align="left" class="newsC">
				<?php
				if (count($cust_group_arr))
				{ 
					echo generateselectbox('newsletter_group[]',$cust_group_arr,0,'','',5,'',false,'sel_newsletter_group');
				}	
				?>				
					</div>
				</li>
				<?php
				}
			}
			?>
			<?php 
				if($Settings_arr['imageverification_req_newsletter']) // if image verification is required
				{
			?>
			<li>
				<div align="left" class="newsC"><?=$Captions_arr['NEWS_LETTER']['ENTER_CODE']?>&nbsp;<span class="redtext">*</span></div>
			  </li>
            <li>
				<div align="left" class="newsC"><img src="<?php url_verification_image('includes/vimg.php?size=4&pass_vname=newsletter_Vimg')?>" border="0" alt="Image Verification"/></div>
			</li>
			<li>
				<div align="left" class="newsC"><span class="newsletterinput">
			      <?php 
					// showing the textbox to enter the image verification code
					$vImage->showCodBox(1,'newsletter_Vimg','class="inputA_imgver"'); 
				?>			</span>	</div>
			</li>	
		  <?php
				}
			?>
            <li>
				<div align="left" class="newsA">
				<input name="newsletter_Submit" type="submit" class="news_button" id="newsletter_Submit" value="<?php echo $Captions_arr['NEWS_LETTER']['SUBSCRIBE']?>" />				</div>

			</li>
			</ul>
			</div>
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
			$Captions_arr['GIFT_VOUCHER'] = getCaptions('GIFT_VOUCHER');
			$vimgfield = (!$Settings_arr['imageverification_req_voucher'])?'':'buycompgiftvoucher_Vimg';
		   ?>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr><td>
			<div class="gift_buy"><a href="<?php echo get_buyGiftVoucherURL()?>" class="buygiftvoucherheader" title="<?php echo stripslash_normal($Captions_arr['GIFT_VOUCHER']['BUY_VOUCHER'])?>"><img src=" <?php url_site_image('buy-gift.gif') ?>" /></a></div>
		    </td></tr>
			</table>
		<?php	
		}
		// ####################################################################################################
		// Function which holds the display logic for spending giftvoucher or promotional code
		// ####################################################################################################
		function mod_spendvoucher($title)
		{
			global $Captions_arr,$ecom_hostname,$db,$ecom_siteid,$Settings_arr,$vImage,$position;
			$Captions_arr['GIFT_VOUCHER'] = getCaptions('GIFT_VOUCHER');
		    if($position=='right_col1' || $position=='right_col2')
			{
			?>
			<table border="0" cellpadding="0" cellspacing="0">
			<tr><td>
		      <div class="gift_use"> 
			  <a href="http://<?php echo $ecom_hostname?>/spend_voucher.html" class="buygiftvoucherheader" title="<?php echo stripslash_normal($Captions_arr['GIFT_VOUCHER']['SPEND_VOUCHER_CLICK_HERE'])?>"><img src="<?php url_site_image('use-gift.gif')?>" /></a></div>
			</td></tr>
			</table>
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
            <div class="gift_div" align="right">
			 <ul class="gift_area">
			<li class="giftheader"><?php echo $Captions_arr['GIFT_VOUCHER']['ENTER_VOUCHER']?></li>
			 <li>
                  <div align="left">
					<input name="cart_promotionalcode" type="text" class="gifttextbox" id="cart_promotionalcode_comp" size="15" />
				</div>
				<div align="left">
				<?php 
				if(!$Settings_arr['imageverification_req_voucher']) // if image verification is required
				{
				?>
				<input name="compvoucher_Submit" type="submit" class="giftbutton" id="compvoucher_Submit" value="<?php echo $Captions_arr['GIFT_VOUCHER']['VOUCHER_GO']?>" />
				<?php
				}
				?>
				</div>
			</li>
			<?php 
				if($Settings_arr['imageverification_req_voucher']) // if image verification is required
				{
			?>

			  <li><?=$Captions_arr['GIFT_VOUCHER']['GIFT_VOUCHER_BUY_VOUCHER_VERIFICATION_CODE']?>&nbsp;<span class="redtext">*</span> </li>
			   <li><img src="<?php url_verification_image('includes/vimg.php?size=4&pass_vname=buycompgiftvoucher_Vimg')?>" border="0" alt="Image Verification"/></li>
			  <li>
			      <?php 
					// showing the textbox to enter the image verification code
					$vImage->showCodBox(1,'buycompgiftvoucher_Vimg','class="gifttextbox"'); 
				?>
				</li>
				 <li>
					<input name="compvoucher_Submit" type="submit" class="giftbutton" id="compvoucher_Submit" value="<?php echo $Captions_arr['GIFT_VOUCHER']['VOUCHER_GO']?>" />
				</li>
		  <?php
				}
			?>
			  <li><a href="<?php echo get_buyGiftVoucherURL()?>" class="buygiftvoucherheader" title="<?php echo $Captions_arr['GIFT_VOUCHER']['BUY_VOUCHER']?>"><?php echo $Captions_arr['GIFT_VOUCHER']['BUY_VOUCHER']?></a></li>
			</ul>
			</div>
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
						<div class="survey_div" align="right">
                       <ul class="survey_area">
				<?php	
						if($title)
						{
				?>
						 <li class="surveyheader"><?php echo $title?></li>
			 	<?php
			 			}
				?>		
						      <li><?php echo stripslashes($surveyData['survey_question'])?></li>
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
						 <li>
                            <div align="left" class="surveyA"><input name="survey_opt" type="radio" value="<?php echo $row_surveyopt['option_id']?>" /></div>
								<div align="left" class="surveyB"><?php echo stripslashes($row_surveyopt['option_text']);?></div>
						</li>
			  <?php
							}
						}
			  ?>
				  <li> 
					<input type="hidden" name="survey_comp_id" value="<?php echo $surveyData['survey_id']?>" />
					<input name="survey_Submit" type="submit" class="surveybutton" value="<?php echo $Captions_arr['SURVEY']['VOTE']?>" />
					</li>
					</ul></div>
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
			if ($position == 'right_col1') // ############## Left ##############
			{
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
								// Check the listing type for shop in product shop group
								if ($groupData['shopbrandgroup_listtype'] == 'Menu')// case if shops are to be shown in list menu
								{
									$cache_type		= 'shop_left_menu';	
									$cache_exists 	= false;
									$cache_required	= false;
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
							}	
						}
					}
				}
			}
			elseif ($position=='right_col2') // ############## Right ##############
			{
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
							$sql_shop = "SELECT a.shopbrand_id,a.shopbrand_name,shopbrand_parent_id ,shopbrand_subshoplisttype
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
							// Check the listing type for shop in product shop group
							if ($groupData['shopbrandgroup_listtype']== 'Menu')// case if shops are to be shown in list menu
							{
								$cache_type		= 'shop_right_menu';	
								$cache_exists 	= false;
								$cache_required	= false;
								if ($Settings_arr['enable_caching_in_site']==1 and !$_REQUEST['shop_id'])// dont pick from cache in case if clicked on any of the shops
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
						<ul class="shopright">
							<?php
								while ($row_shop = $db->fetch_array($ret_shop))
								{
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
								<a href="<?php url_link('shopbybrand.html')?>"><img src="<?php url_site_image('shop-brnd-banner.gif')?>" border="0" title="Click Here" /></a>
			<?php
							}
						}	
					}
				}
			}// End of right
		}
		
		
		// ####################################################################################################
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
			if ($position=='right_col1' or $position=='right_col2') // show the advert only if position is left or right
			{
				if (count($advert_arr))
				{
					foreach ($advert_arr as $d=>$k)
					{
						switch($position)
						{
							case 'right_col1':
								$cache_type		= 'comp_leftadvert';	
							break;
							case 'right_col2':
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
							<table width="100%" border="0" cellpadding="2" cellspacing="0" class="advert_comp_table">
					<?php	
							if($title)
							{
					?>
							  <tr>
								<td align="left" valign="top" class="advert_comp_header"><?php echo $title?></td>
							  </tr>
					<?php
							}
					?>
								  <tr>
									<td align="center" valign="middle" class="advert_comp_td">
				  <?php
						switch ($k['advert_type'])
						{
							case 'IMG':
								$path = url_root_image('adverts/'.$k['advert_source'],1);
								$link = $k['advert_link'];
								if ($link!='')
								{
						?>
									<a href="<?php echo $link?>" title="<?php echo $title?>" target="<?=$k['advert_target']?>">	
						<?php
								}
						?>
									<img src="<?php echo $path?>" alt="Advert" title="<?php echo $title?>" border="0" />
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
									<a href="<?php echo $link?>" title="<?php echo $title?>" target="<?=$k['advert_target']?>">	
						<?php
								}
						?>
									<img src="<?php echo $path?>" alt="Advert" title="<?php echo $title?>" border="0" />
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
								echo stripslashes($path);
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
							<div class="advert_rotate">
							<?php
							$advert_ul_id = uniqid('advert_');
							// get the list of rotating images
							$sql_rotate = "SELECT rotate_image,rotate_link 
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
												$(document).ready(
													function(){
													$(\'ul#'.$advert_ul_id.'\').innerfade({ 
														speed: 1000,
														timeout: '.($k['advert_rotate_speed']*1000).',
														type: \'sequence\',
														containerheight: \''.$k['advert_rotate_height'].'px\'
													});
												});
												</script>';
								$HTML_Content .= '<ul id="'.$advert_ul_id.'">';
								while ($row_rotate = $db->fetch_array($ret_rotate))
								{
									$link = trim($row_rotate['rotate_link']);
									$link_start = $link_end = '';
									if($link!='')
									{
										$link_start     = '<a href="'.$link.'" title="'.$title.'">';
										$link_end       = '</a>';
									}
									$HTML_Content .= '
														<li>'.$link_start.'
														<img src="'.url_root_image('adverts/rotate/'.$row_rotate['rotate_image'],1).'" alt="'.$title.'" />'.
														$link_end.'</li>';
								}
								$HTML_Content .='</ul>';
								echo $HTML_Content;
								}
							?>
							</div>
							<?php	
							break;
						};
						?>
							</td>
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
		}
		
		// ####################################################################################################
		// Function which holds the display logic for sitereviews
		// ####################################################################################################
		function mod_sitereviews($title)
		{
			global $ecom_siteid,$db,$position,$Settings_arr;
				//print_r($title);
		?>	
			<div class="sitereviewconleft" align="left">
				<input class="sitereviewleft" value="Site Reviews" onclick="window.location='<?php url_link('sitereview.html');?>'" type="button">
			</div>	
		<?php	
		}
		
		// ####################################################################################################
		// Function which holds the display logic for Recently Viewed Products
		// ####################################################################################################
		function mod_productlist11($ret_prod,$title)
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
		
function mod_productlist($ret_prod,$title)
		{
			global $position,$Captions_arr,$ecom_siteid,$db,$Settings_arr;
		
		if ($position == 'right_col1') // ############## Left ##############
			{	
		?>
				<ul class="shopleft">
			<?php
				if($title)
				{
			?>
				<li class="shopleftheader"><?php echo $title?></li>
			<?php
				}
			?>	
			
				<?php
					while ($row_prod = $db->fetch_array($ret_prod))
					{
				?>
						<li><h1><a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="shopleftlink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li> 
				<?php
					}
				?>					    
			</ul>	
		<?php
		  }	
		  elseif ($position == 'right_col2')
		  {
		  	
		?>
			<ul class="shopright">
			<?php
				if($title)
				{
			?>
				<li class="shopheaderright"><?php echo $title?></li>
			<?php
				}
			?>	
			
				<?php
					while ($row_prod = $db->fetch_array($ret_prod))
					{
				?>
						<li><h1><a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="shoplinkright" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li> 
				<?php
					}
				?>					    
			</ul>	
		<?php
		  }
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
                        <?php
                        if(file_exists("$image_path/site_images/ssl_main_image.gif"))
                        {
                        ?>
                            <img src="<? url_site_image('ssl_main_image.gif')?>" alt="Secure Payment" title="<?php echo $title?>" border="0" />
                        <?
                        }
                        ?>
                        </td>
                    </tr>
                    </table>
                <?
		}
		// Function to show the top menu item
		function mod_topmenu($title)
		{ //LOGIN_TOPMENU;
			global $db,$ecom_siteid,$ecom_hostname,$inlineSiteComponents,$Captions_arr;
			 $Captions_arr['LOGIN_MENU'] 	= getCaptions('LOGIN_MENU');
			 $cust_id 					= get_session_var("ecom_login_customer");
		?>
			<tr>
				<td colspan="3" class="userloginmenuytop" >
				<ul class="userloginmenuytopul"> 
				<li><h1><a href="<?php url_link('logout.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['LOGOUT']?></a></h1></li>
				<li><h1><a href="<?php url_link('mypricepromise.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_PRICE_PROMISE']?></a></h1></li>
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
				<li><h1><a href="<?php url_link('myorders.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_ORDERS']?></a></h1></li>
				<? $myaddr_module = 'mod_myaddressbook';
					if(in_array($myaddr_module,$inlineSiteComponents))
					{
				?>
					<li><h1><a href="<?php url_link('myaddressbook.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_ADDRESSBOOK']?></a></h1></li>
				<?php
					}
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
				<li><h1><a href="<?php url_link('myprofile.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_PROFILE']?></a></h1></li>
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
			if ($position=='right_col1' or $position=='right_col2') // Best sellers is allowed in left or right panels
			{	
				if ($db->num_rows($ret_main))
				{
						if ($position=='right_col1') // display login for left hand side
						{
						?>
						<table border="0" cellspacing="0" cellpadding="0" width="100%" class="shelf_table">
						<?php 
							$comp_uniqid = uniqid('');
							if($title) // check whether title exists
									{
						?>
										<tr>
											 <td align="left" valign="middle" class="shelf_header"><a href="javascript:handle_expland_comp(document.getElementById('head_<?php echo $comp_uniqid?>'),'<?php echo $comp_uniqid?>')" class="shelf_default" id="head_<?php echo $comp_uniqid?>"><?php echo $title?></a></td>
										</tr>
						<?php
									}
						?>			
									<tr id="<?php echo $comp_uniqid?>" style="display:none; width:100%;" >
									 <td align="left" valign="middle" class="shelf_home_td" style="width:100%">
						<?php	
									while ($row_main = $db->fetch_array($ret_main))
									{
						?>	
										<div class="shelf_home" style="width:100%">
										  <div class="shelf_img"><a href="<?php url_product($row_main['product_id'],$row_main['product_name'],-1)?>"  title="<?php echo stripslashes($row_main['product_name'])?>"><?php
										  $pass_type='image_iconpath';
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
																				?></a></div>
											<div class="shelf_content">
											  <h1><a href="<?php url_product($row_main['product_id'],$row_main['product_name'],-1)?>" class="bestsellerslink" title="<?php echo stripslashes($row_main['product_name'])?>"><?php echo stripslashes($row_main['product_name'])?></a></h1>
											  <h2><?php echo print_price($row_main['product_webprice'],false)?></h2>
											  <h3><?php show_moreinfo($row_main,'infolink')?></h3>
											</div>
											</div>
				
						<?php
								
									}		
						?>
										 <div class="shelf_showall" align="right"> <a href="<? url_link('preorder'.$display_id.'.html')?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>" <?php /*onclick="document.bestseller_component_left<?=$display_id?>.submit();"*/?>><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a> </div>
									</td></tr>
									</table>
									<?php /*<form name="bestseller_component_left<?=$display_id?>" action="<? url_link('bestsellers.html')?>" method="post"><input type="hidden" name="disp_id" value="<?php echo $display_id?>"/></form>*/?>
						<?php	
						}
						elseif($position == 'right_col2') // display logic for right hand side
						{
						
				?>
				<table border="0" cellspacing="0" cellpadding="0" width="100%" class="shelf_right_table">
				<?php 
			  	    $comp_uniqid = uniqid('');
			  		if($title) // check whether title exists
							{
				?>
								<tr>
               						 <td align="left" valign="middle" class="shelf_header_right"><a href="javascript:handle_expland_comp(document.getElementById('head_<?php echo $comp_uniqid?>'),'<?php echo $comp_uniqid?>')" class="shelf_default" id="head_<?php echo $comp_uniqid?>"><?php echo $title?></a></td>
								</tr>
				<?php
							}
				?>			
					        <tr id="<?php echo $comp_uniqid?>" style="display:none; width:100%;" >
               				 <td align="left" valign="middle" class="shelf_home_right" style="width:100%">
				<?php	
							while ($row_main = $db->fetch_array($ret_main))
							{
				?>	
								<div class="shelf_home" style="width:100%">
								  <div class="shelf_img"><a href="<?php url_product($row_main['product_id'],$row_main['product_name'],-1)?>"  title="<?php echo stripslashes($row_main['product_name'])?>"><?php
								  $pass_type='image_iconpath';
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
																		?></a></div>
									<div class="shelf_content">
									  <h1><a href="<?php url_product($row_main['product_id'],$row_main['product_name'],-1)?>" class="bestsellerslink" title="<?php echo stripslashes($row_main['product_name'])?>"><?php echo stripslashes($row_main['product_name'])?></a></h1>
									  <h2><?php echo print_price($row_main['product_webprice'],false)?></h2>
									  <h3><?php show_moreinfo($row_main,'infolink')?></h3>
									</div>
									</div>
		
				<?php
						
							}		
				?>
								 <div class="shelf_showall" align="right"><a href="<? url_link('preorder'.$display_id.'.html')?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>" <?php /*onclick="document.bestseller_component_left<?=$display_id?>.submit();"*/?>><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a> </div>
							</td></tr>
							</table>
							<?php /*<form name="bestseller_component_left<?=$display_id?>" action="<? url_link('bestsellers.html')?>" method="post"><input type="hidden" name="disp_id" value="<?php echo $display_id?>"/></form>*/?>
				<?php	
						
						}
				}	
			}	
		}


	};
?>