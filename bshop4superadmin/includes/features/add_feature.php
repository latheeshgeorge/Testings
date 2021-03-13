<?php
#################################################################
# Script Name 	: add_features.php
# Description 		: Page for adding features
# Coded by 		: SG
# Created on		: 13-June-2007
# Modified by		: SG
# Modified On		: 26-Jul-2007
#################################################################

#Define constants for this page
$page_type = 'Feature';
$help_msg = 'This section helps in adding the values for a feature.';

?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('feature_name','feature_title','feature_option');
	fieldDescription = Array('Feature Name','Feature Title','Feature Option value');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('feature_duration','feature_price');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmEditTheme' action='home.php?request=features' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd">&nbsp;&nbsp;<strong>Add <?=$page_type?></strong></td>
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
				  <td width="45%" align="right" class="fontblacknormal">Feature Name</td>
				  <td width="1%" align="center">:</td>
				  <td width="54%" align="left"><input name="feature_name" type="text" id="feature_name" value="<?=stripslashes($_REQUEST['feature_name'])?>" size="30">
				  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Feature Title</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_title" type="text" id="feature_title" value="<?=stripslashes($_REQUEST['feature_title'])?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Feature description</td>
				  <td align="center">:</td>
				  <td align="left"><textarea name="feature_description" id="feature_description" rows="5" cols="50"><?=stripslashes($_REQUEST['feature_description'])?></textarea></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Feature Request value(Value of &quot;request&quot; in console url)</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_option" type="text" id="feature_option" value="<?=$_REQUEST['feature_option']?>" size="30">
					  <span class="redtext">*</span></td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Service Name</td>
				  <td align="center">:</td>
				  <td align="left">
				  <?php
				  $service_arr = array();
				  $sql_service = "SELECT service_id,service_name FROM services";
				  $res_service = $db->query($sql_service);
				  while($row_service = $db->fetch_array($res_service)) {
				  	$service_arr[$row_service['service_id']] = $row_service['service_name'];
				  }
				  echo generateselectbox('services_service_id',$service_arr,$_REQUEST['services_service_id']);
				  ?>				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Parent Feature</td>
				  <td align="center">:</td>
				  <td align="left">
				  <?php
				  $feature_arr = array(0 => 'Root Level');
				  $sql_feature = "SELECT feature_id,feature_name FROM features ORDER BY feature_name";
				  $res_feature = $db->query($sql_feature);
				  while($row_feature = $db->fetch_array($res_feature)) {
				  	$feature_arr[$row_feature['feature_id']] = $row_feature['feature_name'];
				  }
				  echo generateselectbox('parent_id',$feature_arr,$_REQUEST['parent_id']);
				  ?>				  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Full  Console URL for this feature</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_consoleurl" type="text" id="feature_consoleurl" value="<?=$_REQUEST['feature_consoleurl']?>" size="30">					  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Module Name</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_modulename" type="text" id="feature_modulename" value="<?=$_REQUEST['feature_modulename']?>" size="30">					  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Hide Feature</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_hide" type="radio" id="feature_hide" value="0" checked="checked">N <input name="feature_hide" type="radio" id="feature_hide" value="1">Y					  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Edit Caption is allowed in website layout for this feature?</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_allowedit" type="radio" id="feature_allowedit" value="0" checked="checked">N <input name="feature_allowedit" type="radio" id="feature_allowedit" value="1">Y					  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Feature available in site?</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_insite" type="radio" id="feature_insite" value="0" checked="checked">N <input name="feature_insite" type="radio" id="feature_insite" value="1">Y					  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Feature available in console?</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_inconsole" type="radio" id="feature_inconsole" value="0" checked="checked">N <input name="feature_inconsole" type="radio" id="feature_inconsole" value="1">Y					  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Sort order for feature when displayed in console menu</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_ordering" type="text" id="feature_ordering" value="<?=$_REQUEST['feature_ordering']?>" size="30">					  </td>
				</tr>
				<?php /*?><tr>
				  <td align="right" class="fontblacknormal">Feature Price</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_price" type="text" id="feature_price" value="<?=$_REQUEST['feature_price']?>" size="15">					  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Feature Duration</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_duration" type="text" id="feature_duration" value="<?=$_REQUEST['feature_duration']?>" size="15">					 </td>
				</tr>	
				<tr>
				  <td align="right" class="fontblacknormal">Feature License Limit</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_licenselimit" type="text" id="feature_licenselimit" value="<?=$_REQUEST['feature_licenselimit']?>" size="15">					  </td>
				</tr>              <?php */?>           
				<tr>
				  <td align="right" class="fontblacknormal">Display Feature in Console Menu?</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_displaytouser" type="radio" id="feature_displaytouser" value="0" checked="checked">N <input name="feature_displaytouser" type="radio" id="feature_displaytouser" value="1">Y					  </td>
				</tr>
				<tr>
				  <td align="right" class="fontblacknormal">Show Feature in Website Layout Section for drag and drop?</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_showinmobilecomponentposition" type="radio" id="feature_showinmobilecomponentposition" value="0" checked="checked" />
				    N
				    <input name="feature_showinmobilecomponentposition" type="radio" id="feature_showinmobilecomponentposition" value="1" />
				    Y </td>
			  </tr>
			  <tr>
				  <td align="right" class="fontblacknormal">Show Feature in Mobile Layout Section for drag and drop?</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_showincomponentposition" type="radio" id="feature_showincomponentposition" value="0" checked="checked" />
				    N
				    <input name="feature_showincomponentposition" type="radio" id="feature_showincomponentposition" value="1" />
				    Y </td>
			  </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Allow Feature to be placed in middle position in website layout Section</td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_allowedinmiddlesection" type="radio" id="feature_allowedinmiddlesection" value="0" checked="checked" />
				    N
				    <input name="feature_allowedinmiddlesection" type="radio" id="feature_allowedinmiddlesection" value="1" />
				    Y </td>
			  </tr>
                           <tr>
                                  <td align="right" class="fontblacknormal">Show feature in "Allowed positions settings section" of layout edit page</td>
                                  <td align="center">:</td>
                                  <td align="left"><input name="feature_displayinallowedposition" type="radio" id="feature_displayinallowedposition" value="0" checked="checked" />
                                    N
                                    <input name="feature_displayinallowedposition" type="radio" id="feature_displayinallowedposition" value="1" />
                                    Y </td>
                          </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Console Menu Icon </td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_icon" type="text" id="feature_icon" size="30" /></td>
			  </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Console Menu Disable Icon </td>
				  <td align="center">:</td>
				  <td align="left"><input name="feature_disable_icon" type="text" id="feature_disable_icon" size="30" /></td>
			  </tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td>&nbsp;</td>
				<td colspan="3" align="left">
					<input type="hidden" name="featuretitle" id="featuretitle" value="<?=$_REQUEST['featuretitle']?>" />
					<input type="hidden" name="servicetitle" id="servicetitle" value="<?=$_REQUEST['servicetitle']?>" />
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
