<?php

	// ###############################################################################################################
	// 				Function which holds the display logic of category main info to be shown when called using ajax;
	// ###############################################################################################################

	function show_catmaininfo($editid,$alert='')
	{
		global $db,$ecom_siteid,$ecom_hostname,$ecom_themeid,$ecom_site_mobile_api;
		if($editid)
		{
			$sql_categorty 	= "SELECT * FROM product_categories WHERE category_id=$editid ";
			$ret_category 	= $db->query($sql_categorty);
			if($db->num_rows($ret_category))
			{
				$row_category = $db->fetch_array($ret_category);
			}
			$disp_ext_arr		= array(-1);
		}
	?><div class="editarea_div">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="fieldtable" >
	<tr>
    <td width="17%" align="left" valign="middle" class="tdcolorgray" >Category name <span class="redtext">*</span> </td>
    <td align="left" valign="middle" class="tdcolorgray" width="37%"><input name="cat_name" type="text" class="input" size="25" value="<?php echo stripslashes($row_category['category_name'])?>" maxlength="150" /></td>
    <td width="21%" align="left" valign="middle" class="tdcolorgray">Hide Category </td>
    <td width="25%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="cat_hide" value="1" <?php echo ($row_category['category_hide']==1)?'checked="checked"':''?> />
      Yes
      <input name="cat_hide" type="radio" value="0" <?php echo ($row_category['category_hide']==0)?'checked="checked"':''?> />
      No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CAT_PROD_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" >Parent Category</td>
    <td align="left" valign="top" class="tdcolorgray">
	<?php
			$parent_array = generate_category_tree(0,0,false,false);
			echo generateselectbox('parent_id',$parent_array,$row_category['parent_id']);
		  ?>
    <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_PARENT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    <td colspan="2" align="left" valign="top" class="tdcolorgray"><!--Show any product Image -->
        <input name="chk_category_turnoff_treemenu" type="checkbox" id="chk_category_turnoff_treemenu" value="1" <?php echo ($row_category['category_turnoff_treemenu']==1)?'checked="checked"':''?> />
      Turn Off Tree menu in Category Details page </td>
  </tr>
  <?php
  $sql_them = "SELECT allow_special_category_details 
  							FROM 
								themes 
							WHERE 
								theme_id = $ecom_themeid  
							LIMIT 
								1";
	$ret_them = $db->query($sql_them);
	if ($db->num_rows($ret_them))
	{
		$row_them = $db->fetch_array($ret_them);
		if ($row_them['allow_special_category_details']==1)
			$spcat = true;
		else
			$spcat = false;
	}							
  ?>
  <tr>
    <td align="left" valign="top" class="tdcolorgray">
	<?php 
		if($spcat==true)
		{
	?>	
	Category Details Display Type
	<?php
		}
	?>
	</td>
    <td align="left" valign="top" class="tdcolorgray">
	<?php 
		if($spcat==true)
		{
	?>	
	<select name="special_detailspage_required">
	<option value="0" <?php echo ($row_category['special_detailspage_required']==0)?'selected':''?>>Normal</option>
	<option value="1" <?php echo ($row_category['special_detailspage_required']==1)?'selected':''?>>Special</option>
    </select>
	<?php
		}
	?>
    </td>
    <td colspan="2" align="left" valign="top" class="tdcolorgray"><input name="chk_category_turnoff_pdf" type="checkbox" id="chk_category_turnoff_pdf" value="1" <?php echo ($row_category['category_turnoff_pdf']==1)?'checked="checked"':''?>/>
      Turn Off PDF in Category Details page </td>
  </tr>
  <tr>
	  <?php
			if($ecom_site_mobile_api==1)
			{
			?>
    <td align="left" valign="top" class="tdcolorgray">Show In Mobile Application</td>
    <td align="left" valign="top" class="tdcolorgray"><input name="in_mobile_api_sites" type="checkbox" id="in_mobile_api_sites" value="1" <?php echo ($row_category['in_mobile_api_sites']==1)?'checked="checked"':''?>/>
    <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_CAT_MOB_API')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
    </td>
      <?php
			}
			else
			{
				?>
				<td align="left" class="tdcolorgray">&nbsp;</td>
				<td align="left" class="tdcolorgray">&nbsp;</td> 
				<?php
			}
    ?>
    <td colspan="2" align="left" valign="top" class="tdcolorgray"><input name="category_turnoff_noproducts" type="checkbox" id="category_turnoff_noproducts" value="1" <?php echo ($row_category['category_turnoff_noproducts']==1)?'checked="checked"':''?>/>
Hide &quot;No Products&quot; message in Category Details Page </td>
  </tr>
   <?php
	if($ecom_site_mobile_api==1)
	{
	?>
    <tr>
    <td align="left" valign="top" class="tdcolorgray">Parent For Mobile Application</td>
    <td align="left" valign="top" class="tdcolorgray"><?php
    $mobile_parent_array = generate_mobile_api_category_tree(0,0,false,false);
			echo generateselectbox('mobile_api_parent_id',$mobile_parent_array,$row_category['mobile_api_parent_id']);
		  ?>
    <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('MOB_EDIT_PROD_CAT_PARENT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </td>
    <td colspan="2" align="left" valign="top" class="tdcolorgray">&nbsp; </td>
  </tr>
       <?php
	}
	?>

        <tr>
     <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
     <td align="right" valign="top" class="tdcolorgray" colspan="3">
     <table cellpadding="0" cellspacing="0" border="0" width="100%">
         <tr>
         <td width="14%" align="left" valign="top" class="tdcolorgray_url">Website URL&nbsp;&nbsp;:</td>
         <td width="86%" align="left" valign="top" class="tdcolorgray_url"><?php url_category($row_category['category_id'],$row_category['category_name'],-1);?></td>
            </tr>
        </table></td>
        </tr>
  <tr>
    <td colspan="4" align="left" valign="top" class="seperationtd"><strong>Category Menu Selection</strong></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" >Category Menu  </td>
    <td align="left" valign="top" class="tdcolorgray">
	<?php
			$ext_grp_arr	= array(0);
			// Get the list of already added category group 
			$sql_catgroup = "SELECT catgroup_id FROM product_categorygroup_category WHERE category_id=".$editid;
			$ret_catgroup = $db->query($sql_catgroup);
			if ($db->num_rows($ret_catgroup))
			{
				while ($row_catgroup = $db->fetch_array($ret_catgroup))
				{
					$ext_grp_arr[] = $row_catgroup['catgroup_id'];
				}
			}
		  	$group_arr 		= array();
			// Get the list of all category groups for this site
			$sql_groups = "SELECT catgroup_id,catgroup_name FROM product_categorygroup WHERE sites_site_id=$ecom_siteid ORDER BY catgroup_name";
			$ret_groups = $db->query($sql_groups);
			if ($db->num_rows($ret_groups))
			{
				while ($row_groups = $db->fetch_array($ret_groups))
				{
					$grpid	= $row_groups['catgroup_id'];
					$group_arr[$grpid] = stripslashes($row_groups['catgroup_name']);
				}	
			}
			echo generateselectbox('group_id[]',$group_arr,$ext_grp_arr,'','',5);
		  ?>    		  
      <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_CG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    <td align="left" valign="top" class="tdcolorgray"><?pho /*Default Category Group*/?></td>
    <td align="left" valign="top" class="tdcolorgray">&nbsp; 	</td>
  </tr>
  <tr>
    <td colspan="4" align="left" valign="top" class="tdcolorgray">&nbsp;</td>
  </tr>
  
	<!-- Google base category list change starts here --> 
  
         
   <tr>
    <td colspan="4" align="left" valign="top" class="tdcolorgray">&nbsp;</td>
  </tr>      
         
  <tr>
    <td colspan="4" align="left" valign="top" class="seperationtd"><strong>Descriptions</strong></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" >Short Description </td>
    <td colspan="3" align="left" valign="top" class="tdcolorgray">
	<input name="short_desc" type="text" size="100" value="<?php echo stripslashes($row_category['category_shortdescription']); ?>" maxlength="400" /></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" >Long Description </td>
    <td colspan="3" align="left" valign="top" class="tdcolorgray">
	<?php
		/*$editor 			= new FCKeditor('long_desc') ;
		$editor->BasePath 	= '/console/js/FCKeditor/';
		$editor->Width 		= '650';
		$editor->Height 	= '300';
		$editor->ToolbarSet = 'BshopWithImages';
		$editor->Value 		= stripslashes($row_category['category_paid_description']);
		$editor->Create() ;*/
	   ?>
	   <textarea style="height:300px; width:500px" id="long_desc" name="long_desc"><?=stripslashes($row_category['category_paid_description'])?></textarea>
					   </td>
  </tr>
  <tr>
           <td align="left" valign="top" class="tdcolorgray" >Bottom Description (SEO Purpose)</td>
           <td colspan="3" align="left" valign="top" class="tdcolorgray">
		    <textarea style="height:300px; width:500px" id="bottom_desc" name="bottom_desc"><?=stripslashes($row_category['category_bottom_description'])?></textarea>
		   </td>
         </tr>
  <tr>
  <tr>
       <td colspan="4" align="left" valign="top" class="seperationtd"><strong>Google Base Product Category Mapping</strong>
       <?php
           $showmsg_val = "Google recommend to map our categories to the google product categories. This option allows to do this mapping. This mapping will be used while generating the google base data. <br>Currently the <strong>\'Google product category\'</strong> attribute only needs to be provided for products that belong to the following seven product categories in feeds that target the US, UK, Germany, and France:<br><br> <strong>1. Apparel & Accessories > Clothing<br>2. Apparel & Accessories > Shoes<br>3. Apparel & Accessories (Note that submitting this value for clothing and shoes is not acceptable.)<br>4. Media > Books<br> 5. Media > DVDs & Videos<br>6. Media > Music<br>7. Software > Video Game Software</strong><br>For products that belong to other product categories, providing this attribute is recommended. ";
            ?>
           <a href="#" onmouseover ="ddrivetip('<?=$showmsg_val?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
       </td>
         </tr>
		 
        <tr>
        <td colspan="4" valign="top" class="tdcolorgray" align="right">
		<?php
			if($row_category['google_taxonomy_id'] != 0)
			{
				$sql_default_google_category = "SELECT google_taxonomy_keyword FROM google_productcategory_taxonomy WHERE google_taxonomy_id= ".$row_category['google_taxonomy_id']."";
				$ret_default_google_category = $db->query($sql_default_google_category);
				if ($db->num_rows($ret_default_google_category))
				{
					$row_default_google_category = $db->fetch_array($ret_default_google_category);
				}
		?>	<input type="text" name="google_category_selected" id="google_category_selected" value="<?php echo $row_default_google_category['google_taxonomy_keyword'];?>" style="width:850px; float:left;" readonly="readonly" />
		<?php
			}
		?>
        <input type="hidden" name="google_product_category" id="google_product_category" value="<?php echo $row_category['google_taxonomy_id']; ?>" />
	    </td>
        </tr>
		 <tr>
        <td colspan="4" valign="top" class="tdcolorgray" align="left">
		<div id="googlebase_cat_id" style="width:100%; text-align:left;"></div>
		</td>
		</tr>
		 <tr>
		<td  class="tdcolorgray">&nbsp;</td>
		<td  class="tdcolorgray"><input type="button" onClick="call_ajax_setgooglebase()" value="Set Google Base Product Category" id="set_googlebase" class="red" name="set_googlebase"></td>
		<td colspan="2"></td>
		</tr>
	</table></div>
	<div class="editarea_div">
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
	 <td colspan="4" align="right" valign="middle" class="tdcolorgray" >
      <input name="cat_Submit" type="submit" class="red" value="Save" />
	  <input name="cat_Submit" type="submit" class="red" value="Save & Return" /></td>
    </tr>
	</table>
	</div>
	<?php		
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of images assigned to categories to be shown when called using ajax;
	// ###############################################################################################################
	function show_catimage_list($editid,$alert='')
	{
		
		global $db,$ecom_siteid,$ecom_hostname;
		if($editid)
		{
			$sql_categorty 	= "SELECT * FROM product_categories WHERE category_id=$editid";
			$ret_category 	= $db->query($sql_categorty);
			if($db->num_rows($ret_category))
			{
				$row_category = $db->fetch_array($ret_category);
			}
			$disp_ext_arr		= array(-1);
	?>
		<div class="editarea_div">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<?php
			if ($alert)
			{
		?>
				<tr>
					<td align="center" class="errormsg"><?php echo $alert?>
					</td>
				</tr>
		<?php
			}
		?>
			<tr>
			<td colspan="4" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_CAT_CATIMAGE_SUBHEAD')?></div>
			</td>
			</tr>
		 <tr>
		 <td align="left">
		 <table width="100%" cellpadding="1" cellspacing="1" border="0">
		 <tr id="cat_image_tr2" >
		  <td width="3%" align="left" valign="middle" nowrap="nowrap">&nbsp;
	       <input class="input" type="checkbox" name="category_showimageofproduct"  value="1" <? if($row_category['category_showimageofproduct']==1) echo "checked";?> onclick="handle_images_from_product(this)"></td>
          <td width="17%" align="left" valign="middle" class="tdcolorgray">Show any product Image&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_SHOW_PROD_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td width="2%" align="left" valign="middle"><input name="category_turnoff_mainimage" type="checkbox" id="category_turnoff_mainimage" value="1" <?php echo ($row_category['category_turnoff_mainimage']==1)?'checked="checked"':''?>/></td>
          <td width="24%" align="left" valign="middle" class="tdcolorgray">Turn Off &quot;Main Image&quot; in Category Details page<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_SHOW_NO_MAIN_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td width="2%" align="right"> <input name="category_turnoff_moreimages" type="checkbox" id="category_turnoff_moreimages" value="1" <?php echo ($row_category['category_turnoff_moreimages']==1)?'checked="checked"':''?>/>           </td>
          <td width="23%" align="left" class="tdcolorgray">Turn Off &quot;More Images&quot; in Category Details page<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_SHOW_NO_MORE_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td width="29%" colspan="2" align="right"  class="tdcolorgray_buttons">
		  <div id="catimg_operation_main" <?php echo ($row_category['category_showimageofproduct']==1)?'style="display:none"':''?>>
		  <input name="Assign_Image" type="button" class="red" id="Assign_Image" value="Assign More" onclick="normal_assign_ImageAssign('<?php echo $editid?>','<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['catgroupid']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>');" />
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CAT_PROD_ASSIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;
			&nbsp;
            <?php
				// Get the list of images which satisfy the current critera from the images table
				$sql_img = "SELECT b.id,a.image_id,b.image_title,b.image_order,a.image_gallerythumbpath,a.images_directory_directory_id FROM images a,images_product_category b WHERE 
							a.sites_site_id = $ecom_siteid 
							AND b.product_categories_category_id=$editid 
							AND a.image_id=b.images_image_id ORDER BY b.image_order";	
				$ret_img = $db->query($sql_img);
				if ($db->num_rows($ret_img))
				{
				?>
					<div id="catimgunassign_div" class="unassign_div">
					  <input name="catimg_unassign" type="button" class="red" id="catimg_unassign" value="Un assign" onclick="call_ajax_deleteall('catimg','checkbox_img[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CAT_PROD_UNASSIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>					</div>
					<?php
				}				
				?>
		   </div>		   </td>
        </tr>
		 </table>
		 </td>
		 </tr>
		<?php	
				if($db->num_rows($ret_img))
				{
?>
						<tr id="img_tr_1" <?php echo ($row_category['category_showimageofproduct']==1)?'style="display:none"':''?>>
						<td align="left" class="tdcolorgray_buttons">
						<img src="images/checkbox.gif" border="0" onclick="select_all_img(document.frmEditProductCategory,'checkbox_img[]')" alt="Check all images" title="Check all images"/><img src="images/uncheckbox.gif" border="0" onclick="select_none_img(document.frmEditProductCategory,'checkbox_img[]')" alt="Uncheck all images" title="Uncheck all images"/>
						Please tick the images whose details to be saved</td>
						</tr>
<?php					
							
				?>
							<tr id="img_tr_2" <?php echo ($row_category['category_showimageofproduct']==1)?'style="display:none"':''?>>
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
												  <input type="checkbox" name="checkbox_img[]" id="checkbox_img[]" value="<?php echo $row_img['id']?>" onclick="handle_imagesel('<?php echo $row_img['id']?>')"/>
												  </td>
												  </tr>
												  <tr>
												  <td align="center" class="imagelist_imgtd" colspan="2" ondblclick="if (confirm('Are you sure you want to go to Image edit page?')==true) {window.location='home.php?request=img_gal&fpurpose=edit_img&edit_id=<?php echo $row_img['image_id']?>&curdir_id=<?php echo $row_img['images_directory_directory_id']?>&org_id=<?php echo $editid?>&back_frm=prod_cat'}">
												  <img src="http://<?php echo $ecom_hostname."/images/$ecom_hostname/".$row_img['image_gallerythumbpath']?>">
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
						<tr id="img_tr_3" <?php echo ($row_category['category_showimageofproduct']==1)?'style="display:none"':''?>>
							  <td align="center" class="redtext"> No Images assigned for current product category
							  <input type="hidden" name="catimg_norec" id="catimg_norec" value="1"  />
							  </td>
						</tr>	  
<?php	
					}
?>		
				<tr>
				<td align="center">
				<input name="catimg_save" type="button" class="red" id="catimg_save" value="Save Details" onclick="call_ajax_savedetails('catimg','checkbox_img[]')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CAT_PROD_CHDETIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				</td>			  
				</tr>
</table></div>
<?php
		}
	}
	function show_subcat_list($editid,$alert='',$mobile_only=0)
	{
		global $db,$ecom_siteid;
		if($mobile_only==1)
		{
			$add_condition = ' in_mobile_api_sites=1 AND mobile_api_parent_id='.$editid;
			$main_msg = 'Subcategories of current category to be displayed in mobile application will be listed in following section ';//get_help_messages('EDIT_PROD_CAT_SUBCAT_SUBHEAD');
			$order_by = 'category_order_mobile';
		}
		else
		{
			$add_condition = " parent_id=$editid";	
			$main_msg = get_help_messages('EDIT_PROD_CAT_SUBCAT_SUBHEAD');
			$order_by = 'category_order';
		}	
			 // Get the list of assigned products
				$sql_cat = "SELECT category_id,parent_id,category_name,category_order,category_hide,category_order_mobile FROM product_categories WHERE $add_condition AND sites_site_id=$ecom_siteid ORDER BY $order_by ";
				//echo $sql_cat;
				$ret_cat = $db->query($sql_cat);
	?>
					<div class="editarea_div">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProductCategory,\'checkboxsubcat[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProductCategory,\'checkboxsubcat[]\')"/>','Slno.','Category Name','Category Order','Hide');
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
					?>
						<tr>
						<td colspan="5" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=$main_msg?></div>
						</td>
						</tr>
					<tr>
					<td align="right" colspan="5" class="tdcolorgray_buttons">
					<?php
					if($mobile_only==1)
					{
					?>
						<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_SubCategoryAssignMobile('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['catgroupid']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $editid?>');" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_ASSCATCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
						<?php
						if ($db->num_rows($ret_cat))
						{
						?>
						<div id="subcatunassign_div" class="unassign_div">
						<input name="catorder_unassign" type="button" class="red" id="catorder_unassign" value="Un assign" onclick="call_ajax_deleteall('subcatmobile','checkboxsubcat[]')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SUBCAT_PROD_UNASSIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
						</div>	
						<?php
						}	
					}
					else
					{
					?>
						<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_SubCategoryAssign('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['catgroupid']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $editid?>');" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_ASSCATCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
						<?php
						if ($db->num_rows($ret_cat))
						{
						?>
						<div id="subcatunassign_div" class="unassign_div">
						<input name="catorder_unassign" type="button" class="red" id="catorder_unassign" value="Un assign" onclick="call_ajax_deleteall('subcat','checkboxsubcat[]')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SUBCAT_PROD_UNASSIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
						</div>	
						<?php
						}	
					}				
						?>		  
					</td>
					</tr>
					<?php	
						if ($db->num_rows($ret_cat))
						{
							
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_cat = $db->fetch_array($ret_cat))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
								if($mobile_only==1)
									$order = stripslashes($row_cat['category_order_mobile']);
								else
									$order = stripslashes($row_cat['category_order']);
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxsubcat[]" value="<?php echo $row_cat['category_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=prod_cat&fpurpose=edit&checkbox[0]=<?php echo $row_cat['category_id']?>&catname=<?=$_REQUEST['catname']?>&parentid=<?=$_REQUEST['parentid']?>&catgroupid=<?=$_REQUEST['catgroupid']?>&start=<?php echo $start?>&pg=<?php echo $pg?>&records_per_page=<?php echo $records_per_page?>&sort_by=<?php echo $sort_by?>&sort_order=<?php echo $sort_order?>" title="edit" class="edittextlink"><?php echo stripslashes($row_cat['category_name']);?></a></td>
									<td align="center" class="<?php echo $cls?>"><input type="text" name="cat_order_<?php echo $row_cat['category_id']?>" id="cat_order_<?php echo $row_cat['category_id']?>" value="<?php echo $order;?>" size="6" /></td>
								    <td align="center" class="<?php echo $cls?>"><?php if(stripslashes($row_cat['category_hide'])==1) echo 'Yes'; else echo 'No' ;?></td>

								</tr>
							<?php
							}
						?>
						<tr>
							<td colspan="5" align="center">
							<?php
							if ($db->num_rows($ret_cat))
							{
								if($mobile_only==1)
								{
							?>
									<input name="catorder_save" type="button" class="red" id="catorder_save" value="Save Order" onclick="call_ajax_savedetails('subcatmobile','checkboxsubcat[]')" />
									<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SUBCAT_PROD_SAVEORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
							<?php
								}
								else
								{
							?>
									<input name="catorder_save" type="button" class="red" id="catorder_save" value="Save Order" onclick="call_ajax_savedetails('subcat','checkboxsubcat[]')" />
									<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SUBCAT_PROD_SAVEORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
							<?php	
								}
							}
							?>
							</td>
						</tr>
						<?php	
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="subcat_norec" id="subcat_norec" value="1" />
								  No Subcategories found.</td>
								</tr>
						<?php
						}
						?>	
				</table></div>	
	<?php	
	}
	function show_product_list($editid,$alert='')
	{
		global $db,$ecom_siteid;
			 // Get the list of assigned products
				$sql_cat = "SELECT a.product_id,a.product_name,a.product_webprice,a.product_hide,b.product_order 
									FROM 
										products a, product_category_map b 
									WHERE 
										a.product_id = b.products_product_id 
										AND b.product_categories_category_id=$editid 
									ORDER BY 
										b.product_order ASC";
				$ret_cat = $db->query($sql_cat);
	?>
					<div class="editarea_div">
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProductCategory,\'checkboxprods[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProductCategory,\'checkboxprods[]\')"/>','Slno.','Product Name','Product Order','Hidden?');
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
					?>
						<tr>
						<td colspan="5" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_CAT_PROD_SUBHEAD')?></div>
						</td>
						</tr>
					<tr>
					<td align="right" colspan="5" class="tdcolorgray_buttons">
						<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_ProductAssign('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['catgroupid']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $editid?>');" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_GROUP_ASSCATCAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
						<?php
						if ($db->num_rows($ret_cat))
						{
						?>
						<div id="produnassign_div" class="unassign_div">
						<input name="catorder_unassign" type="button" class="red" id="catorder_unassign" value="Un assign" onclick="call_ajax_deleteall('prods','checkboxprods[]')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SUBCAT_PROD_UNASSIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
						</div>	
						<?php
						}				
						?>		  
					</td>
					</tr>
					<?php	
						if ($db->num_rows($ret_cat))
						{
							
							echo table_header($table_headers,$header_positions); 
							$cnt = 1;
							while ($row_cat = $db->fetch_array($ret_cat))
							{
								$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
							?>
								
								<tr>
									<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxprods[]" value="<?php echo $row_cat['product_id'];?>" /></td>
									<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
									<td align="left" class="<?php echo $cls?>"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<?php echo $row_cat['product_id']?>" title="edit" class="edittextlink"><?php echo stripslashes($row_cat['product_name']);?></a></td>
									<td align="center" class="<?php echo $cls?>"><input type="text" name="prod_order_<?php echo $row_cat['product_id']?>" id="prod_order_<?php echo $row_cat['product_id']?>" value="<?php echo stripslashes($row_cat['product_order']);?>" size="6" /></td>
								    <td align="center" class="<?php echo $cls?>"><?php if(stripslashes($row_cat['product_hide'])=='Y') echo 'Yes'; else echo 'No' ;?></td>

								</tr>
							<?php
							}
						?>
						<tr>
							<td colspan="5" align="center">
							<?php
							if ($db->num_rows($ret_cat))
							{
							?>
							<input name="catorder_save" type="button" class="red" id="catorder_save" value="Save Order" onclick="call_ajax_savedetails('prods','checkboxprods[]')" />
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SUBCAT_PRODLIST_SAVEORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
							<?php
							}
							?>
							</td>
						</tr>
						<?php	
						}
						else
						{
						?>
								<tr>
								  <td colspan="4" align="center" valign="middle" class="norecordredtext_small">
								  <input type="hidden" name="subcat_norec" id="subcat_norec" value="1" />
								  No Products mapped with this category.</td>
								</tr>
						<?php
						}
						?>	
				</table></div>	
	<?php	
	}
	// ###############################################################################################################
	// 				Function which holds the display logic of category main info to be shown when called using ajax;
	// ###############################################################################################################

	function show_category_settings($editid,$alert='')
	{
		global $db,$ecom_siteid,$ecom_hostname,$ecom_themeid;
		
			$arr_style 	= $val_arr = array();
			$val_arr['None']  = 'None';	  
			  	$sql_style	= "SELECT image_listingstyles FROM themes WHERE theme_id=".$ecom_themeid;
				$ret_style 	= $db->query($sql_style);
				if ($db->num_rows($ret_style))
				{
					$row_style	= $db->fetch_array($ret_style);
				 	$arr_style	= explode(',',$row_style['image_listingstyles']);
					$val_arr[0]==0;
					if (count($arr_style))
					{
						foreach($arr_style as $v)
						{
							$temp_arr = explode("=>",$v);
							$val_arr[$temp_arr[0]]=trim($temp_arr[1]);	 // this array will be used for all the following image type drop down boxes
						}
					}				
				}	
				
		if($editid)
		{
			$sql_categorty 	= "SELECT * FROM product_categories WHERE category_id=$editid";
			$ret_category 	= $db->query($sql_categorty);
			if($db->num_rows($ret_category))
			{
				$row_category = $db->fetch_array($ret_category);
			}
			$arr_prod_style 	= $grp_type = $subcatlstng_arr = $subcatlst_arr = array();
			$sql_style_prod	= "SELECT product_listingstyles,subcategory_listingstyles FROM themes WHERE theme_id=".$ecom_themeid;
			$ret_style_prod 	= $db->query($sql_style_prod);
			if ($db->num_rows($ret_style_prod))
			{
				$row_style_prod	= $db->fetch_array($ret_style_prod);
				$arr_prod_style	= explode(',',$row_style_prod['product_listingstyles']);
				$grp_type[0]==0;
				if (count($arr_prod_style))
				{
					foreach($arr_prod_style as $v)
					{
						$temp_arr = explode("=>",$v);
						$grp_type[$temp_arr[0]]=trim($temp_arr[1]);	 // this array will be used for all the following image type drop down boxes
					}
				}				
				$subcatlstng_arr	= explode(',',$row_style_prod['subcategory_listingstyles']);
				$subcatlst_arr[0]==0;
				if (count($subcatlstng_arr))
				{
					foreach($subcatlstng_arr as $v)
					{
						$temp_arr = explode("=>",$v);
						$subcatlst_arr[$temp_arr[0]]=trim($temp_arr[1]);	 // this array will be used for all the following image type drop down boxes
					}
				}
			 }	
		?><div class="editarea_div">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="fieldtable">
		<?php
			if($alert)
			{
		?>
			<tr>
			<td colspan="4" class="errormsg" align="center"><?php echo $alert?></td>
			</tr>
		<?php
			}
		?>	
		<tr>
			<td colspan="4" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_CAT_SETTING')?></div></td>
		</tr>
		<tr>
			<td colspan="4" align="left" valign="top" class="seperationtd" ><strong>Display Settings for Subcategories &amp; Products of this category</strong></td>
		</tr>
		<tr>
			<td width="16%" align="left" valign="top" class="tdcolorgray" >Subcategory List</td>
			<td width="29%" align="left" valign="top" class="tdcolorgray" ><?php 
			$subcat_list = array('Middle'=>'Show in Middle Area Only','List'=>'Show in Menu Only','Both'=>'Both in Middle and Menu');
			echo generateselectbox('category_subcatlisttype',$subcat_list,$row_category['category_subcatlisttype']);
		?>
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_SUBTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			<td width="18%" align="left" valign="top" class="tdcolorgray" >Subcategory Display Method </td>
			<td width="37%" align="left" valign="top" class="tdcolorgray" >
			<?php 
				echo generateselectbox('category_subcatlistmethod',$subcatlst_arr,$row_category['category_subcatlistmethod']);
			?>
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_DISPMETHOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</td>
		</tr>
		<tr>
		  <td align="left" valign="top" class="tdcolorgray" >SubcategoryImage Listing</td>
		  <td align="left" valign="top" class="tdcolorgray" ><?PHP 
		$subcateg_showimage = $row_category['subcategory_showimagetype'];
		if($subcateg_showimage=='') $subcateg_showimage = 'Medium';
		echo generateselectbox('subcategory_showimagetype',$val_arr,$subcateg_showimage); ?>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_SUBCATEGLISTING_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
		  <td colspan="2" align="left" valign="top" class="tdcolorgray" >Fields to be displayed for Subcategories </td>
		  </tr>
		<tr>
		  <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
		  <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
		  <td colspan="2" align="left" valign="top" class="tdcolorgray" ><table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="4%">&nbsp;</td>
              <td><input name="category_showname" type="checkbox" id="category_showname" value="1" <?php echo ($row_category['category_showname']==1)?'checked="checked"':''?>/>
                Subcategory Name </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input name="category_showimage" type="checkbox" id="category_showimage" value="1" <?php echo ($row_category['category_showimage']==1)?'checked="checked"':''?>/>
                Subcategory Image </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input name="category_showshortdesc" type="checkbox" id="category_showshortdesc" value="1" <?php echo ($row_category['category_showshortdesc']==1)?'checked="checked"':''?>/>
                Subcategory Short description </td>
            </tr>
          </table></td>
		  </tr>
		<tr>
		  <td align="left" valign="top" class="tdcolorgray" >Product List</td>
		  <td align="left" valign="top" class="tdcolorgray" ><?php 
			$product_list = array('menu'=>'Show in Menu','middle'=>'Show in Middle Area','both' => 'Both in Middle and Menu');
			echo generateselectbox('product_displaywhere',$product_list,$row_category['product_displaywhere']);
		?>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td align="left" valign="top" class="tdcolorgray" >Product Display Method</td>
		  <td align="left" valign="top" class="tdcolorgray" >
		  <?php
						//$grp_type = array('OneinRow'=>'One in a Row','ThreeinRow'=>'Three in a Row');
						echo generateselectbox('product_displaytype',$grp_type,$row_category['product_displaytype']);
			?>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_DISP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		<tr>
		<td align="left" valign="top" class="tdcolorgray" >Product Ordering </td>
		<td align="left" valign="top" class="tdcolorgray" ><?php
					$catgrsort_arr = array('custom'=>'Custom Ordering','product_name'=>'Product Name','price'=>'Price','product_id'=>'Date Added');
					echo generateselectbox('product_orderfield',$catgrsort_arr,$row_category['product_orderfield']); 
					echo "&nbsp;";
					$sort_ord = array('ASC'=>'Asc','DESC'=>'Desc');
					echo generateselectbox('product_orderby',$sort_ord,$row_category['product_orderby']); 
			?>
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_PORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		<td colspan="2" align="left" valign="top" class="tdcolorgray" >Fields to be displayed for Products <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_DISPPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> <span class="redtext">*</span></td>
		</tr>
		<tr>
		<td align="left" valign="top" class="tdcolorgray"  colspan="2">&nbsp;</td>
		<td colspan="2" align="left" valign="top" class="tdcolorgray" ><table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr>
		<td width="4%">&nbsp;</td>
		<td width="96%" align="left"><input name="product_showimage" type="checkbox" value="1" <?php echo ($row_category['product_showimage']==1)?'checked="checked"':''?> />
		Product Image </td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td align="left"><input name="product_showtitle" type="checkbox" value="1" <?php echo ($row_category['product_showtitle']==1)?'checked="checked"':''?> />
		Product Title </td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td align="left"><input name="product_showshortdescription" type="checkbox" value="1" <?php echo ($row_category['product_showshortdescription']==1)?'checked="checked"':''?> />
		Product Description </td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td align="left"><input name="product_showprice" type="checkbox" value="1" <?php echo ($row_category['product_showprice']==1)?'checked="checked"':''?> />
		Product Price </td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		  <td align="left"><input name="product_showrating" type="checkbox" value="1" <?php echo ($row_category['product_showrating']==1)?'checked="checked"':''?> />
Product  Rating </td>
		  </tr>
		  <tr>
		  <td>&nbsp;</td>
		  <td align="left"><input name="product_showbonuspoints" type="checkbox" value="1" <?php echo ($row_category['product_showbonuspoints']==1)?'checked="checked"':''?> />
Product  Bonus Points </td>
		  </tr>
		</table></td>
		</tr>
		
		<tr>
		<td colspan="4" align="center" class="tdcolorgray">&nbsp;		</td>
		</tr>
		</table></div>
		<div class="editarea_div">
			<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td align="right" valign="middle" class="tdcolorgraynormal" >
				<input name="cat_Submit" type="button" class="red" value="Save" onclick="call_ajax_savesettings('display_settings')" />	
				</td>
			</tr>
			</table>
		</div>
		<?php	
		}
	}
	function list_labelgroups($editid,$alert='')
	{
		global $db,$ecom_siteid;
		// Get the list of assigned label groups
		$sql_grp = "SELECT a.group_id,a.group_name,a.group_hide,a.group_name_hide, b.map_id, b.product_labels_group_group_id,b.product_categories_category_id  
							FROM 
								product_labels_group a, product_category_product_labels_group_map b 
							WHERE 
								a.group_id = b.product_labels_group_group_id  
								AND b.product_categories_category_id = $editid 
							ORDER BY 
								a.group_name ASC";
		$ret_grp = $db->query($sql_grp);
?>
			<div class="editarea_div">
			<table width="100%" cellpadding="1" cellspacing="1" border="0">
			<?php 
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProductCategory,\'checkboxprods[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProductCategory,\'checkboxprods[]\')"/>','Slno.','Label Group Name','Hidden?','Group Name Hidden?');
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
			?>
				<tr>
				<td colspan="5" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_CAT_LBLGRP_SUBHEAD')?></div>
				</td>
				</tr>
			<tr>
			<td align="right" colspan="5" class="tdcolorgray_buttons">
				<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_ProductLabelGroupAssign('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['catgroupid']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $editid?>');" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_ASSLBLGRP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
				<?php
				if ($db->num_rows($ret_grp))
				{
				?>
				<div id="produnassign_div" class="unassign_div">
				<input name="catorder_unassign" type="button" class="red" id="catorder_unassign" value="Un assign" onclick="call_ajax_deleteall('labelgroup','checkboxprods[]')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_CAT_UNASSLBLGRP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
				</div>	
				<?php
				}				
				?>		  
			</td>
			</tr>
			<?php	
				if ($db->num_rows($ret_grp))
				{
					
					echo table_header($table_headers,$header_positions); 
					$cnt = 1;
					while ($row_grp = $db->fetch_array($ret_grp))
					{
						$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
					?>
						<tr>
							<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxprods[]" value="<?php echo $row_grp['map_id'];?>" /></td>
							<td width="5%" align="center" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
							<td align="left" class="<?php echo $cls?>"><a href="home.php?request=prod_label_groups&fpurpose=edit&checkbox[0]=<?php echo $row_grp['group_id']?>" title="edit" class="edittextlink"><?php echo stripslashes($row_grp['group_name']);?></a></td>
							<td align="center" class="<?php echo $cls?>"><?php if(stripslashes($row_grp['group_hide'])==1) echo 'Yes'; else echo 'No' ;?></td>
							<td align="center" class="<?php echo $cls?>"><?php if(stripslashes($row_grp['group_name_hide'])==1) echo 'Yes'; else echo 'No' ;?></td>
						</tr>
					<?php
					}
				}
				else
				{
				?>
					<tr>
					  <td colspan="5" align="center" valign="middle" class="norecordredtext_small">
					  <input type="hidden" name="subcat_norec" id="subcat_norec" value="1" />
					  No Product Label Groups mapped with this category.</td>
					</tr>
				<?php
				}
				?>	
		</table>
		</div>	
	<?php	
	}
	/* Google base category list change starts here */
	function list_googlebase_cate($faction)
	{
		global $db;
		$enable_script	=	"";
		if($faction == 'edit')
		{
			//$enable_script	=	' onchange="call_ajax_updgooglebase(this.value);"';
			$selName	=	'google_product_category_new';
		}
		else
		{
			$selName	=	'google_product_category';
		}
		$sql_grp = "SELECT google_taxonomy_id,google_taxonomy_keyword FROM google_productcategory_taxonomy ORDER BY google_taxonomy_keyword";
		$ret_grp = $db->query($sql_grp);
		
?>		
		<span><strong>Select from the list</strong></span>
		<select name="<?php echo $selName;?>" <?php echo $enable_script;?>>
			<option value="0">--- Select ---</option>
<?php	if($db->num_rows($ret_grp))
		{
			while ($row_grp = $db->fetch_array($ret_grp))
			{
?>			<option value="<?php echo $row_grp['google_taxonomy_id'];?>"><?php echo $row_grp['google_taxonomy_keyword'];?></option>
<?php		}
		}
?>		</select>
<?php
	}
	/* Google base category list change ends here */
	// ###############################################################################################################
    function show_shop_category_list($cat_id,$alert='')
	{
	
		global $db,$ecom_siteid ;
		// Get the list of shops under current shop
		$sql_shop = "SELECT b.map_id,a.shopbrand_id,a.shopbrand_name,a.shopbrand_hide,b.shop_order  
					FROM product_shopbybrand a,category_shop_map b 
					WHERE a.sites_site_id=$ecom_siteid AND 
					b.shopbybrand_category_id = $cat_id 
					AND a.shopbrand_id = b.shopbybrand_shopbybrand_id 	  
					ORDER BY b.shop_order";
		$ret_shop = $db->query($sql_shop);
	  ?>
	  <div class="editarea_div">
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
		<tr>
		  <td align="left" colspan="7" class="helpmsgtd"><div class="helpmsg_divcls">
		 <?php echo get_help_messages('EDIT_PROD_SHOP_GROUP_SHOP_SUBMSG')?></div>	
		  </td>
		  </tr>
		<?
		  // Get the list of products under current category group
		  $sql_displayshop_in_cat = "SELECT shopbybrand_category_id FROM 
						category_shop_map  WHERE 
						shopbybrand_category_id=$cat_id";
		  $ret_displayshop_in_cat = $db->query($sql_displayshop_in_cat);
		 ?>
		  <tr>
		  <td align="right" colspan="5" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_shops('<?php echo $_REQUEST['catname']?>','<?php echo $_REQUEST['catgroupid']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $cat_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CAT_ASSPROD_SHOP_BRND')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_displayshop_in_cat))
			{
			?>
			<div id="shop_unassign_div" class="unassign_div" >
			<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('shops','checkboxshop[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_CAT_UNASSPROD_SHOP_BRND')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div>	
			<?php
			}
			?>		  </td>
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
		if ($db->num_rows($ret_shop))
		{
			$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditProductCategory,\'checkboxshop[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditProductCategory,\'checkboxshop[]\')"/>','Slno.','Product Shop Name','Sort Order','Hidden');
			$header_positions=array('center','center','left','center','center');
			$colspan = count($table_headers);
			echo table_header($table_headers,$header_positions); 
			$cnt = 1;
			while ($row_shop = $db->fetch_array($ret_shop))
			{
				$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
		?>
			
				<tr>
				<td width="5%"  align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxshop[]" value="<?php echo $row_shop['map_id'];?>" /></td>
				<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
				<td class="<?php echo $cls?>" align="left"><a href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?php echo $row_shop['shopbrand_id'];?>" title="Edit Product Shop" class="edittextlink"><?php echo stripslashes($row_shop['shopbrand_name']);?></a></td>
				<td class="<?php echo $cls?>" align="center"><input type="text" name="shop_sort_<?php echo $row_shop['map_id']?>" id="shop_sort_<?php echo $row_shop['map_id']?>" value="<?php echo $row_shop['shop_order']?>" size="3" style="text-align:center" /></td>
				<td class="<?php echo $cls?>" align="center"><?php echo ($row_shop['shopbrand_hide']==1)?'Yes':'No'?></td>
				</tr>
		<?php
			}
			if ($db->num_rows($ret_displayshop_in_cat))
			{
			?>
			<tr>
			<td align="center" colspan="5" class="tdcolorgray_buttons">
			<input name="Saveorder" type="button" class="red" id="Saveorder" value="Save Order" onclick="call_save_order('shops','checkboxshop[]')" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_SAVEORDER_SHOP_BRND_GROUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			</td>
			</tr>
			<?
			}
		}
		else
		{
		?>
		   <tr>
			 <td colspan="<?php echo $colspan?>" align="center" valign="middle" class="norecordredtext_small">
						  <input type="hidden" name="shop_norec" id="shop_norec" value="1" />
						  No Shops Assigned to current category.
			 </td>
		  </tr>
		<?
		}
		?>	
						
		</table>
		</div>
