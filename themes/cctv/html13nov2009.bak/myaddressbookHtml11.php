<?php
	/*############################################################################
	# Script Name 	: myaddressbookHtml.php
	# Description 	: Page which holds the display logic for My Address Book
	# Coded by 		: ANU
	# Created on	: 18-Apr-2008
	##########################################################################*/
	class myaddressbook_Html
	{
		function Show_AddressBook(){
		
				global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
				$session_id = session_id();	// Get the session id for the current section
				$Captions_arr['ADDRESSBOOK'] 	= getCaptions('ADDRESSBOOK');
				$customer_id = get_session_var("ecom_login_customer"); // get customer id
				$where_condition = "WHERE sites_site_id =$ecom_siteid AND customers_customer_id = $customer_id ";
				if($_REQUEST['contact_name'])
				{
				  $where_condition .=" AND address_fname like '%".$_REQUEST['contact_name']."%'"; 
				}
				if($_REQUEST['contact_email'])
				{
			      $where_condition .=" AND address_email like '%".$_REQUEST['contact_email']."%'"; 
				}
				
				$sql_cnt_addressbook = "SELECT count(address_id) as cnt_contact FROM customer_addressbook $where_condition ";
				$ret_cnt_addressbook = $db->query($sql_cnt_addressbook);
				list($tot_cnt)  = $db->fetch_array($ret_cnt_addressbook); 
				$addressperpage = 5;
				$pg_variable	= 'addressbook_pg';
				$start_var 		= prepare_paging($_REQUEST[$pg_variable],$addressperpage,$tot_cnt);
				
				$Limitaddressbook		= " LIMIT ".$start_var['startrec'].", ".$addressperpage;
				$sql_list_addressbook = "SELECT address_id,customers_customer_id,address_title,address_fname,address_mname,address_lname,address_email FROM customer_addressbook $where_condition $Limitaddressbook ";
				$ret_list_addressbook = $db->query($sql_list_addressbook);
				$address_array = array();
					?>
					<form method="post" name="my_enquire" class="frm_cls" action="<?php url_link('myaddressbook.html')?>">
					<input type="hidden" name="fpurpose" value="" />
					<input type="hidden" name="enquiry_id" value="" />
					<input type="hidden" name="enq_mod" value="list_enquiries" />
					<input type="hidden" name="action_purpose" />
				  <table width="100%" border="0" cellpadding="0" cellspacing="2" class="shoppingcarttable">
					  <tr>
						<td  align="left" valign="middle" colspan="6"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <?php echo $Captions_arr['ADDRESSBOOK']['TREE_MENU_ADDRESSBOOK']?></div></td>
					  </tr>
					 <tr>
					   <td class="" colspan="6"><table cellspacing="0" cellpadding="0" width="100%"><tr>
						 <td width="35%" class="shoppingcartcontent">Contact Name  
					     <input class="textfeild" type="text" name="contact_name" size="8" value="<?=$_REQUEST['contact_name']?>"  /></td>
						 <td width="35%"  class="shoppingcartcontent" >Contact Email
					     <input class="textfeild" type="text" name="contact_email" size="8" value="<?=$_REQUEST['contact_email']?>"  /> </td>
						 <td  class="shoppingcartcontent">	<input name="clearenq_button" type="button" class="buttonred_cart" id="clearenq_button" value="<?php echo $Captions_arr['ADDRESSBOOK']['ADDERSSBOOK_SEARCH_BUTTON']?>" onclick="document.my_enquire.submit()" />							   </td>
					  </tr>
					  </table>					  </td> 
					  </tr>
					  <?
					  if($tot_cnt>0){
					  ?>
					  <tr>
					    <td width="24%" align="center" class="pagingcontainertd">&nbsp;</td>
				        <td width="20%" align="center" class="pagingcontainertd">&nbsp;</td>
				        <td width="20%" align="center" class="pagingcontainertd">&nbsp;</td>
				        <td width="15%" align="center" class="pagingcontainertd">&nbsp;</td>
				        <td width="11%" align="center" class="pagingcontainertd"><input name="address_delete" type="button" class="buttonred_cart" id="address_delete" value="<?php echo $Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_DELETE']?>" onclick="javascript:address_delete_all(document.my_enquire)" />				        </td>
				        <td width="10%" align="center" class="pagingcontainertd"><input name="address_add" type="button" class="buttonred_cart" id="address_add" value="<?php echo $Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_ADD']?>" onclick="javascript:add_address(document.my_enquire)" />				       </td>
				    </tr>
				    <tr>
						<td colspan="6" class="pagingcontainertd" align="center">
						<?php
							$path = '';
							$query_string .= "contact_name=".$_REQUEST['contact_name']."&contact_email=".$_REQUEST['contact_email']."";
							paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Contact Address',$pageclass_arr); 	
						?>						</td>
					</tr>
					<?
					 } 
					 ?>
											
				    <tr>
				  	<td align="left" valign="middle" class="shoppingcartheaderA" ><?php echo $Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_CHECKBOX']?></td>
					<td align="left" valign="middle" class="shoppingcartheaderA" ><?php echo $Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_SLNO']?></td>
					<td align="left" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_CONTACTNAME']?></td>
					<td align="left" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_CONTACTEMAIL']?></td>
					<td align="center" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_CONTACTDETAILS']?></td>
				    <td align="center" valign="middle" class="shoppingcartheaderA"><?php echo $Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_EDIT']?>
				      <input type="hidden" name="hid_editid" /></td>
				    </tr>
				  <?php
				if($db->num_rows($ret_list_addressbook))
				{  
				$srno = 1;
				while($row_list_addressbook = $db->fetch_array($ret_list_addressbook))
				{
				?>
				<tr>
				 <td align="left" valign="middle" class="shoppingcartcontent" ><input type="checkbox" name="address_addressid[]" value="<?=$row_list_addressbook['address_id']?>" /></td>
				   <td align="left" valign="middle" class="shoppingcartcontent" ><?php echo $srno++?></td>
					<td align="left" valign="middle" class="shoppingcartcontent" ><?=$row_list_addressbook['address_title']."&nbsp;".$row_list_addressbook['address_fname']."&nbsp;".$row_list_addressbook['address_mname']."&nbsp;".$row_list_addressbook['address_lname']?></td>
					<td align="left" valign="middle" class="shoppingcartcontent"><?=$row_list_addressbook['address_email']?></td>
					<td align="center" valign="middle" class="shoppingcartcontent"><img src="<?php url_site_image('next_arrow.gif')?>" onclick="document.my_enquire.enq_mod.value='details_my_enquiry';document.my_enquire.enquiry_id.value='<?=$row_select_enq['enquiry_id']?>';document.my_enquire.submit();" alt="Details" title="Details" /></td>
				    <td align="center" valign="middle" class="shoppingcartcontent"><a href="#" onclick="javascript:edit_address(document.my_enquire,'<? echo $row_list_addressbook['address_id']; ?>');"><?php echo $Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_EDIT']; ?></a></td>
				</tr>
				 <? 
				}
				  ?>
					<tr>
					 <td  align="center"  colspan="6" >&nbsp; </td>
				</tr>
				  <?
				 }// End of check number of rows
				 else 
				 {
				 ?>
				   <tr>
							<td align="center" valign="middle" class="shoppingcartcontent" colspan="6" >
								<?php echo $Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_NOTFOUND']?>							</td>
						</tr>	
				 <?
				 
				 } 
				?>
				</table>
				</form>	
				<script language="javascript">
					function address_delete_all(frm) {
						frm.action_purpose.value = 'delete_addressbook';
						frm.submit();
					}
					function add_address(frm) {
						frm.action_purpose.value = 'add_addressbook';
						frm.submit();
					}
					function edit_address(frm,val) {
					    alert(val);
						frm.action_purpose.value = 'edit_addressbook';
						frm.hid_editid.value = val;
						frm.submit();
					}
				</script>
				<?
		}
		
		
		function Add_AddressBook(){
				global $alert,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
				$session_id = session_id();	// Get the session id for the current section
				$Captions_arr['ADDRESSBOOK'] 	= getCaptions('ADDRESSBOOK');
				$customer_id = get_session_var("ecom_login_customer"); // get customer id
			
			
					?>
					<form method="post" name="add_address" class="frm_cls" action="<?php url_link('myaddressbook.html')?>">
					<input type="hidden" name="fpurpose" value="" />
					<input type="hidden" name="enquiry_id" value="" />
					<input type="hidden" name="action_purpose" />
				  <table width="100%" border="0" cellpadding="0" cellspacing="2" class="shoppingcarttable">
					  <tr>
						<td  align="left" valign="middle" colspan="6"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <a href="<? url_link('myaddressbook.html');?>"><?php echo $Captions_arr['ADDRESSBOOK']['TREE_MENU_ADDRESSBOOK'];?></a> >> <?php echo $Captions_arr['ADDRESSBOOK']['TREE_MENU_ADD_ADDRESSBOOK']?> </div></td>
					  </tr>
					 
									
				 
				<?php
				if($alert)
				{
			?>
					<tr>
						<td colspan="4" align="center" valign="middle" class="red_msg">
						- <?php echo $alert?> -
						</td>
					</tr>
			<?php
				}
				
				?>
					  <tr>
					    <td  colspan="2"  align="right" >&nbsp;</td>
					    <td  align="center" >&nbsp;</td>
					    <td  align="left" >&nbsp;</td>
				    </tr>
					  <tr>
					    <td  colspan="2"  align="right" >Title</td>
					    <td  align="center" >:</td>
					    <td  align="left" >
			<select name="customer_title" class="regiinput" id="customer_title" >
			<option value="">Select</option>
			<option value="Mr." <?=($_REQUEST['customer_title']=='Mr.')?"selected":''?>>Mr.</option>
			<option value="Mrs." <?=($_REQUEST['customer_title']=='Mrs.')?"selected":''?>>Mrs.</option> 
			<option value="M/S." <?=($_REQUEST['customer_title']=='M/S.')?"selected":''?>>M/S.</option>
			</select></td>
				    </tr>
					  <tr>
					    <td  colspan="2"  align="right" >First Name<span class="redtext">*</span> </td>
					    <td  align="center" >:</td>
					    <td  align="left" ><input type="text" name="txt_fname" /></td>
				    </tr> 
					  <tr>
					    <td  colspan="2"  align="right" >Middle Name </td>
					    <td  align="center" >:</td>
					    <td  align="left" ><input type="text" name="txt_mname" /></td>
				    </tr>
					  <tr>
					    <td  colspan="2"  align="right" >Last name<span class="redtext">*</span> </td>
					    <td  align="center" >:</td>
					    <td  align="left" ><input type="text" name="txt_lname" /></td>
				    </tr>
					  <tr>
					    <td  colspan="2"  align="right" >Address</td>
					    <td  align="center" >:</td>
					    <td  align="left" ><textarea name="address" cols="25" rows="5"></textarea></td>
				    </tr>
				      <tr>
				        <td  colspan="2"  align="right" >E-Mail<span class="redtext">*</span></td>
				        <td  align="center" >:</td>
				        <td  align="left" ><input type="text" name="email" /></td>
			        </tr>
				      <tr>
				        <td  colspan="2"  align="right" >Phone Number </td>
				        <td  align="center" >:</td>
				        <td  align="left" ><input type="text" name="phonenumber" /></td>
			        </tr>
			          <tr>
			            <td  colspan="2"  align="right" >Mobile Number </td>
			            <td  align="center" >:</td>
			            <td  align="left" ><input type="text" name="mobnumber" /></td>
		            </tr>
		            <tr>
					 <td  colspan="2"  align="right" >Fax Number  </td>
				     <td width="5%"  align="center" >:</td>
				     <td width="51%"  align="left" ><input type="text" name="faxnumber" /></td>
					</tr>
				 
				   <tr>
							<td align="center" valign="middle" class="shoppingcartcontent" colspan="4" >&nbsp;</td>
						</tr>
				   <tr>
				     <td align="center" valign="middle" class="shoppingcartcontent" colspan="4" ><input class="buttonred_cart" type="button" name="Submit" value=" Add Address " onclick="javascript:add_newaddress(document.add_address)" /></td>
			        </tr>	
				</table>
				</form>	
				<script language="javascript">
					function add_newaddress(frm) {
						frm.action_purpose.value = 'insert_addressbook';
						frm.submit();
					}
				</script>
				<?
		}
		
			function Edit_AddressBook(){
				global $addressid,$alert,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents;
				$session_id = session_id();	// Get the session id for the current section
				$Captions_arr['ADDRESSBOOK'] 	= getCaptions('ADDRESSBOOK');
				$customer_id = get_session_var("ecom_login_customer"); // get customer id
			
			
					?>
					<form method="post" name="add_address" class="frm_cls" action="<?php url_link('myaddressbook.html')?>">
					<input type="hidden" name="fpurpose" value="" />
					<input type="hidden" name="enquiry_id" value="" />
					<input type="hidden" name="action_purpose" />
				  <table width="100%" border="0" cellpadding="0" cellspacing="2" class="shoppingcarttable">
					  <tr>
						<td  align="left" valign="middle" colspan="6"><div class="treemenu"><a href="<? url_link('');?>"><?=$Captions_arr['COMMON']['TREE_MENU_HOME_LINK'];?></a> >> <a href="<? url_link('myaddressbook.html');?>"><?php echo $Captions_arr['ADDRESSBOOK']['TREE_MENU_ADDRESSBOOK'];?></a> >> <?php echo $Captions_arr['ADDRESSBOOK']['TREE_MENU_ADD_ADDRESSBOOK']?> </div></td>
					  </tr>
					 
									
				 
				<?php
				if($alert)
				{
			?>
					<tr>
						<td colspan="4" align="center" valign="middle" class="red_msg">
						- <?php echo $alert?> -
						</td>
					</tr>
			<?php
				}
				$sql = "SELECT 
							address_title, address_fname, address_mname, address_lname, 
							address_address, address_email,address_phone, address_mobile, address_fax 
						 		FROM 
								   customer_addressbook 
								     WHERE 
									    address_id='".$addressid."'";
				$res = $db->query($sql);
				$row = $db->fetch_array($res);						
				?>
					  <tr>
					    <td  colspan="2"  align="right" >&nbsp;</td>
					    <td  align="center" >&nbsp;</td>
					    <td  align="left" >&nbsp;</td>
				    </tr>
					  <tr>
					    <td  colspan="2"  align="right" >Title</td>
					    <td  align="center" >:</td>
					    <td  align="left" >
			<select name="customer_title" class="regiinput" id="customer_title" >
			<option value="">Select</option>
			<option value="Mr." <?=($row['address_title']=='Mr.')?"selected":''?>>Mr.</option>
			<option value="Mrs." <?=($row['address_title']=='Mrs.')?"selected":''?>>Mrs.</option> 
			<option value="M/S." <?=($row['address_title']=='M/S.')?"selected":''?>>M/S.</option>
			</select></td>
				    </tr>
					  <tr>
					    <td  colspan="2"  align="right" >First Name<span class="redtext">*</span> </td>
					    <td  align="center" >:</td>
					    <td  align="left" ><input type="text" name="txt_fname" value="<?PHP echo $row['address_fname']; ?>" /></td>
				    </tr> 
					  <tr>
					    <td  colspan="2"  align="right" >Middle Name </td>
					    <td  align="center" >:</td>
					    <td  align="left" ><input type="text" name="txt_mname" value="<?PHP echo $row['address_mname']; ?>" /></td>
				    </tr>
					  <tr>
					    <td  colspan="2"  align="right" >Last name<span class="redtext">*</span> </td>
					    <td  align="center" >:</td>
					    <td  align="left" ><input type="text" name="txt_lname" value="<?PHP echo $row['address_lname']; ?>" /></td>
				    </tr>
					  <tr>
					    <td  colspan="2"  align="right" >Address</td>
					    <td  align="center" >:</td>
					    <td  align="left" ><textarea name="address" cols="25" rows="5"><?PHP echo $row['address_address']; ?></textarea></td>
				    </tr>
				      <tr>
				        <td  colspan="2"  align="right" >E-Mail<span class="redtext">*</span></td>
				        <td  align="center" >:</td>
				        <td  align="left" ><input type="text" name="email" <?PHP echo $row['address_email']; ?> /></td>
			        </tr>
				      <tr>
				        <td  colspan="2"  align="right" >Phone Number </td>
				        <td  align="center" >:</td> 
				        <td  align="left" ><input type="text" name="phonenumber" <?PHP echo $row['address_phone']; ?> /></td>
			        </tr>
			          <tr>
			            <td  colspan="2"  align="right" >Mobile Number </td>
			            <td  align="center" >:</td>
			            <td  align="left" ><input type="text" name="mobnumber" <?PHP echo $row['address_mobile']; ?>  /></td>
		            </tr>
		            <tr>
					 <td  colspan="2"  align="right" >Fax Number  </td>
				     <td width="5%"  align="center" >:</td>
				     <td width="51%"  align="left" ><input type="text" name="faxnumber" <?PHP echo $row['address_fax']; ?>  /></td>
					</tr>
				 
				   <tr>
							<td align="center" valign="middle" class="shoppingcartcontent" colspan="4" >&nbsp;</td>
						</tr>
				   <tr>
				     <td align="center" valign="middle" class="shoppingcartcontent" colspan="4" ><input class="buttonred_cart" type="button" name="Submit" value=" Add Address " onclick="javascript:add_newaddress(document.add_address)" /></td>
			        </tr>	
				</table>
				</form>	
				<script language="javascript">
					function add_newaddress(frm) {
						frm.action_purpose.value = 'insert_addressbook';
						frm.submit();
					}
				</script>
				<?
		}			
	}	
?>