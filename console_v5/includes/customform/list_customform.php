<script language="javascript">
function form_delete(section_id) {
	if(confirm("Are you Sure?")) {
		show_processing();
		location.href='home.php?request=customform&form_type=<?=$_REQUEST['form_type']?>&delete_id='+section_id;
	}
}

function activate_fn(section_id) {
	show_processing();
	if(document.getElementById("cb_"+section_id).checked == true) {
		location.href='home.php?request=customform&form_type=<?=$_REQUEST['form_type']?>&activate_status=yes&activate_id='+section_id;
	} else {
		location.href='home.php?request=customform&form_type=<?=$_REQUEST['form_type']?>&activate_status=no&activate_id='+section_id;
	}
}
</script>
<?
if($_REQUEST['activate_status'] == 'yes' && $_REQUEST['activate_id']) {
	$db->query("UPDATE element_sections SET activate=1 WHERE site_id=$ecom_siteid AND section_id=".$_REQUEST['activate_id']);
}
if($_REQUEST['activate_status'] == 'no' && $_REQUEST['activate_id']) {
	$db->query("UPDATE element_sections SET activate=0 WHERE site_id=$ecom_siteid AND section_id=".$_REQUEST['activate_id']);
}
if($_REQUEST['delete_id']) {
	$sql = "SELECT count(*) as cnt FROM element_sections WHERE section_id=".$_REQUEST['delete_id']." and site_id=$ecom_siteid";
	$res = $db->query($sql);
	list($count) = $db->fetch_array($res);
	if($count > 0) {
		$sql = "SELECT element_id FROM elements WHERE site_id=$ecom_siteid AND section_id=".$_REQUEST['delete_id'];
		$res = $db->query($sql);
		while($row = $db->fetch_array($res)) {
			$db->query("DELETE from element_value WHERE element_id=".$row['element_id']);
		}
		$db->query("DELETE FROM elements WHERE section_id=".$_REQUEST['delete_id']);
		$db->query("DELETE FROM element_sections WHERE section_id=".$_REQUEST['delete_id']);
	}
}
$table_headers = array('Section Name','Sort Order','Activate','Action');
$header_positions=array('left','left','left','left');
?>
<table width="100%" border="0" cellspacing="0" cellpadding="10" height="100%">
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td><b><?=$section_type_array[$_REQUEST['form_type']]?>&nbsp; <font size="3">&raquo;</font> Sections</b></td>
				</tr>
				<tr>
					<td><img src="images/blueline.gif" alt="" height="1" width="400" border="0"></td>
				</tr>
				<tr>
				<td class="maininnertabletd3">You can create various sections in the <?=$section_type_array[$_REQUEST['form_type']]?>. Each section will have a section name and you can add the flelds that has to appear under that section. After adding a section you can click on the Manage Form link to add/edit/delete the fields under it. By default the added section will be deactivated. Upon completion of the form creation for the section you can activate the particular section.</td>
				</tr>
			</table>
			<br>
			<table width="100%">
			<tr><td valign="top" width="50%">
			
				<table border="0" cellspacing="0" cellpadding="4" width="100%">
					
					<?php
					echo table_header($table_headers,$header_positions);
					$sql = "SELECT section_id,section_name,activate,sort_no FROM element_sections WHERE site_id=$ecom_siteid AND section_type='".$_REQUEST['form_type']."'";
					$res = $db->query($sql);
					while($row = $db->fetch_array($res)) {
					$count_no++;
		if($count_no %2 == 0)
			$class_val="maininnertabletd1";
		else
			$class_val="maininnertabletd2";	
						?>
						<tr class="<?=$class_val;?>">
							<td width="93" align="center">
							<a href="home.php?request=customform&fpurpose=formcreation_section_edit&section_id=<?=$row['section_id']?>&form_type=<?=$_REQUEST['form_type']?>" onclick="show_processing();"><?=$row['section_name'];?></a>
							</td><td align="center"><?=$row['sort_no']?></td><td align="center"><input type="checkbox" id="cb_<?=$row['section_id']?>" onclick="activate_fn(<?=$row['section_id']?>);" <? if($row['activate'] == 1) echo 'checked'; ?> /></td><td align="center"><a href="home.php?request=customform&fpurpose=formcreation_section_edit&section_id=<?=$row['section_id']?>&form_type=<?=$_REQUEST['form_type']?>" onclick="show_processing();">  Edit  </a>|<a href="home.php?request=customform&fpurpose=formcreation&section_id=<?=$row['section_id']?>&form_type=<?=$_REQUEST['form_type']?>" onclick="show_processing();">  Manage Form  </a>|<a href="#" onclick="form_delete(<?=$row['section_id']?>);">  Delete</a></td>
						</tr>
						<?
					}
					if($db->num_rows($res)==0)
					{
					?>
						<tr>
						<td  colspan="4" class="redtext" align="center">No Sections found.</td>
						</tr>
					<?php
					}
					?>
					<tr>
						<td colspan="4" align="center"><input type="button" class="bigsubmit" onclick="show_processing();location.href='home.php?request=customform&fpurpose=formcreation_section&form_type=<?=$_REQUEST['form_type']?>';" value="Add New Section"></td>
					</tr>
				</table>
			
			</td></tr>
			</table>
		</td>
	</tr>
</table>
