<?php
	/*#################################################################
	# Script Name 	: propertynewsHtml.php
	# Description 	: Page which holds the display logic for property news details
	# Coded by 		: LG
	# Created on	: 20-Feb-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	class savedsearch_Html
	{
		// Defining function to show the static page content
		function Show_SavedsearchList($sql_search)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Captions_arr,$Settings_arr;
			$prodperpage = $Settings_arr['saved_search_display_cnt'];
			
			$Captions_arr['SEARCH'] = getCaptions('SEARCH');
			
			$HTML_img = $HTML_alert = $HTML_treemenu=$HTML_topdesc=$HTML_bottomdesc='';
			
									$HTML_treemenu = '	<div class="row breadcrumbs">
												<div class="container">
												<div class="container-tree">
												<ul>
												<li><a href="'.url_link('',1).'" title="'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
												<li> → '.stripslash_normal($Captions_arr['SEARCH']['SEARCH_SAVED']).'</li>

												</ul>
												</div>
												</div></div>';	
							echo $HTML_treemenu;	
			$sql_search .= " ORDER BY search_count DESC LIMIT ".$prodperpage." ";
			$ret_search = $db->query($sql_search);
			$numcount 	= $db->num_rows($ret_search);
			$sql 				= "SELECT general_savedsearch_topcontent,general_savedsearch_bottomcontent FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
		        $res_admin 			= $db->query($sql);
		        $fetch_arr_admin 	= $db->fetch_array($res_admin);
				if($fetch_arr_admin['general_savedsearch_topcontent']!='')
				{
						$HTML_topdesc .= '<div class="cate_content_bottom" >'.$fetch_arr_admin['general_savedsearch_topcontent'].'
						</div>';
				}	
				if($fetch_arr_admin['general_savedsearch_bottomcontent']!='')
				{				
				$HTML_bottomdesc .= '<div class="cate_content_bottom" >'.$fetch_arr_admin['general_savedsearch_bottomcontent'].'
									</div>';
				}					
			if ($numcount>0) // If record exists
			{	
				//echo $HTML_treemenu;
				echo $HTML_topdesc;
		?>
			<div class="container" >
			<div class="container" >
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
				<tr>
				<td align="left" valign="middle">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
					<?php
					$count_max = 3;
					$count_sav=1;
					while ($row_search = $db->fetch_array($ret_search))
					{
					  	$count_sav++;						
					?>					
						<td valign="middle" align="left" class="saved_keywords"> 
						<a href="<?php url_savedsearch($row_search['search_id'],$row_search['search_keyword'])?>" title="<?=$row_search['search_keyword']?>" class="link"><?=$row_search['search_keyword']?></a>
						</td>
					<?php
						if($count_sav>$count_max)
						{
							echo "</tr><tr>";
							$count_sav=1;
						}
					}
					?>
					</tr>
					</table>
				</td>
				</tr>
				<tr>
				<td align="left" valign="middle" >&nbsp;</td>
				</tr>
				</table>
				</div>
				</div>
<?php
echo $HTML_bottomdesc;
			}
			else
			{
				
					//echo $HTML_treemenu;
			?>
						<div class="container" >
						<div class="container" >

				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
				<tr>
				<td align="left" valign="middle">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">				
						<tr>
							<td align="center" valign="middle" class="staticpagecontent" >No Keyword Found</td>
						</tr>
					</table>
				</td>
				</tr>
				</table>
				</div> 
				</div> 
			<?php
			}
		}
	};
	?> 
