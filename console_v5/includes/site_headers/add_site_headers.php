<?php
	/*#################################################################
	# Script Name 	: add_site_headers.php
	# Description 	: Page for adding Sitea header Images
	# Coded by 		: ANU
	# Created on	: 2-Aug-2007
	# Modified by	: Sny
	# Modified On	: 26-Nov-2007
	#################################################################*/
//#Define constants for this page

$page_type 	= 'Site Headers';
$help_msg 	= get_help_messages('ADD_SITE_HEADERS_MESS1');

?>	
<script language="javascript" type="text/javascript">
function change_show_date_period()
{
	
	if(document.frmAddSiteHeaders.header_period_change_required.checked==true)
	{
		document.getElementById('show_date_period').style.display = '';
	}
	else
	{
		document.getElementById('show_date_period').style.display = 'none';
	}
}
function valform(frm)
{
	
	var req_text ;
	if(document.getElementById('header_filename').value=='' && document.getElementById('header_caption').value=='')
	{
	fieldRequired = Array('header_title','header_filename');
	fieldDescription = Array('Header Title','Header Image file Or Header Caption');
	}
	else
	{
	fieldRequired = Array('header_title');
	fieldDescription = Array('Header Title');
	}
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		// validation for date format
		//alert(document.frmAddSiteHeaders.header_startdate.value);
		if(frm.header_period_change_required.checked  ==true){
			val_dates = compareDates(frm.header_startdate,"Start Date\n Correct Format:dd-mm-yyyy ",frm.header_enddate,"End Date\n Correct Format:dd-mm-yyyy");
			if(val_dates ){
				  show_processing();
				  return true;
			 }else{
			 return false
			 }
		}else{
		show_processing();
		return true;
		}
		  
	}
	 else {
		return false;
	}
}

</script>
<form name='frmAddSiteHeaders' action='home.php?request=site_headers' enctype="multipart/form-data"  method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="5" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=site_headers&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Site Headers </a><span>Add Site header</span></div> </td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="5">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>		 </td>
		</tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="5" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
          <td colspan="5" align="center" valign="middle" class="tdcolorgray" >
		<div class="editarea_div">
		<table width="100%">
        <tr>
          <td width="18%" align="left" valign="middle" class="tdcolorgray" >Header Title  <span class="redtext">*</span> </td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="header_title" value="<?=$_REQUEST['header_title']?>"  />		  </td>
          <td width="15%" align="left" valign="middle" class="tdcolorgray">Select Image <span class="redtext">*</span></td>
          <td width="44%" align="left" valign="middle" class="tdcolorgray"><input name="header_filename" type="file" id="header_filename" />
            <input name="chk_resizeheader" type="checkbox" id="chk_resizeheader" value="1" checked="checked" />
Resize Image </td>
        </tr>
		 <tr  >
		   <td align="left" valign="middle" class="tdcolorgray" >Show in all pages </td>
		   <td width="3%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" id="header_showinall" name="header_showinall" onclick="change_show_date_period()" value="1" <? if($_REQUEST['header_showinall']==1) echo "checked"?> />
	       <br /></td>
		   <td width="20%" align="left" valign="middle" class="tdcolorgray"><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SITE_HEADERS_SHOWALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><a href="javascript:show_calendar('frmAddSiteHeaders.header_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"></a></td>
		   <td align="left" valign="middle" class="tdcolorgray">Hidden </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="header_hide" value="1" checked="checked" />
Yes
  <input type="radio" name="header_hide" value="0" />
