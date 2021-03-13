<?php
	// ###############################################################################################################
	// 				Function which holds the display logic of static pages under the site heaers to be shown when called using ajax;
	// ###############################################################################################################
	function show_siteheaders_maininfo($header_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_hostname ;
		$sql="SELECT header_title,header_filename,header_period_change_required,header_startdate,header_enddate,header_hide,header_showinall,header_caption FROM site_headers WHERE sites_site_id=$ecom_siteid AND header_id=".$header_id;
		$res=$db->query($sql);
		$row=$db->fetch_array($res);
		?>
		 <div class="editarea_div">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
						 <?php
						 }
						 ?>
		<tr>
          <td width="21%" align="left" valign="middle" class="tdcolorgray" >Header Title  <span class="redtext">*</span> </td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray"><input name="header_title" type="text" id="header_title" value="<?=$row['header_title']?>" /></td>
          <td width="11%" align="left" valign="middle" class="tdcolorgray">Select Image <span class="redtext">*</span> </td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray"><input name="header_filename" type="file" id="header_filename" />
            &nbsp;
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_HEADERS_IMG_SEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><a href="javascript:show_calendar('frmAddSiteHeaders.header_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"></a>&nbsp;&nbsp;&nbsp;&nbsp;
		  <input name="chk_resizeheader" type="checkbox" id="chk_resizeheader" value="1" checked="checked" />
Resize Image </td>
        </tr>
		 <tr>
		   <td align="left" valign="middle" class="tdcolorgray" >Show in all pages </td>
		   <td width="2%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" id="header_showinall" name="header_showinall" value="1" <? if($row['header_showinall']==1) echo "checked"?> /></td>
		   <td width="22%" align="left" valign="middle" class="tdcolorgray"><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_HEADERS_SHOWALL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><a href="javascript:show_calendar('frmAddSiteHeaders.header_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"></a></td>
		   <td align="left" valign="middle" class="tdcolorgray" >Hidden </td>
		   <td align="left" valign="middle" class="tdcolorgray"><input type="radio" name="header_hide" value="1" <? if($row['header_hide']==1) echo "checked";?> />
Yes
  <input type="radio" name="header_hide"  value="0" <? if($row['header_hide']==0) echo "checked";?> />
