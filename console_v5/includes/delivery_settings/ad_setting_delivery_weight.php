<?php

	/*#################################################################
	# Script Name 	: list_pricedisplay.php
	# Description 	: Page for listing Product Vendors
	# Coded by 		: SKR
	# Created on	: 22-June-2007
	# Modified by	: Sny
	# Modified On	: 12-Nov-2007
	#################################################################*/
//Define constants for this page
$table_name		= 'delivery_site_option_details';
$page_type		= 'Delivery Charges';
$help_msg 			= get_help_messages('ADD_SETTINGS_DELIVERY_WEIGHT_MESS1');
$delivery_id	= $_REQUEST['deliveryid'];
$alert 			= "Details Saved Successfully";

$sqlname		= "SELECT * FROM delivery_methods where deliverymethod_id=".$_REQUEST['deliveryid'] ;
$resname		= $db->query($sqlname);
$rowname 		= $db->fetch_array($resname);

// Check whether any delivery methods groups exists for this site
$group_exists	= false;
$sql_check 		= "SELECT delivery_group_id,delivery_group_name FROM general_settings_site_delivery_group WHERE sites_site_id=$ecom_siteid AND delivery_group_hidden=0";
$ret_check 		= $db->query($sql_check);
if($db->num_rows($ret_check))
{
	$group_exists = true;
}
else
{
	$sql 		= "SELECT * FROM $table_name where delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " AND sites_site_id =". $ecom_siteid." ORDER BY delopt_option";
	$res 		= $db->query($sql);
}	

	$gensql = "SELECT delivery_settings_weight_min_limit, delivery_settings_weight_max_limit, delivery_settings_weight_increment,
					  delivery_settings_common_min, delivery_settings_common_max, delivery_settings_common_increment,unit_of_weight 
					   FROM general_settings_sites_common 
							WHERE sites_site_id='".$ecom_siteid."'";
	$genres = $db->query($gensql);
	$gennum = $db->num_rows($genres);
	$genrow = $db->fetch_array($genres);
	if($gennum==0)
	{
		$errmsg	 =  " Please Set Delivery Settings. <br/> Please <a href='home.php?request=general_settings&fpurpose=settings_default'> Click here </a> to go to General Settings  ";
	}
	else
	{
		$wgmin 		= $genrow['delivery_settings_weight_min_limit'];
		$wgmax 	= $genrow['delivery_settings_weight_max_limit'];
		$wginc 		= $genrow['delivery_settings_weight_increment'];
		
		$othmin 	= $genrow['delivery_settings_common_min'];
		$othmax 	= $genrow['delivery_settings_common_max'];
		$othinc 		= $genrow['delivery_settings_common_increment'];	
		$unit			= $genrow['unit_of_weight'];			
	} 
   /* Building the option values to be shown in both the first and second drop down boxes */	
  $main_html 		= Build_main_delivery_dropdown_html();
  //$sub_html		= Build_sub_delivery_dropdown_html();
?>
<script type="text/javascript">
function valform(require)
{
var require;
 for(var i=0; i<document.frmlistDelivery.elements.length;i++)
 {  
  if(document.frmlistDelivery.elements[i].name.substr(0,5)=='price')
  { 
   	if (document.frmlistDelivery.elements[i].value<0)
			{
				alert('Price should be a Positive value');
				document.frmlistDelivery.elements[i].focus();
				return false;
			}
  }
  
 }
 if(require=='more')
 {
 document.forms.frmlistDelivery.more_req.value=1;
 }
 show_processing();document.frmlistDelivery.fpurpose.value='deliveryData';document.forms.frmlistDelivery.submit();

}
</script>
<form name="frmlistDelivery" action="home.php?request=delivery_settings" method="post" >	
<input type="hidden" name="fpurpose" value="deliveryData">
<input type="hidden" name="deliveryid" value="<?php echo $delivery_id;?>">
<input type="hidden" name="type1" value="Weight">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><a href="home.php?request=delivery_settings"> Delivery Charges</a><span> '<? echo $rowname['deliverymethod_name'];?>'</span></div>
 
