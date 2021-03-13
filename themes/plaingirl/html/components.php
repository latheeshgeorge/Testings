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
				case 'bottomband1':
					$cache_type		= 'comp_leftstatgroup';	
				break;
				case 'right-middle-band':
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
                            ?>
                                <li>
                                <h2> <a href="<?php url_link('')?>" class="static_menu" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?></a></h2>
                                </li>
                            <?php
                            }
                            while ($row_pg = $db->fetch_array($ret_pg))
                            {
                                $target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
                            ?>
                                <li>
                                <h2> <a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="static_menu" title="<?php echo stripslash_normal($row_pg['title'])?>" <?php echo $target?>><?php echo stripslash_normal($row_pg['title']);?></a></h2>
                                </li>
                            <?php
                            }
                            if ($grpData['group_showxmlsitemaplink']==1 )
                            {
                            ?>
                                <li>
                                <h2> <a href="<?php url_link('sitemap.xml')?>" class="static_menu" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP'])?></a></h2>
                                </li>
                            <?php	
                            }
                            if ($grpData['group_showsitemaplink']==1 ) 
                            {
                            ?>
                                <li>
                                <h2> <a href="<?php url_link('sitemap.html')?>" class="static_menu" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SITEMAP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SITEMAP'])?></a></h2>
                                </li>
                            <?php		
                            }
                            if ($grpData['group_showsavedsearchlink']==1)
                            {
                            ?>
                                <li>
                                <h2> <a href="<?php url_link('saved-search.html')?>" class="static_menu" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH'])?></a></h2>
                                </li>
                            <?php		
                            }
                            if ($grpData['group_showhelplink']==1 )
                            {
                            ?>
                                <li>
                                <h2> <a href="<?php url_link('help.html')?>" class="static_menu" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HELP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HELP'])?></a></h2>
                                </li>
                            <?php
                            }
                            if ($grpData['group_showfaqlink']==1 )
                            {
                            ?>
                                <li>
                                <h2> <a href="<?php url_link('faq.html')?>" class="static_menu" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?></a></h2>
                                </li>
                            <?php
                            }
                            }
                        }		
                        ?>
                    
                    </ul>
                    </div>
