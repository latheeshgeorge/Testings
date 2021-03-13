<?PHP
	function show_maininfo($tab_id,$alert='') 
	{
		global $db,$ecom_siteid,$ecom_hostname,$ecom_themeid;
		$sql_edit = "SELECT  common_tab_id, sites_site_id, tab_title, tab_content, tab_hide  
				FROM 
					product_common_tabs 
				WHERE 
					common_tab_id=".$tab_id;
		$res_edit = $db->query($sql_edit);
		$row_edit = $db->fetch_array($res_edit);
	?>		<div class="editarea_div">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="fieldtable">
             <tr>
               <td width="9%" align="left" class="tdcolorgray">Title <span class="redtext"> *</span></td>
               <td width="31%" align="left" class="tdcolorgray"><input name="tab_title" type="text" id="tab_title" value="<?php echo $row_edit['tab_title']?>" size="30" /></td>
               <td width="10%" align="left" class="tdcolorgray">Hide</td>
               <td width="50%" align="left" class="tdcolorgray"><input type="radio" name="tab_hide" value="1" <?php echo ($row_edit['tab_hide']==1)?'checked="checked"':''?> />
Yes
  <input name="tab_hide" type="radio" value="0" <?php echo ($row_edit['tab_hide']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('Use this section to hide this tab.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             <tr>
               <td align="left" valign="top" class="tdcolorgray">Description </td>
               <td colspan="3" align="left" class="tdcolorgray">
			    <?php
						$editor_elements = "tab_content";
						include_once(ORG_DOCROOT."/console/js/tinymce.php");
				?>			   
				<textarea style="height:300px; width:650px" id="tab_content" name="tab_content"><?=stripslashes($row_edit['tab_content'])?></textarea>			   </td>
              </tr>
			 <tr>
			   <td colspan="4" align="center">&nbsp;</td>
		    </tr>
			 
           </table></div>
		   <div class="editarea_div">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
		   <tr>
			 <td align="right" valign="middle">
			 <input name="prodattach_Submit" type="submit" class="red" value="Save" /></td>
			 </tr>
			 </table>
			 </div>
	<?php	   	
	}
	function show_tabproducts($tab_id,$alert='')
	{
		global $db,$ecom_siteid;
		// Get the ids of products to which current general attachment is linked
		$sql_prodid = "SELECT product_name,products_product_id,product_hide 
							FROM 
								products a, product_tabs b 
							WHERE 
								b.product_common_tabs_common_tab_id = $tab_id 
								AND a.product_id = b.products_product_id 
								AND a.sites_site_id = $ecom_siteid 
							ORDER BY 
								a.product_name ";
		$ret_prodid = $db->query($sql_prodid);

		?>
		<div class="editarea_div">
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
		<tr>
          <td colspan="4" align="left" valign="middle" class="helpmsgtd" ><div class="helpmsg_divcls"><?=get_help_messages('EDIT_COMM_PROD_ATTT_DISPLAY') ?></div></td>
        </tr>
		<?php
		if($alert)
		{
		?>
				<tr>
					<td colspan="4" align="center" class="errormsg"><?php echo $alert?></td>
				</tr>
		 <?php
		}
		?>
		 <tr>
		  <td align="right" colspan="5" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayProdAssign('<?php echo $_REQUEST['pass_search_name']?>','<?php echo $_REQUEST['pass_sort_by']?>','<?php echo $_REQUEST['pass_sort_order']?>','<?php echo $_REQUEST['pass_records_per_page']?>','<?php echo $_REQUEST['pass_start']?>','<?php echo $_REQUEST['pass_pg']?>','<?php echo $tab_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMM_PROD_TAB_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_prodid))
			{
			?>
			<div id="display_product_combounassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('product_unassign','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMM_PROD_TAB_UNASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		</div>	
			<?php
			}
			?>		  </td>
		  </tr>
		<?php
		if($db->num_rows($ret_prodid))
		{
			$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmcommon_attachment,\'checkboxproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmcommon_attachment,\'checkboxproduct[]\')"/>','Slno.','Product Name','Hidden');
			$header_positions=array('center','center','left','center');
			$colspan = count($table_headers);
			echo table_header($table_headers,$header_positions); 
			$cnt = 1;
			while ($row_prodid = $db->fetch_array($ret_prodid))
			{
				$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproduct[]" value="<?php echo $row_prodid['products_product_id'];?>" /></td>
					<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=products&checkbox[0]=<?=$row_prodid['products_product_id']?>&fpurpose=edit" class="edittextlink" ><?php echo stripslashes($row_prodid['product_name']);?></a></td>
					<td class="<?php echo $cls?>" align="center"><?php echo ($row_prodid['product_hide']=='Y')?'Yes':'No'?></td>
					
					</tr>
				<?php
			}
		}
		else
			{
		?>
			<tr>
			  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">Not yet assigned to any of the products
			   <input type="hidden" name="prodattach_norec" id="prodattach_norec" value="1" />
			  </td>
			</tr>
		<?
			}
		
		?>
		</table></div>
		<?  						
	}
?>	 	
	