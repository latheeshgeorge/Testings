<? 
	/*#################################################################
	# Script Name 	: export_for_googlebase.php
	# Description 	: Page for exporting the files required to be submitted to google base
	# Coded by 		: Sny
	# Created on	: 28-Nov-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
	if($_REQUEST['code_type'])
		$ctype = $_REQUEST['code_type'];
	else
		$ctype = 'default';
	//Define constants for this page
	$page_type = 'Product Category';
	$help_msg = get_help_messages('LIST_GOOGLE_EXPORT_MESS1');
	
?>
<script type='text/javascript'>
	function handle_url_update()
	{
		var qrystr = '';
		var ecomhost = '<?php echo $ecom_hostname?>';
		if(document.getElementById('chk_brand_req').checked)
			qrystr = 'brn=1';
		if(document.getElementById('chk_barcode_req').checked)
		{
			if(qrystr!='')
				qrystr = qrystr + '&';
			qrystr = qrystr + 'brc=1';
		}
		
		if(document.getElementById('chk_mpn_req').checked)
		{
			if(qrystr!='')
				qrystr = qrystr + '&';
			qrystr = qrystr + 'mpn=1';
		}
		
		if(document.getElementById('chk_availability_req').checked)
		{
			if(qrystr!='')
				qrystr = qrystr + '&';
			qrystr = qrystr + 'avi=1';
		}
		
		if(document.getElementById('chk_google_product_category_req').checked)
		{
			if(qrystr!='')
				qrystr = qrystr + '&';
			qrystr = qrystr + 'gpc=1';
		}
		if(document.getElementById('chk_google_product_apparel_req'))
		{
			if(document.getElementById('chk_google_product_apparel_req').checked)
			{
				document.getElementById('apparel_agegroup_tr').style.display='';
				if(document.getElementById('apparel_agegroup_req').checked==true)
				{
					if(qrystr!='')
					qrystr = qrystr + '&';
				qrystr = qrystr + 'gpag=1';
				}
				if(document.getElementById('apparel_gender_req').checked==true)
				{
				if(qrystr!='')
					qrystr = qrystr + '&';
				qrystr = qrystr + 'gpge=1';
				}
				if(document.getElementById('apparel_color_req').checked==true)
				{
				if(qrystr!='')
					qrystr = qrystr + '&';
				qrystr = qrystr + 'gpcol=1';
				}
				if(document.getElementById('apparel_size_req').checked==true)
				{
					if(qrystr!='')
						qrystr = qrystr + '&';
					qrystr = qrystr + 'gpsz=1';
				}		
			}
			else
			{
				document.getElementById('apparel_agegroup_tr').style.display='none';
				document.getElementById('apparel_agegroup_req').checked=false;
				document.getElementById('apparel_gender_req').checked=false;
				document.getElementById('apparel_color_req').checked=false;
				document.getElementById('apparel_size_req').checked=false;
	
			}
		}		
		if(document.getElementById('chk_add_image_link_req').checked)
		{
			if(qrystr!='')
				qrystr = qrystr + '&';
			qrystr = qrystr + 'ail=1';
		}
				
		if(qrystr!='')
			qrystr = '?' + qrystr; 
		document.getElementById('google_base_input_url').innerHTML='http://'+ecomhost+'/googlebaseExport.php'+qrystr; 	
	}
	
</script>
<form action="export_for_googlebase.php" method="post" name="frm_promo">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
<tr>
<td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
	<td align="left" class="treemenutd">Google Base Exporter</td>
	</tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" >
	  <?php 
		  Display_Main_Help_msg($help_arr,$help_msg,1);
	  ?>
	 </td>
	</tr>
	<tr>
	<td></td>
	</tr>
	</table>
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
      <tr>
        <td height="316" align="left" valign="top">
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr id="tr_discval">
            <td align="right" valign="middle" class="tdcolorgray" >Include <strong>"Brand"</strong> column in export </td>
            <td align="center" valign="middle" class="tdcolorgray" ><input type="checkbox" name="chk_brand_req" id="chk_brand_req" onclick='handle_url_update()' value="1" checked="checked" /></td>
            <td colspan="2" align="left" valign="middle" class="tdcolorgray" >This option allows to decide whether the column <strong>&quot;Brand&quot;</strong> is to be included in the google base export file. If ticked, the column will be included otherwise it will be excluded. </td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="tdcolorgray" >&nbsp;</td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="listingtableheader" >The following logic will be used to set up data in <strong>Brand</strong> field (if included in the export) </td>
            </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td width="1%" align="left" class="tdcolorgray" >&nbsp;</td>
            <td width="60%" align="left" class="listingtablestyleA" >1. If <strong>&quot;Model&quot;</strong> is specified for a product, then that value will be placed in this field </td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="listingtablestyleA" >2. If <strong>&quot;Model&quot;</strong> is not specified and if <strong>&quot;Product Id&quot;</strong> is specified, then that value will be placed in this field. </td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="listingtablestyleA" >3. If both <strong>&quot;Model&quot;</strong> and <strong>&quot;Product Id&quot;</strong> are blank for a product, then the <strong>&quot; Product Name&quot;</strong>  will be placed in this field.</td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="listingtableheader" >&nbsp;</td>
          </tr>
          <tr id="tr_discval">
            <td colspan="4" align="left" class="tdcolorgray" >&nbsp;</td>
            </tr>
          <tr id="tr_discval">
            <td align="right" valign="middle" class="tdcolorgray" >Include <strong>"Barcode"</strong> column in export </td>
            <td align="center" valign="middle" class="tdcolorgray" ><input type="checkbox" name="chk_barcode_req" id="chk_barcode_req"  onclick='handle_url_update()' value="1"/></td>
            <td colspan="2" align="left" valign="middle" class="tdcolorgray" >This option allows to decide whether the column <strong>&quot;Barcode&quot;</strong> is to be included in the google base export file. If ticked, the column will be included otherwise it will be excluded. The name of the column will be <strong>&quot;gtin&quot;</strong></td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="tdcolorgray" >&nbsp;</td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="listingtableheader" >The following logic will be used to set up data in <strong>Barcode</strong> field (if included in the export) </td>
            </tr>
         <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="listingtablestyleA" >1. If a product do not have variables with values, then the barcode set for the product in the "Stock" tab will be used to fill this column. If variables have values, then the barcode assigned for the first varaible combination in the Stock tab will be set in the  <strong>&quot;Barcode&quot;</strong> column.</td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="listingtableheader" >&nbsp;</td>
          </tr>
          
          
           <tr id="tr_discval">
            <td colspan="4" align="left" class="tdcolorgray" >&nbsp;</td>
            </tr>
           <tr id="tr_discval">
            <td align="right" valign="middle" class="tdcolorgray" >Include <strong>"MPN"</strong> column in export </td>
            <td align="center" valign="middle" class="tdcolorgray" ><input type="checkbox" name="chk_mpn_req" id="chk_mpn_req"  onclick='handle_url_update()' value="1"/></td>
            <td colspan="2" align="left" valign="middle" class="tdcolorgray" >This option allows to decide whether the column <strong>&quot;MPN&quot;</strong> is to be included in the google base export file. If ticked, the column will be included otherwise it will be excluded. The name of the column will be <strong>&quot;MPN&quot;</strong></td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="tdcolorgray" >&nbsp;</td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="listingtableheader" >The following logic will be used to set up data in <strong>MPN</strong> field (if included in the export) </td>
            </tr>
         <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="listingtablestyleA" >1. For products without variable, the values set for the field &quot;Model&quot; in the product edit page under "Main Info" tab will be used to fill this column. For products with variables, the mpn values set for the product variable values will be used to fill in this column.</td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="listingtableheader" >&nbsp;</td>
          </tr>
          
          
          <tr id="tr_discval">
            <td colspan="4" align="left" class="tdcolorgray" >&nbsp;</td>
            </tr>
          <tr id="tr_discval">
            <td align="right" valign="middle" class="tdcolorgray" >Include <strong>"Availability"</strong> column in export </td>
            <td align="center" valign="middle" class="tdcolorgray" ><input type="checkbox" name="chk_availability_req" id="chk_availability_req"  onclick='handle_url_update()' value="1"/></td>
            <td colspan="2" align="left" valign="middle" class="tdcolorgray" >This option allows to decide whether the column <strong>&quot;Availability&quot;</strong> is to be included in the google base export file. If ticked, the column will be included otherwise it will be excluded. The name of the column will be <strong>&quot;availability&quot;</strong></td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="tdcolorgray" >&nbsp;</td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="listingtableheader" >The following logic will be used to set up data in <strong>Availability</strong> field (if included in the export) </td>
            </tr>
         <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="listingtablestyleA" >1. If stock is available for a product, then the value <strong>&quot;in stock&quot;</strong> will be used.</td>
          </tr>
         <tr>
           <td align="left" class="tdcolorgray" >&nbsp;</td>
           <td align="center" class="tdcolorgray" >&nbsp;</td>
           <td align="left" class="tdcolorgray" >&nbsp;</td>
           <td align="left" class="listingtablestyleA" >2. If stock is not available but the setting <strong>&quot;Allow ordering even if not in stock&quot;</strong>  is ticked for a product, then the value <strong>&quot;available for order&quot;</strong> will be used.</td>
         </tr>
         <tr>
           <td align="left" class="tdcolorgray" >&nbsp;</td>
           <td align="center" class="tdcolorgray" >&nbsp;</td>
           <td align="left" class="tdcolorgray" >&nbsp;</td>
           <td align="left" class="listingtablestyleA" >3. If the option <strong>&quot;Preorder&quot;</strong> is ticked for a product, then the value <strong>&quot;preorder&quot;</strong> will be used.</td>
         </tr>
         <tr>
           <td align="left" class="tdcolorgray" >&nbsp;</td>
           <td align="center" class="tdcolorgray" >&nbsp;</td>
           <td align="left" class="tdcolorgray" >&nbsp;</td>
           <td align="left" class="listingtablestyleA" >4. If <strong>stock is not available</strong> and options <strong>&quot;preorder&quot;</strong> and <strong>&quot;Allow ordering even if not in stock&quot;</strong> is not ticked, then the value <strong>&quot;out of stock&quot;</strong> will be used.</td>
         </tr>
          
        
        
        
        
         <tr id="tr_discval">
            <td colspan="4" align="left" class="tdcolorgray" >&nbsp;</td>
            </tr>
          <tr id="tr_discval">
            <td align="right" valign="middle" class="tdcolorgray" >Include <strong>"Google Product Category"</strong> column in export </td>
            <td align="center" valign="middle" class="tdcolorgray" ><input type="checkbox" name="chk_google_product_category_req" id="chk_google_product_category_req"  onclick='handle_url_update()' value="1"/></td>
            <td colspan="2" align="left" valign="middle" class="tdcolorgray" >This option allows to decide whether the column <strong>&quot;Google Product Category&quot;</strong> is to be included in the google base export file. If ticked, the column will be included otherwise it will be excluded. The name of the column will be <strong>&quot;google_product_category&quot;</strong></td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="tdcolorgray" >&nbsp;</td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="listingtableheader" >The 'Google product category' attribute indicates the category (or categories) of the product being sold.</td>
            </tr>
         <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="listingtablestyleA" > It only accepts categories from Google's product taxonomy
Currently, the 'Google product category' attribute only needs to be provided for products that belong to the following seven product categories in feeds that target the US, UK, Germany, and France:<br>

   <br> Apparel & Accessories > Clothing
    <br>Apparel & Accessories > Shoes
    <br>Apparel & Accessories (Note that submitting this value for clothing and shoes is not acceptable.)
    <br>Media > Books
   <br> Media > DVDs & Videos
    <br>Media > Music
    <br>Software > Video Game Software

<br>For products that belong to other product categories, providing this attribute is recommended. For countries other than the US, Japan, UK, Germany and France, this attribute is recommended for all product categories. </td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="listingtableheader" >&nbsp;</td>
          </tr>
        
        
          <tr id="tr_discval">
            <td colspan="4" align="left" class="tdcolorgray" >&nbsp;</td>
            </tr>
            <?php
            $sql_site = "SELECT is_apparel_site FROM sites WHERE site_id=$ecom_siteid LIMIT 1";
	$ret_site = $db->query($sql_site);
	if ($db->num_rows($ret_site))
	{
	$row_site = $db->fetch_array($ret_site);
	}
	if($row_site['is_apparel_site']==1)
	{
            ?>
          <tr id="tr_discval">
            <td align="right" valign="middle" class="tdcolorgray" >Include <strong>"Apparel Product Details"</strong> column in export </td>
            <td align="center" valign="middle" class="tdcolorgray" ><input type="checkbox" name="chk_google_product_apparel_req" id="chk_google_product_apparel_req"  onclick='handle_url_update()' value="1"/></td>
            <td colspan="2" align="left" valign="middle" class="tdcolorgray" >This option allows you to decide whether the "Apparel Product Details" is to be included in the google base export details. If this option is ticked, you will be able to see the attributes for the apparel products. Tick mark the attributes you would like to include in the export. </td>
          </tr>
        
         <tr  id="apparel_agegroup_tr" style="display:none;">
			 <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="listingtablestyleA" >
            <table border="0" cellpadding="0" cellspacing ="0" width="100%">
            <tr>
            <td width="5%"><input type="checkbox" name="apparel_agegroup_req" checked="checkeed" id="apparel_agegroup_req"  onclick='handle_url_update()' value="1"/></td><td>Age Group</td>
            </tr>
             <tr>
            <td width="5%"><input type="checkbox" name="apparel_gender_req" checked="checkeed" id="apparel_gender_req"  onclick='handle_url_update()' value="1"/></td><td>Gender</td>
            </tr>
             <tr>
            <td width="5%"><input type="checkbox" name="apparel_color_req" checked="checkeed" id="apparel_color_req"  onclick='handle_url_update()' value="1"/></td><td>Colour</td>
            </tr>
             <tr>
            <td width="5%"><input type="checkbox" name="apparel_size_req" checked="checkeed" id="apparel_size_req"  onclick='handle_url_update()' value="1"/></td><td>Size</td>
            </tr>
            </table>
             
            </td>
            </tr>
           <?php
	  }
          ?>
         
         <tr id="tr_discval">
            <td colspan="4" align="left" class="tdcolorgray" >&nbsp;</td>
            </tr>
          <tr id="tr_discval">
            <td align="right" valign="middle" class="tdcolorgray" >Include <strong>"Additional Image Link"</strong> column in export </td>
            <td align="center" valign="middle" class="tdcolorgray" ><input type="checkbox" name="chk_add_image_link_req" id="chk_add_image_link_req"  onclick='handle_url_update()' value="1"/></td>
            <td colspan="2" align="left" valign="middle" class="tdcolorgray" >This option allows to decide whether the column <strong>&quot;Additional Image Link&quot;</strong> is to be included in the google base export file. If ticked, the column will be included otherwise it will be excluded. The name of the column will be <strong>&quot;additional_image_link&quot;</strong></td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="tdcolorgray" >&nbsp;</td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="listingtableheader" >The following logic will be used to set up data in <strong>Additional Image Link</strong> field (if included in the export) </td>
            </tr>
         <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="listingtablestyleA" >1. If more than one images are assigned to a product, then first 10 image links (excluding the first image) will be included in this column with each of the image link seperated by a comma (,).</td>
          </tr>
          <tr id="tr_discval">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="center" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="listingtableheader" >&nbsp;</td>
          </tr>
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          <tr id="tr_discval">
            <td colspan="4" align="left" class="tdcolorgray" >&nbsp;</td>
            </tr>  
          <tr>
            <td colspan="4" align="left" style='color:#FF0000;font-weight:bold;font-size:14px;padding:10px 10px 10px 150px' >You can either download the file and upload it manually in google base account or specify the Data Feed url in your google base account.</td>
            </tr>
          <tr >
            <td colspan='4' class="tdcolorgray" >
            <table width='100%' cellpadding='0' cellspacing='0' style='border:dotted 1px'>
            <tr>
            <td width='48%' align='center' class='listingtableheader'>File Download Section</td>	
            <td width='2%' class='listingtableheader' style='border-left:dotted 1px;border-right:dotted 1px'></td>
            <td width='48%' align='center' class='listingtableheader'>Set the following Data Feed url in your google base account.</td>
            </tr>	
            <tr>
            	<td width='48%' align='center' valign='top' style='padding-top:8px'>
            	<div style='float:left'>&nbsp;Use this section to download the file for googlebase.</div><br><br>	
            	<input type="submit" name="generate_file" value="Generate File" class="red" />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_GOOGLE_BASE_GENFILE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>
              <br><span class='tdcolorgray'>* Please check the prices at the end to make sure they are correct.  If they are not please contact support@thewebclinic.co.uk</span>            	</td>
            	<td align="left" width='1%' style='border-left:dotted 1px;border-right:dotted 1px;padding:5px'><span style='color:#FF0000;font-weight:bold;font-size:24px;'>OR</span></td>
            	<td width='48%' align='center' style='padding-top:8px'>
            		<table width='100%' cellpadding='0' cellspacing='0' border='0'>
           <tr>
            <td align="left" class="tdcolorgray" >The URL depends on the above checkboxes. To get relevant data in the Data Feed Url, pls tick the required boxes before copying the url from this section</td>
            </tr> 
             <tr>
            <td  align="center" class="tdcolorgray" ><div style='width:600px;background-color:#CCCCCC;padding:10px;font-weight:bold;border:solid 1px' name='google_base_input_url' id='google_base_input_url'>http://<?php echo $ecom_hostname;?>/googlebaseExport.php?brn=1</div></td>
            </tr>
             <tr>
            <td align="center" class="tdcolorgray" >
            	For more information regarding setting up of data feed url in your google base account, please click <a href='http://www.google.com/support/merchants/bin/answer.py?hl=en_GB&answer=1219255' target='_blank'>here</a>            </td>
            </tr>	
            		</table>            	</td>
            </tr>	
            </table>            </td>
        </table></td>
        </tr>
    </table>
    <p>&nbsp;</p>
    </td>
</tr>
</table>
</form>
