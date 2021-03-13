<?php
	/*#################################################################
	# Script Name 	: add_combo.php
	# Description 	: Page for adding Combo
	# Coded by 		: SKR
	# Created on	: 18-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Combo';
$help_msg = get_help_messages('ADD_COMBO_MESS1');

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('combo_name','display_id[]');
	fieldDescription = Array('Combo Name','Combo Position');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(document.frmAddCombo.combo_activateperiodchange.checked  ==true){
			val_dates = compareDates(document.frmAddCombo.combo_displaystartdate,"Start Date\n Correct Format:dd-mm-yyyy ",document.frmAddCombo.combo_displayenddate,"End Date\n Correct Format:dd-mm-yyyy");
			if(!val_dates){
				return false;
			}
		}
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
function activeperiod(check,bid){
 if(document.frmAddCombo.combo_activateperiodchange.checked == true){
		document.getElementById(bid).style.display = '';
		}
		else{
		document.getElementById(bid).style.display = 'none';
		document.frmAddCombo.combo_activateperiodchange.checked = false;
		}
		
		
}
</script>
<form name='frmAddCombo' action='home.php?request=combo' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="7" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=combo&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Combo Deals</a><span> Add Combo</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="7">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="7" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
		  <td colspan="5" align="left" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
		  <table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="46%" rowspan="2" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                <tr>
                  <td width="45%" align="left">Combo Name <span class="redtext">*</span></td>
                  <td width="55%" align="left"><input class="input" type="text" name="combo_name" value="<?=$_REQUEST['combo_name']?>"/>
&nbsp; <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_NAME')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr>
                <tr>
                  <td align="left">Combo Position<span class="redtext">*</span></td>
                  <td align="left"><?php
			// Get the list of position allovable for combos for the current theme
			$sql_themes = "SELECT combo_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$combopos_arr	= explode(",",$row_themes['combo_positions']);
			}
			
			$disp_array	= array();
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
							if(in_array($pos_arr[$i],$combopos_arr))
							{
								$curid 				= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".$pos_arr[$i];
								$curname			= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
								$disp_array[$curid] = $curname;
							}	
						}	
					}	
				}
			}
			if($ecom_mobilethemeid>0)
			{
			// Get the list of position allovable for category groups for the current theme
			$sql_mobthemes = "SELECT combo_positions FROM themes WHERE theme_id=$ecom_mobilethemeid";
			$ret_mobthemes = $db->query($sql_mobthemes);
			if ($db->num_rows($ret_mobthemes))
			{
				$mobrow_themes = $db->fetch_array($ret_mobthemes);
				$mobcatpos_arr	= explode(",",$mobrow_themes['combo_positions']);
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
								
										$curname			= stripslashes($mobrow_layouts['layout_name'])." (".$mobpos_arr[$i].")";
										$mobdisp_array[$curid] = $curname;
									
								
							}	
						}
					}		
				}
			}
		}
		echo generateselectboxoption('display_id[]',$disp_array,$_REQUEST['display_id'],$mobdisp_array,$_REQUEST['display_id'],'','',5);

			$id='tr_combo';
		  ?>
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_COMBO_POSITION')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		  </td>
                </tr>
				<? 
				
		    if($_REQUEST['combo_activateperiodchange']==1)
		   			 {
					$display='';
					  $exp_combo_displaystartdate=explode("-",$_REQUEST['combo_displaystartdate']);
					  $val_combo_displaystartdate=$exp_combo_displaystartdate[2]."-".$exp_combo_displaystartdate[1]."-".$exp_combo_displaystartdate[0];
					  $exp_combo_displayenddate=explode("-",$_REQUEST['combo_displayenddate']);
					  $val_combo_displayenddate=$exp_combo_displayenddate[2]."-".$exp_combo_displayenddate[1]."-".$exp_combo_displayenddate[0];
		
					 }
					else
					{ 
					 //echo "none";
					  $display='none';
					}
					
					for($i=0;$i<60;$i++)
					$option .= '<option value="'.$i.'">'.$i.'</option>';
					for($i=0;$i<=23;$i++)
					$houroption .= '<option value="'.$i.'">'.$i.'</option>';	
		   ?>
                <?php /*?><tr>
                  <td align="left">Hide Combo</td>
                  <td align="left"><input type="radio" name="combo_active" value="0" <? if($_REQUEST['combo_active']==0 && $_REQUEST['combo_active']!='') echo "checked";?>>
                    Yes
                    <input type="radio" name="combo_active" value="1" <? if($_REQUEST['combo_active']==1 || $_REQUEST['combo_active']=='') echo "checked";?>/>
                    No
                    &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr><?php */?>
                <tr>
                  <td align="left">Hide Combo Name</td>
                  <td align="left"><input type="radio" name="comb_hide" value="1" <? if($_REQUEST['comb_hide']==1) echo "checked";?>/>
                    Yes
                    <input name="comb_hide" type="radio" value="0" <? if($_REQUEST['comb_hide']==0) echo "checked";?> />
                    No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_COMBO_HIDENAME')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr>
                <tr>
                  <td align="left">Show in all</td>
                  <td align="left"><input type="checkbox" name="combo_showinall" value="1" <? if($_REQUEST['combo_showinall']==1) echo "checked";?> />
                    &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMBO_SHOWALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr>
                <tr>
                  <td align="left">Apply Customer Direct Discount also?</td>
                  <td align="left"><input type="checkbox" name="combo_apply_direct_discount_also" value="1" <? if($_REQUEST['combo_apply_direct_discount_also']=='Y') echo "checked";?> />
                    &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_DIRECT_DISC_COMBO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr>
                <tr>
                  <td align="left">Apply Customer Group Direct Discount also?</td>
                  <td align="left"><input type="checkbox" name="combo_apply_custgroup_discount_also" value="1" <? if($_REQUEST['combo_apply_custgroup_discount_also']=='Y') echo "checked";?> />
                    &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_GROUP_DISC_COMBO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr>
                <tr>
                  <td align="left">Change Active Period</td>
                  <td align="left"><input type="checkbox" name="combo_activateperiodchange"  onclick="activeperiod(this.checked,'<? echo $id?>')" value="1" <? if($_REQUEST['combo_activateperiodchange']==1) echo "checked";?> />
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_COMBO_ACTPERIOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr>
                <tr>
                  <td colspan="2" align="left"><table width="100%" cellpadding="0" cellspacing="0" id="<? echo $id;?>" style="display:<?=$display?>">
                    <tr >
                      <td width="16%" height="23" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
                      <td width="22%"  align="left" valign="middle">&nbsp;</td>
                      <td width="14%"  align="left" valign="middle">&nbsp;</td>
                      <td align="left" valign="middle">Hrs</td>
                      <td width="16%"  align="left" valign="middle">Min</td>
                      <td width="15%"  align="left" valign="middle">Sec</td>
                    </tr>
                    <tr >
                      <td align="left" valign="middle" class="tdcolorgray">StartDate </td>
                      <td  align="left" valign="middle"><input class="input" type="text" name="combo_displaystartdate" size="8" value="<?=$_REQUEST['combo_displaystartdate']?>" /></td>
                      <td  align="left" valign="middle"><a href="javascript:show_calendar('frmAddCombo.combo_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
                      <td width="17%" ><select name="combo_starttime_hr" id="combo_starttime_hr">
										<option value="<?php echo $_REQUEST['combo_starttime_hr']?>"><?php echo $_REQUEST['combo_starttime_hr']?></option>
										<?php echo $houroption?>
				  </select></td>
                      <td ><select name="combo_starttime_mn" id="combo_starttime_mn">
										<option value="<?php echo $_REQUEST['combo_starttime_mn']?>"><?php echo $_REQUEST['combo_starttime_mn']?></option>
										<?php echo $option?>
						</select></td>
                      <td ><select name="combo_starttime_ss" id="combo_starttime_ss">
                        <option value="<?php echo $_REQUEST['combo_starttime_ss']?>"><?php echo $_REQUEST['combo_starttime_ss']?></option>
                        <?php echo $option?>
                      </select></td>
                    </tr>
                    <tr >
                      <td align="left" valign="middle" class="tdcolorgray">EndDate</td>
                      <td  align="left" valign="middle"><input class="input" type="text" name="combo_displayenddate" size="8" value="<?=$_REQUEST['combo_displayenddate']?>" /></td>
                      <td  align="left" valign="middle"><a href="javascript:show_calendar('frmAddCombo.combo_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a></td>
                      <td ><select name="combo_endtime_hr" id="combo_endtime_hr">
										<option value="<?php echo $_REQUEST['combo_endtime_hr']?>"><?php echo $_REQUEST['combo_endtime_hr']?></option>
										<?php echo $houroption?>
						</select></td>
                      <td ><select name="combo_endtime_mn" id="combo_endtime_mn">
										<option value="<?php echo $_REQUEST['combo_endtime_mn']?>"><?php echo $_REQUEST['combo_endtime_mn']?></option>
										<?php echo $option?>
										</select></td>
                      <td ><select name="combo_endtime_ss" id="combo_endtime_ss">
                        <option value="<?php echo $_REQUEST['combo_starttime_ss']?>"><?php echo $_REQUEST['combo_starttime_ss']?></option>
                        <?php echo $option?>
                      </select></td>
                    </tr>
                  </table></td>
                </tr>
				
		   
              </table></td>
              <td width="54%" align="left" valign="top">Combo description </td>
            </tr>
            <tr>
              <td align="left" valign="top"><?php
											$editor_elements = "combo_description";
											include_once("js/tinymce.php");
											//include_once("classes/fckeditor.php");
											/*$editor 			= new FCKeditor('combo_description') ;
											$editor->BasePath 	= '/console/js/FCKeditor/';
											$editor->Width 		= '550';
											$editor->Height 	= '300';
											$editor->ToolbarSet = 'BshopWithImages';
											$editor->Value 		= stripslashes($_REQUEST['combo_description']);
											$editor->Create() ;*/
										   
							?>
							<textarea style="height:350px; width:550px" id="combo_description" name="combo_description"><?=stripslashes($_REQUEST['combo_description'])?></textarea>
							</td>
            </tr>
          </table></div>
		  </td>
    </tr>
       <tr>
		<td colspan="5" align="center" valign="middle" class="tdcolorgray">		</td>
		</tr>
		<tr>
         <td colspan="7" align="center" valign="middle" class="tdcolorgray">
		 <div class="editarea_div">
		 <table width="100%">
		 <tr>
		 	<td align="right" valign="middle">		  
			  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
			   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
			  <input name="Submit" type="submit" class="red" value="Submit" />
			</td>
		</tr>
		</table>
		</div>
		</td>
        </tr>
      </table>
</form>	  

