<?php
	/*#################################################################
	# Script Name 	: add_adverts.php
	# Description 	: Page for adding Adverts
	# Coded by 		: ANU
	# Created on	: 26-July-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################*/
#Define constants for this page
$page_type = 'Adverts';
$help_msg = get_help_messages('ADD_ADVERT_MESS1');

?>	
<script language="javascript" type="text/javascript">
function activeperiod(check,bid){
 if(document.frmAddadverts.advert_activateperiodchange.checked == true){
		document.getElementById(bid).style.display = '';
		}
		else{
		document.getElementById(bid).style.display = 'none';
		document.frmAddadverts.advert_activateperiodchange.checked = false;
		}
		
		
}
function handletype_change(vals)
{	
	if (vals=='')
		vals = 'IMG';
	switch(vals)
	{
		case 'IMG':
			document.getElementById('tr_img').style.display = '';
			document.getElementById('tr_loc').style.display = 'none';
			document.getElementById('tr_text').style.display = 'none';
			document.getElementById('tr_link').style.display = '';
		break;
		case 'PATH':
			document.getElementById('tr_img').style.display = 'none';
			document.getElementById('tr_loc').style.display = '';
			document.getElementById('tr_text').style.display = 'none';
			document.getElementById('tr_link').style.display = '';
		break;
		case 'TXT':
			document.getElementById('tr_img').style.display = 'none';
			document.getElementById('tr_loc').style.display = 'none';
			document.getElementById('tr_text').style.display = '';
			document.getElementById('tr_link').style.display = 'none';
		break;
	};
}
function valform(frm)
{
	fieldRequired = Array('advert_title','display_id[]' );
	fieldDescription = Array('Advert Title','Display Positions');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	var reqcnt = fieldRequired.length;
	if(document.frmAddadverts.cbo_type.value=='IMG')
	{
			fieldRequired[reqcnt] 	 = 'file_advert';
			fieldDescription[reqcnt] = 'Image';
			reqcnt++;
	}	
	if(document.frmAddadverts.cbo_type.value=='PATH')
	{
			fieldRequired[reqcnt] 	 = 'txt_imgloc';
			fieldDescription[reqcnt] = 'Path for Image';
			reqcnt++;
	}	
	
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
	
			/* Check whether dispay location is selected*/
			obj = document.getElementById('display_id[]');
			if(obj.options.length==0)
			{
				alert('Display location is required');
				return false;
			}
			else
			{
				for(i=0;i<obj.options.length;i++)
				{
					if(obj.options[i].selected)
					{
						atleastone = true;
					}
				}
				if (atleastone==false)
				{
					alert('Please select the display location');
					return false;
				}
			}
			/* end CHECKING OF SELECTING POSITION*/
		
		if(frm.advert_activateperiodchange.checked  ==true){
			val_dates = compareDates(frm.advert_displaystartdate,"Start Date\n Correct Format:dd-mm-yyyy ",frm.advert_displayenddate,"End Date\n Correct Format:dd-mm-yyyy");
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
	} else {
		return false;
	}
}
</script>
<form name='frmAddadverts' action='home.php?request=adverts' enctype="multipart/form-data"  method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><a href="home.php?request=adverts&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Adverts </a> &gt;&gt; Add Adverts </td>
        </tr>
        <tr>
          <td colspan="4" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
        </tr>
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
        <tr>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Advert Title  <span class="redtext">*</span> </td>
          <td width="29%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="advert_title"  />		  </td>
          <td width="14%" align="left" valign="middle" class="tdcolorgray">Show in all pages</td>
          <td width="29%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="advert_showinall"  value="1" />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_ADVERT_SHOWINALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		 <tr  >
		   <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		   <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		   <td align="left" valign="middle" class="tdcolorgray">Hide Advert </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="advert_hide" value="1" checked="checked" />
		     Yes
		       <input type="radio" name="advert_hide" value="0" checked="checked" />
	       No&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_ADVERT_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
		 <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Display Location <span class="redtext">*</span> </td>
		   <td align="left" valign="middle" class="tdcolorgray"><?php
			// Get the list of position allovable for category groups for the current theme
			$sql_themes = "SELECT advert_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$pos_arr	= explode(",",$row_themes['advert_positions']);
			}
			
			$disp_array	= array();
			// Get the layouts fot the current theme
			$sql_layouts = "SELECT layout_id,layout_code,layout_name,layout_positions FROM themes_layouts WHERE 
							themes_theme_id=$ecom_themeid";
			$ret_layouts = $db->query($sql_layouts);
			if ($db->num_rows($ret_layouts))
			{
				while ($row_layouts = $db->fetch_array($ret_layouts))
				{
					for($i=0;$i<count($pos_arr);$i++)
					{
						$curid 				= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".$pos_arr[$i];
						$curname			= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
						$disp_array[$curid] = $curname;
					}	
				}
			}
			echo generateselectbox('display_id[]',$disp_array,$_REQUEST['display_id'],'','',5);
		  ?>
               <!--  <select name="group_position[]" multiple="multiple">
		 
		  <?
		
	   	  ?>

		  </select>-->   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_ADVERT_DISPLOC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		   </td>
		   <td colspan="2" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
    </tr>
		 <tr  >
		   <td align="left" valign="middle" class="tdcolorgray" >Advert Type  </td>
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray"><?php
					  	$type_arr = array('IMG'=>'Image Upload','PATH'=>'Image URL','TXT'=>'Text/HTML');
						echo generateselectbox('cbo_type',$type_arr,$row['advert_type'],'','handletype_change(this.value)');
					  ?>&nbsp;
					  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_ADVERT_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
		 <tr id="tr_img">
		   <td align="left" valign="middle" class="tdcolorgray" >Select Image <span class="redtext">*</span></td>
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray"><input name="file_advert" type="file" id="file_advert" />
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_ADVERT_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
		 <tr id="tr_loc">
		   <td align="left" valign="middle" class="tdcolorgray" >Specify Image Location <span class="redtext">*</span></td>
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray"><input name="txt_imgloc" type="text" id="txt_imgloc" size="50" value="<?php if($row['advert_type']=='PATH') echo $_REQUEST['txt_imgloc'];?>" /></td>
    </tr>
		 <tr id="tr_text">
		   <td align="left" valign="middle" class="tdcolorgray" >Specify Advert Text/HTML </td>
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray">
		   <?php
						include_once("classes/fckeditor.php");
						$editor 			= new FCKeditor('txt_text') ;
						$editor->BasePath 	= '/console/js/FCKeditor/';
						$editor->Width 		= '650';
						$editor->Height 	= '300';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value 		= ($_REQUEST['advert_type']=='TXT')?trim($_REQUEST['txt_text']):'';
						$editor->Create() ;
				       
		?>
		   </td>
    </tr>
		 <tr id="tr_link">
		   <td align="left" valign="middle" class="tdcolorgray" >Link for advert </td>
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray"><input name="txt_link" type="text" id="txt_link" size="50" value ="<?php $_REQUEST['txt_link']?>" />
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_ADVERT_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
	<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" ><table width="100%" cellpadding="0" cellspacing="0">
		   <tr>
		       <td align="left" valign="middle" class="tdcolorgray" colspan="2"><b>Active Period</b>
			   &nbsp;</td>
		   </tr>
		   <? $id=10;
		   if($row['advert_activateperiodchange']==1)
		   			 {
					  $display='';
					  $exp_advert_displaystartdate=explode("-",$row['advert_displaystartdate']);
					  $val_advert_displaystartdate=$exp_advert_displaystartdate[2]."-".$exp_advert_displaystartdate[1]."-".$exp_advert_displaystartdate[0];
					  $exp_advert_displayenddate=explode("-",$row['advert_displayenddate']);
					  $val_advert_displayenddate=$exp_advert_displayenddate[2]."-".$exp_advert_displayenddate[1]."-".$exp_advert_displayenddate[0];
		
					}
					else
					{ 
					 //echo "none";
					  $display='none';
					}
		   ?>
		    <tr>
		    <td width="28%" align="right" valign="middle" class="tdcolorgray" lign="left">
			    Change Active Period			</td>
			 <td lign="left" valign="middle" class="tdcolorgray" width="72%">
			    <input type="checkbox" name="advert_activateperiodchange"  onclick="activeperiod(this.checked,<? echo $id?>)" value="1" />		&nbsp;
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_ADVERT_PERIOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>	</td>
		   </tr>
		   <tr id="<? echo $id;?>" style="display:<?= $display; ?>">
		   <td colspan="3" class="tdcolorgray" >
		   <table width="100%" cellpadding="0" cellspacing="0"> 
		   <tr >
		    <td align="right" valign="middle" class="tdcolorgray">
			    Start Date			</td>
			<td  align="right" valign="middle"  width="8%">
		  <?
		  		  ?>
		  <input class="input" type="text" name="advert_displaystartdate" size="8" value="<? echo $val_advert_displaystartdate ?>"  />		  </td>
			<td align="left" valign="middle"  >
		  <a href="javascript:show_calendar('frmAddadverts.advert_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		  </tr>
		   <tr >
		    <td align="right" valign="middle" class="tdcolorgray" width="20%">
			    End Date			</td>
			<td  align="right" valign="middle"  width="8%">
		  <?
		  		  ?>
		  <input class="input" type="text" name="advert_displayenddate" size="8" value="<? echo $val_advert_displayenddate ?>"  />		  </td>
			<td align="left" valign="middle"   >
		  <a href="javascript:show_calendar('frmAddadverts.advert_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		  </tr>
		   </table>		   </td>
		   </tr>
		   
		   </table>		   </td>
		   </tr>
		 <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
		 <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td colspan="3" align="left" valign="middle" class="tdcolorgray">
		  
		  <input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
		   <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
		  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
		  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
		  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
		  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
		  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
		  <input name="Submit" type="submit" class="red" value="Submit" /></td>
        </tr>
  </table>
</form>	  
<script type="text/javascript">
	handletype_change('');
</script>
