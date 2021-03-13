<?PHP
	function show_shelf_maininfo($shelf_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname,$ecom_mobilethemeid;
	$sql_shelf="SELECT shelf_id,shelf_name,shelf_description,shelf_order,shelf_hide,shelf_displaytype,shelf_showinall,shelf_showimage,shelf_showtitle,shelf_showdescription,shelf_showprice,shelf_currentstyle,shelf_activateperiodchange,shelf_displaystartdate,shelf_displayenddate,shelf_showinhome FROM product_shelf  WHERE shelf_id=".$shelf_id;
$res_shelf= $db->query($sql_shelf);
$row_shelf = $db->fetch_array($res_shelf);
// Find the feature_id for mod_shelf module from features table
	$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_shelf'";
	$ret_feat = $db->query($sql_feat);
	if ($db->num_rows($ret_feat))
	{
		$row_feat 	= $db->fetch_array($ret_feat);
		$feat_id	= $row_feat['feature_id'];
	}	
?>
<div class="editarea_div">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="fieldtable">
<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		<?
		}
		?> 
		<tr>
		<td align="left" colspan="2" class="onerow_tdcls">
		<div class="editarea_url">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
		<td align="left" valign="top" class="tdcolorgray_url_left">Website URL</td>
		<td align="left" valign="top" class="tdcolorgray_url">:<a href="<?php url_shelf_all($row_shelf['shelf_id'],$row_shelf['shelf_name'],-1);?>" title="Click to view the Shelf in website" target="_blank"><?php url_shelf_all($row_shelf['shelf_id'],$row_shelf['shelf_name'],-1);?></a></td>
		</tr>
		</table>
		</div>
		</td>
		</tr>
 