No&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SITE_HEADERS_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><a href="javascript:show_calendar('frmAddSiteHeaders.header_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"></a></td>
    </tr>
	<tr>
	<td align="left" valign="top" class="tdcolorgray"  >Header Caption</td>
	   <td align="left" valign="middle" class="tdcolorgray" colspan="5" >
	   <textarea name="header_caption" id="header_caption" rows="3" cols="40"></textarea>
	   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SITE_HEADERS_CAPTION')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	</tr>
	<? $id=10;
		   if($_REQUEST['header_period_change_required']==1)
		   			 {
					  $display='';
					}
					else
					{ 
					 //echo "none";
					  $display='none';
					}
					for($i=0;$i<60;$i++)
					$option .= '<option value="'.$i.'">'.$i.'</option>';
					for($i=0;$i<=23;$i++)
					$houroption .= '<option value="'.$i.'">'.$i.'</option>';	
		   ?>
	
		 <tr  >
		   <td align="left" valign="middle" class="tdcolorgray" >Periodic Change Required</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" id="header_period_change_required" name="header_period_change_required" onclick="change_show_date_period()" value="1" <? if($_REQUEST['header_period_change_required']==1) echo "checked"?> /></td>
		   <td align="left" valign="middle" class="tdcolorgray"><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SITE_HEADERS_HEADER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><a href="javascript:show_calendar('frmAddSiteHeaders.header_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"></a></td>
		   <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		   <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
    </tr>
		 <tr>
           <td colspan="5"  align="left" valign="middle" class="tdcolorgray"  id="show_date_period" ><table width="100%" cellpadding="0" cellspacing="2" border="0">
             <tr>
               <td align="left" valign="middle"  >&nbsp;</td>
               <td align="left" valign="middle"  >&nbsp;</td>
               <td align="left" valign="middle" >&nbsp;</td>
               <td width="4%"  align="left" valign="middle" >&nbsp;</td>
               <td width="7%"  align="left" valign="middle" >Hrs</td>
               <td width="7%"  align="left" valign="middle" >Min</td>
               <td width="7%"  align="left" valign="middle" >Sec</td>
               <td width="35%"  align="left" valign="middle" >&nbsp;</td>
             </tr>
             <tr>
               <td width="21%" align="left" valign="middle"  >&nbsp;</td>
               <td width="11%" align="left" valign="middle"  >Start Date<span class="redtext">*</span></td>
               <td width="8%" align="left" valign="middle" >
               <input class="input" type="text" name="header_startdate" size="8" value="<?=$_REQUEST['header_startdate']?>">               </td>
               <td  align="left" valign="middle" ><a href="javascript:show_calendar('frmAddSiteHeaders.header_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>&nbsp;</td>
               <td  align="left" valign="middle" ><select name="header_starttime_hr" id="header_starttime_hr">
										<option value="<?php echo $_REQUEST['header_starttime_hr']?>"><?php echo $_REQUEST['header_starttime_hr']?></option>
										<?php echo $houroption?>
										</select></td>
               <td  align="left" valign="middle" ><select name="header_starttime_mn" id="header_starttime_mn">
										<option value="<?php echo $_REQUEST['header_starttime_mn']?>"><?php echo $_REQUEST['header_starttime_mn']?></option>
										<?php echo $option?>
										</select></td>
               <td  align="left" valign="middle" ><select name="header_starttime_ss" id="header_starttime_ss">
										<option value="<?php echo $_REQUEST['header_starttime_ss']?>"><?php echo $_REQUEST['header_starttime_ss']?></option>
										<?php echo $option?>
										</select></td>
               <td  align="left" valign="middle" >&nbsp;</td>
             </tr>
             <tr>
               <td width="21%" align="left" valign="middle"  >&nbsp;</td>
               <td width="11%" align="left" valign="middle"  >End Date<span class="redtext">*</span></td>
               <td width="8%" align="left" valign="middle" ><input class="input" type="text" name="header_enddate" size="8" value="<?=$_REQUEST['header_enddate']?>" />               </td>
               <td  align="left" valign="middle" ><a href="javascript:show_calendar('frmAddSiteHeaders.header_enddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a> </td>
               <td  align="left" valign="middle" ><select name="header_endtime_hr" id="header_endtime_hr">
										<option value="<?php echo $_REQUEST['header_endtime_hr']?>"><?php echo $_REQUEST['header_endtime_hr']?></option>
										<?php echo $houroption?>
										</select></td>
               <td  align="left" valign="middle" ><select name="header_endtime_mn" id="header_endtime_mn">
										<option value="<?php echo $_REQUEST['header_endtime_mn']?>"><?php echo $_REQUEST['header_endtime_mn']?></option>
										<?php echo $option?>
										</select></td>
               <td  align="left" valign="middle" ><select name="header_endtime_ss" id="header_endtime_ss">
										<option value="<?php echo $_REQUEST['header_endtime_ss']?>"><?php echo $_REQUEST['header_endtime_ss']?></option>
										<?php echo $option?>
										</select></td>
               <td  align="left" valign="middle" >&nbsp;</td>
             </tr>
           </table></td>
    </tr>
	</table>
	</div>
	</td>
	</tr>
		<tr>
          <td colspan="5" align="center" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
		  <table width="100%">
		  <tr>
		  	<td align="right" valign="middle">
			  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
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
<script type="text/javascript">
	change_show_date_period();
</script>
