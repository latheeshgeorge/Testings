<?PHP
	// ###############################################################################################################
	// 				Function which holds the display logic of images assigned to Product Shops to be shown 
	//				when called using ajax;
	// ###############################################################################################################
	
	//Function to show the main info for the shop
	function show_shopmaininfo($edit_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_hostname;
		if($edit_id)
		{
			$sql_shops = "SELECT * FROM product_shopbybrand WHERE shopbrand_id=$edit_id LIMIT 1";
			$ret_shops = $db->query($sql_shops);
			if($db->num_rows($ret_shops))
			{
				$row_shops = $db->fetch_array($ret_shops);
			}
		}
		?>
		<table cellpadding="0"  cellspacing="0" width="100%">
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
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
		  <tr>
	<td align="left" colspan="4" class="onerow_tdcls">
	<div class="editarea_url">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
	<td align="left" valign="top" class="tdcolorgray_url_left">Website URL</td>
	<td align="left" valign="top" class="tdcolorgray_url">:<a href="<?php url_shops($row_shops['shopbrand_id'],$row_shops['shopbrand_name'],-1);?>" title="Click to view the Shop by brand in website" target="_blank"><?php url_shops($row_shops['shopbrand_id'],$row_shops['shopbrand_name'],-1);?></a></td>
	</tr>
	</table>
	</div>
	</td>
	</tr>
		<tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Product Shop  name <span class="redtext">*</span> </td>
          <td width="34%" align="left" valign="middle" class="tdcolorgray"><input name="shopbrand_name" type="text" class="input" size="25" value="<?php echo stripslashes($row_shops['shopbrand_name'])?>"  maxlength="250"/></td>
          <td width="13%" align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">Hide Product Shop </td>
          <td width="33%" align="left" valign="middle" class="tdcolorgray"><input type="radio" name="shopbrand_hide" value="1" <?php echo ($row_shops['shopbrand_hide']==1)?'checked="checked"':''?> />
            Yes
              <input name="shopbrand_hide" type="radio" value="0" <?php echo ($row_shops['shopbrand_hide']==0)?'checked="checked"':''?> />
          No<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
         <tr>
           <td align="left" valign="middle" class="tdcolorgray" >Parent Shop  </td>
           <td align="left" valign="middle" class="tdcolorgray"><?php 
				$shop_parent = generate_shop_tree(0);
				echo generateselectbox('shopbrand_parent_id',$shop_parent,$row_shops['shopbrand_parent_id']);
			?></td>
           <td align="left" valign="middle" class="tdcolorgray" colspan="2">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
             	<td width="19%" align="left" valign="top" class="tdcolorgray_url">Website URL</td>
                <td width="81%" align="left" valign="top" class="tdcolorgray_url">:&nbsp;&nbsp;<?php url_shops($row_shops['shopbrand_id'],$row_shops['shopbrand_name'],-1);?></td>
              </tr>
            </table></td>
         </tr>
         <tr>
           <td align="left" valign="top" class="tdcolorgray" >Shop Menu   </td>
           <td align="left" valign="top" class="tdcolorgray">
		   <?php
		   $ext_grp_arr	= array(0);
			// Get the list of already added shop groups
			$sql_catgroup = "SELECT product_shopbybrand_shopbrandgroup_id FROM product_shopbybrand_group_shop_map 
							WHERE product_shopbybrand_shopbrand_id=$edit_id";
			$ret_catgroup = $db->query($sql_catgroup);
			if ($db->num_rows($ret_catgroup))
			{
				while ($row_catgroup = $db->fetch_array($ret_catgroup))
				{
					$ext_grp_arr[] = $row_catgroup['product_shopbybrand_shopbrandgroup_id'];
				}
			}
		  	$group_arr 		= array();
			// Get the list of all category groups for this site
			$sql_groups = "SELECT shopbrandgroup_id,shopbrandgroup_name FROM product_shopbybrand_group WHERE sites_site_id=$ecom_siteid ORDER BY shopbrandgroup_name";
			$ret_groups = $db->query($sql_groups);
			if ($db->num_rows($ret_groups))
			{
				while ($row_groups = $db->fetch_array($ret_groups))
				{
					$grpid	= $row_groups['shopbrandgroup_id'];
					$group_arr[$grpid] = stripslashes($row_groups['shopbrandgroup_name']);
				}	
			}
			echo generateselectbox('group_id[]',$group_arr,$ext_grp_arr,'','',5);
		  ?>
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_SHOP_CG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
           <td align="left" valign="top" class="tdcolorgray" nowrap="nowrap"><?php /*?>Default Shop Group <?php */?> </td>
           <td align="left" valign="top" class="tdcolorgray"><?php /*
		  	$default_array 		= array();
			// Get the list of all shop groups for this site
			$sql_groups = "SELECT shopbrandgroup_id,shopbrandgroup_name FROM product_shopbybrand_group WHERE sites_site_id=$ecom_siteid ORDER BY shopbrandgroup_name";
			$ret_groups = $db->query($sql_groups);
			if ($db->num_rows($ret_groups))
			{
				while ($row_groups = $db->fetch_array($ret_groups))
				{
					$grpid	= $row_groups['shopbrandgroup_id'];
					$default_array[$grpid] = stripslashes($row_groups['shopbrandgroup_name']);
				}	
			}
			echo generateselectbox('default_shopgroup_id',$default_array,$row_shops['shopbrand_default_shopbrandgroup_id']);
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_SHOP_DCG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>*/ ?>
			 </td>
         </tr>
		  <tr>
		    <td align="left" valign="top" class="tdcolorgray" >Shop Description </td>
		    <td colspan="3" align="left" valign="middle" class="tdcolorgray">
			<?php
				/*$editor 			= new FCKeditor('shopbrand_description') ;
				$editor->BasePath 	= '/console/js/FCKeditor/';
				$editor->Width 		= '650';
				$editor->Height 	= '300';
				$editor->ToolbarSet = 'BshopWithImages';
				$editor->Value 		= stripslashes($row_shops['shopbrand_description']);
				$editor->Create() ;*/
		       ?>
			   <textarea style="height:300px; width:500px" id="shopbrand_description" name="shopbrand_description"><?=stripslashes($row_shops['shopbrand_description'])?></textarea>
			</td>
    </tr>
    <tr>
		    <td align="left" valign="top" class="tdcolorgray" >Bottom Description (SEO Purpose) </td>
		    <td align="left" valign="middle" class="tdcolorgray" colspan="3">
			   <textarea style="height:300px; width:500px" id="shopbrand_bottomdescription" name="shopbrand_bottomdescription"><?=stripslashes($row_shops['shopbrand_bottomdescription'])?></textarea>
			</td>
    		</tr>
    </table>
    </div>
    </td>
    </tr>
     <tr>
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">		
	<tr>
	<td align="right" valign="middle" class="tdcolorgray" colspan="4">
	<input name="shopbrand_Submit" type="submit" class="red" value="Save" />
	<!-- Button to save and return starts here -->
	<input name="shopbrand_Submit" type="submit" class="red" value="Save & Return" />
	<!-- Button to save and return ends here -->
	&nbsp;&nbsp;</td>
	</tr>
	</table>
	</div>
	</td>
	</tr>
		</table>
		
		<?
		
	}	
	function show_shop_settings($edit_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_hostname,$ecom_themeid;
		if($edit_id)
		{
			$sql_shops = "SELECT * FROM product_shopbybrand WHERE shopbrand_id=$edit_id";
			$ret_shops = $db->query($sql_shops);
			if($db->num_rows($ret_shops))
			{
				$row_shops = $db->fetch_array($ret_shops);
			}
		}
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
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
			<td colspan="4" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_SHOP_SETTING')?></div></td>
		</tr>
		<tr>
			<td colspan="4" align="left" valign="top" class="seperationtd" ><strong>Display Settings for Subshops &amp; Products of this Shop</strong></td>
		</tr>
		<tr>
			<td align="left" valign="middle" class="tdcolorgray" ><p>Product Display Method </p></td>
			<td align="left" valign="middle" class="tdcolorgray"><?php 
			 $arr_prod_style 	= $grp_type = array();
			  	$sql_style_prod	= "SELECT product_listingstyles FROM themes WHERE theme_id=".$ecom_themeid;
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
				 }	
			//	$grp_type = array('OneinRow'=>'One in a Row','ThreeinRow'=>'Three in a Row');
				echo generateselectbox('shopbrand_product_displaytype',$grp_type,$row_shops['shopbrand_product_displaytype']);
			?>
			  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_GROUP_DISP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			<td align="left" valign="middle" class="tdcolorgray" nowrap="nowrap">Sub Shop List Format</td>
			<td align="left" valign="middle" class="tdcolorgray"><?php 
				$subcat_list = array('Middle'=>'Show in Middle Area','List'=>'Show below Selected Shops');
				echo generateselectbox('shopbrand_subshoplisttype',$subcat_list,$row_shops['shopbrand_subshoplisttype']);
			?>
		  	<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_GROUP_SUBTYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		<tr>
			<td colspan="2" align="left" valign="top" class="tdcolorgray" ><b>Fields to be displayed for Products</b> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_GROUP_DISPPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			
			<td align="left" valign="top" class="tdcolorgray" nowrap="nowrap">
			<?PHP //if(is_module_valid('mod_shopimage','any')) echo 'Show any product Image';  ?>			</td>
			<td align="left" valign="top" class="tdcolorgray">
			<?PHP //if(is_module_valid('mod_shopimage','any')) { ?>
			<!--		<input class="input" type="checkbox" name="shop_showimageofproduct"  value="1" <? //if($row_shops['shopbrand_showimageofproduct']==1) echo "checked";?> onclick="display_shop_image();"  />
			<a href="#" onmouseover ="ddrivetip('<?//=get_help_messages('EDIT_PROD_CAT_SHOW_PROD_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			-->				<?PHP //} ?>			</td>
		</tr>
		<tr>
		<td colspan="2" align="left" valign="top" class="tdcolorgray" ><table width="100%" border="0">
		  <tr>
			<td width="46%">&nbsp;</td>
			<td width="54%"><input name="shopbrand_product_showimage" type="checkbox" value="1" <?php echo ($row_shops['shopbrand_product_showimage']==1)?'checked="checked"':''?> />
			  Product Image </td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td><input name="shopbrand_product_showtitle" type="checkbox" value="1"  <?php echo ($row_shops['shopbrand_product_showtitle']==1)?'checked="checked"':''?> />
			  Product Title </td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td><input name="shopbrand_product_showshortdescription" type="checkbox" value="1" <?php echo ($row_shops['shopbrand_product_showshortdescription']==1)?'checked="checked"':''?> />
			  Product Description </td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td><input name="shopbrand_product_showprice" type="checkbox" value="1" <?php echo ($row_shops['shopbrand_product_showprice']==1)?'checked="checked"':''?>>
			  Product Price </td>
		  </tr>
		  <tr>
		    <td>&nbsp;</td>
		    <td><input name="shopbrand_product_showrating" type="checkbox" value="1" <?php echo ($row_shops['shopbrand_product_showrating']==1)?'checked="checked"':''?> />
