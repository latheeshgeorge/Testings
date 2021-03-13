<?php
	/*#################################################################
	# Script Name 	: add_cust_disc_group.php
	# Description 	: Page for adding Customer Discount Group
	# Coded by 		: Anu
	# Created on	: 29-Feb-2008
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Customer Discount Group';
$help_msg =get_help_messages('ADD_CUST_DISC_GROUP_MESS1');

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('cust_disc_grp_name');
	fieldDescription = Array('Discount Group Name');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('cust_disc_grp_discount');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
	    if(frm.cust_disc_grp_discount.value>99) {
			alert("Discount allowed should be below 100% ");
			return false;
		}
		else if(frm.cust_disc_grp_discount.value<0) {
		alert("Discount allowed should be a Positive value. ");
		frm.cust_disc_grp_discount.focus();
		return false;
		} 
		else { 
		show_processing();
		return true;
		}
	} else {
		return false;
	}
}
</script>
<form name='frmAddCustomerGroup' action='home.php?request=cust_discount_group' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=cust_discount_group&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Customer Discount Groups</a><span> Add Group</span></div></td>
        </tr>
        <tr>
		  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>		 </td>
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
          <td align="left" valign="middle" class="tdcolorgray" colspan="2" >
		  <div class="editarea_div">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td width="26%" align="left" valign="middle" class="tdcolorgray" >Customer Discount Group Name <span class="redtext">*</span> </td>
				  <td width="74%" align="left" valign="middle" class="tdcolorgray">
				  <input class="input" type="text" name="cust_disc_grp_name"  value="<?=$_REQUEST['cust_disc_grp_name']?>"  maxlength="100"/>		  </td>
				</tr>
				 <tr>
				  <td width="26%" align="left" valign="middle" class="tdcolorgray" >Discount Allowed (%)</td>
				  <td width="74%" align="left" valign="middle" class="tdcolorgray">
				  <input class="input" type="text" name="cust_disc_grp_discount" size="3"  value="<?=$_REQUEST['cust_disc_grp_discount']?>" />
				  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CUST_DISC_GROUP_DISC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr>
				 <tr>
				   <td align="left" valign="middle" class="tdcolorgray" >Display Categories Mapped with group in Myhome Page </td>
				   <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="cust_disc_display_category_in_myhome" id="cust_disc_display_category_in_myhome" value="1" /></td>
				</tr>
				 <tr>
				   <td align="left" valign="middle" class="tdcolorgray" >Apply Customer Direct Discount also?</td>
				   <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="cust_apply_direct_discount_also" id="cust_apply_direct_discount_also" value="1" />
				   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_DIRECT_DISC_CUSTGRP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				   </td>
				</tr>
				<tr>
				   <td align="left" valign="middle" class="tdcolorgray" >Apply Direct Product Discount also?</td>
				   <td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="cust_apply_direct_product_discount_also" id="cust_apply_direct_product_discount_also" value="1" />
				   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_PROD_DIRECT_DISC_CUSTGRP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				   </td>
				</tr>
			   <?php /*?> <tr>
				  <td align="left" valign="middle" class="tdcolorgray" >Active</td>
				  <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="cust_disc_grp_active" value="1" <? if($_REQUEST['cust_disc_grp_active']==1 || $_REQUEST['cust_disc_grp_active']=='') echo "checked";?>  />Yes<input type="radio" name="cust_disc_grp_active" value="0"  <? if($_REQUEST['cust_disc_grp_active']==0 && $_REQUEST['cust_disc_grp_active']!='') echo "checked";?> />No&nbsp;
				  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CUST_DISC_GROUP_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				</tr><?php */?>
				</table>
			</div>
		</td>
		</tr>
		<tr>
		  <td valign="middle" class="tdcolorgray" colspan="2">
		  <div class="editarea_div">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr class="editcontent">
				<td class="tdcolorgray" align="right">		  
				  <input type="hidden" name="pass_group_name" id="pass_group_name" value="<?=$_REQUEST['pass_group_name']?>" />
				   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
				  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
				  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
				  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
				  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
				  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
				  <input name="Submit" type="submit" class="red" value="Submit" />
				  <input name="Submit" type="submit" class="red" value="Save & Return to Edit" />
				</td>
			</tr>
			</table>
			</div>
			</td>
		</tr>
	  </table>
</form>	  

