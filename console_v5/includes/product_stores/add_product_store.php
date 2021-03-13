<?php
	/*#################################################################
	# Script Name 	: add_product_store.php
	# Description 	: Page for adding Product Store 
	# Coded by 		: LSH
	# Created on	: 26-March-2008
	# Modified by	: LSH
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type 	= 'Branches';
$help_msg 	= get_help_messages('ADD_PRODUCT_STORE_SHORT');

?>	
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('shop_title','shop_address','shop_mobile','shop_contactperson','shop_conatactperson_designation','shop_phone','shop_email');
	fieldDescription = Array('Warehouse Name','Address','MobileNo','Contact Person','Designation','Phone','Email');
fieldEmail = Array('shop_email');
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	fieldSpecChars = Array('shop_title','shop_phone','shop_contactperson','shop_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars))
	{
			/* Check whether dispay location is selected*/
			show_processing();
			return true;
	}
	else
	{
		return false;
	}
}
</script>
<form name='frmAddProductStore' action='home.php?request=product_stores' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=product_stores&storename=<?php echo $_REQUEST['storename']?>&sort_by=<?php echo $_REQUEST['sort_by']?>&sort_order=<?php echo $_REQUEST['sort_order']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>">List Branches </a><span> Add Branch</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
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
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
    </tr>
		 <?php
		 	}
		 ?> 
		 <tr>
          <td colspan="4" align="center" valign="middle">
		  <div class="editarea_div">
		  <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Warehouse Name <span class="redtext">*</span> </td>
          <td width="29%" align="left" valign="middle" class="tdcolorgray"><input name="shop_title" type="text" class="input" size="25" value="<?php echo $_REQUEST['shop_title']?>"  maxlength="100"/></td>
           <td width="20%" align="left" valign="middle" class="tdcolorgray" >Contact Person Designation <span class="redtext">*</span> </td>
          <td width="29%" align="left" valign="middle" class="tdcolorgray"><input name="shop_conatactperson_designation" type="text" class="input" size="25" value="<?php echo $_REQUEST['shop_conatactperson_designation']?>"  maxlength="100"/></td>
          </tr>
		 <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Address <span class="redtext">*</span> </td>
          <td width="29%" align="left" valign="middle" class="tdcolorgray"><input name="shop_address" type="text" class="input" size="25" value="<?php echo $_REQUEST['shop_address']?>"  maxlength="100"/></td>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Phone <span class="redtext">*</span> </td>
          <td width="29%" align="left" valign="middle" class="tdcolorgray"><input name="shop_phone" type="text" class="input" size="25" value="<?php echo $_REQUEST['shop_phone']?>"  maxlength="100"/></td>
          
		  </tr>
		  
		   <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Mobile <span class="redtext">*</span> </td>
          <td width="29%" align="left" valign="middle" class="tdcolorgray"><input name="shop_mobile" type="text" class="input" size="25" value="<?php echo $_REQUEST['shop_mobile']?>"  maxlength="100"/></td>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Email <span class="redtext">*</span> </td>
          <td width="29%" align="left" valign="middle" class="tdcolorgray"><input name="shop_email" type="text" class="input" size="25" value="<?php echo $_REQUEST['shop_email']?>"  maxlength="100"/></td>
          
		  </tr>
		   <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Contact Person <span class="redtext">*</span> </td>
          <td width="29%" align="left" valign="middle" class="tdcolorgray"><input name="shop_contactperson" type="text" class="input" size="25" value="<?php echo $_REQUEST['shop_contactperson']?>"  maxlength="100"/></td>
          <td width="22%" align="left" valign="middle" class="tdcolorgray">Active Warehouse </td>
          <td width="29%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="shop_active" value="1" <? if($_REQUEST['shop_active']==1) echo "checked";?> />
            Yes
              <input name="shop_active" type="radio" value="0" <? if($_REQUEST['shop_active']==0) echo "checked";?> />
          No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PRODUCT_STORE_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>

		
		  </tr>
		   <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Order </td>
          <td width="29%" align="left" valign="middle" class="tdcolorgray"><input name="shop_order" type="text" class="input" size="3" value="<?php echo $_REQUEST['shop_order']?>"  maxlength="15"/></td>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" > </td>
          <td width="29%" align="left" valign="middle" class="tdcolorgray"></td>
          </tr>
		  </table>
		  </div>
		  </td>
		  </tr>
		  
		<tr>
			<td colspan="4" align="center" valign="middle">
				<div class="editarea_div">
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="100%" align="right" valign="middle">
						<input type="hidden" name="storename" id="storename" value="<?=$_REQUEST['storename']?>" />
						<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
						<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
						<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
						<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
						<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
						<input type="hidden" name="fpurpose" id="fpurpose" value="save_add" />
						<input name="productstore_Submit" type="submit" class="red" value="Save" />
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>		
  </table>
</form>	  

