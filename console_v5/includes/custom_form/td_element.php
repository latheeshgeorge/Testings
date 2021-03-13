<?php
if($_REQUEST['td_submit'] and (!$_REQUEST['emt_id']))
{
	
	$flag_name = true;
	$result = $db->query("select max(sort_no)+1 as cnt from elements where sites_site_id=$ecom_siteid and element_sections_section_id=".$_REQUEST['section_id']);
	$row = $db->fetch_array($result);
	if($row['cnt']>0)
	{
		$sort_no=$row['cnt'];
	}
	else
	{
		$sort_no=1;
	}
	$sort_order=
	$element_name = 'e_'.uniqid(rand());
	$element_label = str_replace("'","",$_REQUEST['element_label']);
	$element_label = str_replace("\"","",$element_label);	
	if((trim($size) == "") or (!is_numeric($size)))
	$size = 20;
		
	$error_msg = str_replace("\"","",$_REQUEST['error_msg']);
	$error_msg = str_replace("'","",$error_msg);
	
	if($error_msg)
	{
		$mandatory = 'Y';
	}
	else
	{
		$mandatory = 'N';
	}
	$sql_check =  "SELECT element_id FROM elements WHERE  sites_site_id=$ecom_siteid AND element_sections_section_id=".$_REQUEST['section_id']." AND element_label='".$element_label."'  AND element_type='date' LIMIT 1";
	$ret_check =  $db->query($sql_check);
	if($db->num_rows($ret_check)>0)
	{
	  $flag_name= false;
	}
	
	if($flag_name)
	{
		$sql_insert = "INSERT INTO elements (sites_site_id,element_sections_section_id,element_name,element_type,element_label,element_valign,element_align,element_size,sort_no,mandatory,error_msg,maxlength) 
		VALUES(".$ecom_siteid.",".$_REQUEST['section_id'].",'".$element_name."','date','".$element_label."','".$_REQUEST['element_valign']."','".$_REQUEST['element_align']."',".addslashes($_REQUEST['element_size']).",".$sort_no.",'".$mandatory."','".$error_msg."',".$_REQUEST['maxlength'].")";
		$db->query($sql_insert);
				?>
				<script language="JavaScript">
				window.opener.location.href="../home.php?request=customform&fpurpose=manage_form&form_type=<?=$_REQUEST['form_type']?>&section_id=<?=$_REQUEST['section_id']?>";
				window.close();
				</script>
				<?php
	}
	else
	{
		$alert = 'ERROR!..Element Name already exists';
	}
}
if($_REQUEST['td_submit'] and $_REQUEST['emt_id'])
{
	
	
	$flag_name = true;
	
	if((trim($size) == "") or (!is_numeric($size)))
		$size = 20;
	
	$element_label = str_replace("'","",$_REQUEST['element_label']);
	$element_label = str_replace("\"","",$element_label);	
	$error_msg = str_replace("\"","",$_REQUEST['error_msg']);
	$error_msg = str_replace("'","",$error_msg);
	if($error_msg)
	{
		$mandatory = 'Y';
	}
	else
	{
		$mandatory = 'N';
	}
	$sql_check =  "SELECT element_id FROM elements WHERE  sites_site_id=$ecom_siteid AND element_sections_section_id=".$_REQUEST['section_id']." AND element_label='".$element_label."' AND element_id<>".$_REQUEST['emt_id']." AND element_type='date' LIMIT 1";
	$ret_check =  $db->query($sql_check);
	if($db->num_rows($ret_check)>0)
	{
	  $flag_name= false;
	}
	if($flag_name)
	{
		$sql_insert = "UPDATE elements SET element_label='$element_label',  element_valign='".$_REQUEST['element_valign']."', element_align='".$_REQUEST['element_align']."', element_size='".addslashes($_REQUEST['element_size'])."',mandatory='".$mandatory. "',error_msg='".$error_msg."',maxlength=".$_REQUEST['maxlength']." WHERE sites_site_id='$ecom_siteid' AND element_id=".$_REQUEST['emt_id'];
		$db->query($sql_insert);
			?>
				<script language="JavaScript">
				window.opener.location.href="../home.php?request=customform&fpurpose=manage_form&form_type=<?=$_REQUEST['form_type']?>&section_id=<?=$_REQUEST['section_id']?>";
				window.close();
				</script>
			<?php
	}
	else
	{
		$alert = "ERROR!..Element Name already exists";
	}
}

