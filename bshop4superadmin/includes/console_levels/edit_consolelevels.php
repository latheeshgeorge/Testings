<?php
/*#################################################################
# Script Name 	: edit_console_levels.php
# Description 	: Page for editing console levels
# Coded by 		: Sny
# Created on	: 01-Jun-2007
# Modified by	: 
# Modified On	: 
#################################################################

#Define constants for this page
*/
$page_type = 'Console Levels';
$help_msg = 'This section helps in adding new Console Levels.';

$sql_feat = "SELECT feature_id,feature_name,feature_insite,feature_inconsole,feature_licenselimit FROM features ORDER BY feature_name";
$ret_feat = $db->query($sql_feat);

// Get the details of current console level
$sql_level = "SELECT level_name,level_description,level_price,level_duration FROM console_levels WHERE level_id=".$_REQUEST['level_id'];
$res_level = $db->query($sql_level);
$row 		= $db->fetch_array($res_level);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('level_name');
	fieldDescription = Array('Level Name');
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
<form name='frmEditConsolelevel' action='home.php?request=levels' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=levels&levelname=<?=$_REQUEST['levelname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>" title="List Themes">List Console Levels </a><strong> <font size="1">>></font> Edit <?=$page_type?></strong></td>
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
				  <td align="right" class="fontblacknormal">Level  name</td>
				  <td align="center">:</td>
				  <td align="left"><input name="level_name" type="text" id="level_name" value="<?=stripslashes($row['level_name'])?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Price</td>
				  <td align="center">:</td>
				  <td align="left"><input name="level_price" type="text" id="level_price" value="<?=stripslashes($row['level_price'])?>" size="30"></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Duration</td>
				  <td align="center">:</td>
				  <td align="left"><input name="level_duration" type="text" id="level_duration" value="<?=stripslashes($row['level_duration'])?>" size="30" />			        Months </td>
				</tr>
				<tr>
				  <td align="right" valign="top" class="fontblacknormal">Description</td>
				  <td align="center" valign="top">:</td>
				  <td align="left" valign="top"><textarea name="level_description" cols="50" rows="6"><?=stripslashes($row['level_description'])?></textarea></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">&nbsp;</td>
				  <td align="center">&nbsp;</td>
				  <td align="left">&nbsp;</td>
			  </tr>
				<?php
					$assigned_arr 	= array(0);
					$limit_arr 		= array(0);
					// Find the list of all features assigned to this console level
					$sql_assigned = "SELECT features_feature_id,services_limit FROM console_levels_details WHERE console_levels_level_id=".$_REQUEST['level_id'];
					$ret_assigned = $db->query($sql_assigned);
					if ($db->num_rows($ret_assigned))
					{
						while ($row_assigned = $db->fetch_array($ret_assigned))
						{
							$cur_id 				= $row_assigned['features_feature_id'];
							$assigned_arr[]			= $cur_id ;
							$limit_arr[$cur_id]	= $row_assigned['services_limit'];
						}
					}
					if($db->num_rows($ret_feat))
					{
						$table_headers = array('<img src="images/checkall.gif" border="0" alt="Edit" onclick="select_all(\'checkbox[]\',document.frmEditConsolelevel,true)" /> <img src="images/uncheckall.gif" border="0" alt="Edit" onclick="select_all(\'checkbox[]\',document.frmEditConsolelevel,false)" />','Slno.','Feature Name ','Feature available in site?','Feature available in console?');
						$header_positions=array('center','left','left','center','center');
						
						$colspan = count($table_headers);
				?>
				<tr>
				  <td colspan="3" align="left"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                    <tr>
                      <td colspan="<?php echo $colspan?>" align="left"><strong>Select the features to be included in current Console Level</strong></td>
                    </tr>
					<?php
						echo table_header($table_headers,$header_positions);
						$rowcnt = 1;
						while($row_feat = $db->fetch_array($ret_feat))
						{
							$c_featid = $row_feat['feature_id'];
							if($rowcnt %2 == 0)
								$class_val="maininnertabletd1";
							else
								$class_val="maininnertabletd2";	
							if(in_array($row_feat['feature_id'],$assigned_arr))
								$checked = 'checked="checked"';
							else
								$checked = '';	
					?>
							<tr class="<?php echo $class_val?>">
							  <td align="center"><input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_feat['feature_id']?>" <?php echo $checked?> /></td>
							  <td align="left"><?php echo $rowcnt++?>.</td>
							  <td align="left"><?php echo stripslashes($row_feat['feature_name'])?></td>
							  <td align="center"><?php echo ($row_feat['feature_insite']==1)?'Y':'N'?></td>
							  <td align="center"><?php echo ($row_feat['feature_inconsole'])?'Y':'N'?></td>
							  <?php /*?><td align="center">
							  <?php
							  	if($row_feat['feature_licenselimit'])
								{
									if(in_array($row_feat['feature_id'],$assigned_arr))
										$limit = $limit_arr[$c_featid];
									else
										$limit = $row_feat['feature_licenselimit'];
							  ?>
							  		<input type="text" name="limit_<?php echo $row_feat['feature_id']?>" value="<?php echo $limit?>" size="8" />
							  <?php
							  	}
							  ?>
							  </td><?php */?>
							</tr>
					<?php
							$arr++;
						}
					?>
                  </table></td>
				</tr>
			<?php
				}
			?>	
				<tr align="center">
				<td colspan="3" align="center">
					<input type="hidden" name="levelname" id="levelname" value="<?=$_REQUEST['levelname']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="level_id" id="level_id" value="<?=$_REQUEST['level_id']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="update" />
					<input type="Submit" name="Save_consolelevels" id="Save_consolelevels" value="Save" class="input-button">				</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
  </table>
</form>