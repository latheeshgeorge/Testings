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
$page_type = 'Banners';
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
/*function handletype_change(vals)
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
			document.getElementById('tr_target').style.display = '';
			if (document.getElementById('resizeimg_div'))
				document.getElementById('resizeimg_div').style.display = '';
		break;
		case 'PATH':
			document.getElementById('tr_img').style.display = 'none';
			document.getElementById('tr_loc').style.display = '';
			document.getElementById('tr_text').style.display = 'none';
			document.getElementById('tr_link').style.display = '';
			document.getElementById('tr_target').style.display = '';
			if (document.getElementById('resizeimg_div'))
				document.getElementById('resizeimg_div').style.display = 'none';
		break;
		case 'TXT':
			document.getElementById('tr_img').style.display = 'none';
			document.getElementById('tr_loc').style.display = 'none';
			document.getElementById('tr_text').style.display = '';
			document.getElementById('tr_link').style.display = 'none';
			document.getElementById('tr_target').style.display = 'none';
			if (document.getElementById('resizeimg_div'))
				document.getElementById('resizeimg_div').style.display = 'none';
		break;
		case 'SWF':
			document.getElementById('tr_img').style.display = '';
			document.getElementById('tr_loc').style.display = 'none';
			document.getElementById('tr_text').style.display = 'none';
			document.getElementById('tr_link').style.display = 'none';
			document.getElementById('tr_target').style.display = 'none';
			if (document.getElementById('resizeimg_div'))
				document.getElementById('resizeimg_div').style.display = 'none';
		break;
	};
} */
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
                        if(document.getElementById('tr_rotate'))
                            document.getElementById('tr_rotate').style.display = 'none';
                        document.getElementById('tr_link').style.display = '';
                        document.getElementById('tr_target').style.display = '';
                        if (document.getElementById('resizeimg_div'))
                                document.getElementById('resizeimg_div').style.display = '';
                break;
                case 'PATH':
                        document.getElementById('tr_img').style.display = 'none';
                        document.getElementById('tr_loc').style.display = '';
                        document.getElementById('tr_text').style.display = 'none';
                         if(document.getElementById('tr_rotate'))
                            document.getElementById('tr_rotate').style.display = 'none';
                        document.getElementById('tr_link').style.display = '';
                        document.getElementById('tr_target').style.display = '';
                        if (document.getElementById('resizeimg_div'))
                                document.getElementById('resizeimg_div').style.display = 'none';
                break;
                case 'TXT':
                        document.getElementById('tr_img').style.display = 'none';
                        document.getElementById('tr_loc').style.display = 'none';
                        document.getElementById('tr_text').style.display = '';
                        document.getElementById('tr_link').style.display = 'none';
                        document.getElementById('tr_target').style.display = 'none';
                        if(document.getElementById('tr_rotate'))
                            document.getElementById('tr_rotate').style.display = 'none';
                        if (document.getElementById('resizeimg_div'))
                                document.getElementById('resizeimg_div').style.display = 'none';
                break;
                case 'SWF':
                        document.getElementById('tr_img').style.display = '';
                        document.getElementById('tr_loc').style.display = 'none';
                        document.getElementById('tr_text').style.display = 'none';
                        document.getElementById('tr_link').style.display = 'none';
                        document.getElementById('tr_target').style.display = 'none';
                        if(document.getElementById('tr_rotate'))
                            document.getElementById('tr_rotate').style.display = 'none';
                        if (document.getElementById('resizeimg_div'))
                                document.getElementById('resizeimg_div').style.display = 'none';
                break;
                case 'ROTATE':
                     document.getElementById('tr_img').style.display = 'none';
                        document.getElementById('tr_loc').style.display = 'none';
                        document.getElementById('tr_text').style.display = 'none';
                        document.getElementById('tr_link').style.display = 'none';
                        document.getElementById('tr_target').style.display = 'none';
                        if (document.getElementById('resizeimg_div'))
                                document.getElementById('resizeimg_div').style.display = 'none';
                        if(document.getElementById('tr_rotate'))
                            document.getElementById('tr_rotate').style.display = '';
                break;
        };
}
function handle_showclick(mod)
{ 
	if (mod=='advert_showinall')
	{
		if (document.frmAddAdverts.advert_showinall.checked)
			document.frmAddAdverts.advert_showinhome.checked = false;
	}
	else
	{
		if (document.frmAddAdverts.advert_showinhome.checked)
			document.frmAddAdverts.advert_showinall.checked = false;
	}		
}


