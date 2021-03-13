<? 
	/*#################################################################
	# Script Name 	: database_offline.php
	# Description 		: Managing database offline
	# Coded by 		: Sny
	# Created on		: 14-Aug-2008
	# Modified by		: Sny
	# Modified On		: 21-Aug-2008
#################################################################*/
	//Define constants for this page
	$page_type 	= 'Manage Databse Offline';
	/*$help_msg 		= 'This feature allows you to download your product / product varaibles database as a CSV file, edit it in an application such as MS Excel, and then re-upload it. <br>The downloaded file will be in CSV format
							<br>When editing the downloaded file, you must not alter the first row of the fiile and also the columns which are indicated as "Don\'t Modify", as this will compromise database integrity and cause the upload to fail.<br/> For certain fields, the values supported by those fields are specified with the column header itself.';*/
	$help_msg = get_help_messages('LIST_DATABASE_OFFLINE_MESS1');
	if($_REQUEST['c']==1)
		$alert = 'Please select the file to upload';
	elseif($_REQUEST['c']==2)
		$alert = 'Please select a CSV file to upload';
		/*if($ecom_siteid==83 )// and $_SESSION['console_id']!=8)
		{
			echo "<center><br><br><span class='redtext'><strong>You are not authorised to view this page.</strong></span></center>";
			exit;
		}*/
