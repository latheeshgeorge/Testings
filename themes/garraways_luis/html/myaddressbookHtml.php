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
		
				global $ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$alert;
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
				
				$HTML_treemenu = '<div class="tree_menu_conA">
								  <div class="tree_menu_topA"></div>
								  <div class="tree_menu_midA">
									<div class="tree_menu_content">
									  <ul class="tree_menu">
									<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
									 <li>'.stripslash_normal($Captions_arr['ADDRESSBOOK']['TREE_MENU_ADDRESSBOOK']).'</li>
									</ul>
									  </div>
								  </div>
								  <div class="tree_menu_bottomA"></div>
								</div>';
								if($alert)
								{ 
								$HTML_alert .= 
								'<div class="cart_msg_outerA">
								<div class="cart_msg_topA"></div>
										<div class="cart_msg_txt">';
											  if($Captions_arr['REQUEST_A_CALLBACK'][$alert]){
															$HTML_alert .= "Error !! ". stripslash_normal($Captions_arr['REQUEST_A_CALLBACK'][$alert]);
													  }else{
															$HTML_alert .=  "Error !! ". $alert;
													  }
								$HTML_alert .=	'</div>
								<div class="cart_msg_bottomA"></div>
								</div>';
								}
								echo $HTML_alert;
					?>
					<form method="post" name="my_addressbook" class="frm_cls" action="<?php url_link('myaddressbook.html')?>">
					<input type="hidden" name="fpurpose" value="" />
					<input type="hidden" name="address_id" value="" />
					<input type="hidden" name="action_purpose" value="show_addressbook" />
					<?=$HTML_treemenu?>
					<div class="reg_shlf_outr">
				<div class="reg_shlf_inner">
					<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
					<?php
					if($Captions_arr['ADDRESSBOOK']['TREE_MENU_ADDRESSBOOK']){
					?>
					<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['ADDRESSBOOK']['TREE_MENU_ADDRESSBOOK'])?> </span></div></div>
					<? }?>
						<div class="reg_shlf_cont_div">
							<div class="reg_shlf_pdt_con">	
                            <table cellpadding="3" cellspacing="0" width="100%" class="reg_table_top">
						<tr>
						  <td width="17%">Contact Name </td>
						  <td width="22%"  class="usermenucontent" >Contact Email</td>
						  <td width="61%"  class="usermenucontent" >&nbsp;</td>
						</tr>
						<tr>
						  <td><span class="usermenucontent">
						    <input class="textfeild" type="text" name="contact_name" size="8" value="<?=$_REQUEST['contact_name']?>"  />
						  </span></td>
						  <td  class="usermenucontent" ><input class="textfeild" type="text" name="contact_email" size="8" value="<?=$_REQUEST['contact_email']?>"  /></td>
						  <td align="left"  class="usermenucontent" > <div class="cart_shop_cont">
                          <div>
                            <input name="address_search" type="button" class="inner_btn_red" id="address_search" value="<?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDERSSBOOK_SEARCH_BUTTON'])?>" onclick="document.my_addressbook.submit()" />
                          </div>
                        </div> </td>
						</tr>
						<tr>
						<td colspan="3" align="right" class="usermenucontent">                       
						</td>
						</tr>
						</table>
					<table width="100%" border="0" cellpadding="3" cellspacing="0" class="reg_table">
                      <?php
					  if($start_var['pages'] > 0)
					  {
					  ?>
				     <tr>
						<td colspan="5" class="pagingcontainertd_normal" align="center">
						<?php
							$path = '';
							$query_string .= "contact_name=".$_REQUEST['contact_name']."&contact_email=".$_REQUEST['contact_email']."";
							paging_footer($path,$query_string,$tot_cnt,$start_var['pg'],$start_var['pages'],'',$pg_variable,'Contact Address',$pageclass_arr); 	
						?></td>
					</tr>
                    <?php
					  }
					?>
				    <tr >
				      <td colspan="5" align="right" valign="middle" >
					   <?php
					  	if($db->num_rows($ret_list_addressbook))
						{
						?>
						<input name="clearaddr_button" type="button" class="inner_btn_red" id="clearaddr_button" value="<?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_DELETE'])?>" onclick="address_delete_all(document.my_addressbook)" />
					    <?php
						}
						?>
					  </td>
				      </tr>
				    <tr >
				  	<td align="left" valign="middle" class="ordertableheader" ><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_CHECKBOX'])?></td>
					<td align="left" valign="middle" class="ordertableheader" ><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_SLNO'])?></td>
					<td align="left" valign="middle" class="ordertableheader"><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_CONTACTNAME'])?></td>
					<td align="left" valign="middle" class="ordertableheader"><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_CONTACTEMAIL'])?></td>
					<td align="center" valign="middle" class="ordertableheader"><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_EDIT'])?>
				      <input type="hidden" name="hid_editid" />
					 <input type="hidden" name="hid_deleteid" /> </td>
				    </tr>
				  <?php
				if($db->num_rows($ret_list_addressbook))
				{  
					$srno = 1;
					while($row_list_addressbook = $db->fetch_array($ret_list_addressbook))
					{
					?>
					<tr  class="edithreflink_tronmouse" title="View Details" onmouseover="this.className='edithreflink_trmouseout'" onmouseout="this.className='edithreflink_tronmouse'">
					 <td align="left" valign="middle" class="ordertabletdcolorB" ><input type="checkbox" name="address_addressid[]" value="<?=$row_list_addressbook['address_id']?>" /></td>
					   <td align="left" valign="middle" class="ordertabletdcolorB" ><?php echo $srno++?></td>
						<td align="left" valign="middle" class="ordertabletdcolorB" ><?=$row_list_addressbook['address_title']."&nbsp;".$row_list_addressbook['address_fname']."&nbsp;".$row_list_addressbook['address_mname']."&nbsp;".$row_list_addressbook['address_lname']?></td>
						<td align="left" valign="middle" class="ordertabletdcolorB"><?=$row_list_addressbook['address_email']?></td>
						<td align="center" valign="top" class="ordertabletdcolorB"><img src="<?php url_site_image('edit.gif')?>" onclick="javascript:edit_address(document.my_addressbook,'<? echo $row_list_addressbook['address_id']; ?>');" alt="Edit" title="Edit" /><!--<img src="<?php //url_site_image('delete.gif')?>" onclick="javascript:address_delete(document.my_addressbook,'<? //echo $row_list_addressbook['address_id']; ?>');" alt="Delete" title="Delete" />-->				</td>
					</tr>
					 <? 
					}
				 }// End of check number of rows
				 else 
				 {
				 ?>
				   <tr>
						<td align="center" valign="middle" class="shoppingcartcontent" colspan="5" >
							<?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_NOTFOUND'])?>						</td>	
				   </tr>	
				 <?
				 
				 } 
				?>
				  <tr>
					<td align="center" colspan="5"> <div class="cart_shop_cont_addr"><div><input name="address_add" type="button" class="inner_btn_red" id="address_add" value="<?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_ADD'])?>" onclick="javascript:add_address(document.my_addressbook)" />	</div></div></td>
				</tr> 
				</table>
					 </div>
						</div>
					</div>
					<div class="reg_shlf_inner_bottom"></div>
				</div>
				</div>
				</form>	
				<script language="javascript">
					function address_delete_all(frm) {
					   atleastone = 0;
					   for(i=0;i<frm.elements.length;i++)
						{
	
						   if (frm.elements[i].type =='checkbox' && frm.elements[i].name=='address_addressid[]')
								{
									if (frm.elements[i].checked==true)
									{
										atleastone = 1;
									}	
								}
								
						}
					  if (atleastone==0)
						{
							alert('Please select address to delete');
							return false;
						}	
						else
						{
						if(confirm('Are you sure you want to delete selected Addressbook?'))
						{
							frm.action_purpose.value = 'delete_addressbook_all';
						    frm.submit();
						}
						}	
						
					}
					function address_delete(frm,val) {
					if(confirm('Are you sure you want to delete This Addressbook?'))
						{
						frm.action_purpose.value = 'delete_addressbook';
						frm.hid_deleteid.value = val;
						frm.submit();
						}
					}
					function add_address(frm) {
						frm.action_purpose.value = 'add_addressbook';
						frm.submit();
					}
					function edit_address(frm,val) {
						frm.action_purpose.value = 'edit_addressbook';
						frm.hid_editid.value = val;
						frm.submit();
					}
				</script>
				<?
		}
		
		
		function Add_AddressBook(){
				global $alert,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$short,$long,$medium;
				$session_id = session_id();	// Get the session id for the current section
				$Captions_arr['ADDRESSBOOK'] 	= getCaptions('ADDRESSBOOK');
				$customer_id = get_session_var("ecom_login_customer"); // get customer id
				$HTML_treemenu = '<div class="tree_menu_conA">
								  <div class="tree_menu_topA"></div>
								  <div class="tree_menu_midA">
									<div class="tree_menu_content">
									  <ul class="tree_menu">
									<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
									<li><a href="'.url_link('myaddressbook.html',1).'">'.stripslash_normal($Captions_arr['ADDRESSBOOK']['TREE_MENU_ADDRESSBOOK']).'</a></li>
									 <li>'.stripslash_normal($Captions_arr['ADDRESSBOOK']['TREE_MENU_ADD_ADDRESSBOOK']).'</li>
									</ul>
									  </div>
								  </div>
								  <div class="tree_menu_bottomA"></div>
								</div>';
			
					?>
					<form method="post" name="add_address" class="frm_cls" action="<?php url_link('myaddressbook.html')?>">
					<input type="hidden" name="fpurpose" value="" />
					<input type="hidden" name="address_id" value="" />
					<input type="hidden" name="action_purpose" />
					<?=$HTML_treemenu?>
					<?php
					if($alert)
					{ 
					$HTML_alert .= 
					'<div class="cart_msg_outerA">
					<div class="cart_msg_topA"></div>
							<div class="cart_msg_txt">';
									$HTML_alert .= $alert;
					$HTML_alert .=	'</div>
					<div class="cart_msg_bottomA"></div>
					</div>';
					}
					echo $HTML_alert;
				?>
			<div class="reg_shlf_outr">
				<div class="reg_shlf_inner">
					<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
					<?php
					if($Captions_arr['ADDRESSBOOK']['TREE_MENU_ADD_ADDRESSBOOK']){
					?>
					<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['ADDRESSBOOK']['TREE_MENU_ADD_ADDRESSBOOK'])?> </span></div></div>
					<? }?>
						<div class="reg_shlf_cont_div">
							<div class="reg_shlf_pdt_con">	
			<table width="100%" border="0" cellpadding="0" cellspacing="2" class="reg_table">
							<tr>
					<td  colspan="2"  align="right" class="regiconentA" ><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['TITLE']);?></td>
					<td  align="center" >:</td>
					<td  align="left" class="regi_txtfeildA" >
					<select name="customer_title" class="regiinput" id="customer_title" >
					<option value="">Select</option>
					<option value="Mr." <?=($_REQUEST['customer_title']=='Mr.')?"selected":''?>>Mr.</option>
					<option value="Mrs." <?=($_REQUEST['customer_title']=='Mrs.')?"selected":''?>>Mrs.</option>
					<option value="Miss." <?=($_REQUEST['customer_title']=='Miss.')?"selected":''?>>Miss.</option> 
					<option value="M/S." <?=($_REQUEST['customer_title']=='M/S.')?"selected":''?>>M/S.</option>
					</select></td>
				</tr>
				<tr>
					<td  colspan="2"  align="right" class="regiconentA" ><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_FNAME']);?><span class="redtext">*</span> </td>
					<td  align="center" >:</td>
					<td  align="left"  class="regi_txtfeildA"><input type="text" name="txt_fname" value="<?=$_REQUEST['txt_fname'] ?>" maxlength="<?=$short?>"/></td>
				</tr> 
				<tr>
					<td  colspan="2"  align="right"  class="regiconentA"><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_MNAME']);?></td>
					<td  align="center" >:</td>
					<td  align="left" class="regi_txtfeildA" ><input type="text" name="txt_mname" value="<?=$_REQUEST['txt_mname'] ?>" maxlength="<?=$short?>"/></td>
				</tr>
				<tr>
					<td  colspan="2"  align="right" class="regiconentA" ><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_LNAME']);?><span class="redtext">*</span> </td>
					<td  align="center" >:</td>
					<td  align="left" class="regi_txtfeildA" ><input type="text" name="txt_lname" value="<?=$_REQUEST['txt_lname'] ?>" maxlength="<?=$short?>"/></td>
				</tr>
				<tr>
					<td  colspan="2"  align="right" class="regiconentA" ><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_ADDRESS']);?></td>
					<td  align="center" >:</td>
					<td  align="left" class="regi_txtfeildA" ><textarea name="address" cols="25" rows="5"><?=$_REQUEST['address'] ?></textarea></td>
				</tr>
				<tr>
					<td  colspan="2"  align="right" class="regiconentA" ><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_EMAIL']);?><span class="redtext">*</span></td>
					<td  align="center" >:</td>
					<td  align="left" class="regi_txtfeildA" ><input type="text" name="email" value="<?=$_REQUEST['email'] ?>" maxlength="<?=$medium?>"/></td>
				</tr>
				<tr>
					<td  colspan="2"  align="right" class="regiconentA" ><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_PHONE']);?></td>
					<td  align="center" >:</td>
					<td  align="left"  class="regi_txtfeildA"><input type="text" name="phonenumber" value="<?=$_REQUEST['phonenumber'] ?>" maxlength="<?=$short?>"/></td>
				</tr>
				<tr>
					<td  colspan="2"  align="right" class="regiconentA" ><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_MOBILE']);?></td>
					<td  align="center" >:</td>
					<td  align="left"  class="regi_txtfeildA"><input type="text" name="mobnumber" value="<?=$_REQUEST['mobnumber'] ?>" maxlength="<?=$short?>"/></td>
				</tr>
				<tr>
					<td  colspan="2"  align="right" class="regiconentA" ><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_FAX']);?></td>
					<td width="1%"  align="center" >:</td>
				  <td width="74%"  align="left" class="regi_txtfeildA" ><input type="text" name="faxnumber" value="<?=$_REQUEST['faxnumber'] ?>" maxlength="<?=$short?>"/></td>
				</tr>
                <tr>
                	<td  colspan="2"  align="right" class="regiconentA" >&nbsp;</td>
                    <td width="1%"  align="center" ></td>
					<td align="left"><input class="inner_btn_red" type="button" name="Submit" value=" <?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADD_ADDRESS']);?> " onclick="javascript:add_newaddress(document.add_address)" /></td>	
                </tr>
				
				</table>
				
				</div>
						</div>
					</div>
					<div class="reg_shlf_inner_bottom"></div>
				</div>
				</div>	
				</form>	
				<script language="javascript">
				function add_newaddress(frm)
				{
					//alert(feildmsg);
					fieldRequired 		= Array('txt_fname','txt_lname','email');
					fieldDescription 	= Array('<?=stripslash_javascript($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_FNAME'])?>','<?=stripslash_javascript($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_LNAME'])?>','<?=stripslash_normal($Captions_javascript['ADDRESSBOOK']['ADDRESSBOOK_EMAIL'])?>');
					fieldEmail 			= Array('email');
					fieldConfirm 		= Array();
					fieldConfirmDesc  	= Array();
					fieldNumeric 		= Array();
					fieldSpecChars 		= Array('txt_fname','txt_mname','txt_lname','phonenumber');
					fieldCharDesc       = Array('First Name','Middle Name','Last Name','Phone');
					if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)){
						   frm.action_purpose.value = 'insert_addressbook';
						   frm.submit();
					}
					
				}
				</script>
				<?
		}
		
			function Edit_AddressBook(){
				global $addressid,$alert,$ecom_siteid,$db,$ecom_hostname,$Settings_arr,$ecom_themeid,$default_layout,$Captions_arr,$inlineSiteComponents,$short,$long,$medium;
				$session_id = session_id();	// Get the session id for the current section
				$Captions_arr['ADDRESSBOOK'] 	= getCaptions('ADDRESSBOOK');
				$customer_id = get_session_var("ecom_login_customer"); // get customer id
				$HTML_treemenu = '<div class="tree_menu_conA">
								  <div class="tree_menu_topA"></div>
								  <div class="tree_menu_midA">
									<div class="tree_menu_content">
									  <ul class="tree_menu">
									<li><a href="'.url_link('',1).'">'.stripslash_normal($Captions_arr['COMMON']['TREE_MENU_HOME_LINK']).'</a></li>
									<li><a href="'.url_link('myaddressbook.html',1).'">'.stripslash_normal($Captions_arr['ADDRESSBOOK']['TREE_MENU_ADDRESSBOOK']).'</a></li>
									 <li>'.stripslash_normal($Captions_arr['ADDRESSBOOK']['TREE_MENU_EDIT_ADDRESSBOOK']).'</li>
									</ul>
									  </div>
								  </div>
								  <div class="tree_menu_bottomA"></div>
								</div>';
			
					?>
					<form method="post" name="edit_address" class="frm_cls" action="<?php url_link('myaddressbook.html')?>">
					<input type="hidden" name="fpurpose" value="" />
					<input type="hidden" name="address_id" value="" />
					<input type="hidden" name="action_purpose" />
					<input type="hidden" name="addressid" value="<?PHP echo $addressid; ?>" />
					<?=$HTML_treemenu?>
					<?
					if($alert)
				{ 
				$HTML_alert .= 
				'<div class="cart_msg_outerA">
				<div class="cart_msg_topA"></div>
						<div class="cart_msg_txt">';
							  $HTML_alert .=$alert;
				$HTML_alert .=	'</div>
				<div class="cart_msg_bottomA"></div>
				</div>';
				}
				echo $HTML_alert;
					?>
					<div class="reg_shlf_outr">
				<div class="reg_shlf_inner">
					<div class="reg_shlf_inner_top"></div>
					<div class="reg_shlf_inner_cont">
					<?php
					if($Captions_arr['ADDRESSBOOK']['TREE_MENU_EDIT_ADDRESSBOOK']){
					?>
					<div class="reg_shlf_hdr_outr"><div class="reg_shlf_hdr_in"><span><?=stripslash_normal($Captions_arr['ADDRESSBOOK']['TREE_MENU_EDIT_ADDRESSBOOK'])?> </span></div></div>
					<? }?>
						<div class="reg_shlf_cont_div">
							<div class="reg_shlf_pdt_con">	
				 	 <table width="100%" border="0" cellpadding="0" cellspacing="2" class="reg_table">
					<?php				
                    $sql = "SELECT 
							address_title, address_fname, address_mname, address_lname, 
							address_address, address_email,address_phone, address_mobile, address_fax 
						 		FROM 
								   customer_addressbook 
								     WHERE 
									    address_id='".$addressid."'";
				$res = $db->query($sql);
				$row = $db->fetch_array($res);	
				 $customer_title = $row['address_title'];					
				?>
					  <tr>
					    <td  colspan="2"  align="right"  class="regiconentA"><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['TITLE']);?></td>
					    <td  align="center" >:</td>
					    <td  align="left" class="regi_txtfeildA" >
			<select name="customer_title" class="regiinput" id="customer_title" >
			<option value="">Select</option>
			<option value="Mr." <?=($customer_title=='Mr.')?"selected":''?>>Mr.</option>
			<option value="Mrs." <?=($customer_title=='Mrs.')?"selected":''?>>Mrs.</option>
			<option value="Miss." <?=($customer_title=='Miss.')?"selected":''?>>Miss.</option> 
			<option value="M/S." <?=($customer_title=='M/S.')?"selected":''?>>M/S.</option>
			</select></td>
				    </tr>
					  <tr>
					    <td  colspan="2"  align="right" class="regiconentA" ><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_FNAME']);?> <span class="redtext">*</span> </td>
					    <td  align="center" >:</td>
					    <td  align="left" class="regi_txtfeildA" ><input type="text" name="txt_fname" value="<?PHP echo $row['address_fname']; ?>" maxlength="<?=$short?>"/></td>
				    </tr> 
					  <tr>
					    <td  colspan="2"  align="right" class="regiconentA" ><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_MNAME']);?>  </td>
					    <td  align="center" >:</td>
					    <td  align="left" class="regi_txtfeildA"><input type="text" name="txt_mname" value="<?PHP echo $row['address_mname']; ?>" maxlength="<?=$short?>"/></td>
				    </tr>
					  <tr>
					    <td  colspan="2"  align="right" class="regiconentA"><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_LNAME']);?><span class="redtext">*</span> </td>
					    <td  align="center" >:</td>
					    <td  align="left" class="regi_txtfeildA"><input type="text" name="txt_lname" value="<?PHP echo $row['address_lname']; ?>"  maxlength="<?=$short?>"/></td>
				    </tr>
					  <tr>
					    <td  colspan="2"  align="right" class="regiconentA" valign="top"><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_ADDRESS']);?></td>
					    <td  align="center" >:</td>
					    <td  align="left" class="regi_txtfeildA"><textarea name="address" cols="25" rows="5" class="regiinput"><?PHP echo $row['address_address']; ?></textarea></td>
				    </tr>
				      <tr>
				        <td  colspan="2"  align="right" class="regiconentA"><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_EMAIL']);?><span class="redtext">*</span></td>
				        <td  align="center" >:</td>
				        <td  align="left" class="regi_txtfeildA"><input type="text" name="email" value="<?PHP echo $row['address_email']; ?>"  maxlength="<?=$medium?>"/></td>
			        </tr>
				      <tr>
				        <td  colspan="2"  align="right" class="regiconentA"><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_PHONE']);?></td>
				        <td  align="center" >:</td> 
				        <td  align="left" class="regi_txtfeildA"><input type="text" name="phonenumber" value="<?PHP echo $row['address_phone']; ?>" maxlength="<?=$short?>"/></td>
			        </tr>
			          <tr>
			            <td  colspan="2"  align="right" class="regiconentA"><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_MOBILE']);?></td>
			            <td  align="center" >:</td>
			            <td  align="left" class="regi_txtfeildA"><input type="text" name="mobnumber" value="<?PHP echo $row['address_mobile']; ?>"  maxlength="<?=$short?>"/></td>
		            </tr>
		            <tr>
					 <td  colspan="2"  align="right" class="regiconentA"><?php echo stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_FAX']);?> </td>
				     <td width="1%"  align="center" >:</td>
				     <td width="75%"  align="left" class="regi_txtfeildA"><input type="text" name="faxnumber" value="<?PHP echo $row['address_fax']; ?>"  maxlength="<?=$short?>"/></td>
					</tr>
				 
				   <tr>
							<td align="center" valign="middle" class="shoppingcartcontent" colspan="4" >&nbsp;</td>
						</tr>
				</table>
			<div style="margin-right:34%">
								<div class="cart_shop_cont"><div>
												<input class="inner_btn_red" type="button" name="Submit" value=" <?=stripslash_normal($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_UPDATE'])?> " onclick="javascript:update_newaddress(document.edit_address)" />
								</div></div>
								</div>
							</div>
						</div>
					</div>
					<div class="reg_shlf_inner_bottom"></div>
				</div>
				</div>	
				</form>	
				<script language="javascript">
				function update_newaddress(frm)
				{
					//alert(feildmsg);
					fieldRequired 		= Array('txt_fname','txt_lname','email');
					fieldDescription 	= Array('<?=stripslash_javascript($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_FNAME'])?>','<?=stripslash_javascript($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_LNAME'])?>','<?=stripslash_javascript($Captions_arr['ADDRESSBOOK']['ADDRESSBOOK_EMAIL'])?>');
					fieldEmail 			= Array('email');
					fieldConfirm 		= Array();
					fieldConfirmDesc  	= Array();
					fieldNumeric 		= Array();
					fieldSpecChars 		= Array('txt_fname','txt_mname','txt_lname','phonenumber');
					fieldCharDesc       = Array('First Name','Middle Name','Last Name','Phone');
					if(Validate_Form_Objects(frm,fieldRequired,fieldDescription,fieldEmail,fieldConfirm,fieldConfirmDesc,fieldNumeric,fieldSpecChars,fieldCharDesc)){
					frm.action_purpose.value = 'update_addressbook';
					frm.submit();
					}
				
				}
					
				</script>
				<?
		}			
	}	
?>