Product Rating </td>
	      </tr>
	       <tr>
		    <td>&nbsp;</td>
		    <td><input name="shopbrand_product_showbonuspoints" type="checkbox" value="1" <?php echo ($row_shops['shopbrand_product_showbonuspoints']==1)?'checked="checked"':''?> />
Product Bonus Points </td>
	      </tr>
		</table></td>
		<td colspan="2" align="left" valign="top" class="tdcolorgray">&nbsp;</td>
		</tr>
		</table>
		</div>
		</td>
		</tr>
		 <tr>
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
		<tr>
		<td colspan="4" align="right" class="tdcolorgray">
		 <input name="shop_Submit" type="button" class="red" value="Save" onclick="call_ajax_savesettings('display_settings')" />
		&nbsp;&nbsp;</td>
		</tr>
		</table>
		</div>
		</td>
		</tr>
		</table>
		<?
	}	
	function show_shopimage_list($editid,$alert='')
	{
		global $db,$ecom_siteid,$ecom_hostname;
		if($editid)
		{
			$sql_shops = "SELECT * FROM product_shopbybrand WHERE shopbrand_id=$editid";
			$ret_shops = $db->query($sql_shops);
			if($db->num_rows($ret_shops))
			{
				$row_shops = $db->fetch_array($ret_shops);
			}
		}
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
		<?php
			if ($alert)
			{
		?>
			<tr>
				<td align="center" class="errormsg" colspan="7"><?php echo $alert?>
				</td>
			</tr>
		<?php
			}
			?>
	    <tr>
			<td colspan="7" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_SHOP_SHOPIMAGE_SUBHEAD')?></div>
			</td>
		</tr>		
		<tr id="shop_image_tr2" >
		  <?php
		  // Get the list of images which satisfy the current critera from the images table
					$sql_img = "SELECT b.id,a.image_id,b.image_title,b.image_order,a.image_gallerythumbpath,a.images_directory_directory_id FROM images a,images_shopbybrand b WHERE 
								a.sites_site_id = $ecom_siteid 
								AND b.product_shopbybrand_shopbrand_id=$editid 
								AND a.image_id=b.images_image_id ORDER BY b.image_order";	
					$ret_img = $db->query($sql_img);
			
		 ?>
		 
          <td align="left" class="tdcolorgray_buttons" width="5%" >
            <?PHP if(is_module_valid('mod_shopimage','any')) { ?>
            <input class="input" type="checkbox" name="shop_showimageofproduct"  value="1" <? if($row_shops['shopbrand_showimageofproduct']==1) echo "checked";?> onclick="handle_images_from_product(this)" > 
            <?PHP } ?>
          </td> 
		  <td align="left" class="tdcolorgray_buttons"  width="15%"><?PHP if(is_module_valid('mod_shopimage','any')) echo 'Show any product Image';  ?>
		  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_SHOP_SHOW_PROD_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td width="2%" align="left" valign="middle"><input name="shopbrand_turnoff_mainimage" type="checkbox" id="shopbrand_turnoff_mainimage" value="1" <?php echo ($row_shops['shopbrand_turnoff_mainimage']==1)?'checked="checked"':''?>/></td>
          <td width="25%" align="left" valign="middle" class="tdcolorgray">Turn Off &quot;Main Image&quot; in Shop Details page<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_SHOP_SHOW_NO_MAIN_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td width="2%" align="right"> <input name="shopbrand_turnoff_moreimages" type="checkbox" id="shopbrand_turnoff_moreimages" value="1" <?php echo ($row_shops['shopbrand_turnoff_moreimages']==1)?'checked="checked"':''?>/>           </td>
          <td width="24%" align="left" class="tdcolorgray">Turn Off &quot;More Images&quot; in Shop Details page<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_SHOP_SHOW_NO_MORE_IMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>

          <td  align="right" class="tdcolorgray_buttons" width="27%">
		    <div id="shopimg_operation_main" <?php echo ($row_shops['shopbrand_showimageofproduct']==1)?'style="display:none"':''?>>
			<input name="Assign_Image" type="button" class="red" id="Assign_Image" value="Assign More"  onclick="normal_assign_ImageAssign('<?php echo $editid?>','<?php echo $_REQUEST['shopname']?>','<?php echo $_REQUEST['show_shopgroup']?>','<?php echo $_REQUEST['parentid']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>');"/>
            <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_PROD_ASSIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
                        <?php
				if ($db->num_rows($ret_img))
				{
				?>
            <div id="shopimgunassign_div" class="unassign_div" > &nbsp;&nbsp;&nbsp;
                <input name="shopimg_unassign" type="button" class="red" id="shopimg_unassign" value="Un assign" onclick="call_ajax_deleteall('shopimg','checkbox_img[]')" />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_PROD_UNASSIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></div>            <?php
				}				
				?>
		  </div>	</td>
        </tr>
		
		<? 
			
					
					if($db->num_rows($ret_img))
					{
?>
						<tr id="img_tr_1" <?php echo ($row_shops['shopbrand_showimageofproduct']==1)?'style="display:none"':''?>>
						<td align="left" colspan="7" class="tdcolorgray_buttons">
						<img src="images/checkbox.gif" border="0" onclick="select_all_img(document.frmEditShopByBrand,'checkbox_img[]')" alt="Check all images" title="Check all images"/><img src="images/uncheckbox.gif" border="0" onclick="select_none_img(document.frmEditShopByBrand,'checkbox_img[]')" alt="Uncheck all images" title="Uncheck all images"/>
						&nbsp; Please Tick the images whose details want to save </td>
			</tr>
							<tr id="img_tr_2" <?php echo ($row_shops['shopbrand_showimageofproduct']==1)?'style="display:none"':''?>>
							  <td colspan="6">
									<table width="83%" border="0" cellpadding="0" cellspacing="8" class="imagelisttable">
									<tr>
<?php
										$max_cols 	= 5;
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
												  <td align="center" class="imagelist_imgtd" colspan="2" ondblclick="if (confirm('Are you sure you want to go to Image edit page?')==true) {window.location='home.php?request=img_gal&fpurpose=edit_img&edit_id=<?php echo $row_img['image_id']?>&curdir_id=<?php echo $row_img['images_directory_directory_id']?>&org_id=<?php echo $editid?>&back_frm=prod_shop'}">
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
						<tr id="img_tr_3" <?php echo ($row_shops['shopbrand_showimageofproduct']==1)?'style="display:none"':''?>>
							  <td align="center" class="norecordredtext_small" colspan="6"> No Images assigned for current product Shop
							  <input type="hidden" name="catimg_norec" id="catimg_norec" value="1"  />
							  </td>
						</tr>	  
<?php	
					}
?>		
</table>
</div>
</td>
</tr>
 <tr>
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">

      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
							<tr>
							<td  align="right" colspan="7">
							<input name="shopimg_save" type="button" class="red" id="shopimg_save" value="Save Details" onclick="call_ajax_savedetails('shopimg','checkbox_img[]')" />
                         	<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_PROD_CHDETIMG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>

							&nbsp;&nbsp;</td>
							</tr>
	</table>
	</div>
	</td>
	</tr>						
</table>
<?php
	}
	// ###############################################################################################################
	//Function which holds the display logic of shops under the current shop to be shown when
    //called using ajax;
	// ###############################################################################################################
    function show_subshop_list($shop_id,$alert='')
	{
			global $db,$ecom_siteid ;
		// Get the list of shops under current shop
			$sql_shop = "SELECT shopbrand_id,shopbrand_name,shopbrand_hide,shopbrand_order FROM product_shopbybrand 
						WHERE sites_site_id=$ecom_siteid AND 
						shopbrand_parent_id = $shop_id ORDER BY shopbrand_order";
			$ret_shop = $db->query($sql_shop);
	?>
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
	<tr>
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">
				<?php
				if($alert)
				{
				?>
						<tr>
							<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
						</tr>
			 <?php
				}
				?>
				<tr>
						<td colspan="5" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_SHOP_SUBSHOP_SUBHEAD')?></div>
						</td>
				</tr>
				<?
				if ($db->num_rows($ret_shop))
				{
					$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShopByBrand,\'checkboxsubshop[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShopByBrand,\'checkboxsubshop[]\')"/>','Slno.','Product Shop Name','Sort Order','Hidden');
					$header_positions=array('center','center','left','center','center');
					$colspan = count($table_headers);
					echo table_header($table_headers,$header_positions); 
					$cnt = 1;
				while ($row_shop = $db->fetch_array($ret_shop))
				{
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
						<td width="5%"  align="left" class="<?php echo $cls?>"><input type="checkbox" name="checkboxsubshop[]" value="<?php echo $row_shop['shopbrand_id'];?>" /></td>
						<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
						<td class="<?php echo $cls?>" align="left"><a href="home.php?request=shopbybrand&fpurpose=edit&checkbox[0]=<?php echo $row_shop['shopbrand_id'];?>" title="Edit Product Shop" class="edittextlink"><?php echo stripslashes($row_shop['shopbrand_name']);?></a></td>
						<td class="<?php echo $cls?>" align="center"><input type="text" name="shop_sort_<?php echo $row_shop['shopbrand_id']?>" id="shop_sort_<?php echo $row_shop['shopbrand_id']?>" value="<?php echo $row_shop['shopbrand_order']?>" size="3" style="text-align:center" /></td>
						<td class="<?php echo $cls?>" align="center"><?php echo ($row_shop['shopbrand_hide']==1)?'Yes':'No'?></td>
					</tr>
				<?php
				}
				}
				else
				{
				?>
				   <tr>
					 <td colspan="<?php echo $colspan?>" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="subshop_norec" id="subshop_norec" value="1" />
								  No Sub Shops Exists for current shop
					 </td>
					</tr>
				<?
				}
				?>
			</table>
			</div>
			</td>
			</tr>
		    <tr>
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">	
				<tr>
		  <td colspan="5" class="tdcolorgray_buttons" align="right">
		<?PHP  	if ($db->num_rows($ret_shop))
					{
					?>
					<div id="subshop_unassign_div" class="unassign_div" >
					<input name="Savorder_subshop" type="button" class="red" id="Savorder_subshop" value="Save Order" onclick="call_save_order('subshop','checkboxsubshop[]')" />
					<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_SAVE_ORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			
					
				   <?
					}
					?>		  </td>
				  </tr>	
				</table>
				</div>
				</td>
				</tr>				
				</table>
