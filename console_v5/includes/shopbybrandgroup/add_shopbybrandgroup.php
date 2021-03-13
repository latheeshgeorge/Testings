<?php
	/*#################################################################
	# Script Name 	: add_shopbybrandgroup.php
	# Description 	: Page for adding Product Shop by brand group
	# Coded by 		: Sny
	# Created on	: 13-Dec-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type 	= 'Product Shop Groups';
$help_msg 	= get_help_messages('ADD_PROD_SHOP_GROUP1');

?>	
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('shopbrandgroup_name');
	fieldDescription = Array('Product Shop Group Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(document.frmAddShopByBrandGroup.shopbrandgroup_listtype.value=='Dropdown' && document.frmAddShopByBrandGroup.shopbrandgroup_subcatlisttype.value=='List')
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
			//show_processing();
			//return true;
		}	
		
		
		if(document.frmAddShopByBrandGroup.shopbrandgroup_activateperiodchange.checked  == true){
			
			val_dates = compareDates(document.frmAddShopByBrandGroup.shopbrandgroup_displaystartdate,"Start Date\n Correct Format:dd-mm-yyyy ",document.frmAddShopByBrandGroup.shopbrandgroup_displayenddate,"End Date\n Correct Format:dd-mm-yyyy");
			if(val_dates ){
				  show_processing();
				  return true;
			 }else{
			 return false
			 }
		}else{
		 show_processing();
		return true;
		}
		
	} else {
		return false;
	}
}
function change_show_date_period()
{
	
	if(document.frmAddShopByBrandGroup.shopbrandgroup_activateperiodchange.checked==true)
	{
		document.getElementById('show_date_period').style.display = '';
	}
	else
	{
		document.getElementById('show_date_period').style.display = 'none';
	}
}
</script>
<form name='frmAddShopByBrandGroup' action='home.php?request=shopbybrandgroup' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=shopbybrandgroup&start=<?php echo $_REQUEST['start']?>&p_f=<?php echo $_REQUEST['p_f']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>">List Product Shop Groups </a><span> Add Product Shop Group </span></div></td>
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
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
        <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Product Shop Group Name <span class="redtext">*</span> </td>
          <td width="32%" align="left" valign="middle" class="tdcolorgray"><input name="shopbrandgroup_name" type="text" class="input" size="25" value="<?php echo $_REQUEST['shopbrandgroup_name']?>"  maxlength="100"/></td>
          <td width="19%" align="left" valign="middle" class="tdcolorgray">Hide Product Shop Group </td>
          <td width="29%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="shopbrandgroup_hide" value="1" <? if($_REQUEST['shopbrandgroup_hide']==1) echo "checked" ?> />
            Yes
              <input name="shopbrandgroup_hide" type="radio" value="0" <? if($_REQUEST['shopbrandgroup_hide']==0) echo "checked" ?> />
          No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHOP_BRAND_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Display Location <span class="redtext">*</span></td>
           <td align="left" valign="top" class="tdcolorgray"><?php
			// Get the list of position allovable for shopbybrandgroup for the current theme
			$sql_themes = "SELECT shopbybrand_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$shoppos_arr	= explode(",",$row_themes['shopbybrand_positions']);
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
							if(in_array($pos_arr[$i],$shoppos_arr))
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

		  ?>
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHOP_BRAND_GROUP_LOC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
           <td align="left" valign="top" class="tdcolorgray" nowrap="nowrap">Hide Product Shop Group Name </td>
           <td align="left" valign="top" class="tdcolorgray"><input type="radio" name="shopbrandgroup_hidename" value="1" <? if($_REQUEST['shopbrandgroup_hidename']==1) echo "checked" ?> />
Yes
  <input name="shopbrandgroup_hidename" type="radio" value="0" <? if($_REQUEST['shopbrandgroup_hidename']==0) echo "checked" ?> />
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHOP_BRAND_GROUP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Show in all Pages</td>
           <td align="left" valign="top" class="tdcolorgray"><input type="radio" name="shopbrandgroup_showinall" value="1" <? if($_REQUEST['shopbrandgroup_showinall']==1) echo "checked" ?> />
Yes
  <input name="shopbrandgroup_showinall" type="radio" value="0" <? if($_REQUEST['shopbrandgroup_showinall']==0) echo "checked" ?> />
No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHOP_BRAND_GROUP_SHOWALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
           <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
           <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
         </tr>
         <tr>
          <td align="left" valign="top" class="tdcolorgray" >Shop List Type </td>
          <td align="left" valign="top" class="tdcolorgray"><?php 
				//$grp_type = array('Menu'=>'Menu','Dropdown'=>'Dropdown Box');
				$grp_type = array('Menu'=>'Menu','Dropdown'=>'Dropdown Box','Header'=>'Header Only');
				echo generateselectbox('shopbrandgroup_listtype',$grp_type,$_REQUEST['shopbrandgroup_listtype']);
			?>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHOP_BRAND_GROUP_LISTTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="left" valign="middle" class="tdcolorgray"><!--Sub Shop List Type --></td>
          <td align="left" valign="middle" class="tdcolorgray"><?php 
			//	$subcat_list = array('Middle'=>'Show in Middle Area','List'=>'Show below Selected Shop');
			//	echo generateselectbox('shopbrandgroup_subcatlisttype',$subcat_list,$_REQUEST['shopbrandgroup_subcatlisttype']);
		  ?>
           <!-- <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHOP_BRAND_GROUP_SUBTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> --></td>
        </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Show shops rotator? </td>
           <td align="left" valign="top" class="tdcolorgray"><input type="checkbox" name="shopbrandgroup_display_rotator" id="shopbrandgroup_display_rotator" value="1" <?php echo ($_REQUEST['shopbrandgroup_display_rotator']==1)?'checked="checked"':''?>/>
           <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_GROUP_SHOP_ROTATE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
           <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
           <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
         </tr>
		  <tr>
		    <td colspan="2" align="left" valign="middle" class="tdcolorgray" ><b>Active Period</b></td>
		    <td colspan="2" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
    </tr>
		  <tr>
		    <td colspan="4" align="left" valign="top" class="tdcolorgray" ><table width="100%" border="0">
              <tr>
                <td width="17%" align="left" valign="middle" class="tdcolorgray" >Change Active Period </td>
                <td width="83%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" id="shopbrandgroup_activateperiodchange" name="shopbrandgroup_activateperiodchange" onclick="change_show_date_period()" value="1" <?php echo ($_REQUEST['shopbrandgroup_activateperiodchange']==1)?'checked="checked"':''?> />                </td>
              </tr>
			  <?php
			  	if($_REQUEST['shopbrandgroup_activateperiodchange']==1)
				{
				  $display='';$display='';
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
              <tr id="show_date_period" style="display:<?php echo $display?>">
                <td colspan="2" align="left" valign="middle" class="tdcolorgray" ><table width="100%" cellpadding="0" cellspacing="2" border="0">
                    <tr>
                      <td align="left" valign="middle"  >&nbsp;</td>
                      <td align="left" valign="middle"  >&nbsp;</td>
                      <td align="left" valign="middle" >&nbsp;</td>
                      <td align="left" valign="middle" >&nbsp;</td>
                      <td align="left" valign="middle" >Hrs</td>
                      <td align="left" valign="middle" >Min</td>
                      <td align="left" valign="middle" >Sec</td>
                      <td align="left" valign="middle" >&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="17%" align="left" valign="middle"  >&nbsp;</td>
                      <td width="11%" align="left" valign="middle"  >Start Date</td>
                      <td width="10%" align="left" valign="middle" ><input name="shopbrandgroup_displaystartdate" type="text" class="input" id="shopbrandgroup_displaystartdate" size="8" value="<?php echo $_REQUEST['shopbrandgroup_displaystartdate']?>" />                      </td>
                      <td width="6%" align="left" valign="middle" ><a href="javascript:show_calendar('frmAddShopByBrandGroup.shopbrandgroup_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a> </td>
                      <td width="7%" align="left" valign="middle" ><select name="shopbrandgroup_starttime_hr" id="shopbrandgroup_starttime_hr">
										<option value="<?php echo $_REQUEST['shopbrandgroup_starttime_hr']?>"><?php echo $_REQUEST['shopbrandgroup_starttime_hr']?></option>
										<?php echo $houroption?>
					  </select></td>
                      <td width="7%" align="left" valign="middle" ><select name="shopbrandgroup_starttime_mn" id="shopbrandgroup_starttime_mn">
										<option value="<?php echo $_REQUEST['shopbrandgroup_starttime_mn']?>"><?php echo $_REQUEST['shopbrandgroup_starttime_mn']?></option>
										<?php echo $option?>
					  </select></td>
                      <td width="7%" align="left" valign="middle" ><select name="shopbrandgroup_starttime_ss" id="shopbrandgroup_starttime_ss">
										<option value="<?php echo $_REQUEST['shopbrandgroup_starttime_ss']?>"><?php echo $_REQUEST['shopbrandgroup_starttime_ss']?></option>
										<?php echo $option?>
					  </select></td>
                      <td width="35%" align="left" valign="middle" >&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="17%" align="left" valign="middle"  >&nbsp;</td>
                      <td width="11%" align="left" valign="middle"  >End Date</td>
                      <td width="10%" align="left" valign="middle" ><input name="shopbrandgroup_displayenddate" type="text" class="input" id="shopbrandgroup_displayenddate" size="8" value="<?php echo $_REQUEST['shopbrandgroup_displayenddate']?>" />                      </td>
                      <td width="6%" align="left" valign="middle" ><a href="javascript:show_calendar('frmAddShopByBrandGroup.shopbrandgroup_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a> </td>
                      <td width="7%" align="left" valign="middle" ><select name="shopbrandgroup_endtime_hr" id="shopbrandgroup_endtime_hr">
										<option value="<?php echo $_REQUEST['shopbrandgroup_endtime_hr']?>"><?php echo $_REQUEST['shopbrandgroup_endtime_hr']?></option>
										<?php echo $houroption?>
					  </select></td>
                      <td width="7%" align="left" valign="middle" ><select name="shopbrandgroup_endtime_mn" id="shopbrandgroup_endtime_mn">
										<option value="<?php echo $_REQUEST['shopbrandgroup_endtime_mn']?>"><?php echo $_REQUEST['shopbrandgroup_endtime_mn']?></option>
										<?php echo $option?>
					  </select></td>
                      <td width="7%" align="left" valign="middle" ><select name="shopbrandgroup_endtime_ss" id="shopbrandgroup_endtime_ss">
										<option value="<?php echo $_REQUEST['shopbrandgroup_endtime_ss']?>"><?php echo $_REQUEST['shopbrandgroup_endtime_ss']?></option>
										<?php echo $option?>
					  </select></td>
                      <td width="35%" align="left" valign="middle" >&nbsp;</td>
                    </tr>
                </table></td>
              </tr>
            </table></td>
		  </tr>
		  </table>
		  </div>
		  </td>
		  </tr>
		  <tr>
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">		  
	
        <tr>
          <td align="right" valign="middle" class="tdcolorgray" colspan="4" >		  
		  <input type="hidden" name="shopgroupname" id="shopgroupname" value="<?=$_REQUEST['shopgroupname']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		   <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="save_add" />
          <input name="shopbrandgroup_Submit" type="submit" class="red" value="Save" />
        &nbsp;&nbsp;</td>
        </tr>        
        </table>
        </div>
        </td>
        </tr>
      </table>
</form>	  

