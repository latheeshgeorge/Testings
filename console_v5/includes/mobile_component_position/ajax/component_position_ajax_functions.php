<?php
	function show_component_positions($layoutcode,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themename;
		if($ecom_themename=='')
		{
			$sql_site = "SELECT themename FROM themes a,sites b WHERE b.site_id=$ecom_siteid AND
			a.theme_id=b.themes_theme_id";
			$ret_site = $db->query($sql_site);
			if ($db->num_rows($ret_site))
			{
				$row_site 		= $db->fetch_array($ret_site);
				$ecom_themename = strtolower($row_site['themename']);
			}
			$path = '../../';
		}
		else
			$path = '../';
?>
			<table width="100%" border="0" cellspacing="2" cellpadding="2">
		  	<tr>
			<td width="19%" valign="top" align="left" class="tdcolorgraynormal" >
			<strong>Available Components</strong><br>
			<?php
				//Get the components from site_menu
				$sql_sitemenu = "SELECT a.menu_id,b.feature_name,b.feature_ordering FROM site_menu a,features b WHERE 
								a.sites_site_id=$ecom_siteid AND b.feature_displaytouser=1 AND a.features_feature_id=b.feature_id ORDER BY b.feature_ordering";
				$ret_sitemenu = $db->query($sql_sitemenu);
				?>
				<div dropobj="0" class="DragContainer" id="sitemenu" overclass="OverDragContainer">
					<?php
						while($row_sitemenu = $db->fetch_array($ret_sitemenu))
						{
					?>
							<div origclass="DragBox" dragobj="0" class="DragBox" id="<?php echo $row_sitemenu['menu_id']?>" overclass="OverDragBox" dragclass="DragDragBox">
							<?php echo stripslashes($row_sitemenu['feature_name'])?>
							</div>
					<?php
						}
					?>	
				</div>
			</td>
			<td width="81%" valign="top" align="left" class="tdcolorgraynormal">
					<?php 
						$fname = $layoutcode.".php";
						include ($path."themes/".strtolower($ecom_themename)."/html/console_templatelayout/".$fname);
					?>
			</td>
		  </tr>
			</table>
<?php	
	}
?>