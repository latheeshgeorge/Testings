<?php
/*#################################################################
# Script Name 	: Edit_AbletoBy.php
# Description 	: Page for Adding Shops under a site
# Coded by 		: LH
# Created on	: 11-Apr-2008
# Modified by	: 
# Modified On	: 
#################################################################
#Define constants for this page
*/
$page_type = 'AbleToBy';
$help_msg = 'This section helps in editing a  details of AbleToBy payment type.';
$sql = "SELECT * FROM payment_method_forsites_able2buy_details WHERE det_id=".$_REQUEST['det_id'];
$res = $db->query($sql);
$row = $db->fetch_array($res);
?>
<script language="javascript">
function valform(frm)
{
	fieldRequired = Array('det_code','det_caption');
	fieldDescription = Array('Code','Caption');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('det_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmEditAbleToByInSite' action='home.php?request=sites' method="post" onsubmit="return valform(this);">

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="maininnertable">
      <tr>
        <td align="left" class="menutabletoptd"><a href="home.php?request=sites&code=<?=$code?>&domain=<?=$domain?>&client=<?=$client?>&theme=<?php $_REQUEST['theme']?>&site_status=<?=$site_status?>&pass_sort_by=<?=$pass_sort_by?>&pass_sort_order=<?=$pass_sort_order?>&pass_records_per_page=<?=$pass_records_per_page?>&pass_pg=<?=$pass_pg?>">&nbsp;<strong>List Sites</strong></a><strong> <b><font size="1">&gt;&gt;</font></b> Add <?=$page_type?></strong></td>
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
				  <td colspan="3" class="redtext"><div align="left">* <span>are required </span></div></td>
				</tr>
				<tr>
				  <td width="29%" align="right" class="fontblacknormal">Code </td>
				  <td width="1%" align="center">:</td>
				  <td colspan="2" align="left"><input name="det_code" type="text" id="det_code" value="<?=$row['det_code']?>" size="65" />
			      <span class="redtext">*</span><span class="fontblacknormal"> </span></td>
		      </tr>
				<tr>
				  <td align="right" class="fontblacknormal"> Caption </td>
				  <td align="center">:</td>
				  <td colspan="2" align="left"><span class="fontblacknormal">
				    <input name="det_caption" type="text" id="det_caption" value="<?=$row['det_caption']?>" size="65" />
                    <span class="redtext">*</span>&nbsp;</span></td>
	            </tr>
				
				<tr>
				  <td align="right" class="fontblacknormal">Order</td>
				  <td align="center">:</td>
				  <td colspan="2" align="left"><input name="det_order" type="text" size="2" value="<?php echo $row['det_order']?>" /></td>
		      </tr>
				
				<tr>
				  <td align="right">Active</td>
			      <td align="right">:</td>
			      <td colspan="2" align="left"><input name="det_hidden" type="checkbox" id="det_hidden" value="1" <?php echo ($row['det_hidden']==0)?'checked="checked"':''?> /></td>
		      </tr>
				<tr>
				  <td colspan="3" align="right">&nbsp;</td>
				</tr>
				<tr align="center">
				<td colspan="3">
				<input type="hidden" name="det_id" id="det_id" value="<?=$_REQUEST['det_id']?>" />
					<input type="hidden" id='code_name' name="code_name" value="<?=$_REQUEST['code_name']?>" />
					<input type="hidden" name="site_id" id="site_id" value="<?=$_REQUEST['site_id']?>" />
					<input type="hidden" name="title" id="title" value="<?=$_REQUEST['title']?>" />
                    <input type="hidden" name="domain" id="domain" value="<?=$_REQUEST['domain']?>" />
                    <input type="hidden" name="client" id="client" value="<?=$_REQUEST['client']?>" />
                    <input type="hidden" name="status" id="status" value="<?=$_REQUEST['status']?>" />
					<input type="hidden" name="theme" id="theme" value="<?=$_REQUEST['theme']?>" />
                    <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
                    <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
                    <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
                    <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
					 <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
                    <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
                    <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
                    <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
                    <input type="hidden" name="fpurpose" id="fpurpose" value="update_AbleToBY" />
                  <input type="Submit" name="UpdateAbleToBy_submit" id="UpdateAbleToBy_submit" value="Update" class="input-button"></td>
			  </tr>
				<tr>
				  <td colspan="4" align="right">&nbsp;</td>
				</tr>
			</table>
		</td>
      </tr>
  </table>
</form>