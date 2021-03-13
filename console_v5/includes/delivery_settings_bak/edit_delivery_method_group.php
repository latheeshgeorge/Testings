<?php
	/*#################################################################
	# Script Name 	: edit_delivery_method_group.php
	# Description 	: Page for editing Delivery method group
	# Coded by 		: Sny
	# Created on	: 12-Nov-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Delivery Method Groups';
$help_msg 			= get_help_messages('EDIT_DELIVERY_METHOD_GROUP_MESS1');
if(is_array($_REQUEST['delgroupid'])){
	list($_REQUEST['delgroup_id'])=$_REQUEST['delgroupid'];
}

$sql_group = "SELECT * FROM general_settings_site_delivery_group 
				WHERE sites_site_id = $ecom_siteid  AND delivery_group_id=".$_REQUEST['delgroup_id'];
$ret_group = $db->query($sql_group);
/*if($db->num_rows($ret_group)==0) { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }*/

if ($db->num_rows($ret_group))
{
	$row_group = $db->fetch_array($ret_group);
}
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('delivery_group_name');
	fieldDescription = Array('Group Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmAddSettingsCaptions' action='home.php?request=delivery_settings' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"> <a href="home.php?request=delivery_settings">Delivery Settings</a> &gt;&gt; <a href="home.php?request=delivery_settings&fpurpose=list_delmethod_groups&group_name=<?php echo $_REQUEST['group_name']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>">List Delivery Settings Groups </a> &gt;&gt;Edit <?php echo $page_type?></td>
        </tr>
        <tr>
          <td colspan="2" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
        </tr><?php if($alert) {?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr><?php }?>
        <tr>
          <td width="21%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="79%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Group Name  <span class="redtext">*</span> </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="delivery_group_name"  type="text" id="delivery_group_name" value="<?=$row_group['delivery_group_name']?>" size="50" /></td>
        </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >Order</td>
          <td align="left" valign="middle" class="tdcolorgray" ><input type="text" name="delivery_group_order" value="<?=$row_group['delivery_group_order']?>" size="3" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_DELIVERY_METHOD_GROUP_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	    </tr>
         <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Hide Group </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="delivery_group_hidden" type="radio" value="0" <?php echo ($row_group['delivery_group_hidden']==0)?'checked="checked"':''?> />
            No
              <input name="delivery_group_hidden" type="radio" value="1" <?php echo ($row_group['delivery_group_hidden']==1)?'checked="checked"':''?>/> 
              Yes <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_DELIVERY_METHOD_GROUP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		 
		 <tr>
		   <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
    </tr>
		 <tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" ><p>&nbsp;</p>           </td>
        </tr>
					
        <tr>
          	<td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
			<td align="left" valign="middle" class="tdcolorgray">
				<input type="hidden" name="group_name" id="group_name" value="<?=$_REQUEST['group_name']?>" />
				<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
				<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
				<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
				<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
				<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
				<input type="hidden" name="delgroup_id" id="delgroup_id" value="<?=$_REQUEST['delgroup_id']?>" />
				<input type="hidden" name="fpurpose" id="fpurpose" value="update_methodgroups" />
				<input name="update_methodgroups" type="submit" class="red" value="Update" />			</td>
        </tr>
        <tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
      </table>
</form>	  

