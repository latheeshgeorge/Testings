<?php
	/*#################################################################
	# Script Name 	: add_product_category_groups.php
	# Description 	: Page for adding Product Category Groups
	# Coded by 		: Sny
	# Created on	: 14-June-2007
	# Modified by	: Sny
	# Modified On	: 26-Jun-2007
	#################################################################*/
#Define constants for this page
$page_type = 'Product Category Groups';
$help_msg = get_help_messages('ADD_PROD_CAT_GROUP1');

	$arr_style 	= $val_arr = array();
	$val_arr['None']  = 'None';
	$sql_style	= "SELECT image_listingstyles,theme_top_cat_dropdownmenu_support FROM themes WHERE theme_id=".$ecom_themeid;
	$ret_style 	= $db->query($sql_style);
	if ($db->num_rows($ret_style))
	{
		$row_style	= $db->fetch_array($ret_style);
		$subcatdropdownsupport = $row_style['theme_top_cat_dropdownmenu_support'];
		$arr_style	= explode(',',$row_style['image_listingstyles']);
		if (count($arr_style))
		{
			foreach($arr_style as $v)
			{
				$temp_arr = explode("=>",$v);
				$val_arr[$temp_arr[0]]=trim($temp_arr[1]);	 // this array will be used for all the following image type drop down boxes
			}
		}				
	}

?>	
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('catgroup_name');
	fieldDescription = Array('Group Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('catgroup_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(document.frmAddProductCategoryGroup.catgroup_listtype.value=='Dropdown' && document.frmAddProductCategoryGroup.catgroup_subcatlisttype.value=='List')
		{
			alert('Subcategory List Type does not support Group List Type');
			return false;
		}
		else
		{
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
			show_processing();
			return true;
		}	
	} else {
		return false;
	}
}
function handle_dropstyle(obj)
{
	if(obj.checked==true)
	{
		if(document.getElementById('subcatdrop_tr'))
			document.getElementById('subcatdrop_tr').style.display = '';
		
	}	
	else
	{
		if(document.getElementById('subcatdrop_tr'))
			document.getElementById('subcatdrop_tr').style.display = 'none';
	}
}
</script>
<form name='frmAddProductCategoryGroup' action='home.php?request=prod_cat_group' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_cat_group&start=<?php echo $_REQUEST['start']?>&p_f=<?php echo $_REQUEST['p_f']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>">List  Category Menus</a><span> Add  Category Menu</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>		 </td>
		</tr>
		<?php
			if($alert)
			{
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
    </tr>
		 <?php
		 	}
		 ?> 
		 <tr>
          <td colspan="4" align="center" valign="middle">
		  <div class="editarea_div" >
		  <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="16%" height="23" align="left" valign="middle" class="tdcolorgray" >Menu name <span class="redtext">*</span> </td>
          <td width="35%" align="left" valign="middle" class="tdcolorgray"><input name="catgroup_name" type="text" class="input" size="25" value="<?php echo $_REQUEST['catgroup_name']?>" /></td>
          <td width="25%" align="left" valign="middle" class="tdcolorgray">Hide Menu </td>
          <td width="24%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="catgroup_hide" value="1" <?php if($_REQUEST['catgroup_hide']==1) echo "checked"?> />
            Yes
              <input name="catgroup_hide" type="radio" value="0" <?php if($_REQUEST['catgroup_hide']==0) echo "checked"?> />
          No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
         <tr>
           <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
           <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
           <td align="left" valign="middle" class="tdcolorgray">Hide Menu Name</td>
           <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="catgroup_hidename" value="1" <?php if($_REQUEST['catgroup_hidename']==1) echo "checked"?> />
Yes
  <input name="catgroup_hidename" type="radio" value="0" <?php if($_REQUEST['catgroup_hidename']==0) echo "checked"?> />