<?php
					}// End of top
					elseif ($position == 'bottom') // Case if value of position is bottom;
					{
						if(count($grp_array))
						{
						?>
						<div class="static_con_bottom">
							<ul >
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
									<li>
									<h4><a href="<?php url_link('')?>" class="Category" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HOME'])?></a></h4>
									</li>
									<?php			
								}
									$show_top = 0;
								}	
								
								while ($row_pg = $db->fetch_array($ret_pg))
								{
								$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
									?>
									<li>
									<h4><a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="bottomlink" title="<?php echo stripslash_normal($row_pg['title'])?>" <?php echo $target?>><?php echo stripslash_normal($row_pg['title']);?></a></h4>
									</li>
									<?php
								}
								}
								?>
								<?php			
								if ($grpData['group_showsitemaplink']==1)
								{
									?>
									<li>
									<h4><a href="<?php url_link('sitemap.html')?>" class="Category" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SITEMAP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SITEMAP'])?></a></h4>
									</li>
									<?php			
								}
								if ($grpData['group_showxmlsitemaplink']==1 )
								{			
									?>
									<li>
									<h4><a href="<?php url_link('sitemap.xml')?>" class="Category" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP'])?></a></h4>
									</li>
									<?php									
								}
								if ($grpData['group_showfaqlink']==1)
								{
									?>
									<li>
									<h4><a href="<?php url_link('faq.html')?>" class="Category" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?></a></h4>
									</li>
									<?php			
								}
								if ($grpData['group_showhelplink']==1)
								{
									?>
									<li>
									<h4><a href="<?php url_link('help.html')?>" class="Category" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HELP'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_HELP'])?></a></h4>
									</li>
									<?php			
								}
								if ($grpData['group_showsavedsearchlink']==1)
								{
									?>
									<li>
									<h4><a href="<?php url_link('saved-search.html')?>" class="Category" title="<?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH'])?>"><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH'])?></a></h4>
									</li>
									<?php			
								}
								?>
							</ul>
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
							if($grpData['group_listtype'] == 'Menu')
							{
							?>
							<div class="static_lf_con">
							<div class="static_lf_top"></div>
								<div class="static_lf_mid">
								<?php
									if($prev_grp != $grpData['group_id'])
									{
										if ($grpData['group_hidename']==0) // Decide whether or not to show the groupname
										{
										?>
										<div class="static_header">
										<div class="static_header_top"></div>
										<div class="static_header_bottom"><?php echo stripslashes($title)?></div>
										</div>
									<?php
										}
										$prev_grp = $grpData['group_id'];
									}
									?>
								<ul class="static">
								<?php
										if ($grpData['group_showhomelink']==1)
										{
										?>
								<li>
								<h2><a href="<?php url_link('')?>" class="staticlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a></h2>
								</li>
								<?
										}
										while ($row_pg = $db->fetch_array($ret_pg))
										{
										$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
										?>
								<li>
								<h2><a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="staticlink" title="<?php echo stripslashes($row_pg['title'])?>" <?php echo $target?>><?php echo stripslashes($row_pg['title']);?></a></h2>
								</li>
								<?php
										}
										
										if ($grpData['group_showsitemaplink']==1)
										{
										?>
								<li>
								<h2><a href="<?php url_link('sitemap.html')?>" class="staticlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></a></h2>
								</li>
								<?php		
										}
										if ($grpData['group_showxmlsitemaplink']==1 )
										{	
										?>
								<li>
								<h2><a href="<?php url_link('sitemap.xml')?>" class="staticlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></a></h2>
								</li>
								<?php		
										}
										if ($grpData['group_showhelplink']==1)
										{
										?>
								<li>
								<h2><a href="<?php url_link('help.html')?>" class="staticlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></a></h2>
								</li>
								<?php		
										}
										if ($grpData['group_showsavedsearchlink']==1)
										{
										?>
								<li>
								<h2><a href="<?php url_link('saved-search.html')?>" class="staticlink" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></h2>
								</li>
								<?php			
										}
										?>
								</ul>
								</div>
							<div class="static_lf_bottom"></div>
							</div>
<?php
								}
								if($grpData['group_listtype'] == 'Dropdown')
								{
								?>
                                           <div class="shp_brnd_lf_con">
                                                <div class="shp_brnd_lf_top">
                                                    <div class="shp_brnd_header">
                                                    <?php
                                                    if ($grpData['group_hidename']==0) // Decide whether or not to show the groupname
                                                    {
                                                    ?>
                                                        <div class="shp_brnd_header_top"><?php echo stripslash_normal($title)?></div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <div class="shp_brnd_header_bottom"></div>
                                                    </div>
                                                    <div class="shp_brnd_select">
                                                        <select name="staticpagegroup_<?php echo $grpData['group_id']?>" onchange="handle_dropdownval_sel(this.value)">
                                                        <option value="">-- <?php echo $Captions_arr['COMMON']['SELECT']?> -- </option>
                                                        <?php
                                                        if ($grpData['group_showhomelink']==1)
														{
														?>
                                                        <option value="<?php url_link('')?>" ><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></option>
														<?
														}
                                                      
                                                        while ($row_pg = $db->fetch_array($ret_pg))
                                                        {
                                                        ?>
                                                        <option value="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title'],-1); else echo $row_pg['page_link'];?>" <?php echo ($row_pg['page_id']==$_REQUEST['page_id'])?'selected="selected"':''?>><?php echo stripslash_normal($row_pg['title'])?></option>
                                                        <?php		
                                                        }
                                                      
                                                        if ($grpData['group_showsitemaplink']==1)
														{
														?>
														<option value="<?php url_link('sitemap.html')?>" <?php echo ($_REQUEST['req']=='sitemap')?'selected="selected"':''?>><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></option>
												<?php		
														}
														if ($grpData['group_showxmlsitemaplink']==1 )
														{	
														?>
														<option value="<?php url_link('sitemap.xml')?>" ><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></option>
												<?php		
														}
														if ($grpData['group_showhelplink']==1)
														{
														?>
														<option value="<?php url_link('help.html')?>" <?php echo ($_REQUEST['req']=='site_help')?'selected="selected"':''?>><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></option>
												<?php		
														}
														if ($grpData['group_showfaqlink']==1 )
														{
														?>
														<option value="<?php url_link('faq.html')?>" <?php echo ($_REQUEST['req']=='site_faq')?'selected="selected"':''?>><?php echo stripslash_normal($Captions_arr['STATIC_PAGES']['STAT_FAQ'])?></option>
															
														<?php
														}
														if ($grpData['group_showsavedsearchlink']==1)
														{
														?>
														<option value="<?php url_link('saved-search.html')?>" <?php echo ($_REQUEST['req']=='savedsearch')?'selected="selected"':''?>><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></option>
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
			global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$ecom_themeid;
			
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
				case 'middleband':
					$cache_type		= 'comp_leftcatgroup';	
				break;
				case 'right-middle-band':
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
						if ($position == 'topband') // Case if value of position is top;
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
													 if($row_cat['category_id']==80314)
																{
																?>
																<li class="item1"><a href="#" class="category_menu" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></a>
																<?php	
																}
																else
																{
																	?>
																	<li class="item1"><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="category_menu" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><span><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></span></a>
																	
																	<?php
																}
													// Check whether there exists first level subcategories for current category
													$sql_subcat = "SELECT category_id,category_name 
																	FROM 
																		product_categories 
																	WHERE 
																		parent_id =".$row_cat['category_id']." 
																		AND sites_site_id = $ecom_siteid 
																		AND category_hide = 0 
																	ORDER BY 
																		category_name";
													$ret_subcat = $db->query($sql_subcat);
													if($db->num_rows($ret_subcat))
													{
													?>
														<!-- dropdown or flyout 1 -->
														<ul style="width:<?php echo $row_cat['category_subcat_width']?>px;">
														<?php
														while($row_subcat = $db->fetch_array($ret_subcat))
														{
															    if($row_subcat['category_id']==83050)
																{
																?>
																<li><a href="<?php url_static_page(50475,$row_subcat['category_name'],-1)?>" ><?php echo ucwords(stripslash_normal($row_subcat['category_name']));?></a></li>
																<?php	
																}
																else if($row_subcat['category_id']==83051)
																{
																?>
																<li><a href="<?php url_static_page(50586,$row_subcat['category_name'],-1)?>" ><?php echo ucwords(stripslash_normal($row_subcat['category_name']));?></a></li>
																<?php
																}
																else
																{
																	?>
																		<li><a href="<?php url_category($row_subcat['category_id'],$row_subcat['category_name'],-1)?>"><?php echo stripslashes($row_subcat['category_name'])?></a></li>
																	<?php
																}
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
											<li class="item1"><a href="http://blog.dincwear.eu/" class="category_menu" title="blog" target="_blank"><span>blog</span></a></a>

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
														<ul  id="main_navigation"> 
															<?php
																$center_point = 2;
																$cursubcat_cnt = 0;
																while ($row_cat = $db->fetch_array($ret_cat))
																{
																	?>
																	<li>
																	<h2><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="category_menu" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><span><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></span></a></h2>
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
																						category_name";
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
																			$nav_cls 	= 'nav-c'.$num_rows;
																		}
																		
																		if($cursubcat_cnt<=$center_point)
																		{
																			$align_cls	= 'align-left';
																		}	
																		if(($cursubcat_cnt==$center_point))// or ($cursubcat_cnt==($center_point+1)))
																		{
																			if($num_rows>=4)
																				$align_cls	= 'align-center4';
																			else
																				$align_cls	= 'align-center3';
																		}
																		elseif($cursubcat_cnt>$center_point)
																			$align_cls	= 'align-right';
																		$cursubcat_cnt++;
																?>
																		<div class="dropdown <?php echo $align_cls.' '.$nav_cls?>">
																<?php
																		$subcat_cnt 	= 0;
																		$subcat_maxcnt 	= 4;				
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
																						category_name";
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
						if ($position == 'middleband') // Case if value of position is bottom;
						{
							$startpnt = 0;
							$cnt = 0;
							$max_cnt = 5; 
							if(count($grp_array))
							{
								//Iterating through the group array to fetch the pages to be shown.
								foreach ($grp_array as $k=>$grpData)
								{
									$sql_cat = "SELECT a.category_id,a.category_name,parent_id,category_shortdescription,category_paid_for_longdescription,category_paid_description,category_showimageofproduct  
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
                                    	 <div class="cate_mid_cont"> 
                                           <div class="cate_mid_cont_top">
                                           <?php
													while ($row_cat = $db->fetch_array($ret_cat))
													{
															$startpnt++;
															if($cnt==0)
															{
															?>
															  <div class="sub_cate_mid_in">
															<?php
															}
															if($cnt<($max_cnt-1))
																$main_class= 'sub_cate_mid_div';
															else
																$main_class = 'sub_cate_mid_div_last';
														?>
														<div class="<?php echo $main_class?>">
														<div class="sub_cate_mid_name"><a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><span><?php echo ucwords(stripslash_normal($row_cat['category_name']));?></span></a></div>
														<div class="sub_cate_mid_img">
														<a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="" title="<?php echo stripslash_normal($row_cat['category_name'])?>">
														<?php
														if ($row_cat['category_showimageofproduct']==0) // Case to check for images directly assigned to category
														{
														if ($_REQUEST['catthumb_id'])	
															$showonly = $_REQUEST['catthumb_id'];
														else
															$showonly = 0;
															// Calling the function to get the type of image to shown for current 
															$pass_type = 'image_thumbpath';	
															// Calling the function to get the image to be shown
															$catimg_arr = get_imagelist('prodcat',$row_cat['category_id'],$pass_type,0,$showonly,1);
															if(count($catimg_arr))
															{
																$exclude_catid 	= $catimg_arr[0]['image_id']; // exclude id in case of multi images for category
																$HTML_image = '<div class="cat_main_image_a">'.show_image(url_root_image($catimg_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext','',1).'</div>';
															}
															else
															{
																// calling the function to get the default image
																$no_img = get_noimage('prodcat',$pass_type); 
																if ($no_img)
																{
																	$HTML_image = show_image($no_img,$row_cat['category_name'],$row_cat['category_name'],'','',1);
																}       
															}
														}
														else // Case of check for the first available image of any of the products under this category
														{
															// Calling the function to get the id of products under current category with image assigned to it
															$cur_prodid = find_AnyProductWithImageUnderCategory($row_cat['category_id']);
															if ($cur_prodid)// case if any product with image assigned to it under current category exists
															{
																	// Calling the function to get the type of image to shown for current 
																	$pass_type = 'image_thumbpath';	
																	// Calling the function to get the image to be shown
																	$img_arr = get_imagelist('prod',$cur_prodid,$pass_type,0,0,1);
																if(count($img_arr))
																{
																	$HTML_image = '<div class="cat_main_image_a">'.show_image(url_root_image($img_arr[0][$pass_type],1),$row_cat['category_name'],$row_cat['category_name'],'imgwraptext','',1).'</div>';
																}
																else
																{
																	// calling the function to get the default image
																	$no_img = get_noimage('prodcat',$pass_type); 
																	if ($no_img)
																	{
																		$HTML_image = show_image($no_img,$row_cat['category_name'],$row_cat['category_name'],'','',1);
																	}       
																}
															}
														}	
															echo $HTML_image;
														?>
														</a>
														</div>
														<?php
														if (trim($row_cat['category_shortdescription'])!='')
														{
															$cat_desc = nl2br(stripslash_normal($row_cat['category_shortdescription']));
														}
														if ($cat_desc!='')
														{
															$HTML_catdesc = '<div class="sub_cate_mid_des">'.$cat_desc.'</div>';
														}
															echo $HTML_catdesc;
														?>
														<div class="sub_cate_mid_more"> <a href="<?php url_category($row_cat['category_id'],$row_cat['category_name'],-1)?>" class="cate_mid_ul_a" title="<?php echo stripslash_normal($row_cat['category_name'])?>"><img src="<?php url_site_image('ca-more.gif')?>"/></a></div>
														</div>
														<?php
															$cnt++;
														   if($cnt>=$max_cnt)
														   {
															   ?>
															   </div>
															   <?php
															   $cnt=0;
														   }
													}
													if($cnt!=0 && $cnt<$max_cnt)
													{
													?>
                                                    </div>
                                                    <?php
													}
													?>
                                                     </div>
                                          <div class="cate_mid_cont_bottom"></div>
                                         </div>
										<?php		
										}
									}
								}
							}
						
						}// End of middleband
						if ($position == 'right-middle-band') // Case if value of position is top;
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
											<div class="category_lf_con">
											<div class="category_lf_top"></div>
											<div class="category_lf_mid">
											<? if($prev_grp != $grpData['catgroup_id'])
												{
												?>
												<div class="category_header">
													<div class="category_header_top"></div>
													
													<?php
													if ($grpData['catgroup_hidename']==0) // Decide whether or not to show the groupname
													{
													?>
													<div class="category_header_bottom"><?php echo stripslash_normal($title)?></div>
													<?php
													}
													?>
													</div>
													<?php 
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
											<div class="shp_brnd_header_top"></div>
                                            <?php
											if ($grpData['catgroup_hidename']==0) // Decide whether or not to show the groupname
											{
											?>
											<div class="shp_brnd_header_bottom"><?php echo stripslash_normal($title)?></div>
                                            <?php
											}
											?>
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
						}// End of right-middle-band
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
											<div class="cat_con_bottom">
												<ul >
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
			if($position=='topband1')
			{
			?>
				                
             <div class="search_inner">
            <form method="post" name="frm_quicksearch" class="frm_cls" action="<?php url_link('search.html')?>">
    	    <div class="search_input"> 
            <input name="quick_search" type="text"  class="searchinput" id="quick_search" />
            </div>
    		<div class="search_btn">
            <input name="button_submit_search" type="image" src="<?php url_site_image('search-btn.gif')?>" onclick="javascript:document.frm_quicksearch.submit()"  />
            </div>
            <input type="hidden" name="search_submit" value="search_submit" />
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
			$cust_id 					= get_session_var("ecom_login_customer");
			?>
            	<div class="top_news_con">
				  <div class="top_news_con_in">
					<?php
                    if (!$cust_id) // case customer is not logged in
                    {
                        $hide_newuser 		=  $Settings_arr['hide_newuser'];
                        $hide_forgotpass 	=  $Settings_arr['hide_forgotpass'];
                        if ($position=='topband') // Best sellers is allowed in left or right panels
                        {	
                        ?>
                          <div class="top_news_con_left"><a href="<?php url_link('custlogin.html')?>" title="<?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['LOGIN'])?>">
                                        <?=stripslash_normal($Captions_arr['CUST_LOGIN']['LOGIN'])?></a></div>
                        <?
                        }
                  }
                  else
                  {
                    if($position=='topband')
                    {
                       $Captions_arr['LOGIN_MENU'] 	= getCaptions('LOGIN_MENU');
                       $sr_arr = array('Mr.','Mrs.','Miss.','Ms.','M/s.','Dr.','Sir.','Rev.');
                       $rp_arr = array('','','','','','','','');
                        ?>
                          <div class="top_news_con_left">
                                 <div class='welcome_class'><?=stripslash_normal($Captions_arr['LOGIN_MENU']['WELCOME'])?> <span><?php echo str_replace($sr_arr,$rp_arr,get_session_var('ecom_login_customer_shortname'))?></span></div> 
                               <?php /* <div class="login_user_linkA"> <a href="<?php url_link('login_home.html')?>" title="<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_ACCOUNT'])?>"><?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_ACCOUNT'])?></a> </div>
                                <div class="login_user_linkB"> <a href="<?php url_link('logout.html')?>" class="loginlink" title="<?=stripslash_normal($Captions_arr['LOGIN_MENU']['LOGOUT'])?>"><?=stripslash_normal($Captions_arr['LOGIN_MENU']['LOGOUT'])?></a> </div>*/?>
                            </div>
                        <?php
                    }
                  }
                  ?>
                 <div class="top_news_con_mid">
					<div class="top_phone"><?php /*?>Info@dincwear<?php */?></div>	<div class="top_social"><a href="http://www.facebook.com/pages/Dincwear/269120856564541" title="Facebook" target="_blank"><img src="<?php url_site_image('soci-top_01.gif')?>"></a> <a href="https://twitter.com/Dincwear_Dance" title="Twitter" target="_blank"><img src="<?php url_site_image('soci-top_02.gif')?>"></a><?php /*?><img src="<?php url_site_image('soci-top_03.gif')?>"><img src="<?php url_site_image('soci-top_04.gif')?>"><?php */?>
					<a href="http://instagram.com/dincwear1" target="_blank" title="Instagram"><img src="<?php url_site_image('insta.png')?>" alt="Instagram" /></a>
					</div> 
				  <?//=stripslash_normal($Captions_arr['CUST_LOGIN']['CALL_US'])?>
				  </div>
				  <div class="top_news_con_right">
				  <form name="frm_newsletter" method="post" action="" class="frm_cls" onsubmit="return newsletter_validation(this)">
				  <div class="top_news_con_right_l"><input name="newsletter_email" type="text" class="top_news_con_input" id="newsletter_email" value="Newsletter Signup" onclick="javascript:document.frm_newsletter.newsletter_email.value=''" onblur="javascript:if(document.frm_newsletter.newsletter_email.value=='') document.frm_newsletter.newsletter_email.value='Newsletter SignUp'"  /> </div>
				  <div class="top_news_con_right_r"><input name="" type="image" src="<?php url_site_image('news-btn.gif')?>" /> </div>
				  <input type="hidden" name="newsletter_Submit" value="1" />
				  </form>
				   </div> 
                   </div>    
				</div>
		  <?php
			if (!$cust_id) // case customer is not logged in
			{			
			if($position=='right-middle-band_test')
				{ 
					if($Settings_arr['showcustomerlogin_as_banner']==1) // Decide whether customer login is to be displayed as banner or in normal style
					{
					?>
						<div class="login_lf_con">
						  <div class="login_lf_otr">
							<div class="signup_btn">
							  <div ><span><a href="<?php url_link('custlogin.html')?>" title="<?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['LOGIN'])?>">
								<?=stripslash_normal($Captions_arr['CUST_LOGIN']['LOGIN'])?>
								</a></span></div>
							</div>
							<div class="login_btn">
							  <div ><span><a href="<?php url_link('registration.html')?>" title="<?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['NEW_USER'])?>"><?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['SIGNUP'])?></a></span></div>
							</div>
						  </div>
						</div>
					<?php
					}
					else
					{
					$frm = uniqid('_login');
					?>
						<div class="login_con">
						<form name="frm_custlogin<?=$frm?>" id="frm_custlogin" method="post" action="" onsubmit="return validate_login(this)" class="frm_cls">
						<div class="login_top"></div>
						<div class="login_middle">
							<?
							if ($title) // Decide whether or not to show the groupname
							{
							?>
							<div class="login_header">
							<div class="login_header_top"></div>
							<div class="login_header_bottom"><?php echo stripslashes($title)?></div>
							</div>
							<?php
							}
							?>
						  <table class="logintable" border="0" cellpadding="0" cellspacing="0">
							<tbody>
							  <tr>
								<td class="logintablecontent"><?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['EMAIL'])?></td>
								<td class="logintablecontentright" align="right" valign="top"><input name="custlogin_uname" class="inputA" id="custlogin_uname" size="15" type="text" /></td>
							  </tr>
							  <tr>
								<td class="logintablecontent"><?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['PASSWORD'])?> </td>
								<td class="logintablecontentright" align="right" valign="top"><input name="custlogin_pass" class="inputA" id="custlogin_pass" size="15" type="password"></td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td class="logintablecontentright" align="right" valign="top"><input name="custologin_Submit" class="buttongray" id="custologin_Submit" value="<?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['LOGIN'])?>" type="submit">
								</td>
							  </tr>
							  <tr>
								<td colspan="2" align="right"><?php
								if($hide_newuser==0) // check whether new user link is disabled from main shop settings
								{
								?>
								<a href="<?php url_link('registration.html')?>" class="loginlink" title="<?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['NEW_USER'])?>"><img src="<?php url_site_image('cust-new.gif')?>" /></a>
								<?php
								}
								if($hide_forgotpass==0) // check whether the forgot password link is disabled from main shop settings
								{
								?>
								<a href="<?php url_link('forgotpassword.html')?>" class="loginlink" title="<?php echo stripslash_normal($Captions_arr['CUST_LOGIN']['FORGOT_PASS'])?>"><img src="<?php url_site_image('cust-password.gif')?>" /></a>
								<?php
								}
								?>
								</td>
							  </tr>
							</tbody>
						  </table>
						</div>
						<div class="login_bottom"></div>
						<input type="hidden" name="redirect_back" value="0" />
						</form>						
						</div>
<?
					}
				}
			}
			else // case of customer is logged in 
			{ 
				if($position=='topband1')
				{
					$Captions_arr['LOGIN_MENU'] 	= getCaptions('LOGIN_MENU');
				?>
                    <div class="email_otr">			
                        <div class=" login_outr">
                         <div class='welcome_class'><?=stripslash_normal($Captions_arr['LOGIN_MENU']['WELCOME'])?> <span><?php echo get_session_var('ecom_login_customer_shortname')?></span></div> 
                        </div>
                        <div class="login_link_outr">
                        <div class="login_user_linkA"> <a href="<?php url_link('login_home.html')?>" title="<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_ACCOUNT'])?>"><?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_ACCOUNT'])?></a> </div>
                        <div class="login_user_linkB"> <a href="<?php url_link('logout.html')?>" class="loginlink" title="<?=stripslash_normal($Captions_arr['LOGIN_MENU']['LOGOUT'])?>"><?=stripslash_normal($Captions_arr['LOGIN_MENU']['LOGOUT'])?></a> </div>
                        </div>
                    </div>
				<?php
				}
			 	if($position=='right-middle-band')
				{
					$Captions_arr['LOGIN_MENU'] 	= getCaptions('LOGIN_MENU');
			?>
					<div class="login_con">
					<div class="login_top"></div>
						<div class="login_middle">
							<ul class="cust_loginul">
								<li>
								<h2><a href="<?php url_link('login_home.html')?>" class="userloginmenulink">
								<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_HOME'])?>
								</a></h2>
								</li>
								<li>
								<h2><a href="<?php url_link('myprofile.html')?>" class="userloginmenulink">
								<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_PROFILE'])?>
								</a></h2>
								</li>
								<li>
								<h2><a href="<?php url_link('myenquiries.html')?>" class="userloginmenulink">
								<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_ENQUIRIES'])?>
								</a></h2>
								</li>
								<li>
								<h2><a href="<?php url_link('wishlist.html')?>" class="userloginmenulink">
								<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_WISHLIST'])?>
								</a></h2>
								</li>
							<?php	
							$myfav_module = 'mod_myfavorites';
							if(in_array($myfav_module,$inlineSiteComponents)){
							?>
								<li>
								<h2><a href="<?php url_link('myfavorites.html')?>" class="userloginmenulink">
								<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_FAVOURITE'])?>
								</a></h2>
								</li>
							<? }	
							$myaddr_module = 'mod_myaddressbook';
							if(in_array($myaddr_module,$inlineSiteComponents))
							{
							?>
								<li>
								<h2><a href="<?php url_link('myaddressbook.html')?>" class="userloginmenulink">
								<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_ADDRESSBOOK'])?>
								</a></h2>
								</li>
							<?
							}
							?>
							<li>
							<h2><a href="<?php url_link('myorders.html')?>" class="userloginmenulink">
							<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_ORDERS'])?>
							</a></h2>
							</li>
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
								<li>
								<h2><a href="<?php url_link('mydownloads.html')?>" class="userloginmenulink">
								<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_DOWNLOADS'])?>
								</a></h2>
								</li>
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
								<li>
								<h2><a href="<?php url_link('mypayonaccountpayment.html')?>" class="userloginmenulink">
								<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_PAYONACCDETAILS'])?>
								</a></h2>
								</li>
							<?php
							}
							?>
							<li>
								<h2><a href="<?php url_link('mypricepromise.html')?>" class="userloginmenulink">
								<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_PRICE_PROMISE'])?>
								</a></h2>
								</li>
							<li>
							<h2><a href="<?php url_link('logout.html')?>" class="userloginmenulink">
							<?=stripslash_normal($Captions_arr['LOGIN_MENU']['LOGOUT'])?>
							</a></h2>
							</li>
							</ul>
						</div>
					<div class="login_bottom"></div>
					</div>
<?
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
            <div class="cart_con">
            <div class="cart_btn">
					<ul class="cart_view_linkul">
					 <li> <a href="<?php url_link('enquiry.html')?>" class="cart_view_linkA" title="<?php echo stripslash_normal($Captions_arr['COMMON']['VIEW_ENQUIRY'])?>"><span><?php echo stripslash_normal($Captions_arr['COMMON']['VIEW_ENQUIRY'])?></span></a> </li>
					 <li> <a href="#" class="cart_view_linkB" onclick="handle_checkout_submit('<?php echo $ecom_hostname?>',0,'<?php echo $pass_tot?>')"  title="<?php echo stripslash_normal($Captions_arr['COMMON']['CHECK_OUT'])?>"><span><?php echo stripslash_normal($Captions_arr['COMMON']['CHECK_OUT'])?></span></a> </li>
					  <li> <a href="#" onclick="gobackto_cart('<?php echo ($protectedUrl)?base64_encode($ecom_hostname):$ecom_hostname?>')" class="cart_view_linkC" title="<?php echo stripslash_normal($Captions_arr['COMMON']['VIEW_CART'])?>"><span><?php echo stripslash_normal($Captions_arr['COMMON']['VIEW_CART'])?></span></a> </li>
					</ul>
				  </div>
				  <div class="cart_txt"><?php echo stripslash_normal($Captions_arr['COMMON']['ITEMS_IN_CART'])?>  <span><?php echo  (get_session_var('cart_total_items'))?get_session_var('cart_total_items'):0?> </span> <?php echo stripslash_normal($Captions_arr['COMMON']['CART_TOTAL'])?> <span><?php echo $cart_tot?></span></div>
				          
			</div>
			<?php
				  if($cust_id)
				  {
					  $Captions_arr['LOGIN_MENU'] = getCaptions('LOGIN_MENU');
					  ?>
				  
                                <div class="login_user_linkB"> <a href="<?php url_link('logout.html')?>"  title="<?=stripslash_normal($Captions_arr['LOGIN_MENU']['LOGOUT'])?>"><?=stripslash_normal($Captions_arr['LOGIN_MENU']['LOGOUT'])?></a> </div>
                                <div class="login_user_linkA"> <a href="<?php url_link('login_home.html')?>" title="<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_ACCOUNT'])?>"><?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_ACCOUNT'])?></a> </div>
                   <?php
			   }
                   ?>  
			<?php
			}
			if($position=='right' || $position=='left')
			{ return;
			?>
				<div class="cart_lf_con">
				  <div class="cart_lf_top"> </div>
				  <div class="cart_lf_bottom">
					<div class="cart_lf_header"><?php echo stripslash_normal($Captions_arr['COMMON']['CART_MAINHEADING'])?></div>
					<div class="cart_lf_outer">
					  <div class="cart_lf_price"><?php echo stripslash_normal($Captions_arr['COMMON']['ITEMS_IN_CART'])?> <span><?php echo  (get_session_var('cart_total_items'))?get_session_var('cart_total_items'):0?></span></div>
					  <div class="cart_lf_price"><?php echo stripslash_normal($Captions_arr['COMMON']['CART_TOTAL'])?> <span><?php echo $cart_tot?></span></div>
					</div>
					<div class="cart_lf_outerA">
					  <div class="cart_btnlf"> <a href="#" onclick="gobackto_cart('<?php echo ($protectedUrl)?base64_encode($ecom_hostname):$ecom_hostname?>')" class="cart_linklf" title="<?php echo stripslash_normal($Captions_arr['COMMON']['VIEW_CART'])?>"><span><?php echo stripslash_normal($Captions_arr['COMMON']['VIEW_CART'])?></span></a> <a href="#" class="cart_linklf" onclick="handle_checkout_submit('<?php echo $ecom_hostname?>',0,'<?php echo $pass_tot?>')"  title="<?php echo stripslash_normal($Captions_arr['COMMON']['CHECK_OUT'])?>"><span><?php echo stripslash_normal($Captions_arr['COMMON']['CHECK_OUT'])?></span></a> </div>
					  <div class="cart_lf_outerB"><a href="<?php url_link('enquiry.html')?>" class="cart_linklf_txt" title="<?php echo stripslash_normal($Captions_arr['COMMON']['VIEW_ENQUIRY'])?>"><span><?php echo stripslash_normal($Captions_arr['COMMON']['VIEW_ENQUIRY'])?></span></a></div>
					   </div>
				  </div>
				</div>
			<?php
			}
		}
		// ####################################################################################################
		// Function which holds the display logic for call back request 
		// ####################################################################################################
		function mod_callback($title)
		{return;
			global $position;
			if($position=='right-middle-band')
			{
				?>
				<div class="left_comp_callback"><a href="<?php url_link('callback.html')?>"><img src="<?php url_site_image('callback.gif')?>" alt="Call Back" title="Call back request" border="0"/></a></div>
                
				<?php	
			}	
		}
		// ####################################################################################################
		// Function which holds the display logic for best sellers
		// ####################################################################################################
		function mod_bestsellers($ret_main,$title,$display_id)
		{return;
			global $ecom_siteid,$db,$ecom_hostname,$position,$Captions_arr,$Settings_arr;
			if ($position=='right-middle-band') // Best sellers is allowed in left or right panels
			{	
				if ($db->num_rows($ret_main))
				{
				?>
					<div class="best_seller_lf_con">
					<div class="best_seller_lf_top">
					<?php
					while ($row_main = $db->fetch_array($ret_main))
					{
					?>
					<div class="best_seller_pdt">
						<div class="best_seller_pdt_left">
						<div class="best_seller_pdt_name"> <a href="<?php url_product($row_main['product_id'],$row_main['product_name'],-1)?>" title="<?php echo stripslash_normal($row_main['product_name'])?>" class="best_seller_pdt_namea"><?php echo stripslash_normal($row_main['product_name'])?></a> </div>
							<div class="best_seller_pdt_price">
							<?php
								$price_arr =  show_Price($row_main,array(),'compshelf',false,3);
								if($price_arr['discounted_price'])
									echo $price_arr['discounted_price'];
								else
									echo $price_arr['base_price']
								?>
							</div>
						</div>
					<div class="best_seller_pdt_right">
					<div class="best_seller_pdt_buy">
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
					</div>
					</div>
					<? }?>
					</div>
					<div class="best_seller_lf_bottom">
					<div class="best_seller_showall_otr"><a href="<? url_link('bestsellers'.$display_id.'.html')?>" class="best_seller_showall" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?></a></div>
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
		 return;
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
		return;
			global $db,$ecom_siteid,$ecom_hostname,$Settings_arr,$Captions_arr,$position,$Settings_arr;
			$Captions_arr['COMBO']	= getCaptions('COMBO');

			if ($position=='right-middle-band')// Combo deal is allowed only in left or right panel
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
								case 'right-middle-band':
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
		{return;
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
							if($shelfData['shelf_currentstyle']=='nor') // case of normal design layout
							{
								switch($shelfData['shelf_displaytype'])
								{
									case '3row': 
									case '3rowall': 
									?>
									<div class="spcl_shlf_lf_con">
										<div class="spcl_shlf_lf_top">
										<?php
										if ($title)
										{
										?>
										<div class="spcl_shlf_header">
										<div class="spcl_shlf_header_top"></div>
											<div class="spcl_shlf_header_bottom">
											<?php  echo $title?>
											</div>
										</div>
										<?php
										}?>
										<div class="spcl_shlf_pdt">
										<?php
										while($row_prod = $db->fetch_array($ret_prod))
										{
										?>
											<div class="spcl_shlf_pdt_outer">
											<div class="spcl_shlf_pdt_top">
											<?php
											if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
											{
											?>
												<div class="spcl_shlf_pdt_name"> <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"  title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a> </div>
											<?
											}
											if($shelfData['shelf_showimage']==1) // whether image is to be displayed
											{
											?>
											<div class="spcl_shlf_pdt_img"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>">
											<?php
														$pass_type = 'image_thumbpath';
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
											<?
											}
											if($shelfData['shelf_showprice']==1) // whether price is to be displayed
											{
											?>
											<div class="spcl_shlf_pdt_price">
											<?php $price_arr =  show_Price($row_prod,array(),'compshelf',false,4);
												if($price_arr['discounted_price'])
													echo $price_arr['discounted_price'];
												else
													echo $price_arr['base_price'];
												?>
											</div>
											<?php
											}
											?>
											</div>
											<div class="spcl_shlf_pdt_bottom"> <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['COMMON_BUY_NOW'])?></a> </div>
										</div>
										<?	
										}
										?>
										</div>
										</div>
									<div class="spcl_shlf_lf_bottom">
									<div class="spcl_shlf_showall_otr"> <a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="spcl_shlf_showall" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?></a> </div>
									</div>
									</div>
<?php	
									break;
								};	
							}	
							elseif($shelfData['shelf_currentstyle']=='sp1') // case of christmas layout
							{
								switch($shelfData['shelf_displaytype'])
								{
								case '3row': 
								case '3rowall': 
									?>
									<div class="spcl_shlfA_lf_con">
										<div class="spcl_shlfA_lf_top">
										<?php
										if ($title)
										{
										?>
											<div class="spcl_shlfA_header">
											<?php  echo $title?>
											</div>
										<?php
										}
										while($row_prod = $db->fetch_array($ret_prod))
										{
										?>
											<div class="spcl_shlfA_lf_outer">
											<div class="spcl_shlfA_pdt_top"></div>
												<div class="spcl_shlfA_pdt_bottom">
												<div class="spcl_shlfA_pdt_left">
												<?php
												
												if($shelfData['shelf_showtitle']==1)// whether title is to be displayed
												{
											    ?>
												<div class="spcl_shlfA_pdt_mid_name"><a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?=$row_prod['product_name']?>"><?=$row_prod['product_name']?></a></div>
												<?
												}
												if($shelfData['shelf_showimage']==1) // whether image is to be displayed
												{
												?>
												<div class="spcl_shlfA_pdt_mid_img"> 
												<a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>">
												<?php
													$pass_type = 'image_thumbpath';
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
												if($shelfData['shelf_showprice']==1) // whether price is to be displayed
												{
												?>
												<div class="spcl_shlfA_pdt_mid_price">
												<?php $price_arr =  show_Price($row_prod,array(),'compshelf',false,3);
															if($price_arr['discounted_price'])
																echo $price_arr['discounted_price'];
															else
																echo $price_arr['base_price'];
												?>
												</div>
												<?php
												}
												?>
												</div>
												<div class="spcl_shlfA_pdt_right"> <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>" class="spcl_shlfA_pdt_right_link"> <img src="<?php url_site_image('spcl-buy.gif')?>" border="0" /></a> </div>
												</div>
											</div>
										<?php
										}
										?>
										<div class="spcl_shlfA_showall_otr"> <a href="<?php  url_shelf_all($shelfData['shelf_id'],$shelfData['shelf_name'],-1)?>" class="spcl_shlfA_showall" title="<?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?>"><?php echo stripslash_normal($Captions_arr['COMMON']['SHOW_ALL'])?></a> </div>
										</div>
									<div class="spcl_shlfA_lf_bottom"></div>
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
		return;
			global $Captions_arr,$ecom_hostname,$vImage,$db,$ecom_siteid,$Settings_arr,$vImage,$position;
			$vimgfield = (!$Settings_arr['imageverification_req_newsletter'])?'':'newsletter_Vimg';
			$Captions_arr['NEWS_LETTER'] = getCaptions('NEWS_LETTER');
			if($position=='bottomband1' )
			{
				/*if($Settings_arr['shownewsletter_as_banner']==1) // Decide whether customer login is to be displayed as banner or in normal style
				{
					?>
						<div class="nws_div"> <a href="<?php url_link('newsletter.html')?>"><img src="<?php url_site_image('neweletter-banner.gif')?>" border="0" /></a> </div>
					<?php
				}
				else
				{*/
			?>
                      <div class="newsletter_bottom">
                        <div class="newsletter_bottom_otr">
                            <div class="newsletter_top"> 
                            <form name="frm_newsletter" method="post" action="" class="frm_cls" onsubmit="return newsletter_validation(this)">
                                <div class="newsletter_input"> <input name="newsletter_email" type="text" class="newsletter_input_in" id="newsletter_email" value="Newsletter SignUp" onclick="javascript:document.frm_newsletter.newsletter_email.value=''"  /></div>
                                <div class="newsletter_submit"> <!--<img src="<?php /*url_site_image('news-login.gif')*/?>" width="77" height="22" /> --><input type="image" src="<?php url_site_image('news-login.gif')?>"  width="77" height="22"  /></div>
                         		 <input type="hidden" name="newsletter_Submit" value="1" />
                            </form>
                            </div>
                             <?php
							/*if($Settings_arr['imageverification_req_newsletter']) // if image verification is required
							{
							?>
							<div class="rt_newsletterimg"><img src="<?php url_verification_image('includes/vimg.php?size=4&pass_vname=newsletter_Vimg&amp;bg=143 186 2')?>" border="0" alt="Image Verification"/> </div>
							<div class="rt_newsletterimg_input">
							<?php 
								// showing the textbox to enter the image verification code
								$vImage->showCodBox(1,'newsletter_Vimg','class="img_input"'); 
							?>
							</div>
							<?php
							}*/
							?>
                            <div class="newsletter_bottom_in"></div>
                        </div>
                      </div>
                      
                      
             <?php
				/*}*/
			}
		}
		// ####################################################################################################
		// Function which holds the display logic for Gift Vouchers
		// ####################################################################################################
		function mod_giftvoucher($title)
		{
		return;
			global $Captions_arr,$ecom_hostname,$db,$ecom_siteid,$Settings_arr,$vImage,$position;
			$Captions_arr['GIFT_VOUCHER'] = getCaptions('GIFT_VOUCHER');
			$vimgfield = (!$Settings_arr['imageverification_req_voucher'])?'':'buycompgiftvoucher_Vimg';
		    if($position=='right-middle-band')
			{
			?>
				
                <div class="left_comp_gift_use"><a href="<?php echo get_buyGiftVoucherURL()?>" title="<?php echo stripslash_normal($Captions_arr['GIFT_VOUCHER']['BUY_VOUCHER'])?>"><img src="<?php url_site_image('giftvoucher.gif')?>" border="0" /></a></div>
			<?php	
		    }
			elseif($position=='bottomband')
			{
			?>
				
                <div class="bottom_comp_gift_use"><a href="<?php echo get_buyGiftVoucherURL()?>" title="<?php echo stripslash_normal($Captions_arr['GIFT_VOUCHER']['BUY_VOUCHER'])?>"><img src="<?php url_site_image('giftvoucher.gif')?>" border="0" /></a></div>
                
			<?php	
		    }
		}
		// ####################################################################################################
		// Function which holds the display logic for spending giftvoucher or promotional code
		// ####################################################################################################
		function mod_spendvoucher($title)
		{ return;
			global $Captions_arr,$ecom_hostname,$db,$ecom_siteid,$Settings_arr,$vImage,$position;
			$Captions_arr['GIFT_VOUCHER'] = getCaptions('GIFT_VOUCHER');
		    if($position=='right-middle-band')
			{
			?>
				<div class="left_comp_gift_buy"><a href="http://<?php echo $ecom_hostname?>/spend_voucher.html" title="<?php echo stripslash_normal($Captions_arr['GIFT_VOUCHER']['SPEND_VOUCHER_CLICK_HERE'])?>"><img src="<?php url_site_image('spend_giftvoucher.gif')?>"  /></a></div>
                
			<?
			}
			if($position=='bottomband')
			{
			?>
				<div class="bottom_comp_gift_buy"><a href="http://<?php echo $ecom_hostname?>/spend_voucher.html" title="<?php echo stripslash_normal($Captions_arr['GIFT_VOUCHER']['SPEND_VOUCHER_CLICK_HERE'])?>"><img src="<?php url_site_image('spend_giftvoucher.gif')?>"  /></a></div>
                
			<?
			}
			?>
		<?php	
		}
		// ####################################################################################################
		// Function which holds the display logic for Survey
		// ####################################################################################################
		function mod_survey($survey_array,$title)
		{ return;
			global $Captions_arr,$ecom_hostname,$db,$ecom_siteid,$position;
			$Captions_arr['SURVEY'] = getCaptions('SURVEY');
			/* if($position=='left' || $position=='right')*/
			 if($position=='right-middle-band')
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
									<div class="survey_header"> 
                                        <div class="survey_header_t"></div>
                                        <div class="survey_header_b"><?php echo $title?></div>
									</div>
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
		{return;
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
						if ($position == 'right-middle-band') // ############## Left ##############
						{
							if ($groupData['shopbrandgroup_listtype'] == 'Menu')// case if shops are to be shown in list menu
							{
								if($position=='right-middle-band')
									$cache_type		= 'shop_left_menu';	
								else
									$cache_type		= 'shop_right_menu';	
								$cache_exists 	= false;
								$cache_required	= false;
								
							}
							elseif($groupData['shopbrandgroup_listtype'] =='Dropdown')
							{
								if($position=='right-middle-band')
									$cache_type		= 'shop_left_dropdown';	
								else
									$cache_type		= 'shop_right_dropdown';
								$cache_exists 	= false;
								$cache_required	= false;	
							}
							elseif($groupData['shopbrandgroup_listtype'] =='Header')
							{
								if($position=='right-middle-band')
									$cache_type		= 'shop_left_header';	
								else
									$cache_type		= 'shop_right_header';
								$cache_exists 	= false;
								$cache_required	= false;
							}
						}
						elseif($position == 'middleband')
						{
							$cache_type		= 'shop_top_menu';
							
							$cache_exists 	= false;
							$cache_required	= false;
						}
						elseif($position == 'bottomband1')
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
								if ($position == 'right-middle-band') // ############## Left ##############
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
												<div class="shp_brnd_header_top"> </div>
												<div class="shp_brnd_header_bottom"><?php echo stripslash_normal($title)?></div>
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
											<div class="shp_brnd_header_top"></div>
											<div class="shp_brnd_header_bottom"><?php echo $title?></div>
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
									elseif($groupData['shopbrandgroup_listtype'] =='Header1')
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
								elseif($position == 'middleband')
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
                                <div class="shp_brnd_otr">   
                                <div class="shp_brnd_con">
                                <div class="shp_brnd_mid">
                                <div class="shp_brnd_thumbimg_con">
                                  <div class="shp_brnd_nav"><a href="#" onmouseover="scrollDivRight('<?=$divid?>')" onmouseout="stopMe()" ><img src="<?php url_site_image('shop-arrow-l.gif')?>"></a></div>
                                  <div id="<?=$divid?>" class="shp_brnd_thumbimg_inner">
                                    <div id="shp_brnd_thumb" style="width: <?php echo $div_width?>px;">
                                    <?=$HTML_Content?>												
                                    </div>
                                  </div>
                                  <div class="shp_brnd_nav"><a href="#" onmouseover="scrollDivLeft('<?=$divid?>',<?php echo ($cnts*90)?>)" onmouseout="stopMe()" o=""><img src="<?php url_site_image('shop-arrow-r.gif')?>"></a>
                                  </div>
                                </div>
                                </div>
                                </div>
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
								elseif($position == 'bottomband1')
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
                                <div class="bottom_shop_con">
                                <div class="bottom_shop_top"></div>
                                <div class="bottom_shop_mid">
                                <?=$HTML_Content?>
                                </div>
                                <div class="bottom_shop_bottom"></div>
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
		{return;
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
			$pass_type = 'image_thumbpath';	
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
		return;
			global $Captions_arr,$ecom_siteid,$db,$Settings_arr,$position;
		   	$frm_name = uniqid('recent_');
			if($position=='left' || $position=='right')
			{
			?>
				<div class="recnt_lf_con">
				<form name="<? echo $frm_name?>" id="<? echo $frm_name?>" action="" method="post">
				<input type="hidden" name="remove_recent" id="remove_recent" value="remove_recent" />
				<div class="recnt_lf_top">
				<?php
				if($title)
				{
				?>
				  <div class="recnt_header"><?php echo $title ?></div>
				<? 
				}
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
				  				<div class="recnt_pdt">
					<?php 
								if (!$Settings_arr['recentlyviewed_hide_image'])
								{
					?>
									<div class="recnt_pdt_img"> <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>" title="<?php echo stripslash_normal($row_prod['product_name'])?>">
					  <?php
									// Calling the function to get the type of image to shown for current 
									$pass_type = 'image_iconpath';
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
					  				</a></div>
									<div class="recnt_pdt_name"> <a href="<?php url_product($row_prod['product_id'],$row_prod['product_name'],-1)?>"  title="<?php echo stripslash_normal($row_prod['product_name'])?>"><?php echo stripslash_normal($row_prod['product_name'])?></a> </div>
					<?
								}
					?>
				  			</div>
				  <?php
							}
						}
					}	
					?>
				  	<a href="#"  title="<?php echo stripslash_normal($Captions_arr['COMMON']['COMON_RECENT'])?>"  onclick="if(confirm('Are You sure You want to clear the recent product list')){ document.<?=$frm_name?>.submit()};" class="recent_view-showall"><?php echo stripslash_normal($Captions_arr['COMMON']['COMON_RECENT'])?></a> </div>
					<div class="recnt_lf_bottom"></div>
					</form>					
					</div>
					
<?php 
				}
		}
		// ####################################################################################################
		// Function which holds the display logic for adverts
		// ####################################################################################################
		function mod_adverts($advert_arr,$title)
		{
			global $ecom_siteid,$db,$position,$Settings_arr;
			            switch($position)
						{
							case 'topband':
								$cache_type		= 'comp_leftadvert';	
							break;
							case 'right-middle-band':
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
			if ($position=='topband') // show the advert only if position is topband
			{ 
				foreach ($advert_arr as $d=>$k)
					{
						// Do the following only if caching is not enabled or cache does not exists
						if ($cache_exists==false)
						{
							if($cache_required)// if caching is required start recording the output
							{
								ob_start();
							}
						switch ($k['advert_type'])
						{
							case 'TXT':
								$path = $k['advert_source'];
							?>
                            
                              <div class="home_cont_text"> 
                              <div class="advert_content_scroll">
      							<?php echo stripslash_normal($path); ?>
      
       						 <!--<img src="<?php /*url_site_image('main.jpg')*/?>" width="930" height="408" />-->
        					</div>
                            </div>
								
  							<?php
							break;
							case 'ROTATE':   // case if ad rotate images are set
							?>
                              <div class="home_cont">
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
								?>
 								</div>
  <?php	
							break;
							case 'PATH':
							?>
                            <div class="home_cont">
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
							<?php
							break;
							case 'IMG':
								$path = url_root_image('nor/img/'.$k['advert_source'],1);
								$link = $k['advert_link'];
								if ($link!='')
								{
							?>
  								<a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">
  							<?php
								}
							?>
  								<div class="home_cont"> <img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo stripslashes($k['advert_title'])?>" border="0" /> </div>
  							<?php		
								if ($link!='')
								{
							?>
 								 </a>
  							<?php		
								}
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
			elseif ($position=='right-middle-band') // show the advert only if position is bottomband
			{
				foreach ($advert_arr as $d=>$k)
					{
					 // Do the following only if caching is not enabled or cache does not exists
						if ($cache_exists==false)
							{
								if($cache_required)// if caching is required start recording the output
								{
									ob_start();
								}
							switch ($k['advert_type'])
								{
									case 'TXT':
										$path = $k['advert_source'];
									?>
									
									  <div class="left_comp_multybuy">
										<?php echo stripslash_normal($path); ?>    						
									</div>
										
									<?php
									break;
									case 'IMG':
									$path = url_root_image('nor/img/'.$k['advert_source'],1);
									$link = $k['advert_link'];
									if ($link!='')
									{
									?>
									<a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">
									<?php
									}
									?>
									 <div class="left_comp_multybuy"> <img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo stripslashes($k['advert_title'])?>" border="0" /> </div>
									<?php		
									if ($link!='')
									{
									?>
									</a>
									<?php		
									}
									break;
							case 'PATH':
							?>
                            <div class="left_comp_multybuy">
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
							<?php
							break;							
							case 'ROTATE':   // case if ad rotate images are set
							?>
                              <div class="left_comp_multybuy">
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
								?>
 								</div>
  <?php	
							break;
									
								}
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
			elseif ($position=='left-middle-band') // show the advert only if position is bottomband
			{
				foreach ($advert_arr as $d=>$k)
					{
					 // Do the following only if caching is not enabled or cache does not exists
						if ($cache_exists==false)
							{
								if($cache_required)// if caching is required start recording the output
								{
									ob_start();
								}
							switch ($k['advert_type'])
								{
									case 'TXT':
										$path = $k['advert_source'];
									?>
									
									  <div class="leftmiddle_comp_multybuy">
										<?php echo stripslash_normal($path); ?>    						
									</div>
										
									<?php
									break;
									
								}
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
			elseif ($position=='bottomband') // show the advert only if position is bottomband
			{
				foreach ($advert_arr as $d=>$k)
					{
					// Do the following only if caching is not enabled or cache does not exists
						switch ($k['advert_type'])
						{
							case 'TXT':
								$path = $k['advert_source'];
							?>
                            
                             <div class="home_cont_text">
      							<?php echo stripslash_normal($path); ?>
        					</div>
								
  							<?php
							break;
							case 'IMG':
									$path = url_root_image('nor/img/'.$k['advert_source'],1);
									$link = $k['advert_link'];
									if ($link!='')
									{
									?>
									<a href="<?php echo $link?>" title="<?php echo stripslashes($k['advert_title'])?>" target="<?=$k['advert_target']?>">
									<?php
									}
									?>
									 <div class="bottom_comp_multybuy"> <img src="<?php echo $path?>" alt="<?php echo stripslashes($k['advert_title'])?>" title="<?php echo stripslashes($k['advert_title'])?>" border="0" /> </div>
									<?php		
									if ($link!='')
									{
									?>
									</a>
									<?php		
									}
									break;
							case 'PATH':
							?>
                            <div class="bottom_comp_multybuy">
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
							<?php
							break;
							
						}
					}
				
			}
			
		}
		
		// ####################################################################################################
		// Function which holds the display logic for sitereviews
		// ####################################################################################################
		function mod_sitereviews($title)
		{return;
			global $ecom_siteid,$db,$position,$Settings_arr;
			if ($position=='right-middle-band') // show the advert only if position is right-middle-band
			{
		?>
			<div class="left_comp_review"><a href="<?php url_link('sitereview.html');?>"><img src="<?php url_site_image('comp3.gif')?>"  /></a></div>
		<?php	
			}
			elseif ($position=='bottomband') // show the advert only if position is right-middle-band
			{
		?>
			<div class="bottom_comp_review"><a href="<?php url_link('sitereview.html');?>"><img src="<?php url_site_image('comp3.gif')?>"  /></a></div>
		<?php	
			}
		}
		
		// ####################################################################################################
		// Function which holds the display logic for Recently Viewed Products
		// ####################################################################################################
		function mod_productlist($ret_prod,$title)
		{   return;
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
		{ return;
			global $position,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr;
			if($db->num_rows($stat_query))
			{
				$row_query = $db->fetch_array($stat_query);
			}
			if($position=='right-middle-band')
			{
			?>
            <div class="left_comp_stat">
		   	<?php
            if($title)
            {
            ?>
                <div class="left_comp_stat_hdr"><?php echo $title?></div>
            <?
            }
            ?>

            <div class="left_comp_stat_no">
            <div class="left_comp_stat_span"><span><?=$row_query['site_hits']?></span><?php echo stripslash_normal($Captions_arr['COMMON']['WEB_STATISTICS'])?> </div>
            </div>

            </div>

			<?
			}
			
			if($position=='bottomband')
			{
			?>
            <div class="bottom_comp_stat">
		   	<?php
            if($title)
            {
            ?>
                <div class="bottom_comp_stat_hdr"><?php echo $title?></div>
            <?
            }
            ?>

            <div class="bottom_comp_stat_no">
            <div class="bottom_comp_stat_span"><span><?=$row_query['site_hits']?></span><?php echo stripslash_normal($Captions_arr['COMMON']['WEB_STATISTICS'])?> </div>
            </div>

            </div>

			<?
			}
		}
		function mod_ssl($title)
        { return;
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
			<tr>
			  <td colspan="3" class="userloginmenuytop" >
			  	<ul class="userloginmenuytopul">
				<li>
					<h2>
					<a href="<?php url_link('logout.html')?>" class="userloginmenuytoplink">
					<?=stripslash_normal($Captions_arr['LOGIN_MENU']['LOGOUT'])?>
					</a>
					</h2>
				  </li>
				   <li>
					<h2>
					<a href="<?php url_link('mypricepromise.html')?>" class="userloginmenuytoplink">
					  <?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_PRICE_PROMISE'])?>
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
				  <? 
				  	$myaddr_module = 'mod_myaddressbook';
					if(in_array($myaddr_module,$inlineSiteComponents))
					{
				?>
					  	<li>
							<h2>
							<a href="<?php url_link('myaddressbook.html')?>" class="userloginmenuytoplink">
							<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_ADDRESSBOOK'])?>
							</a>
							</h2>
				  		</li>
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
						  <li>
							<h2>
							<a href="<?php url_link('mypayonaccountpayment.html')?>" class="userloginmenuytoplink">
							  <?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_PAYONACCDETAILS'])?>
							</a>
							</h2>
						  </li>
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
					  <li>
						<h2>
						<a href="<?php url_link('mydownloads.html')?>" class="userloginmenuytoplink">
						  <?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_DOWNLOADS'])?>
						</a>
						</h2>
					  </li>
				  <?php
					}
					$myfav_module = 'mod_myfavorites';
					if(in_array($myfav_module,$inlineSiteComponents))
					{
				?>
					  <li>
						<h2><a href="<?php url_link('myfavorites.html')?>" class="userloginmenuytoplink">
						  <?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_FAVOURITE'])?>
						  </a></h2>
					  </li>
				  <?php
					}
					?>
				  <li>
					<h2>
					<a href="<?php url_link('wishlist.html')?>" class="userloginmenuytoplink">
					  <?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_WISHLIST'])?>
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
					<h2><a href="<?php url_link('myprofile.html')?>" class="userloginmenuytoplink">
					  <?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_PROFILE'])?>
					</a>
					</h2>
				  </li>
				  <li>
					<h2><a href="<?php url_link('login_home.html')?>" class="userloginmenuytoplink">
					<?=stripslash_normal($Captions_arr['LOGIN_MENU']['MY_HOME'])?>
					</a>
					</h2>
				  </li>
				  <li>
					<h2>
					<span class='logged_in_li'>
					&nbsp;
					<?=stripslash_normal($Captions_arr['LOGIN_MENU']['YOU_LOGGED IN'])?>
					</span>
					</h2>
				  </li>
				</ul>
				</td>
			</tr>
<?php
		}
		
		/* Function to show the currency selector */
		function mod_currencyselector($title)
		{ return;
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
		{ return;
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
		{ return;
			global $ecom_siteid,$db,$ecom_hostname,$position,$Captions_arr,$Settings_arr;
			if ($position=='right-middle-band') // Best sellers is allowed in left or right panels
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
		 { return;
			 global $db,$ecom_siteid,$sitesel_curr,$position,$ecom_common_settings;
			 if($position =='left' or $position=='right' and ($ecom_common_settings['paytypeCode']['pay_on_account']['paytype_code']=='pay_on_account'))
			 {
		?>
				<div class="credit_banner" align="left"><a href="<?php url_link('payonaccount.html')?>"><img src="<?php url_site_image('creditbanner.gif')?>" alt="PayonAccount" title="PayonAccount" border="0" /></a></div>
<?
			}
		 }
	};
?>
