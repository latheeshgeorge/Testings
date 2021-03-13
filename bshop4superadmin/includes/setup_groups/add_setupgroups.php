<?php
/*
#################################################################
# Script Name 	: add_add_setupgroups.php
# Description 		: Page for adding setupwizard groups
# Coded by 		: Sny
# Created on		: 10-Jul-2008
# Modified by		: 
# Modified On		: 
#################################################################
*/
#Define constants for this page
$page_type = 'Setup Wizard Group';

if($_REQUEST['theme_id'])
{
	$sql_theme = "SELECT themename  
							FROM 
								themes 
							WHERE 
								theme_id=".$_REQUEST['theme_id']." 
							LIMIT 
								1";
	$ret_theme = $db->query($sql_theme);
	if ($db->num_rows($ret_theme))
	{
		$row_theme = $db->fetch_array($ret_theme);
		$themename = stripslashes($row_theme['themename']);
	}
}
$help_msg = 'This section helps in adding setup wizard groups to the theme "<strong>'.$themename.'</strong>"';
// Get the value of largest order for groups for current theme
$sql_group = "SELECT max(group_order) FROM setup_groups WHERE themes_theme_id=".$_REQUEST['theme_id']." LIMIT 1";
$ret_group = $db->query($sql_group);
list($max_order) = $db->fetch_array($ret_group);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('group_title');
	fieldDescription = Array('Group Title');
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
<form name='frmEditTheme' action='home.php?request=setup_groups' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd"><a href="home.php?request=setup_groups&src_title=<?php echo $_REQUEST['src_title']?>&theme_id=<?php echo $_REQUEST['theme_id']?>&sort_by=<?php echo $_REQUEST['sort_by']?>&sort_order=<?php echo $_REQUEST['sort_order']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&pg=<?php echo $_REQUEST['pg']?>"><b>List Groups</b></a>&nbsp;<font size="1">>></font> <strong>Add <?=$page_type?></strong></td>
      </tr>
      <tr>
        <td class="maininnertabletd2">
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
				  <td width="31%" align="right" class="fontblacknormal">Group title</td>
				  <td width="3%" align="center">:</td>
				  <td width="66%" align="left"><input name="group_title" type="text" id="cardtype_key" value="<?php echo $_REQUEST['group_title']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Group Order</td>
				  <td align="center">:</td>
				  <td align="left"><input name="group_order" type="text" id="group_order" value="<?php echo ($max_order+1)?>" size="5">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Show in color picking section? </td>
				  <td align="center">:</td>
				  <td align="left"> 
				    <input name="group_hidden" type="radio" value="0"  checked="checked"/>Yes
			        <input name="group_hidden" type="radio" value="1" />No</td>
			  </tr>
								
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					
					<input type="hidden" name="src_title" id="src_title" value="<?=$_REQUEST['src_title']?>" />
					<input type="hidden" name="theme_id" id="theme_id" value="<?=$_REQUEST['theme_id']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
					<input type="Submit" name="Submit" id="Submit" value="Add" class="input-button">				</td>
				</tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
    </table>
</form>