<?
	}
		// ###############################################################################################################
	//Function which holds the display logic of display products under the product shop to be shown when
    //called using ajax;
	// ###############################################################################################################
 	function show_shop_products($shop_id,$alert='')
	{
		global $db,$ecom_siteid ;
		$sql_product = "SELECT b.map_id,a.product_id,a.product_name,a.product_hide,b.map_sortorder FROM products a,
						product_shopbybrand_product_map b WHERE a.sites_site_id=$ecom_siteid AND 
						b.product_shopbybrand_shopbrand_id=$shop_id AND 
						a.product_id=b.products_product_id ORDER BY map_sortorder";
		$ret_product = $db->query($sql_product);
		?>
		<table width="100%" cellpadding="0" cellspacing="1" border="0">
		<tr>
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">	
				  
		 <?
		  // Get the list of products under current product shop
		  $sql_productmap_in_group = "SELECT products_product_id FROM 
						product_shopbybrand_product_map  WHERE 
						product_shopbybrand_shopbrand_id=$shop_id";
		  $ret_productmap_in_group = $db->query($sql_productmap_in_group);
				if($alert)
				{
					?>
							<tr>
								<td colspan="5" align="center" class="errormsg"><?php echo $alert?></td>
							</tr>
				 <?php
				}
				?>
		 <tr>
					<td colspan="5" class="helpmsgtd" align="left"><div class="helpmsg_divcls"><?=get_help_messages('EDIT_PROD_SHOP_PROD_SUBHEAD')?></div></td>
		</tr>
		  <tr>
		  <td align="right" colspan="5" class="tdcolorgray_buttons">
			<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_shopprod('<?php echo $_REQUEST['shopname']?>','<? echo $_REQUEST['show_shopgroup']?>','<?php echo $_REQUEST['sort_by']?>','<?php echo $_REQUEST['sort_order']?>','<?php echo $_REQUEST['records_per_page']?>','<?php echo $_REQUEST['start']?>','<?php echo $_REQUEST['pg']?>','<?php echo $shop_id?>');" />
			<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_ASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
			<?php
			if ($db->num_rows($ret_productmap_in_group))
			{
			?>
				<div id="subshopproduct_groupunassign_div" class="unassign_div" >
				<input name="Unassign" type="button" class="red" id="Unassign" value="Unassign" onclick="call_ajax_deleteall('subshopproduct_group','checkboxshopproduct[]')" />
				<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_UNASSPROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>			</div>	
			<?php
			}
			?>		  </td>
			</tr>
				<?
				if ($db->num_rows($ret_product))
				{
				$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmEditShopByBrand,\'checkboxshopproduct[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmEditShopByBrand,\'checkboxshopproduct[]\')"/>','Slno.','Product Name','Order','Hidden');
				$header_positions=array('center','center','left','center','center');
				$colspan = count($table_headers);
				echo table_header($table_headers,$header_positions); 
				$cnt = 1;
				while ($row_product = $db->fetch_array($ret_product))
				{
					
					$cls = ($cnt%2==0)?'listingtablestyleA':'listingtablestyleB';
				?>
					
					<tr>
					<td width="5%" align="center" class="<?php echo $cls?>"><input type="checkbox" name="checkboxshopproduct[]" value="<?php echo $row_product['map_id'];?>" /></td>
					<td width="5%" align="left" class="<?php echo $cls?>"><?php echo $cnt++?>.</td>
					<td class="<?php echo $cls?>" align="left"><a href="home.php?request=products&fpurpose=edit&checkbox[0]=<? echo $row_product['product_id']?>" class="edittextlink"><?php echo stripslashes($row_product['product_name']);?></a></td>
					<td class="<?php echo $cls?>" align="center" width="10%"><input type="text" name="shopprod_order_<?php echo stripslashes($row_product['map_id']);?>" id="shopprod_order_<?php echo stripslashes($row_product['map_id']);?>" value="<?php echo stripslashes($row_product['map_sortorder']);?>" size="4" style="text-align:center" /></td>
					<td class="<?php echo $cls?>" align="center"><?php echo ($row_product['product_hide']=='Y')?'Yes':'No'?></td>
					</tr>
				<?php
				}
				
					
				}
				else
				{
				?>
				<tr>
					 <td colspan="5" align="center" valign="middle" class="norecordredtext_small">
  								  <input type="hidden" name="shopproductgroup_norec" id="shopproductgroup_norec" value="1" />
								 No Products exists in current shop</td>
					</tr>
				<?
				}
				?>
				</table>
				</div>
				</td>
				</tr>
				<tr>
      <td colspan="4" class="tdcolorgray">
		  <div class="editarea_div">
      <table  border="0" cellpadding="0" cellspacing="0" class="listingtable" width="100%">	
    <?php
      if ($db->num_rows($ret_productmap_in_group))
					{
					?>
						<tr>
							<td colspan="5" align="right" ><input name="Savorder_subshopprod" type="button" class="red" id="Savorder_subshopprod" value="Save Order" onclick="call_save_order('subshopproduct','checkboxshopproduct[]')" /> &nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SHOP_BRAND_SAVEORD_PROD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;</td>
						</tr>
					  			
					<?
					} 
					?>
      </table>
      </div>
      </td>
      </tr>
				</table>
<?		
		
	}
	/* SEO tab in static page starts here */
	function show_page_seoinfo($page_id,$alert='')
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname;
		
		
			$sql_title	=	"SELECT
											title,meta_description
									FROM
											se_shop_title
									WHERE
											sites_site_id=$ecom_siteid
									AND
											product_shopbybrand_shopbrand_id=".$page_id;
											
			$sql_keys	=	"SELECT
											keywd.keyword_keyword,skey.se_keywords_keyword_id
									FROM
											se_keywords keywd,se_shop_keywords skey
									WHERE
											skey.product_shopbybrand_shopbrand_id = ".$page_id."
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