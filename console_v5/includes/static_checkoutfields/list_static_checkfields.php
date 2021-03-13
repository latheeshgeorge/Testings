<?php
	
	/*#################################################################
	# Script Name 	: list_static_checkfields.php
	# Description 	: Page to manage the static checkout fields
	# Coded by 		: LSH
	# Created on	: 07-Apr-2008
	# Modified by	: LSH
	# Modified On	: 
	#################################################################*/

//#Define constants for this page
$page_type 		= 'Static Fields';
$help_msg 			= get_help_messages('LIST_STATIC_FIELD_MESS1');
$boxperrow		= 5;
$table_name 		= 'general_settings_site_checkoutfields';
if($_REQUEST['field_type'])	
$keytype		= $_REQUEST['field_type'];
else
$keytype = 'PERSONAL';
if($keytype == 'PERSONAL' || $keytype == 'DELIVERY' || $keytype == 'VOUCHER' || $keytype == 'CUSTREG' || $keytype == 'CUSTREG_COMPANY'){
$table_headers = array('Field Name','Field Order','Compulsory?','Error Message');
$header_positions=array('left','left','center','center');
$colspan = count($table_headers);
}
else
{ 
$table_headers = array('Field Name','Field Order','Error Message');
$header_positions=array('left','left','center');
$colspan = count($table_headers);
}
if($keytype)
{
	    $sql_details = "SELECT field_order,field_error_msg,field_name,field_det_id,field_req FROM general_settings_site_checkoutfields WHERE field_type='$keytype' AND sites_site_id=$ecom_siteid ORDER BY field_order ASC";
		//echo $sql_details;
		$ret_details = $db->query($sql_details);
}
?>

<script language="javascript" type="text/javascript">
function handle_typechange()
	{
		show_processing();
		document.frmcheckfields.retain_val.value 	= '<?php echo $keytype?>';
		document.frmcheckfields.type_change.value 	= 1;
		document.frmcheckfields.pg.value 	= 0;
		document.frmcheckfields.submit();
	}
	function save_details()
	{
		show_processing();
	   document.frmcheckfields.fpurpose.value 	= 'save_order';
		document.frmcheckfields.submit();
	}
</script>

<form method="post" action="home.php?request=settings_static_checkfields" name="frmcheckfields">
<input type="hidden" name="fpurpose" value="" />
<input type="hidden" name="pg" value="<?php echo $_REQUEST['pg']?>" />
<input type="hidden" name="type_change" value="" />
<input type="hidden" name="retain_val" value="" />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	  <td valign="middle" align="left" class="treemenutd"><div class="treemenutd_div"><span> <?=$page_type?>
    </b><br />
    <img src="images/blueline.gif" alt="" border="0" height="1" width="400" /></span></span></td>
    </tr>
	 	<tr>
     <td colspan="2" align="left" valign="middle" class="helpmsgtd_main" >
	  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
	 </td>
  </tr>
  
  <tr>
    <td colspan="2"  align="center" valign="top" >
	<div class="sorttd_div" >
	<table width="100%" border="0" cellpadding="0" cellspacing="0">

      <tr>
        <td  align="left" valign="middle" class="seperationtd"  colspan="3">&nbsp;<strong>Select type</strong>          <?php
		  $sql_type = "SELECT DISTINCT field_type FROM general_settings_site_checkoutfields WHERE sites_site_id =".$ecom_siteid."";
		// echo $sql_type;
		  $ret_type = $db->query($sql_type);
		  $keytype_arr = array();
		  while($row_type = $db->fetch_array($ret_type))
		  {
		    	if($row_type['field_type']=='CUSTREG')
					$caption = 'Customer Registration';
				elseif($row_type['field_type']=='CUSTREG_COMPANY')
					$caption = 'Customer Registration Company';	
				else
					$caption = ucwords(strtolower($row_type['field_type']));
			$keytype_arr[$row_type['field_type']] = $caption;
		  }
/*		  $keytype_arr = array('home'=>'Home','cat'=>'Categories','prod'=>'Products','shop'=>'Shops','combo'=>'Combo Deals','shelf'=>'Shelves','bestsellers'=>'Best Sellers','stat'=>'Static Pages','saved'=>'Saved Search');
*/		  echo generateselectbox('field_type',$keytype_arr,$_REQUEST['field_type'],'','handle_typechange()');
	  ?>        &nbsp;<a href="#" style="cursor:pointer;" onmouseover="return ddrivetip('<?=get_help_messages('LIST_STATIC_CHECK_TYPE')?>');" onmouseout="return hideddrivetip();"><img src="images/helpicon.png" border="0" alt="" /></a></td>
	  </tr>
      
      <tr>
        <td align="left" valign="middle" class="listeditd" >&nbsp;Assign order and error message for "
		<?php 
			if($keytype=='CUSTREG')
				$caption = 'Customer Registration';
			elseif($keytype=='CUSTREG_COMPANY')
				$caption = 'Customer Registration Company';	
			else
				$caption = ucwords(strtolower($keytype));
			echo $caption;?>"		</td>
        </tr>
	  <?php
	  if($alert)
	  {
	  ?>
	   <tr>
        <td align="center" valign="middle" class="errormsg" ><? echo $alert;?></td>
      </tr>
	  <? 
	  }
	  ?>
	<tr>
					  <td valign="middle" align="left"  width="100%" >
					  <table width="100%" border="0" cellspacing="0" cellpadding="2">
					  <? 
					  echo table_header($table_headers,$header_positions); 
					  ?>
					  <?
					  while($row_details = $db->fetch_array($ret_details))
					  {?>
					  <tr>
					     <td class="listingtablestyleA"  ><?=$row_details['field_name']?></td><td class="listingtablestyleA"  ><input type="text" size="3" name="field_order~<?=$row_details['field_det_id']?>" id="field_order~<?=$row_details['field_det_id']?>" value="<?=$row_details['field_order']?>" /></td>
						 <? if($keytype == 'PERSONAL' || $keytype == 'DELIVERY' || $keytype == 'VOUCHER' || $keytype == 'CUSTREG' || $keytype == 'CUSTREG_COMPANY'){?>
						   <td class="listingtablestyleA"  align="center"><input type="checkbox" name="required~<?=$row_details['field_det_id']?>" id="required~<?=$row_details['field_det_id']?>" value="1" <? if($row_details['field_req']==1) echo "checked"; else echo ''; ?> />
						   <input type="hidden" name="checkbox_req" value="1" id="checkbox_req" />						   </td>
						 <? }?>
						 <td class="listingtablestyleA"  align="center"><input type="text" name="field_error_msg~<?=$row_details['field_det_id']?>" id="field_error_msg~<?=$row_details['field_det_id']?>" value="<?=$row_details['field_error_msg']?>"  size="45"/>						 </td>
					  </tr>
					  <? }?>
					  </table>					  </td>
	</tr>				  
	<tr>
  <td valign="middle" align="right" >
	<input type="submit" name="fieldSubmit" value="Save Changes" class="red" onclick="save_details();" />  </td>
 </tr> 
    <tr>
	  <td valign="middle" align="center">&nbsp;</td>
  	</tr>
<?php 
  $query_string .= "&request=se_keyword&cbo_keytype=".$keytype;
  
?>
</table>
	</div>
</td>
</tr>
</table>
</form>
