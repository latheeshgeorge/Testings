<?php
	/*#################################################################
	# Script Name 	: list_settings_letter_templates.php
	# Description 	: Page for listing the email templates avilable for the site
	# Coded by 		: ANU
	# Created on	: 20-June-2007
	# Modified by	: Sny
	# Modified On	: 30-Jul-2007
	#################################################################*/
//Define constants for this page
$table_name='general_settings_site_letter_templates';
$page_type='Email Templates';
$help_msg = get_help_messages('LIST_LETTER_TEMPLATE_MESS1');
$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmlistEmailTemplates,\'lettertemplate_id[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmlistEmailTemplates,\'lettertemplate_id[]\')"/>','Slno.','Email Title','Disabled');
$header_positions=array('left','left','left','left');
$colspan = count($table_headers);
$query_string .= "request=settings_letter_templates&";

//#Search terms
$search_fields = array('currency_name','lettertemplate_title','sort_by','sort_order','pg','start');
foreach($search_fields as $v) {
	$query_string .= "$v=".$_REQUEST[$v]."&";#For passing search terms to javascript for passing to different pages.
}
	
//#Sort
$sort_by = (!$_REQUEST['sort_by'])?'lettertemplate_title':$_REQUEST['sort_by'];
$sort_order = (!$_REQUEST['sort_order'])?'ASC':$_REQUEST['sort_order'];
$sort_options = array('lettertemplate_title' => 'Template Title');
$sort_option_txt = generateselectbox('sort_by',$sort_options,$sort_by);
$sort_by_txt= generateselectbox('sort_order',array('ASC' => 'ASC','DESC' => 'DESC'),$sort_order);

//#Search Options
$where_conditions = "WHERE sites_site_id=$ecom_siteid ";
if($_REQUEST['lettertemplate_title']) {
	$where_conditions .= "AND  lettertemplate_title LIKE '%".add_slash($_REQUEST['lettertemplate_title'])."%' ";
}


//#Select condition for getting the already existing templates for the site
$sql_templates_insite = "SELECT  lettertemplate_id,lettertemplate_letter_type,lettertemplate_contents FROM $table_name ";
$res_templates_insite = $db->query($sql_templates_insite);
$template_type_insite = array();
while($template_types = $db->fetch_array($res_templates_insite)){
 $template_type_insite[] =  $template_types['lettertemplate_letter_type'];
 
}
// selectign all the templates added by the super admin for checking if there is any new entries
$sql_commontemplate = "SELECT template_id,template_lettertype,template_content,template_code,template_lettertitle,template_lettersubject FROM common_emailtemplates ";
$email_template = array();
$template_content = array();
$res_commontemplate = $db->query($sql_commontemplate);
while($commontemplate = $db->fetch_array($res_commontemplate)){
$email_template_type[]     = $commontemplate['template_lettertype'];
$email_template_contents[] = $commontemplate['template_content'];
$email_template_title[] = $commontemplate['template_lettertitle'];
$template_lettersubject[] = $commontemplate['template_lettersubject'];
//$emailtemplate['template_content'] = $commontemplate['template_content'];
//$emailtemplate[$commontemplate['template_id']]['template_content'] = $commontemplate['template_content'];
//$emailtemplate[$commontemplate['template_id']]['template_code'] = $commontemplate['template_code'];
}
$new_templates = array_diff($email_template_type,$template_type_insite);
foreach ($new_templates as $key => $val){
$insert_array = array();
$insert_array['sites_site_id']  				= $ecom_siteid;
$insert_array['lettertemplate_letter_type']   	= $val;
$insert_array['lettertemplate_from']   			= addslashes(stripslashes($ecom_email));
$insert_array['lettertemplate_contents']   		= add_slash($email_template_contents[$key],false);
$insert_array['lettertemplate_title']   		= add_slash($email_template_title[$key]);
$insert_array['lettertemplate_subject']   		= add_slash($template_lettersubject[$key]);
$db->insert_from_array($insert_array, 'general_settings_site_letter_templates');
}
//////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////For paging///////////////////////////////////////////
//#Select condition for getting total count
$sql_count = "SELECT count(*) as cnt FROM $table_name  $where_conditions";
$res_count = $db->query($sql_count);
list($numcount) = $db->fetch_array($res_count);#Getting total count of records

$records_per_page =(is_numeric($_REQUEST['records_per_page']) && ($_REQUEST['records_per_page']))?$_REQUEST['records_per_page']:10;#Total records shown in a page

$pg = $_REQUEST['pg'];
if (!($pg > 0) || $pg == 0) {  $pg = 1; }
$pages = ceil($numcount / $records_per_page);#Getting the total pages
if($pg > $pages) {
	$pg = $pages;
}

$start = ($pg - 1) * $records_per_page;#Starting record.

/////////////////////////////////////////////////////////////////////////////////////
/////// end paging/////////


?>
<script  type="text/javascript">

function edit_selected(mode)
{
	
	len=document.frmlistEmailTemplates.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistEmailTemplates.elements[j]
		if (el!=null && el.name== "lettertemplate_id[]" )
		   if(el.checked) {
		   		cnt++;
				general_id=el.value;
		   }		
	}
	if(cnt==0) {
		alert('Please select atleast one E-mail Template ');
		return false;
		
	}
	else if(cnt>1 ){
		alert('Please select only one E-mail Template to edit');
		return false;
	}
	else
	{
		document.frmlistEmailTemplates.fpurpose.value=mode;
		document.frmlistEmailTemplates.submit();
	}
	
	
}

