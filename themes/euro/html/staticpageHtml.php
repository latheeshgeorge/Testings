<?php
	/*############################################################################
	# Script Name 	: staticpageHtml.php
	# Description 	: Page which holds the display logic for Static pages
	# Coded by 		: Anu
	# Created on	: 22-Feb-2008
	# Modified by	: ANU
	# Modified On	: 22-Feb-2008
	##########################################################################*/
	class static_Html
	{
		// Defining function to show the selected static pages
		function Show_StaticPage($row_statpage)
		{
			global 	$Captions_arr,$ecom_siteid,$db,$ecom_hostname,$ecom_themename,
					$ecom_themeid,$default_layout,$inlineSiteComponents,$Settings_arr;
			// ** Fetch any captions for product details page
			$Captions_arr['STATIC_PAGES'] 	= getCaptions('STATIC_PAGES');
			
			// ** Fetch the product details
			//$row_statpage	= $db->fetch_array($ret_statpage);
			
			// ** Check to see whether current user is logged in 
			$cust_id 	= get_session_var("ecom_login_customer");
			
		?>
		<div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?=stripslashes($row_statpage['title'])?></div>
		<div class="shelf_main_con" >
		<div class="shelf_top"><?=stripslashes($row_statpage['title'])?></div>
		<div class="shelf_mid">
		<form method="post" name="frm_staticpage" id="frm_staticpage" action="" class="frm_cls">
		<input type="hidden" name="fpurpose" value="" />
		<input type="hidden" name="fproduct_id" value="<?php echo $_REQUEST['product_id']?>" />
			<table border="0" cellpadding="0" cellspacing="0" class="staticpagetable">
			<?php /*?><tr>
			  <td colspan="2" align="left" class="staticpageheader"><?php echo stripslashes($row_statpage['title'])?></td>
			  </tr><?php */?>
			<tr>
			  <td valign="top" class="staticpagecontent">
			  <?php
			  if($row_statpage['allow_auto_linker'] == 1) {
				//echo 't';
				echo auto_linker(stripslashes($row_statpage['content']));
			} else {
				echo stripslashes($row_statpage['content']);
			} 
			  //echo stripslashes($row_statpage['content'])
			  ?>
			  </td>
			  
			</tr>
			
		  </table>
 		</form>
		</div>
		<div class="shelf_bottom"></div>	
		</div>
		
				<table border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
				<tr>
				<td align="center">
				<?php 
				$sql_inline = "SELECT display_order,display_component_id,display_id,display_title 
				FROM 
					display_settings a,features b 
				WHERE 
					a.sites_site_id=$ecom_siteid 
					AND a.display_position='middle' 
					AND b.feature_allowedinmiddlesection = 1  
					AND layout_code='".$default_layout."' 
					AND a.features_feature_id=b.feature_id 
					AND b.feature_modulename='mod_shelf' 
				ORDER BY 
						display_order 
						ASC";
			$ret_inline = $db->query($sql_inline); // to dispplay the advert for the category page
			if ($db->num_rows($ret_inline))
			{
				while ($row_inline = $db->fetch_array($ret_inline))
				{
					//$modname 			= $row_inline['feature_modulename'];
					$body_dispcompid	= $row_inline['display_component_id'];
					$body_dispid		= $row_inline['display_id'];
					$body_title			= $row_inline['display_title'];
					include ("includes/base_files/shelf.php");
				}
			}
		
				?>
				</td>
				</tr>	
				</table>
				
	<?php	
				$sql_inline = "SELECT display_order,display_component_id,display_id,display_title 
									FROM 
										display_settings a,features b 
									WHERE 
										a.sites_site_id=$ecom_siteid 
										AND a.display_position='middle' 
										AND b.feature_allowedinmiddlesection = 1  
										AND layout_code='".$default_layout."' 
										AND a.features_feature_id=b.feature_id 
										AND b.feature_modulename='mod_staticgroup' 
									ORDER BY 
											display_order 
											ASC";
				$ret_inline = $db->query($sql_inline);
				if ($db->num_rows($ret_inline))
				{
					while ($row_inline = $db->fetch_array($ret_inline))
					{
						$body_dispcompid	= $row_inline['display_component_id'];
						$body_dispid			= $row_inline['display_id'];
						$body_title				= $row_inline['display_title'];
						include ("includes/base_files/homepage_static_group.php");
					}
				}	
	
		}
		function Show_HomeStaticPage($title,$ret_grp) 
			{ 
				global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$Captions_arr,$inlineSiteComponents;
				if ($db->num_rows($ret_grp))
				{
					$grpData = $db->fetch_array($ret_grp);
					$sql_pg = "SELECT a.page_id,a.title,a.content,a.page_type,a.page_link,page_link_newwindow    
											FROM 
												static_pages a,static_pagegroup_static_page_map b 
											WHERE
												a.sites_site_id = $ecom_siteid 
												AND b.static_pagegroup_group_id=".$grpData['group_id']." 
												AND a.page_id=b.static_pages_page_id 
												AND static_pages_hide = 0  
												AND hide = 0  
												AND a.pname <> 'Home' 
											ORDER BY 
												static_pages_order ASC";
		
					$ret_pg = $db->query($sql_pg);
				?>
						<div class="sta_rt_lnk" > 
						<?php
							if($title!='')
							{
						?>
						<div class="sta_rt_top"><?php echo $title?></div>
						<?php
							}
						?>
						<div class="sta_rt_mid">
						<ul class="static_rt">  
						<?php
							if ($grpData['group_showhomelink']==1)
							{
								
						?>
									<li><h5> <a href="<?php url_link('')?>" class="static_lnk" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HOME']?></a> </h5></li>
						<?php
							}
							if($db->num_rows($ret_pg))
							{
								while ($row_pg = $db->fetch_array($ret_pg))
								{
									$target = ($row_pg['page_link_newwindow']==1 and $row_pg['page_type']!='Page')?'target="_blank"':'';
						?>
									<li><h5> <a href="<?php if ($row_pg['page_type']=='Page') url_static_page($row_pg['page_id'],$row_pg['title']); else echo $row_pg['page_link'];?>" class="static_lnk" title="<?php echo stripslashes($row_pg['title'])?>" <?php echo $target?>><?php echo stripslashes($row_pg['title']);?></a></h5></li>
						<?php
								}
							}	
							if ($grpData['group_showsavedsearchlink']==1)
								{
					?>							
										<li><h5> <a href="<?php url_link('saved-search.html')?>" class="static_lnk" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SAVEDSEARCH']?></a></h5></li>
					<?php		
								}
								if ($grpData['group_showsitemaplink']==1 )
								{
					?>							
										<li><h5> <a href="<?php url_link('sitemap.html')?>" class="static_lnk" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_SITEMAP']?></a></h5></li>
					<?php		
								}
								if ($grpData['group_showxmlsitemaplink']==1 )
								{
								?>
									<li><h5> <a href="<?php url_link('sitemap.xml')?>" class="static_lnk" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_XMLSITEMAP']?></a></h5></li>
								<?php	
								}
								if ($grpData['group_showfaqlink']==1 )
								{
				?>		
									<li><h2> <a href="<?php url_link('faq.html')?>" class="static_lnk" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_FAQ']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_FAQ']?></a></h5></li>
				<?php
								}
								if ($grpData['group_showhelplink']==1 )
								{
				?>		
									<li><h5> <a href="<?php url_link('help.html')?>" class="static_lnk" title="<?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?>"><?php echo $Captions_arr['STATIC_PAGES']['STAT_HELP']?></a></h5></li>
				<?php
								}
						?>	
							</ul>  
						</div>
						<div class="sta_rt_bottom"></div>
					  </div>
				<?php
					}
			}
	};	
?>