<?PHP
	function bestseller_maininfo($alert)
	{
	global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname;
	
	$sql 				= "SELECT * FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
	$res_admin 			= $db->query($sql);
	$fetch_arr_admin 	= $db->fetch_array($res_admin);
	
	$sql 				= "SELECT * FROM general_settings_sites_common_onoff WHERE sites_site_id=".$ecom_siteid;
	$res_admin 			= $db->query($sql);
	$fetch_arr_admin_1 	= $db->fetch_array($res_admin);
	
	// This is done to move the fields and its value from general_settings_sites_common_onoff table to the same array which have values from general_settings_sites_common table	
	foreach ($fetch_arr_admin_1 as $k=>$v)
	{
		$fetch_arr_admin[$k]=$v;	
	}
?><div class="editarea_div">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<?php
			if ($alert)
			{
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" id="mainerror_tr" ><?php echo $alert; ?></td>
          </tr>
		 <?php
		 	}
		 ?> 
		
		<tr>
          <td align="left" valign="middle" class="tdcolorgray" ><input name="best_seller_picktype" type="radio" value="0" <?php echo (!$fetch_arr_admin['best_seller_picktype'])?'checked="checked"':''?> />
            Pick Automatically 
              <input name="best_seller_picktype" type="radio" value="1" <?php echo ($fetch_arr_admin['best_seller_picktype'])?'checked="checked"':''?> />
Pick Manually <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_LISTTYPE_BEST_SELLERS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          </tr>
		<tr >
		  <td  align="center" valign="top" class="tdcolorgray" id="bestseller_maintr" >&nbsp;</td>
		  </tr>
		<tr >
		  <td  align="center" valign="top" class="tdcolorgray" id="bestseller_maintr" ><input name="Submit" type="submit" class="red" value="Save Settings" /></td>
		  </tr>
	</table></div>	  
<?php
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of products under the best seller when called using ajax;
	// ###############################################################################################################
	function show_product_bestseller_list($alert='')
	{
		global $db,$ecom_siteid ;
		
		  // Get the list of products under current category group
		  $sql_product = "SELECT b.bestsel_id,a.product_id,a.product_name,b.bestsel_hidden,b.bestsel_sortorder FROM products a,
						general_settings_site_bestseller b WHERE a.sites_site_id=$ecom_siteid AND 
						b.sites_site_id=$ecom_siteid AND 
						a.product_id=b.products_product_id ORDER BY b.bestsel_sortorder ASC";
		 
		  $ret_product = $db->query($sql_product);
		  if($_REQUEST['pick_type']==0)
			{
			?><div class="editarea_div">
				<table width="100%" cellpadding="0" cellspacing="1" border="0">
					<tr>
						<td colspan="4" align="center">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_PROD_BESTSELLER_MSG')?></td>
					</tr>
				</table>
				</div>			
			<?php	
				return;			
			}
			?><div class="editarea_div">
		  <table width="100%" cellpadding="0" cellspacing="1" border="0">
		   <tr>
          <td colspan="5" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('BESTSELLER_PROD_MESS1') ?></div></td>
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
				?>
				 <?
		  // Get the list of products picked for best seller
		  $sql_products_in_bestseller = "SELECT products_product_id FROM general_settings_site_bestseller WHERE  
										sites_site_id=$ecom_siteid LIMIT 1";
		  $ret_products_in_bestseller = $db->query($sql_products_in_bestseller);
		 ?>
		 <tr>
		  <td  colspan="5" align="right"  class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_prodBestsellerAssign();" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_ASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_products_in_bestseller))
			{
			?>
			<div id="product_bestsellerunassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('product_bestseller','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_UNASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<input name="SaveOrder" type="button" class="red" id="SaveOrder" value="Save Order" onclick="call_ajax_changeorderall('product_bestseller','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SAVEORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}
			?>		  </td>
			</tr>
			
				<?PHP
				if ($db->num_rows($ret_product))
				{
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmBestseller,\'checkboxproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmBestseller,\'checkboxproduct[]\')"/>','Slno.','Product Name','Order','Hidden');
				$header_positions=array('center','center','left','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_product = $db->fetch_array($ret_product))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="7%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproduct[]" value="<?php echo $row_product['bestsel_id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=products&fpurpose=edit&checkbox[]=<? echo $row_product['product_id']; ?>" class="edittextlink"><?php echo stripslashes($row_product['product_name']);?></a></td>
					<td class="<?php echo $cls?>" align="left"><input type="text" name="product_bestseller_order_<?php echo $row_product['bestsel_id']?>" id="product_bestseller_order_<?php echo $row_product['bestsel_id']?>" value="<?php echo stripslashes($row_product['bestsel_sortorder']);?>" size="2" /></td>
					<td class="<?php echo $cls?>" align="left"><?php echo ($row_product['bestsel_hidden']==1)?'Yes':'No'?></td>
					
					</tr>
				<?php
				}
				}
				else
				{
				?>
				<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="productbestseller_norec" id="productbestseller_norec" value="1" />
								  No Products Set as Bestseller. </td>
								</tr>
				<?	
				}
				?>
				</table></div>
		
<?	}

 // ###############################################################################################################
	// 				Function which holds the display logic of products under the upsell list
	// ###############################################################################################################
	function show_product_upsell_list($alert='')
	{
		global $db,$ecom_siteid ;
		
		  // Get the list of products under current category group
		  $sql_product = "SELECT b.upsell_id,a.product_id,a.product_name,a.product_hide,b.upsell_order 
							FROM 
								products a,	upsell_products_map b 
							WHERE 
								a.sites_site_id=$ecom_siteid 
								AND b.sites_site_id=$ecom_siteid 
								AND a.product_id=b.products_product_id 
							ORDER BY 
								b.upsell_order ASC";
		 
		  $ret_product = $db->query($sql_product);
		 ?>
		  <table width="100%" cellpadding="0" cellspacing="1" border="0">
		   <tr>
          <td colspan="5" align="left" valign="middle" class="helpmsgtd" >
			  <div class="helpmsg_divcls"><?=get_help_messages('UPSELL_PROD_MESS1') ?></div>
		  </td>
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
				?>
				 <?
		  // Get the list of products picked for best seller
		  $sql_products_in_bestseller = "SELECT products_product_id FROM general_settings_site_bestseller WHERE  
										sites_site_id=$ecom_siteid LIMIT 1";
		  $ret_products_in_bestseller = $db->query($sql_products_in_bestseller);
		 ?>
		 <tr>
		  <td  colspan="5" align="right"  class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_prodUpsellAssign();" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_ASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_products_in_bestseller))
			{
			?>
			<div id="product_bestsellerunassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('product_upsell','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_UNASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>
			<input name="SaveOrder" type="button" class="red" id="SaveOrder" value="Save Order" onclick="call_ajax_changeorderall('product_upsell','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SAVEORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.gif" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}
			?>		  </td>
			</tr>
			
				<?PHP
				if ($db->num_rows($ret_product))
				{
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmBestseller,\'checkboxproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmBestseller,\'checkboxproduct[]\')"/>','Slno.','Product Name','Order','Hidden');
				$header_positions=array('center','center','left','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_product = $db->fetch_array($ret_product))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="7%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproduct[]" value="<?php echo $row_product['upsell_id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=products&fpurpose=edit&checkbox[]=<? echo $row_product['product_id']; ?>" class="edittextlink"><?php echo stripslashes($row_product['product_name']);?></a></td>
					<td class="<?php echo $cls?>" align="left"><input type="text" name="product_bestseller_order_<?php echo $row_product['upsell_id']?>" id="product_bestseller_order_<?php echo $row_product['upsell_id']?>" value="<?php echo stripslashes($row_product['upsell_order']);?>" size="2" /></td>
					<td class="<?php echo $cls?>" align="left"><?php echo ($row_product['product_hide']=='Y')?'Yes':'No'?></td>
					
					</tr>
				<?php
				}
				}
				else
				{
				?>
				<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="productbestseller_norec" id="productbestseller_norec" value="1" />
								  <?=get_help_messages('UPSELL_NO_PROD_MESS1')?> </td>
								</tr>
				<?	
				}
				?>
				</table>
		
<?	}
  ?>