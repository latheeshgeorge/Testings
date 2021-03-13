<?php
	/*#################################################################
	# Script Name 	: add_product_attachment.php
	# Description 		: Page for adding Product Attachments
	# Coded by 		: Sny
	# Created on		: 26-Aug-2008
	# Modified by		: 
	# Modified On		: 
	#################################################################*/
	
//Define constants for this page
$page_type = 'Products';
$help_msg = get_help_messages('PROD_MAIN_EDIT_DOWNLOADABLE');

// Get the name of current product
$sql_prod = "SELECT product_name 
						FROM 
							products 
						WHERE 
							product_id=".$_REQUEST['checkbox'][0]." 
							AND sites_site_id = $ecom_siteid 
						LIMIT 
							1";
$ret_prod = $db->query($sql_prod);
if ($db->num_rows($ret_prod))
{
	$row_prod = $db->fetch_array($ret_prod);
	$showprodname = stripslashes($row_prod['product_name']);
}
else
	 exit;

// Get the details of selected downloadable item
$sql_download = "SELECT proddown_id,sites_site_id,products_product_id,proddown_adddate,proddown_title,proddown_shortdesc,proddown_filename,proddown_orgfilename,
								proddown_order,proddown_limited,proddown_limit,proddown_days_active,proddown_days,proddown_hide 
							FROM 
								product_downloadable_products 
							WHERE 
								sites_site_id = $ecom_siteid 
								AND proddown_id=".$_REQUEST['edit_id']." 
							LIMIT 
								1";