?>
<script type="text/javascript">
function handle_operation_submit(mod)
{
	document.getElementById('cur_mod').value = '';
	switch(mod)
	{
		case 'download':
			var atleast_one = false;
			if(document.getElementById('chk_selallprods').checked==false) /* Case if all products is not ticked, then atleast one category should be selected*/
			{
				catobj = document.getElementById('sel_category_id[]');
				for(i=0;i<catobj.length;i++)
				{
					if(catobj[i].selected)
						atleast_one = true;
				}
			}
			else
				atleast_one = true;
			if (atleast_one==false)
			{
				alert('Please select atleast one category');
				return false;
			}
			else
			{
				document.frm_offlinedownload.cur_mod.value = 'offline_download';
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
			document.frm_offlineupload.cur_mod.value = 'offline_upload';
			document.frm_offlineupload.submit();
		break;
	};
}
function handle_selcattr(obj)
{
	if(obj.checked)
	{
		document.getElementById('selcat_tr1').style.display = 'none';
		document.getElementById('selcat_tr2').style.display = 'none';
	}	
	else
	{
		document.getElementById('selcat_tr1').style.display = '';
		document.getElementById('selcat_tr2').style.display = '';
	}	
}
function handle_long_desc(obj)
{
        if(obj.checked)
            document.getElementById('longdesc_tr').style.display = '';
        else
            document.getElementById('longdesc_tr').style.display = 'none';
}
function handle_selmain(obj)
{
     if (obj.value=='prod')
     {	
	 	if(document.getElementById('download_long_desc_include'))
	 	{
        	if(document.getElementById('download_long_desc_include').checked==true)
			{
				if(document.getElementById('longdesc_tr'))
		        	document.getElementById('longdesc_tr').style.display = '';
			}
			else
			{
				if(document.getElementById('longdesc_tr'))
		        	document.getElementById('longdesc_tr').style.display = 'none';
			}
		}
		else
		{
			if(document.getElementById('longdesc_tr'))
		        	document.getElementById('longdesc_tr').style.display = '';
		}
		
        if(document.getElementById('longdescsel_tr'))
            document.getElementById('longdescsel_tr').style.display = '';
            
     }
     else
     {
        if(document.getElementById('longdesc_tr'))
            document.getElementById('longdesc_tr').style.display = 'none';
        if(document.getElementById('longdescsel_tr'))
            document.getElementById('longdescsel_tr').style.display = 'none';
     }
}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
	<td align="left" class="treemenutd"><div class="treemenutd_div"><span><?php echo $page_type?></span></div></td>
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
	<div class="editarea_div">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="49%" height="316" align="left" valign="top">
		<form action="do_database_offline.php" method="post" name="frm_offlinedownload"  enctype="multipart/form-data">
		<input type="hidden" name="cur_mod" id="cur_mod" value="" />
		<div class="productdet_mainoutercls">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="4" align="left" class="seperationtd" ><strong>Download</strong></td>
          </tr>
          <tr>
            <td colspan="4" align="left" class="tdcolorgray" >This section allows to download the product/product variables details in Excel (.xls) format. The data in the excel file can be modified and the changes can be uploaded using the &quot;Upload&quot; section. <br />
              <br />
              <span class='redtext'>Please read the above instructions carefully before starting to edit the xls file.</span> </td>
            </tr>
          <tr>
            <td colspan="4" align="left" class="tdcolorgray" >Select Download Option</td>
            </tr>
          <tr>
            <td colspan="2" align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" valign="top" class="tdcolorgray" ><select name="main_select" id="main_select" onchange="handle_selmain(this)">
              <option value="prod" <?php echo ($_REQUEST['offline_what']=='prod')?'selected="selected"':''?>>Products</option>
              <option value="prod_var" <?php echo ($_REQUEST['offline_what']=='prod_var')?'selected="selected"':''?>>Product Variables & Messages</option>
              <option value="prod_label" <?php echo ($_REQUEST['offline_what']=='prod_label')?'selected="selected"':''?>>Product Labels</option>
              <optgroup label="Fixed / Direct"></optgroup>
              <option value="prod_fixedstock" <?php echo ($_REQUEST['offline_what']=='prod_fixedstock')?'selected="selected"':''?>>Product with Fixed Stock Only</option>
              <option value="prod_fixedprice" <?php echo ($_REQUEST['offline_what']=='prod_fixedprice')?'selected="selected"':''?>>Product with Fixed Price Only</option>
              <option value="prod_normalimage" <?php echo ($_REQUEST['offline_what']=='prod_normalimage')?'selected="selected"':''?>>Product with Direct Images Only</option>
              <option value="prod_fixedall" <?php echo ($_REQUEST['offline_what']=='prod_fixedall')?'selected="selected"':''?>>Product with Fixed Stock, Fixed Price & Direct Images Only</option>
              <optgroup label="Combination"></optgroup>
              <option value="prod_combstock" <?php echo ($_REQUEST['offline_what']=='prod_combstock')?'selected="selected"':''?>>Product with Combination Stock</option>
              <option value="prod_combprice" <?php echo ($_REQUEST['offline_what']=='prod_combprice')?'selected="selected"':''?>>Product with Combination Price</option>
              <option value="prod_combimage" <?php echo ($_REQUEST['offline_what']=='prod_combimage')?'selected="selected"':''?>>Product with Combination Images</option>
			  <option value="prod_comball" <?php echo ($_REQUEST['offline_what']=='prod_comball')?'selected="selected"':''?>>Product with Combination Stock, Price & Images Only</option>
              <?php /*?><option value="prod_varstock" <?php echo ($_REQUEST['offline_what']=='prod_stock')?'selected="selected"':''?>>Product With Variable Stock</option><?php */?>
            </select>
              &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_DATABASE_OFFLINE_MESS_DOWNLOAD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
          <tr>
            <td colspan="4" align="left" class="tdcolorgray" >All products </td>
            </tr>
          <tr>
            <td colspan="2" align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" valign="top" class="tdcolorgray" ><input name="chk_selallprods" id="chk_selallprods" type="checkbox" value="1" checked="checked" onclick="handle_selcattr(this)" />
              &nbsp;<a href="#" onmouseover ="ddrivetip(' <?=get_help_messages('LIST_DATABASE_OFFLINE_MESS_PRODUCTS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
          <tr id="selcat_tr1" style="display:none">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="3" align="left" valign="top" class="tdcolorgray">Select Categories from where the products are to be downloaded <span class="redtext">* </span></td>
            </tr>
          <tr id="selcat_tr2" style="display:none">
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="3" align="left" class="tdcolorgray" >
			<?php
							$cat_arr = generate_category_tree(0,0,false,true,false);
							echo generateselectbox('sel_category_id[]',$cat_arr,'-1','','',8);
			?>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_DATABASE_OFFLINE_MESS_CATEGORIES')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</td>
          </tr>
          <tr id='longdescsel_tr'>
            <td width="1%" align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="3" align="left" class="tdcolorgray" ><?php /*Include product long description in download file*/ ?>
              <?php /*<input type="checkbox" name="download_long_desc_include" id="download_long_desc_include" value="1" onclick="handle_long_desc(this)"/>*/ ?></td>
            </tr>
          <tr id="longdesc_tr" style="display:none">
            <td width="1%" align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" colspan="3"><span class='redtext'><strong>Note:</strong> Please cross check the downloaded file manually to see whether any breaking happened due to html errors in long description before starting to make changes in the file. <br><br>In case if you find any breaking in long description, try to exclude the long description from the export file by unticking the option <strong>"Include product long description in download file."</strong></span><br><br></td>
          </tr>
          <tr>
            <td width="1%" align="left" class="tdcolorgray" >&nbsp;</td>
            <td width="11%" align="left" class="tdcolorgray" >&nbsp;</td>
            <td width="36%" align="right" class="tdcolorgray" ><input type="button" name="database_download" value="Download" class="red"  onclick="handle_operation_submit('download')"/></td>
            <td width="52%" align="right" class="tdcolorgray" >&nbsp;</td>
          </tr>
        </table>	
		</div>
		</form>		</td>
        <td width="2%" align="left" valign="top">&nbsp;</td>
        <td width="49%" align="left" valign="top">
		<form action="do_database_offline.php" method="post" name="frm_offlineupload"  enctype="multipart/form-data">
		<input type="hidden" name="cur_mod" id="cur_mod" value="" />
		<div class="productdet_mainoutercls">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="4" align="left" class="seperationtd" >Upload</td>
          </tr>
          <tr>
            <td colspan="4" align="left" class="tdcolorgray" >This section allows to upload the generated CSV file. The CSV file being uploaded should be the one created from the excel file exported using the &quot;Download&quot; section. Upload will take some time depending on the amount of data. <br />
              <br />
              <span class='redtext'>Please read the above instructions on how to save the xls file in csv format.</span> </td>
          </tr>
          <tr>
            <td width="1%" align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="3" align="left" class="tdcolorgray" >Select Upload Option </td>
            </tr>
          <tr>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td width="16%" align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="tdcolorgray" ><select name="select_upload" id="select2" onchange="call_ajax_showlistall('main_select','')">
              <option value="prod" <?php echo ($_REQUEST['offline_what']=='prod')?'selected="selected"':''?>>Products</option>
              <option value="prod_var" <?php echo ($_REQUEST['offline_what']=='prod_var')?'selected="selected"':''?>>Product Variables &amp; Messages</option>
              <option value="prod_label" <?php echo ($_REQUEST['offline_what']=='prod_label')?'selected="selected"':''?>>Product Labels</option>
              <optgroup label="Fixed / Direct"></optgroup>
              <option value="prod_fixedstock" <?php echo ($_REQUEST['offline_what']=='prod_fixedstock')?'selected="selected"':''?>>Product with Fixed Stock Only</option>
              <option value="prod_fixedprice" <?php echo ($_REQUEST['offline_what']=='prod_fixedprice')?'selected="selected"':''?>>Product with Fixed Price Only</option>
              <option value="prod_normalimage" <?php echo ($_REQUEST['offline_what']=='prod_normalimage')?'selected="selected"':''?>>Product with Direct Images Only</option>
              <option value="prod_fixedall" <?php echo ($_REQUEST['offline_what']=='prod_fixedall')?'selected="selected"':''?>>Product with Fixed Stock, Fixed Price & Direct Images Only</option>
              <optgroup label="Combination"></optgroup>
              <option value="prod_combstock" <?php echo ($_REQUEST['offline_what']=='prod_combstock')?'selected="selected"':''?>>Product with Combination Stock</option>
              <option value="prod_combprice" <?php echo ($_REQUEST['offline_what']=='prod_combprice')?'selected="selected"':''?>>Product with Combination Price</option>
              <option value="prod_combimage" <?php echo ($_REQUEST['offline_what']=='prod_combimage')?'selected="selected"':''?>>Product with Combination Images</option>
              <option value="prod_comball" <?php echo ($_REQUEST['offline_what']=='prod_comball')?'selected="selected"':''?>>Product with Combination Stock, Price & Images Only</option>
              <?php /*?><option value="prod_varstock" <?php echo ($_REQUEST['offline_what']=='prod_varstock')?'selected="selected"':''?>>Product Variable Stock</option><?php */?>
            </select>
&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_DATABASE_OFFLINE_MESS_UPLOAD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
          <tr>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="3" align="left" class="tdcolorgray" >Select Upload File </td>
            </tr>
          <tr>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="2" align="left" class="tdcolorgray" ><input name="upload_file" type="file" id="upload_file" /></td>
          </tr>
          <tr>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="3" align="left" class="tdcolorgray" >&nbsp;</td>
            </tr>
          
          <tr>
            <td align="left" class="tdcolorgray" >&nbsp;</td>
            <td colspan="3" align="center" class="tdcolorgray" ><input type="button" name="database_upload" id="database_upload" class="red" value="Upload" onclick="handle_operation_submit('upload')" /></td>
            </tr>
        </table>
		</div>
		</form>		</td>
      </tr>
    </table> 
	</div>
	   </td>
</tr>
</table>