<tr>
		<td width="51%" valign="top" class="tdcolorgray" >
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Shelf Name <span class="redtext">*</span> </td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="shelf_name" value="<?=stripslashes($row_shelf['shelf_name'])?>"  /><br>
                  <input type='checkbox' name="shelf_updatewebsitelayout" value="1"> Update the title of shelf in website layout section.
          &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELF_WEBSITELAYOUT')?>')" onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>                  </td>
        </tr>
		
		 <tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Shelf Position <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <?php
		  	if($ecom_mobilethemeid==0)
			{
			// Find the display settings details for this category group
			 $sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$shelf_id AND 
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
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$shelf_id AND 
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
							display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$shelf_id AND 
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
			$sql_themes = "SELECT shelf_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$shelfpos_arr	= explode(",",$row_themes['shelf_positions']);
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
						//$layout_arr = explode(',',$row_layouts['layout_positions']);
						for($i=0;$i<count($pos_arr);$i++)
						{
							if(in_array($pos_arr[$i],$shelfpos_arr))
							{
								$curid 				= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".stripslashes($pos_arr[$i]);
								if(!in_array($curid,$ext_val))
								{
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
			$sql_mobthemes = "SELECT shelf_positions FROM themes WHERE theme_id=$ecom_mobilethemeid";
			$ret_mobthemes = $db->query($sql_mobthemes);
			if ($db->num_rows($ret_mobthemes))
			{
				$mobrow_themes = $db->fetch_array($ret_mobthemes);
				$mobcatpos_arr	= explode(",",$mobrow_themes['shelf_positions']);
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
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_DISPLOC')?>.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        
		 <!--<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Shelf Order <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="shelf_order" size="3" value="<?=stripslashes($row_shelf['shelf_order'])?>"  />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>-->
	</table>		</td>
		<td width="49%" valign="top" class="tdcolorgray">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<?php /*?><tr>
		<td width="100%" colspan="2" align="left"><b>Where to show?</b>
		&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_SHOW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr><?php */?>
		 <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Show in all &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_SHOW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="shelf_showinall" value="1"  <? if($row_shelf['shelf_showinall']==1) echo "checked"?>>
           </td>
	      </tr>
		
		 
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >Hide</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="shelf_hide" value="1" <? if($row_shelf['shelf_hide']==1) echo "checked"?> />&nbsp;&nbsp;Yes&nbsp;&nbsp;<input type="radio" name="shelf_hide" value="0" <? if($row_shelf['shelf_hide']==0) echo "checked"?> />&nbsp;&nbsp;No
		  &nbsp;&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		 <tr>
			<td colspan="2" align="left"><b>Active Period</b>
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_PERIOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
		  
		   <tr>
          <td width="39%" align="left" valign="middle" class="tdcolorgray" >Change Active Period </td>
          <td width="61%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" id="shelf_activateperiodchange" name="shelf_activateperiodchange" onclick="change_show_date_period()" value="1" <? if($row_shelf['shelf_activateperiodchange']==1) echo "checked"?>  />		  </td>
        </tr>
		<? 
			if($row_shelf['shelf_activateperiodchange']==1)
			{
			
			  $active_start_arr 		= explode(" ",$row_shelf['shelf_displaystartdate']);
			  $active_end_arr 			= explode(" ",$row_shelf['shelf_displayenddate']);
			  $active_starttime_arr 	= explode(":",$active_start_arr[1]);
						$active_start_hr			= $active_starttime_arr[0];
						$active_start_mn			= $active_starttime_arr[1];
						$active_start_ss			= $active_starttime_arr[2];	
						$active_endttime_arr 		= explode(":",$active_end_arr[1]);
						$active_end_hr				= $active_endttime_arr[0];
						$active_end_mn				= $active_endttime_arr[1];
						$active_end_ss				= $active_endttime_arr[2];	
			  $display='';
			  $exp_shelf_displaystartdate=explode("-",$active_start_arr[0]);
			  $val_shelf_displaystartdate=$exp_shelf_displaystartdate[2]."-".$exp_shelf_displaystartdate[1]."-".$exp_shelf_displaystartdate[0];
			  $exp_shelf_displayenddate=explode("-",$active_end_arr[0]);
			  $val_shelf_displayenddate  =$exp_shelf_displayenddate[2]."-".$exp_shelf_displayenddate[1]."-".$exp_shelf_displayenddate[0];
			}
			else
			{
			  $display='none';
			}
			
					for($i=0;$i<60;$i++)
					$option .= '<option value="'.$i.'">'.$i.'</option>';
				for($i=0;$i<=23;$i++)
					$houroption .= '<option value="'.$i.'">'.$i.'</option>';	
		?>
		<tr id="show_date_period" style="display:<?=$display?>;">
		 <td colspan="2" align="left" valign="middle" class="tdcolorgray" >
		 <table width="100%" cellpadding="0" cellspacing="2" border="0">
		 <tr>
		   <td align="left" valign="middle"  >&nbsp;</td>
		   <td align="left" valign="middle" >&nbsp;</td>
		   <td width="10%" align="left" valign="middle" >&nbsp;</td>
		   <td width="15%" align="left" valign="middle" >Hrs</td>
		   <td width="14%" align="left" valign="middle" >Min</td>
		   <td width="13%" align="left" valign="middle" >Sec</td>
		   <td width="13%" align="left" valign="middle" >&nbsp;</td>
		 </tr>
		 <tr>
		 <td width="17%" align="left" valign="middle"  >Start Date</td>
          <td width="18%" align="left" valign="middle" >
		  <input class="input" type="text" name="shelf_displaystartdate" size="8" value="<?=$val_shelf_displaystartdate?>"  />		  </td>
		  <td align="left" valign="middle" >
		  <a href="javascript:show_calendar('frmEditShelf.shelf_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		  <td align="left" valign="middle" ><select name="shelf_starttime_hr" id="shelf_starttime_hr">
										<option value="<?php echo $active_start_hr?>"><?php echo $active_start_hr?></option>
										<?php echo $houroption?>
										</select></td>
		  <td align="left" valign="middle" ><select name="shelf_starttime_mn" id="shelf_starttime_mn">
										<option value="<?php echo $active_start_mn?>"><?php echo $active_start_mn?></option>
										<?php echo $option?>
										</select></td>
		  <td align="left" valign="middle" ><select name="shelf_starttime_ss" id="shelf_starttime_ss">
										<option value="<?php echo $active_start_ss?>"><?php echo $active_start_ss?></option>
										<?php echo $option?>
										</select></td>
		  <td align="left" valign="middle" >&nbsp;</td>
		 </tr>
		 <tr>
		 <td width="17%" align="left" valign="middle"  >End Date</td>
          <td width="18%" align="left" valign="middle" >
		  <input class="input" type="text" name="shelf_displayenddate" size="8" value="<?=$val_shelf_displayenddate?>"  />		  </td>
		  <td align="left" valign="middle" >
		  <a href="javascript:show_calendar('frmEditShelf.shelf_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		  <td align="left" valign="middle" ><select name="shelf_endtime_hr" id="shelf_endtime_hr">
										<option value="<?php echo $active_end_hr?>"><?php echo $active_end_hr?></option>
										<?php echo $houroption?>
										</select></td>
		  <td align="left" valign="middle" ><select name="shelf_endtime_mn" id="shelf_endtime_mn">
										<option value="<?php echo $active_end_mn?>"><?php echo $active_end_mn?></option>
										<?php echo $option?>
										</select></td>
		  <td align="left" valign="middle" ><select name="shelf_endtime_ss" id="shelf_endtime_ss">
										<option value="<?php echo $active_end_ss?>"><?php echo $active_end_ss?></option>
										<?php echo $option?>
										</select></td>
		  <td align="left" valign="middle" >&nbsp;</td>
		 </tr>
		 </table>		  </td>
		</tr>
         <tr>
		   <td  colspan="2" align="left" valign="middle">
               <table border="0" cellspacing="0" cellpadding="0" width="100%">
               <tr>
                <td width="22%"  align="left" valign="middle" class="tdcolorgray_url" >Website URL</td>
               <td width="78%"  align="left" valign="middle" class="tdcolorgray_url">:&nbsp;&nbsp;<?php url_shelf_all($row_shelf['shelf_id'],$row_shelf['shelf_name'],-1);?></td>
               </tr>
               </table>
           </td>
	      </tr>
       </table>	   </td>
  </tr>
	   <tr>
          <td colspan="2" align="left" valign="top" class="tdcolorgray" >Description &nbsp;<a href="#" onmouseover ="ddrivetip('This description will be displayed only while viewing the shelf in middle area.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
  </tr>
		 <tr>
          <td colspan="2" align="left" valign="top" class="tdcolorgray" >
			<?php
			//	include_once("classes/fckeditor.php");
				/*$editor 			= new FCKeditor('shelf_description') ;
				$editor->BasePath 	= '/console/js/FCKeditor/';
				$editor->Width 		= '550';
				$editor->Height 	= '300';
				$editor->ToolbarSet = 'BshopWithImages';
				$editor->Value 		= stripslashes($row_shelf['shelf_description']);
				$editor->Create() ;*/
			?>
			<textarea style="height:300px; width:500px" id="shelf_description" name="shelf_description"><?=stripslashes($row_shelf['shelf_description'])?></textarea>
			</td>
        </tr>
		</table>
		</div>
		<div class="editarea_div">
		 <table width="100%">
		 <tr>
		 	<td width="100%" align="right" valign="middle">	
			<input name="Submit" type="submit" class="red" value=" Save " />	
			<!-- Button to save and return starts here -->	   
			<input name="Submit" type="submit" class="red" value="Save & Return" />
			<!-- Button to save and return ends here -->
			</td>
		</tr>
		</table>
		</div>

<?php
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of products under the shelf to be shown when called using ajax;
	// ###############################################################################################################
	function show_product_shelf_list($shelf_id,$alert='')
	{
		global $db,$ecom_siteid ;
		
		  // Get the list of products under current category group
		  $sql_product = "SELECT b.id,a.product_id,a.product_name,a.product_hide,b.product_order FROM products a,
						product_shelf_product b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_shelf_shelf_id=$shelf_id AND 
						a.product_id=b.products_product_id ORDER BY b.product_order";
		 
		  $ret_product = $db->query($sql_product);
		  ?><div class="editarea_div">
		  <table width="100%" cellpadding="0" cellspacing="1" border="0">
		   <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('SHELVES_PRODUCTS')?></div></td>
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
		  // Get the list of products under current category group
		  $sql_products_in_shelf = "SELECT products_product_id FROM 
						product_shelf_product WHERE  
						product_shelf_shelf_id=$shelf_id";
		 
		  $ret_products_in_shelf = $db->query($sql_products_in_shelf);
		
				?>
		<tr>
		  <td align="right" colspan="6" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_prodShelfAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shelf_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_products_in_shelf))
			{
			?>
			<div id="product_shelfunassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('product_shelf','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_UNASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<input name="SaveOrder" type="button" class="red" id="SaveOrder" value="Save Order" onclick="call_ajax_changeorderall('product_shelf','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_PROD_SAVEORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</div>	
			<?php
			}
			?>
		  </td>
		</tr>
				<?PHP
				if ($db->num_rows($ret_product))
				{
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShelf,\'checkboxproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShelf,\'checkboxproduct[]\')"/>','Slno.','Product Name','Order','Hidden');
				$header_positions=array('center','center','left','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_product = $db->fetch_array($ret_product))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproduct[]" value="<?php echo $row_product['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_product['product_id']?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_product['product_name']);?></a></td>
					<td class="<?php echo $cls?>" align="left"><input type="text" name="product_shelf_order_<?php echo $row_product['id']?>" id="product_shelf_order_<?php echo $row_product['id']?>" value="<?php echo stripslashes($row_product['product_order']);?>" size="2" /></td>
					<td class="<?php echo $cls?>" align="left"><?php echo ($row_product['product_hide']=='Y')?'Yes':'No'?></td>
					
					</tr>
				<?php
				}
				}
				else
				{
				?>
				<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="productshelf_norec" id="productshelf_norec" value="1" />
								  No Products Assigned for this Shelf </td>
			</tr>
				<?	
				}
				?>
</table></div>
		
<?	}

    // ###############################################################################################################
	// 				Function which holds the display logic of display products under the shelf using ajax;
	// ###############################################################################################################
	
    function show_display_product_shelf_list($shelf_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_display_product = "SELECT b.id,a.product_id,a.product_name,a.product_hide,b.id FROM products a,
						product_shelf_display_product b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_shelf_shelf_id=$shelf_id AND 
						a.product_id=b.products_product_id ";
		  
		$ret_display_product = $db->query($sql_display_product);
		
		
		if($_REQUEST['showinall']==1)
		{
		?>
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_PROD_SHELF_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		?>
		<div class="editarea_div">
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
		   <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('DISPLAY_SHELVES_PRODUCTS')?></div></td>
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
		  // Get the list of products under current category group
		  $sql_display_products_in_shelf = "SELECT products_product_id FROM 
						product_shelf_display_product WHERE  
						product_shelf_shelf_id=$shelf_id";
		 
		  $ret_display_products_in_shelf = $db->query($sql_display_products_in_shelf);
			?>
			 <tr>
		  <td align="right" colspan="6" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayProdShelfAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shelf_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_PROD_SHELVES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_display_products_in_shelf))
			{
			?>
			<div id="display_product_shelfunassign_div" class="unassign_div">
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('display_product_shelf','checkboxdisplayproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_UNASS_PROD_SHELVES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		</div>	
			<?php
			}
			?>
		  </td>
			</tr>
			<?PHP	
				if ($db->num_rows($ret_display_product))
		       {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShelf,\'checkboxdisplayproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShelf,\'checkboxdisplayproduct[]\')"/>','Slno.','Product Name','Hidden');
				$header_positions=array('center','center','left','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_display_product = $db->fetch_array($ret_display_product))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplayproduct[]" value="<?php echo $row_display_product['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_display_product['product_id']?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>" title="edit" class="edittextlink"><?php echo stripslashes($row_display_product['product_name']);?></a></td>
					<td class="<?php echo $cls?>" align="left"><?php echo ($row_display_product['product_hide']=='Y')?'Yes':'No'?></td>
					
					</tr>
				<?php
				}
				?>
				
		<?
		}
			else
			{
		?>
			<tr  >
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Products assigned to display this Shelf
			   <input type="hidden" name="display_productshelf_norec" id="display_productshelf_norec" value="1" />
			  </td>
			</tr>
		<?
			}
		
		?>
		</table></div>
		<?  
		}
	 // ###############################################################################################################
	// 				Function which holds the display logic of display categories under the shelf using ajax;
	// ###############################################################################################################
	 
	 function show_display_category_shelf_list($shelf_id,$alert='')
	 {
	 	global $db,$ecom_siteid ;
		 $sql_category_shelf = "SELECT b.id,a.category_id,a.category_name,a.category_hide FROM product_categories a,
						product_shelf_display_category b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_shelf_shelf_id=$shelf_id AND 
						a.category_id=b.product_categories_category_id ";
		 
		
		 $ret_category_shelf = $db->query($sql_category_shelf);
		 
			
			if($_REQUEST['showinall']==1)
		{
		?>
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_PROD_SHELF_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		?>		<div class="editarea_div">
				<table width="100%" cellpadding="0" cellspacing="1" border="0">
				 <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('DISPLAY_SHELVES_CATEGORIES')?></div></td>
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
		  // Get the list of categories under current category group
		  $sql_display_products_in_shelf = "SELECT product_categories_category_id FROM 
						product_shelf_display_category WHERE  
						product_shelf_shelf_id=$shelf_id";
		 
		
		 $ret_display_products_in_shelf = $db->query($sql_display_products_in_shelf);

				?>
				<tr>
		  <td align="right" colspan="6" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayCategoryShelfAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shelf_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_display_products_in_shelf))
			{
			?>
			<div id="display_category_shelfunassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('display_category_shelf','checkboxdisplaycategory[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_UNASS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</div>	
			<?php
			}
			?>
		  </td>
			</tr>
				<?PHP
				
				if ($db->num_rows($ret_category_shelf))
		        {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShelf,\'checkboxdisplaycategory[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShelf,\'checkboxdisplaycategory[]\')"/>','Slno.','Category Name','Hidden');
				$header_positions=array('center','center','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_category_group = $db->fetch_array($ret_category_shelf))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplaycategory[]" value="<?php echo $row_category_group['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_category_group['category_id']?>" class="edittextlink"><?php echo stripslashes($row_category_group['category_name']);?></a></td>
					<td class="<?php echo $cls?>" align="left"><?php echo ($row_category_group['category_hide']=='1')?'Yes':'No'?></td>
					</tr>
				<?php
				}
				
			}
			else
			{
			?>
			<tr  >
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Categories assigned to display this Shelf
			  <input type="hidden" name="display_categoryshelf_norec" id="display_categoryshelf_norec" value="1" />
			  </td>
			</tr>
			<?
			}
			?>
			</table>
			</div>
			<?
	 }
	  // ###############################################################################################################
	// 				Function which holds the display logic of display static pages under the shelf using ajax;
	// ###############################################################################################################
	 function show_display_static_shelf_list($shelf_id,$alert='')
	 {
	 global $db,$ecom_siteid ;
	 $sql_static_shelf = "SELECT b.id,a.page_id,a.title,a.hide FROM static_pages a,
						product_shelf_display_static b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_shelf_shelf_id=$shelf_id AND 
						a.page_id=b.static_pages_page_id ";
		
		 
		 $ret_static_shelf = $db->query($sql_static_shelf);
	
	 if($_REQUEST['showinall']==1)
		{
		?>
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_PROD_SHELF_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		?><div class="editarea_div">
	 <table width="100%" cellpadding="0" cellspacing="1" border="0">
	  <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('DISPLAY_SHELVES_STATICPAGES')?></div></td>
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
	    // Get the list of static pages under current category group
		$sql_display_static_in_shelf = "SELECT static_pages_page_id FROM 
						product_shelf_display_static WHERE  
						product_shelf_shelf_id=$shelf_id";
		 
		
		 $ret_display_static_in_shelf = $db->query($sql_display_static_in_shelf);
		
				?>
		<tr>
		  <td align="right" colspan="6" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayStaticShelfAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shelf_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_STATPG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_display_static_in_shelf))
			{
			?>
			<div id="display_static_shelfunassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('display_static_shelf','checkboxdisplaystatic[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_UNASS_STATPG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</div>	
			<?php
			}
			?>
		  </td>
		</tr>
				<?PHP
				if ($db->num_rows($ret_static_shelf))
		        {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShelf,\'checkboxdisplaystatic[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShelf,\'checkboxdisplaystatic[]\')"/>','Slno.','Page Name','Hidden');
				$header_positions=array('center','center','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_static_group = $db->fetch_array($ret_static_shelf))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplaystatic[]" value="<?php echo $row_static_group['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<?=$row_static_group['page_id']?>&fpurpose=edit" class="edittextlink" ><?php echo stripslashes($row_static_group['title']);?></a></td>
					<td class="<?php echo $cls?>" align="left"left><?php echo ($row_static_group['hide']=='1')?'Yes':'No'?></td>
					</tr>
				<?php
				}
				}
				else
				{
				?>
				<tr  >
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Static Pages assigned to display this Shelf
			  <input type="hidden" name="display_staticshelf_norec" id="display_staticshelf_norec" value="1" />
			  </td>
			</tr>
				<?
				
				}
				?>
