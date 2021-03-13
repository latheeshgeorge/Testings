<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="78%" valign="top" align="left">
	<table width="100%" border="0" cellspacing="2" cellpadding="2" class="dragtableclassinner">
      <tr>
        <td height="50" colspan="2" align="center" class="topcolumn">
		Header Image
		</td>
      </tr>
      <tr>
        <td colspan="2" align="left" class="topcolumn">
		<?php
				$cur_pos = 'top';
				$sql_pos = "SELECT display_id,a.features_feature_id,b.feature_name,display_position,display_component_id FROM display_settings a,features b WHERE a.sites_site_id=$ecom_siteid AND 
				display_position='$cur_pos' AND layout_code = '$layoutcode' AND a.features_feature_id=b.feature_id ORDER BY display_order";
				$ret_pos = $db->query($sql_pos);
			?>
				<div dropobj="0" class="DragContainertop" id="<?php echo $cur_pos?>" overclass="OverDragContainer">
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
					<td width="96%" class="pos_header"><strong>Position: <?php echo $cur_pos?></strong></td>
					<td width="4%">
					<?php
						if ($db->num_rows($ret_pos))
						{
					?>	
					  <a href="javascript:edit_titles('<?php echo $cur_pos?>')" title="edit titles"><img src="images/edit.gif" alt="Edit Titles" border="0" /></a><?php
						}
					?>					</td>
					</tr>
					</table>
					<br />
				<?php
						if ($db->num_rows($ret_pos))
						{
							while ($row_pos = $db->fetch_array($ret_pos))
							{
								$showname = getComponenttitle($row_pos['features_feature_id'],$row_pos['display_component_id']);
								// Find the original name of the feature
								$sql_feat = "SELECT feature_name FROM features WHERE feature_id=".$row_pos['features_feature_id'];
								$ret_feat = $db->query($sql_feat);
								if ($db->num_rows($ret_feat))
								{
									$row_feat = $db->fetch_array($ret_feat);
									$showfeatname = stripslashes($row_feat['feature_name']);
								}
								$uniq = uniqid('');
				?>		
								<div dragobj="0"  id="<?php echo $row_pos['features_feature_id']?>_<?php echo $row_pos['display_component_id']?>_<?php echo $uniq?>_<?php echo $row_pos['display_id']?>" overclass="OverDragBox" dragclass="DragDragBox" title="<?php echo $showfeatname?>">
								<?php echo $showname?>
								<input type="hidden" name="txt_<?php echo $pos_arr[$i];?>_<?php echo $row_pos['display_id']?>" value="<?php echo $row_pos['display_id']?>" />
								</div>
				<?php
							}
						}	
				?>						
				</div>		</td>
      </tr>
      <tr>
        <td width="31%" height="100" align="left" valign="top" class="leftcolumn">
		<?php
				$cur_pos = 'left';
				//$sql_pos = "SELECT display_id,b.menu_id,b.menu_title,display_position,display_title FROM display_settings a,site_menu b WHERE a.site_id=$ecom_siteid AND 
				//display_position='$cur_pos' AND layout_code = '$layoutcode' AND a.menu_id=b.menu_id ORDER BY display_order";
				$sql_pos = "SELECT display_id,a.features_feature_id,b.feature_name,display_position,display_component_id FROM display_settings a,features b WHERE a.sites_site_id=$ecom_siteid AND 
				display_position='$cur_pos' AND layout_code = '$layoutcode' AND a.features_feature_id=b.feature_id ORDER BY display_order";
				
				$ret_pos = $db->query($sql_pos);
			?>
				<div dropobj="0" class="DragContainer" id="<?php echo $cur_pos?>" overclass="OverDragContainer">
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
					<td width="96%" class="pos_header"><strong>Position: <?php echo $cur_pos?></strong></td>
					<td width="4%">
					<?php
						if ($db->num_rows($ret_pos))
						{
					?>	
					  <a href="javascript:edit_titles('<?php echo $cur_pos?>')" title="edit titles"><img src="images/edit.gif" alt="Edit Titles" border="0" /></a><?php
						}
					?>					</td>
					</tr>
					</table>
					<br />
					<?php
								if ($db->num_rows($ret_pos))
								{
									while ($row_pos = $db->fetch_array($ret_pos))
									{
										$showname = getComponenttitle($row_pos['features_feature_id'],$row_pos['display_component_id']);
										// Find the original name of the feature
										$sql_feat = "SELECT feature_name FROM features WHERE feature_id=".$row_pos['features_feature_id'];
										$ret_feat = $db->query($sql_feat);
										if ($db->num_rows($ret_feat))
										{
											$row_feat = $db->fetch_array($ret_feat);
											$showfeatname = stripslashes($row_feat['feature_name']);
										}
										$uniq = uniqid('');
					?>		
										<div dragobj="0"  id="<?php echo $row_pos['features_feature_id']?>_<?php echo $row_pos['display_component_id']?>_<?php echo $uniq?>_<?php echo $row_pos['display_id']?>" overclass="OverDragBox" dragclass="DragDragBox" title="<?php echo $showfeatname?>">
										<?php echo $showname?>
										<input type="hidden" name="txt_<?php echo $pos_arr[$i];?>_<?php echo $row_pos['display_id']?>" value="<?php echo $row_pos['display_id']?>" />
										</div>
					<?php
									}
								}	
					?>							
				</div>		</td>
        <td width="42%" valign="top" align="left" class="middlecolumn">
			<?php
				$cur_pos = 'middle';
				//$sql_pos = "SELECT display_id,b.menu_id,b.menu_title,display_position,display_title FROM display_settings a,site_menu b WHERE a.site_id=$ecom_siteid AND 
				//display_position='$cur_pos' AND layout_code = '$layoutcode' AND a.menu_id=b.menu_id ORDER BY display_order";
				$sql_pos = "SELECT display_id,a.features_feature_id,b.feature_name,display_position,display_component_id FROM display_settings a,features b WHERE a.sites_site_id=$ecom_siteid AND 
				display_position='$cur_pos' AND layout_code = '$layoutcode' AND a.features_feature_id=b.feature_id ORDER BY display_order";
				$ret_pos = $db->query($sql_pos);
			?>
					<div dropobj="0" class="DragContainer" id="<?php echo $cur_pos?>" overclass="OverDragContainer">
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
						<td width="96%" class="pos_header"><strong>Position: <?php echo $cur_pos?></strong></td>
						<td width="4%">
						<?php
							if ($db->num_rows($ret_pos))
							{
						?>	
						  <a href="javascript:edit_titles('<?php echo $cur_pos?>')" title="edit titles"><img src="images/layout_edit.gif" alt="Edit Titles" border="0" /></a><?php
							}
						?>					</td>
						</tr>
						</table>
						<br />
				<?php
						if ($db->num_rows($ret_pos))
						{
							while ($row_pos = $db->fetch_array($ret_pos))
							{
								$showname = getComponenttitle($row_pos['features_feature_id'],$row_pos['display_component_id']);
								// Find the original name of the feature
								$sql_feat = "SELECT feature_name FROM features WHERE feature_id=".$row_pos['features_feature_id'];
								$ret_feat = $db->query($sql_feat);
								if ($db->num_rows($ret_feat))
								{
									$row_feat = $db->fetch_array($ret_feat);
									$showfeatname = stripslashes($row_feat['feature_name']);
								}
								$uniq = uniqid('');
				?>		
								<div dragobj="0"  id="<?php echo $row_pos['features_feature_id']?>_<?php echo $row_pos['display_component_id']?>_<?php echo $uniq?>_<?php echo $row_pos['display_id']?>" overclass="OverDragBox" dragclass="DragDragBox" title="<?php echo $showfeatname?>">
								<?php echo $showname?>
								<input type="hidden" name="txt_<?php echo $pos_arr[$i];?>_<?php echo $row_pos['display_id']?>" value="<?php echo $row_pos['display_id']?>" />
								</div>
				<?php
							}
						}	
				?>						
					</div>
			</td>
        </tr>
      <tr>
        <td height="50" colspan="2" align="left" valign="middle" class="rightcolumn">
		<?php
							$cur_pos = 'bottom';
							//$sql_pos = "SELECT display_id,b.menu_id,b.menu_title,display_position,display_title FROM display_settings a,site_menu b WHERE a.site_id=$ecom_siteid AND 
							//display_position='$cur_pos' AND layout_code = '$layoutcode' AND a.menu_id=b.menu_id ORDER BY display_order";
							$sql_pos = "SELECT display_id,a.features_feature_id,b.feature_name,display_position,display_component_id FROM display_settings a,features b WHERE a.sites_site_id=$ecom_siteid AND 
							display_position='$cur_pos' AND layout_code = '$layoutcode' AND a.features_feature_id=b.feature_id ORDER BY display_order";
							$ret_pos = $db->query($sql_pos);
		?>
				<div dropobj="0" class="DragContainertop" id="<?php echo $cur_pos?>" overclass="OverDragContainer">
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
					<td width="96%" class="pos_header"><strong>Position: <?php echo $cur_pos?></strong></td>
					<td width="4%">
					<?php
						if ($db->num_rows($ret_pos))
						{
					?>	
					  <a href="javascript:edit_titles('<?php echo $cur_pos?>')" title="edit titles"><img src="images/edit.gif" alt="Edit Titles" border="0" /></a><?php
						}
					?>					</td>
					</tr>
					</table>
					<br />
					<?php
						if ($db->num_rows($ret_pos))
						{
							while ($row_pos = $db->fetch_array($ret_pos))
							{
								$showname = getComponenttitle($row_pos['features_feature_id'],$row_pos['display_component_id']);
								// Find the original name of the feature
								$sql_feat = "SELECT feature_name FROM features WHERE feature_id=".$row_pos['features_feature_id'];
								$ret_feat = $db->query($sql_feat);
								if ($db->num_rows($ret_feat))
								{
									$row_feat = $db->fetch_array($ret_feat);
									$showfeatname = stripslashes($row_feat['feature_name']);
								}
								$uniq = uniqid('');
					?>		
									<div dragobj="0"  id="<?php echo $row_pos['features_feature_id']?>_<?php echo $row_pos['display_component_id']?>_<?php echo $uniq?>_<?php echo $row_pos['display_id']?>" overclass="OverDragBox" dragclass="DragDragBox" title="<?php echo $showfeatname?>">
									<?php echo $showname?>
									<input type="hidden" name="txt_<?php echo $pos_arr[$i];?>_<?php echo $row_pos['display_id']?>" value="<?php echo $row_pos['display_id']?>" />
									</div>
					<?php
							}
						}	
					?>					
				</div>		</td>
      </tr>
      <tr>
        <td height="20" colspan="2" align="right" valign="middle" class="footercolumntext">Website Design and Search Engine   Optimisation from Business 1st. Copyright &copy; 2007</td>
        </tr>
    </table></td>
  </tr>
</table>
