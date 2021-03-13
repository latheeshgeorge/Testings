<?php
#################################################################
# Script Name 	: edit_theme_layouts.php
# Description 	: Page for editing layouts
# Coded by 	: SKR
# Created on	: 02-June-2007
# Modified by	: Sny
# Modified On	: 15-Jan-2010
#################################################################

#Define constants for this page
$page_type = 'Theme Layout';
$help_msg = 'This section helps in editing the Layouts for a Theme.';
//#Sql
$sql_theme = "SELECT layout_code,themes_theme_id,layout_name,layout_positions,layout_support_cart FROM themes_layouts WHERE layout_id=".$_REQUEST['layout_id'];
$res_theme = $db->query($sql_theme);
$row 		= $db->fetch_array($res_theme);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('themename','path');
	fieldDescription = Array('Theme Name','Theme Path');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmEditTheme' action='home.php?request=themes' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=themes&theme_name=<?=$_REQUEST['pass_theme_name']?>&themetype=<?=$_REQUEST['pass_themetype']?>&sort_by=<?=$_REQUEST['pass_sort_by']?>&sort_order=<?=$_REQUEST['pass_sort_order']?>&records_per_page=<?=$_REQUEST['pass_records_per_page']?>&pg=<?=$_REQUEST['pass_pg']?>" title="List Themes">List Themes</a><strong> <font size="1">>></font> Edit <?=$page_type?></strong></td>
      </tr>
  
      <tr>
        <td class="maininnertabletd3">
			<?=$help_msg?>
		</td>
      </tr>
	  <tr>
        <td class="maininnertabletd2">
			<table width="100%"  border="0" cellpadding="4" cellspacing="1" class="">
				<tr align="left">
				  <td colspan="3" class="redtext"><div align="left">* <span>are required </span></div></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Layout name</td>
				  <td align="center">:</td>
				  <td align="left"><input name="layout_name" type="text" id="layout_name" value="<?=$row['layout_name']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Layout Code</td>
				  <td align="center">:</td>
				  <td align="left"><input name="layout_code" type="text" id="layout_code" value="<?=$row['layout_code']?>" size="30">
				    <span class="redtext">*</span> </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Layout Positions</td>
				  <td align="center">:</td>
				  <td align="left">
				  <?
				  //$arr_layout_positions=explode(',',$row['layout_positions']);
				  
				  ?>
				  <input name="layout_positions" type="text" id="layout_positions" value="<?=$row['layout_positions']?>" size="30">
				  <!--<select name="layout_positions[]" multiple="multiple">
				  
				  <option value="left" <? if(in_array('left',$arr_layout_positions)) echo "selected";?>>left</option>
				  <option value="right" <? if(in_array('right',$arr_layout_positions)) echo "selected";?>>right</option>
				  <option value="inline" <? if(in_array('inline',$arr_layout_positions)) echo "selected";?>>inline</option>
				  <option value="top" <? if(in_array('top',$arr_layout_positions)) echo "selected";?>>top</option>
				  </select>-->
				  </td>
				</tr>
                                 <tr>
                                  <td align="right" class="fontblacknormal">Support Product Details</td>
                                  <td align="center">:</td>
                                  <td align="left"><input name="layout_support_cart" type="checkbox" id="layout_support_cart" value="1" <? echo ($row['layout_support_cart'])?'checked':''?> size="30">
                                    <span class="redtext">*</span> </td>
                                </tr>
				<tr>
				  <td align="right" class="fontblacknormal">&nbsp;</td>
				  <td align="center">&nbsp;</td>
				  <td align="left">&nbsp;</td>
			  </tr>
				<tr>
				  <td align="right" class="fontblacknormal">&nbsp;</td>
				  <td align="center">&nbsp;</td>
				  <td align="left">&nbsp;</td>
			  </tr>
				<tr>
				  <td colspan="3" align="left" class="menutabletoptd">Default Components Positions</td>
				</tr>
				<tr>
				  <td colspan="3" align="left" >
				  <table width="90%" border="0" cellpadding="1" cellspacing="1" align="center">
				  <tr>
				  <td align="left" class="fontblacknormal"><b>Feature Ttile</b></td>
				  <td align="left"><b>Position</b></td>
				</tr>
				<?
				/*$sql_feature="SELECT feature_id,feature_title FROM features WHERE feature_insite=1 AND feature_displaytouser=1";
				$res_feature = $db->query($sql_feature);
				$featu_cnt=0;
				while($row_feature= $db->fetch_array($res_feature))
				{
					
					$featu_cnt++;
					//Fetching already assigned default value
					$sql_default="SELECT def_position,def_order FROM themes_layouts_features_default_positions WHERE themes_layouts_layout_id=".$_REQUEST['layout_id'] . " AND features_feature_id=".$row_feature['feature_id'];
					$res_default = $db->query($sql_default);
					$def_position='';
					$def_order='';
					if(mysql_num_rows($res_default))
					{
						$row_default=$db->fetch_array($res_default);
						$def_position=$row_default['def_position'];
						$def_order=$row_default['def_order'];
						
					
					}
					
				?>
			
				<tr>
				  <td align="center" class="fontblacknormal"><?=$row_feature['feature_title']?></td>
				  <td align="center"><select name="feature_position<?=$featu_cnt?>">
				  <option value="">-select-</option>
				  <?
				  $exp_layout_positions=explode(',',$row['layout_positions']);
				  foreach ($exp_layout_positions as $v)
				  {
				  ?>
				  <option value="<?=$v?>" <? if($def_position==$v) echo "selected";?>><?=$v?></option>
				  <?
				  }
				  ?>
				  </select></td>
				  <td align="left">
				  
				  
				  &nbsp;<input name="feature_order<?=$featu_cnt?>" type="text" id="feature_order<?=$featu_cnt?>" value="<?=$def_order?>" size="5">
				        <input type="hidden" name="feature_id<?=$featu_cnt?>" id="feature_id<?=$featu_cnt?>" value="<?=$row_feature['feature_id']?>" />
				    </td>
				</tr>
			
				<?	
				}  */
				// Get the rows from themes_layouts_feature_special_position_components. These values decide how to split the positions
                                $special_comp = array();
                                $sql_comp_pos = "SELECT features_feature_modulename, themes_mapping_fields,direct_entry 
                                                    FROM 
                                                        themes_layouts_feature_special_position_components";
                                $ret_comp_pos = $db->query($sql_comp_pos);
                                if($db->num_rows($ret_comp_pos))
                                {
                                    while($row_comp_pos = $db->fetch_array($ret_comp_pos))
                                    {
                                        $special_comp[$row_comp_pos['features_feature_modulename']] = array('map_field'=>$row_comp_pos['themes_mapping_fields'],'direct_entry'=>$row_comp_pos['direct_entry']);
                                    }
                                }
                                
                                $sql_feature="SELECT feature_id,feature_title,feature_modulename FROM features WHERE feature_displayinallowedposition=1 ORDER BY feature_title";
                                $res_feature = $db->query($sql_feature);
                                $featu_cnt=0;
                                while($row_feature= $db->fetch_array($res_feature))
                                {
                                    $featu_cnt++;
                                    $allow_position     = array();
                                    //Fetching already assigned default value
                                    $sql_default        ="SELECT allow_positions FROM themes_layouts_feature_allowed_positions WHERE themes_layouts_layout_id=".$_REQUEST['layout_id'] . " AND features_feature_id=".$row_feature['feature_id'];
                                    $res_default        = $db->query($sql_default);
                                    $def_position       = array();
                                    if(mysql_num_rows($res_default))
                                    {
                                        $row_default    = $db->fetch_array($res_default);
                                        $get_position   = $row_default['allow_positions'];
                                    }
                                ?>
                                <tr>
                                  <td align="left" class="fontblacknormal" style="width:35%" valign='top'><?=$row_feature['feature_title']?></td>
                                  <td align="left" valign='top'>
                                  <?php
                                  $show_direct_position = false;
                                  $special_arr = $specialtemp_arr = array();
                                    $saved_pos = array();
                                    // Check whether current component is there in $special_comp array
                                    if(array_key_exists($row_feature['feature_modulename'],$special_comp))
                                    {
                                       if($special_comp[$row_feature['feature_modulename']]['map_field']!='') // if the 
                                       {
                                            // get the listing type from themes table
                                            $sql_theme = "SELECT ".$special_comp[$row_feature['feature_modulename']]['map_field']." 
                                                            FROM 
                                                                themes 
                                                            WHERE 
                                                                theme_id=".$row['themes_theme_id']." 
                                                            LIMIT 
                                                                1";
                                            $ret_theme = $db->query($sql_theme);
                                            if($db->num_rows($ret_theme))
                                            {
                                                list($sp_val) = $db->fetch_array($ret_theme);
                                                $specialmain_arr = explode(',',$sp_val);
                                                foreach ($specialmain_arr as $k=>$v)
                                                {
                                                    $specialtemp_arr = explode('=>',$v);
                                                    $special_arr[$specialtemp_arr[0]] = $specialtemp_arr[1];
                                                }
                                            }
                                        }
                                        else // case if values to be dispolayed is given directly in table
                                        {
                                            $sp_val = $special_comp[$row_feature['feature_modulename']]['direct_entry'];
                                            $specialmain_arr = explode(',',$sp_val);
                                            foreach ($specialmain_arr as $k=>$v)
                                            {
                                                $specialtemp_arr = explode('=>',$v);
                                                $special_arr[$specialtemp_arr[0]] = $specialtemp_arr[1];
                                            }
                                        }
                                        $saved_pos = array();
                                        // Picking the already set positions
                                        if(strstr($get_position,'~')===false)
                                        {
                                            if(strstr($get_position,'=>')===false)
                                            {
                                                $saved_pos = explode(',',$get_position);
                                            }
                                            else
                                            {
                                                $saved_pos_temp = explode('=>',$get_position);
                                                $saved_pos_temp2 = explode(',',$saved_pos_temp[1]);
                                                $saved_pos[$saved_pos_temp[0]] = $saved_pos_temp2;
                                            }
                                        }
                                        else
                                        {
                                           $saved_temp  = explode('~',$get_position);
                                           foreach ($saved_temp as $savkey=>$savval)
                                           {
                                                $saved_pos_temp = explode('=>',$savval);
                                                $saved_pos_temp2 = explode(',',$saved_pos_temp[1]);
                                                $saved_pos[$saved_pos_temp[0]] = $saved_pos_temp2;
                                           }
                                        }
                                    }
                                    else
                                    {
                                        $allow_position = explode(',',$get_position);
                                        $show_direct_position = true;
                                    }
                                     if($show_direct_position == true)
                                     {
                                  ?>
                                        <select name="feature_position<?=$featu_cnt?>[]" size="5" multiple>
                                        <?
                                        $exp_layout_positions=explode(',',$row['layout_positions']);
                                        foreach ($exp_layout_positions as $v)
                                        {
                                        ?>
                                            <option value="<?=$v?>" <? if(in_array($v,$allow_position)) echo "selected";?>><?=$v?></option>
                                        <?
                                        }
                                        ?>
                                        </select>
                                   <?php
                                    }
                                    else
                                    {
                                    ?>
                                        <table width='100%' cellpadding='1' cellspacing='1' border='0'>
                                         <?php
                                           foreach ($special_arr as $kk=>$vv)
                                           {
                                         ?>
                                                <tr>
                                                    <td width='35%' valign='top' align='left'><?php echo $vv?></td>
                                                    <td valign='top' align='left' >
                                                        <select name="feature_position<?=$featu_cnt?>_<?php echo $kk?>[]" size="5" multiple>
                                                        <?
                                                        $exp_layout_positions=explode(',',$row['layout_positions']);
                                                        foreach ($exp_layout_positions as $v)
                                                        {
                                                            $checked = '';
                                                            if (count($saved_pos[$kk]))
                                                            {
                                                                if(in_array($v,$saved_pos[$kk])) 
                                                                    $checked = "selected";
                                                            }   
                                                        ?>
                                                            <option value="<?=$v?>" <?php echo $checked?> ><?=$v?></option>
                                                        <?
                                                        }
                                                        ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                        <?php
                                          }
                                        ?>
                                        </table>
                                    <?php
                                    }
                                  ?>
                                    <input type="hidden" name="feature_id<?=$featu_cnt?>" id="feature_id<?=$featu_cnt?>" value="<?=$row_feature['feature_id']?>" />
                                 
                                  </td>
                                </tr>
                        
                                <?      
                                }  
                                ?>
				</table>
				</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr>
				<td colspan="3" align="center">
					<input type="hidden" name="features_cnt" id="features_cnt" value="<?=$featu_cnt?>" />
					<input type="hidden" name="pass_theme_id" id="pass_theme_id" value="<?=$_REQUEST['pass_theme_id']?>" />
					<input type="hidden" name="pass_theme_name" id="pass_theme_name" value="<?=$_REQUEST['pass_theme_name']?>" />
                    <input type="hidden" name="pass_themetype" id="pass_themetype" value="<?=$_REQUEST['pass_themetype']?>" />

					<input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
					<input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
					<input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
					<input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
					<input type="hidden" name="pass_theme_name" id="pass_theme_name" value="<?=$_REQUEST['pass_theme_name']?>" />
					
					<input type="hidden" name="layout_id" id="layout_id" value="<?=$_REQUEST['layout_id']?>" />
					<input type="hidden" name="theme_name" id="theme_name" value="<?=$_REQUEST['theme_name']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="update_layouts" />
					<input type="Submit" name="Submit" id="Submit" value="Edit" class="input-button">				</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
    </table>
</form>