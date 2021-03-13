<?php
	// ###############################################################################################################
	// 				Function which holds the display logic of static pages under the page gruop to be shown when called using ajax;
	// ###############################################################################################################
    function show_static_maininfo($group_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_mobilethemeid ;
		$sql="SELECT group_name,group_hidename,group_showinall,group_showhomelink,group_showsitemaplink,group_showhelplink,group_showsavedsearchlink,
							group_hide,group_order,group_showxmlsitemaplink,group_showfaqlink,group_listtype  
					FROM 
						static_pagegroup 
					WHERE 
						sites_site_id=$ecom_siteid 
						AND group_id=".$group_id." LIMIT 1";
		$res=$db->query($sql);
		$row=$db->fetch_array($res);
		// Find the feature_id for mod_productcatgroup module from features table
			$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_staticgroup'";
			$ret_feat = $db->query($sql_feat);
			if ($db->num_rows($ret_feat))
			{
				$row_feat 	= $db->fetch_array($ret_feat);
				$feat_id	= $row_feat['feature_id'];
			}
		
		?><div class="editarea_div" >
		   <table border="0" width="100%" cellpadding="0" cellspacing="0" class="fieldtable">
		   <?php 
			if($alert)
			{			
			?>
			<tr>
			  <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
			</tr>
			<?
			}
		?>
		   <tr>
          <td width="12%" align="left" valign="middle" class="tdcolorgray" >Page Menu Name <span class="redtext">*</span> </td>
          <td width="44%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="group_name" value="<?=$row['group_name']?>"  />
            <br>
            <input type='checkbox' name="statgroup_updatewebsitelayout" value="1"> Update the title of page menu in website layout section.
          &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_WEBSITELAYOUT')?>')" onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
            </td>
          <td width="17%" align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">Hide Name </td>
          <td width="27%" align="left" valign="middle" class="tdcolorgray"> <input type="checkbox" name="group_hidename" value="1" <? if($row['group_hidename']==1) echo "checked";?> />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_HIDENAME')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>  </td>
        </tr>
		 <tr>
          <td width="12%" align="left" valign="middle" class="tdcolorgray" >Display Location  <span class="redtext">*</span> </td>
          <td width="44%" align="left" valign="middle" class="tdcolorgray">
		   <?php
		  	if($ecom_mobilethemeid==0)
			{
			// Find the display settings details for this category group
			 $sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$group_id AND 
							features_feature_id=$feat_id AND a.themes_layouts_layout_id=b.layout_id ";
			 $ret_disp = $db->query($sql_disp);
			 $disp_array		= array();
				if ($db->num_rows($ret_disp))
				{
					while ($row_disp = $db->fetch_array($ret_disp))
					{	
						$layoutid				= $row_disp['themes_layouts_layout_id'];
						$layoutcodechk			= $row_disp['layout_code'];

						
						$layoutcode				= $row_disp['layout_code'];
						$layoutname				= stripslashes($row_disp['layout_name']);
						$disp_id				= $row_disp['display_id'];
						$curid 					= $disp_id."_".$layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
						$ext_val[]				= $layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
						$disp_array[$curid] 	= $layoutname."(".stripslashes($row_disp['display_position']).")(".$row_disp['display_order'].")";
						$disp_ext_arr[]			= $curid; // used to highlight all the items in the dropdownbox
						
						
					}
				}
		    }
		    else
		    {			
			// Find the display settings details for this category group
			 $sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$group_id AND 
							features_feature_id=$feat_id AND b.themes_theme_id= $ecom_themeid AND a.themes_layouts_layout_id=b.layout_id ";
			 $ret_disp = $db->query($sql_disp);
			 $disp_array		= array();
				if ($db->num_rows($ret_disp))
				{
					while ($row_disp = $db->fetch_array($ret_disp))
					{	
						$layoutid				= $row_disp['themes_layouts_layout_id'];
						$layoutcodechk			= $row_disp['layout_code'];

						
						$layoutcode				= $row_disp['layout_code'];
						$layoutname				= stripslashes($row_disp['layout_name']);
						$disp_id				= $row_disp['display_id'];
						$curid 					= $disp_id."_".$layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
						$ext_val[]				= $layoutid."_".stripslashes($layoutcode)."_".stripslashes($row_disp['display_position']);
						$disp_array[$curid] 	= $layoutname."(".stripslashes($row_disp['display_position']).")(".$row_disp['display_order'].")";
						$disp_ext_arr[]			= $curid; // used to highlight all the items in the dropdownbox
						
						
					}
				}
				// Find the display settings details for this category group
			  $sql_dispmob = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$group_id AND 
							features_feature_id=$feat_id AND b.themes_theme_id= $ecom_mobilethemeid AND a.themes_layouts_layout_id=b.layout_id ";
			 $ret_dispmob = $db->query($sql_dispmob);
			 $mobdisp_array		= array();
				if ($db->num_rows($ret_dispmob))
				{
					while ($mobrow_disp = $db->fetch_array($ret_dispmob))
					{	
						$layoutid				= $mobrow_disp['themes_layouts_layout_id'];
						$layoutcodechk			= $mobrow_disp['layout_code'];
						
						$layoutcode				= $mobrow_disp['layout_code'];
					$layoutname				= stripslashes($mobrow_disp['layout_name']);
					$disp_id				= $mobrow_disp['display_id'];
					$curid 					= $disp_id."_".$layoutid."_".stripslashes($layoutcode)."_".stripslashes($mobrow_disp['display_position']);
					$mobext_val[]				= $layoutid."_".stripslashes($layoutcode)."_".stripslashes($mobrow_disp['display_position']);
					$mobdisp_array[$curid] 	= $layoutname."(".stripslashes($mobrow_disp['display_position']).")(".$mobrow_disp['display_order'].")";
					$mobdisp_ext_arr[]			= $curid; // used to highlight all the items in the dropdownbox
						
					}
				}					
			}
			// Get the list of position allovable for category groups for the current theme
			$sql_themes = "SELECT page_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$statpos_arr	= explode(",",$row_themes['page_positions']);
			}
			// Get the layouts fot the current theme
			$sql_layouts = "SELECT layout_id,layout_code,layout_name,layout_positions FROM themes_layouts WHERE 
							themes_theme_id=$ecom_themeid ORDER BY layout_name";
			$ret_layouts = $db->query($sql_layouts);
			if ($db->num_rows($ret_layouts))
			{ 
				while ($row_layouts = $db->fetch_array($ret_layouts))
				{
					$pos_arr = explode(',',$row_layouts['layout_positions']);
					if(count($pos_arr))
					{
						for($i=0;$i<count($pos_arr);$i++)
						{
							if(in_array($pos_arr[$i],$statpos_arr))
							{
								$curid 	= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".stripslashes($pos_arr[$i]);					if(count($ext_val)){
								if(!in_array($curid,$ext_val))
								{
									$curname			= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
									$disp_array["0_".$curid] = $curname;
								}
								}else {
									$curname			= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
									$disp_array["0_".$curid] = $curname;
								}	
							}	
						}	
					}	
				}
			}
			if($ecom_mobilethemeid>0)
			{
			// Get the list of position allovable for category groups for the current theme
			$sql_mobthemes = "SELECT page_positions FROM themes WHERE theme_id=$ecom_mobilethemeid";
			$ret_mobthemes = $db->query($sql_mobthemes);
			if ($db->num_rows($ret_mobthemes))
			{
				$mobrow_themes = $db->fetch_array($ret_mobthemes);
				$mobcatpos_arr	= explode(",",$mobrow_themes['page_positions']);
			}
			// Get the layouts fot the current mobiletheme
			 $mobsql_layouts = "SELECT layout_id,layout_code,layout_name,layout_positions FROM themes_layouts WHERE 
							themes_theme_id=$ecom_mobilethemeid ORDER BY layout_name";
			$mobret_layouts = $db->query($mobsql_layouts);
			if ($db->num_rows($mobret_layouts))
			{
				while ($mobrow_layouts = $db->fetch_array($mobret_layouts))
				{
					$mobpos_arr = explode(',',$mobrow_layouts['layout_positions']);
					if(count($mobpos_arr))
					{
						for($i=0;$i<count($mobpos_arr);$i++)
						{
							if(in_array($mobpos_arr[$i],$mobcatpos_arr))
							{
								$curid 				= $mobrow_layouts['layout_id']."_".stripslashes($mobrow_layouts['layout_code'])."_".stripslashes($mobpos_arr[$i]);
								$curname = '';
								if(count($mobext_val)){ // by anu for checking is there any selected values are there
									if(!in_array($curid,$mobext_val))
									{
										$curname			= stripslashes($mobrow_layouts['layout_name'])." (".$mobpos_arr[$i].")";
										$mobdisp_array["0_".$curid] = $curname;
									}
								}else {
									$curname			= stripslashes($mobrow_layouts['layout_name'])." (".$mobpos_arr[$i].")";
									$mobdisp_array["0_".$curid] = $curname;
								}	
							}	
						}
					}		
				}
			}
		}
			echo generateselectboxoption('display_id[]',$disp_array,$disp_ext_arr,$mobdisp_array,$mobdisp_ext_arr,'','',5);

		  ?>
		  <!--<select name="group_position[]" multiple="multiple">
		  <?
	/*	  #Getting position values of the current group
		  $sql_group_position="SELECT group_position FROM static_pagegroup_position WHERE static_pagegroup_group_id=".$group_id;
		  $res_group_position=$db->query($sql_group_position);
		 if(mysql_num_rows($res_group_position))
		 {
		 	 	$arr_group_position=array();
				while($row_group_position=$db->fetch_array($res_group_position))
				{
					$arr_group_position[]=$row_group_position['group_position'];
				}
		  }	
		  
		  #Getting position values of the site theme
		  $sql_them_position="SELECT page_positions FROM themes a,sites b WHERE b.site_id=$ecom_siteid AND a.theme_id=b.themes_theme_id";
		  $res_theme_position = $db->query($sql_them_position);
		  $row_theme_position = $db->fetch_array($res_theme_position);
		  $val_theme_page_positions=$row_theme_position['page_positions'];
		  $exp_theme_val_page_positions=explode(',',$val_theme_page_positions);
		  foreach($exp_theme_val_page_positions as $v)
		  {
		  	$selected='';
			if(in_array($v,$arr_group_position))
			{
				$selected='selected';
			}
			echo "<option value=$v $selected>$v</option>";
		  }*/
	   	  ?>

		  </select>-->
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_DISPLOC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		  </td>
          <td colspan="2" align="left" valign="top" class="tdcolorgray"><table width="100%" border="0" height="100%">
            <tr>
              <td width="42%" nowrap="nowrap">Show in all pages </td>
              <td width="58%"><input class="input" type="checkbox" name="group_showinall"  value="1" <? if($row['group_showinall']==1) echo "checked";?> />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_SHOWALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            <tr>
              <td>Hidden</td>
              <td><input type="radio" name="group_hide" value="1" <? if($row['group_hide']==1) echo "checked";?> />
