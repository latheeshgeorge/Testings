<?php
	/*#################################################################
	# Script Name 	: edit_product_variable_message.php
	# Description 	: Page for editing Product variable messages
	# Coded by 		: Sny
	# Created on	: 02-Jul-2007
	# Modified by	: Sny
	# Modified On	: 23-Jul-2007
	#################################################################*/
//Define constants for this page
$page_type = 'Products';
$help_msg = 'This section helps in editing Product Variable Messages';

// Get the name of current product
$sql_prod = "SELECT product_name FROM products WHERE product_id=".$_REQUEST['checkbox'][0]. " AND sites_site_id = $ecom_siteid LIMIT 1";
$ret_prod = $db->query($sql_prod);
if ($db->num_rows($ret_prod))
{
	$row_prod = $db->fetch_array($ret_prod);
	$showprodname = stripslashes($row_prod['product_name']);
}
else
	exit;
// Get the details of messages being editing
$sql_msg = "SELECT * FROM product_variable_messages WHERE message_id=$edit_id";
$ret_msg = $db->query($sql_msg);
if ($db->num_rows($ret_msg))
{
	$row_msg = $db->fetch_array($ret_msg);
}
?>	
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('message_title');
	fieldDescription = Array('Message Title');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('message_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		show_procession();
		return true;	
	}	
	else
	{
		return false;
	}
}
</script>
<form name='frmEditProductVariableMessage' action='home.php?request=products' method="post" onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Products</a> <a href="home.php?request=products&amp;fpurpose=edit&amp;checkbox[0]=<?php echo $_REQUEST['checkbox'][0]?>&amp;productname=<?php echo $_REQUEST['productname']?>&amp;manufactureid=<?php echo $_REQUEST['manufactureid']?>&amp;categoryid=<?php echo $_REQUEST['categoryid']?>&amp;vendorid=<?php echo $_REQUEST['vendorid']?>&amp;rprice_from=<?php echo $_REQUEST['rprice_from']?>&amp;rprice_to=<?php echo $_REQUEST['rprice_to']?>&amp;cprice_from=<?php echo $_REQUEST['cprice_from']?>&amp;cprice_to=<?php echo $_REQUEST['cprice_to']?>&amp;discount=<?php echo $_REQUEST['discount']?>&amp;discountas=<?php echo $_REQUEST['discountas']?>&amp;bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&amp;stockatleast=<?php echo $_REQUEST['stockatleast']?>&amp;preorder=<?php echo $_REQUEST['preorder']?>&amp;prodhidden=<?php echo $_REQUEST['prodhidden']?>&amp;start=<?php echo $_REQUEST['start']?>&amp;pg=<?php echo $_REQUEST['pg']?>&amp;records_per_page=<?php echo $_REQUEST['records_per_page']?>&amp;sort_by=<?php echo $sort_by?>&amp;sort_order=<?php echo $sort_order?>&curtab=<?=$_REQUEST['curtab']?>">Edit Product</a> <span> Edit Product Variable Message for &quot;<?php echo $showprodname?>&quot;</span> </td>
        </tr>
        <tr>
		  <td colspan="4" align="left" valign="middle" class="helpmsgtd_main">
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
           <td colspan="4" align="left" valign="top" class="tdcolorgraynormal" >
		   <div class="editarea_div">
		   <table width="100%" border="0" cellspacing="1" cellpadding="1">
             <tr>
               <td width="21%" align="left">Message Title <span class="redtext">*</span></td>
               <td width="32%" align="left"><input name="message_title" type="text" id="message_title" value="<?php echo stripslashes($row_msg['message_title'])?>" size="30" /></td>
               <td width="14%" align="left">&nbsp;</td>
               <td width="33%" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td align="left">Input Type </td>
               <td align="left"><input type="radio" name="message_type" value="1" <?php echo ($row_msg['message_type']=='TXTBX')?'checked="checked"':''?> />
Textbox
  <input name="message_type" type="radio" value="0" <?php echo ($row_msg['message_type']=='TXTAREA')?'checked="checked"':''?> />
Text Area<a href="#" onmouseover ="ddrivetip('Use this section to decide whether to show the input type as textbox or textarea.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td align="left">&nbsp;</td>
               <td align="left">&nbsp;</td>
             </tr>
             <tr>
               <td align="left">Hide</td>
               <td align="left"><input type="radio" name="message_hide" value="1" <?php echo ($row_msg['message_hide']==1)?'checked="checked"':''?> />
Yes
  <input name="message_hide" type="radio" value="0" <?php echo ($row_msg['message_hide']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('Use this section to hide this Variable.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td align="left">&nbsp;</td>
               <td align="left">&nbsp;</td>
             </tr>
             <tr>
               <td align="left">Order</td>
               <td align="left"><input name="message_order" type="text" size="5" value="<?php echo $row_msg['message_order']?>"/></td>
               <td align="left">&nbsp;</td>
               <td align="left">&nbsp;</td>
             </tr>
           </table>
		   </div>
		   </td>
         </tr>

        <tr>
          <td width="59%" colspan="4" align="right" valign="middle" class="tdcolorgray">
<div class="editarea_div">
		  	<input type="hidden" name="productname" id="productname" value="<?=$_REQUEST['productname']?>" />
		  	<input type="hidden" name="manufactureid" id="manufactureid" value="<?=$_REQUEST['manufactureid']?>" />
		  	<input type="hidden" name="categoryid" id="categoryid" value="<?=$_REQUEST['categoryid']?>" />
		  	<input type="hidden" name="vendorid" id="vendorid" value="<?=$_REQUEST['vendorid']?>" />
			<input type="hidden" name="rprice_from" id="rprice_from" value="<?=$_REQUEST['rprice_from']?>" />
			<input type="hidden" name="rprice_to" id="rprice_to" value="<?=$_REQUEST['rprice_to']?>" />
			<input type="hidden" name="cprice_from" id="cprice_from" value="<?=$_REQUEST['cprice_from']?>" />
			<input type="hidden" name="cprice_to" id="cprice_to" value="<?=$_REQUEST['cprice_to']?>" />
			<input type="hidden" name="discount" id="discount" value="<?=$_REQUEST['discount']?>" />
			<input type="hidden" name="discountas" id="discountas" value="<?=$_REQUEST['discountas']?>" />
			<input type="hidden" name="bulkdiscount" id="bulkdiscount" value="<?=$_REQUEST['bulkdiscount']?>" />
			<input type="hidden" name="stockatleast" id="stockatleast" value="<?=$_REQUEST['stockatleast']?>" />
			<input type="hidden" name="preorder" id="preorder" value="<?=$_REQUEST['preorder']?>" />
			<input type="hidden" name="prodhidden" id="prodhidden" value="<?=$_REQUEST['prodhidden']?>" />
			<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			<input type="hidden" name="parent_id" id="parent_id" value="<?=$_REQUEST['parent_id']?>" />
			<input type="hidden" name="edit_id" id="edit_id" value="<?=$_REQUEST['edit_id']?>" />
			<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<? echo $_REQUEST['checkbox'][0];?>" />
			<input type="hidden" name="curtab" id="curtab" value="<?=$_REQUEST['curtab']?>" />
			<input type="hidden" name="fpurpose" id="fpurpose" value="save_editprodmsg" />
			<input name="prodmsg_Submit" type="submit" class="red" value="Save" />
			</div>
		  </td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
      </table>
</form>	  