function getSelected(purpose){
		document.frmlistEmailTemplates.fpurpose.value=purpose;
		document.frmlistEmailTemplates.submit();
}
function save_disabled()
{	
	handle_disable_click();
	document.frmlistEmailTemplates.fpurpose.value = 'save_email_disabled';
	document.frmlistEmailTemplates.submit();
}
function handle_disable_click()
{
	len=document.frmlistEmailTemplates.length;
	var cnt=0;		
	for (var j = 1; j <= len; j++) {
		el = document.frmlistEmailTemplates.elements[j]
		if (el!=null && el.name== "lettertemplate_id[]" )
		   el.checked = true;
	}
}
</script>
<form name="frmlistEmailTemplates" action="home.php" method="post" >	
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="request" value="settings_letter_templates" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="pg" value="<?=$pg?>" />

  <table border="0" cellpadding="0" cellspacing="0" class="maintable" width="100%">
    <tr>
      <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span> List Email templates</span></td>
    </tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
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
			<td  colspan="3" align="center" valign="middle" class="errormsg"><?php echo $alert?></td>
		</tr>
<?php
	}
?> 
    <?php
	  if($numcount)
	  { 
	?>
	<tr><td  colspan="3" align="right" valign="middle" class="sorttd"><?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?></td></tr>
	 <?php
	  } ?>
	<tr>
      <td height="48" colspan="4" class="sorttd">
	  <div class="sorttd_div" >
      	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sorttabletop">		
        <tr>
          <td width="9%" align="left" valign="middle">Email title  </td>
          <td width="20%" align="left" valign="middle"><input name="lettertemplate_title" type="text"  id="lettertemplate_title" value="<?=$_REQUEST['lettertemplate_title']?>" /></td>
          <td width="10%" align="left" valign="middle">Records Per Page </td>
          <td width="13%" align="left" valign="middle"><input name="records_per_page" type="text"  id="records_per_page" size="3" value="<?=$records_per_page?>" /></td>
          <td width="6%" align="left" valign="middle">Sort By</td>
          <td width="29%" align="left" valign="middle"><?=$sort_option_txt?> in 
            <?=$sort_by_txt?></td>
          <td width="13%" align="right" valign="middle"><input name="go_button" type="button" class="red" id="go_button" value="Go" onclick="getSelected('')" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_LETTER_TEMPLATE_GO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
      </table>
	  </div>
      </td>
	</tr>  
    
    <tr>
      <td colspan="3" class="listingarea">
	  <div class="listingarea_div" >
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
	  <tr>
      <td class="listeditd" align="left" valign="middle" colspan="3">
	   <?
	  if($numcount)
	  {
	  ?>
	  <a href="#" onclick="return edit_selected('edit_letter_templates')" class="editlist" title="Edit">Edit</a> <? } ?></td>
      <td class="listeditd" align="right" valign="middle"><input type="button" name="Button" value="Save" class="red" onclick="save_disabled()" />
        <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_LETTER_TEMPLATE_SAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
       <?
	   echo table_header($table_headers,$header_positions);
	   if($numcount>0) 
	   {
	   $sql_settings_currency = "SELECT lettertemplate_id,lettertemplate_title,lettertemplate_disabled 
	   FROM $table_name $where_conditions 
	   ORDER BY $sort_by $sort_order 
	   LIMIT $start,$records_per_page ";
	   $res = $db->query($sql_settings_currency); 
	   if ($db->num_rows($res)){
	   $count_no = 0;
	   while($row = $db->fetch_array($res))
	   {
			$count_no++;
			$array_values = array();
			if($count_no %2 == 0)
				$class_val="listingtablestyleA";
			else
				$class_val="listingtablestyleB";	
	  
	   ?>
        <tr >
          <td width="9%"align="left" valign="middle" class="<?=$class_val;?>"><input name="lettertemplate_id[]" value="<? echo $row['lettertemplate_id']?>" type="checkbox"></td>
          <td  width="8%" align="left" valign="middle" class="<?=$class_val;?>"><?=$count_no?></td>
          <td align="left" valign="middle" class="<?=$class_val;?>"><a href="home.php?sort_by=<?=$sort_by?>&sort_order=<?=$sort_order?>&pg=<?=$_REQUEST['pg']?>&request=settings_letter_templates&fpurpose=edit_letter_templates&lettertemplate_id=<?=$row['lettertemplate_id']?>&lettertemplate_letter_type=<?php echo $_REQUEST['lettertemplate_letter_type']?>&records_per_page=<?=$records_per_page?>&start=<?=$start?>&lettertemplate_title=<?=$_REQUEST['lettertemplate_title']?>" class="edittextlink" title="Edit"><? echo $row['lettertemplate_title']?></a></td>
		   <td align="left" valign="middle" class="<?=$class_val;?>"><input type="checkbox" name="letter_disabled[]" value="<?php echo $row['lettertemplate_id']?>" <?php echo ($row['lettertemplate_disabled']==1)?'checked="checked"':''?> /></td>
        </tr>
      <?
	  }
	}
}	
	  else
		{
	?>	
			<tr>
				  <td align="center" valign="middle" class="norecordredtext" colspan="<?php echo $colspan?>">
				  	No Letter templates exists for this site.				  </td>
			</tr>	  
	<?php
		}
		
	?>
	<tr>
      <td class="listeditd" align="left" valign="middle" colspan="3"> <?
	  if($numcount)
	  {
	  ?><a href="#" onclick="return edit_selected('edit_letter_templates')" class="editlist" title="Edit">Edit</a> <? } ?>  </td>
      <td align="right" valign="middle" class="listeditd">
	  </td>
    </tr>
      </table>
	  </div>
	  </td>
    </tr>
	<tr>
      <td align="right" valign="middle" class="listing_bottom_paging" colspan="2">
	   <?
	  if($numcount)
	  {
	  ?>
	  <?php paging_footer($query_string,$numcount,$pg,$pages,$page_type,$colspan); ?> <? } ?></td>
    </tr>
    </table>
</form>
