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
			};// Cache checking section
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
							<div class="static_div"  align="right">  
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
								
								if ($grpData['group_showsavedsearchlink']==1)
								{
								?>							
										<li><h1> <a href="<?php url_link('saved-search.html')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></h1></li>
								<?php		
								}
								if ($grpData['group_showxmlsitemaplink']==1 )
								{
								?>
										<li><h1> <a href="<?php url_link('sitemap.xml')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></a></h1></li>
								<?php	
								}	
								if ($grpData['group_showsitemaplink']==1 )
								{
								?>							
										<li><h1> <a href="<?php url_link('sitemap.html')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></a></h1></li>
								<?php		
								}
								if ($grpData['group_showhelplink']==1 )
								{
								?>		
										<li><h1> <a href="<?php url_link('help.html')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></a></h1></li>
								<?php
								}
								while ($row_pg = $db->fetch_array($ret_pg))
								{
									$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
								?>
										<li><h1> <a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="static" title="<?php echo stripslashes($row_pg['title'])?>" <?php echo $target?>><?php echo stripslashes($row_pg['title']);?></a></h1></li>
								<?php
								}
								if ($grpData['group_showhomelink']==1)
								{
					?>
									<li><h1> <a href="<?php url_link('')?>" class="static" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a> </h1></li>
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
							<ul class="link_bottom">  
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
												static_pages_order DESC";
								$ret_pg = $db->query($sql_pg);
								if ($grpData['group_showhelplink']==1)
								{
						?>
									<li><h1><a href="<?php url_link('help.html')?>" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></a></h1></li>
						<?php			
								}
								if ($grpData['group_showsavedsearchlink']==1)
								{
						?>
									<li><h1><a href="<?php url_link('saved-search.html')?>" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></h1></li>
						<?php			
								}
								if ($grpData['group_showxmlsitemaplink']==1 )
								{			
								?>
									<li><h1><a href="<?php url_link('sitemap.xml')?>" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></a></h1></li>
								<?php									
								}
								if ($grpData['group_showsitemaplink']==1)
								{
						?>
									<li><h1><a href="<?php url_link('sitemap.html')?>" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></a></h1></li>
						<?php			
								}
								if($show_top==0)
								{
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
									if ($grpData['group_showhomelink']==1)
									{
						?>
										<li><h1><a href="<?php url_link('')?>" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a></h1></li>
						<?php			
									}
										$show_top = 0;
								}				
									//$show_bottom = 1;
								}	
						?>			
								</ul>
						<?php	
						}
					}// End of bottom
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
			global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$split_span;
			
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
				if ($Settings_arr['enable_caching_in_site']==1 and !$_REQUEST['category_id'] and $position!='topband')
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
						if ($position == 'topband') // Case if value of position is top;
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
											<td align="left" valign="top" class="maintable_categorey_left">&nbsp;</td>
											<td align="left" valign="top" class="maintable_top_categorey_td" <?php echo $split_span?>>
											<ul class="categorytop">  
												<?php
													// Check whether any category from top level is clicked or not
													$top_cat_id = get_session_var('top_main_category');
													$sel_displayed = false;
													if ($top_cat_id!='')
													{
														// Get the name of current category 
														$sql_selcat = "SELECT category_name 
																				FROM 
																					product_categories 
																				WHERE 
																					category_id = $top_cat_id 
																					AND sites_site_id = $ecom_siteid 
																				LIMIT 
																					1";
														$ret_selcat = $db->query($sql_selcat);
														if ($db->num_rows($ret_selcat))
														{
															$row_selcat = $db->fetch_array($ret_selcat);
															$sel_displayed = true;
														?>
															<li class="selected_cat"><h1><a href="<?php url_category($top_cat_id,$row_selcat['category_name'],-1)?>" title="<?php echo stripslashes($row_selcat['category_name'])?>"><?php echo $row_selcat['category_name']?></a></h1></li >
															
														<?php	
														}
													}
													else
														$top_cat_id = -1;
												?>	
												
											<?php
												while ($row_cat = $db->fetch_array($ret_cat))
												{
													$topcat_arr[] = $row_cat['category_id'];
													if($sel_displayed==false)
													{
														$catclass 			= 'selected_cat';													
														$sel_displayed 	= true;	
														set_session_var('top_main_category',$row_cat['category_id']);
													}
													else
														$catclass = 'categorytoplink';
													if($top_cat_id!=$row_cat['category_id'])
													{
														if ($catclass!='selected_cat')
														{
											?>
															<li><h1><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="<?php echo $catclass?>" title="<?php echo stripslashes($row_cat['category_name'])?>"><?php echo stripslashes($row_cat['category_name']);?></a></h1></li>
											<?
														}
														else
														{
												?>		<li class="<?php echo $catclass?>"><h1><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>"  title="<?php echo stripslashes($row_cat['category_name'])?>"><?php echo stripslashes($row_cat['category_name']);?></a></h1></li>
											<?php
														}
													}
												}
												set_session_var('top_main_category_arr',$topcat_arr);
											?>
											 </ul> 
											 </td>
											<td align="left" valign="top" class="maintable_categorey_left">&nbsp;</td>
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
										if ($grpData['catgroup_listtype']== 'Menu')// case if categories are to be shown in list menu
										{
										?>
											<ul class="category">
										<?php
											while ($row_cat = $db->fetch_array($ret_cat))
											{
												if($prev_grp != $grpData['catgroup_id'])
												{
													if ($grpData['catgroup_hidename']==0) // Decide whether or not to show the groupname
													{
											?>
														<li class="categoryheader"><?php echo stripslashes($title)?></li>
											<?php
													}
														$prev_grp = $grpData['catgroup_id'];
												}
												// Start:- to check for whether the categories under the group is displayed as a heading with/without a link
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
											}
										?>
										</ul>
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
						elseif ($position=='right') // ############## Right ##############
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
												echo '<ul class="category"><li class="categoryheader">'.$title.'</li>';
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
		function mod_quicksearch()
		{
			global $Captions_arr,$position,$ecom_hostname,$ecom_themename,$db,$ecom_siteid,$default_layout,$components;
			$checkout_link = get_Checkoutlink(1);
			if($_REQUEST['req']=='cart') // if current page is related to cart then call cartcalc function to refresh the cart totals and number of items in cart
			{
				$cartData = cartCalc(); // calling cart calc 
			}	
			if ($position=='top') // show only if position value is top
			{
			?>
				  <div class="search_div" align="right">
					<ul class="search_area">  
						<li><a href="<?php url_link('advancedsearch.html')?>" class="advancedsearch" title="<?php echo $Captions_arr['COMMON']['ADVANCED_SEARCH']?>"><?php echo $Captions_arr['COMMON']['ADVANCED_SEARCH']?></a></li>
						<li>
						<form method="post" name="frm_quicksearch" class="frm_cls" action="<?php url_link('search.html')?>">
						  <input name="quick_search" type="text" class="topsarchtextbox" id="quick_search" size="15" />
						  <input name="button_submit_search" type="submit" class="topsarchbutton" id="button_submit_search" value="<?php echo $Captions_arr['COMMON']['SEARCH_GO']?>"  onclick="show_wait_button(this,'Please wait...')"/>
					    <input type="hidden" name="search_submit" value="search_submit" />
					</form>
						</li>
						<li>
							<?php 
								// Check whether any shop by brand group is set to top position
								$query	= "SELECT 
													display_component_id 
												FROM 
													display_settings a, features b 
												WHERE 
													a.sites_site_id='$ecom_siteid' 
													AND a.display_position LIKE '$position' 
													AND feature_modulename='mod_shopbybrandgroup'
													AND a.features_feature_id=b.feature_id 
													AND a.layout_code='$default_layout' 
												ORDER BY 
													a.display_order;";	
								$ret_query = $db->query($query);
								if ($db->num_rows($ret_query))
								{	
									$row_query = $db->fetch_array($ret_query);
									$display_componentid = $row_query['display_component_id'];
									$position ='with_search';		
									include ('themes/'.$ecom_themename.'/modules/mod_shopbybrandgroup.php');
								}	
							?>
						</li> 
					</ul> 
			  </div>
			  <div class="checkou_div" align="right">
	            <ul class="topcartlink">
                	 <li><?php echo $Captions_arr['COMMON']['CART_TOTAL']?><?php echo print_price(get_session_var('cart_total'),true)?></li>
                	 <li><?php echo $Captions_arr['COMMON']['ITEMS_IN_CART']?><?php echo get_session_var('cart_total_items')?></li>
                	 <li class="check_out"><a href="#"  onclick="handle_checkout_submit('<?php echo $ecom_hostname?>',0)" class="viewcart" title="<?php echo $Captions_arr['COMMON']['CHECK_OUT']?>"><?php echo $Captions_arr['COMMON']['CHECK_OUT']?></a></li>
                 	<li class="cart_link"><a href="#" onclick="gobackto_cart('<?php echo $ecom_hostname?>')" class="viewcart" title="<?php echo $Captions_arr['COMMON']['VIEW_CART']?>"><?php echo $Captions_arr['COMMON']['VIEW_CART']?></a></li>
					<li class="enquire_list"><a href="<?php url_link('enquiry.html')?>" onclick="gobackto_cart('v4demo4.arys.net')" class="viewcart" title="<?php echo $Captions_arr['COMMON']['VIEW_ENQUIRY']?>"><?php echo $Captions_arr['COMMON']['VIEW_ENQUIRY']?></a></li>
           		 </ul>  
    		  </div>   
		<?php	
			}	
		}
		// ####################################################################################################
		// Function which holds the display logic for customer login
		// ####################################################################################################
		function mod_customerlogin($title)
		{
			global $Captions_arr,$ecom_hostname,$inlineSiteComponents,$Settings_arr;
			$Captions_arr['CUST_LOGIN'] = getCaptions('CUST_LOGIN');
			$cust_id 					= get_session_var("ecom_login_customer");
			if (!$cust_id) // case customer is not logged in
			{
				$hide_newuser 		=  $Settings_arr['hide_newuser'];
				$hide_forgotpass 	=  $Settings_arr['hide_forgotpass'];
				if ($title)
				{
		?>
					<ul class="left_comp_links">
				 <li > <h1> <a href="<?php url_link('custlogin.html')?>"><?php echo $title?></a> </h1> </li >
				 </ul>
		<?php
				}
			}	
		}
		
		// ####################################################################################################
		// Function which holds the display logic for call back request 
		// ####################################################################################################
		function mod_callback($title)
		{
			if($title)
			{
		?>
			<ul class="left_comp_links">
			 <li > <h1> <a href="<?php url_link('callback.html')?>"><?php echo $title?></a> </h1> </li >
			 </ul>	
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
					switch($position)
					{
						case 'left':
							$cache_type		= 'comp_leftbestseller';	
						break;
						case 'right':
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
					}
					// Do the following only if caching is not enabled or cache does not exists
					if ($cache_exists==false)
					{
						if($cache_required)// if caching is required start recording the output
						{
							ob_start();
						}	
						if ($position=='left') // display login for left hand side
						{
							if($title) // check whether title exists
							{
				?>
								<ul class="left_comp_dis">
								<li><h1><a href="<? url_link('bestsellers'.$display_id.'.html')?>"><?php echo $title?></a></h1></li >
								</ul>
				<?php
							}
						}
						elseif($position == 'right') // display logic for right hand side
						{
							if($title) // check whether title exists
							{
				?>
								<ul class="left_comp_dis">
								<li><h1><a href="<? url_link('bestsellers'.$display_id.'.html')?>"><?php echo $title?></a></h1></li >
								</ul>
				<?php
							}
						}
						if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
						{
							$content = ob_get_contents();
							ob_end_clean();
							save_Cache($cache_type,$ecom_siteid,$content);
							echo $content;
						}
					}	
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
						<ul class="compare_comp">   
				<?php
							if($title) // check whether title exists
							{
				?>
								<li class="compare_compheader"><?php echo $title?></li>
				<?php
							}
							while ($row_compare_pdts = $db->fetch_array($ret_compare_pdts))
							{
				?>	
								<li><h1><img src="<?php url_site_image('delete.gif')?>" onclick="document.common_compare_list.remove_compareid.value=<?=$row_compare_pdts['product_id']?>; if(confirm('Are You sure You want to remove the product from the compare list')){ document.common_compare_list.submit()};" alt="Remove" title="Remove" />&nbsp;<a href="<?php url_product($row_compare_pdts['product_id'],$row_compare_pdts['product_name'],-1)?>" class="compare_complink" title="<?php echo stripslashes($row_compare_pdts['product_name'])?>"><?php echo stripslashes($row_compare_pdts['product_name'])?></a></h1></li>
		
				<?php
							}
							if(count($_SESSION['compare_products'])>1)
							{	
				?>
								<li><h1 align="right"> <a href="<?php url_link('compare_products.html')?>" class="showall_compare" title="<?php echo $Captions_arr['COMMON']['COMPARE_PRODUCTS']?>" target="_blank"><?php echo $Captions_arr['COMMON']['COMPARE_PRODUCTS']?></a> </h1> </li>
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
						?>
										<ul class="left_comp_dis">
											<li><h1><a href="<?php url_combo($combData['combo_id'],$combData['combo_name'],-1)?>" title="<?php echo $title?>"><?php echo $title?></a></h1></li >
										</ul>
										<ul class="combodeals">  
						<?php
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
			if($position =='left' or $position == 'right' or $position=='middleband')
			{
				// Getting the settings for shelves from settings table
				// Deciding the sort by field
				$shelfsort_by			= $Settings_arr['product_orderfield_shelf'];
				
				// Getting the limit of products to be shown in left of right components for the shelf
				if($position=='middleband' or $position=='middle' or $position=='right')
					$limit					= $Settings_arr['product_maxshelfprod_in_component'];
				else
					$limit = 1; // overriding the limit, since only heading is displayed
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
					//print_r($shelfData);
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
											a.product_discount,a.product_discount_enteredasval,a.product_applytax,a.product_bonuspoints    									  
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
									case 'list': // case of one in a row
										if($position!='middleband')
										{
											if ($title)
											{
									?>
												<ul class="left_comp_dis">
												<li><h1><a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>"><?php echo $title?></a></h1></li >
												</ul>
									<?php
											}
										}
										else
										{
										?>
											<div class="shelf_div_A" >
												<ul class="shelfA">
													<li>
													<a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="shelfbutton" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a>
													</li >     
													<?php
													while($row_prod = $db->fetch_array($ret_prod))
													{
													?>            
													<li>
															<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
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
															</a>
													</li >
													<?php
													}
													?>
													<li><div class="comp_shelfAheader" align="center"><?php echo $title?></div></li >
												</ul> 
											</div>
										<?php	
										}	
									break;
									case 'rows':
									if ($title)
									{
									?>
									<div class="product_list_sub_header"><?php echo $title?></div>
									<?php
									}
									while($row_prod = $db->fetch_array($ret_prod))
									{
									?>
										<div class="product_list_sub">
										<ul>
											<?php
											if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
											{
											?>
											<li> <h1 class="pro_name_sub"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></h1></li >
											<?php
											}
											if($shelfData['shelf_showimage']==1) // whether image is to be displayed
											{
											?>
											<li><h1>
											<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslashes($row_prod['product_name'])?>">
											<?php
												// Calling the function to get the type of image to shown for current 
												//$pass_type = 'image_thumbpath';
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
											</h1></li >
											<?php
											}
										?>
										</ul>
										</div>
								<?php
									}
								?>
										<div class="div_shw">
										<ul class="compshelflist">
										<li><h1 align="left"><a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="showall" title="<?php echo $Captions_arr['COMMON']['SHOW_ALL']?>"><?php echo $Captions_arr['COMMON']['SHOW_ALL']?></a></h1></li>
										</ul>
										</div>
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
		?>
		<div class="newsletter" align="right">
		<form name="frm_newsletter" method="post" action="" class="frm_cls" onsubmit="return newsletter_validation(this)">
		<ul class="newsletter_ul">  
		<li>Newsletter Sign Up</li>
		<li>
			<input name="newsletter_email" type="text" class="newslettertextbox" id="newsletter_email" size="15"  />
			<?php
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
			?>
					<input name="<?php echo stripslashes($row_groups['custgroup_name'])?>" type="checkbox" value="<?php echo  $row_groups['custgroup_id']?>" /><?php echo stripslashes($row_groups['custgroup_name'])?>
			<?php	
				}		
			}				
			?>
		</li>
		<li>
		<input type="submit" value="<?php echo $Captions_arr['NEWS_LETTER']['SUBSCRIBE']?>"  class="newsletter_button" name="newsletter_Submit"/>
		</li> 
		</ul> 
		</form>
	</div>
		<?php
		}
		// ####################################################################################################
		// Function which holds the display logic for Gift Vouchers
		// ####################################################################################################
		function mod_giftvoucher($title)
		{
			global $Captions_arr,$ecom_hostname,$db,$ecom_siteid,$Settings_arr,$vImage;
			$Captions_arr['GIFT_VOUCHER'] = getCaptions('GIFT_VOUCHER');
			$vimgfield = (!$Settings_arr['imageverification_req_voucher'])?'':'buycompgiftvoucher_Vimg';
			
		?>
			<form method="post" name="voucher_frm" id="voucher_frm" action="" class="frm_cls" onsubmit="return validate_smallvoucher(this,'<?php echo $vimgfield?>')">
			<input type='hidden' name='cart_savepromotional' id="cart_savepromotional_comp" value="1" />
			<?php
				if($_REQUEST['req']=='') // case of home page 
				{
			?>
					<ul class="gift_voucher">
					<li >Enter Voucher Number</li >
					<li><div align="right" class="gift_div1"><input name="cart_promotionalcode" type="text"  class="gift_input"/></div><div align="left" class="gift_div2"><input name="compvoucher_Submit" id="compvoucher_Submit" type="submit" class="gift_inputbutton" value="<?php echo $Captions_arr['GIFT_VOUCHER']['VOUCHER_GO']?>" />
					</div></li >
					<li><a href="<?php url_link('buy_voucher.html')?>"><?php echo $Captions_arr['GIFT_VOUCHER']['BUY_VOUCHER']?></a></li >
					</ul>
			<?php
				}
				else // case other than home page
				{
			?>
					<ul class="gift_inner">
					<li class="gift_inner_header">Enter Voucher Number</li >
					<li><div align="right" class="gift_inn1"><input name="cart_promotionalcode" type="text"  class="gift_input"/></div><div align="left" class="gift_inn2"><input name="compvoucher_Submit" id="compvoucher_Submit" type="submit" class="gift_inputbutton" value="<?php echo $Captions_arr['GIFT_VOUCHER']['VOUCHER_GO']?>" />
					</div></li >
					<li><a href="<?php url_link('buy_voucher.html')?>"><?php echo $Captions_arr['GIFT_VOUCHER']['BUY_VOUCHER']?></a></li >
					</ul>
			<?php	
				}
			?>		
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
						if($title)
						{
				?>
							<ul class="left_comp_links">
							 <li > <h1> <a href="<?php url_link('survey'.$surveyData['survey_id'].'.html');?>"><?php echo $title?></a> </h1> </li >
							 </ul>	
			 	<?php
			 			}
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
			$sort_by 	=  $Settings_arr['productshop_orderfield'];
			$sort_by 	=  ($sort_by=='custom')?'b.shop_order':$sort_by;
			$sort_order =  $Settings_arr['productshop_orderby'];
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
			if ($position == 'with_search') // ############## with_search ##############
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
												b.shop_order";
							$ret_shop = $db->query($sql_shop);
							if ($db->num_rows($ret_shop))
							{
								if($groupData['shopbrandgroup_listtype'] =='Dropdown')
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
	
											<select name="prodshopgroup_<?php echo $groupData['shopbrandgroup_id']?>" onchange="handle_dropdownval_sel(this.value)">
												<option value="">-- <?php echo $Captions_arr['COMMON']['SELECT']?> -- </option>
								<?php
												// Case if categories are to be shown in dropdown box
												while ($row_shop = $db->fetch_array($ret_shop))
												{
								?>
													<option value="<?php url_shops($row_shop['shopbrand_id'],$row_shop['shopbrand_name'],-1,$groupData['shopbrandgroup_id'])?>" <?php echo (($row_shop['shopbrand_id']==$_REQUEST['shop_id']))?'selected="selected"':''?>><?php echo stripslashes($row_shop['shopbrand_name'])?></option>
								<?php		
												}
								?>
											</select>
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
		
		
		// ####################################################################################################
		// Function which holds the display logic for Recently Viewed Products
		// ####################################################################################################
		function mod_recentlyviewedproduct($cookval,$title)
		{
			global $Captions_arr,$ecom_siteid,$db,$Settings_arr;
		   $frm_name = uniqid('recent_');

		?>
				<div class="pro_recent" >
	    <?php
					if($title)
					{
	?>
				    	<div class="pro_recent_header">&nbsp;<?php echo $title?></div>
	<?php
					}
	?>	
				<div class="pro_recent_content">
				<form name="<? echo $frm_name?>" id="<? echo $frm_name?>" action="" method="post">
				<input type="hidden" name="remove_recent" id="remove_recent" value="remove_recent" />
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
								<ul>
									<li class="recent_header"><a href="<?php echo url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" class="recentprodlink" title="<?php echo stripslashes($row_prod['product_name'])?>"><?php echo stripslashes($row_prod['product_name'])?></a></li>
									<li class="recent_content">
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
									</li>
									</ul>
									</li>
								</ul> 
							<?php
							}		
						}					
					}
				?>					    
				<ul class="compshelflist">
				<li><h1 align="left"><a href="#" class="recent_showall" title="<?php echo $Captions_arr['COMMON']['CLICK_HERE']?>" onclick="if(confirm('Are You sure You want to clear the recent product list')){ document.<?=$frm_name?>.submit()};"><?php echo $Captions_arr['COMMON']['COMON_RECENT']?></a></h1></li>
				</ul>
				</form>
				</div>
			    </div>
			
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
		?>	
			<ul class="left_comp_links">
			 <li > <h1> <a href="<?php url_link('sitereview.html');?>"><?php echo $title?></a> </h1> </li >
			 </ul>
		<?php	
		}
		
		// ####################################################################################################
		// Function which holds the display logic for Recently Viewed Products
		// ####################################################################################################
		function mod_productlist($ret_prod,$title)
		{
			global $Captions_arr,$ecom_siteid,$db,$Settings_arr;
			return;
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
			<div align="center" class="site_stat"><?=$row_query['site_hits']?> <?php echo $Captions_arr['COMMON']['WEB_STATISTICS']?></div>
		<?
		}
		function mod_ssl($title){
				global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
				?>
			<table border="0" cellpadding="0" cellspacing="2" class="ssltable">
			<?php
				if($title)
				{
			?>
				<tr>
					<td  class="sslheader" align="left"><?php echo $title?></td>
				</tr>
				
			<?php
				}
				$pass_type='image_extralargepath';
				
				// Get the payment method id for google checkout frm payment_methods table inorder to avoid it from coming in ssl in components section
				$sql_paym = "SELECT  paymethod_id 
									FROM 
										payment_methods 
									WHERE 
										 paymethod_key='GOOGLE_CHECKOUT' 
									LIMIT 
										1";
				$ret_paym = $db->query($sql_paym);
				if ($db->num_rows($ret_paym))
				{
					$row_paym = $db->fetch_array($ret_paym);
					$paymid	  = $row_paym['paymethod_id'];
				}	
				$sql_img =	"SELECT payment_methods_forsites_id,payment_methods_paymethod_id,paymethod_name,paymethod_key, 
									paymethod_ssl_imagelink,payment_method_sites_image_id 
									from payment_methods_forsites pms,payment_methods pm
									WHERE 
										pms.sites_site_id = $ecom_siteid 
										AND pm.paymethod_id=pms.payment_methods_paymethod_id 
										AND pm.paymethod_id<>$paymid"; 
				$ret_img = $db->query($sql_img);
				while ($row_img =	$db->fetch_array($ret_img)){
				
				if($row_img['payment_method_sites_image_id']){
					$img_name =  getImageByID($row_img['payment_method_sites_image_id']);
					 $img="http://$ecom_hostname/images/$ecom_hostname/".$img_name."" ;
					
			?>	
			<tr>
				    <td align="center" ><?php if($row_img['paymethod_ssl_imagelink']) {?><a href="#" onclick="window.open('<?=$row_img['paymethod_ssl_imagelink']?>','<?=str_replace(' ','_',$row_img['paymethod_name']) ?>','scrollbars=1,resizable=1,height=550,width=500,top=150,left=300');"><? }?><? show_image($img,1,1); //show_image(url_root_image($row_img['image_extralargepath'],1),'','')?>
					<?php if($row_img['paymethod_ssl_imagelink']) {?></a> <? }?></td>
				</tr>
				<?
				}
				else{
				global $image_path;
				///echo "$image_path/site_images/".strtolower($ssl_img['paymethod_key'])."_ssl.gif";
				if(file_exists("$image_path/site_images/".strtolower($row_img['paymethod_key'])."_ssl.gif")){
						$img=strtolower($row_img['paymethod_key'])."_ssl.gif" ;	
						
				?>
				<tr>
				    <td align="center" ><?php if($row_img['paymethod_ssl_imagelink']) {?><a href="#"  onclick="window.open('<?=$row_img['paymethod_ssl_imagelink']?>','<?=str_replace(' ','_',$row_img['paymethod_name']) ?>','scrollbars=1,resizable=1,height=500,width=450,top=150,left=300');"><? }?><img src="<? url_site_image($img)?>" alt="<?=$row_img['paymethod_name']?>" title="<?php echo $title?>" border="0" />
				<?php if($row_img['paymethod_ssl_imagelink']) {?>	</a><? }?></td>
				</tr>
				<?
				}
				}
			}
				?>
			</table>
			<?

		}
		// Function to show the top menu item
		function mod_topmenu()
		{ //LOGIN_TOPMENU;
			global $db,$ecom_siteid,$ecom_hostname,$inlineSiteComponents,$Captions_arr;
			 $Captions_arr['LOGIN_MENU'] 	= getCaptions('LOGIN_MENU');
			  $cust_id 					= get_session_var("ecom_login_customer");
		?>
			 <div class="user_menu">
			<ul>
				<li><a href="<?php url_link('login_home.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_HOME']?></a></li>
				<li><a href="<?php url_link('myprofile.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_PROFILE']?></a></li>
				<li><a href="<?php url_link('myenquiries.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_ENQUIRIES']?></a></li>
				<li><a href="<?php url_link('wishlist.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_WISHLIST']?></a></li>

				<?php	
					$myfav_module = 'mod_myfavorites';
					if(in_array($myfav_module,$inlineSiteComponents))
					{
				?>
					<li><a href="<?php url_link('myfavorites.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_FAVOURITE']?></a></li>
				<?php
					}
					$myaddr_module = 'mod_myaddressbook';
					if(in_array($myaddr_module,$inlineSiteComponents))
					{
				?>
					<li><a href="<?php url_link('myaddressbook.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_ADDRESSBOOK']?></a></li>
				<?php
					}
				?>
				<li><a href="<?php url_link('myorders.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_ORDERS']?></a></li>
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
				<li><a href="<?php url_link('mydownloads.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_DOWNLOADS']?></a></li>
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
					<li><a href="<?php url_link('mypayonaccountpayment.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['MY_PAYONACCDETAILS']?></a></li>
				<?php
				}
				?>
				<li><a href="<?php url_link('logout.html')?>" class="userloginmenuytoplink"><?=$Captions_arr['LOGIN_MENU']['LOGOUT']?></a></li>
				</ul>
				</div>

		<?php
		}
		
		/* Function to show the currency selector */
		function mod_currencyselector($title)
		{
			global $db,$ecom_siteid,$sitesel_curr;
		  	// get the list of currencies to be used with the site
			$curr_arr = get_currency_list();
	  	?>
	  		<form method="post" name="frm_maincurrency" enctype="multipart/form-data" class="frm_cls" action="">
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
					echo generateselectbox('cbo_selcurrency',$curr_arr,$sitesel_curr,'','document.frm_maincurrency.submit()',0,'currencyselectordropdown');
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
			if (count($header_arr)) 
			{
				$random = array_rand($header_arr);
				$header_image = $header_arr[$random];
				$header_image = $header_image;
				$header_image = url_root_image($header_image);
				
				//$header_image = "<img src=\"$header_image\" alt=\"\" width=\"244\" height=\"45\" border=\"0\" />";
					return $header_image;
			} else {
				$header_image = url_site_image('main_header.jpg');
				//$header_image = "<img src=\"$header_image\" alt=\"\" width=\"244\" height=\"45\" border=\"0\" />";
					return $header_image;
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
					switch($position)
					{
						case 'left':
							$cache_type		= 'comp_leftpreorder';	
						break;
						case 'right':
							$cache_type		= 'comp_rightpreorder';	
						break;
					};
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
					}
					// Do the following only if caching is not enabled or cache does not exists
					if ($cache_exists==false)
					{
						if($cache_required)// if caching is required start recording the output
						{
							ob_start();
						}	
						if ($position=='left' or $position=='right') // display login for left hand side
						{
				?>
							<ul class="left_comp_dis">
							<li><h1><a href="<? url_link('preorder'.$display_id.'.html')?>"><?php echo $title?></a></h1></li >
							</ul>
				<?php	
						}
					}
					if($cache_required)// If caching was required then stop the recording and save the cache and display the cached content
					{
						$content = ob_get_contents();
						ob_end_clean();
						save_Cache($cache_type,$ecom_siteid,$content);
						echo $content;
					}
				}	
			}	
		}

		// ####################################################################################################
		// Function which holds the display logic for stand alone subcategory listing
		// ####################################################################################################
		function mod_subcategorylist($cat_id)
		{
			global $ecom_siteid,$db,$ecom_hostname,$position,$Captions_arr,$Settings_arr;
			// Check whether there exists any subcategories for the given category
			$sql_cat = "SELECT category_id,category_name 
								FROM 
									product_categories 
								WHERE 
									parent_id=$cat_id 
									AND sites_site_id = $ecom_siteid 
								ORDER BY 
									category_order";
			$ret_cat = $db->query($sql_cat);
			if ($db->num_rows($ret_cat))
			{	
		?>
				<div id="scroll_up"></div> 
				<div id="scroll_box">
				<ul class="categorey">  
				<?php
					while ($row_cat = $db->fetch_array($ret_cat))
					{
				?>
						<li><h1><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'])?>" class="catelink" title="<?php echo stripslashes($row_cat['category_name'])?>"><?php echo stripslashes($row_cat['category_name'])?></a></h1></li >
				<?php
					}
				?>
				</ul>
				</div>
				<div id="scroll_down"></div>
		<?php	
			}
		}	
		// ####################################################################################################
		// Function which holds the display logic for stand alone subcategory listing
		// ####################################################################################################
		function mod_featured($ret_featured,$title)
		{
			global $ecom_siteid,$db,$ecom_hostname,$position,$Captions_arr,$Settings_arr;
			$row_featured = $db->fetch_array($ret_featured);
			$img_arr = get_imagelist('prod',$row_featured['product_id'],'image_iconpath',0,0,1);
		?>
			 <ul class="featured">
		  		<li class="featuredheader"><h1><?php echo $title?></h1></li >
				<li class="featured_prod"> <h1> <a href="<?php url_product($row_featured['product_id'],$row_featured['product_name'],-1)?>" title="<?php echo stripslashes($row_featured['product_name'])?>">
				<?php 
						if(count($img_arr))
						{
							show_image(url_root_image($img_arr[0]['image_iconpath'],1),$row_featured['product_name'],$row_featured['product_name']);
						}
						echo stripslashes($row_featured['product_name'])?></a> </h1> </li >
  			</ul>
		<?php	
		}	
	};
?>