$ret_download = $db->query($sql_download);
if ($db->num_rows($ret_download))
{
	$row_download = $db->fetch_array($ret_download);
}
?>	
<script language="javascript" type="text/javascript">
function valforms(frm)
{
	fieldRequired 			= Array('proddown_title');
	fieldDescription 		= Array('Title');
	fieldEmail				= Array();
	fieldConfirm 			= Array();
	fieldConfirmDesc  	= Array();
	fieldNumeric 			= Array('proddown_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
	{
		if(document.getElementById('proddown_limited').checked)
		{
			if (document.getElementById('proddown_limit').value=='' || isNaN(document.getElementById('proddown_limit').value) || document.getElementById('proddown_limit').value<0)
			{
				alert('Download limit should be specified and it should be numeric and positive');
				return false;
			}	
		}
		if(document.getElementById('proddown_days_active').checked)
		{
			if (document.getElementById('proddown_days').value=='' || isNaN(document.getElementById('proddown_days').value) || document.getElementById('proddown_days').value<0)
			{
				alert('Number of Active days should be specified and it should be numeric and positive');
				return false;
			}	
		}
			show_processing();
			return true;
	}	
	else
	{
		return false;
	}
}
function handle_download_tr(mainobj,obj)
{
	if(mainobj.checked)
		obj.style.display = '';
	else
		obj.style.display = 'none';
}
</script>
<form action='home.php?request=products' method="post" enctype="multipart/form-data" name='frmEditProductDownload' onsubmit="return valforms(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=products&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>">List Products</a> <a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $_REQUEST['checkbox'][0]?>&productname=<?php echo $_REQUEST['productname']?>&manufactureid=<?php echo $_REQUEST['manufactureid']?>&categoryid=<?php echo $_REQUEST['categoryid']?>&vendorid=<?php echo $_REQUEST['vendorid']?>&rprice_from=<?php echo $_REQUEST['rprice_from']?>&rprice_to=<?php echo $_REQUEST['rprice_to']?>&cprice_from=<?php echo $_REQUEST['cprice_from']?>&cprice_to=<?php echo $_REQUEST['cprice_to']?>&discount=<?php echo $_REQUEST['discount']?>&discountas=<?php echo $_REQUEST['discountas']?>&bulkdiscount=<?php echo $_REQUEST['bulkdiscount']?>&stockatleast=<?php echo $_REQUEST['stockatleast']?>&preorder=<?php echo $_REQUEST['preorder']?>&prodhidden=<?php echo $_REQUEST['prodhidden']?>&start=<?php echo $_REQUEST['start']?>&pg=<?php echo $_REQUEST['pg']?>&records_per_page=<?php echo $_REQUEST['records_per_page']?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>&curtab=<?php echo $_REQUEST['curtab']?>">Edit Product</a>  <span> Edit Downlodable Items for the Product <strong>&quot;<?php echo $showprodname?> &quot; </strong></span></div></td>
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
               <td  align="left">Title <span class="redtext"> *</span></td>
               <td width="27%" align="left"><input name="proddown_title" type="text" id="proddown_title" value="<?php echo stripslashes($row_download['proddown_title'])?>" size="30" /></td>
               <td width="11%" align="left">Hidden?</td>
               <td width="50%" align="left"><input type="radio" name="proddown_hide" value="1" <?php echo ($row_download['proddown_hide']==1)?'checked="checked"':''?> />
Yes
  <input name="proddown_hide" type="radio" value="0" <?php echo ($row_download['proddown_hide']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('<?php echo get_help_messages('PROD_DOWNLOADABLE_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><a href="#" onmouseover ="ddrivetip('Use this section to hide this downlodable item.')"; onmouseout="hideddrivetip()"></a></td>
             </tr>
             <tr>
               <td  align="left">Update File</td>
               <td align="left"><input name="proddown_filename" type="file" id="proddown_filename" />
               <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_ATTACH_UPFILE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
               <td align="left" valign="bottom">Current File</td>
               <td align="left" valign="bottom"><?php
				  	if($row_download['proddown_orgfilename'])
					{
						
				  ?>
                 <?php echo $row_download['proddown_orgfilename']?> &nbsp;&nbsp;<a href="includes/products/download.php?download_id=<?php echo $row_download['proddown_id']?>" title="Download" class="edittextlink"><img src="images/download.gif" alt="Download" title="Click to download this file" border="0" /></a>
                 <?php
				  	}
				  ?></td>
             </tr>
             <tr>
               <td align="left">Short Description </td>
               <td colspan="3" align="left"><input name="proddown_shortdesc" id="proddown_shortdesc" type="text" size="100" value="<?php echo stripslashes($row_download['proddown_shortdesc'])?>" />
                 <a href="#" onmouseover ="ddrivetip('<?php echo get_help_messages('PROD_DOWNLOADABLE_SHORTDESC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><a href="#" onmouseover ="ddrivetip('Short description, if specified, will be displayed in the customer downloads page.')"; onmouseout="hideddrivetip()"></a></td>
             </tr>
             <tr>
               <td  align="left">Order</td>
               <td align="left"><input name="proddown_order" type="text" id="proddown_order" value="<?php echo $row_download['proddown_order']?>" size="5"/></td>
               <td align="left">&nbsp;</td>
               <td align="left">&nbsp;</td>
             </tr>
             <tr>
               <td width="12%" align="left"><input type="checkbox" name="proddown_limited" id="proddown_limited" value="1" onclick="handle_download_tr(this,document.getElementById('downloadlimit_tr'))" <?php echo ($row_download['proddown_limited']==1)?'checked="checked"':''?> /></td>
               <td colspan="4" align="left">Download Limited?<a href="#" onmouseover ="ddrivetip('<?php echo get_help_messages('PROD_DOWNLOADABLE_LIMITE_CHK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><a href="#" onmouseover ="ddrivetip('If the number of times the file can be downloaded by the same customer is to be limited then tick mark this.')"; onmouseout="hideddrivetip()"></a></td>
             </tr>
             <tr id="downloadlimit_tr" <?php echo ($row_download['proddown_limited']==1)?'':'style="display:none"'?>>
               <td align="left">&nbsp;</td>
               <td colspan="4" align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                   <tr>
                     <td align="left">Specify the maximum number of times the file can be downloaded
                       <input name="proddown_limit" id="proddown_limit" type="text" size="2" value="<?php echo $row_download['proddown_limit']?>" /></td>
                   </tr>
               </table></td>
             </tr>
             <tr>
               <td align="left"><input type="checkbox" name="proddown_days_active" id="proddown_days_active" value="1" onclick="handle_download_tr(this,document.getElementById('downloaddays_tr'))" <?php echo ($row_download['proddown_days_active']==1)?'checked="checked"':''?>/></td>
               <td colspan="4" align="left">Download Period Limited? <a href="#" onmouseover ="ddrivetip('<?php echo get_help_messages('PROD_DOWNLOADABLE_LIMIT_PRD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><a href="#" onmouseover ="ddrivetip('If the file download is to be allowed only for few days then use this option. The exact date range at which the download row_download be allowed for each of the customer depends on the date on which the payment is marked as paid.')"; onmouseout="hideddrivetip()"></a></td>
             </tr>
             <tr id="downloaddays_tr"  <?php echo ($row_download['proddown_days_active']==1)?'':'style="display:none"'?>>
               <td align="left">&nbsp;</td>
               <td colspan="4" align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                   <tr>
                     <td align="left">Specify the number of days for which the file can be downloaded
                       <input name="proddown_days" id="proddown_days" type="text" size="2" value="<?php echo $row_download['proddown_days']?>" /></td>
                   </tr>
               </table></td>
             </tr>
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
			<input type="hidden" name="edit_id" id="edit_id" value="<? echo $_REQUEST['edit_id'];?>" />
			<input type="hidden" name="fpurpose" id="fpurpose" value="save_edit_proddownload" />
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

