<?php
	/*#################################################################
	# Script Name 	: add_shelf.php
	# Description 	: Page for adding Shelf
	# Coded by 		: SKR
	# Created on	: 18-July-2007
	# Modified by	: SKR
	# Modified On	: 31-July-2007
	# Modified by	: LG
	# Modified On	: 30-Jan 2008
	#################################################################*/
#Define constants for this page
$page_type = 'Shelves';
//$help_msg = 'This section helps in adding the Shelves';
$help_msg = get_help_messages('ADD_PROD_SHELF_MESS1');
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('shelf_name','display_id[]','shelf_currentstyle');
	fieldDescription = Array('Shelf Name','Shelf Position','Listing Style');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array('shelf_order');
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		if(frm.shelf_activateperiodchange.checked  ==true) {
			val_dates = compareDates(frm.shelf_displaystartdate,"Start Date\n Correct Format:dd-mm-yyyy ",frm.shelf_displayenddate,"End Date\n Correct Format:dd-mm-yyyy");
			if(val_dates ){
				  show_processing();
				  return true;
			 }else{
			 return false
			 }
		}
		if(frm.shelf_showimage.checked==false && frm.shelf_showtitle.checked==false && frm.shelf_showdescription.checked==false && frm.shelf_showprice.checked==false && frm.shelf_showrating.checked==false && frm.shelf_showbonuspoints.checked==false) 
		{
			    alert('Please Check any of Fields Items to Display in Shelf ');	   
				return false;    
		}
		else{
		show_processing();    		

		return true;
		}
	} else {
		return false;
	}
}
function change_show_date_period()
{
	
	if(document.frmAddShelf.shelf_activateperiodchange.checked==true)
	{
		document.getElementById('show_date_period').style.display = '';
	}
	else
	{
		document.getElementById('show_date_period').style.display = 'none';
	}
}
function handle_showclick(mod) 
{
	if (mod=='showinall')
	{
		if (document.frmAddShelf.shelf_showinall.checked)
			document.frmAddShelf.shelf_showinhome.checked = false;
	}
	else
	{
		if (document.frmAddShelf.shelf_showinhome.checked)
			document.frmAddShelf.shelf_showinall.checked = false;
	}		
}
</script>
<form name='frmAddShelf' action='home.php?request=shelfs' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=shelfs&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Shelves</a><span>Add Shelf</span></div></td>
        </tr>
        <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="2">
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
          <td colspan="2" align="center" valign="middle" class="tdcolorgray" >
		  <div class="editarea_div">
		  <table width="100%">
		  
		<tr>
		<td width="49%" valign="top" class="tdcolorgray" >
		<table width="99%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="32%" align="left" valign="middle" class="tdcolorgray" >Shelf Name <span class="redtext">*</span> </td>
          <td width="68%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="shelf_name" value="<?=$_REQUEST['shelf_name']?>"  />
		  </td>
        </tr>
		 <tr>
          <td width="32%" align="left" valign="middle" class="tdcolorgray" >Shelf Position <span class="redtext">*</span></td>
          <td width="68%" align="left" valign="middle" class="tdcolorgray">
		  <?php
			// Get the list of position allovable for category groups for the current theme
			$sql_themes = "SELECT shelf_positions FROM themes WHERE theme_id=$ecom_themeid";
			$ret_themes = $db->query($sql_themes);
			if ($db->num_rows($ret_themes))
			{
				$row_themes = $db->fetch_array($ret_themes);
				$shelfpos_arr	= explode(",",$row_themes['shelf_positions']);
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
							if(in_array($pos_arr[$i],$shelfpos_arr))
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
			$sql_mobthemes = "SELECT shelf_positions FROM themes WHERE theme_id=$ecom_mobilethemeid";
			$ret_mobthemes = $db->query($sql_mobthemes);
			if ($db->num_rows($ret_mobthemes))
			{
				$mobrow_themes = $db->fetch_array($ret_mobthemes);
				$mobcatpos_arr	= explode(",",$mobrow_themes['shelf_positions']);
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

			//echo generateselectbox('display_id[]',$disp_array,$_REQUEST['display_id'],'','',5);
		  ?>		 
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHELVES_DISPLOC')?>.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
        </tr>
		<!-- <tr>
          <td width="32%" align="left" valign="middle" class="tdcolorgray" >Shelf Order <span class="redtext">*</span></td>
          <td width="68%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="shelf_order" size="3"  value="<?=(!$_REQUEST['shelf_order'])?0:$_REQUEST['shelf_order']?>" />
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHELVES_ORDER')?>.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
        </tr>-->
		 <tr>
          <td width="32%" align="left" valign="middle" class="tdcolorgray" >Display Type </td>
          <td width="68%" align="left" valign="middle" class="tdcolorgray">
		  <? $sql_style="SELECT shelf_displaytypes FROM themes WHERE theme_id=".$ecom_themeid;
		 $ret_style = $db->query($sql_style);
		 $row_style=$db->fetch_array($ret_style);
		 $arr_style=explode(',',$row_style['shelf_displaytypes']); 
		 ?>
		 <select name="shelf_displaytype">
		  <?
		 foreach($arr_style as $v)
		 {
		 	$val_arr = explode("=>",$v);
		 ?>
		 <option value="<?=$val_arr[0]?>" <? if($_REQUEST['shelf_displaytype']==$val_arr[0]) echo "selected";?>><?=$val_arr[1]?></option>
		 <?
		 }
		 ?>
		  </select>
		  
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHELVES_DISPTYPE')?>.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td width="32%" align="left" valign="middle" class="tdcolorgray" >Listing Style<span class="redtext">*</span></td>
          <td width="68%" align="left" valign="middle" class="tdcolorgray">
		  <select name="shelf_currentstyle">
		  <option value="">--select--</option>
		 <?
		 $sql_style="SELECT shelf_listingstyles FROM themes WHERE theme_id=".$ecom_themeid;
		 $ret_style = $db->query($sql_style);
		 $row_style=$db->fetch_array($ret_style);
		 $arr_style=explode(',',$row_style['shelf_listingstyles']);
		 foreach($arr_style as $v)
		 {
		 	$val_arr = explode("=>",$v);
		 ?>
		 <option value="<?=$val_arr[0]?>" <? if($_REQUEST['shelf_currentstyle']==$val_arr[0]) echo "selected";?> ><?=$val_arr[1]?></option>
		 <?
	
		 }
		 ?>
		  </select>
		  
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHELVES_LISTSTYLE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >Hide</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="shelf_hide" value="1"  <? if($_REQUEST['shelf_hide']==1) echo "checked";?> />Yes<input type="radio" name="shelf_hide" value="0" <? if($_REQUEST['shelf_hide']==0) echo "checked";?>  />No
		  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHELVES_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		</table>
		</td>
		<td width="51%" valign="top" class="tdcolorgray">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" width="40%" >Show in all &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHELVES_SHOW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="shelf_showinall" value="1" <? if($_REQUEST['shelf_showinall']==1) echo "checked";?> />		  </td>
        </tr>
		
		   <tr>
			<td colspan="2" align="left"><b>Fields to be displayed in shelf</b>
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHELVES_FIELD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   </tr>
		  
		 <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Show Image </td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="shelf_showimage" value="1"  <? if($_REQUEST['shelf_showimage'] == 1) echo "checked";?> />		  </td>
        </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >Show Title </td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="shelf_showtitle" value="1" <? if($_REQUEST['shelf_showtitle']==1) echo "checked";?>/>		  </td>
        </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >Show Description </td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="shelf_showdescription" value="1" <? if($_REQUEST['shelf_showdescription']==1) echo "checked";?> />		  </td>
        </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >Show Price </td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" name="shelf_showprice" value="1" <? if($_REQUEST['shelf_showprice']==1) echo "checked";?>  />		  </td>
        </tr>
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" >Show Rating </td>
		  <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="shelf_showrating" value="1" <? if($_REQUEST['shelf_showrating']==1) echo "checked";?>>          </td>
		  </tr>
		  <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Show Bonus Points </td>
		  <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="shelf_showbonuspoints" value="1" <? if($_REQUEST['shelf_showbonuspoints']==1) echo "checked";?>>          </td>
		  </tr>
		
		<? 
				
		    if($_REQUEST['shelf_activateperiodchange']==1)
		   			 {
					  $display='';
					  $exp_shelf_displaystartdate=explode("-",$_REQUEST['shelf_displaystartdate']);
					  $val_shelf_displaystartdate=$exp_shelf_displaystartdate[2]."-".$exp_shelf_displaystartdate[1]."-".$exp_shelf_displaystartdate[0];
					  $exp_shelf_displayenddate=explode("-",$_REQUEST['shelf_displayenddate']);
					  $val_shelf_displayenddate=$exp_shelf_displayenddate[2]."-".$exp_shelf_displayenddate[1]."-".$exp_shelf_displayenddate[0];
		
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
			<td width="100%" colspan="2" align="left"><b>Active Period</b>
			&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_SHELVES_PERIOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
		   <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Change Active Period </td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="checkbox" id="shelf_activateperiodchange" name="shelf_activateperiodchange" onclick="change_show_date_period()" value="1" <? if($_REQUEST['shelf_activateperiodchange']==1) echo "checked";?>  />		  </td>
        </tr>
		
		<tr id="show_date_period" style="display:<?=$display?>">
		 <td colspan="2" width="100%" align="left" valign="middle" class="tdcolorgray" >
		 <table width="100%" cellpadding="0" cellspacing="2" border="0">
		 <tr>
		   <td align="left" valign="middle"  >&nbsp;</td>
		   <td align="left" valign="middle" >&nbsp;</td>
		   <td align="left" valign="middle" >&nbsp;</td>
		   <td align="left" valign="middle" >Hrs</td>
		   <td align="left" valign="middle" >Min</td>
		   <td align="left" valign="middle" >Sec</td>
		   <td align="left" valign="middle" >&nbsp;</td>
		 </tr>
		 <tr>
		 <td width="18%" align="left" valign="middle"  >Start Date</td>
          <td width="13%" align="left" valign="middle" >
		  <input class="input" type="text" name="shelf_displaystartdate" size="8" value="<?=$_REQUEST['shelf_displaystartdate']?>"  />		  </td>
		  <td width="9%" align="left" valign="middle" >
		  <a href="javascript:show_calendar('frmAddShelf.shelf_displaystartdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		  <td width="10%" align="left" valign="middle" ><select name="shelf_starttime_hr" id="shelf_starttime_hr">
										<option value="<?php echo $_REQUEST['shelf_starttime_hr']?>"><?php echo $_REQUEST['shelf_starttime_hr']?></option>
										<?php echo $houroption?>
				  </select></td>
		  <td width="10%" align="left" valign="middle" ><select name="shelf_starttime_mn" id="shelf_starttime_mn">
										<option value="<?php echo $_REQUEST['shelf_starttime_mn']?>"><?php echo $_REQUEST['shelf_starttime_mn']?></option>
										<?php echo $option?>
										</select></td>
		  <td width="9%" align="left" valign="middle" ><select name="shelf_starttime_ss" id="shelf_starttime_ss">
										<option value="<?php echo $_REQUEST['shelf_starttime_ss']?>"><?php echo $_REQUEST['shelf_starttime_ss']?></option>
										<?php echo $option?>
										</select></td>
		  <td width="31%" align="left" valign="middle" >&nbsp;</td>
		 </tr>
		 <tr>
		 <td width="18%" align="left" valign="middle"  >End Date</td>
          <td width="13%" align="left" valign="middle" >
		  <input class="input" type="text" name="shelf_displayenddate" size="8" value="<?=$_REQUEST['shelf_displayenddate']?>"  />		  </td>
		  <td align="left" valign="middle" >
		  <a href="javascript:show_calendar('frmAddShelf.shelf_displayenddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a>		  </td>
		  <td align="left" valign="middle" ><select name="shelf_endtime_hr" id="shelf_endtime_hr">
										<option value="<?php echo $_REQUEST['shelf_endtime_hr']?>"><?php echo $_REQUEST['shelf_endtime_hr']?></option>
										<?php echo $houroption?>
										</select></td>
		  <td align="left" valign="middle" ><select name="shelf_endtime_mn" id="shelf_endtime_mn">
										<option value="<?php echo $_REQUEST['shelf_endtime_mn']?>"><?php echo $_REQUEST['shelf_endtime_mn']?></option>
										<?php echo $option?>
										</select></td>
		  <td align="left" valign="middle" ><select name="shelf_endtime_ss" id="shelf_endtime_ss">
										<option value="<?php echo $_REQUEST['shelf_endtime_ss']?>"><?php echo $_REQUEST['shelf_endtime_ss']?></option>
										<?php echo $option?>
										</select></td>
		  <td align="left" valign="middle" >&nbsp;</td>
		 </tr>
		 </table>		  </td>
		</tr>
		</table>
		</td>
		</tr>
		<tr>
          <td colspan="2" align="left" valign="top" class="tdcolorgray" >Description &nbsp;<a href="#" onmouseover ="ddrivetip('This description will be displayed only while viewing the shelf in middle area.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
          <tr>
          <td colspan="2" align="left" valign="top" class="tdcolorgray" >
			<?php
				//include_once("classes/fckeditor.php");
				$editor_elements = "shelf_description";
				include_once(ORG_DOCROOT."/console/js/tinymce.php");
				/*$editor 			= new FCKeditor('shelf_description') ;
				$editor->BasePath 	= '/console/js/FCKeditor/';
				$editor->Width 		= '550';
				$editor->Height 	= '300';
				$editor->ToolbarSet = 'BshopWithImages';
				$editor->Value 		= stripslashes($_REQUEST['shelf_description']);
				$editor->Create() ;*/
			?> 
			<textarea style="height:300px; width:500px" id="shelf_description" name="shelf_description"><?=stripslashes($_REQUEST['shelf_description'])?></textarea>         
		  </td>
        </tr>
		</table>
		</div>
		<tr>
         <td colspan="2" align="right" valign="middle" class="tdcolorgray">
		 <div class="editarea_div">
		 <table width="100%">
		 <tr>
		 	<td width="100%" align="right" valign="middle">		  
			  <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
			   <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
			  <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
			  <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
			  <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
			  <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
			  <input type="hidden" name="fpurpose" id="fpurpose" value="insert" />
			  <input name="Submit" type="submit" class="red" value="Submit" />
			</td>
		</tr>
		</table>
		</div>
		</td>
        </tr>
      </table>
</form>	  