<?
	}
	/* SEO tab in static page starts here */
	function show_page_seoinfo($page_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname;
		
		
			$sql_title	=	"SELECT
											title,meta_description
									FROM
											se_category_title
									WHERE
											sites_site_id=$ecom_siteid
									AND
											product_categories_category_id=".$page_id;
											
			$sql_keys	=	"SELECT
											keywd.keyword_keyword,skey.se_keywords_keyword_id
									FROM
											se_keywords keywd,se_category_keywords skey
									WHERE
											skey.product_categories_category_id = ".$page_id."
									AND
											skey.se_keywords_keyword_id = keywd.keyword_id
									AND
											keywd.sites_site_id = ".$ecom_siteid."
											ORDER BY se_keywords_keyword_id ASC";
		
		$res_title = $db->query($sql_title);
		if($db->num_rows($res_title)>0) 
		{
			$row_title = $db->fetch_array($res_title);
		}
		else
		{
			$row_title['title']	=	"";
			$row_title['meta_description']	=	"";
		}
		//echo $row_title['title'];echo "<br>";
		$res_keys = $db->query($sql_keys);
		if($db->num_rows($res_keys)>0) 
		{
			$field_cnt	=	1;
			$field_values	=	array();
			while($row_keys = $db->fetch_array($res_keys))
			{
				$field_values[$field_cnt]	=	$row_keys['keyword_keyword'];
				$field_cnt++;
			}
		}
		//echo $sql_keys;
?><div class="editarea_div">
		<table width="100%" border="0">
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
			<td class="tdcolorgray" align="left"><b>Title:</b></td>
			<td align="left"><input type="text" name="page_title" value="<?php echo $row_title['title'];?>" size="84"/></td>
		</tr>
		<tr>
			<td class="tdcolorgray" align="left"><b>Meta description:</b></td>
			<td align="left"><textarea  name="page_meta"cols="63" rows="2"><?php echo $row_title['meta_description'];?></textarea></td>
		</tr>
		<tr>
			<td class="tdcolorgray" align="left"><b>Keyword #1:</b></td>
			<td align="left">
				<input type="text" name="keyword_1" id="keyword_1" value="<?php echo $field_values[1];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #2:</b></td>
			<td align="left">
				<input type="text" name="keyword_2" id="keyword_2" value="<?php echo $field_values[2];?>" size="50" />&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #3:</b></td>
			<td align="left">
				<input type="text" name="keyword_3" id="keyword_3" value="<?php echo $field_values[3];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
				
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #4:</b></td>
			<td align="left">
				<input type="text" name="keyword_4" id="keyword_4" value="<?php echo $field_values[4];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
			</td>
		  </tr>
		  <tr>
			<td class="tdcolorgray" align="left"><b>Keyword #5:</b></td>
			<td align="left">
				<input type="text" name="keyword_5" id="keyword_5" value="<?php echo $field_values[5];?>"  size="50"/>&nbsp;&nbsp;&nbsp;
			</td>
		  </tr>
		</table></div>
		<div class="editarea_div">
			<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td align="right" valign="middle" class="tdcolorgraynormal" >
				<input name="cat_Submit" type="button" class="red" value="Save" onclick="call_save_seo('seo')" />	
				</td>
			</tr>
			</table>
		</div>
<?php
	}
?>