No <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_HIDENAME')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
         <tr>
          <td align="left" valign="top" class="tdcolorgray" >Display Location  <span class="redtext">*</span></td>
          <td align="left" valign="top" class="tdcolorgray">
		  <?php
			// Get the list of position allovable for category groups for the current theme
			$sql_themes = "SELECT categorygroup_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$catpos_arr	= explode(",",$row_themes['categorygroup_positions']);
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
							if(in_array($pos_arr[$i],$catpos_arr))
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
			$sql_mobthemes = "SELECT categorygroup_positions FROM themes WHERE theme_id=$ecom_mobilethemeid";
			$ret_mobthemes = $db->query($sql_mobthemes);
			if ($db->num_rows($ret_mobthemes))
			{
				$mobrow_themes = $db->fetch_array($ret_mobthemes);
				$mobcatpos_arr	= explode(",",$mobrow_themes['categorygroup_positions']);
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

			//echo generateselectbox('display_id[]',$disp_array,$_REQUEST['display_id'],'','',5);
		  ?>
		 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_LOC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="left" valign="top" class="tdcolorgray" ><p>Menu Format </p></td>
          <td align="left" valign="top" class="tdcolorgray"><?php 
				$grp_type = array('Menu'=>'Menu','Dropdown'=>'Dropdown Box');
				echo generateselectbox('catgroup_listtype',$grp_type,$_REQUEST['catgroup_listtype']);
			?>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_GTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
	    <tr>
		    <td align="left" valign="middle" class="tdcolorgray" >Category Image Type <br /></td>
		    <td colspan="3" align="left" valign="middle" class="tdcolorgray"><?= generateselectbox('category_showimagetype',$val_arr,''); //$fetch_arr_admin['category_showimagetype'] ?>	          (applicable only if this category group in assigned to home page) </td>
	    </tr>
		  <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Show in all Pages</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="catgroup_showinall" value="1" <?php if($_REQUEST['catgroup_showinall']==1) echo "checked"?>/>
Yes
  <input name="catgroup_showinall" type="radio" value="0" <?php if($_REQUEST['catgroup_showinall']==0) echo "checked"?>/>
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_SHOWALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="left" valign="middle" class="tdcolorgray"><?php 
		  if($subcatdropdownsupport)
		  {
		  ?>
Show Subcategories listing in Dropdown menu?
  <?php
		  }
		  ?></td>
          <td align="left" valign="middle" class="tdcolorgray"><?php 
		  if($subcatdropdownsupport)
		  {
		  ?>
            <input type="checkbox" name="catgroup_show_subcat_indropdown" id="catgroup_show_subcat_indropdown" value="1" checked="checked" onchange="handle_dropstyle(this)"/>
            <?php
		  }
		  ?></td>
	    </tr>
		<?php 
		  if($subcatdropdownsupport)
		  {
		  ?>
			  <tr id="subcatdrop_tr">
				<td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
				<td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
				<td align="right" valign="middle" class="tdcolorgray">Subcategory Style in Dropdown Menu </td>
				<td align="left" valign="middle" class="tdcolorgray">
				<select name="catgroup_show_subcat_indropdown_subcount" id="catgroup_show_subcat_indropdown_subcount">
				<option value="1">Show First Level Subcategories Only</option>
				<option value="2" selected>Show First and Second Level Subcategories</option>
				</select>
				</td>
			 </tr>
		  <?php
		  }
		  ?>	 
		  <tr>
            <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
            <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
          <td colspan="2" align="center" valign="middle" class="tdcolorgray">
		  <?php 
		  if($subcatdropdownsupport)
		  {
		  ?>
(Applicable only if category menu is assigned to top area)
  <?php
		  }
		  ?></td>
        </tr>
		</table>
		</div>
		</td>
		</tr>
		
		<tr>
			<td colspan="4" align="right" valign="middle" class="tdcolorgray" >
				<div class="editarea_div" >
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="100%" align="right" valign="middle">
							<input type="hidden" name="catgroupname" id="catgroupname" value="<?=$_REQUEST['catgroupname']?>" />
							<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
							<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
							<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
							<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
							<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
							<input type="hidden" name="fpurpose" id="fpurpose" value="save_add" />
							<input name="catgroup_Submit" type="submit" class="red" value="Save" />
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		
  </table>
</form>	  