Yes
  <input type="radio" name="group_hide"  value="0" <? if($row['group_hide']==0) echo "checked";?> />
No <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table></td>
        </tr>
		 <tr>
          <td width="12%" align="left" valign="middle" class="tdcolorgray" ><!--Group Order -->
           Page Menu Format </td>
          <td width="44%" align="left" valign="middle" class="tdcolorgray"><select name="group_listtype" id="group_listtype"><option value="Menu" <?php  if($row['group_listtype']=='Menu') echo "selected"?> >Menu</option><option value="Dropdown" <?php if($row['group_listtype']=='Dropdown') echo "selected";?>>Dropdown Box</option></select></td>
          <td width="17%" align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">Show Home page link </td>
	      <td width="27%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="group_showhomelink"  value="1" <? if($row['group_showhomelink']==1) echo "checked";?> />
           <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_SHOWHOMEPAGE_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	    </tr>
		 <tr>
          <td width="12%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="44%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td width="17%" align="left" valign="middle" class="tdcolorgray">Show Sitemap link </td>
	      <td width="27%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="group_showsitemaplink"  value="1" <? if($row['group_showsitemaplink']==1) echo "checked";?> />
           <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_SHOWSITEMAP_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	    </tr>
		 <tr>
          <td width="12%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="44%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray" >Show Help link </td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="group_showhelplink"  value="1" <? if($row['group_showhelplink']==1) echo "checked";?> />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_SHOWHELP_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	    </tr>
       
         <tr>
           <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
           <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
           <td align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">Show FAQ link </td>
           <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="group_showfaqlink"  value="1" <? if($row['group_showfaqlink']==1) echo "checked";?> />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_STAT_GROUP_SHOWFAQ_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
         <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">Show Saved Search link </td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="group_showsavedsearchlink"  value="1" <? if($row['group_showsavedsearchlink']==1) echo "checked";?> />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_SAVEDSEARCH_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <?php /*?><tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">Show XML Sitemap link </td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="group_showxmlsitemaplink"  value="1" <? if($row['group_showxmlsitemaplink']==1) echo "checked";?> />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_XML_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr><?php */?>
		</table>
		</div>
		   <div class="editarea_div" >
		   <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="right" valign="middle" class="tdcolorgray" >
				 <input name="Submit" type="submit" class="red" value="Save" />
		  <input name="Submit" type="submit" class="red" value="Save & Return" />
				</td>
			</tr>
			</table>
			</div>
		<?
    } 
	function show_static_pages_list($group_id,$alert='')
	{
		global $db,$ecom_siteid ;
			 // Get the list of static pages under this psge gruop
				$sql_pages = "SELECT 
										sps.id,sp.page_id,sp.title,sp.hide,sps.static_pages_hide,sp.pname,sps.static_pages_order
 								FROM 
										static_pages sp,static_pagegroup_static_page_map sps
  								WHERE 
										sps.static_pagegroup_group_id=$group_id 
								AND 
										sps.static_pages_page_id=sp.page_id
 								ORDER BY 
										static_pages_order";
				$ret_pages = $db->query($sql_pages);
	?>
					<div class="editarea_div" >
					<table width="100%" cellpadding="1" cellspacing="1" border="0" class="fieldtable">
					<tr>
						<td align="left" colspan="5" class="helpmsgtd"><div class="helpmsg_divcls">
						<?php echo get_help_messages('EDIT_STATIC_GROUP_STAT_SUBMSG')?>	</div>
						</td>
					</tr>
				 <?php
				 // Check whether static pages are added to this static Page Group
					$sql_pages_in_group = "SELECT 
													static_pages_page_id 
											FROM 
													static_pagegroup_static_page_map
								 			WHERE 
													static_pagegroup_group_id=$group_id";
					$ret_pages_in_group = $db->query($sql_pages_in_group);
					//$static_pages = array();
					//while($pages_in_group = $db->fetch_array($ret_pages_in_group)){
					//$static_pages[] = $pages_in_group['static_pages_page_id'];
					//}print_r($static_pages);
				 
				 ?>
				 <tr>
				  <td colspan="5" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="normal_assign_static('<?php echo $_REQUEST['pass_group_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $group_id?>')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_ASSSTAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
						<?php
						if ($db->num_rows($ret_pages_in_group))
						{
						?>
							<div id="pagesunassign_div" class="unassign_div" >
							<!--Change Hidden Status to -->
							<?php
								/*$static_pages_status = array(0=>'No',1=>'Yes');
								echo generateselectbox('static_pages_chstatus',$static_pages_status,0);*/
							?>
							<!--<input name="static_pages_chstatus" type="button" class="red" id="static_pages_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('pages_ingroup','checkboxpages[]')" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_CHSTATUS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>-->	
													&nbsp;&nbsp;&nbsp;<input name="static_pages_delete" type="button" class="red" id="static_pages_delete" value="Un Assign" onclick="call_ajax_deleteall('pages_ingroup','checkboxpages[]')" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_UNASSSTAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
						<?php
						}				
						?>
						&nbsp;&nbsp;<input name="static_pages_chorder" type="button" class="red" id="static_pages_chorder" value="Save Order" onclick="call_ajax_changeorderall('pages_ingroup','checkboxpages[]')" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_CHORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_pages))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditStaticGroup,\'checkboxpages[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditStaticGroup,\'checkboxpages[]\')"/>','Slno.','Page Name','Order','Hidden');
							$header_positions=array('center','center','left','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_pages = $db->fetch_array($ret_pages))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxpages[]" value="<?php echo $row_pages['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=stat_page&fpurpose=edit&page_id=<?=$row_pages['page_id']?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_pages['pname']);?></a></td>
									<td class="<?php echo $cls?>" align="center"><input type="text" name="static_pages_order_<?php echo $row_pages['id']?>" id="static_pages_order_<?php echo $row_pages['id']?>" value="<?php echo stripslashes($row_pages['static_pages_order']);?>" size="3"/></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_pages['hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						?>
						<?	
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="staticpages_norec" id="staticpages_norec" value="1" />
								  No Static Pages Assigned for this Static Page group </td>
								</tr>
						<?php
						}
						?>	
				</table>
				</div>
	<?php	
	}
	

	// ###############################################################################################################
	// 				Function which holds the display logic of categories assigned to a page group to be shown when called using ajax;
	// ###############################################################################################################
	function show_category_list($group_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of categories added to this Page group
				$sql_categories = "SELECT spdc.id,spdc.static_pagegroup_group_id,spdc.product_categories_category_id,spdc.static_pagegroup_category_hide,pc.category_name,pc.category_hide  FROM static_pagegroup_display_category spdc,product_categories pc WHERE pc.category_id=spdc.product_categories_category_id AND  static_pagegroup_group_id=$group_id ORDER BY category_name";
				$ret_categories = $db->query($sql_categories);
	
	   if($_REQUEST['showinall']==1)
		{
		?>
		<div class="editarea_div" >
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_CAT_STAT_GROUP_SHOWALL_MSG')?>	</td>
				</tr>
			</table>	
			</div>		
		<?php	
			return;			
		}
		?>
					<div class="editarea_div" >
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<tr>
						<td align="left" colspan="4" class="helpmsgtd"><div class="helpmsg_divcls">
						<?php echo get_help_messages('EDIT_STAT_GROUP_CAT_SUBMSG')?>	</div>
						</td>
					</tr>
					<?php
				 // Check whether categories are added to this static Page Group
					$sql_categories_in_group = "SELECT product_categories_category_id FROM static_pagegroup_display_category
								 WHERE static_pagegroup_group_id=$group_id";
					$ret_categories_in_group = $db->query($sql_categories_in_group);
					
				 
				 ?>
				<tr>
				  <td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="normal_assign_CategoryGroupAssign('<?php echo $_REQUEST['pass_group_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $group_id?>')"/>
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_ASSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
						<?php
						if ($db->num_rows($ret_categories_in_group))
						{
						?>
							<div id="categoryunassign_div" class="unassign_div" >
							<!--Change Hidden Status to--> 
							<?php
								/*$categories_status = array(0=>'No',1=>'Yes');
								echo generateselectbox('categories_chstatus',$categories_status,0);*/
							?>
							<!--<input name="category_chstatus" type="button" class="red" id="category_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('category','checkboxcategory[]')" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_CHSTATUSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>-->
										
							&nbsp;&nbsp;&nbsp;<input name="categories_unassign" type="button" class="red" id="categories_unassign" value="Un Assign" onclick="call_ajax_deleteall('category','checkboxcategory[]')" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_UNASSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
						<?php
						}				
						?></td>
				</tr>
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_categories))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditStaticGroup,\'checkboxcategory[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditStaticGroup,\'checkboxcategory[]\')"/>','Slno.','Category Name','Hidden');
							$header_positions=array('center','center','left','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_category = $db->fetch_array($ret_categories))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxcategory[]" value="<?php echo $row_category['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_category['product_categories_category_id'];?>&catname=&parentid=&catgroupid=&start=&pg=&records_per_page= &sort_by=&sort_order=" class="edittextlink" title="Edit"><?php echo stripslashes($row_category['category_name']);?></a></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_category['category_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small"><input type="hidden" name="category_norec" id="category_norec" value="1" />No Categories Assigned for this Page Group.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
				</div>
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of products assigned to the page group to be shown when called using ajax;
	// ###############################################################################################################
	function show_product_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of paroduct assigned for the static page group
				$sql_products = "select p.product_id,p.product_name,p.product_hide,spdp.id,spdp.static_pagegroup_product_hide FROM
products p,static_pagegroup_display_product spdp
WHERE spdp.products_product_id=p.product_id  AND spdp.sites_site_id=$ecom_siteid
AND static_pagegroup_group_id=$edit_id ORDER BY product_name";
				$ret_products = $db->query($sql_products);
						 // Check whether Products are added to this static Page Group
							$sql_product_in_group = "SELECT products_product_id FROM static_pagegroup_display_product
										 WHERE static_pagegroup_group_id=$edit_id";
							$ret_product_in_group = $db->query($sql_product_in_group);		
						 
						   if($_REQUEST['showinall']==1)
							{
							?><div class="editarea_div" >
								<table width="100%" cellpadding="0" cellspacing="1" border="0">
									<tr>
										<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_CAT_STAT_GROUP_SHOWALL_MSG')?>	</td>
									</tr>
								</table>	
								</div>		
							<?php	
								return;			
							}
							?>
							<div class="editarea_div" >
						 <table width="100%" cellpadding="1" cellspacing="1" border="0">
						<tr>
							<td align="left" colspan="4" class="helpmsgtd"><div class="helpmsg_divcls">
							<?php echo get_help_messages('EDIT_STAT_GROUP_PROD_SUBMSG')?></div>	
							</td>
						</tr>
						<tr>
						  <td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="normal_assign_staticprod('<?php echo $_REQUEST['pass_group_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $edit_id?>')"/>
								<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_ASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
								<?php
								if ($db->num_rows($ret_product_in_group))
								{
								?>
									<div id="productsunassign_div" class="unassign_div" >
									<!--Change Hidden Status to--> 
									<?php
										/*$products_status = array(0=>'No',1=>'Yes');
										echo generateselectbox('product_chstatus',$products_status,0);*/
									?>
									<!--<input name="product_chstatus" type="button" class="red" id="product_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('product','checkboxproducts[]')" />
									<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_CHSTATUSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				-->								
									&nbsp;&nbsp;&nbsp;<input name="products_unassign" type="button" class="red" id="products_unassign" value="Un Assign" onclick="call_ajax_deleteall('product','checkboxproducts[]')" />
									<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_UNASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
								<?php
								}				
								?></td>
						</tr>
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_products))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditStaticGroup,\'checkboxproducts[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditStaticGroup,\'checkboxproducts[]\')"/>','Slno.','Product Name','Hidden');
							$header_positions=array('center','center','left','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_products = $db->fetch_array($ret_products))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproducts[]" value="<?php echo $row_products['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_products['product_id'];?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_products['product_name']);?></a></td>
									
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_products['product_hide']=='Y')?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No product Assigned to this Page Group. <input type="hidden" name="products_norec" id="products_norec" value="1" /></td>
								</tr>
						<?php
						}
						?>	
				</table>	
				</div>
	<?php	
	}
		// ###############################################################################################################
	// 				Function which holds the display logic of Pages assinged to the page groups when called using ajax;
	// ###############################################################################################################
	function show_assign_pages_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of Static Pages assigned to Page groups
				$sql_assign_pages = "select sp.page_id,sp.title,sp.hide,spds.id,spds.static_pagegroup_pages_hide FROM
