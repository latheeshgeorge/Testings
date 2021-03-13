<? 
	/*#################################################################
	# Script Name 		: advanced_offline.php
	# Description 		: Managing advanced offline
	# Coded by 			: Joby
	# Created on		: 21-Sept-2011
#################################################################*/
	//Define constants for this page
	$page_type 	= 'Manage Advanced Offline';
	/*$help_msg 		= 'This feature allows you to download your product / product varaibles database as a CSV file, edit it in an application such as MS Excel, and then re-upload it. <br>The downloaded file will be in CSV format
							<br>When editing the downloaded file, you must not alter the first row of the fiile and also the columns which are indicated as "Don\'t Modify", as this will compromise database integrity and cause the upload to fail.<br/> For certain fields, the values supported by those fields are specified with the column header itself.';*/
	//$help_msg = get_help_messages('LIST_ADVANCED_OFFLINE_MESS1');
	$help_msg ='This feature can be used to update the following information for multiple products which are mapped to the selected categories.<br>
&bull; Barcode<br>
&bull; Price<br>
&bull; Bulk Discount<br>
&bull; Stock<br>
&bull; Images<br>
<br>
Please read the following points carefully before proceeding. <br>
<br>
1. If the data in any of the columns in the exported Excel (.xls) file have numbers with more than 15 digits, then you will have to apply some formating to those columns in the file. This is because Excel will automatically convert numbers with more than 15 digits to the exponential format (eg. 1.21  E+21) by default. To avoid this, you will have to change the format of such columns to &quot;Text&quot; before starting to edit the values in the exported (xls) file. To change the format of a column in the excel file, you have to click on the header (A, B, C etc) of respective column. This will automatically select the entire column. Right click on the selection and choose &quot;Format Cells&quot; option. This will show a window with certain settings. Select the option &quot;Text&quot; from the values listed under the option &quot;Category:&quot; in the window and click on &quot;Ok&quot; button to apply the change. Please save the file in excel format itself. Once this is done the numbers with more than 15 digits (if any) in such columns will not be converted to exponential format. Usually this format change will have to be applied to the &quot;Barcode&quot; column, which is expected to have numbers with more than 15 digits. This step should be repeated for all columns for which you need to specify number with more than 15 digits. While continuing with editing please use the &quot;Save&quot; option to save the applied changes in excel format itself. <br />
  <br />
  2. Once all the editing is done, you need to save the excel (xls) file in csv format. To do this, you have to go to the menu File -&gt; Save as in Excel. A dialogue box will be displayed with file name and option to select the file type. Choose the option &quot;CSV (Comma delimited)&quot; from the options listed in the drop down of the option &quot;Save as type:&quot; and click on the Save button to save the file in csv format. The csv file will have the extension &quot;.csv&quot;. Please make sure that you do not open the saved csv file in excel again, because this action may again convert the numbers with more than 15 digits to exponential format. That is, in case if you want to make any changes to the data after creating a csv file, please make the change in the excel (xls) file and again create the csv file instead of trying to open the csv file in excel for editing. <br />
  <br />
  3. Use the upload option to upload the saved csv file.<br />
  <br />
  4. 
  While editing the downloaded file, you must not alter the first row of the fiile and also the columns which are indicated as "Dont Modify", as this will compromise database integrity and cause the upload to fail.<br />
  <br />
  5. 
  For certain fields, the values supported by those fields are specified with the column header of the excel file itself.</p>';
	if($_REQUEST['c']==1)
		$alert = 'Please select the file to upload';
	elseif($_REQUEST['c']==2)
		$alert = 'Please select a CSV file to upload';
		if($ecom_siteid==83)// and $_SESSION['console_id']!=8)
		{
			echo "<center><br><br><span class='redtext'><strong>You are not authorised to view this page.</strong></span></center>";
			exit;
		}
?>
<script type="text/javascript">
function handle_operation_submit(mod)
{
	document.getElementById('cur_mod').value = '';
	switch(mod)
	{
		case 'download':
			var atleast_one = false;
			catobj = document.getElementById('sel_category_id[]');/* Case if all products is not ticked, then atleast one category should be selected*/
			for(i=0;i<catobj.length;i++)
			{
				if(catobj[i].selected)
					atleast_one = true;
			}

			if (atleast_one==false)
			{
				alert('Please select atleast one category');
				return false;
			}
			else
			{
				document.frm_offlinedownload.cur_mod.value = 'stock_download';
				document.frm_offlinedownload.submit();
			}
		break;
		case 'upload':
			if(document.frm_offlineupload.upload_file)
			{
				if(document.frm_offlineupload.upload_file.value=='')
				{
					alert('Please select the CSV file to be uploaded');
					return false;
				}	
			}
			document.frm_offlineupload.cur_mod.value = 'stock_upload';
			document.frm_offlineupload.submit();
		break;
	};
}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
	<td align="left" class="treemenutd"><strong><?php echo $page_type?></strong></td>
	</tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main">
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
				<td colspan="2" align="center" valign="middle" class="errormsg" ><?=stripslashes($alert)?></td>
			</tr>
		<?
		}
		?>
	</table>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="49%" height="316" align="left" valign="top">
		<form action="do_advanced_offline_new.php" method="post" name="frm_offlinedownload"  enctype="multipart/form-data">
		<input type="hidden" name="cur_mod" id="cur_mod" value="" />
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="4" align="left" >&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" align="left" class="seperationtd" ><strong>Download</strong></td>
          </tr>
          <tr>
            <td colspan="4" align="left" class="tdcolorgray" >
