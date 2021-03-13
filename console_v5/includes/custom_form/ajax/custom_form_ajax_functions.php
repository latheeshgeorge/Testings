<?php
	// ###############################################################################################################
	// 				Function which holds the display logic of products to which voucher is linked when called using ajax;
	// ###############################################################################################################
	function show_section_product_list($editid,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of assigned products
				$sql_prod = "SELECT a.product_id,a.product_name,a.product_hide,b.product_active,b.id,a.product_webprice 
										FROM products a,element_section_products b 
												 WHERE b.element_sections_section_id=$editid 
												 	   AND a.product_id=b.products_product_id 
													   		ORDER BY a.product_name";
				$ret_prod = $db->query($sql_prod);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditSection,\'checkboxprod[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditSection,\'checkboxprod[]\')"/>','Slno.','Product Name','Web Price','Hidden');
						$header_positions=array('center','center','left','center','center');
						$colspan = count($table_headers);
						if($alert)
						{
					?>
							<tr>
								<td colspan="<?php echo $colspan?>" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_prod))
						{
							
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_prod = $db->fetch_array($ret_prod))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxprod[]" value="<?php echo $row_prod['product_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=products&fpurpose=edit&checkbox[]=<?php echo $row_prod['product_id']?>" class="edittextlink" ><?php echo stripslashes($row_prod['product_name']);?></a></td>
									<td align="center" class="<?php echo $cls?>"><?php echo display_price($row_prod['product_webprice']);?></td>
									<td align="center" class="<?php echo $cls?>"><?php echo ($row_prod['product_hide']=='N')?'No':'Yes';?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="secprod_norec" id="secprod_norec" value="1" />
								  Not linked to any products yet.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	function show_order_list($promotionalid,$alert='')
	{
		global $db,$ecom_siteid;
			// Get the voucher number related to current voucher id
			$sql_promot = "SELECT code_id,code_number FROM promotional_code WHERE code_id=$promotionalid";
			$ret_promot = $db->query($sql_promot);
			if ($db->num_rows($ret_promot))
			{
				$row_promot 	= $db->fetch_array($ret_promot);
				$vnum			= stripslashes($row_promot['code_number']); 
			}
			 // Check whether any order has been placed with the current voucher number
			$sql_order= "SELECT order_id ,order_date,order_custfname,order_custtitle,order_custlname,order_totalprice FROM orders WHERE promotional_code_code_id=$promotionalid  
						AND promotional_code_code_number ='".$vnum."' AND sites_site_id=$ecom_siteid ORDER BY order_date DESC";
						$ret_order = $db->query($sql_order);
						   $table_headers = array('Slno.','Order Id','Order Date','Customer name','Order Total');
							$header_positions=array('center','center','center','center','center');
							$colspan = count($table_headers);
							?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="<? echo $colspan?>" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_order))
						{
							
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_order = $db->fetch_array($ret_order))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
								$date = dateFormat($row_order['order_date'],'');
							
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="center" class="<?php echo $cls?>"><?php echo stripslashes($row_order['order_id']);?></td>
									<td align="center" class="<?php echo $cls?>"><?php echo stripslashes($date);?></td>
									<td align="center" class="<?php echo $cls?>"><?php echo stripslashes($row_order['order_custtitle']).".".stripslashes($row_order['order_custfname'])." ".stripslashes($row_order['order_custlname']);?></td>
									<td align="center" class="<?php echo $cls?>"><?php echo display_price($row_order['order_totalprice']);?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="order_norec" id="order_norec" value="1" />
								  No Orders found.</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
?>