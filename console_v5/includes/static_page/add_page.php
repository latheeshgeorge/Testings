<?php
	/*#################################################################
	# Script Name 	: add_page.php
	# Description 	: Page for adding Static Pages
	# Coded by 		: SKR
	# Created on	: 27-June-2007
	# Modified by	: LG
	# Modified On	: 29-jan-2008
	#################################################################*/
#Define constants for this page
$page_type = 'Static Page';
$help_msg = get_help_messages('ADD_STAT_PAGE_MESS1');
?>	
<script language="javascript" type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('title');
	fieldDescription = Array('Page Title');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
function changePageType()
{
	
	if(document.frmAddPage.page_type[0].checked==true)
	{
		document.getElementById('show_content_tr').style.display='';
		document.getElementById('show_link_tr').style.display='none';
	}
	else if(document.frmAddPage.page_type[1].checked==true)
	{
		document.getElementById('show_link_tr').style.display='';
		document.getElementById('show_content_tr').style.display='none';
	}
}
</script>
<form name='frmAddPage' action='home.php?request=stat_page' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="6" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=stat_page&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Static Pages</a><span> Add Page</span></div></td>
        </tr>
        <tr>
		  <td colspan="6" align="left" valign="middle" class="helpmsgtd_main">
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
          <td colspan="6" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>
		<tr>
		    <td width="100%" class="tdcolorgray" colspan="6">
			<div class="editarea_div">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
			 	<tr class="editkeys">
					<td class="tdcolorgray">
						<table cellspacing="6" cellpadding="0" width="100%" border="0">
						  <tr>
							  <td  align="left" valign="middle" class="tdcolorgray" >Page Title <span class="redtext">*</span> </td>
							  <td align="left" valign="middle" class="tdcolorgray">
							  <input class="input" type="text" name="title"  value="<?=$_REQUEST['title']?>" maxlength="100" />					  </td>
						  </tr>
			 <?php /*?> <tr> commented for not to edit the pname. This is be used only for internal purpose
				 <td  align="left" valign="middle" class="tdcolorgray" >Page Name  </td>
				  <td width="63%"  align="left" valign="middle" class="tdcolorgray" >
				  <input class="input" type="text" name="pname"  value="<?=$_REQUEST['pname']?>" />
			    </td>
			  </tr><?php */?>
						 <tr>
								<td width="21%" align="left" valign="middle" class="tdcolorgray" >Hide Page</td>
								<td width="79%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="hide" value="1"  <? if($_REQUEST['hide']==1) echo "checked"?> />&nbsp;Yes&nbsp;<input type="radio" name="hide" value="0" <? if($_REQUEST['hide']==0) echo "checked"?> />&nbsp;No&nbsp;
						   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_STAT_PAGE_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
						 </tr>
					 <?php
						$sql_site = "SELECT in_web_clinic FROM sites WHERE site_id=$ecom_siteid LIMIT 1";
						$ret_site = $db->query($sql_site);
						$row_site = $db->fetch_array($ret_site);
						// Check whether webclinic is active for current website
						if($row_site['in_web_clinic']==1)
						{
					 ?>
							<tr>
							<td align="left" valign="middle" class="tdcolorgray" >Allow Auto linker</td>
							<td align="left" valign="middle" class="tdcolorgray"><input type="checkbox" name="allow_auto_linker" id="allow_auto_linker" value="1" <? if($_REQUEST['allow_auto_linker']==1) echo "checked";?> /></td>
							</tr>
					 <?php
								}
							 ?> 
							 <tr>
								   <td width="21%" align="left" valign="middle" class="tdcolorgray" >Page Type  </td>
								  <td align="left" valign="middle" class="tdcolorgray" colspan="3">
								  <input type="radio" id="page_type" name="page_type" value="Page" <? if($_REQUEST['page_type']=='Page') echo "checked"; elseif(!$_REQUEST['page_type']) echo "checked"?> onclick="changePageType()" />&nbsp;Page&nbsp;
								  <input type="radio" id="page_type" name="page_type" value="Link" <? if($_REQUEST['page_type']=='Link') echo "checked"; ?> onclick="changePageType()" />&nbsp;Link&nbsp;
								  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_STAT_PAGE_PTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
							 </tr>
							</table>
						</td>
						<td class="tdcolorgray">
							<table cellpadding="0" cellspacing="0" width="100%">
								<?php
								if($ecom_site_mobile_api==1)
								{
								?>
								<tr>
								<td width="25%" align="left" valign="middle" class="tdcolorgray" >In Mobile Application</td>
								<td><input type="checkbox" value="1" id="in_mobile_api_sites" name="in_mobile_api_sites"></td>
								</tr>
								<tr>
								<td colspan="2" >&nbsp;</td>
								</tr>
								<?php
								}
								
								   #Getting position values of the site theme
								 $sql_group="SELECT group_id,group_name FROM static_pagegroup WHERE sites_site_id=$ecom_siteid AND group_hide=0";
								 $res_group = $db->query($sql_group);
								  if ($db->num_rows($res_group))
									{
									?>
								<tr>
								  <td width="25%" align="left" valign="middle" class="tdcolorgray" >Page Menu </td>
								  <td width="75%" align="left" valign="middle" class="tdcolorgray">
								  <!--<select name="page_group[]" multiple="multiple" size="4" >-->
								 <?
								
									  while($row_group = $db->fetch_array($res_group))
									  {
										$val_group=$row_group['group_name'];
										$id_group=$row_group['group_id'];
										$disp_array[$id_group] = $val_group;
										//echo "<option value=$id_group>$val_group</option>";
									  }
									 
								  echo generateselectbox('page_group[]',$disp_array,$_REQUEST['page_group'],'','',5);
								  ?>
								
								 <!-- </select>-->
								  &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_STAT_PAGE_PGROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					  </td>
								</tr>
								<? } ?>
								
							</table>
						</td>
					</tr>
					</table>
				</div>
		</tr>
		<tr>
			<td width="100%" class="tdcolorgray" colspan="6">
			<div class="editarea_div">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr class="editcontent">
						<td class="tdcolorgray">
						 <?php
							if($_REQUEST['page_type']=='Page')
							{
								$content_tr_display='';
								$link_tr_display='none';
							}
							else if($_REQUEST['page_type']=='Link')
							{
								$content_tr_display='none';
								$link_tr_display='';
							}
							else 
							{
								$content_tr_display='';
								$link_tr_display='none';
							}
							?>
						<table width="100%" cellpadding="0" cellspacing="0">
							 <tr id="show_content_tr" style="display:<?=$content_tr_display?>" >
								  <td width="10%" align="left" valign="top" class="tdcolorgray"  >Content  </td>
								  <td colspan="3" align="left" valign="top" class="tdcolorgray" >
								  <?php
									$editor_elements = "content";
									include_once("js/tinymce.php");
									
									$pgcontent = str_ireplace('</textarea>','<~~textarea>',stripslashes($_REQUEST['content']));		   
								?>	<textarea style="height:500px; width:700px" id="content" name="content"><?=$pgcontent?></textarea>
								<?php // Replacing <~~textarea> with </textarea> using javascript?>
									<script type="text/javascript">
									document.getElementById('content').value = document.getElementById('content').value.replace(/<~~textarea>/gi, "</textarea>");
									</script>						 </td>
							</tr>
						   <tr id="show_link_tr" style="display:<?=$link_tr_display?>">
		
							  <td width="20%" align="left" valign="middle" class="tdcolorgray" >Page Link  </td>
							  <td width="34%" align="left" valign="middle" class="tdcolorgray">
							 <input name="page_link" type="text" class="input" size="40"  value="<?=$_REQUEST['page_link']?>" />					 </td>
							  <td width="13%" align="left" valign="middle" class="tdcolorgray">Open Link in                     </td>
							  <td width="33%" align="left" valign="middle" class="tdcolorgray">
							  <select name="page_link_newwindow" id="page_link_newwindow">
								<option value="1" <?php echo ($_REQUEST['page_link_newwindow']==1)?'selected="selected"':''?>>New Window</option>
								<option value="0" <?php echo ($_REQUEST['page_link_newwindow']==0)?'selected="selected"':''?>>Same Window</option>
							  </select></td>
						  </tr>
						   <tr>
							 <td align="right" valign="middle" class="tdcolorgray" ><p>&nbsp;</p></td>
							 <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
							 <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
							 <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
						  </tr>
					  </table>
						</td>
					</tr>
			</table>
			</div>
			</td>
		</tr>
		<tr>
			<td width="100%" class="tdcolorgray">
			<div class="editarea_div">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="right" valign="middle" class="tdcolorgray" >
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

