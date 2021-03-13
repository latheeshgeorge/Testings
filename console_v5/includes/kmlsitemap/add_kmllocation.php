<?php
/*#################################################################
# Script Name 		: add_kmllocation.php
# Description 		: Page for adding kml locations
# Coded by 			: Sny
# Created on		: 05-Oct-2009
#################################################################*/
#Define constants for this page
$page_type = 'KML Location';
$help_msg = get_help_messages('ADD_KML_MAIN_MSG');
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('kml_location_name','kml_company_name','kml_street','kml_city','kml_state','kml_zip','kml_phone','kml_latitude','kml_longitude','kml_description');
	fieldDescription = Array('Location Name','Company Name','Street','City','State','Zip','Phone','Latitude','Longitude','Description');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('kml_latitude','kml_longitude');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
function ajax_return_contents() 
{
	var ret_val='';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val = req.responseText;
			if(ret_val!='' && ret_val.indexOf('notfound')==-1)
			{
				ret_arr = ret_val.split(',');
				document.getElementById('kml_latitude').value = ret_arr[0];
				document.getElementById('kml_longitude').value = ret_arr[1];
			}
			else
			{
					document.getElementById('kml_latitude').value = '';
					document.getElementById('kml_longitude').value = '';
			}
			if(ret_val.indexOf('notfound')!=-1)
				document.getElementById('coordinates_wait').innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sorry! couldnt find the co ordinates';
			else
				document.getElementById('coordinates_wait').innerHTML='';
		}
		else
		{
			 show_request_alert(req.status);
			//alert("Problem in requesting XML :"+req.statusText);
		}
	}
}
function  call_ajax_getcoordinates()
{
	var address = document.getElementById('kml_company_name').value+','+document.getElementById('kml_street').value+','+document.getElementById('kml_city').value+','+document.getElementById('kml_state').value+','+document.getElementById('kml_zip').value;
	if(address=='')
	{
		alert('Please specify the address');
	}
	else
	{
		document.getElementById('coordinates_wait').innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please wait ...';
		Handlewith_Ajax('services/kmlsitemap.php','fpurpose=getcoordinates&address='+address);
	}
}
</script>
<form name='frmhelp' action='home.php?request=kmlsitemap' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=kmlsitemap&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_location_name=<?=$_REQUEST['search_location_name']?>&search_company_name=<?=$_REQUEST['search_company_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List KML Sitemap Location</a><span> Add KML Sitemap Location</span></div></td>
        </tr>
        <tr>
		  <td colspan="2" align="left" valign="middle" class="helpmsgtd_main">
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
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
		<td colspan="2" valign="top" class="tdcolorgray" >
		<div class="listingarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="15%" align="left" valign="top" class="tdcolorgray" >Location Name <span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input name="kml_location_name" type="text" class="input" id="kml_location_name" value="<?=$_REQUEST['kml_location_name']?>" size="50"  />		  </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="tdcolorgray" >Company Name <span class="redtext">*</span> </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="kml_company_name" type="text" class="input" id="kml_company_name" value="<?=$_REQUEST['kml_company_name']?>" size="50">          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="tdcolorgray" >Street <span class="redtext">*</span> </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="kml_street" type="text" class="input" id="kml_street" value="<?=$_REQUEST['kml_street']?>" size="50" />          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="tdcolorgray" >City <span class="redtext">*</span> </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="kml_city" type="text" class="input" id="kml_city" value="<?=$_REQUEST['kml_city']?>" size="50" />          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="tdcolorgray" >State <span class="redtext">*</span> </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="kml_state" type="text" class="input" id="kml_state" value="<?=$_REQUEST['kml_state']?>" size="50" />          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="tdcolorgray" >Zip <span class="redtext">*</span> </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="kml_zip" type="text" class="input" id="kml_zip" value="<?=$_REQUEST['kml_zip']?>" size="50" />          </td>
        </tr>
        <tr>
          <td align="left" valign="top" class="tdcolorgray" >Phone <span class="redtext">*</span> </td>
          <td align="left" valign="middle" class="tdcolorgray"><input name="kml_phone" type="text" class="input" id="kml_phone" value="<?=$_REQUEST['kml_phone']?>" size="50" />
          </td>
        </tr>
		
		 <tr>
		   <td align="left" valign="top" class="tdcolorgray" >Co Ordinates <span class="redtext">*</span></td>
		   <td align="left" valign="middle" class="tdcolorgray">Latitude 
	        <input name="kml_latitude" type="text" class="input" id="kml_latitude" value="<?=$_REQUEST['kml_latitude']?>" size="20" /> 
	        Longitude 
	        <input name="kml_longitude" type="text" class="input" id="kml_longitude" value="<?=$_REQUEST['kml_longitude']?>" size="20" />&nbsp;&nbsp;<?php /*?><a href='javascript:call_ajax_getcoordinates()' class="edittextlink">Get coordiates using zip code</a> <div id="coordinates_wait" style="display:inline" class="fontredheading"></div><?php */?>
			 <a href='javascript:call_ajax_getcoordinates()' class="edittextlink">Get coordiates </a> <div id="coordinates_wait" style="display:inline" class="fontredheading"></div>
			</td>
	      </tr>
		 <tr>
		   <td align="left" valign="top" class="tdcolorgray" >Description <span class="redtext">*</span> </td>
		   <td align="left" valign="middle" class="tdcolorgray">
		   <textarea name="kml_description" rows="5" cols="70" ><?=stripslashes($_REQUEST['kml_description'])?></textarea>		   </td>
	      </tr>
		 <tr>
          <td width="15%" align="left" valign="top" class="tdcolorgray" > Order  <span class="redtext">*</span></td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input name="kml_order" type="text" class="input" id="kml_order" value="<?=$_REQUEST['kml_order']?>" size="3"  /> 
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_KML_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td align="left" valign="top" class="tdcolorgray" >Hide</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="kml_hide" value="1" <? if($_REQUEST['kml_hide']==1){ echo "checked";} ?>  />Yes<input type="radio" name="kml_hide" value="0" <? if($_REQUEST['kml_hide']==0){ echo "checked";} ?>  />No
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_KML_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		</table>
		</div>
		</td>		
		</tr>
		<tr>
		 	<td colspan="2" align="center" valign="middle" class="tdcolorgray" width="100%">
		 		<div class="listingarea_div">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="100%" align="right" valign="middle">
							<input type="hidden" name="search_location_name" id="search_location_name" value="<?=$_REQUEST['search_location_name']?>" />
							<input type="hidden" name="search_location_name" id="search_company_name" value="<?=$_REQUEST['search_company_name']?>" />
							<input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
							<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
							<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
							<input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
							<input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
							<input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
							<input name="Submit" type="submit" class="red" value="Save" />
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
      </table>
</form>	  

