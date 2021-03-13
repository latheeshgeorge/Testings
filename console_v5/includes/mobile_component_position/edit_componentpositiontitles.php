<?php
/*#################################################################
# Script Name 	: edit_componentpositiontitles.php
# Description 	: Page for editing component position titles
# Coded by 		: Sny
# Created on	: 25-Jul-2007
# Modified by	: Sny
# Modified On	: 26-Jul-2007
#################################################################*/

/*Define constants for this page*/
$page_type = 'Component Titles';
$help_msg =get_help_messages('MAN_COMPO_POS_EDIT_COMP');
$table_headers = array('Slno.','Component Name','Shown in site as','Original Name');
$header_positions=array('left','left','left','left');
$colspan = count($table_headers);


// Building the query by combining the site_menu and features tables
/*$sql = "SELECT a.display_id,a.display_title,a.display_position,a.display_order,b.module_name,b.feature_title FROM  
		display_settings a, features b WHERE a.site_id=".$ecom_siteid." AND a.display_position = '".$_REQUEST['movetoedit']."' AND b.feature_allowedit=1  
		AND a.feature_id = b.feature_id AND a.layout_code='".$_REQUEST['passlayoutcode']."' ORDER BY a.display_order";
*/		
// Building the query by combining the site_menu and features tables
$sql = "SELECT display_id,display_title,a.features_feature_id,a.themes_layouts_layout_id,b.feature_name,display_position,display_component_id FROM display_settings a,features b WHERE a.sites_site_id=$ecom_siteid AND 
			display_position='".$_REQUEST['movetoedit']."' AND layout_code = '".$_REQUEST['passlayoutcode']."' 
			AND a.features_feature_id=b.feature_id ORDER BY display_order";
$ret = $db->query($sql);
?>
<script language="javascript" type="text/javascript">
function valform(frm)
{
	/*for(i=0;i<=frm.elements.length;i++)
	{
	 if (frm.elements[i].type =='text' && frm.elements[i].name=='txt_displaytitle[]')
		{
			if(frm.elements[i].value=='')
			{
				 alert("The field 'Shown in site as' should not be blank");
				 frm.elements[i].focus();
				 return false;
			}
        }
	
	}*/
	if(confirm('Are you sure you want to save changes?')) {
		show_processing();
		return true;
	} else {
		return false;
	}
}
</script>
<form name='frmComponentTitles' action='home.php?request=mob_comp_pos' method="post" onSubmit="return valform(this);" >
<input type="hidden" name="fpurpose" value="Save_titles" />
<input type="hidden" name="layoutcode" value="<?php echo $_REQUEST['passlayoutcode']?>" />
<input type="hidden" name="cur_pos" value="<?php echo $_REQUEST['cur_pos']?>" />
<table width="100%" border="0" cellpadding="0" cellspacing="1">
<tr>
<td>
<table border="0" cellpadding="2" cellspacing="0" width="100%" align="left">
  <tr> 
	<td align="left" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=mob_comp_pos&layoutcode=<?php echo $_REQUEST['passlayoutcode']?>">Assign and manage the components</a> <span> Edit Component Titles</span></td>
  </tr>
</table></td>
</tr>
<tr>
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
		  <td align="center" class="errormsg"><?php echo $alert?></td>
		</tr>
<?php
	}
?>
<tr>
<td class="tdcolorgraynormal">
<div class="listingarea_div">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <?php
	echo table_header($table_headers,$header_positions);
	if ($db->num_rows($ret))
	{
		$i = 1;
		$prev_pos = '';
		$cnts = 0;
		while ($row = $db->fetch_array($ret))
		{
			$showname 	= stripslashes($row['display_title']);
			$orgname	= getComponenttitle($row['features_feature_id'],$row['display_component_id']);
			$cls = ($cnts%2==0)?'listingtablestyleB':'listingtablestyleA';
			$cnts++;
	?>
		  <tr>
			<td align="center" width="5%" class="<?php echo $cls?>"><?php echo $i++;?>.</td>
			<td align="left" width="28%" class="<?php echo $cls?>"><?php echo stripslashes($row['feature_name'])?></td>
			<td align="left" width="30%" class="<?php echo $cls?>">
			<input type="text" name="txt_displaytitle[]" value="<?php echo $showname?>" size="40" <?php echo $sel;?> />
			<input type="hidden" name="txt_displayid[]" value="<?php echo $row['display_id'];?>" />
			</td>
			<td align="left" width="37%" class="<?php echo $cls?>"><?php echo $orgname?></td>
		  </tr>
		 
  <?php
  			$curlayout_id = $row['themes_layouts_layout_id'];
		}
	}
  ?>
   <tr>
	 <td align="center" colspan="<?php echo $colspan?>">&nbsp;</td>
  </tr>
  </table>
  </div>
  <div class="editarea_div">
  <table width="100%" border="0" cellspacing="2" cellpadding="2">
   <tr>
			<td align="right" colspan="<?php echo $colspan?>"><input type="submit" name="title_Submit" value="Save" class="red" /></td>
  </tr>
</table>
</div>
</td>
</tr>
<tr>
<td class="tdcolorgraynormal">&nbsp;</td>
</tr>
</table>
<input type="hidden" name="cur_layoutid" value="<?php echo $curlayout_id?>" />
</form>