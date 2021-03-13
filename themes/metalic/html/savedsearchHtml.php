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
			$sql_search .= " ORDER BY search_count DESC LIMIT ".$prodperpage." ";
			$ret_search = $db->query($sql_search);
			$numcount 	= $db->num_rows($ret_search);
			if ($numcount>0) // If record exists
			{
		?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
			<tr>
			<td align="left" valign="middle" >
			<div class="pro_det_treemenu" align="left"> <ul>
							<li><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a>>></li> <li><?=$Captions_arr['SEARCH']['SEARCH_SAVED']?></li></ul></div>
			</td>
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
					<td valign="middle" align="left"> 
					<h1><a href="<?php url_savedsearch($row_search['search_id'],$row_search['search_keyword'])?>" title="<?=$row_search['search_keyword']?>" class="link"><?=$row_search['search_keyword']?></a></h1> </td>
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
<?php
			} else {
			?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="shelfBtable">
			<tr>
			<td align="left" valign="middle" class="pro_de_shelfBheader"> Saved Search</td>
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