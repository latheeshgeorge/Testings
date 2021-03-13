<?php
	/*#################################################################
	# Script Name 	: add_vendor.php
	# Description 	: Page for adding Site Vendor
	# Coded by 		: SKR
	# Created on	: 18-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Product Vendor';
$help_msg =get_help_messages('ADD_PROD_VENDOR_MESS1');

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('vendor_name','vendor_email');
	fieldDescription = Array('Vendor Name','Email');
	fieldEmail = Array('vendor_email');
	fieldConfirm = Array();
	fieldSpecChars = Array('vendor_name','vendor_telephone');
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmAddUser' action='home.php?request=prod_vendor' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_vendor&sort_by=<?=$sort_by?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Vendors</a> <span> Add Vendor</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?
		if($alert)
		{
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		 <?
		 }
		 ?> 
		  <tr>
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">

      <table  border="0" cellpadding="2" cellspacing="2"  width="100%">
		 <tr>
          <td width="16%" align="left" valign="middle" class="tdcolorgray" >Vendor Name <span class="redtext">*</span> </td>
          <td  align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="vendor_name"  value="<?=$_REQUEST['vendor_name']?>" />		  </td>
		    <td width="8%" align="left" valign="middle" class="tdcolorgray">Telephone</td>
          <td width="56%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="vendor_telephone"  value="<?=$_REQUEST['vendor_telephone']?>" maxlength="20" /></td>
        </tr>
		  <tr>
          <td width="16%" align="left" valign="middle" class="tdcolorgray" >Address</td>
          <td width="20%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="vendor_address" value="<?=$_REQUEST['vendor_address']?>"  />		  </td>
		   <td align="left" valign="middle" class="tdcolorgray">Fax</td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="vendor_fax" value="<?=$_REQUEST['vendor_fax']?>"  /></td>
        	    </tr>
		  
		  <tr>
          <td width="16%" align="left" valign="middle" class="tdcolorgray" >Email<span class="redtext">&nbsp;*</span> </td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="vendor_email" value="<?=$_REQUEST['vendor_email']?>"  />		  </td>
		   <td align="left" valign="middle" class="tdcolorgray" >Website</td>
          <td  align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="vendor_website" value="<?=$_REQUEST['vendor_website']?>"  /></td>
         </tr>
		 <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Hidden</td>
          <td align="left" valign="middle" class="tdcolorgray" ><input type="radio" name="vendor_hide" value="Y"  <? if($_REQUEST['vendor_hide']=='Y') echo "checked";?>/>
            Yes
              <input type="radio" name="vendor_hide" value="N"   <? if($_REQUEST['vendor_hide']=='N') echo "checked"; if(!$_REQUEST['vendor_hide']) echo "checked"?>/>
           No <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_VENDOR_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
	    </tr>
		<tr>
			  <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
			  <td colspan="3" align="left" valign="middle" class="tdcolorgray">			  </td>
		 </tr>
		         </table>
        </div>
        </td>
        </tr>
         <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">
      <table  border="0" cellpadding="0" cellspacing="0"  width="100%">        
		<tr>
          <td  align="right" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['search_name']?>" />
		   <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
		  <input name="Submit" type="submit" class="red" value="Save" />
		  &nbsp;&nbsp;</td>
        </tr>
        </table>
        </div>
        </td>
        </tr>
      </table>
</form>	  