This section allows you to download the details of products to an Excel (.xls) file.You need to select the categories from the list provided in this section and click on the "Download" button. This will export all the products directly under the selected categories in an Excel file. You can open the  file in Excel and apply the changes to the data.<br>
<br /> <span class='redtext'>Please read the above instructions carefully before starting to edit the xls file.</span><br />
<span style='color:#FF0000;'><br><strong>Note:</strong></span><br>
<span style='line-height:14px'>
<strong>1.</strong> Please make sure that you <strong>DO NOT</strong> change the data in columns whose heading is marked with "(Don't Modify)" in the Excel file. If you change these data, you may not be able to upload the modification or may result in data corruption.<br>
<strong>2.</strong> Also make sure that you <strong>DO NOT</strong> modify the data in columns marked as "N/A" in the Excel file.<br>
<strong>3.</strong> Once the Excel file is downloaded for updation, please <strong>DO NOT</strong> apply any changes in the data directly in the website until the modified CSV is uploaded, because this may create some problems while uploading the generated CSV file.
</span>
            </td>
            </tr>
            <tr>
            <td colspan="4" align="left" class="tdcolorgray" >&nbsp;</td>
            </tr>        
          <tr>
            <td colspan="4" align="left" class="tdcolorgray" >Select the product categories</td>
            </tr>
                   
          <tr id="selcat_tr2">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="3" align="left" class="tdcolorgray" >
			<?php
							$cat_arr = generate_category_tree(0,0,false,true,false);
							echo generateselectbox('sel_category_id[]',$cat_arr,'-1','','',25);
			/*?>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ADVANCED_OFFLINE_MESS_CATEGORIES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><?php */?>			</td>
          </tr>
      
          <tr>
            <td width="1%" align="left" class="tdcolorgray" >&nbsp;</td>
            <td width="11%" align="left" class="tdcolorgray" >&nbsp;</td>
            <td width="36%" align="right" class="tdcolorgray" ><input type="button" name="database_download" value="Download" class="red"  onclick="handle_operation_submit('download')"/></td>
            <td width="52%" align="right" class="tdcolorgray" >&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" align="left" class="tdcolorgray" >&nbsp;</td>
            </tr>
        </table>	
		</form>		</td>
        <td width="2%" align="left" valign="top">&nbsp;</td>
        <td width="49%" align="left" valign="top">
		<form action="do_advanced_offline_new.php" method="post" name="frm_offlineupload"  enctype="multipart/form-data">
		<input type="hidden" name="cur_mod" id="cur_mod" value="" />
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="4" align="left" >&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" align="left" class="seperationtd" >Upload</td>
          </tr>
          <tr>
            <td colspan="4" align="left" class="tdcolorgray" >This section allows to upload the generated CSV file. The CSV file being uploaded should be the one created from the excel file exported using the &quot;Download&quot; section. Upload will take some time depending on the amount of data. <br>
            <br /> <span class='redtext'>Please read the above instructions on how to save the xls file in csv format.</span><br />
			<br><strong><span style='color:#FF0000'>Note:</span></strong><br> 1. Data will be uploaded only if the CSV file is error free </td>
          </tr>
          <tr>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="3" align="left" class="tdcolorgray" >&nbsp;</td>
            </tr>
          <tr>
          <tr>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="3" align="left" class="tdcolorgray" >Select Upload File </td>
            </tr>
          <tr>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="tdcolorgray" ><input name="upload_file" type="file" id="upload_file" /><?php /*&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_ADVANCED_OFFLINE_MESS_UPLOAD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><?php */?></td>
          </tr>
          
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="3" align="center" class="tdcolorgray" >&nbsp;</td>
          </tr>
          <tr>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="3" align="center" class="tdcolorgray" ><input type="button" name="database_upload" id="database_upload" class="red" value="Upload" onclick="handle_operation_submit('upload')" /></td>
            </tr>
          <tr>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="3" align="left" class="tdcolorgray" >&nbsp;</td>
            </tr>
        </table>
		</form>		</td>
      </tr>
    </table>    </td>
</tr>
</table>