function valform(frm)
{
	fieldRequired = Array('advert_title','display_id[]' );
	fieldDescription = Array('Banner Title','Display Positions');
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
	if(document.frmAddadverts.cbo_type.value=='ROTATE')
	{
			fieldRequired[reqcnt] 	 = 'rotate_height';
			fieldDescription[reqcnt] = 'Rotator Height';
			reqcnt++;
			fieldRequired[reqcnt] 	 = 'rotate_speed';
			fieldDescription[reqcnt] = 'Rotate Speed';
			reqcnt++;
			fieldNumeric[0] 			= 'rotate_height';
			fieldNumeric[1] 			= 'rotate_speed';
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
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=adverts&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Banners </a><span> Add Banners</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
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
		<?
		}
		?>
		 <tr>
          <td colspan="4" align="center" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
		   <table  border="0" cellpadding="0" cellspacing="0"width="100%">
			<tr>
			  <td width="19%" align="left" valign="middle" class="tdcolorgray" >Banner Title  <span class="redtext">*</span> </td>
			  <td width="38%" align="left" valign="middle" class="tdcolorgray">
			  <input class="input" type="text" name="advert_title" value="<?=$_REQUEST['advert_title']?>"  />		  </td>
			  <td width="13%" align="left" valign="middle" class="tdcolorgray">Show in all pages</td>
			  <td width="30%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="advert_showinall"  value="1" <? if($_REQUEST['advert_showinall']==1) echo "checked" ?>   />
			  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_ADVERT_SHOWINALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
			<?php /*?> <tr  >
			   <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
			   <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
			   <td align="left" valign="middle" class="tdcolorgray">Show in home page </td>
			   <td align="left" valign="middle" class="tdcolorgray"><input  type="checkbox" name="advert_showinhome" value="1" <? if($_REQUEST['advert_showinhome']==1) echo "checked"?>   onclick="handle_showclick('showinhome')"   />	</td>
		</tr><?php */?>
			 <tr>
			   <td align="left" valign="middle" class="tdcolorgray" >Display Location <span class="redtext">*</span> </td>
			   <td align="left" valign="middle" class="tdcolorgray"><?php
				// Get the list of position allovable for category groups for the current theme
				$sql_themes = "SELECT advert_positions FROM themes WHERE theme_id=$ecom_themeid";
				$ret_themes = $db->query($sql_themes);
				if ($db->num_rows($ret_themes))
				{
					$row_themes = $db->fetch_array($ret_themes);
					$advpos_arr	= explode(",",$row_themes['advert_positions']);
				}
				
				$disp_array	= array();
				// Get the layouts fot the current theme
				$sql_layouts = "SELECT layout_id,layout_code,layout_name,layout_positions FROM themes_layouts WHERE 
								themes_theme_id=$ecom_themeid ORDER BY layout_name";
				$ret_layouts = $db->query($sql_layouts);
				if ($db->num_rows($ret_layouts))
				{
					while ($row_layouts = $db->fetch_array($ret_layouts))
					{
						$pos_arr = explode(',',$row_layouts['layout_positions']);
						if(count($pos_arr))
						{
							for($i=0;$i<count($pos_arr);$i++)
							{
								if(in_array($pos_arr[$i],$advpos_arr))
								{
									$curid 				= $row_layouts['layout_id']."_".stripslashes($row_layouts['layout_code'])."_".$pos_arr[$i];
									$curname			= stripslashes($row_layouts['layout_name'])." (".$pos_arr[$i].")";
									$disp_array[$curid] = $curname;
								}	
							}	
						}	
					}
				}
			if($ecom_mobilethemeid>0)
			{
			// Get the list of position allovable for category groups for the current theme
			$sql_mobthemes = "SELECT advert_positions FROM themes WHERE theme_id=$ecom_mobilethemeid";
			$ret_mobthemes = $db->query($sql_mobthemes);
			if ($db->num_rows($ret_mobthemes))
			{
				$mobrow_themes = $db->fetch_array($ret_mobthemes);
				$mobcatpos_arr	= explode(",",$mobrow_themes['advert_positions']);
			}
			// Get the layouts fot the current mobiletheme
			 $mobsql_layouts = "SELECT layout_id,layout_code,layout_name,layout_positions FROM themes_layouts WHERE 
							themes_theme_id=$ecom_mobilethemeid ORDER BY layout_name";
			$mobret_layouts = $db->query($mobsql_layouts);
			if ($db->num_rows($mobret_layouts))
			{
				while ($mobrow_layouts = $db->fetch_array($mobret_layouts))
				{
					$mobpos_arr = explode(',',$mobrow_layouts['layout_positions']);
					if(count($mobpos_arr))
					{
						for($i=0;$i<count($mobpos_arr);$i++)
						{
							if(in_array($mobpos_arr[$i],$mobcatpos_arr))
							{
								$curid 				= $mobrow_layouts['layout_id']."_".stripslashes($mobrow_layouts['layout_code'])."_".stripslashes($mobpos_arr[$i]);
								
										$curname			= stripslashes($mobrow_layouts['layout_name'])." (".$mobpos_arr[$i].")";
										$mobdisp_array[$curid] = $curname;
									
								
							}	
						}
					}		
				}
			}
		}
		echo generateselectboxoption('display_id[]',$disp_array,$_REQUEST['display_id'],$mobdisp_array,$_REQUEST['display_id'],'','',5);

			  ?>
				   <!--  <select name="group_position[]" multiple="multiple">
				  </select>-->   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_ADVERT_DISPLOC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		   </td>
				   <td align="left" valign="middle" class="tdcolorgray">Hide Banner </td>
				   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="advert_hide" value="1"  <? if($_REQUEST['advert_hide']==1) echo "checked";?> />
					 Yes
					 <input type="radio" name="advert_hide" value="0" <? if($_REQUEST['advert_hide']==0) echo "checked";?> />
					 No&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_ADVERT_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
			 <tr  >
			   <td align="left" valign="middle" class="tdcolorgray" >Banner Type  </td>
			   <td colspan="3" align="left" valign="middle" class="tdcolorgray">
					   <?php
					   //type_arr = array('IMG'=>'Image Upload','PATH'=>'Image URL','TXT'=>'Text/HTML','SWF'=>'Flash');
						// Get the display format type for adverts from themes table
						$sql_adv = "SELECT advert_support_types 
										FROM 
											themes
										WHERE 
											theme_id =$ecom_themeid 
										LIMIT 
												1";
							$ret_adv = $db->query($sql_adv);
							if($db->num_rows($ret_adv))
							{
								$row_adv = $db->fetch_array($ret_adv);
								$tempr  = explode(',',$row_adv['advert_support_types']);
								foreach ($tempr as $k=>$v)
								{
									$temp_now = explode('=>',$v);
									$type_arr[$temp_now[0]] = $temp_now[1];
								}
							}
							
							echo generateselectbox('cbo_type',$type_arr,$row['advert_type'],'','handletype_change(this.value)');
				  ?>
						  &nbsp;
							  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_ADVERT_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
			 <tr id="tr_img">
			   <td align="left" valign="middle" class="tdcolorgray" >Select File <span class="redtext">*</span></td>
			   <td align="left" valign="middle" class="tdcolorgray"><input name="file_advert" type="file" id="file_advert" />
			   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_ADVERT_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> &nbsp;&nbsp;&nbsp;</td>
			   <td colspan="2" align="left" valign="middle" class="tdcolorgray"><div id="resizeimg_div"><input name="chk_advertresize" type="checkbox" id="chk_advertresize" value="1" checked="checked" />
			   Resize Image</div> </td>
		</tr>
			 <tr id="tr_loc">
			   <td align="left" valign="middle" class="tdcolorgray" >Specify Image Location <span class="redtext">*</span></td>
			   <td  align="left" valign="middle" class="tdcolorgray" colspan="3"><input name="txt_imgloc" type="text" id="txt_imgloc" size="50" value="<?php if($row['advert_type']=='PATH') echo $_REQUEST['txt_imgloc'];?>" />&nbsp;
			   (e.g. Address = "http://www.bshop4.co.uk/console/images/logo.gif") </td>
		</tr>
			 <tr id="tr_text">
			   <td align="left" valign="top" class="tdcolorgray" >Specify Banner Text/HTML </td>
			   <td colspan="3" align="left" valign="middle" class="tdcolorgray">
		   <?php
						/*include_once("classes/fckeditor.php");
						$editor 			= new FCKeditor('txt_text') ;
						$editor->BasePath 	= '/console/js/FCKeditor/';
						$editor->Width 		= '650';
						$editor->Height 	= '300';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value 		= ($_REQUEST['advert_type']=='TXT')?trim($_REQUEST['txt_text']):'';
						$editor->Create() ;*/
						$editor_elements = "txt_text";
						include_once("js/tinymce.php");
				       
		?>		   <textarea style="height:300px; width:650px" id="txt_text" name="txt_text"><?php echo ($_REQUEST['advert_type']=='TXT')?trim($_REQUEST['txt_text']):'';?></textarea>
		</td>
    </tr>
		 <tr id="tr_link">
		   <td align="left" valign="middle" class="tdcolorgray" >Link for Banner </td>
		   <td colspan="3" align="left" valign="middle" class="tdcolorgray"><input name="txt_link" type="text" id="txt_link" size="50" value ="<?php echo  $_REQUEST['txt_link']?>" />
		   &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_ADVERT_LINK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
		   &nbsp;(e.g. Address = "http://www.bshop4.co.uk)</td>
    </tr>
	<tr id="tr_target">
		   <td align="left" valign="middle" class="tdcolorgray" >Banner Link Open in </td>
	   <td colspan="3" align="left" valign="middle" class="tdcolorgray">
	   <?php $advert_target_arr = array('_blank' => 'New Window','_self' => 'Same Window');
	  echo generateselectbox('advert_target',$advert_target_arr,$_REQUEST['advert_target']);
	   ?>&nbsp;
	     <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_ADVERT_LINK_TARGET')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
     <tr id="tr_rotate">
        <td colspan="1" align="right" valign="middle" class="tdcolorgray">&nbsp;
        
        </td>
        <td colspan="3" align='left' class='tdcolorgray'>
        <table width="100%" cellpadding="1" cellspacing="1" border="0" class="listingtablestyleB">
		 <tr>
            <td colspan="3" align="left" class="seperationtd_special">Rotate Settings</td>
        </tr>
		<tr>
			<td align="left" width='15%'>Rotate Section Height:</td>
			<td align="left" colspan="2"><input type='text' name='rotate_height' value='100' size="10"/> (pixels)</td>
		</tr>
		<tr>
			<td align="left" width='15%'>Rotate Speed:</td>
			<td align="left" colspan="2"><input type='text' name='rotate_speed' value='5' size="10"/> (seconds)</td>
		</tr>
        <tr>
            <td colspan="3" align="left" class="seperationtd_special">Rotator Image Management Section</td>
        </tr>
        <?php
            $cnt=1;
            for ($i=1;$i<=5;$i++)
            {
            ?>
                <tr>
                    <td colspan="3" align="left"><strong># <?php echo $cnt++?></strong></td>
                </tr>
                <tr>
                    <td align="left" width='15%'>Image:</td>
                    <td align="left" width='10%'><input type='file' name='rotate_img_<?php echo $i?>' value=''/></td>
                    <td align="left"><input type='checkbox' name='rotate_resize_<?php echo $i?>' value='1' checked="checked"/> Resize Image</td>
                </tr> 
                    <tr>
                    <td align="left">Link for Image: (optional)</td>
                    <td align="left" colspan="2"><input type='text' name='rotate_link_<?php echo $i?>' value='' size="50"/></td>
                </tr>
                    <tr>
                      <td align="left">Alternative Text: (optional) </td>
                      <td colspan="2" align="left"><input type='text' name='rotate_alttext_<?php echo $i?>' value='' size="50"/></td>
                    </tr>
                <tr>
                    <td align="left">Sort Order:</td>
                    <td align="left" colspan="2"><input type='text' name='rotate_order_<?php echo $i?>' value='' size="5"/></td>
                </tr>
                <tr>
                    <td colspan='3'>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
                </tr>
            <?php
            }
        ?>
        </table>
        </td>
    </tr>
	<tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" ><table width="100%" cellpadding="0" cellspacing="0">
		   <tr>
		       <td align="left" valign="middle" class="tdcolorgray" colspan="2"><b>Active Period</b>
			   &nbsp;</td>
		   </tr>
		   <? $id=10;
		   if($_REQUEST['advert_activateperiodchange']==1)
		   			 {
					  $display='';
					  $exp_advert_displaystartdate=explode("-",$_REQUEST['advert_displaystartdate']);
					  $val_advert_displaystartdate=$exp_advert_displaystartdate[2]."-".$exp_advert_displaystartdate[1]."-".$exp_advert_displaystartdate[0];
					  $exp_advert_displayenddate=explode("-",$_REQUEST['advert_displayenddate']);
					  $val_advert_displayenddate=$exp_advert_displayenddate[2]."-".$exp_advert_displayenddate[1]."-".$exp_advert_displayenddate[0];
		
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
		    <tr>
		    <td width="28%" align="right" valign="middle" class="tdcolorgray" lign="left">
			    Change Active Period			</td>
			 <td lign="left" valign="middle" class="tdcolorgray" width="72%">
			    <input type="checkbox" name="advert_activateperiodchange"  onclick="activeperiod(this.checked,<? echo $id?>)" value="1" <? if($_REQUEST['advert_activateperiodchange']==1) echo "checked"; ?> />		&nbsp;
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_ADVERT_PERIOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>	</td>
		   </tr>
		   <tr id="<? echo $id;?>" style="display:<?= $display; ?>">
		   <td colspan="3" class="tdcolorgray" >
		   <table width="100%" cellpadding="0" cellspacing="0"> 
		   <tr >
		     <td align="right" valign="middle" class="tdcolorgray">&nbsp;</td>
		     <td  align="right" valign="middle">&nbsp;</td>
		     <td width="4%" align="left" valign="middle"  >&nbsp;</td>
		     <td width="7%" align="left" valign="middle"  >Hrs</td>
		     <td width="6%" align="left" valign="middle"  >Min</td>
		     <td width="7%" align="left" valign="middle"  >Sec</td>
		     <td width="36%" align="left" valign="middle"  >&nbsp;</td>
		     <td width="12%" align="left" valign="middle"  >&nbsp;</td>
		   </tr>
		   <tr >
		    <td align="right" valign="middle" class="tdcolorgray">
			    Start Date			</td>
			<td  align="right" valign="middle"  width="8%">
		  <?
		  		  ?>
		  <input class="input" type="text" name="advert_displaystartdate" size="8" value="<? echo $_REQUEST['advert_displaystartdate'] ?>"  />		  </td>
			<td align="left" valign="middle"  >
		  <a href="javascript:show_calendar('frmAddadverts.advert_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		    <td align="left" valign="middle"  ><select name="advert_starttime_hr" id="advert_starttime_hr">
										<option value="<?php echo $_REQUEST['active_start_hr']?>"><?php echo $_REQUEST['active_start_hr']?></option>
										<?php echo $houroption?>
										</select></td>
		    <td align="left" valign="middle"  ><select name="advert_starttime_mn" id="advert_starttime_mn">
										<option value="<?php echo $_REQUEST['active_start_mn']?>"><?php echo $_REQUEST['active_start_mn']?></option>
										<?php echo $option?>
										</select></td>
		    <td align="left" valign="middle"  ><select name="advert_starttime_ss" id="advert_starttime_ss">
										<option value="<?php echo $_REQUEST['active_start_ss']?>"><?php echo $_REQUEST['active_start_ss']?></option>
										<?php echo $option?>
										</select></td>
		    <td align="left" valign="middle"  >&nbsp;</td>
		    <td align="left" valign="middle"  >&nbsp;</td>
		   </tr>
		   <tr >
		    <td align="right" valign="middle" class="tdcolorgray" width="20%">
			    End Date			</td>
			<td  align="right" valign="middle"  width="8%">
		  <?
		  		  ?>
		  <input class="input" type="text" name="advert_displayenddate" size="8" value="<? echo $_REQUEST['advert_displayenddate'] ?>"  />		  </td>
			<td align="left" valign="middle"   >
		  <a href="javascript:show_calendar('frmAddadverts.advert_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		    <td align="left" valign="middle"   ><select name="advert_endtime_hr" id="advert_endtime_hr">
										<option value="<?php echo $_REQUEST['active_end_hr']?>"><?php echo $_REQUEST['active_end_hr']?></option>
										<?php echo $houroption?>
										</select></td>
		    <td align="left" valign="middle"   ><select name="advert_endtime_mn" id="advert_endtime_mn">
										<option value="<?php echo $_REQUEST['active_end_mn']?>"><?php echo $_REQUEST['active_end_mn']?></option>
										<?php echo $option?>
										</select></td>
		    <td align="left" valign="middle"   ><select name="advert_endtime_ss" id="advert_endtime_ss">
            <option value="<?php echo $_REQUEST['active_end_ss']?>"><?php echo $_REQUEST['active_end_ss']?></option>
            <?php echo $option?>
          </select></td>
		    <td align="left" valign="middle"   >&nbsp;</td>
		    <td align="left" valign="middle"   >&nbsp;</td>
		   </tr>
		   </table>		   </td>
		   </tr>
		   
		   </table>		   </td>
		   </tr>
		  </table>
		  </div>
		  </td>
		  </tr>
		 <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
		  <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
			<tr>
				<td width="100%" align="right" valign="middle">
				<input type="hidden" name="pass_search_name" id="pass_search_name" value="<?=$_REQUEST['pass_search_name']?>" />
			   <input type="hidden" name="pass_start" id="pass_start" value="<?=$_REQUEST['pass_start']?>" />
			  <input type="hidden" name="pass_sort_by" id="pass_sort_by" value="<?=$_REQUEST['pass_sort_by']?>" />
			  <input type="hidden" name="pass_sort_order" id="pass_sort_order" value="<?=$_REQUEST['pass_sort_order']?>" />
			  <input type="hidden" name="pass_records_per_page" id="pass_records_per_page" value="<?=$_REQUEST['pass_records_per_page']?>" />
			  <input type="hidden" name="pass_pg" id="pass_pg" value="<?=$_REQUEST['pass_pg']?>" />
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
	handletype_change('');
</script>