if($_REQUEST['emt_id']) {
	$result = $db->query("select element_name,element_label,element_valign,element_align,element_size,error_msg,maxlength from elements where element_id=".$_REQUEST['emt_id']);
	$row = $db->fetch_array($result);
}
$valign_array = array('top','bottom','middle');
$halign_array = array('left','right','center');
?>
<script>
function valform(frm)
{ 
 if(frm.element_label.value=='')
	{
	 alert('Enter the Label');
	 return false;
	}
	else
	{
	 return true;
	}
}
</script>
<form name="frmtd" action="" method="POST" class="frmcls" onsubmit="return valform(this);">
<table width="100%" align="center" cellpadding="0" cellspacing="0">  
	<tr >
		<td colspan="2" align="left" class="listingtableheader"><?php if($_REQUEST['emt_id']) echo "Edit Date Text Box"; else echo "Add Date Text Box"; ?></td>
	</tr>
	<tr >
		<td class="tdcolorgray">
			Label		</td>
		<td class="tdcolorgray">
			<input type="text" class="input" name="element_label" value="<?php if($element_label) echo $element_label; else echo $row['element_label']; ?>" size="35">		</td>
	</tr>
	<tr >
		<td class="tdcolorgray">
			Horizontal Align		</td>
		<td class="tdcolorgray">
			<select name="element_align">
				<?php 
					foreach($halign_array as $v) 
					{
						if($v == $align)
						{
						?>
						<option value="<?=$v?>" selected><?=$v?></option>
						<?php
						}
						else if($v == $row['element_align'])
						{
						?>
						<option value="<?=$v?>" selected><?=$v?></option>
						<?php
						}
						else
						{
						?>
						<option value="<?=$v?>"><?=$v?></option>
						<?php
						}
					}
				?>
				</select>		</td>
	</tr>
	<tr >
		<td class="tdcolorgray">
			Vertical Align		</td>
		<td class="tdcolorgray">
			<select name="element_valign">
				<?php 
					foreach($valign_array as $v) 
					{
						if($v == $valign)
						{
						?>
						<option value="<?=$v?>" selected><?=$v?></option>
						<?php
						}
						else if($v == $row['element_valign'])
						{
						?>
						<option value="<?=$v?>" selected><?=$v?></option>
						<?php
						}
						else
						{
						?>
						<option value="<?=$v?>"><?=$v?></option>
						<?php
						}
					}
				?>
				</select>		</td>
	</tr>
	<tr >
		<td class="tdcolorgray">
			Size		</td>
		<td class="tdcolorgray">
			<input type="text" name="element_size" value="<?php if($size) echo $size; else if($row['element_size']) echo $row['element_size']; else echo "20"; ?>" size="2">		</td>
	</tr>
	<tr >
		<td class="tdcolorgray">
			Character Limit		</td>
		<td class="tdcolorgray">
			<input type="text" name="maxlength" value="<?php if($tb_maxlength) echo $tb_maxlength; else if($row['maxlength']) echo $row['maxlength']; else echo "0"; ?>" size="2"> No limit if set to zero.		</td>
	</tr>
	<tr>
		<td class="tdcolorgray">
			Error Message		</td>
		<td class="tdcolorgray">
			<input type="text" name="error_msg" value="<?php if($error_msg) echo $error_msg; else echo $row['error_msg']; ?>">		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="tdcolorgray">
		<?php
			if($_REQUEST['emt_id'])
			{
		?>
			<input type="submit" name="td_submit" value="Update" class="red">
			<?php
			}
			else
			{
			?>
			<input type="submit" name="td_submit" value="Create" class="red">
			<?php
			}
			?>		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="tdcolorgray">
			<font color="#ff0000"><?=$alert?></font>		</td>
	</tr>
</table>
<input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
<input type="hidden" name="emt_id" value="<?=$_REQUEST['emt_id']?>">
<input type="hidden" name="section_id" value="<?=$_REQUEST['section_id']?>" />
<input type="hidden" name="form_type" value="<?=$_REQUEST['form_type']?>" />
<input type="hidden" name="start" value="<?=$_REQUEST['start']?>" />
<input type="hidden" name="pg" value="<?=$_REQUEST['pg']?>" />
<input type="hidden" name="search_name" value="<?=$_REQUEST['search_name']?>" />
<input type="hidden" name="records_per_page" value="<?=$_REQUEST['records_per_page']?>" />
<input type="hidden" name="sort_by" id="sort_by" value="<?=$_REQUEST['sort_by']?>" />
<input type="hidden" name="sort_order" id="sort_order" value="<?=$_REQUEST['sort_order']?>" />
</form>
