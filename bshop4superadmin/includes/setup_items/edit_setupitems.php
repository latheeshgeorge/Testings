<?php
/*
#################################################################
# Script Name 	: edit_setupgroups.php
# Description 		: Page for editing setup groups 
# Coded by 		: SNY
# Created on		: 10-Juj-2008
# Modified by		: 
# Modified On		: 
#################################################################
*/
#Define constants for this page
$page_type 	= 'Setup Wizard Item';
$help_msg		= 'This section helps in editing the values for a setup Item.';

$sql 	= "SELECT * 
				FROM 
					setup_items 
				WHERE 
					item_id=".$_REQUEST['item_id']." 
				LIMIT 
					1";
$res 	= $db->query($sql);
$row = $db->fetch_array($res);
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
<form name='frmEditTheme' action='home.php?request=setup_items' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<strong>Edit <?=$page_type?></strong></td>
      </tr>
      <tr>
        <td class="maininnertabletd2">
			<?=$help_msg?>
		</td>
      </tr>
	  <tr>
        <td class="maininnertabletd2"><table width="100%"  border="0" cellpadding="4" cellspacing="1" class="">
          <tr align="left">
            <td colspan="3" class="redtext"><div align="left">* <span>are required </span></div></td>
          </tr>
          <tr>
            <td width="31%" align="right" class="fontblacknormal">Item title</td>
            <td width="3%" align="center">:</td>
            <td width="66%" align="left"><input name="item_title" type="text" id="item_title" value="<?php echo $row['item_title']?>" size="40" />
                <span class="redtext">*</span></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">Item Order</td>
            <td align="center">:</td>
            <td align="left"><input name="item_order" type="text" id="item_order" value="<?php echo $row['item_order']?>" size="5" />
                <span class="redtext">*</span></td>
          </tr>
		  <?PHP 
				$tsql = "SELECT setup_tags.tag_id, setup_tags.tag_text
									FROM setup_tags 
									   ORDER BY setup_tags.tag_order 	";
					$tres = $db->query($tsql);
					while($trow = $db->fetch_array($tres)) {
						$tagid = $trow['tag_id'];				   
				
				$tagsql = "SELECT setup_tags.tag_id, setup_tags.tag_text, setup_items_tags_values.item_value
								  FROM setup_tags, setup_items_tags_values 
								  	   WHERE setup_items_tags_values.setup_items_item_id='".$_REQUEST['item_id']."' 
									   		 AND setup_items_tags_values.setup_tags_tag_id=$tagid	
								  				ORDER BY setup_tags.tag_order";
				$tagres = $db->query($tagsql);
				$tagrow = $db->fetch_array($tagres);
				 
				$color = "color_".$trow['tag_id'];
				
				?>
				<tr>
				  <td align="right" class="fontblacknormal"><?PHP echo $trow['tag_text']; ?> </td>
				  <td align="center">:</td>
				  <td align="left"><input name="color_<?PHP echo $trow['tag_id']; ?>" type="text" id="color_<?PHP echo $trow['tag_id']; ?>" value="<?php echo $tagrow['item_value'];?>" size="30" /></td>
			  </tr>
			  	<? } ?>
          <!--<tr>
            <td align="right" class="fontblacknormal">Item Forecolor </td>
            <td align="center">:</td>
            <td align="left"><input name="forecolor" type="text" id="forecolor" value="<?php echo $row['item_forecolor']?>" size="30" /></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal">Item Bgcolor </td>
            <td align="center">:</td>
            <td align="left"><input name="bgcolor" type="text" id="bgcolor" value="<?php echo $row['item_bgcolor']?>" size="30" /></td>
          </tr> -->
          <tr>
            <td align="right" class="fontblacknormal">Item Layoutcode </td>
            <td align="center">:</td>
            <td align="left"><input name="layout" type="text" id="layout" value="<?php echo $row['layout_code']?>" size="80" />
                <span class="redtext">*</span></td>
          </tr>
          <tr>
            <td align="right" class="fontblacknormal" valign="top">Item Template </td>
            <td align="center" valign="top">:</td>
            <td align="left"><textarea name="template" rows="30" cols="90"><?php echo $row['item_template']?></textarea></td>
          </tr>
          <tr>
            <td colspan="3" align="right">&nbsp;</td>
          </tr>
          <tr align="center">
            <td>&nbsp;</td>
            <td colspan="3" align="left">
				
				<input type="hidden" name="item_id" id="item_id" value="<?=$_REQUEST['item_id']?>" />
				<input type="hidden" name="group_id" id="group_id" value="<?=$_REQUEST['group_id']?>" />
					<input type="hidden" name="src_title" id="src_title" value="<?=$_REQUEST['src_title']?>" />
					<input type="hidden" name="theme_id" id="theme_id" value="<?=$_REQUEST['theme_id']?>" />
					<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
					<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
					<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
					<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					<input type="hidden" name="fpurpose" id="fpurpose" value="update" />
					<input type="Submit" name="Submit" id="Submit" value="Edit" class="input-button">
            </td>
          </tr>
          <tr>
            <td colspan="3" align="right">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table>
</form>