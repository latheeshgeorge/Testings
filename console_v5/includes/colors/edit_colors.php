<?php
/*#################################################################
# Script Name 	: edit_colors.php
# Description 	: Page for editing Product Variable Colors
# Coded by 	: Sny
# Created on	: 11-Jan-2010
# Modified by	: 
# Modified On	: 
#################################################################*/
#Define constants for this page
$page_type      = 'Product Variable Colors';
$help_msg       = get_help_messages('EDIT_PROD_VAR_COLOR_MESS1');
$sql            = "SELECT color_name,color_hexcode,images_image_id 
                    FROM 
                        general_settings_site_colors 
                    WHERE 
                        sites_site_id=$ecom_siteid 
                        AND color_id=".$color_id;
$res=$db->query($sql);
if($db->num_rows($res)==0)
{ 
    echo " <font color='red'> You Are Not Authorised  </a>"; 
    exit;
}
$row=$db->fetch_array($res);
?>
<script charset="UTF-8" src="js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="js/mColorPicker.js"></script>
<script language="javascript" type="text/javascript">
function valform(frm)
{
    fieldRequired           = Array('color_name','color_hexcode');
    fieldDescription        = Array('Color Name','Color Hex Code');
    fieldEmail              = Array();
    fieldConfirm            = Array();
    fieldConfirmDesc        = Array();
    fieldNumeric            = Array();
    if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric))
    {
		$(this).serialize();
        show_processing();
        return true;
    } 
    else
    {
        return false;
    }
}
function assign_color_value_image()
{
	document.frmeditcolors.fpurpose.value = 'add_colorimg';
	document.frmeditcolors.submit();
}
function delete_color_value_image()
{
	if(confirm('Are you sure you want to unassign the image?'))
	{
		document.frmeditcolors.fpurpose.value = 'rem_colorimg';
		document.frmeditcolors.submit()
	}
}
</script>
<form name='frmeditcolors' id="frmeditcolors" action='home.php?request=colorcodes' method="post" onsubmit="return valform(this);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=colorcodes&sort_by=<?=$_REQUEST['sort_by']?>&sort_order=<?=$_REQUEST['sort_order']?>&records_per_page=<?=$_REQUEST['records_per_page']?>&search_name=<?=$_REQUEST['search_name']?>&start=<?=$_REQUEST['start']?>&pg=<?=$_REQUEST['pg']?>">List Product Variable Colors </a><span> Edit Product Variable Color</span></td>
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
		<td colspan="3">
		<div class="editarea_div">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Color Name&nbsp;<span class="redtext">*</span> </td>
          <td width="75%" align="left" valign="middle" class="tdcolorgray">
            <input name="color_name"  class="input" value="<?=$row['color_name']?>" size="45"  />
          </td>
        </tr>
        <tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Hexadecimal Code for color (#FF0000 for red))</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="text" class="color" type="text" name="color_hexcode" size="7"  value="<?=$row['color_hexcode']?>" />
          </td>
        </tr>
        <tr>
          <td width="25%" align="left" valign="middle" class="tdcolorgray" >Image Pattern </td>
          <td align="left" valign="middle" class="tdcolorgray">
		  <table width="10%" cellpadding="0" cellspacing="0" border="0" id="varimg_table_ext">
		<tr>
		<td align="left" style="width:16px">
		<?php
		  $disp_delimg = false;
		  if ($row['images_image_id']!=0)
		  {
			$sql_img = "SELECT a.image_id,a.image_gallerythumbpath,a.images_directory_directory_id 
							FROM 
								images a 
							WHERE 
								a.sites_site_id = $ecom_siteid 
								AND a.image_id=".$row['images_image_id']." 
							LIMIT 
								1";	
			$ret_img = $db->query($sql_img);
			if($db->num_rows($ret_img))
			{
				$row_img = $db->fetch_array($ret_img);
				$disp_delimg = true;
				$assign_cap = 'Change Image';
		  ?>
				<a href="javascript:assign_color_value_image()" style="cursor:pointer" onmouseover ="ddrivetip('<center><br><img src=http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?> title=Preview border=0/><br><br><strong>Click to change the image</strong></center>')"; onmouseout="hideddrivetip()"><img src="http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?>" width="16px" height="16px" border="0"/></a>
		  <?php
			}
		  }
		  else
		  {
		  ?>
				<img src="images/var_noimg.gif" title="No Image Assigned. Click to Assign" width="16px" height="16px" onclick="assign_color_value_image()" style="cursor:pointer"/>
		  <?php	
				$assign_cap = 'Assign Image';
		  }
		  ?>&nbsp;
		  </td>
		<td align="left" style="width:16px; height:16px">
		<?php
			if($disp_delimg)
			{
		  ?>
				<img src="images/var_delimg.gif" title="Unassign Image" width="16px" height="16px" onclick="delete_color_value_image()" style="cursor:pointer"/>
		  <?php
			}
		?>
		</td>
		</tr>
		</table>
		  </td>
        </tr>
       
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
		 </td>
	  </td>
	  </tr>
	  </table>
	  </div>
	   <div class="editarea_div">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
          <td align="right" valign="middle" class="tdcolorgray">
            <input type="hidden" name="color_id" id="color_id" value="<?=$color_id?>" />
            <input type="hidden" name="search_name" id="search_name" value="<?=$_REQUEST['search_name']?>" />
            <input type="hidden" name="start" id="start" value="<?=$_REQUEST['start']?>" />
            <input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
            <input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
            <input type="hidden" name="records_per_page" id="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
            <input type="hidden" name="pg" id="pg" value="<?=$_REQUEST['pg']?>" />
            <input type="hidden" name="fpurpose" id="fpurpose" value="update_colors" />
            <input type="hidden" name="retdiv_id" id="retdiv_id" value="maincontent" />
            <input type="hidden" name="retdiv_more" id="retdiv_more" value="" />
			<input type="hidden" name="src_page" id="src_page" value="add_colorimg" />
			<input type="hidden" name="remvarvalueimg" id="remvarvalueimg" value="" />
			<input type="hidden" name="src_id" id="src_id" value="<?=$color_id?>" />
            <input name="Submit" type="submit" class="red" value="Save" />
        </td>
        </tr>
      </table>
	  </div>
</form>