No</td>
		   <td align="left" valign="middle" class="tdcolorgray"><?php /*?><a href="#" class="edittextlink_header" onclick="showImagePopup('http://<?=$ecom_hostname?>/images/<?=$ecom_hostname?>/<?=$row['header_filename']?>','<?=$ecom_hostname?>');">View Header Image</a><?php */?></td>
    </tr>
	<tr>
	<td align="left" valign="top" class="tdcolorgray"  >Header Caption</td>
	   <td align="left" valign="middle" class="tdcolorgray" colspan="5" >
	   <textarea name="header_caption" id="header_caption" rows="3" cols="40"><?=stripslashes($row['header_caption'])?></textarea>
	   <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_HEADERS_CAPTION')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	</tr>
		 <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Periodic Change Required</td>
		   <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" id="header_period_change_required" name="header_period_change_required" onclick="change_show_date_period()" value="1" <? if($row['header_period_change_required']==1) echo "checked"?> /></td>
		   <td align="left" valign="middle" class="tdcolorgray"><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_HEADERS_PERIOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a><a href="javascript:show_calendar('frmAddSiteHeaders.header_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"></a></td>
		   <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		   <td width="13%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
           <td width="31%" align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
    </tr>
	
	<?
				 
			if($row['header_period_change_required']==1)
		    {
			  $display='';
			  
			  $active_start_arr 		= explode(" ",$row['header_startdate']);
			  $active_end_arr 			= explode(" ",$row['header_enddate']);
			  $active_starttime_arr 	= explode(":",$active_start_arr[1]);
						$active_start_hr			= $active_starttime_arr[0];
						$active_start_mn			= $active_starttime_arr[1];
						$active_start_ss			= $active_starttime_arr[2];	
						$active_endttime_arr 		= explode(":",$active_end_arr[1]);
						$active_end_hr				= $active_endttime_arr[0];
						$active_end_mn				= $active_endttime_arr[1];
						$active_end_ss				= $active_endttime_arr[2];	
			  $display='';
			  $exp_header_displaystartdate=explode("-",$active_start_arr[0]);
			  $val_header_displaystartdate=$exp_header_displaystartdate[2]."-".$exp_header_displaystartdate[1]."-".$exp_header_displaystartdate[0];
			  $exp_header_displayenddate=explode("-",$active_end_arr[0]);
			  $val_header_displayenddate  =$exp_header_displayenddate[2]."-".$exp_header_displayenddate[1]."-".$exp_header_displayenddate[0];

			}
			else
			{
			  $display='none';
			}
				for($i=0;$i<60;$i++)
					$option .= '<option value="'.$i.'">'.$i.'</option>';
				for($i=0;$i<=23;$i++)
					$houroption .= '<option value="'.$i.'">'.$i.'</option>';	
		  		  ?>
		 
		 <tr  id="show_date_period" style="display:<?=$display?>">
		   <td colspan="6" align="left" valign="middle" class="tdcolorgray" ><table width="100%" cellpadding="0" cellspacing="2" border="0">
               <tr>
                 <td align="left" valign="middle"  >&nbsp;</td>
                 <td align="left" valign="middle"  >&nbsp;</td>
                 <td align="left" valign="middle" >&nbsp;</td>
                 <td width="5%"  align="left" valign="middle" >&nbsp;</td>
                 <td width="6%"  align="left" valign="middle" >Hrs</td>
                 <td width="6%"  align="left" valign="middle" >Min</td>
                 <td width="6%"  align="left" valign="middle" >Sec</td>
                 <td width="40%"  align="left" valign="middle" >&nbsp;</td>
               </tr>
               <tr>
                 <td width="15%" align="left" valign="middle"  >&nbsp;</td>
                 <td width="14%" align="left" valign="middle"  >Start Date<span class="redtext">*</span></td>
                 <td width="8%" align="left" valign="middle" >
                     <input class="input" type="text" name="header_startdate" size="8" value="<?=$val_header_displaystartdate?>"  />                 </td>
                 <td  align="left" valign="middle" ><a href="javascript:show_calendar('frmEditSiteHeaders.header_startdate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a> &nbsp;</td>
                 <td  align="left" valign="middle" ><select name="header_starttime_hr" id="header_starttime_hr">
										<option value="<?php echo $active_start_hr?>"><?php echo $active_start_hr?></option>
										<?php echo $houroption?>
				 </select></td>
                 <td  align="left" valign="middle" ><select name="header_starttime_mn" id="header_starttime_mn">
										<option value="<?php echo $active_start_mn?>"><?php echo $active_start_mn?></option>
										<?php echo $option?>
				 </select></td>
                 <td  align="left" valign="middle" ><select name="header_starttime_ss" id="header_starttime_ss">
										<option value="<?php echo $active_start_ss?>"><?php echo $active_start_ss?></option>
										<?php echo $option?>
				 </select></td>
                 <td  align="left" valign="middle" >&nbsp;</td>
               </tr>
               <tr>
                 <td width="15%" align="left" valign="middle"  >&nbsp;</td>
                 <td width="14%" align="left" valign="middle"  >End Date<span class="redtext">*</span></td>
                 <td width="8%" align="left" valign="middle" ><input class="input" type="text" name="header_enddate" size="8" value="<?=$val_header_displayenddate?>" />                 </td>
                 <td  align="left" valign="middle" ><a href="javascript:show_calendar('frmEditSiteHeaders.header_enddate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0" /></a> </td>
                 <td  align="left" valign="middle" ><select name="header_endtime_hr" id="header_endtime_hr">
										<option value="<?php echo $active_end_hr?>"><?php echo $active_end_hr?></option>
										<?php echo $houroption?>
				 </select></td>
                 <td  align="left" valign="middle" ><select name="header_endtime_mn" id="header_endtime_mn">
										<option value="<?php echo $active_end_mn?>"><?php echo $active_end_mn?></option>
										<?php echo $option?>
										</select></td>
                 <td  align="left" valign="middle" ><select name="header_endtime_ss" id="header_endtime_ss">
										<option value="<?php echo $active_end_ss?>"><?php echo $active_end_ss?></option>
										<?php echo $option?>
				 </select></td>
                 <td  align="left" valign="middle" >&nbsp;</td>
               </tr>
           </table></td>
		  <!-- <td colspan="3" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>-->
    </tr>
       
        <tr>
          <td colspan="6" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
        <?php 
        if($row['header_filename']!='')
        {?>
		 <tr>
		   <td colspan="6" align="center" valign="middle" class="tdcolorgray" >
		   <img src="http://<?=$ecom_hostname?>/images/<?=$ecom_hostname?>/<?=$row['header_filename']?>" border="0" />
		  <?php 
		  if(trim($row['header_caption'])!='')
		  {
		  ?>
		   &nbsp;&nbsp;&nbsp;<input type="button" name='remove_img' id='remove_img' value='Remove Image' class='red' onclick='call_ajax_removeimg()'/>
		  <?php 
		  }
		  ?> 
		   </td>
    	</tr>
    	<?php 
        }
    	?>
		 <tr>
          <td colspan="6" align="center" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
		</table>
		</div>
		<div class="editarea_div">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td align="right" valign="middle"><input type="submit" name="submit" value="Save" id="submit" class="red" /></td>
		</tr>
		</table>
		</div>
		<?
	}	
	/*function show_static_pages_list($group_id,$alert='')
	{
		global $db,$ecom_siteid ;
			 // Get the list of static pages under this psge gruop
				$sql_pages = "SELECT sps.id,sp.page_id,sp.title,sps.static_pages_hide,sp.pname,sps.static_pages_order
 FROM static_pages sp,static_pagegroup_static_page_map sps
  WHERE sps.static_pagegroup_group_id=$group_id AND 
	sps.static_pages_page_id=sp.page_id
 ORDER BY static_pages_order";
				$ret_pages = $db->query($sql_pages);
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_pages))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditStaticGroup,\'checkboxpages[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditStaticGroup,\'checkboxpages[]\')"/>','Slno.','Page Name','Order','Hidden');
							$header_positions=array('center','center','left','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_pages = $db->fetch_array($ret_pages))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxpages[]" value="<?php echo $row_pages['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=stat_page&fpurpose=edit&page_id=<?=$row_pages['page_id']?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_pages['pname']);?></a></td>
									<td class="<?php echo $cls?>" align="center"><input type="text" name="static_pages_order_<?php echo $row_pages['id']?>" id="static_pages_order_<?php echo $row_pages['id']?>" value="<?php echo stripslashes($row_pages['static_pages_order']);?>" size="3"/></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_pages['static_pages_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="staticpages_norec" id="staticpages_norec" value="1" />
								  No Static Pages Assigned for this Static Page group </td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}*/
	

	// ###############################################################################################################
	// 				Function which holds the display logic of categories assigned to a Site Header to be shown when called using ajax;
	// ###############################################################################################################
	function show_category_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of categories added to this Site Header
				$sql_categories = "SELECT hdc.id,hdc.site_headers_header_id,hdc.product_categories_category_id,pc.category_name,pc.category_hide,hdc.header_display_category_hide  FROM header_display_category hdc,product_categories pc WHERE pc.category_id=hdc.product_categories_category_id AND  site_headers_header_id=$edit_id ORDER BY category_name";
				$ret_categories = $db->query($sql_categories);
	
					
					   if($_REQUEST['showinall']==1)
						{
						?>	 
							<table width="100%" cellpadding="0" cellspacing="1" border="0">
								<tr>
									<td colspan="4" align="center">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_SITE_HEADER_SHOWALL_MSG')?>	</td>
								</tr>
							</table>			
						<?php	
							return;			
						}
					?>
					<div class="editarea_div">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					 <?php
					 // Check whether categories are Assiged to this Site Headers
						$sql_categories_in_headers = "SELECT id FROM header_display_category
									 WHERE site_headers_header_id=$edit_id";
						$ret_categories_in_headers = $db->query($sql_categories_in_headers);
						
					 
					 ?>
					 <tr>
					  <td align="left" colspan="7" class="helpmsgtd"><div class="helpmsg_divcls">
					 <?php echo get_help_messages('EDIT_SITE_HEADER_CAT_SUBMSG')?></div>	
					  </td>
					  </tr>
					<tr>
					  <td colspan="6" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="document.frmEditSiteHeaders.fpurpose.value='list_assign_categories';document.frmEditSiteHeaders.submit();" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_HEADERS_ASSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
							<?php
							if ($db->num_rows($ret_categories_in_headers))
							{
							?>
								<div id="categoryunassign_div" class="unassign_div" >
								<!--Change Hidden Status to 
								<?php
									//$categories_status = array(0=>'No',1=>'Yes');
									//echo generateselectbox('categories_chstatus',$categories_status,0);
								?>
								<input name="category_chstatus" type="button" class="red" id="category_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('category','checkboxcategory[]')" />
								<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_HEADERS_CHSTATUS_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
									-->		
								&nbsp;&nbsp;&nbsp;<input name="categories_unassign" type="button" class="red" id="categories_unassign" value="Un Assign" onclick="call_ajax_deleteall('category','checkboxcategory[]')" />
								<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_HEADERS_UNASSCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
							<?php
							}				
							?></td>
      			  </tr>
		
					
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_categories))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditSiteHeaders,\'checkboxcategory[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditSiteHeaders,\'checkboxcategory[]\')"/>','Slno.','Category Name','Hidden');
							$header_positions=array('center','center','left','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_category = $db->fetch_array($ret_categories))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxcategory[]" value="<?php echo $row_category['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_category['product_categories_category_id'];?>&catname=&parentid=&catgroupid=&start=&pg=&records_per_page= &sort_by=&sort_order=" class="edittextlink" title="Edit"><?php echo stripslashes($row_category['category_name']);?></a></td>
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_category['category_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small"><input type="hidden" name="category_norec" id="category_norec" value="1" />
								  No Categories Assigned for this Site Header </td>
								</tr>
						<?php
						}
						?>	
				</table>	
				</div>
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of products assigned to the Adverts to be shown when called using ajax;
	// ###############################################################################################################
	function show_product_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of paroduct assigned for the static page group
				$sql_products = "SELECT 
										p.product_id,p.product_name,hdp.id,p.product_hide 
								FROM
										products p,header_display_product hdp
								WHERE 
										hdp.products_product_id=p.product_id  
								AND 
										hdp.sites_site_id=$ecom_siteid
								AND 
										site_headers_header_id=$edit_id 
								ORDER BY product_name";
				$ret_products = $db->query($sql_products);
	
	
					   if($_REQUEST['showinall']==1)
						{
						?>
							<table width="100%" cellpadding="0" cellspacing="1" border="0">
								<tr>
									<td colspan="4" align="center">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_SITE_HEADER_SHOWALL_MSG')?>	</td>
								</tr>
							</table>			
						<?php	
							return;			
						}
					?>
					 <div class="editarea_div">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					 <?php
					 // Check whether Products are added to this Site Header
						$sql_product_in_headers = "SELECT products_product_id FROM header_display_product
									 WHERE site_headers_header_id=$edit_id";
						$ret_product_in_headers = $db->query($sql_product_in_headers);		
					 
					 ?>
					 <tr>
					  <td align="left" colspan="7" class="helpmsgtd"><div class="helpmsg_divcls">
					 <?php echo get_help_messages('EDIT_SITE_HEADER_PROD_SUBMSG')?></div>	
					  </td>
					  </tr>
					<tr>
					  <td colspan="6" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="document.frmEditSiteHeaders.fpurpose.value='list_assign_products';document.frmEditSiteHeaders.submit();" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_HEADERS_ASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
							<?php
							if ($db->num_rows($ret_product_in_headers))
							{
							?>
								<div id="productsunassign_div" class="unassign_div">
								<!--Change Hidden Status to -->
								<?php
								/*	$products_status = array(0=>'No',1=>'Yes');
									echo generateselectbox('product_chstatus',$products_status,0);*/
								?>
								<!--<input name="product_chstatus" type="button" class="red" id="product_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('product','checkboxproducts[]')" />
								<a href="#" onmouseover ="ddrivetip('<?//get_help_messages('EDIT_SITE_HEADERS_CHSTATUSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
								-->					
								&nbsp;&nbsp;&nbsp;<input name="products_unassign" type="button" class="red" id="products_unassign" value="Un Assign" onclick="call_ajax_deleteall('product','checkboxproducts[]')" />
								<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_HEADERS_UNASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
							<?php
							}				
							?></td>
					</tr>
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_products))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditSiteHeaders,\'checkboxproducts[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditSiteHeaders,\'checkboxproducts[]\')"/>','Slno.','Product Name','Hidden');
							$header_positions=array('center','center','left','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_products = $db->fetch_array($ret_products))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxproducts[]" value="<?php echo $row_products['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_products['product_id'];?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_products['product_name']);?></a></td>
									
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_products['product_hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No product Assigned to this Site Header. 
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
	// 				Function which holds the display logic of Pages assinged to the Site Headers when called using ajax;
	// ###############################################################################################################
	function show_assign_pages_list($edit_id,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of Static Pages assigned to Page groups
				 $sql_assign_pages = "SELECT 
				 							sp.page_id,sp.title,hds.id,sp.hide 
										FROM
											static_pages sp,header_display_static hds
										WHERE 
											hds.static_pages_page_id=sp.page_id  
										AND 	
											hds.sites_site_id=$ecom_siteid
										AND 
											site_headers_header_id=$edit_id 
										ORDER BY title";
				$ret_assign_pages = $db->query($sql_assign_pages);
	
	
					   if($_REQUEST['showinall']==1)
						{
						?>
							<table width="100%" cellpadding="0" cellspacing="1" border="0">
								<tr>
									<td colspan="4" align="center">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="4" align="center" class="errormsg"> <?php echo get_help_messages('EDIT_SITE_HEADER_SHOWALL_MSG')?>	</td>
								</tr>
							</table>			
						<?php	
							return;			
						}
					?>
					<div class="editarea_div">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					 <tr>
					  <td align="left" colspan="7" class="helpmsgtd"><div class="helpmsg_divcls">
					 <?php echo get_help_messages('EDIT_SITE_HEADER_STAT_SUBMSG')?></div>	
					  </td>
					  </tr>
					<?php
					 // Check whether Pages are added to this Site Headers
						$sql_assigned_pages = "SELECT static_pages_page_id FROM header_display_static
									 WHERE site_headers_header_id=$edit_id";
						$ret_assigned_pages = $db->query($sql_assigned_pages);		
					 
					 ?>
					<tr>
					  <td colspan="6" align="right" valign="middle" class="tdcolorgray" ><input name="Addmore" type="button" class="red" id="Addmore" value="Assign More" onclick="document.frmEditSiteHeaders.fpurpose.value='list_assign_pages';document.frmEditSiteHeaders.submit();" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SITE_HEADERS_ASSSTAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
							<?php
							if ($db->num_rows($ret_assigned_pages))
							{
							?>
								<div id="assign_pagesunassign_div" class="unassign_div" >
								<!--Change Hidden Status to -->
								<?php
									/*$products_status = array(0=>'No',1=>'Yes');
									echo generateselectbox('assign_pages_chstatus',$products_status,0);*/
								?>
								<!--<input name="assign_pages_chstatus" type="button" class="red" id="assign_pages_chstatus" value="Change" onclick="call_ajax_changestatuspagesall('assign_pages','checkboxassignpages[]')" />
								<a href="#" onmouseover ="ddrivetip('Allows to Change the status of selected Site Header(s) assigned in the Site Header. Select the Page(s), select the new status and press the \'Change\' button.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>-->	
										
								&nbsp;&nbsp;&nbsp;<input name="assign_pages_unassign" type="button" class="red" id="assign_pages_unassign" value="Un Assign" onclick="call_ajax_deleteall('assign_pages','checkboxassignpages[]')" />
								<a href="#" onmouseover ="ddrivetip('Allows to Un assign the selected Product(s)  from the Site Header .')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>	
							<?php
							}				
							?></td>
					</tr>
					<?php 
						if($alert)
						{
					?>
							<tr>
								<td colspan="6" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				 		}
						if ($db->num_rows($ret_assign_pages))
						{
							$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditSiteHeaders,\'checkboxassignpages[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditSiteHeaders,\'checkboxassignpages[]\')"/>','Slno.','Title','Hidden');
							$header_positions=array('center','center','left','center','center');
							$colspan = count($table_headers);
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_assign_pages = $db->fetch_array($ret_assign_pages))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxassignpages[]" value="<?php echo $row_assign_pages['id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=stat_page&fpurpose=edit&checkbox[0]=<?php echo $row_assign_pages['page_id'];?>" class="edittextlink" title="Edit"><?php echo stripslashes($row_assign_pages['title']);?></a></td>
									
									<td class="<?php echo $cls?>" align="center"><?php echo ($row_assign_pages['hide']==1)?'Yes':'No'?></td>
								</tr>
							<?php
							}
						}
						else
						{
						?>
								<tr>
								  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">No Static Pages Assigned to this Site Header.
								    <input type="hidden" name="assign_pages_norec" id="assign_pages_norec" value="1" /></td>
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