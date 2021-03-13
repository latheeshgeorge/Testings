<?php
	/*#################################################################
	# Script Name 	: add_shelfgroup.php
	# Description 	: Page for adding Shelf  Group
	# Coded by 		: Joby
	# Created on	: 29-Apr-2011

	#################################################################*/
#Define constants for this page
$page_type = 'Shelf Menus';
//$help_msg = 'This section helps in adding the Shelves';
$help_msg = get_help_messages('ADD_SHELF_MENU_MESS1');
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('name','display_id[]');
	fieldDescription = Array('Shelf Menu Name','Shelf Position');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(frm.shelf_activateperiodchange.checked  ==true) {
			val_dates = compareDates(frm.shelf_displaystartdate,"Start Date\n Correct Format:dd-mm-yyyy ",frm.shelf_displayenddate,"End Date\n Correct Format:dd-mm-yyyy");
			if(val_dates ){
				  show_processing();
				  return true;
			 }else{
			 return false
			 }
		}

		else{
		show_processing();    		

		return true;
		}
	} else {
		return false;
	}
}


</script>
<form name='frmAddShelfgroup' action='home.php?request=shelfgroup' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=shelfgroup&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Shelf Menus</a><span> Add Shelf Menu</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
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
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
		<td width="99%" valign="top" class="tdcolorgray" >
		<div class="editarea_div">
		<table width="99%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="22%" align="left" valign="middle" class="tdcolorgray" >Shelf Menu Name <span class="redtext">*</span> </td>
          <td width="78%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="name" value="<?=$_REQUEST['name']?>"  />
		  </td>
        </tr>
		 <tr>
          <td  align="left" valign="middle" class="tdcolorgray" >Shelf Menu Position <span class="redtext">*</span></td>
          <td  align="left" valign="middle" class="tdcolorgray">
		  <?php
			// Get the list of position allovable for category groups for the current theme
			$sql_themes = "SELECT shelfgroup_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$shelfpos_arr	= explode(",",$row_themes['shelfgroup_positions']);
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
							if(in_array($pos_arr[$i],$shelfpos_arr))
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
			$sql_mobthemes = "SELECT shelfgroup_positions FROM themes WHERE theme_id=$ecom_mobilethemeid";
			$ret_mobthemes = $db->query($sql_mobthemes);
			if ($db->num_rows($ret_mobthemes))
			{
				$mobrow_themes = $db->fetch_array($ret_mobthemes);
				$mobcatpos_arr	= explode(",",$mobrow_themes['shelfgroup_positions']);
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
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHELF_MENUS_DISPLOC')?>.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
        </tr>


		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >Hide</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="hide" value="1"  <? if($_REQUEST['hide']==1) echo "checked";?> />Yes<input type="radio" name="hide" value="0" <? if($_REQUEST['hide']==0) echo "checked";?>  />No
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHELF_MENUS_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
       
        <tr>
          <td align="left" valign="middle" class="tdcolorgray"  >Show in all &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHELF_MENUS_SHOW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="showinall" value="1" <? if($_REQUEST['showinall']==1) echo "checked";?> />		  </td>
        </tr>
		</table></div>
		</td>
       </tr>        
        <tr>
         <td align="right" valign="middle" class="tdcolorgray">
		 	<div class="editarea_div">
			<table width="100%">
			<tr><td width="100%" align="right" valign="middle">
			  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
			   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
			  <input name="Submit" type="submit" class="red" value="Save" />
			  </td></tr>
			  </table>
			  </div>
		  </td>
        </tr>
      </table>
</form>	  

