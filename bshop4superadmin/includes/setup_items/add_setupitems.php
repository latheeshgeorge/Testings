<?php
/*
#################################################################
# Script Name 	: add_setupitems.php
# Description 		: Page for adding setupwizard items
# Coded by 		: Sny
# Created on		: 11-Jul-2008
# Modified by		: 
# Modified On		: 
#################################################################
*/
#Define constants for this page
$page_type = 'Setup Wizard Items';

if($_REQUEST['group_id'])
{
	$sql_grp = "SELECT group_title  
							FROM 
								setup_groups 
							WHERE 
								group_id=".$_REQUEST['group_id']."
							LIMIT 
								1";
	$ret_grp = $db->query($sql_grp);
	if ($db->num_rows($ret_grp))
	{
		$row_grp = $db->fetch_array($ret_grp);
		$group_title = stripslashes($row_grp['group_title']);
	}
}
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
$help_msg = 'This section helps in adding setup wizard Item to the Group "<strong>'.$group_title.'</strong>"';
// Get the value of largest order for groups for current theme
$sql_group = "SELECT max(item_order) FROM setup_items WHERE setup_groups_group_id=".$_REQUEST['group_id']." AND themes_theme_id=".$_REQUEST['theme_id']." LIMIT 1";
$ret_group = $db->query($sql_group);
list($max_order) = $db->fetch_array($ret_group);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('group_title','layout');
	fieldDescription = Array('Group Title','Layout Code');
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
<form name='frmEditItem' action='home.php?request=setup_items' method="post" onsubmit="return valform(this);">

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
				  <td width="31%" align="right" class="fontblacknormal">Item title</td>
				  <td width="3%" align="center">:</td>
				  <td width="66%" align="left"><input name="item_title" type="text" id="item_title" value="<?php echo $_REQUEST['item_title']?>" size="40">
					  <span class="redtext">*</span></td>
				</tr>
				
				<tr>
				  <td align="right" class="fontblacknormal">Item Order</td>
				  <td align="center">:</td>
				  <td align="left"><input name="item_order" type="text" id="item_order" value="<?php echo ($max_order+1)?>" size="5">
					  <span class="redtext">*</span></td>
				</tr>
			
				<?PHP 
				$tagsql = "SELECT tag_id, tag_text FROM setup_tags ORDER BY tag_order";
				$tagres = $db->query($tagsql);
				while($tagrow = $db->fetch_array($tagres)) 
				{  
				$color = "color_".$tagrow['tag_id'];
				
				?>
				<tr>
				  <td align="right" class="fontblacknormal"><?PHP echo $tagrow['tag_text']; ?> </td>
				  <td align="center">:</td>
				  <td align="left"><input name="color_<?PHP echo $tagrow['tag_id']; ?>" type="text" id="color_<?PHP echo $tagrow['tag_id']; ?>" value="<?php echo $$color?>" size="30" /></td>
			  </tr>
			  	<? } ?>
				<!--<tr>
				  <td align="right" class="fontblacknormal">Item Bgcolor </td>
				  <td align="center">:</td>
				  <td align="left"><input name="bgcolor" type="text" id="bgcolor" value="<?php echo $_REQUEST['item_bgcolor']?>" size="30" /></td>
			  </tr> -->
				<tr>
				  <td align="right" class="fontblacknormal">Item Layoutcode </td>
				  <td align="center">:</td>
				  <td align="left"><input name="layout" type="text" id="layout" value="<?php echo $_REQUEST['layout_code']?>" size="80" />
			      <span class="redtext">*</span></td>
			  </tr>
				<tr>
				  <td align="right" class="fontblacknormal" valign="top">Item Template  </td>
				  <td align="center" valign="top">:</td>
				  <td align="left"><textarea name="template" rows="30" cols="90"></textarea></td>
			  </tr>
								
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					
					<input type="hidden" name="src_title" id="src_title" value="<?=$_REQUEST['src_title']?>" />
					<input type="hidden" name="theme_id" id="theme_id" value="<?=$_REQUEST['theme_id']?>" />
					<input type="hidden" name="group_id" id="group_id" value="<?=$_REQUEST['group_id']?>" />
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