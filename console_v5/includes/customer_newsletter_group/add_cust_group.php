<?php
	/*#################################################################
	# Script Name 	: add_cust_group.php
	# Description 	: Page for adding Customer Group
	# Coded by 		: SKR
	# Created on	: 03-Aug-2007
	# Modified by	: ANU
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Customer Newsletter Group';
$help_msg =get_help_messages('ADD_CUST_GROUP_MESS1');

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('custgroup_name');
	fieldDescription = Array('Group Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('custgroup_discount');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(frm.custgroup_discount.value>99) {
			alert(" Discount Should be less than 100% ");
			return false;
		} else {
			show_processing();
			return true;
		}
	} else {
		return false;
	}
}
</script>
<form name='frmAddCustomerGroup' action='home.php?request=cust_group' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=cust_group&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Customer Newsletter  Groups</a><span> Add <?=$page_type?></span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		 <tr>
          <td colspan="2" align="center" valign="middle" class="tdcolorgray" >
			<div class="editarea_div">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			  <td width="15%" align="left" valign="middle" class="tdcolorgray" >Group Name <span class="redtext">*</span> </td>
			  <td width="85%" align="left" valign="middle" class="tdcolorgray">
			  <input class="input" type="text" name="custgroup_name"  value="<?=$_REQUEST['custgroup_name']?>" />
			  </td>
			</tr>
			<!-- <tr>
			  <td width="15%" align="left" valign="middle" class="tdcolorgray" >Discount (%) </td>
			  <td width="85%" align="left" valign="middle" class="tdcolorgray">
			  <input class="input" type="text" name="custgroup_discount" size="3"  value="<?=$_REQUEST['custgroup_discount']?>" />
			  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CUST_GROUP_DISC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr> -->
			<tr>
			  <td align="left" valign="middle" class="tdcolorgray" >Active</td>
			  <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="custgroup_active" value="1" <? if($_REQUEST['custgroup_active']==1 || $_REQUEST['custgroup_active']=='') echo "checked";?>  />Yes<input type="radio" name="custgroup_active" value="0"  <? if($_REQUEST['custgroup_active']==0 && $_REQUEST['custgroup_active']!='') echo "checked";?> />No&nbsp;
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CUST_GROUP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
			</table>
			</div>
			</td>
			</tr>
			<tr>
				<td align="right" valign="middle" class="tdcolorgray" colspan="2">
					<div class="editarea_div">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="100%" align="right" valign="middle" class="tdcolorgray" >						
								<input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
								<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
								<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
								<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
								<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
								<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
								<input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
								<input name="Submit" type="submit" class="red" value="Save" />
							</td>
						</tr>
						</table>
					</div>
				</td>
			</tr>
      </table>
</form>	  

