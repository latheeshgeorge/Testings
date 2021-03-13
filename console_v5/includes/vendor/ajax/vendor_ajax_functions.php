<?PHP
	function show_vendor_maininfo($vendor_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname;

	$sql_vendor="SELECT vendor_name,vendor_address,vendor_telephone,vendor_fax,vendor_email,vendor_website,vendor_hide FROM product_vendors  WHERE vendor_id=".$vendor_id;
$res_vendor= $db->query($sql_vendor);
$row_vendor = $db->fetch_array($res_vendor);
?>
   <div class="editarea_div">

	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<?
		if($alert)
		{
		?>
        <tr>
          <td colspan="5" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		<?
		}
		?> 
		 <tr>
			  <td width="19%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
			  <td colspan="2" align="left" valign="middle" class="tdcolorgray" ></td>
        </tr>
		<tr>
			  <td align="left" valign="middle" class="tdcolorgray" >Vendor Name <span class="redtext">*</span> </td>
			  <td  align="left" valign="middle" class="tdcolorgray">
			  <input class="input" type="text" name="vendor_name" value="<?=$row_vendor['vendor_name']?>"  /> 		  </td>
			   <td width="13%" align="left" valign="middle" class="tdcolorgray">Telephone</td>
		  <td width="21%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="vendor_telephone" value="<?=$row_vendor['vendor_telephone']?>"  /></td>
              <td width="29%" align="left" valign="middle" class="tdcolorgray">&nbsp;
<input name="frmAddCountry" type="button" class="red" id="change_hide" value=" Show Statistics " onclick="document.frmAddCountry.fpurpose.value='statistics'; document.frmAddCountry.vendor_id.value='<?PHP echo $row['vendor_id']; ?>'; document.frmAddCountry.submit();" />
<input type="hidden" name="vendor_id" />
<input type="hidden" name="vendor_edit" value="1" />			 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_VENDOR_STATISTICS_HEADING')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
		</tr>
		  <tr>
			  <td align="left" valign="middle" class="tdcolorgray" >Address</td>
			  <td width="18%" align="left" valign="middle" class="tdcolorgray">
		    <input class="input" type="text" name="vendor_address"  value="<?=$row_vendor['vendor_address']?>" />		  </td>
			  <td align="left" valign="middle" class="tdcolorgray">Fax</td>
			  <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="vendor_fax" value="<?=$row_vendor['vendor_fax']?>"></td>
		      <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
	    </tr>
		  <tr>
			  <td align="left" valign="middle" class="tdcolorgray" >Email<span class="redtext">&nbsp;*</span> </td>
			  <td align="left" valign="middle" class="tdcolorgray">
			  <input class="input" type="text" name="vendor_email" value="<?=$row_vendor['vendor_email']?>"  />		  </td>
			  <td align="left" valign="middle" class="tdcolorgray" >Website</td>
			  <td colspan="3" align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="vendor_website" value="<?=$row_vendor['vendor_website']?>"  /></td>
		  </tr>
		  <tr>
			  <td align="left" valign="middle" class="tdcolorgray" >Hidden</td>
			  <td colspan="2" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="vendor_hide" value="Y" <? if($row_vendor['vendor_hide']=='Y') echo "checked";?>  />
			    Yes
			      <input type="radio" name="vendor_hide" value="N"  <? if($row_vendor['vendor_hide']=='N') echo "checked";?>/>
		      No <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_VENDOR_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
              <td align="left" valign="middle" class="tdcolorgray"></td>
              <td align="left" valign="middle" class="tdcolorgray"></td>
		</tr>
		 
	</table>
	</div>	
	<div class="editarea_div">
      <table  border="0" cellpadding="2" cellspacing="2"  width="100%">	
      <tr>
		  		    <td colspan="2" align="right" valign="middle" class="tdcolorgray"> &nbsp; <input name="Submit" type="submit" class="red" value="Update" /></td>
      </tr>	
      </table>
      </div>										
<?php	
	}

	// ###############################################################################################################
	// 				Function which holds the display logic of products assigned to the page group to be shown when called using ajax;
	// ###############################################################################################################
	function show_product_list($vendor_id,$alert='')
	{
		global $db,$ecom_siteid;
		// Get the list of paroduct assigned for the static page group
	 $sql_products = "select p.product_id,p.product_name,pdvd.map_id FROM
products p,product_vendor_map pdvd
WHERE pdvd.products_product_id=p.product_id  AND pdvd.sites_site_id=$ecom_siteid
AND pdvd.product_vendors_vendor_id=$vendor_id";
		$ret_products = $db->query($sql_products);
	?>
<div class="editarea_div">
<table width="100%" cellpadding="1" cellspacing="1" border="0">
 <tr>
          <td colspan="5" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_VENDOR_ASSIGN') ?></div></td>
        </tr>
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
				
				 // Check whether Products are added to this vendor
			$sql_product_in_vendor = "SELECT products_product_id FROM product_vendor_map
						 WHERE product_vendors_vendor_id=$vendor_id";
			$ret_product_in_vendor = $db->query($sql_product_in_vendor);	
			?>
			 <tr>
          <td colspan="5" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="document.frmAddCountry.fpurpose.value='list_assign_products';document.frmAddCountry.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_ASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_product_in_vendor))
				{
				?>
					<div id="productsunassign_div" class="unassign_div" >
					&nbsp;&nbsp;&nbsp;<input name="products_unassign" type="button" class="red" id="products_unassign" value="Un Assign" onclick="call_ajax_deleteall('product','checkboxproducts[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_STAT_GROUP_UNASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
				<?php
				}				
				?></td>
        </tr>
			
			<?PHP		
						if ($db->num_rows($ret_products))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmAddCountry,\'checkboxproducts[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmAddCountry,\'checkboxproducts[]\')"/>','Slno.','Product Name');
							$header_positions=array('center','center','left','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_products = $db->fetch_array($ret_products))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproducts[]" value="<?php echo $row_products['map_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_products['product_id'];?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_products['product_name']);?></a></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No product Assigned to this Vendor. 
								    <input type="hidden" name="products_norec" id="products_norec" value="1" /></td>
								</tr>
						<?php
						}
						?>	
</table>	
</div>
	<?php	
	}
	// ###############################################################################################################
	
?>