</td>
        </tr>
       <tr>
		  <td align="left" valign="middle" class="helpmsgtd_main" colspan="4">
		  <?php 
			  Display_Main_Help_msg($help_arr,$help_msg);
		  ?>
		 </td>
		</tr>
		<? if($_REQUEST['alert']){?>
		 <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert ?></td>
        </tr>
		<? }?>
        
		  <!-- <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Delivery Method:</b></td>
         
        </tr>-->
		<?php
			if($group_exists==false) // case if delivery groups does not exists
			{
		?>
		<tr>
				<td colspan="4" align="left" valign="middle"  >
				<div class="editarea_div">
				<table width="678" border="0" cellpadding="4" cellspacing="0">
				<tr>
				<td colspan="4" align="left" valign="middle" class="sorttd" >
				<table width="678" border="0" cellpadding="4" cellspacing="0">
				<tr>
				  <td align="center" width="700"><b>Option</b></td>
				  <td align="center" width="300"><b>Charges (<?php echo  display_curr_symbol()?>)</b></td>
				</tr>
  <?
  
  $k=0;
  while($row=$db->fetch_array($res))
  {
   	$option=$row['delopt_option'];
	//list($big,$small) = split('[.]',$option);
	$big = $option;
	$k++;
   if($k==1)
   {
   	$msg='More than zero but less than ';
   }
   else
   {
    $msg='More than previous but less than';
	}
		
   ?>
   <tr>
  <td align="right" width="700"> <? echo $msg; ?> 
   <select name="del_optionbig[]" class="input">
    <option value=''></option>
	<? /*for($i=$othmin ;$i<=$othmax; $i+=$othinc){ ?>  
	<option value="<? echo $i; ?>"<? if($big==$i) echo "selected"; ?>><? echo $i; ?></option>
	<? }*/
		Build_main_delivery_dropdown($big,$main_html);
	?>
	</select>
	<?php /*?>&nbsp;.&nbsp;
	<select name="del_optionsmall[]" class="input">
		<option value=''></option><?php */?>
		<?
		/* for($j=$wgmin ;$j<$wgmax; $j+=$wginc){if($j<10)
		{
		$test = "0".$j;
		}
		else
		{
		$test= $j;
		}
		?>
		<option value="<? echo $test; ?>" <? if($small==$j) echo "selected"; ?>><? echo $test; ?></option>
		<? }*/
		//Build_sub_delivery_dropdown($small,$sub_html);
		?>
		<?php /*?></select> <?php */?>
<?php echo $unit	?>
	
      </td>
      <td align="center" width="300"> 
	<input name="price[]" class="input" type="text" size="5" value="<? echo $row['delopt_price']?>">
      </td>
    </tr> 
  <?
  }
   
   for($j=0; $j<5; $j++){
   if($j==0 && $k==0){
   $msg='More than zero but less than ';
   }else{
    $msg='More than previous but less than';}
    ?>
  <tr>
  <td align="right" width="700"> <? echo $msg?>
   <select name="del_optionbig[]" class="input"> 
    <option value=''></option>
	<? /*for($i=$othmin ;$i<=$othmax; $i+=$othinc){?>
	<option value="<? echo $i; ?>"><? echo $i; ?></option>
	<? }*/
	Build_main_delivery_dropdown('',$main_html);
	?>
	</select>
	<?php /*?>&nbsp;.&nbsp;
	<select name="del_optionsmall[]" class="input">
		<option value=''></option><?php */?>
		<? /*for($i=$wgmin ;$i<$wgmax; $i+=$wginc){if($i<10)
		{
		$test = "0".$i;
		}
		else
		{
		$test= $i;
		}
		
		?>
		<option value="<? echo $test; ?>"><? echo $test; ?></option>
		<? }*/
		//Build_sub_delivery_dropdown('',$sub_html);
		?>
		<?php /*?></select> <?php */?>
	<?php echo $unit	?>
	
      </td>
      <td align="center" width="300"> 
	<input name="price[]" class="input" type="text" size="5">
      </td>
    </tr>
	<? } ?>
    </table>
   
		</td>
		</tr>
		</table>
		</div>
		</td>
		</tr>
		<tr id="largebuttonlink">
				<td  align="left" valign="middle"  colspan="4" >
				<div class="editarea_div">
				<table  border="0" cellpadding="0" cellspacing="0" width="100%">
 	 <tr >
		<td colspan="2" align="right" width="48%"><input type="button" value="Submit Rates" class="red"  onClick="return valform()"></td>
		<td  colspan="2" align="right">&nbsp;
		  <input type="button" value="Submit and enter more rates" class="red"  onClick="return valform('more')"></td>
	</tr>
	</table></div>
		</td>
		</tr>
		<?php
			}
			else // case if delivery groups exists
			{
				?>
				<tr>
				<td  align="left" valign="middle"  colspan="4" >
				<div class="editarea_div">
				<table  border="0" cellpadding="4" cellspacing="0" width="100%">
				<?php
				while ($row_check = $db->fetch_array($ret_check))
				{
		?>		<tr>
					<td colspan="4" align="left" valign="top" >	
		<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
					<td colspan="4" align="left" valign="middle" class="seperationtd" >
					<?php echo stripslashes($row_check['delivery_group_name'])?>
					</td>
					</tr>
					<tr>
					<td colspan="4" align="left" valign="middle" class="sorttd" >
					<table width="678" border="0" cellpadding="4" cellspacing="0">
					<tr>
					  <td align="center" width="700"><b>Option</b></td>
					  <td align="center" width="300"><b>Charges (<?php echo  display_curr_symbol()?>)</b></td>
					</tr>
  <?
					$k					= 0;
					$sql 				= "SELECT * FROM $table_name where delivery_group_id=".$row_check['delivery_group_id']." AND delivery_methods_deliverymethod_id=".$_REQUEST['deliveryid'] . " AND sites_site_id =". $ecom_siteid." ORDER BY delopt_option";
					$res 				= $db->query($sql);
				  while($row=$db->fetch_array($res))
				  {
					   	$option 			= $row['delopt_option'];
						//list($big,$small) 	= split('[.]',$option);
						$big = $option;
					   	$k++;
					   	if($k==1)
					   	{
							$msg='More than zero but less than ';
					   	}
					   	else
					   	{
							$msg='More than previous but less than';
						}
						
				   ?>
					   <tr>
					  <td align="right"> <? echo $msg; ?>  
				   <select name="del_optionbig_<?php echo $row_check['delivery_group_id']?>[]" class="input">
					<option value=''></option>
					<? 
						Build_main_delivery_dropdown($big,$main_html);
						/*for($i=$othmin ;$i<=$othmax; $i+=$othinc){ ?>
					<option value="<? echo $i; ?>"<? if($big==$i) echo "selected"; ?>><? echo $i; ?></option>
					<? }*/?>
					</select>
					<?php /*?>&nbsp;.&nbsp;
					<select name="del_optionsmall_<?php echo $row_check['delivery_group_id']?>[]" class="input">
						<option value=''></option><?php */?>
						<? 
						/*for($j=$wgmin ;$j<$wgmax; $j+=$wginc){if($j<10)
						{
						$test = "0".$j;
						}
						else
						{
						$test= $j;
						}
						?>
						<option value="<? echo $test; ?>" <? if($small==$j) echo "selected"; ?>><? echo $test; ?></option>
						<? }*/
						//Build_sub_delivery_dropdown($small,$sub_html);
						?>
						<?php /*?></select> <?php */?>
					<?php echo $unit	?>
					
					  </td>
					  <td align="center"> 
					<input name="price_<?php echo $row_check['delivery_group_id']?>[]" class="input" type="text" size="5" value="<? echo $row['delopt_price']?>">
					  </td>
					</tr> 
				  <?
				  }
				   for($j=0; $j<5; $j++)
				   {
					   	if($j==0 && $k==0)
						{
						   	$msg='More than zero but less than ';
					  	}
						else
						{
							$msg='More than previous but less than';
						}
					?>
				  <tr>
				  <td align="right"> <? echo $msg?>
				   <select name="del_optionbig_<?php echo $row_check['delivery_group_id']?>[]" class="input">
					<option value=''></option>
					<? 
					/*for($i=$othmin ;$i<=$othmax; $i+=$othinc)
					{
					?>
						<option value="<? echo $i; ?>"><? echo $i; ?></option>
					<?
					}*/
					Build_main_delivery_dropdown('',$main_html);
					?>
					</select>
					<?php /*?>&nbsp;.&nbsp;
					<select name="del_optionsmall_<?php echo $row_check['delivery_group_id']?>[]" class="input">
						<option value=''></option><?php */?>
						<? /*for($i=$wgmin ;$i<$wgmax; $i+=$wginc){
						if($i<10)  
						{
						$test = "0".$i;
						}
						else
						{
						$test= $i;
						}
						
						?>
						<option value="<? echo $test; ?>"><? echo $test; ?></option>
						<? }*/
					//	Build_sub_delivery_dropdown('',$sub_html);
						?>
						<?php /*?></select> <?php */?>
					<?php echo $unit	?>
					
					  </td>
					  <td align="center"> 
					<input name="price_<?php echo $row_check['delivery_group_id']?>[]" class="input" type="text" size="5">
					  </td>
					</tr>
					<? 
				   } 
?>
					</table>
				</td>
				</tr>
				</table>
				
				</td>
				</tr>
<?php  				   
				}	// row_check
?>
</table>
</div>
</td>
</tr>
    <tr id="largebuttonlink">
				<td  align="left" valign="middle"  colspan="4" >
				<div class="editarea_div">
				<table  border="0" cellpadding="0" cellspacing="0" width="100%">
 	<tr>
		<td colspan="2" align="right" width="48%">&nbsp;<input type="button" value="Submit Rates" class="red"  onClick="return valform()"></td>
		<td  colspan="2" align="right">&nbsp;
		  <input type="button" value="Submit and enter more rates" class="red"  onClick="return valform('more')"></td>
	</tr>
	</table>
	</div>
	</td>
	</tr>
	
				
		<?php	
			}
		?>	
		   
      </table>
	  <input type="hidden" name="more_req" value="" />
</form>
<script type="text/javascript">
hide_processing();
</script>
