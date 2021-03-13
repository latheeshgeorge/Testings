<?php
/*#################################################################
# Script Name 	: add_site_shop.php
# Description 	: Page for Adding Shops under a site
# Coded by 		: ANU
# Created on	: 10-July-2007
# Modified by	: 
# Modified On	: 
#################################################################
#Define constants for this page
*/
$page_type = 'Shop in Site';
$help_msg = 'This section helps in adding a new Shop in a site.';
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('shop_title','shop_address','shop_phone','shop_mobile','shop_email');
	fieldDescription = Array('Shop Title','Shop Address','Shop Phone','Mobile','Email');
	fieldEmail = Array('shop_email');
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('shop_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmAddShopsInSite' action='home.php?request=sites' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd"><a href="home.php?request=sites&title=<?=$title?>&domain=<?=$domain?>&client=<?=$client?>&theme=<?php $_REQUEST['theme']?>&site_status=<?=$site_status?>&sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&records_per_page=<?=$records_per_page?>&pg=<?=$pg?>">&nbsp;<strong>List Sites</strong></a><strong> <b><font size="1">&gt;&gt;</font></b> Add <?=$page_type?></strong></td>
      </tr>
       <tr>
        <td class="maininnertabletd3">
			<?=$help_msg?>
		</td>
      </tr>
	  <tr>
        <td class="maininnertabletd2">
			<table width="100%"  border="0" cellpadding="4" cellspacing="1" class="">
				<tr align="left">
				  <td colspan="4" class="redtext"><div align="left">* <span>are required </span></div></td>
				</tr>
				<tr>
				  <td width="29%" align="right" class="fontblacknormal">Shop title </td>
				  <td width="1%" align="center">:</td>
				  <td width="25%" align="left"><input name="shop_title" type="text" id="shop_title" value="<?=$_REQUEST['shop_title']?>" size="30" />
			      <span class="redtext">*</span></td>
			      <td align="left"><span class="fontblacknormal"> </span></td>
		      </tr>
				<tr>
				  <td align="right" class="fontblacknormal"> Address </td>
				  <td align="center">:</td>
				  <td align="left"><span class="fontblacknormal">
				    <input name="shop_address" type="text" id="shop_address" value="<?=$_REQUEST['shop_address']?>" size="30" />
                    <span class="redtext">*</span>&nbsp;</span></td>
		          <td align="left">&nbsp;</td>
	            </tr>
				<tr>
				  <td align="right" class="fontblacknormal"> Phone </td>
				  <td align="center">:</td>
				  <td align="left"><input name="shop_phone" type="text" id="shop_phone" value="<?=$_REQUEST['shop_phone']?>" size="30"><span class="redtext">&nbsp;*</span></td>
				  <td align="left">&nbsp;</td>
			    </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Mobile </td>
				  <td align="center">:</td>
				  <td align="left"><input name="shop_mobile" type="text" id="shop_mobile" value="<?=$_REQUEST['shop_mobile']?>" size="30" />
			      <span class="redtext">*</span></td>
			      <td align="left">&nbsp;</td>
		      </tr>
				<tr>
				  <td align="right" class="fontblacknormal"> Email  </td>
				  <td align="center">:</td>
				  <td align="left"><input name="shop_email" type="text" id="shop_email" value="<?=$_REQUEST['shop_email']?>" size="30" />
			      <span class="redtext">&nbsp;*</span></td>
		          <td align="left">&nbsp;</td>
	          </tr>
				<tr>
				  <td align="right" class="fontblacknormal"> Contact Person </td>
				  <td align="center">:</td>
				  <td align="left"><input name="shop_contactperson" type="text" id="shop_contactperson" value="<?=$_REQUEST['shop_contactperson']?>" size="30" /></td>
			      <td align="left">&nbsp;</td>
		      </tr>
				<tr>
<td align="right" class="fontblacknormal">Contect Person Designation </td>
				  <td align="center">:</td>
				  <td align="left"><input name="shop_conatactperson_designation" type="text" id="shop_conatactperson_designation" value="<?=$_REQUEST['shop_conatactperson_designation']?>" size="30" /></td>
		          <td align="left">&nbsp;</td>
			  </tr>
				<tr>
				  <td align="right" class="fontblacknormal">Order</td>
				  <td align="center">:</td>
				  <td align="left"><input name="shop_order" type="text" size="2" value="<?php echo $_REQUEST['shop_order']?>" /></td>
			      <td align="left">&nbsp;</td>
		      </tr>
				
				<tr>
				  <td align="right">Active</td>
			      <td align="right">:</td>
			      <td align="left"><input name="shop_active" type="checkbox" id="shop_active" value="1" <?php echo ($_REQUEST['shop_active']==1)?'checked="checked"':''?> /></td>
			      <td align="right">&nbsp;</td>
		      </tr>
				<tr>
				  <td colspan="4" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td colspan="5">
					<input type="hidden" name="site_id" id="title" value="<?=$_REQUEST['site_id']?>" />
					<input type="hidden" name="title" id="title" value="<?=$_REQUEST['title']?>" />
                    <input type="hidden" name="domain" id="domain" value="<?=$_REQUEST['domain']?>" />
                    <input type="hidden" name="client" id="client" value="<?=$_REQUEST['client']?>" />
                    <input type="hidden" name="status" id="status" value="<?=$_REQUEST['status']?>" />
					<input type="hidden" name="theme" id="theme" value="<?=$_REQUEST['theme']?>" />
                    <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
                    <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
                    <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
                    <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
                    <input type="hidden" name="fpurpose" id="fpurpose" value="insert_shops" />
                  <input type="Submit" name="AddShop_submit" id="AddShop_submit" value="Add" class="input-button"></td>
			  </tr>
				<tr>
				  <td colspan="4" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
  </table>
</form>