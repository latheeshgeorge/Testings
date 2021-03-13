<?php
	/*
	#################################################################
	# Script Name 	: list_seo.php
	# Description 	: Page for managing Seo 
	# Coded by 		: LSH
	# Created on	: 09-Oct-2007
	# Modified by	: 
	# Modified On	: 
	#################################################################
	*/

//Define constants for this page
$table_name = 'static_pages';
$page_type = 'Static Pages';
$help_msg = 'This section Helps to Edit Static Pages';

/////////////////////////////////For paging///////////////////////////////////////////

$cbo_sites = $_REQUEST['cbo_sites'];
$page_id = $_REQUEST['page_id'];

$sql="SELECT title,content,hide,pname,page_type,page_link,page_link_newwindow 
			FROM static_pages 
					WHERE sites_site_id=$cbo_sites AND page_id=".$page_id;
$res=$db->query($sql);
$row=$db->fetch_array($res);

?>
<script type="text/javascript">
function valform(frm)
{
	fieldRequired = Array('title');
	fieldDescription = Array('Page Title');
	fieldEmail = Array();
	fieldConfirm = Array();
	fieldConfirmDesc  = Array();
	fieldNumeric = Array();
	if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric)) {
		return true;
	} else {
		return false;
	}
}
	function handle_searchsubmit()
	{
		if(document.main.cbo_sites.value=='')
		{
			//alert("Please select a Site");
			//return false;
		}
		formhandler('sel_site');
	}
	function formhandler(purpose)
	{
		var purp
		purp=purpose
		document.main.fpurpose.value=purp
		document.main.submit()	
	}
	function handle_typechange()
	{
	
		document.frmlistStaticpages.retain_val.value 	= 1;
		document.frmlistStaticpages.type_change.value 	= 1;
		document.frmlistStaticpages.submit();
	}
	
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form name='frmAddPage' action='home.php?request=seo' method="post" onsubmit="return valform(this);">
  <tr>
    <td colspan="6" align="left" valign="middle" class="menutabletoptd">
	<b><a href="home.php?request=seo&cbo_sites=<?=$cbo_sites?>">Manage SEO</a><font size="1">>></font> 
			  Edit <?=$page_type?> 
			  </b><br />
			  <img src="images/blueline.gif" alt="" border="0" height="1" width="400">
	</td>
  </tr>
  <tr>
    <td colspan="6" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
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
    <td width="52%" class="tdcolorgray"><table cellspacing="6" cellpadding="0" width="100%" border="0">
      <tr>
        <td  align="left" valign="middle" class="tdcolorgray" >Page Title <span class="redtext">*</span> </td>
        <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="title"  value="<?=$row['title']?>" maxlength="100" />
        </td>
      </tr>
      <?php /*?> <tr> commented for not to edit the pname. This is be used only for internal purpose
				 <td  align="left" valign="middle" class="tdcolorgray" >Page Name  </td>
				  <td width="63%"  align="left" valign="middle" class="tdcolorgray" >
				  <input class="input" type="text" name="pname"  value="<?=$_REQUEST['pname']?>" />
			    </td>
			  </tr><?php */?>
      <tr>
        <td width="37%" align="left" valign="middle" class="tdcolorgray" >Hide Page</td>
        <td width="63%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="hide" value="1"  <? if($row['hide']==1) echo "checked"?> />
          &nbsp;Yes&nbsp;
          <input type="radio" name="hide" value="0" <? if($row['hide']==0) echo "checked"?> />
          &nbsp;No&nbsp; </td>
      </tr>
      <tr>
        <td width="37%" align="left" valign="middle" class="tdcolorgray" >Page Type </td>
        <td align="left" valign="middle" class="tdcolorgray" colspan="3"><input type="radio" id="page_type" name="page_type" value="Page" <? if($row['page_type']=='Page') echo "checked"; elseif(!$_REQUEST['page_type']) echo "checked"?> onclick="changePageType()" />
          &nbsp;Page&nbsp;
          <input type="radio" id="page_type" name="page_type" value="Link" <? if($row['page_type']=='Link') echo "checked"; ?> onclick="changePageType()" />
          &nbsp;Link&nbsp;</td>
      </tr>
    </table></td>
    <td width="48%" class="tdcolorgray"><table cellpadding="0" cellspacing="0" width="100%">
    <?
	 $sql_group="SELECT group_id,group_name FROM static_pagegroup WHERE sites_site_id=$cbo_sites AND group_hide=0";
		  $res_group = $db->query($sql_group);
		 if($db->num_rows($res_group)>0){
	?>
      <tr>
        <td width="29%" align="left" valign="middle" class="tdcolorgray" >Page Groups</td>
        <td width="71%" align="left" valign="middle" class="tdcolorgray"><!--<select name="page_group[]" multiple="multiple" size="4" >-->
            		  <select name="page_group[]" multiple="multiple">
			  <?
		$sql_page_group="SELECT static_pagegroup_group_id 
				FROM static_pagegroup_static_page_map 
					WHERE static_pages_page_id=".$page_id;
		  $res_page_group=$db->query($sql_page_group);
		 if($db->num_rows($res_page_group))
		 {
		 	 	$arr_page_group=array();
				while($row_page_group=$db->fetch_array($res_page_group))
				{
					$arr_page_group[]=$row_page_group['static_pagegroup_group_id'];
				}
		  }	
		  
		   #Getting position values of the site theme
		 
		  while($row_group = $db->fetch_array($res_group))
		  {
		  	$val_group=$row_group['group_name'];
			$id_group=$row_group['group_id'];
			$selected='';
			if(in_array($id_group,$arr_page_group))
			{
				$selected='selected';
			}
		  	echo "<option value=$id_group $selected>$val_group</option>";
		  }
					  ?>
          </select>
          &nbsp;</td>
      </tr>  <? }?>
    </table></td>
  </tr>
  <?
		if($row['page_type']=='Page')
		{
			$content_tr_display='';
			$link_tr_display='none';
		}
		else
		{
			$content_tr_display='none';
			$link_tr_display='';
		}
		?>
  <tr>
    <td colspan="2" class="tdcolorgray"><table width="100%" cellpadding="0" cellspacing="0">
      <tr id="show_content_tr" style="display:<?=$content_tr_display?>" >
        <td width="20%" align="left" valign="top" class="tdcolorgray"  >&nbsp;&nbsp;Content </td>
        <td colspan="3" align="left" valign="middle" class="tdcolorgray" ><?php
										
										include_once("classes/fckeditor.php");
										$editor 			= new FCKeditor('content') ;
										$editor->BasePath 	= '/js/FCKeditor/';
										$editor->Width 		= '650';
										$editor->Height 	= '300';
										$editor->ToolbarSet = 'BshopWithImages';
										$editor->Value 		= stripslashes($row['content']);
										$editor->Create() ;
									   
						?> <textarea name="content" cols="100" rows="15"><?PHP echo stripslashes($_REQUEST['content']); ?></textarea></td>
      </tr>
      <tr id="show_link_tr" style="display:<?=$link_tr_display?>">
        <td width="20%" align="left" valign="middle" class="tdcolorgray" >Page Link </td>
        <td width="34%" align="left" valign="middle" class="tdcolorgray"><input name="page_link" type="text" class="input" size="40"  value="<?=$_REQUEST['page_link']?>" />
        </td>
        <td width="13%" align="left" valign="middle" class="tdcolorgray">Open Link in </td>
        <td width="33%" align="left" valign="middle" class="tdcolorgray"><select name="page_link_newwindow" id="page_link_newwindow">
          <option value="1" <?php echo ($_REQUEST['page_link_newwindow']==1)?'selected="selected"':''?>>New Window</option>
          <option value="0" <?php echo ($_REQUEST['page_link_newwindow']==0)?'selected="selected"':''?>>Same Window</option>
        </select></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
    <td align="left" valign="middle" class="tdcolorgray" colspan="5"><input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
        <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
        <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
        <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
        <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
        <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
		<input type="hidden" name="cbo_sites" id="cbo_sites" value="<?=$_REQUEST['cbo_sites']?>" />
		<input type="hidden" name="page_id" id="page_id" value="<?=$_REQUEST['page_id']?>" />
        <input type="hidden" name="fpurpose" id="fpurpose" value="stat_update" />
		<input type="Submit" name="Submit" id="Submit" value="Update" class="input-button"></td>
  </tr>
  </form>
</table>
