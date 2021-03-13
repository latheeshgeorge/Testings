<?php
	/*#################################################################
	# Script Name 	: add_product_attachment.php
	# Description 	: Page for adding Product Attachments
	# Coded by 		: Sny
	# Created on	: 26-Jul-2007
	# Modified by	: Sny
	# Modified On	: 27-Jul-2007
	#################################################################*/
	
//Define constants for this page
$page_type = 'Products';
$help_msg = get_help_messages('PROD_ATTACH_ADD_MAIN_HELP');//'This section helps in adding Product Attachments';

// Get the name of current product
$sql_prod = "SELECT product_name FROM products WHERE product_id=".$_REQUEST['checkbox'][0];
$ret_prod = $db->query($sql_prod);
if ($db->num_rows($ret_prod))
{
	$row_prod = $db->fetch_array($ret_prod);
	$showprodname = stripslashes($row_prod['product_name']);
}
?>	
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	var atleastone = false;
	fieldRequired = Array('attach_title');
	fieldDescription = Array('Attachment Title');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('tab_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
			show_processing();
			return true;
	}	
	else
	{
		return false;
	}
}
</script>
<form action='home.php?request=products' method="post" enctype="multipart/form-data" name='frmAddProductAttach' onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Products</a>  <a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&curtab=<?php echo $_REQUEST['curtab']?>">Edit Product</a>  <span>Add Attachment for Product &quot;<?php echo $showprodname?> &quot;</span> </div></td>
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
               <td width="9%" align="left">Title <span class="redtext"> *</span></td>
               <td width="31%" align="left"><input name="attach_title" type="text" id="attach_title" value="<?php echo $_REQUEST['attach_title']?>" size="30" /></td>
               <td width="10%" align="left">&nbsp;</td>
               <td width="50%" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td align="left">Type </td>
               <td align="left">
			   <?php
			  		$attach_type = array('Audio'=>'Audio(mp3,wma)','Video'=>'Video(mpg,mpeg,wmv)','Pdf'=>'Pdf','Other'=>'Other');
					echo generateselectbox('attach_type',$attach_type,$_REQUEST['attach_type']);
				?>			   </td>
               <td align="left">&nbsp;</td>
               <td align="left">&nbsp;</td>
             </tr>
             <tr>
               <td align="left">File</td>
               <td align="left"><input name="attach_file" type="file" id="attach_file" /></td>
               <td colspan="2" align="left">&nbsp;</td>
             </tr>
			 <?php
			 // Check whether attachment icon option is to be displayed
			 $sql_check = "SELECT allow_attachment_icon FROM themes WHERE theme_id = $ecom_themeid LIMIT 1";
			 $ret_check = $db->query($sql_check);
			 if($db->num_rows($ret_check))
			 {
			 	$row_check = $db->fetch_array($ret_check);
				if($row_check['allow_attachment_icon']==1)
				{
			 ?>
             <tr>
               <td align="left">Icon</td>
               <td align="left"><input type="file" name="attach_file_icon" />
                 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_ATTACH_ICON')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td colspan="2" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td align="left">Hide</td>
               <td align="left"><input type="radio" name="attach_hide" value="1" <?php echo ($_REQUEST['attach_hide']==1)?'checked="checked"':''?> />
Yes
  <input name="attach_hide" type="radio" value="0" <?php echo ($_REQUEST['attach_hide']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('Use this section to hide this attachment.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td colspan="2" align="left">&nbsp;</td>
             </tr>
             <tr>
               <td align="left">Order</td>
               <td align="left"><input name="attach_order" type="text" id="attach_order" value="<?php echo $_REQUEST['attach_order']?>" size="5"/></td>
               <td colspan="2" align="left">&nbsp;</td>
             </tr>
			 <?php
			 	}
			 }
			 ?>
           </table>
		   </div>
		   </td>
         </tr>
         
         <tr>
           <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
         </tr>
        <tr>
          <td colspan="4" align="right" valign="middle" class="tdcolorgray">
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
			<input type="hidden" name="checkbox[0]" id="checkbox[0]" value="<? echo $_REQUEST['checkbox'][0];?>" />
			<input type="hidden" name="fpurpose" id="fpurpose" value="save_add_prodattach" />
			<input type="hidden" name="curtab" id="curtab" value="<?=$_REQUEST['curtab']?>" />
			<input name="prodtab_Submit" type="submit" class="red" value="Save" />
		</div>	
			
			</td>
        </tr>
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
  </table>
</form>	  