</table> </div>
<?PHP			
	 }
	   // ###############################################################################################################
	// 				Function which holds the display logic of display static pages under the shelf using ajax;
	// ###############################################################################################################
	 function show_display_shop_shelf_list($shelf_id,$alert='')
	 {
	 global $db,$ecom_siteid ;
	 $sql_static_shelf = "SELECT b.id,a.shopbrand_id,a.shopbrand_name,a.shopbrand_hide FROM product_shopbybrand a,
						product_shelf_display_shop b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_shelf_shelf_id=$shelf_id AND 
						a.shopbrand_id=b.product_shop_shop_id ";
		 $ret_static_shelf = $db->query($sql_static_shelf);
	 if($_REQUEST['showinall']==1)
		{
		?>
			<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td colspan="4" align="center">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_PROD_SHELF_SHOWALL_MSG')?>	</td>
				</tr>
			</table>			
		<?php	
			return;			
		}
		?>
	 <div class="editarea_div">
	 <table width="100%" cellpadding="0" cellspacing="1" border="0">
	  <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('DISPLAY_SHELVES_SHOPS')?></div></td>
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
	    // Get the list of static pages under current category group
		$sql_display_static_in_shelf = "SELECT product_shop_shop_id FROM 
						product_shelf_display_shop WHERE  
						product_shelf_shelf_id=$shelf_id";
		 
		
		 $ret_display_static_in_shelf = $db->query($sql_display_static_in_shelf);
		
				?>
		<tr>
		  <td align="right" colspan="6" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayShopShelfAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shelf_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ASS_SHOP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_display_static_in_shelf))
			{
			?>
			<div id="display_static_shelfunassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('display_shop_shelf','checkboxdisplayshop[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_UNASS_SHOP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</div>	
			<?php
			}
			?>
		  </td>
		</tr>
				<?PHP
				if ($db->num_rows($ret_static_shelf))
		        {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShelf,\'checkboxdisplayshop[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShelf,\'checkboxdisplayshop[]\')"/>','Slno.','Shop Name','Hidden');
				$header_positions=array('center','center','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
					while ($row_static_group = $db->fetch_array($ret_static_shelf))
					{
						
						$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
					?>
						<tr>
						<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplayshop[]" value="<?php echo $row_static_group['id'];?>" /></td>
						<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
						<td class="<?php echo $cls?>" align="left"><a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<?=$row_static_group['page_id']?>&fpurpose=edit" class="edittextlink" ><?php echo stripslashes($row_static_group['shopbrand_name']);?></a></td>
						<td class="<?php echo $cls?>" align="left"left><?php echo ($row_static_group['hide']=='1')?'Yes':'No'?></td>
						</tr>
					<?php
					}
				}
				else
				{
				?>
				<tr  >
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Shops assigned to display this Shelf
			  <input type="hidden" name="display_staticshelf_norec" id="display_staticshelf_norec" value="1" />
			  </td>
			</tr>
				<?
				
				}
				?>
