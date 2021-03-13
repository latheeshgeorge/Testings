<?php
	// ###############################################################################################################
	// 				Function which holds the display logic of products under the shelf to be shown when called using ajax;
	// ###############################################################################################################
	function show_product_shelf_list($shelf_id,$alert='')
	{
		global $db,$ecom_siteid ;
		
		  // Get the list of products under current category group
		  $sql_product = "SELECT b.id,a.product_id,a.product_name,a.product_hide,b.product_order FROM products a,
						product_shelf_product b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_shelf_shelf_id=$shelf_id AND 
						a.product_id=b.products_product_id ";
		 
		  $ret_product = $db->query($sql_product);
		  ?>
		  <table width="100%" cellpadding="0" cellspacing="1" border="0">
				<?php
				if($alert)
				{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				}
				if ($db->num_rows($ret_product))
				{
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShelf,\'checkboxproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShelf,\'checkboxproduct[]\')"/>','Slno.','Product Name','Order','Hidden');
				$header_positions=array('center','center','left','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_product = $db->fetch_array($ret_product))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproduct[]" value="<?php echo $row_product['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><?php echo stripslashes($row_product['product_name']);?></td>
					<td class="<?php echo $cls?>" align="left"><input type="text" name="product_shelf_order_<?php echo $row_product['id']?>" id="product_shelf_order_<?php echo $row_product['id']?>" value="<?php echo stripslashes($row_product['product_order']);?>" size="2" /></td>
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
  								  <input type="hidden" name="productshelf_norec" id="productshelf_norec" value="1" />
								  No Products Assigned for this Shelf </td>
								</tr>
				<?	
				}
				?>
				</table>
		
<?	}

    // ###############################################################################################################
	// 				Function which holds the display logic of display products under the shelf using ajax;
	// ###############################################################################################################
	
    function show_display_product_shelf_list($shelf_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_display_product = "SELECT b.id,a.product_id,a.product_name,a.product_hide,b.id FROM products a,
						product_shelf_display_product b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_shelf_shelf_id=$shelf_id AND 
						a.product_id=b.products_product_id ";
		  
		$ret_display_product = $db->query($sql_display_product);
		
		?>
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
		<?php
				if($alert)
				{
					?>
							<tr>
								<td colspan="4" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				}
				if ($db->num_rows($ret_display_product))
		       {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShelf,\'checkboxdisplayproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShelf,\'checkboxdisplayproduct[]\')"/>','Slno.','Product Name','Hidden');
				$header_positions=array('center','center','left','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_display_product = $db->fetch_array($ret_display_product))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplayproduct[]" value="<?php echo $row_display_product['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><?php echo stripslashes($row_display_product['product_name']);?></td>
					<td class="<?php echo $cls?>" align="left"><?php echo ($row_display_product['product_hide']=='Y')?'Yes':'No'?></td>
					
					</tr>
				<?php
				}
				?>
				
		<?
		}
			else
			{
		?>
			<tr  >
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Products assigned to display this Shelf
			   <input type="hidden" name="display_productshelf_norec" id="display_productshelf_norec" value="1" />
			  </td>
			</tr>
		<?
			}
		
		?>
		</table>
		<?  
		}
	 // ###############################################################################################################
	// 				Function which holds the display logic of display categories under the shelf using ajax;
	// ###############################################################################################################
	 
	 function show_display_category_shelf_list($shelf_id,$alert='')
	 {
	 	global $db,$ecom_siteid ;
		 $sql_category_shelf = "SELECT b.id,a.category_id,a.category_name,a.category_hide FROM product_categories a,
						product_shelf_display_category b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_shelf_shelf_id=$shelf_id AND 
						a.category_id=b.product_categories_category_id ";
		 
		
		 $ret_category_shelf = $db->query($sql_category_shelf);
		 
			?>
			
				<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<?php
				if($alert)
				{
					?>
							<tr>
								<td colspan="4" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				}
				
				if ($db->num_rows($ret_category_shelf))
		        {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShelf,\'checkboxdisplaycategory[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShelf,\'checkboxdisplaycategory[]\')"/>','Slno.','Category Name','Hidden');
				$header_positions=array('center','center','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_category_group = $db->fetch_array($ret_category_shelf))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplaycategory[]" value="<?php echo $row_category_group['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><?php echo stripslashes($row_category_group['category_name']);?></td>
					<td class="<?php echo $cls?>" align="left"><?php echo ($row_category_group['category_hide']=='1')?'Yes':'No'?></td>
					</tr>
				<?php
				}
				
			}
			else
			{
			?>
			<tr  >
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Categories assigned to display this Shelf
			  <input type="hidden" name="display_categoryshelf_norec" id="display_categoryshelf_norec" value="1" />
			  </td>
			</tr>
			<?
			}
			?>
			</table>
			<?
	 }
	  // ###############################################################################################################
	// 				Function which holds the display logic of display static pages under the shelf using ajax;
	// ###############################################################################################################
	 function show_display_static_shelf_list($shelf_id,$alert='')
	 {
	 global $db,$ecom_siteid ;
	 $sql_static_shelf = "SELECT b.id,a.page_id,a.title,a.hide FROM static_pages a,
						product_shelf_display_static b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_shelf_shelf_id=$shelf_id AND 
						a.page_id=b.static_pages_page_id ";
		
		 
		 $ret_static_shelf = $db->query($sql_static_shelf);
	 ?>
	 <table width="100%" cellpadding="0" cellspacing="1" border="0">
				<?php
				
				if($alert)
				{
					?>
							<tr>
								<td colspan="4" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				}
				if ($db->num_rows($ret_static_shelf))
		        {
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShelf,\'checkboxdisplaystatic[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShelf,\'checkboxdisplaystatic[]\')"/>','Slno.','Page Name','Hidden');
				$header_positions=array('center','center','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_static_group = $db->fetch_array($ret_static_shelf))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxdisplaystatic[]" value="<?php echo $row_static_group['id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><?php echo stripslashes($row_static_group['title']);?></td>
					<td class="<?php echo $cls?>" align="left"left><?php echo ($row_static_group['hide']=='1')?'Yes':'No'?></td>
					</tr>
				<?php
				}
				}
				else
				{
				?>
				<tr  >
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">No Static Pages assigned to display this Shelf
			  <input type="hidden" name="display_staticshelf_norec" id="display_staticshelf_norec" value="1" />
			  </td>
			</tr>
				<?
				
				}
				?>
	  </table> 
	<?			
	
	 }
		
?>	
	