static_pages sp,static_pagegroup_display_static spds
WHERE spds.static_pages_page_id=sp.page_id  AND spds.sites_site_id=$ecom_siteid
AND static_pagegroup_group_id=$edit_id ORDER BY title";
				$ret_assign_pages = $db->query($sql_assign_pages);
	
					 if($_REQUEST['showinall']==1)
							{
							?><div class="editarea_div" >
								<table width="100%" cellpadding="0" cellspacing="1" border="0">
									
									<tr>
										<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_CAT_STAT_GROUP_SHOWALL_MSG')?>	</td>
									</tr>
								</table>	
								</div>		
							<?php	
								return;			
							}
							?>
							<div class="editarea_div" >
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<tr>
							<td align="left" colspan="4" class="helpmsgtd"><div class="helpmsg_divcls">
							<?php echo get_help_messages('EDIT_STAT_GROUP_DISSTAT_SUBMSG')?></div>	
							</td>
					  </tr>
				  <?php
				 // Check whether Pages are added to this static Page Group
					$sql_assigned_pages = "SELECT static_pages_page_id FROM static_pagegroup_display_static
								 WHERE static_pagegroup_group_id=$edit_id";
					$ret_assigned_pages = $db->query($sql_assigned_pages);		
				 ?>
				<tr>
				  <td colspan="4" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="normal_assign_StaticGroupAssign('<?php echo $_REQUEST['pass_group_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $edit_id?>')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_ASSSTATPGROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
						<?php
						if ($db->num_rows($ret_assigned_pages))
						{
						?>
							<div id="assign_pagesunassign_div" class="unassign_div" >
							<!--Change Hidden Status to -->
							<?php
								/*$products_status = array(0=>'No',1=>'Yes');
								echo generateselectbox('assign_pages_chstatus',$products_status,0);*/
							?>
							<!--<input name="assign_pages_chstatus" type="button" class="red" id="assign_pages_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('assign_pages','checkboxassignpages[]')" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_CHSTATUSGROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
							-->								
							&nbsp;&nbsp;&nbsp;<input name="assign_pages_unassign" type="button" class="red" id="assign_pages_unassign" value="Un Assign" onclick="call_ajax_deleteall('assign_pages','checkboxassignpages[]')" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_UNASSSTATGROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
						<?php
						}				
						?></td>
				</tr>
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="4" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_assign_pages))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditStaticGroup,\'checkboxassignpages[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditStaticGroup,\'checkboxassignpages[]\')"/>','Slno.','Title','Hidden');
							$header_positions=array('center','center','left','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_assign_pages = $db->fetch_array($ret_assign_pages))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxassignpages[]" value="<?php echo $row_assign_pages['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<?php echo $row_assign_pages['page_id'];?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_assign_pages['title']);?></a></td>
									
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_assign_pages['hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No page Assigned to this Page Group. <input type="hidden" name="assign_pages_norec" id="assign_pages_norec" value="1" /></td>
								</tr>
						<?php
						}
						?>	
				</table>
				</div>	
	<?php	
	}
	// ###############################################################################################################
	
?>