</table> </div>
<?PHP			
	 }
	function show_display_settings($shelf_id,$alert='') 
	{	 
		global $db,$ecom_siteid,$ecom_themeid,$ecom_mobilethemeid;
		$sql_shelf="SELECT shelf_name,shelf_description,shelf_order,shelf_hide,shelf_displaytype,shelf_showinall,shelf_showimage,
						shelf_showtitle,shelf_showdescription,shelf_showprice,shelf_currentstyle,shelf_activateperiodchange,shelf_displaystartdate,
						shelf_displayenddate,shelf_showinhome,shelf_showrating,shelf_showbonuspoints  
					FROM 
						product_shelf  
					WHERE 
						shelf_id=".$shelf_id;
	$res_shelf= $db->query($sql_shelf);
	$row_shelf = $db->fetch_array($res_shelf);
	// Find the feature_id for mod_shelf module from features table
	$sql_feat = "SELECT feature_id FROM features WHERE feature_modulename='mod_shelf'";
	$ret_feat = $db->query($sql_feat);
	if ($db->num_rows($ret_feat))
	{
		$row_feat 	= $db->fetch_array($ret_feat);
		$feat_id	= $row_feat['feature_id'];
	}
	
	// Find the display settings details for this shelf
	$sql_disp = "SELECT a.display_id,a.display_position,a.layout_code,a.themes_layouts_layout_id,a.display_order,b.layout_name FROM 
					display_settings a,themes_layouts b WHERE a.sites_site_id = $ecom_siteid AND display_component_id=$shelf_id AND 
					features_feature_id=$feat_id AND a.themes_layouts_layout_id=b.layout_id";
	$ret_disp = $db->query($sql_disp);
?>		<div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="fieldtable">
						 <tr>
          <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('DISPLAY_SETTINGS')?></div></td>
        </tr>
<?php
				
				if($alert)
				{
					?>
							<tr>
								<td colspan="4" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				} ?>
<tr>
		<td width="44%" valign="top" class="tdcolorgray" >
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
        
		 <!--<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Shelf Order <span class="redtext">*</span></td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="shelf_order" size="3" value="<?=stripslashes($row_shelf['shelf_order'])?>"  />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>-->
		 <tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Display Type </td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <select name="shelf_displaytype" id="shelf_displaytype">
		  <?
		  $arr_style =array();
		 $sql_style	= "SELECT shelf_displaytypes FROM themes WHERE theme_id=".$ecom_themeid;
		 $ret_style = $db->query($sql_style);
		 $row_style	= $db->fetch_array($ret_style);
		 $arr_style	= explode(',',$row_style['shelf_displaytypes']);
		 if($ecom_mobilethemeid)
		 { 
		 $arr_style_mob = array();
		 $sql_style_mob	= "SELECT shelf_displaytypes FROM themes WHERE theme_id=".$ecom_mobilethemeid;
		 $ret_style_mob = $db->query($sql_style_mob);
		 $row_style_mob	= $db->fetch_array($ret_style_mob);
		 $arr_style_mob	= explode(',',$row_style_mob['shelf_displaytypes']);
	     }
	     if(is_array($arr_style) && is_array($arr_style_mob))
	     {
	     $arr_style = array_merge($arr_style , $arr_style_mob);
		 }
		 foreach($arr_style as $v)
		 {
		 	$val_arr = explode("=>",$v);
		 ?>
		 <option value="<?=$val_arr[0]?>" <?php echo ($row_shelf['shelf_displaytype']==$val_arr[0])?'selected':''?>><?=$val_arr[1]?></option>
		 <?
	
		 }
		 ?>
		 </select>
		 &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_DISPTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Listing Style <span class="redtext">*</span> </td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
		  <select name="shelf_currentstyle" id="shelf_currentstyle">
		  <option value="">--select--</option>
		  <?
		 $sql_style="SELECT shelf_listingstyles FROM themes WHERE theme_id=".$ecom_themeid;
		 $ret_style = $db->query($sql_style);
		 $row_style=$db->fetch_array($ret_style);
		 $arr_style=explode(',',$row_style['shelf_listingstyles']);
		 foreach($arr_style as $v)
		 {
		 	$val_arr = explode("=>",$v);
		 ?>
		 	<option value="<?=$val_arr[0]?>" <?php echo ($row_shelf['shelf_currentstyle']==$val_arr[0])?'selected':''?>><?=$val_arr[1]?></option>
		 <?
		 }
		 ?>
		  </select>
		  
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_LISTSTYLE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
         
	</table>		</td>
		<td width="56%" valign="top" class="tdcolorgray">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<?php /*?><tr>
		<td width="100%" colspan="2" align="left"><b>Where to show?</b>
		&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_SHOW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr><?php */?>
		
		
         
		<tr>
		<td colspan="2" align="left"><b>Fields to be displayed in shelf</b>
		&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHELVES_FIELD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		 <tr>
          <td width="37%" align="left" valign="middle" class="tdcolorgray" >Show Image </td>
          <td width="63%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="shelf_showimage" value="1" <? if($row_shelf['shelf_showimage']==1) echo "checked"?>  />		  </td>
        </tr>
		
		<tr>
          <td width="37%" align="left" valign="middle" class="tdcolorgray" >Show Title </td>
          <td width="63%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="shelf_showtitle" value="1" <? if($row_shelf['shelf_showtitle']==1) echo "checked"?>  />		  </td>
        </tr>
		<tr>
          <td width="37%" align="left" valign="middle" class="tdcolorgray" >Show Description </td>
          <td width="63%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="shelf_showdescription" value="1" <? if($row_shelf['shelf_showdescription']==1) echo "checked"?>  />		  </td>
        </tr>
		<tr>
          <td width="37%" align="left" valign="middle" class="tdcolorgray" >Show Price </td>
          <td width="63%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="shelf_showprice" value="1" <? if($row_shelf['shelf_showprice']==1) echo "checked"?>  />		  </td>
        </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >Show Rating </td>
		  <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="shelf_showrating" value="1" <? if($row_shelf['shelf_showrating']==1) echo "checked"?>>          </td>
		  </tr>
		 <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Show Bonus Points </td>
		  <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="shelf_showbonuspoints" value="1" <? if($row_shelf['shelf_showbonuspoints']==1) echo "checked"?>>          </td>
		  </tr> 
		 
		<? 
			if($row_shelf['shelf_activateperiodchange']==1)
			{
			
			  $active_start_arr 		= explode(" ",$row_shelf['shelf_displaystartdate']);
			  $active_end_arr 			= explode(" ",$row_shelf['shelf_displayenddate']);
			  $active_starttime_arr 	= explode(":",$active_start_arr[1]);
						$active_start_hr			= $active_starttime_arr[0];
						$active_start_mn			= $active_starttime_arr[1];
						$active_start_ss			= $active_starttime_arr[2];	
						$active_endttime_arr 		= explode(":",$active_end_arr[1]);
						$active_end_hr				= $active_endttime_arr[0];
						$active_end_mn				= $active_endttime_arr[1];
						$active_end_ss				= $active_endttime_arr[2];	
			  $display='';
			  $exp_shelf_displaystartdate=explode("-",$active_start_arr[0]);
			  $val_shelf_displaystartdate=$exp_shelf_displaystartdate[2]."-".$exp_shelf_displaystartdate[1]."-".$exp_shelf_displaystartdate[0];
			  $exp_shelf_displayenddate=explode("-",$active_end_arr[0]);
			  $val_shelf_displayenddate  =$exp_shelf_displayenddate[2]."-".$exp_shelf_displayenddate[1]."-".$exp_shelf_displayenddate[0];
			}
			else
			{
			  $display='none';
			}
			
					for($i=0;$i<60;$i++)
					$option .= '<option value="'.$i.'">'.$i.'</option>';
				for($i=0;$i<=23;$i++)
					$houroption .= '<option value="'.$i.'">'.$i.'</option>';	
		?>
    </table>	   </td>
	   </tr>
	   
		 <tr>
		   <td colspan="2" align="center" valign="top" class="tdcolorgray" > 
		   		 </td>
  		 </tr>
	</table>
	</div>
	<div class="editarea_div">
	 <table width="100%">
	 <tr>
		<td width="100%" align="right" valign="middle">
			<input name="cat_Submit" type="button" class="red" value="Save" onclick="call_ajax_savesettings('display_settings')" />
		</td>
	</tr>
	</table>
	</div>
<? 
	} 
	/* SEO tab in static page starts here */
	function show_page_seoinfo($page_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname;
		
		
			$sql_title	=	"SELECT
											title,meta_description
									FROM
											se_shelf_title
									WHERE
											sites_site_id=$ecom_siteid
									AND
											product_shelf_shelf_id=".$page_id;
											
			$sql_keys	=	"SELECT
											keywd.keyword_keyword,skey.se_keywords_keyword_id
									FROM
											se_keywords keywd,se_shelf_keywords skey
									WHERE
											skey.product_shelf_shelf_id = ".$page_id."
									AND
											skey.se_keywords_keyword_id = keywd.keyword_id
									AND
											keywd.sites_site_id = ".$ecom_siteid."
											ORDER BY se_keywords_keyword_id ASC";
		
		$res_title = $db->query($sql_title);
		if($db->num_rows($res_title)>0) 
		{
			$row_title = $db->fetch_array($res_title);
		}
		else
		{
			$row_title['title']	=	"";
			$row_title['meta_description']	=	"";
		}
		//echo $row_title['title'];echo "<br>";
		$res_keys = $db->query($sql_keys);
		if($db->num_rows($res_keys)>0) 
		{
			$field_cnt	=	1;
			$field_values	=	array();
			while($row_keys = $db->fetch_array($res_keys))
			{
				$field_values[$field_cnt]	=	$row_keys['keyword_keyword'];
				$field_cnt++;
			}
		}
		//echo $sql_keys;
?><div class="editarea_div">
		<table width="100%" border="0">
			<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
			<td class="tdcolorgray" align="left"><b>Title:</b></td>
			<td align="left"><input type="text" name="page_title" value="<?php echo $row_title['title'];?>" size="84"/></td>
		</tr>
		<tr>
			<td class="tdcolorgray" align="left"><b>Meta description:</b></td>
			<td align="left"><textarea  name="page_meta"cols="63" rows="2"><?php echo $row_title['meta_description'];?></textarea></td>
		</tr>
		<tr>
			<td class="tdcolorgray" align="left"><b>Keyword #1:</b></td>
			<td align="left">
				<input type="text" name="keyword_1" id="keyword_1" value="<?php echo $field_values[1];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #2:</b></td>
			<td align="left">
				<input type="text" name="keyword_2" id="keyword_2" value="<?php echo $field_values[2];?>" size="50" />&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #3:</b></td>
			<td align="left">
				<input type="text" name="keyword_3" id="keyword_3" value="<?php echo $field_values[3];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #4:</b></td>
			<td align="left">
				<input type="text" name="keyword_4" id="keyword_4" value="<?php echo $field_values[4];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #5:</b></td>
			<td align="left">
				<input type="text" name="keyword_5" id="keyword_5" value="<?php echo $field_values[5];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
			</td>
		  </tr>
		</table></div>
		<div class="editarea_div">
			<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td align="right" valign="middle" class="tdcolorgraynormal" >
				<input name="cat_Submit" type="button" class="red" value="Save" onclick="call_save_seo('seo')" />	
				</td>
			</tr>
			</table>
		</div>
<?php
	}
?>	