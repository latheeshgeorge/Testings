<?php
	/*#################################################################
	# Script Name 	: list_pricedisplay.php
	# Description 	: Page for listing Product Vendors
	# Coded by 		: SKR
	# Created on	: 22-June-2007
	# Modified by	: Sny
	# Modified On	: 19-Nov-2007
	#################################################################*/
//Define constants for this page
$table_name		= 'delivery_site_option_details';
$page_type		= 'Delivery Charges';
$help_msg 			= get_help_messages('ADD_SETTINGS_DELIVERY_EDIT_LOCATION_MESS1');
$delivery_id	= $_REQUEST['deliveryid'];
$sqllocation	= "SELECT * FROM delivery_site_location 
						WHERE delivery_methods_deliverymethod_id=".$delivery_id." AND sites_site_id=$ecom_siteid";
$reslocation 	= $db->query($sqllocation);
/*if($db->num_rows($reslocation)==0) { echo " <font color='red'> You Are Not Authorised  </a>"; exit; }
*/
$sqlname		= "SELECT * FROM delivery_methods where deliverymethod_id=".$_REQUEST['deliveryid'] ;
$resname		= $db->query($sqlname);
$rowname 		= $db->fetch_array($resname);
?>
<script language="javascript" type="text/javascript">
function delete_confirmbox(locid,delid)
{
	if (confirm('Are you sure you want to deleted this location'))
	{
		window.location ='home.php?request=delivery_settings&fpurpose=deletelocation&deliveryid='+delid+'&locationid='+locid;
	}	
}
</script>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><a href="home.php?request=delivery_settings"> Delivery Charges </a>&gt;&gt; '<? echo $rowname['deliverymethod_name'];?>'
 
</td>
        </tr>
        <tr>
          <td colspan="4" align="left" valign="middle" class="helpmsgtd" ><?=$help_msg ?></td>
        </tr>
		<?
		if($_REQUEST['delete']){
		 $alert= "Delivery Id ". $delivery_id . " Is Deleted";
		  ?>
		<tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert ?></td>
        </tr> 
		
		<?
		}
		?>
		
          <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Delivery Method:</b></td>
         
        </tr>
		<tr>
		<td colspan="4" align="left" valign="middle"  >
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" class="treemenutd"><b>Location</b>&nbsp;&nbsp;&nbsp;&nbsp;<b><u><a href="home.php?request=delivery_settings&fpurpose=editdelivery&deliveryid=<? echo $delivery_id;?>" onclick="show_processing();"><font color="#CC0000">(Please Click here to add more Locations & amount spent )</font></a></u></b><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_DELIVERY_SETTINGS_LOCATION_ADD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>

	</tr>
			<? while($rowlocation = $db->fetch_array($reslocation)) {?>
			<tr>
			<td class="tdcolorgray" width="20%"><? echo $rowlocation['location_name']?></td>
			<td class="tdcolorgray"><a class="edittextlink" href="home.php?request=delivery_settings&fpurpose=editdelivery&deliveryid=<?=$rowlocation['delivery_methods_deliverymethod_id']?>&locationid=<? echo $rowlocation['location_id'];?>" onclick="show_processing()">Edit</a> | <a class="edittextlink" href="#" onclick="delete_confirmbox('<?php echo $rowlocation['location_id']?>','<?=$rowlocation['delivery_methods_deliverymethod_id']?>')">Delete</a></td>
		</tr>
		<? } ?>
		</table>
		</td>
	</tr>
</table>  
		</td>
		</tr>

		 <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
         
        </tr>
		  <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" >&nbsp;</td>
         
        </tr>
		
		   
      </table>
