<?php
	/*#################################################################
	# Script Name 	: list_pricedisplay.php
	# Description 	: Page for listing Product Vendors
	# Coded by 		: SKR
	# Created on	: 22-June-2007
	# Modified by	: Sny
	# Modified On	: 19-Nov-2007
    # Modified by	: LSH
	# Modified On	: 17-Jan-2012
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
$ajax_return_function = 'ajax_return_contents';
include "ajax/ajax.php";
include "includes/delivery_settings/ajax/delivery_ajax_functions.php";
?>
<script language="javascript" type="text/javascript">
function delete_confirmbox(locid,delid)
{
	if (confirm('Are you sure you want to deleted this location'))
	{
		window.location ='home.php?request=delivery_settings&fpurpose=deletelocation&deliveryid='+delid+'&locationid='+locid;
	}	
}
function show_country_list_div(lociddiv,locid,delid)
{
	obj = eval('document.getElementById("'+lociddiv+'")');
	imgobj = eval('document.getElementById("del_img_'+locid+'")');
	if (obj.style.display=='none')
	{
		obj.style.display = '';
		imgobj.src = 'images/down_arr.gif';
		call_ajax_showlist('show_country',locid,delid,0);
	}	
	else
	{
		obj.style.display = 'none';
		imgobj.src = 'images/right_arr.gif';
	}	
}
function call_ajax_showlist(mod,locid,delid,countryid)
{
	var fpurpose	= '';
	var retdivid	= '';
	var moredivid	= '';
	var qrystr	= 'locationid='+locid+'&deliveryid='+delid+'&countryid='+countryid;
	var del_str	= '';
	switch(mod)
	{
		case 'show_country':
			retdivid   	= 'countrylist_div_'+locid;
			fpurpose	= 'ajax_show_country_list';
		break;
		case 'ajax_unassign_country':
			retdivid   	= 'countrylist_div_'+locid;
			fpurpose	= 'ajax_unassign_country';
			var atleastone = false;
			del_str = '';
			frm = document.frm_delivery_list;
			var len  = frm.elements.length;
			for (i=0;i<len;i++)
			{
				if (frm.elements[i].type== "checkbox" && frm.elements[i].name == countryid && frm.elements[i].checked==true) 
				{
					if(del_str!='')
						del_str = del_str + ',';
					del_str = del_str + frm.elements[i].value;
				}
			}
			if(del_str=='')
			{
				alert('Please select the countries to be unassigned')
				return false;
			}
			else
			{
				if(confirm('Are you sure you want to unassign the selected countries from current delivery location?'))
				{
					
				}
				else
					return false;
			}
		break;
	};
	document.getElementById('retdiv_id').value 	= retdivid;/* Name of div to show the result */	
	retobj 						= eval("document.getElementById('"+retdivid+"')");
	retobj.innerHTML 				= '<center><img src="images/loading.gif" alt="Loading"></center>';															
	/* Calling the ajax function */
	qrystr = qrystr + '&unassign_id='+del_str;
	Handlewith_Ajax('services/delivery_settings.php','fpurpose='+fpurpose+'&'+qrystr);
}
function ajax_return_contents() 
{
	var ret_val = '';
	var disp 	= 'no';
	if(req.readyState==4)
	{
		if(req.status==200)
		{
			ret_val 	= req.responseText;
			targetdiv 	= document.getElementById('retdiv_id').value;
			targetobj 	= eval("document.getElementById('"+targetdiv+"')");
			targetobj.innerHTML = ret_val; /* Setting the output to required div */
			hide_processing();
		}
		else
		{
			 show_request_alert(req.status);
		}
	}
}
function country_unassign(checkboxid,locid,delid)
{
	call_ajax_showlist('ajax_unassign_country',locid,delid,checkboxid);
}
function select_all_chk(id)
{
	frm = document.frm_delivery_list;
	var len  = frm.elements.length;
	for (i=0;i<len;i++)
	{
		if (frm.elements[i].type== "checkbox" && frm.elements[i].name == id) 
		{
			if (frm.elements[i].checked==false)
				frm.elements[i].checked = true;
		}
	}

}
function select_none_chk(id)
{
	frm = document.frm_delivery_list;
	var len  = frm.elements.length;
	for (i=0;i<len;i++)
	{
		if (frm.elements[i].type== "checkbox" && frm.elements[i].name == id) 
		{
			if (frm.elements[i].checked==true)
				frm.elements[i].checked = false;
		}
	}

}
</script>
<form method="post" action="" id="frm_delivery_list" name="frm_delivery_list">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=delivery_settings"> Delivery Charges </a><span> '<? echo $rowname['deliverymethod_name'];?>'</span></div></td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<?
		if($_REQUEST['delete']==1){
		 $alert= "Delivery Location Deleted Successfully";
		  ?>
		<tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert ?></td>
        </tr> 
		
		<?
		}
		?>
		<tr>
          <td colspan="4" align="center" valign="middle">
		<div class="editarea_div">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Delivery Method:</b></td>
        </tr>
		<tr>
		<td colspan="4" align="left" valign="middle"  class="sorttd">
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
		<tr>
			<td colspan="2" class="treemenutd"><b>Location</b>&nbsp;&nbsp;&nbsp;&nbsp;<b><u><a href="home.php?request=delivery_settings&fpurpose=editdelivery&deliveryid=<? echo $delivery_id;?>" onclick="show_processing();"><font color="#CC0000">(Please Click here to add more Locations )</font></a></u></b><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_DELIVERY_SETTINGS_LOCATION_ADD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
			<? while($rowlocation = $db->fetch_array($reslocation)) {

				if($_REQUEST['location_id']==$rowlocation['location_id'])
				{
					$display = '';
					$arrow_img = 'down_arr.gif';
				}
				else
				{
					$display = 'none';
					$arrow_img = 'right_arr.gif';
				}	
				
			?>
		<tr>
			<td class="tdcolorgray" width="20%"><? echo $rowlocation['location_name']?></td>
			<td class="tdcolorgray"><a class="edittextlink" href="home.php?request=delivery_settings&fpurpose=editdelivery&deliveryid=<?=$rowlocation['delivery_methods_deliverymethod_id']?>&locationid=<? echo $rowlocation['location_id'];?>" onclick="show_processing()">Edit</a> | <a class="edittextlink" href="#" onclick="delete_confirmbox('<?php echo $rowlocation['location_id']?>','<?=$rowlocation['delivery_methods_deliverymethod_id']?>')">Delete</a>
			<?php
			if($ecom_site_delivery_location_country_map==1)
			{
			?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="edittextlink" href="javascript:show_country_list_div('<?php echo 'countrylist_'.$rowlocation['location_id'];?>','<?php echo $rowlocation['location_id']?>','<?php echo $_REQUEST['deliveryid']?>')">Countries <img src="images/<?php echo $arrow_img?>" border="0" id="del_img_<?php echo $rowlocation['location_id']?>"/></a>
			<?php
			}
			?>
			</td>
		</tr>
		<?php
			if($ecom_site_delivery_location_country_map==1)
			{
				
			?>
				<tr id="countrylist_<? echo $rowlocation['location_id'];?>" style="display:<?php echo $display?>">
				<td class="tdcolorgray" colspan="4">
				<div id="countrylist_div_<? echo $rowlocation['location_id'];?>">
				<?php
				if($display=='')
					show_country_list($rowlocation['location_id'],$rowlocation['delivery_methods_deliverymethod_id'],'');
				?>
				</div>
				
				</td>
				</tr>
			<?php
			}
			?>
		<? } ?>
		</table>
		<input type="hidden" name='retdiv_id' id='retdiv_id' value=''>
		</td>
	</tr>
	</table>
	</div>
	</td>
	</tr>
</table>  
</form>	
