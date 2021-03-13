<?PHP
	function show_card_maininfo($card_id,$alert='')
	{
	global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname;
$sql_card="SELECT card_name,card_extraprice,card_order,card_active FROM giftwrap_card  WHERE card_id=".$card_id;
$res_card= $db->query($sql_card);
$row_card = $db->fetch_array($res_card);

?>		<div class="editarea_div">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="fieldtable">
		<?php 
		if($alert)
		{			
		?>
        <tr>
          <td colspan="2" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
        </tr>
		<?
		}
		?>  
         <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Card Name <span class="redtext">*</span> </td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="card_name" value="<?=$row_card['card_name']?>"  />		  </td>
        </tr>
		 <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Extra Price (<?php echo  display_curr_symbol()?>)</td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="card_extraprice" size="3" value="<?=$row_card['card_extraprice']?>"  />		  </td>
        </tr>
		 <tr>
          <td width="15%" align="left" valign="middle" class="tdcolorgray" >Order</td>
          <td width="85%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="card_order" size="3" value="<?=$row_card['card_order']?>"  />
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFTWRAP_CARD_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
       
        <tr>
          <td align="left" valign="middle" class="tdcolorgray" >Hidden</td>
          <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="card_active" value="0" <? if($row_card['card_active']==0) echo "checked";?> />Yes<input type="radio" name="card_active" value="1" <? if($row_card['card_active']==1) echo "checked";?> />No
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFTWRAP_CARD_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
	</table>
	</div>	
	<div class="editarea_div">
   <table width="100%">
   <tr>
	<td align="right" valign="middle"><input name="Submit" type="submit" class="red" value="Update" /></td>
	</tr>
	</table>
	</div>
<?php
}
	// ###############################################################################################################
	// 				Function which holds the display logic of images assigned to giftwrap card to be shown when called using ajax;
	// ###############################################################################################################
	function show_card_image_list($card_id,$alert='')
	{
		
		global $db,$ecom_siteid,$ecom_hostname;
		
		$sql_img = "SELECT id FROM images_giftwrap_card
						 WHERE giftwrap_card_card_id=$card_id LIMIT 1";
		$ret_img= $db->query($sql_img);
	?><div class="editarea_div">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
				<td align="left" class="helpmsgtd"><div class="helpmsg_divcls"><?php echo get_help_messages('EDIT_GIFTWRAP_CARD_IMAGES')?></div>
				</td>
			</tr>
		<?php
			if ($alert)
			{
		?>
			<tr>
				<td align="center" class="errormsg"><?php echo $alert?>
				</td>
			</tr>
		<?php
			} ?>
			<tr>
		  <td align="right" colspan="4" class="tdcolorgray_buttons">
				<input name="Assign_Image" type="button" class="red" id="Assign_Image" value="Assign More" onclick="document.frmEditCard.fpurpose.value='add_card_img';document.frmEditCard.submit();" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFTWRAP_CARD_ASSIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_img))
				{
				?>
					<div id="card_imgunassign_div" class="unassign_div" >
					<input name="card_img_save" type="button" class="red" id="card_img_save" value="Save Details" onclick="call_ajax_saveimagedetails('checkbox_img[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFTWRAP_CARD_SAVEDET')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
					
					&nbsp;&nbsp;&nbsp;<input name="card_img_unassign" type="button" class="red" id="card_img_unassign" value="Un assign" onclick="call_ajax_deleteall('checkbox_img[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_GIFTWRAP_CARD_UNASSIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>				</div>	
				<?php
				}				
				?>		  </td>
        </tr>
					
			<?PHP	
					// Get the list of images which satisfy the current critera from the images table
					 $sql_img = "SELECT b.id,a.image_id,b.image_title,b.image_order,a.image_gallerythumbpath,a.images_directory_directory_id FROM images a,images_giftwrap_card b WHERE 
								a.sites_site_id = $ecom_siteid 
								AND b.giftwrap_card_card_id=$card_id 
								AND a.image_id=b.images_image_id ORDER BY b.image_order";	
					$ret_img = $db->query($sql_img);
			if($db->num_rows($ret_img))
					{
?>
						<tr>
						<td align="left">
						<img src="images/checkbox.gif" border="0" onclick="select_all_img(document.frmEditCard,'checkbox_img[]')" alt="Check all images" title="Check all images"/><img src="images/uncheckbox.gif" border="0" onclick="select_none_img(document.frmEditCard,'checkbox_img[]')" alt="Uncheck all images" title="Uncheck all images"/>
						</td>
			</tr>
<?php					
							
				?>
							<tr>
							  <td>
									<table width="83%" border="0" cellpadding="0" cellspacing="8" class="imagelisttable">
									<tr>
<?php
										$max_cols 	= 6;
										$cur_col	= 0;
										while ($row_img = $db->fetch_array($ret_img))
										{
?>
											  <td align="center" valign="middle" class="imagelistproducttabletd" id="img_td_<?php echo $row_img['id']?>">
												  <table width="100%" border="0" cellpadding="1" cellspacing="1" class="imagelist_imgtable">
												  <tr>
												  <td align="left" valign="middle" width="90%" class="imagelistproducttabletdtext">
												  Order <input type="text" name="img_ord_<?php echo $row_img['id']?>" id="img_ord_<?php echo $row_img['id']?>" value="<?php echo $row_img['image_order']?>" size="2" class="imagelistproductinputbox" />
												  </td>
												  <td align="left" valign="middle">
												  <input type="checkbox" name="checkbox_img[]" id="checkbox_img[]" value="<?php echo $row_img['id']?>" onclick="handle_imagesel('<?php echo $row_img['id']?>')" />
												  </td>
												  </tr>
												  <tr>
												  <td align="center" class="imagelist_imgtd" colspan="2" ondblclick="if (confirm('Are you sure you want to go to Image edit page?')==true) {window.location='home.php?request=img_gal&fpurpose=edit_img&edit_id=<?php echo $row_img['image_id']?>&curdir_id=<?php echo $row_img['images_directory_directory_id']?>&org_id=<?php echo $card_id?>&back_frm=gift_card'}">
												  <img src="http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?>"/>
												  </td>
												  </tr>
												  <tr>
												  <td align="left" valign="middle" colspan="2" class="imagelistproducttabletdtext">
													Title <input type="text" name="img_title_<?php echo $row_img['id']?>" id="img_title_<?php echo $row_img['id']?>" value="<?php echo stripslashes($row_img['image_title']);?>" class="imagelistproductinputbox" size="28" />
												  </td>
												  </tr>
												  </table>
											  </td>
<?php
											$cur_col++;
											if($cur_col>=$max_cols)
											{
												$cur_col = 0;
												echo "</tr><tr>";
											}
										}
										if ($curcol<$max_cols)
										{
											echo "<td colspan='".($maxcols-$curcol)."'>&nbsp;</td>";
										}
?>		  
									</tr>
								  </table>
							  </td>
							</tr>
<?php
						
					}
					else
					{
?>
						<tr>
							  <td align="center" class="redtext"> No Images assigned for current giftwrap card
							  <input type="hidden" name="card_img_norec" id="card_img_norec" value="1"  />
							  </td>
						</tr>	  
<?php	
					}
?>		
</table></div>
<?php
	}
?>