<?PHP
	function show_maininfo($attach_id,$alert='') 
	{
		global $db,$ecom_siteid,$ecom_hostname,$ecom_themeid;
		$sql_edit = "SELECT common_attachment_id, attachment_title, attachment_orgfilename,attachment_filename,attachment_type,
					attachment_hide, sites_site_id  
				FROM 
					product_common_attachments 
				WHERE 
					common_attachment_id=".$attach_id;
		$res_edit = $db->query($sql_edit);
		$row_edit = $db->fetch_array($res_edit);
	?>		<div class="editarea_div">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="fieldtable">
             <tr>
               <td width="9%" align="left" class="tdcolorgray">Title <span class="redtext"> *</span></td>
               <td width="31%" align="left" class="tdcolorgray"><input name="attach_title" type="text" id="attach_title" value="<?php echo $row_edit['attachment_title']?>" size="30" /></td>
               <td width="10%" align="left" class="tdcolorgray">Hide</td>
               <td width="50%" align="left" class="tdcolorgray"><input type="radio" name="attach_hide" value="1" <?php echo ($row_edit['attachment_hide']==1)?'checked="checked"':''?> />
Yes
  <input name="attach_hide" type="radio" value="0" <?php echo ($row_edit['attachment_hide']==0)?'checked="checked"':''?> />
No<a href="#" onmouseover ="ddrivetip('Use this section to hide this attachment.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
             </tr>
             <tr>
               <td align="left" class="tdcolorgray">Type </td>
               <td align="left" class="tdcolorgray">
			   <?php
			  		$attach_type = array('Audio'=>'Audio(mp3,wma)','Video'=>'Video(mpg,mpeg,wmv)','Pdf'=>'Pdf','Other'=>'Other');
					echo generateselectbox('attach_type',$attach_type,$row_edit['attachment_type']);
				?>			   </td>
               <td align="left" class="tdcolorgray">&nbsp;</td>
               <td align="left" class="tdcolorgray">&nbsp;</td>
             </tr>
             <tr>
               <td align="left" class="tdcolorgray">Update File </td>
               <td align="left" class="tdcolorgray"><input name="attach_file" type="file" id="attach_file" /></td>
               <td align="left" class="tdcolorgray">Current File </td>
               <td align="left" class="tdcolorgray">
			   <?php
			   	if($row_edit['attachment_orgfilename'])
				{
					echo $row_edit['attachment_orgfilename']?> &nbsp;&nbsp;<a href="includes/common_product_attachments/download.php?attach_id=<?php echo $row_edit['common_attachment_id']?>" title="Download" class="edittextlink"><img src="images/download.gif" alt="Download" title="Click to download this file" border="0" /></a>
               <?php
			  	}
				  ?>			   </td>
             </tr>
           </table>
</div>
		   <div class="editarea_div">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="fieldtable">
			<tr>
			 <td colspan="4" align="center" valign="middle">
			   <input name="prodattach_Submit" type="submit" class="red" value="Save" />			 </td>
			 </tr>
			 </table>
			 </div>
	<?php	   	
	}
	function show_attachedproducts($attach_id,$alert='')
	{
		global $db,$ecom_siteid;
		// Get the ids of products to which current general attachment is linked
		$sql_prodid = "SELECT product_name,products_product_id,product_hide 
							FROM 
								products a, product_attachments b 
							WHERE 
								b.product_common_attachments_common_attachment_id = $attach_id 
								AND a.product_id = b.products_product_id 
								AND a.sites_site_id = $ecom_siteid 
							ORDER BY 
								a.product_name ";
		$ret_prodid = $db->query($sql_prodid);
		?><div class="editarea_div">
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
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_displayProdAssign('<?php echo $_REQUEST['search_name']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $attach_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMM_PROD_ATTT_ASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_prodid))
			{
			?>
			<div id="display_product_combounassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('product_unassign','checkboxproduct[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_COMM_PROD_ATTT_UNASS_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		</div>	
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
		</table>
		</div>
		<?  						
	}
?>	 	
	