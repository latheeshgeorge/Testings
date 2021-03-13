<?php
/*#################################################################
# Script Name 	: add_console_levels.php
# Description 	: Page for adding console levels
# Coded by 		: Sny
# Created on	: 01-Jun-2007
# Modified by	: 
# Modified On	: 
#################################################################

#Define constants for this page
*/
$page_type = 'Console Levels';
$help_msg = 'This section helps in adding new Console Levels.';

// Get the list of all features from features table
$sql_feat = "SELECT feature_id,feature_name,feature_insite,feature_inconsole,feature_licenselimit 
				FROM features ORDER BY feature_name";
$ret_feat = $db->query($sql_feat);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('level_name');
	fieldDescription = Array('Level Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('level_price','level_duration');
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
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<a href="home.php?request=levels&levelname=<?=$_REQUEST['levelname']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&pg=<?=$_REQUEST['pg']?>" title="List Themes">List Console Levels </a><strong> <font size="1">>></font> Add <?=$page_type?></strong></td>
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
				  <td align="left"><input name="level_name" type="text" id="level_name" value="<?=$_REQUEST['level_name']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Price</td>
				  <td align="center">:</td>
				  <td align="left"><input name="level_price" type="text" id="level_price" value="<?=$_REQUEST['level_price']?>" size="30"></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Duration</td>
				  <td align="center">:</td>
				  <td align="left"><input name="level_duration" type="text" id="level_duration" value="<?=$_REQUEST['level_duration']?>" size="30" /> 
				  Months </td>
				</tr>
				<tr>
				  <td align="right" valign="top" class="fontblacknormal">Description</td>
				  <td align="center" valign="top">:</td>
				  <td align="left" valign="top"><textarea name="level_description" cols="50" rows="6"><?php echo $_REQUEST['level_description']?></textarea></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">&nbsp;</td>
				  <td align="center">&nbsp;</td>
				  <td align="left">&nbsp;</td>
			  </tr>
				<?php
					if($db->num_rows($ret_feat))
					{
						$table_headers = array('<img src="images/checkall.gif" border="0" alt="Edit" onclick="select_all(\'checkbox[]\',document.frmEditConsolelevel,true)" title=\'Select All\' /> <img src="images/uncheckall.gif" border="0" alt="Edit" onclick="select_all(\'checkbox[]\',document.frmEditConsolelevel,false)" title=\'Deselect All\' />','Slno.','Feature Name ','Feature available in site?','Feature available in console?');
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
							if($rowcnt %2 == 0)
								$class_val="maininnertabletd1";
							else
								$class_val="maininnertabletd2";	
					?>
							<tr class="<?php echo $class_val?>">
							  <td align="center"><input type="checkbox" name="checkbox[]" id="checkbox[]" value="<?php echo $row_feat['feature_id']?>" /></td>
							  <td align="left"><?php echo $rowcnt++?>.</td>
							  <td align="left"><?php echo stripslashes($row_feat['feature_name'])?></td>
							  <td align="center"><?php echo ($row_feat['feature_insite']==1)?'Y':'N'?></td>
							  <td align="center"><?php echo ($row_feat['feature_inconsole']==1)?'Y':'N'?></td>
							  <?php /*?><td align="center">
							  <?php
							  	if($row_feat['feature_licenselimit'])
								{
							  ?>
							  		<input type="text" name="limit_<?php echo $row_feat['feature_id']?>" value="<?php echo $row_feat['feature_licenselimit']?>" size="8" />
							  <?php
							  	}
							  ?>
							  </td><?php */?>
							</tr>
					<?php
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
					<input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
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