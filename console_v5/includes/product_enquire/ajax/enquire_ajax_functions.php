<?php
	// ###############################################################################################################
	// 				Function which holds the display logic of products under the combo to be shown when called using ajax;
	// ###############################################################################################################
	function show_customer_details($enq_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql="SELECT pe.enquiry_id,pe.enquiry_fname,pe.enquiry_middlename,pe.enquiry_lastname,pe.enqiury_address,pe.enquiry_postcode,pe.enquiry_email,pe.enquiry_phone,pe.enquiry_title,pe.enquiry_fax,pe.enquiry_mobile,pe.enquiry_hidden,pe.enquiry_status,pe.enquiry_text from product_enquiries pe WHERE pe.sites_site_id=$ecom_siteid AND pe.enquiry_id=".$enq_id." LIMIT 1";
		$res=$db->query($sql);
		$row=$db->fetch_array($res);
		?>
        <div class="editarea_div">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<?php 
		if($alert)
		{			
		?>
		<tr>
          <td  align="center" valign="middle" class="errormsg"  colspan="3"><?=$alert?></td>
        </tr>
		<? 		
		 }
         ?>
		<tr>
		  <td align="left" valign="top" class="seperationtd" colspan="3" >Customer Details  <span class="redtext">*</span> </td>
		 </tr> 
		<tr>
		<td colspan="3" class="tdcolorgray" >
		<table cellspacing="0" cellpadding="0" width="100%">
		
		 <tr> 
		  <td align="left" valign="middle" class="tdcolorgray" colspan="2"><strong>Name </strong></td><td width="39%" align="left" valign="middle" class="tdcolorgray" >:&nbsp;<?=$row['enquiry_title']." ".$row['enquiry_fname']." ".$row['enquiry_middlename'].' '.$row['enquiry_lastname']?> </td>
		  <td width="19%" align="left" valign="middle" class="tdcolorgray" ><strong>Enquiry Status  </strong></td>
		  <td width="32%" align="left" valign="middle" class="tdcolorgray" >:
			<select name="enquiry_statuss" class="dropdown" id="enquiry_statuss">
			  <option value="NEW" <?= ($row['enquiry_status']=='NEW')?'selected':''; ?> >NEW</option>
			  <option value="PENDING" <?= ($row['enquiry_status']=='PENDING')?'selected':''; ?> >PENDING</option>
			   <option value="REPLY_SENT" <?=($row['enquiry_status']=='REPLY_SENT')?'selected':''; ?>>REPLY SENT</option>
			<option value="ONGOING" <?=($row['enquiry_status']=='ONGOING')?'selected':''; ?>>ON GOING</option>
			  <option value="CLOSED" <?= ($row['enquiry_status']=='CLOSED')?'selected':''; ?>>CLOSED</option>
			  <option value="CANCELLED" <?= ($row['enquiry_status']=='CANCELLED')?'selected':''; ?>>CANCELLED</option>
			</select>
		
			<input name="Submit" type="button" class="red" value="Go" onclick="enquire_action('go')" />&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ENQUIRE_DETAILS_DO')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		 </tr>
		  <tr>
		  <td height="21" colspan="2"  align="left" valign="middle" class="tdcolorgray"><div align="left"><strong>Phone </strong> </div></td>
		  <td width="39%"  align="left" valign="middle" class="tdcolorgray">:&nbsp;<?=$row['enquiry_phone'] ?></td>
		  <td width="19%"  align="left" valign="middle" class="tdcolorgray"><strong> Post Code</strong></td>
		  <td width="32%"  align="left" valign="middle" class="tdcolorgray">:
			<?=$row['enquiry_postcode'] ?>		    </td>
		  </tr>
		   <tr>
		  <td align="left" valign="middle" class="tdcolorgray" colspan="2"><div align="left"><strong>Mobile</strong></div></td>
		  <td align="left" valign="middle" class="tdcolorgray">:    		      
			<?=$row['enquiry_mobile'] ?></td>
		  <td align="left" valign="middle" class="tdcolorgray"><strong> Address</strong></td>
		  <td align="left" valign="middle" class="tdcolorgray">:
			<?=$row['enqiury_address']?>			</td>
		  </tr>
		  <tr>
		  <td  align="left" valign="middle" class="tdcolorgray" colspan="2"><div align="left"><strong>Fax</strong></div></td>
		  <td align="left" valign="middle" class="tdcolorgray">:
			<?=$row['enquiry_fax'] ?>		    </td>
		  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
		  </tr>
		  
		 <tr> 
		  <td align="left" valign="top" class="tdcolorgray" colspan="2"><strong>Email</strong></td>
		  <td colspan="2" align="left" valign="top" class="tdcolorgray">:
			<?=$row['enquiry_email'] ?></td>
		   <td  align="left" valign="middle" class="tdcolorgray" id="id_sendmail"> <input name="Submit2" type="button" class="red" value="Send Mail" onclick="select_mail()" />
		   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ENQUIRE_DETAILS_SENDMAIL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		 </tr>
		 <tr id="send_mail_id" style="display:none">
			 <td align="left" valign="middle" class="tdcolorgray" colspan="5">
				 <table border="0" cellpadding="0" cellspacing="0" width="100%">
					 <tr>
						 <td width="14%" align="right" valign="top" class="tdcolorgray">Subject :</td>
						<td width="86%" align="left" valign="middle" class="tdcolorgray"><input type="text" name="mail_subject" value="" id="mail_subject" size="60" /></td>
					</tr>
					<tr>	
						 <td align="right" valign="top" class="tdcolorgray">Content :</td><td align="left" valign="middle" class="tdcolorgray"><textarea name="mail_content" id="mail_content" rows="12" cols="50"></textarea></td>
					 </tr>
					 <tr><td align="center" valign="middle" class="tdcolorgray" colspan="2"><input name="Submit2" type="button" class="red" value="Cancel" onclick="select_mail()" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ENQUIRE_DETAILS_CANCEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;<input name="Submit2" type="button" class="red" value="Send" onclick="enquire_action('send_mail')"/><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PROD_ENQUIRE_DETAILS_SEND')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td></tr>
				 </table>			 </td>
		 </tr>
		  </table>		  </td>
		  </tr>
			<? 
			$sql_element_section = "SELECT DISTINCT section_name,element_sections_section_id FROM product_enquiry_dynamic_values WHERE sites_site_id=$ecom_siteid AND  product_enquiries_enquiry_id =".$enq_id."";
			//echo $sql_element_section;
			$res_section=$db->query($sql_element_section);
			if($db->num_rows($res_section)){
					$cnt =1;	
					while($row_section = $db->fetch_array($res_section))
					{ 
					$sqld ="SELECT dynamic_label,dynamic_value from product_enquiry_dynamic_values where product_enquiries_enquiry_id =".$enq_id." AND element_sections_section_id=".$row_section['element_sections_section_id']."";
					$resd=$db->query($sqld);
					if($db->num_rows($resd)){
					?>
					<tr>
					<td class="seperationtd" align="left" colspan="2"><?=$row_section['section_name']?>&nbsp;&nbsp;<span class="redtext">*</span></td>
					</tr>
					<tr>
					  <td  colspan="2" class="tdcolorgray">
					  <table cellspacing="0" cellpadding="0" width="100%" border="0">
					  <tr>
						<td align="left" valign="middle" class="tdcolorgray" colspan="2">						</td>
					   <td class="tdcolorgray"  align="left" >&nbsp;&nbsp;</td>
						</tr>
			
					<?
					while($rowd=$db->fetch_array($resd)){
					$cnt++;
					?>
					<tr>
					<td align="left" valign="middle" class="tdcolorgray" colspan="2">
					  <div align="left"><strong><?=$rowd['dynamic_label']?> </strong></div></td>
					  <td class="tdcolorgray"  align="left" width="78%" >:&nbsp;<?=$rowd['dynamic_value']?></td>
					</tr>
					<? } ?>
					   </table>					  </td>
					</tr>
					   <?
					   }
					}
				} 
				?>
		<tr>
		<td class="tdcolorgray"   align="left">&nbsp;		</td>
		<td width="89%" align="left"  class="tdcolorgray">
		&nbsp;&nbsp;</td>
		</tr>
		<? if($row['enquiry_text']!=''){?>
		<tr>
		  <td class="tdcolorgray"    align="left" valign="top"><strong>Enquiry note</strong></td>
		  <td class="tdcolorgray" align="left">&nbsp;</td>
		  </tr>
		<tr>
		<td class="tdcolorgray"    align="left" width="11%" valign="top">&nbsp;</td>
		<td class="tdcolorgray" align="left">
		  <?=nl2br($row['enquiry_text']);?></td>
		</tr>
		<? }?>
		<tr>
		  <td  align="left" valign="middle" class="tdcolorgray" >		  </td>
		</tr>
		</table>
		</div>
		
		<?
	}	
	function show_product_details_list($enq_id,$alert='')
	{
		global $db,$ecom_siteid ;
		
		  // Get the list of products under current category group
		 $srno =1;
		$sqlp = "select  pr.products_product_id,pr.id,pr.product_text from product_enquiry_data pr where pr.product_enquiries_enquiry_id=".$enq_id." ";
		$resp=$db->query($sqlp);
		  ?>
		  <div class="editarea_div">
		  <table width="100%" cellpadding="0" cellspacing="1" border="0">
			    <tr>
				  <td   align="left" valign="middle" class="helpmsgtd" colspan="6" ><div class="helpmsg_divcls"><? echo get_help_messages('LIST_PROD_ENQ_PRODDET_MSS1') ?></div></td>
				</tr>
				<?php
				if($alert)
				{
					?>
							<tr>
								<td  align="center" class="errormsg" colspan="6"><?php echo $alert?></td>
							</tr>
				 <?php
				}
				if ($db->num_rows($resp))
				{
				
				$table_headers = array('Slno.','Product_name','Retail','Cost','Discount','');
				$header_positions=array('left','left','left','left','left','left');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($rowp = $db->fetch_array($resp))
				{
					$sqlpr = "select product_id,product_name,product_webprice,product_costprice,product_discount from products where product_id=".$rowp['products_product_id']."";	
					$respr=$db->query($sqlpr);
					$rowpr = $db->fetch_array($respr);
					$rowv = $db->fetch_array($resv);
					$sql_msgs = "SELECT message_id,message_value ,message_caption
								FROM 
									product_enquiry_data_messages 
								WHERE 	
									product_enquiry_enquiry_id=".$rowp['id']." ";
				   $ret_msgs = $db->query($sql_msgs);
				   $vars_exists = false;
				   $sqlv = "select variable_name,variable_value from product_enquiry_data_vars where product_enquiry_data_id=".$rowp['id']."";	
					$resv=$db->query($sqlv);
					if($db->num_rows($resv) || $db->num_rows($ret_msgs)){
											$vars_exists 	= true;

					}
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
		    		<td width="6%" align="left" valign="top" class="<?=$cls;?>"  ><?php echo $srno++?>.</td>
			 		<td width="43%" align="left" valign="middle" class="<?=$cls;?>"  > <a href="home.php?request=products&checkbox[]=<? echo $rowpr['product_id'] ?>&fpurpose=edit" class="edittextlink"><? echo $rowpr['product_name']; ?></a>
					<?   if($vars_exists) { 
							 if($db->num_rows($resv)){
									while($rowv = $db->fetch_array($resv))
									{
									// If variables exists for current product, show it in the following section
									 if (trim($rowv['variable_value'])!='')
															print "<br><span class=''>".stripslashes($rowv['variable_name']).": ". stripslashes($rowv['variable_value'])."</span>"; 
														else
															print "<br><span class=''>".stripslashes($rowv['variable_name'])."</span>"; 
										
										
								 
								 
									 }
								  }
						 ?>
						 <?		  
							// Show the product messages if any
							 if ($db->num_rows($ret_msgs))
								{
									
									while($row_msgs = $db->fetch_array($ret_msgs))
									{
										if($row_msgs['message_value']!='') {
										  print "<br><span class=''>".stripslashes($row_msgs['message_caption']).": ". stripslashes($row_msgs['message_value'])."</span>"; 
										}
									}
								}
							}	?>					</td>
			  		<td width="13%" align="left" valign="middle" class="<?=$cls;?>"  ><?php echo display_price($rowpr['product_webprice'])?></td>
     		    	<td width="13%" align="left" valign="middle" class="<?=$cls;?>"  ><?php echo display_price($rowpr['product_costprice'])?></td>
		  			<td width="12%" align="left" valign="middle" class="<?=$cls;?>"  ><?php echo display_price($rowpr['product_discount'])?></td>
		 		   <td width="13%" align="left" valign="middle" class="<?=$cls;?>"  ><?php if($rowp['product_text']){?>
		 		     <div id="<?=$rowp['products_product_id']?>_div" onclick="handle_showdetailsdiv('<?=$rowp['products_product_id']?>_tr','<?=$rowp['products_product_id']?>_div')" title="Click here" style="cursor: pointer;">Details<img src="images/right_arr.gif"></div>
					<? }?></td>

					</tr> 
					<tr id="<?=$rowp['products_product_id']?>_tr" style="display:none;">
					<td width="6%">&nbsp;</td><td  colspan="5"><table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
					<td  align="left" class="listingtableheader">Product Message</td>
					</tr>
					<tr>
					<td align="left" class="listingtablestyleB"><?= nl2br($rowp['product_text'])?></td>
					</tr></table></td>
					</tr>
				     <?
				$cnt++;
				}
				}
				else
				{
				?>
				<tr>
								  <td colspan="6" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="productcombo_norec" id="productcombo_norec" value="1" />
								  No Products </td>
			</tr>
				<?	
				}
				?>
</table></div>
		
<?	}
function function_displaynote_add($enq_id,$alert)
{
global $db,$ecom_siteid ;

 // Get all the notes added for this enquiry
						$sql_notes = "SELECT note_id,date_format(a.note_addedon,'%d/%b/%Y %r') notedate,a.note,b.user_fname,b.user_lname FROM product_enquiry_notes a,sites_users_7584 b WHERE a.product_enquiries_enquiry_id=$enq_id AND 
						a.added_by = b.user_id ORDER BY note_addedon DESC ";
						$ret_notes = $db->query($sql_notes);
						?>
						<div class="editarea_div">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
					<tr>
					  <td   align="left" valign="middle" class="helpmsgtd" colspan="6" ><div class="helpmsg_divcls"><? echo get_help_messages('LIST_PROD_ENQ_NOTE_SUBMSS1') ?></div></td>
					</tr>
<?php  
					if($alert)
					{			
					?>
					<tr>
					  <td  align="center" valign="middle" class="errormsg"  colspan="2" ><?=$alert?></td>
					</tr>
					<?
					}
					?>
					<tr>

					 <td  valign="top" class="tdcolorgrayleft" width="50%">
					 <table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					   <td class="listingtableheader" colspan="2" align="left">Existing Notes
					   </td>
					 </tr>
						<?	
						if ($db->num_rows($ret_notes))
						{
						?>
						 <tr><td>
						  <table cellspacing="3" cellpadding="0" border="0">
						<?
							while ($row_notes = $db->fetch_array($ret_notes))
							{
					   ?>
					 
					 
						  <tr>
							<td width="94%" class="listingitallicstyleB" align="left"><?php echo $row_notes['notedate']?></td>
							<td width="6%" class="listingitallicstyleB"><a href="#" onclick="if(confirm('Are you sure you want to remove this note?')) { delete_note(<?=$row_notes['note_id'] ?>,<?=$enq_id?>);}" title="Delete"><img src="images/del.gif" width="16" height="16" border="0" /></a></td>
						  </tr>
						  <tr>
							<td colspan="2" align="left"><?php echo nl2br(stripslashes($row_notes['note']));?></td>
						  </tr>
						  <tr>
							<td colspan="2" align="right">(<?php echo $row_notes['user_fname'].$row_notes['user_lname']?>) </td>
						  </tr>
						
						<?php
								}
								?>
						   </table>
							</td>
						</tr>
								<?
							}
							else
							{
						?>
								<tr>
								  <td colspan="2" align="center" class="redtext">No Notes added yet.</td>
								</tr>
								 <input type="hidden" name="enquiry_norec" id="enquiry_norec" value="1"  />
						<?php
							}
							?>
							
					  </table>
					  </td>
					  <td class="tdcolorgrayleft" valign="top">						
 					<table width="100%" border="0" cellspacing="0" cellpadding="0"   >
							<tr>
								<td class="listingtableheader" align="left">Add Notes
								</td>
							</tr>
							<tr>
								<td >
									<table border="0" cellspacing="2" cellpadding="2">
										<tr>
										  <td width="3%">&nbsp;</td><td  valign="top" align="left">Specify your note here:</td>
										</tr>
										<tr>
										   <td width="3%">&nbsp;</td><td  valign="top" align="left"><textarea name="txt_note" cols="55" rows="4"></textarea></td>
										</tr>
										<tr>
										  <td align="right" valign="top" colspan="2"><input type="button" name="note_Submit" value="Save Note" class="red" onclick="if(document.frmListProductEnquiries.txt_note.value==''){ alert('Please specify the note'); } else { save_note_ajax(<?=$enq_id?>); }" /></td>
										</tr>
									</table>
								</td>
							</tr>
							
					  </table>
					 </td>
  </tr>
</table></div>
<?php
}
    
?>	
