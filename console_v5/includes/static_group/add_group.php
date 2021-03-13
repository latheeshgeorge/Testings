<?php
	/*#################################################################
	# Script Name 	: add_group.php
	# Description 	: Page for adding Staic Page Group
	# Coded by 		: SKR
	# Created on	: 26-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Sataic Page Menu';
$help_msg = get_help_messages('ADD_STAT_PAGE_GROUP_MESS1');
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('group_name','group_position');
	fieldDescription = Array('Menu Name','Menu Position');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	fieldSpecChars = Array('group_name');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars)) {
	
			/* Check whether dispay location is selected*/
			obj = document.getElementById('display_id[]');
			if(obj.options.length==0)
			{
				alert('Display location is required');
				return false;
			}
			else
			{
				for(i=0;i<obj.options.length;i++)
				{
					if(obj.options[i].selected)
					{
						atleastone = true;
					}
				}
				if (atleastone==false)
				{
					alert('Please select the display location');
					return false;
				}
			}
			/* end CHECKING OF SELECTING POSITION*/
		
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmAddUser' action='home.php?request=stat_group' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=stat_group&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['group_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Static Page Menu</a><span> Add Menu</span></div></td>
        </tr>
        <tr>
		  <td colspan="4" align="left" valign="middle" class="helpmsgtd_main"><div class="helpmsg_divcls">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?></div>
		 </td>
		</tr>
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
      	<td colspan="4" class="listingarea">
	   <div class="editarea_div" >
	   <table class="tdcolorgray" width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="13%" align="left" valign="middle" class="tdcolorgray" >Page Menu Name <span class="redtext">*</span> </td>
          <td width="38%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="group_name"  value="<?=$_REQUEST['group_name']?>"  maxlength="100"/>		  </td>
          <td width="17%" align="left" valign="middle" class="tdcolorgray">Hide Name </td>
          <td width="32%" align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="group_hidename" value="1" <? if($_REQUEST['group_hidename']==1) echo "checked"?> />
          <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_STAT_GROUP_HIDENAME')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		 <tr>
          <td width="13%" align="left" valign="middle" class="tdcolorgray" >Display Location  <span class="redtext">*</span> </td>
          <td width="38%" align="left" valign="middle" class="tdcolorgray">
		  <?php
			// Get the list of position allovable for category groups for the current theme
			$sql_themes = "SELECT page_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$statpos_arr	= explode(",",$row_themes['page_positions']);
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
							if(in_array($pos_arr[$i],$statpos_arr))
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
								
										$curname			= stripslashes($mobrow_layouts['layout_name'])." (".$mobpos_arr[$i].")";
										$mobdisp_array[$curid] = $curname;
									
								
							}	
						}
					}		
				}
			}
		}
		echo generateselectboxoption('display_id[]',$disp_array,$_REQUEST['display_id'],$mobdisp_array,$_REQUEST['display_id'],'','',5);

			
		  ?>
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_STAT_GROUP_DISPLOC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		<!--  <select name="group_position[]" multiple="multiple">
		 
		  <?//
		  /* #Getting position values of the site theme
		  $sql_position="SELECT page_positions FROM themes a,sites b WHERE b.site_id=$ecom_siteid AND a.theme_id=b.themes_theme_id";
		  $res_position = $db->query($sql_position);
		  $row_position = $db->fetch_array($res_position);
		  $val_page_positions=$row_position['page_positions'];
		  $exp_val_page_positions=explode(',',$val_page_positions);
		  foreach($exp_val_page_positions as $v)
		  {
		  	echo "<option value=$v>$v</option>";
		  }*/
	   	  ?>

		  </select>-->		  </td>
          <td colspan="2" align="left" valign="top" class="tdcolorgray"><table width="100%" border="0">
            <tr>
              <td width="34%">Show in all pages </td>
              <td width="66%"><input class="input" type="checkbox" name="group_showinall"  value="1" <? if($_REQUEST['group_showinall']==1) echo "checked"?> />
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_STAT_GROUP_SHOWALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            <tr>
              <td>Hidden</td>
              <td><input type="radio" name="group_hide" value="1" <? if($_REQUEST['group_hide']==1) echo "checked"?>  />
                Yes
                  <input type="radio" name="group_hide" value="0" <? if($_REQUEST['group_hide']==0) echo "checked"?> />
              No <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_STAT_GROUP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
          </table></td>
	    </tr>
		 <tr>
          <td width="13%" align="left" valign="middle" class="tdcolorgray" >Page Menu Format </td>
          <td width="38%" align="left" valign="middle" class="tdcolorgray"><select name="group_listtype" id="group_listtype"><option value="Menu" >Menu</option><option value="Dropdown">Dropdown Box</option></select></td>
          <td align="left" valign="middle" class="tdcolorgray">Show Home page link </td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="group_showhomelink"  value="1" <? if($_REQUEST['group_showhomelink']==1) echo "checked";?> />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_STAT_GROUP_SHOWHOMEPAGE_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	    </tr>
		 <tr>
		   <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		   <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">Show Sitemap link </td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="group_showsitemaplink"  value="1" <? if($_REQUEST['group_showsitemaplink']==1) echo "checked";?> />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_STAT_GROUP_SHOWSITEMAP_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	    </tr>
		 <tr>
          <td width="13%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="38%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray" >Show Help link </td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="group_showhelplink"  value="1" <? if($_REQUEST['group_showhelplink']==1) echo "checked";?> />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_STAT_GROUP_SHOWHELP_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	    </tr>
       
         <tr>
           <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
           <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
           <td align="left" valign="middle" class="tdcolorgray">Show FAQ link </td>
           <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="group_showfaqlink"  value="1" <? if($_REQUEST['group_showfaqlink']==1) echo "checked";?> />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_STAT_GROUP_SHOWFAQ_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">Show Saved Search link </td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="group_showsavedsearchlink"  value="1" <? if($_REQUEST['group_showsavedsearchlink']==1) echo "checked";?> />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_SAVEDSEARCH_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <?php /*?><tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">Show XML Sitemap link </td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="group_showxmlsitemaplink"  value="1" <? if($_REQUEST['group_showxmlsitemaplink']==1) echo "checked";?> />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_XML_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr><?php */?>
		</table>
		</div>
		</td>
		</tr>
		<tr>
          <td colspan="4" align="center" valign="top">
		   <div class="editarea_div" >
		   <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="right" valign="middle" >
				  <input type="hidden" name="pass_group_name" id="pass_group_name" value="<?=$_REQUEST['pass_group_name']?>" />
				   <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['start']?>" />
				  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['sort_by']?>" />
				  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['sort_order']?>" />
				  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
				  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pg']?>" />
				  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
				  <input name="Submit" type="submit" class="red" value="Save" />
				  <input name="Submit" type="submit" class="red" value="Save & Return to Edit" />
				</td>
			</tr>
			</table>
			</div>
			</td>
        </tr>
      </table>
</form>	  

