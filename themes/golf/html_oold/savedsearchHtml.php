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
		function Show_SavedsearchList($sql_search,$numcount)
		{
			global $ecom_siteid,$db,$ecom_hostname,$Captions_arr;
			$prodperpage = 99;
			
			$Captions_arr['SEARCH'] = getCaptions('SEARCH');
			////////////////////////////////For paging///////////////////////////////////////////
			$pg_variable	= 'search_pg';
	        if ($_REQUEST['req']!='')// LIMIT for products is applied only if not displayed in home page
			{
				$start_var 		= prepare_paging($_REQUEST[$pg_variable],$prodperpage,$numcount);
			}	
			/////////////////////////////////////////////////////////////////////////////////////	
			
			if ($numcount>0) // If record exists
			{	
				$sql_search .= " ORDER BY search_id ASC LIMIT ".$start_var['startrec'].", ".$prodperpage." ";
				$ret_search = $db->query($sql_search);
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
			<tr>
			<td align="left" valign="middle" ><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php  echo $Captions_arr['SEARCH']['SEARCH_SAVED'];?><?php //echo class="pro_de_shelfBheader" $Captions_arr['COMBO']['COMBO_TREEMENU_TITLE']?></div></td>
			</tr>
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
					<h2><a href="<?php url_savedsearch($row_search['search_id'],$row_search['search_keyword'])?>" title="<?=$row_search['search_keyword']?>" class="link"><?=$row_search['search_keyword']?></a></h2> </td>
			<?php
					if($count_sav>$count_max)
					{
					echo "</tr><tr>";
					$count_sav=1;
					}
				}
				?>
				</tr>
				<?	
					if($numcount>0)
					{
					?>
					<tr>
						<td>&nbsp;</td>
					</tr>	
					<tr>
						<td colspan="3" class="pagingcontainertd" align="center">
					<?php
					  	$path = "saved-search.html";
					  	$query_string = "";
						paging_footer($path,$query_string,$numcount,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Keywords',$pageclass_arr); 	
				 	?> 
				 		</td>
					</tr>
					<?
					 } 
					?>				
				</table>
			
			</td>
			</tr>
			<tr>
			<td align="left" valign="middle" >&nbsp;</td>
			</tr>
			</table>
<?php
			} else {
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
			<tr>
			<td align="left" valign="middle"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php  echo $Captions_arr['SEARCH']['SEARCH_SAVED'];?><?php //echo class="pro_de_shelfBheader" $Captions_arr['COMBO']['COMBO_TREEMENU_TITLE']?></div></td>
			</tr>
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
			<?php
			}
		}
	};
	?> 