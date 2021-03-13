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
$help_msg = 'This section Helps to manage Static Pages';

/////////////////////////////////For paging///////////////////////////////////////////

$cbo_sites = $_REQUEST['cbo_sites'];
$keytype		= $_REQUEST['records_per_page'];

/*
$table_headers = array('Slno.','Page Title','Page in Groups','Type','Hidden','Action');
$header_positions=array('center','center','center','center','center','center');
$colspan = count($table_headers);
//#Sort

$sort_by = (!$_REQUEST['sort_by'])?'title':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('title' => 'Title','page_type'=>'Type');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= 'Sort by '.generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

$where_conditions = "WHERE sites_site_id=$cbo_sites ";
$search_name = $_REQUEST['search_name'];
if($_REQUEST['search_name']) {
	$where_conditions .= "AND ( title LIKE '%".add_slash($_REQUEST['search_name'])."%')";
}

$sql_count = "SELECT count(*) as cnt
				 FROM $table_name  
				 	$where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records

$records_per_page = (is_numeric($_REQUEST['records_per_page']) &&($_REQUEST['records_per_page']))?$_REQUEST['records_per_page']:10;#Total records shown in a page
$pg= ($_REQUEST['search_click']==1)?1:$_REQUEST['pg'];
if (!($pg > 0) || $pg == 0) { $pg = 1; }
$start = ($pg - 1) * $records_per_page;#Starting record.
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}
$count_no = ($pg == 1)?0:$records_per_page*($pg-1);
$colspan = 6;
/////////////////////////////////////////////////////////////////////////////////////
$query_string .= "request=seo&fpurpose=staticpage&sort_by=$sort_by&sort_order=$sort_order&cbo_sites=$cbo_sites&search_name=$search_name";
*/
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
			  Add <?=$page_type?> 
			  </b><br /><img src="images/blueline.gif" alt="" border="0" height="1" width="400">
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
        <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="title"  value="<?=$_REQUEST['title']?>" maxlength="100" />
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
        <td width="63%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="hide" value="1"  <? if($_REQUEST['hide']==1) echo "checked"?> />
          &nbsp;Yes&nbsp;
          <input type="radio" name="hide" value="0" <? if($_REQUEST['hide']==0) echo "checked"?> />
          &nbsp;No&nbsp; </td>
      </tr>
      <tr>
        <td width="37%" align="left" valign="middle" class="tdcolorgray" >Page Type </td>
        <td align="left" valign="middle" class="tdcolorgray" colspan="3"><input type="radio" id="page_type" name="page_type" value="Page" <? if($_REQUEST['page_type']=='Page') echo "checked"; elseif(!$_REQUEST['page_type']) echo "checked"?> onclick="changePageType()" />
          &nbsp;Page&nbsp;
          <input type="radio" id="page_type" name="page_type" value="Link" <? if($_REQUEST['page_type']=='Link') echo "checked"; ?> onclick="changePageType()" />
          &nbsp;Link&nbsp;</td>
      </tr>
    </table></td>
    <td width="48%" class="tdcolorgray"><table cellpadding="0" cellspacing="0" width="100%">
      <?
					   #Getting position values of the site theme
					  $sql_group="SELECT group_id,group_name 
					  				FROM static_pagegroup 
					  					WHERE sites_site_id=$cbo_sites AND group_hide=0";
					 $res_group = $db->query($sql_group);
					  if ($db->num_rows($res_group))
						{
						?>
      <tr>
        <td width="29%" align="left" valign="middle" class="tdcolorgray" >Page Groups</td>
        <td width="71%" align="left" valign="middle" class="tdcolorgray"><!--<select name="page_group[]" multiple="multiple" size="4" >-->
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
          &nbsp;</td>
      </tr>
      <? } ?>
    </table></td>
  </tr>
  <?
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
  <tr>
    <td colspan="2" class="tdcolorgray"><table width="100%" cellpadding="0" cellspacing="0">
      <tr id="show_content_tr" style="display:<?=$content_tr_display?>" >
        <td width="20%" align="left" valign="top" class="tdcolorgray"  >&nbsp;&nbsp;Content </td>
        <td colspan="3" align="left" valign="middle" class="tdcolorgray" ><?php
									/*	
										include_once("classes/fckeditor.php");
										$editor 			= new FCKeditor('content') ;
										$editor->BasePath 	= '/js/FCKeditor/';
										$editor->Width 		= '650';
										$editor->Height 	= '300';
										$editor->ToolbarSet = 'BshopWithImages';
										$editor->Value 		= stripslashes($_REQUEST['content']);
										$editor->Create() ;
									  */ 
						?>
          <textarea name="content" cols="100" rows="15"><?PHP echo stripslashes($_REQUEST['content']); ?></textarea></td>
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
        <input type="hidden" name="fpurpose" id="fpurpose" value="stat_insert" />
		<input type="Submit" name="Submit" id="Submit" value="Add" class="input-button"></td>
  </tr>
  </form>
</table>
