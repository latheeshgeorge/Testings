<?php
	/*#################################################################
	# Script Name 	: add_contact.php
	# Description 	: Page for adding Vendor Contact
	# Coded by 		: SKR
	# Created on	: 21-June-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Vendor Contact';
$help_msg = 'This section helps in adding the Vendor Contacts';

?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('contact_name','contact_email');
	fieldDescription = Array('Contact Name','Email');
	fieldEmail = Array('contact_email');
	fieldSpecChars = Array('contact_name','contact_phone','contact_mobile');
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('contact_sortorder');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmAddContact' action='home.php?request=prod_vendor' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=prod_vendor">Manage Vendor</a> <a href="home.php?request=prod_vendor&fpurpose=list_contact&vendor_id=<?=$_REQUEST['vendor_id']?>&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Contacts</a> <span>Add Contact</span></div></td>
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
		 </table>
		 <div class="editarea_div">
		 <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Contact Name <span class="redtext">*</span> </td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="contact_name" value="<?=$_REQUEST['contact_name']?>"  />		  </td>
          <td align="left" valign="middle" class="tdcolorgray">Email <span class="redtext">*</span></td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="contact_email" value="<?=$_REQUEST['contact_email']?>"  /></td>
        </tr>
		  <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Address</td>
          <td width="20%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="contact_address" value="<?=$_REQUEST['contact_address']?>"  />		  </td>
          <td width="10%" align="left" valign="middle" class="tdcolorgray">Fax</td>
          <td width="55%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="contact_fax"  value="<?=$_REQUEST['contact_fax']?>" /></td>
	    </tr>
		  
		 <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Telephone</td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="contact_phone"  value="<?=$_REQUEST['contact_phone']?>" maxlength="20" /></td>
          	      <td align="left" valign="middle" class="tdcolorgray">Position</td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="contact_position" value="<?=$_REQUEST['contact_position']?>"  /></td>

				</tr>
		  <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Mobile</td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="contact_mobile"  value="<?=$_REQUEST['contact_mobile']?>" />		  </td>
         <td align="left" valign="middle" class="tdcolorgray">Sort Order </td>
          <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="contact_sortorder" value="<?=$_REQUEST['contact_sortorder']?>" size="3"></td>
		 </tr>
		
       
        
		 <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
		</table>
		</div>
		<div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
          <td align="right" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
		   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
		  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
		  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
		  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
		  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="insert_contact" />
		  <input type="hidden" name="vendor_id" value="<?=$_REQUEST['vendor_id']?>" />
		  <input type="hidden" name="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
		  <input type="hidden" name="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
		  <input type="hidden" name="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
		  <input type="hidden" name="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
		  <input type="hidden" name="pass_start" value="<?=$_REQUEST['pass_start']?>" />
		  <input name="Submit" type="submit" class="red" value="Save" /></td>
        </tr>
      </table>
	  </div>
</form>	  

