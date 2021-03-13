<?php
	// ###############################################################################################################
	// 				Function which holds the display logic of products under the best seller when called using ajax;
	// ###############################################################################################################
	function show_design_maininfo($alert)
	{
		global $db,$ecom_siteid,$ecom_themeid ;
		$sql 							= "SELECT * FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
		$res_admin 				= $db->query($sql);
		$fetch_arr_admin 	= $db->fetch_array($res_admin);
		
		$sql 							= "SELECT * FROM general_settings_sites_common_onoff WHERE sites_site_id=".$ecom_siteid;
		$res_admin 				= $db->query($sql);
		$fetch_arr_admin_1 	= $db->fetch_array($res_admin);
		
		// This is done to move the fields and its value from general_settings_sites_common_onoff table to the same array which have values from general_settings_sites_common table	
		foreach ($fetch_arr_admin_1 as $k=>$v)
		{
			$fetch_arr_admin[$k]=$v;	
		}
			$arr_prod_style 	= $grp_type = $subcatlstng_arr = $subcatlst_arr = array();
			$sql_style_prod	= "SELECT subcategory_listingstyles FROM themes WHERE theme_id=".$ecom_themeid;
			$ret_style_prod 	= $db->query($sql_style_prod);
			if ($db->num_rows($ret_style_prod))
			{
				$row_style_prod = $db->fetch_array($ret_style_prod);
				$subcatlstng_arr	= explode(',',$row_style_prod['subcategory_listingstyles']);
				if (count($subcatlstng_arr))
				{
					foreach($subcatlstng_arr as $v)
					{
						$temp_arr = explode("=>",$v);
						$subcatlst_arr[$temp_arr[0]]=trim($temp_arr[1]);	 // this array will be used for all the following image type drop down boxes
					}
				}
			 }	
		
	//$fetch_arr_admin 	= $db->fetch_array($res_admin);
		?>
		<table width="100%" border="0" cellpadding="2" cellspacing="2" >
			<tr>
			<td colspan="7" class="tdcolorgray">	
		<div class="listingarea_div">
		<table width="100%" border="0" cellpadding="2" cellspacing="2" class="tdcolorgray">
		
		 <tr>
          <td colspan="7" align="left" valign="middle" class="seperationtd" ><a name="Design_Layout">&nbsp;</a><b>Design & Layout</b></td>
        </tr>
		<?php
			if ($alert)
			{
		?>
      	  <tr>
          	<td colspan="7" align="center" valign="middle" class="errormsg" id="mainerror_tr" ><?php echo $alert; ?></td>
          </tr>
		 <?php
		 	}
		 ?> 
        <tr>
         <td align="left" valign="middle" width="2%" ><input type="checkbox" name="empty_cart" value="1" <?php echo ($fetch_arr_admin['empty_cart'] == 1)?"checked":"";?> /></td>
		 <td colspan="3" align="left" valign="middle"   >Display Clear Cart button <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_EPTYCART')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         <td width="2%" align="left" valign="middle"   ><input type="checkbox" name="show_cart_promotional_voucher" value="1" <?php echo ($fetch_arr_admin['show_cart_promotional_voucher'] == 1)?"checked":"";?> /></td>
         <td colspan="2" align="left" valign="middle"   >Display Promotional code / Voucher text box in View Cart <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_EPTYCART_PROM_VOUCH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
          <td align="left" valign="middle" ><input type="checkbox" name="hide_newuser" value="1" <?php echo ($fetch_arr_admin['hide_newuser'] == 1)?"checked":"";?> /></td>
          <td colspan="3" align="left" valign="middle"   >Hide New User link in login <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_EPTYCART_NEWUSER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="left" valign="middle" ><input type="checkbox" name="empty_wishlist" value="1" <?php echo($fetch_arr_admin['empty_wishlist'] == 1)?"checked":"";?> /></td>
          <td colspan="2" align="left" valign="middle"   >Display Clear Wish List button <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_EPTYCART_WISHLIST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
        <tr>
		
          <td align="left" valign="middle" ><input type="checkbox" name="show_qty_box" value="1" <?php echo ($fetch_arr_admin['show_qty_box'] == 1)?"checked":"";?> /></td>
          <td colspan="3" align="left" valign="middle" >Display Quantity box <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_EPTYCART_QTY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td align="left" valign="middle" ><input type="checkbox" name="hide_forgotpass" value="1" <?php echo($fetch_arr_admin['hide_forgotpass'] == 1)?"checked":"";?> /></td>
          <td colspan="2" align="left" valign="middle" >Hide Forgot Password link in login <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_EPTYCART_FRGTPASSW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr>
		<tr>
		<td align="left" valign="middle" ><input type="checkbox" name="shownewsletter_as_banner" value="1" <?php echo ($fetch_arr_admin['shownewsletter_as_banner'] == 1)?"checked":"";?> /></td>
		<td align="left" valign="middle" >Display newsletter as banner<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_DISPLAY_NEWSLETTER_BANNER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td align="left" valign="middle" ><input type="checkbox" name="showcustomerlogin_as_banner" value="1" <?php echo ($fetch_arr_admin['showcustomerlogin_as_banner'] == 1)?"checked":"";?> />			</td>
		  <td  align="left" valign="middle" >Display customer login as banner<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_DISPLAY_CUST_LOGIN_BANNER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle" ><input type="checkbox" name="product_compare_enable" value="1" <?php echo ($fetch_arr_admin['product_compare_enable'] == 1)?"checked":"";?> onclick="display_compare_count();" /></td>
		  <td align="left" valign="middle" >Enable Product Compare feature <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_ENABLE_PDT_COMPARE_FEATURE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td  align="left" valign="middle" >&nbsp;</td>
		  </tr>
		 <tr>
          <td align="left" valign="middle" colspan="7" class="tdcolorgray">
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr id="compare_count_id"  <?=($fetch_arr_admin['product_compare_enable'])?'style="display:"':'style="display:none"';?>>
              <td align="left" valign="middle" class="tdcolorgray"><table width="100%" border="0">
                    <tr>
                      <td width="2%">&nbsp;</td>
                      <td colspan="3" align="left">Maximum number of products to Compare 
                        <select name="no_of_products_to_compare">
                          <option value="2" <?=($fetch_arr_admin['no_of_products_to_compare']==2)?'selected':'';?>>2</option>
                          <option value="3" <?=($fetch_arr_admin['no_of_products_to_compare']==3)?'selected':'';?>>3</option>
                          <option value="4" <?=($fetch_arr_admin['no_of_products_to_compare']==4)?'selected':'';?>>4</option>
                        </select>
                        <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_MAX_NUM_OF_PDT_TO_COMPARE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                </tr>
                    <tr>
					  <td width="2%"><input type="checkbox" name="product_compare_prodlist_enable" value="1" <?php echo($fetch_arr_admin['product_compare_prodlist_enable'] == 1)?"checked":"";?>/></td>
					  <td >Show Product Compare in Product Listing Pages <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('COMPARE_ALLOW_PROD_LIST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
					  <td align="left" valign="middle" width="2%"><input type="checkbox" name="product_compare_proddetail_enable" value="1" <?php echo($fetch_arr_admin['product_compare_proddetail_enable'] == 1)?"checked":"";?>/></td>
					  <td align="left" valign="middle" >Show Product Compare in Product Details Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('COMPARE_ALLOW_PROD_DETAILS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
				    </tr>
                                </table></td>
            </tr>
          </table>
		  </td>
          </tr>
        <tr>
          <td colspan="4" align="left" valign="top" >
		  	<table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="60%">Search Product Listing</td>
              <td width="40%">
                <select name="search_prodlisting">
                  <?
				 $sql_style="SELECT product_listingstyles FROM themes WHERE theme_id=".$ecom_themeid;
				 $ret_style = $db->query($sql_style);
				 $row_style=$db->fetch_array($ret_style);
				 $arr_style=explode(',',$row_style['product_listingstyles']);
				 foreach($arr_style as $v)
				 {
					$val_arr = explode("=>",$v);
				 ?>
                  	<option value="<?=$val_arr[0]?>" <?php echo ($fetch_arr_admin['search_prodlisting']==$val_arr[0])?'selected':''?>>
                   	  <?=$val_arr[1]?>
                    </option>
                  <?
				 }
		 ?>
                </select><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_SEARCHPROD_ROW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            <tr>
              <td>Linked Product Listing </td>
              <td>
                <select name="linked_prodlisting">
              <?
				 foreach($arr_style as $v)
				 {
					$val_arr = explode("=>",$v);
			 ?>
                  <option value="<?=$val_arr[0]?>" <?php echo ($fetch_arr_admin['linked_prodlisting']==$val_arr[0])?'selected':''?>>
                    <?=$val_arr[1]?>
                  </option>
             <?
				 }
		 ?>
                </select><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_LINK_PRODROW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            <tr>
              <td>Best Sellers Listing </td>
              <td><select name="bestseller_prodlisting">
                  <?
				 foreach($arr_style as $v)
				 {
					$val_arr = explode("=>",$v);
			 ?>
                  <option value="<?=$val_arr[0]?>" <?php echo ($fetch_arr_admin['bestseller_prodlisting']==$val_arr[0])?'selected':''?>>
                  <?=$val_arr[1]?>
                  </option>
                  <?
				 }
		 ?>
                </select>
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_BESTSELLER_PRODROW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
			<tr>
              <td>Favorite Product Listing </td>
              <td><select name="favorite_prodlisting">
                  <?
				 foreach($arr_style as $v)
				 {
					$val_arr = explode("=>",$v);
			 ?>
                  <option value="<?=$val_arr[0]?>" <?php echo ($fetch_arr_admin['favorite_prodlisting']==$val_arr[0])?'selected':''?>>
                  <?=$val_arr[1]?>
                  </option>
                  <?
				 }
		 ?>
                </select>
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_FAVORITE_PRODROW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
			<tr>
              <td>Products in favorite Categories(ShowAll)</td>
              <td><select name="favoritecategory_prodlisting">
                  <?
				 foreach($arr_style as $v)
				 {
					$val_arr = explode("=>",$v);
			 ?>
                  <option value="<?=$val_arr[0]?>" <?php echo ($fetch_arr_admin['favoritecategory_prodlisting']==$val_arr[0])?'selected':''?>>
                  <?=$val_arr[1]?>
                  </option>
                  <?
				 }
		 ?>
                </select>
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_FAVORITE_PRODROW_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
			<tr>
              <td>Recently purchased products(ShowAll) </td>
              <td><select name="recentpurchased_prodlisting">
                  <?
				 foreach($arr_style as $v)
				 {
					$val_arr = explode("=>",$v);
			 ?>
                  <option value="<?=$val_arr[0]?>" <?php echo ($fetch_arr_admin['recentpurchased_prodlisting']==$val_arr[0])?'selected':''?>>
                  <?=$val_arr[1]?>
                  </option>
                  <?
				 }
		 ?>
                </select>
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_RECENT_PRODROW_CAT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
			<tr>
			  <td nowrap="nowrap">Preorder Product Listing </td>
			  <td><select name="preorder_prodlisting">
                  <?
				 foreach($arr_style as $v)
				 {
					$val_arr = explode("=>",$v);
			 ?>
                  <option value="<?=$val_arr[0]?>" <?php echo ($fetch_arr_admin['preorder_prodlisting']==$val_arr[0])?'selected':''?>>
                  <?=$val_arr[1]?>
                  </option>
                  <?
				 }
		 ?>
                </select>
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_PREORDER_PRODROW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		    </tr>
			<tr>
			  <td nowrap="nowrap">Promotional Code Product Listing </td>
			  <td><select name="promo_prodlisting" id="promo_prodlisting">
                <?
				 foreach($arr_style as $v)
				 {
					$val_arr = explode("=>",$v);
			 ?>
                <option value="<?=$val_arr[0]?>" <?php echo ($fetch_arr_admin['promo_prodlisting']==$val_arr[0])?'selected':''?>>
                <?=$val_arr[1]?>
                </option>
                <?
				 }
		 ?>
              </select>
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_PREORDER_PRODROW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			  </tr>
          </table></td>
          <td colspan="3" align="left" valign="top" class="tdcolorgray"  ><table width="100%" border="0" cellpadding="2" cellspacing="2">
            <tr>
              <td>Continue Shopping button:</td>
              <td><select name="config_continue_shopping">
                <option value="home" <?php echo ($fetch_arr_admin['config_continue_shopping'] == 'home')?"selected":""; ?>>Go Back to Home page</option>
				<option value="back" <?php echo ($fetch_arr_admin['config_continue_shopping'] == 'back')?"selected":""; ?>>Go Back to the previous page</option>

               <?php
			    /*?> <option value="product" <?php echo ($fetch_arr_admin['config_continue_shopping'] == 'product')?"selected":""; ?>>Go Back to Product detail page</option>
                <option value="category" <?php echo ($fetch_arr_admin['config_continue_shopping'] == 'category')?"selected":""; ?>>Go Back to Product's Category Page</option><?php 
				*/
				?>
                
              </select>
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_CONTBUTT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            <tr>
              <td>Payment Type  Listing </td>
              <td><?php
			  	 $sql_style	= "SELECT paymenttype_displaytypes FROM themes WHERE theme_id=".$ecom_themeid;
				 $ret_style = $db->query($sql_style);
				 $row_style	= $db->fetch_array($ret_style);
				 $arr_pymtstyle	= explode(',',$row_style['paymenttype_displaytypes']);
			  ?>
                  <select name="paytype_listingtype">
                    <?
		 		
				 foreach($arr_pymtstyle as $v)
				 {
					$val_arr = explode("=>",$v);
				 ?>
                    <option value="<?=$val_arr[0]?>" <?php echo ($fetch_arr_admin['paytype_listingtype']==$val_arr[0])?'selected':''?>>
                    <?=$val_arr[1]?>
                    </option>
                    <?
				 }
		 ?>
                  </select>
                <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_TYPE_PAY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            <?php
               // Get the list of layouts that can be used for cart page
                     $sql_layout = "SELECT layout_id,layout_name  
                                        FROM 
                                            themes_layouts 
                                        WHERE 
                                            themes_theme_id = $ecom_themeid 
                                            AND layout_support_cart=1 
                                        ORDER BY 
                                            layout_order";
                    $ret_layout = $db->query($sql_layout);
                    if($db->num_rows($ret_layout))
                    {
                        while ($row_layout = $db->fetch_array($ret_layout))
                        {
                            $layout_arr[$row_layout['layout_id']] = stripslashes($row_layout['layout_name']);
                        }                 
                    }
            if (count($layout_arr))
            {
            ?>
                <tr>
                    <td>Product Details Layout</td>
                    <td>
                        <?php 
                            echo generateselectbox('themes_layouts_layout_id',$layout_arr,$fetch_arr_admin['themes_layouts_layout_id']);
                        ?>
                        <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_CAT_LAYOUT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </td>
                </tr>  
          <?php
            }
          ?>
          
          </table></td>
        </tr>
		<tr><td colspan="7" class="tdcolorgray"><table border="0" cellspacing="2" cellpadding="2" width="100%">
		<tr>
		  <td  align="left" valign="middle" class="boldtd" colspan="6">Product Details Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SET_PRODDETAILS_FIELDS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		
		<tr>
		  <td width="2%" align="left" valign="middle" ><input type="checkbox" name="proddet_showfavourite" value="1" <?php echo ($fetch_arr_admin['proddet_showfavourite'] == 1)?"checked":"";?>/></td>
		  <td width="39%" align="left" valign="middle"  >Show Add/Remove to Favourites<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_ADDREMOVE_FAV')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td width="2%" align="left" valign="middle"  ><input type="checkbox" name="bonus_points_instock" value="1" <?php echo ($fetch_arr_admin['bonus_points_instock'] == 1)?"checked":"";?>/></td>
		  <td width="30%" align="left" valign="middle" >Display Bonus Points <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_BONUSPOINTS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td width="2%" align="left" valign="middle">
		    <input type="checkbox" name="proddet_showemailfriend" value="1" <?php echo ($fetch_arr_admin['proddet_showemailfriend'] == 1)?"checked":"";?>/>		  </td>
		  <td width="25%" colspan="2" align="left" valign="middle" >Show Email a Friend <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_EMAILTOFRND')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		
		<tr>
		  <td align="left" valign="middle" ><input type="checkbox" name="proddet_showpdf" value="1" <?php echo ($fetch_arr_admin['proddet_showpdf'] == 1)?"checked":"";?>/></td>
		  <td align="left" valign="middle" >Show Download PDF <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_DOWNLOADPDF')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td align="left" valign="middle" ><input type="checkbox" name="proddet_showwritereview" value="1" <?php echo ($fetch_arr_admin['proddet_showwritereview'] == 1)?"checked":"";?>/></td>
		  <td align="left" valign="middle" >Show Write Review <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SHOW_WRITEREVIEW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td  align="left" valign="middle"><input type="checkbox" name="proddet_showreadreview" value="1" <?php echo ($fetch_arr_admin['proddet_showreadreview'] == 1)?"checked":"";?>/></td>
		  <td  align="left" valign="middle" >Show Read Review <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SHOW_READREVIEW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle" ><input type="checkbox" name="proddet_showwishlist" value="1" <?php echo ($fetch_arr_admin['proddet_showwishlist'] == 1)?"checked":"";?> /></td>
		  <td align="left" valign="middle" >Show Add to Wishlist  <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_SHOW_WISHLIST')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td align="left" valign="middle" ><input type="checkbox" name="show_bookmarks" value="1" <?php echo ($fetch_arr_admin['show_bookmarks'] == 1)?"checked":"";?> /></td>
		  <td align="left" valign="middle" >Show Bookmarks<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_DISPLAY_BOOKMARK')?>')"; onmouseout="hideddrivetip()"> <img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td  align="left" valign="middle"><input type="checkbox" name="show_downloads_newrow" value="1" <?php echo ($fetch_arr_admin['show_downloads_newrow'] == 1)?"checked":"";?> /></td>
		  <td  align="left" valign="middle" >Show downloads in new row<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_DISPLAY_SIZECHART_POPUP')?>')"; onmouseout="hideddrivetip()"> <img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle" ><input type="checkbox" name="showsizechart_in_popup" value="1" <?php echo ($fetch_arr_admin['showsizechart_in_popup'] == 1)?"checked":"";?>></td>
		  <td align="left" valign="middle" >Show sizechart in popup for product details <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_DISPLAY_SIZECHART_POPUP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   <td align="left" valign="middle" ><input type="checkbox" name="proddet_showbarcode" value="1" <?php echo ($fetch_arr_admin['proddet_showbarcode'] == 1)?"checked":"";?>></td>
		  <td align="left" valign="middle" >Show barcode in product details </td>
		  <td  align="left" valign="middle">&nbsp;</td>
		  <td  align="left" valign="middle" >&nbsp;</td>
		  </tr>
		  </table>
		  </td>
		  </tr>
		<tr><td colspan="7" class="tdcolorgray"><table border="0" cellspacing="2" cellpadding="2" width="100%">
		<tr>
		  <td colspan="6" align="left" valign="middle" class="boldtd"><b>Advanced Search</b><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADV_SERACH_FIELD_SHOW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		 </tr>
		<tr>
          <td width="2%" align="left" valign="middle" ><input type="checkbox" name="adv_showkeyword" value="1" <?php echo ($fetch_arr_admin['adv_showkeyword'] == 1)?"checked":"";?>/></td>
		  <td width="39%"  align="left" valign="middle"  >Show Keyword<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADV_SERACH_FIELD_SHOW_KEYWORD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td width="2%"  align="left" valign="middle" ><input type="checkbox" name="adv_showproductmodel" value="1" <?php echo ($fetch_arr_admin['adv_showproductmodel'] == 1)?"checked":"";?>/></td>
		  <td width="30%"  align="left" valign="middle"  >Show Product Model<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADV_SERACH_FIELD_SHOW_MODEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td width="2%" align="left" valign="middle" ><input type="checkbox" name="adv_showlabel" value="1"<?php echo($fetch_arr_admin['adv_showlabel'] == 1)?"checked":"";?> /></td>
		  <td width="25%"   align="left" valign="middle"  >Show Product Labels (If any) <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADV_SERACH_FIELD_SHOW_LABEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle" ><input name="adv_showsearchfor" type="checkbox" id="adv_showsearchfor" value="1" <?php echo($fetch_arr_admin['adv_showsearchfor'] == 1)?"checked":"";?>/></td>
		  <td  align="left" valign="middle"    >Show Search for option </td>
		  <td  align="left" valign="middle"  ><input type="checkbox" name="adv_showcharacteristics" value="1" <?php echo ($fetch_arr_admin['adv_showcharacteristics'] == 1)?"checked":"";?>/></td>
		  <td  align="left" valign="middle" >Show Characteristics<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADV_SERACH_FIELD_SHOW_CHARACTERISTICS')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td align="left" valign="middle" ><input name="adv_shosearchsortby" type="checkbox" id="adv_shosearchsortby" value="1" <?php echo ($fetch_arr_admin['adv_shosearchsortby'] == 1)?"checked":"";?> /></td>
		  <td  align="left" valign="middle"   >Show Sort By </td>
		  </tr>
		<tr>
		  <td align="left" valign="middle" ><input name="adv_showsearchincluding" type="checkbox" id="adv_showsearchincluding" value="1"<?php echo($fetch_arr_admin['adv_showsearchincluding'] == 1)?"checked":"";?> /></td>
		  <td  align="left" valign="middle"    >Show Search Including option </td>
		  <td  align="left" valign="middle"  ><input type="checkbox" name="adv_showstocklevel" value="1" <?php echo ($fetch_arr_admin['adv_showstocklevel'] == 1)?"checked":"";?>/></td>
		  <td  align="left" valign="middle" >Show Stock Level<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADV_SERACH_FIELD_SHOW_STOCKLEVEL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td align="left" valign="middle" ><input name="adv_showsearchperpage" type="checkbox" id="adv_showsearchperpage" value="1" <?php echo ($fetch_arr_admin['adv_showsearchperpage'] == 1)?"checked":"";?>/></td>
		  <td  align="left" valign="middle"   >Show Results Per Page </td>
		  </tr>
		<tr>
		  <td align="left" valign="middle" ><input type="checkbox" name="adv_showcategory" value="1" <?php echo ($fetch_arr_admin['adv_showcategory'] == 1)?"checked":"";?>/></td>
		  <td  align="left" valign="middle"    >Show Category<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADV_SERACH_FIELD_SHOW_CATEGORY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td  align="left" valign="middle"  ><input type="checkbox" name="adv_showpricerange" value="1" <?php echo ($fetch_arr_admin['adv_showpricerange'] == 1)?"checked":"";?>/></td>
		  <td  align="left" valign="middle" >Show Price Range<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADV_SERACH_FIELD_SHOW_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td  align="left" valign="middle"   >&nbsp;</td>
		  </tr>
		
		  </table>
		  </td>
		  </tr>
		  <tr>
		 <td colspan="7" class="tdcolorgray"><table border="0" cellspacing="2" cellpadding="2" width="100%">
		 	<tr>
		   	<td colspan="6" align="left" valign="middle" class="boldtd">Newsletter Subsription <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('NEWS_SUBSCRIBE_SHOW_FIELD_SET')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		   	</tr>
		   <tr>
		  <td width="2%" align="left" valign="middle" ><input type="checkbox" name="newsletter_title_req" value="1" <?php echo ($fetch_arr_admin['newsletter_title_req'] == 1)?"checked":"";?> /></td>
		  <td width="39%" align="left" valign="middle">Show Title </td>
		  <td width="2%" align="left" valign="middle" ><input type="checkbox" name="newsletter_name_req" value="1" <?php echo ($fetch_arr_admin['newsletter_name_req'] == 1)?"checked":"";?> /></td>
		  <td width="30%" align="left" valign="middle" >Show Name </td>
		  <td width="2%" align="left" valign="middle" ><input type="checkbox" name="newsletter_phone_req" value="1" <?php echo ($fetch_arr_admin['newsletter_phone_req'] == 1)?"checked":"";?>/></td>
		  <td width="25%" align="left" valign="middle" >Show Phone </td>
		  </tr>
		  <tr>
		  <td width="2%" align="left" valign="middle" ><input type="checkbox" name="newsletter_group_req" value="1" <?php echo ($fetch_arr_admin['newsletter_group_req'] == 1)?"checked":"";?> /></td>
		  <td width="39%" align="left" valign="middle">Show Newsletter Group </td>
		  <td width="2%" align="left" valign="middle" >&nbsp;</td>
		  <td width="30%" align="left" valign="middle" >&nbsp; </td>
		  <td width="2%" align="left" valign="middle" >&nbsp;</td>
		  <td width="25%" align="left" valign="middle" >&nbsp;</td>
		  </tr>
		   </table></td></tr>
		   <tr>
		 <td colspan="7" class="tdcolorgray">
		 <table border="0" cellspacing="2" cellpadding="2" width="100%">
		 <tr>
		  <td colspan="6" align="left" valign="middle" class="boldtd">Customer Registration Page </td>
		  </tr>
		  <tr>
		  <td width="2%" align="left" valign="middle" ><input type="checkbox" name="show_custreg_newanddiscprod_newsletter_checkbox" value="1" <?php echo ($fetch_arr_admin_1['show_custreg_newanddiscprod_newsletter_checkbox'] == 1)?"checked":"";?> /></td>
		  <td width="39%" align="left" valign="middle">Show New and Discounted Products Newsletter Checkbox </td>
		  <td width="2%" align="left" valign="middle" ><input type="checkbox" name="show_custreg_newslettergroup_checkbox" value="1" <?php echo ($fetch_arr_admin_1['show_custreg_newslettergroup_checkbox'] == 1)?"checked":"";?> /></td>
		  <td width="30%" align="left" valign="middle" >Show Newsletter group Section</td>
		  <td width="2%" align="left" valign="middle" >&nbsp;</td>
		  <td width="25%" align="left" valign="middle" >&nbsp;</td>
		  </tr>
		 </table>
		 </td>
		 </tr>
		<tr>
		 <td colspan="7" class="tdcolorgray"><table border="0" cellspacing="2" cellpadding="2" width="100%">
		 <tr>
		  <td colspan="6" align="left" valign="middle" class="boldtd">Product Compare Page <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('COMPARE_SHOW_FIELD_SET')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		<tr>
		  <td width="2%" align="left" valign="middle" ><input type="checkbox" name="comp_showprice" value="1" <?php echo ($fetch_arr_admin['comp_showprice'] == 1)?"checked":"";?> /></td>
		  <td width="39%" align="left" valign="middle">Show Price </td>
		  <td width="2%" align="left" valign="middle" ><input type="checkbox" name="comp_showlabels" value="1" <?php echo ($fetch_arr_admin['comp_showlabels'] == 1)?"checked":"";?> /></td>
		  <td width="30%" align="left" valign="middle" >Show Key Features </td>
		  <td width="2%" align="left" valign="middle" ><input type="checkbox" name="comp_showstock" value="1" <?php echo ($fetch_arr_admin['comp_showstock'] == 1)?"checked":"";?>/></td>
		  <td width="25%" align="left" valign="middle" >Show Stock </td>
		  </tr>
		
		<tr>
		  <td align="left" valign="middle" ><input type="checkbox" name="comp_showbulkdisc" value="1" <?php echo ($fetch_arr_admin['comp_showbulkdisc'] == 1)?"checked":"";?> /></td>
		  <td align="left" valign="middle" >Show Bulk Discount </td>
		  <td align="left" valign="middle" ><input type="checkbox" name="comp_showmanufact" value="1" <?php echo ($fetch_arr_admin['comp_showmanufact'] == 1)?"checked":"";?> /></td>
		  <td align="left" valign="middle" >Show Product Id </td>
		  <td align="left" valign="middle" ><input type="checkbox" name="comp_showshipping" value="1" <?php echo ($fetch_arr_admin['comp_showshipping'] == 1)?"checked":"";?> /></td>
		  <td align="left" valign="middle" >Shop Extra Shipping Cost </td>
		  </tr>
		<tr>
		  <td align="left" valign="middle" ><input type="checkbox" name="comp_showbonus" value="1" <?php echo ($fetch_arr_admin['comp_showbonus'] == 1)?"checked":"";?> /></td>
		  <td align="left" valign="middle" >Show Bonus Points </td>
		  <td align="left" valign="middle" ><input type="checkbox" name="comp_showweight" value="1" <?php echo ($fetch_arr_admin['comp_showweight'] == 1)?"checked":"";?> /></td>
		  <td align="left" valign="middle" >Show Weight </td>
		  <td align="left" valign="middle" ><input type="checkbox" name="comp_showmodel" value="1" <?php echo ($fetch_arr_admin['comp_showmodel'] == 1)?"checked":"";?> /></td>
		  <td align="left" valign="middle" >Show Model </td>
		  </tr>
		<tr>
		  <td align="left" valign="middle" ><input type="checkbox" name="comp_showrating" value="1" <?php echo ($fetch_arr_admin['comp_showrating'] == 1)?"checked":"";?> /></td>
		  <td align="left" valign="middle" >Show Product Rating </td>
		  <td align="left" valign="middle" ><input type="checkbox" name="comp_showdesc" value="1" <?php echo ($fetch_arr_admin['comp_showdesc'] == 1)?"checked":"";?> /></td>
		  <td align="left" valign="middle" >Show Short Description </td>
		  <td align="left" valign="middle" ><input type="checkbox" name="comp_showfreedelivery" value="1" <?php echo ($fetch_arr_admin['comp_showfreedelivery'] == 1)?"checked":"";?> /></td>
		  <td align="left" valign="middle" >Free Delivery </td>
		  </tr>
		
		  </table></td></tr>
	<tr><td colspan="7" class="tdcolorgray"><table width="100%" border="0">
   <tr>
    <td colspan="4" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="left" valign="top" class="seperationtd" ><strong>Default Settings for Category Add page</strong></td>
    </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
  </tr>
  <tr>
    <td width="13%" align="left" valign="top" class="tdcolorgray" >Subcategory List</td>
    <td width="41%" align="left" valign="top" class="tdcolorgray" ><?php 
				$subcat_list = array('Middle'=>'Show in Middle Area','List'=>'Show below selected category','Both'=>'Both in Middle and Below Selected Category');
				echo generateselectbox('category_subcatlisttype',$subcat_list,$fetch_arr_admin['category_subcatlisttype']);
		  ?>
      <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_ADD_DEFAULT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    <td width="18%" align="left" valign="top" class="tdcolorgray" >Subcategory Display Method </td>
    <td width="28%" align="left" valign="top" class="tdcolorgray" >
	<?php 
				echo generateselectbox('category_subcatlistmethod',$subcatlst_arr,$fetch_arr_admin['category_subcatlistmethod']);
			?>
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_GROUP_DISPMETHOD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>	</td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" >Subcategory Image </td>
    <td align="left" valign="top" class="tdcolorgray" >
	<?PHP 
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
		$subcateg_showimage = $fetch_arr_admin['subcategory_showimagetype'];
		if($subcateg_showimage=='') $subcateg_showimage = 'Medium';
		echo generateselectbox('subcategory_showimagetype',$val_arr,$subcateg_showimage); ?>	</td>
    <td colspan="2" align="left" valign="top" class="tdcolorgray" >Fields to be displayed for Subcategories </td>
    </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
    <td colspan="2" align="left" valign="top" class="tdcolorgray" >
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="4%">&nbsp;</td>
              <td><input name="category_showname" type="checkbox" id="category_showname" value="1" <?php echo ($fetch_arr_admin['category_showname']==1)?'checked="checked"':''?>/>
                Subcategory Name </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input name="category_showimage" type="checkbox" id="category_showimage" value="1" <?php echo ($fetch_arr_admin['category_showimage']==1)?'checked="checked"':''?>/>
                Subcategory Image </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input name="category_showshortdesc" type="checkbox" id="category_showshortdesc" value="1" <?php echo ($fetch_arr_admin['category_showshortdesc']==1)?'checked="checked"':''?>/>
                Subcategory Short description </td>
            </tr>
          </table>	</td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" >Product List</td>
    <td align="left" valign="top" class="tdcolorgray" ><?php 
				$product_list = array('menu'=>'Show in Menu','middle'=>'Show in Middle Area','both' => 'Both in Middle and Menu');
				echo generateselectbox('product_displaywhere',$product_list,$fetch_arr_admin['product_displaywhere']);
			?>
      <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_LISTTYPE_ADD_DEFAULT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    <td align="left" valign="top" class="tdcolorgray" >Product Display Method</td>
    <td align="left" valign="top" class="tdcolorgray" ><?php
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
						//$grp_type = array('OneinRow'=>'One in a Row','ThreeinRow'=>'Three in a Row');
						echo generateselectbox('product_displaytype',$grp_type,$fetch_arr_admin['product_displaytype']);
			?>
      <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_LIST_ADD_DEFAULT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" >Product Ordering </td>
    <td align="left" valign="top" class="tdcolorgray" >
	<?php
					$catgrsort_arr = array('custom'=>'Custom Ordering','product_name'=>'Product Name','price'=>'Price','product_id'=>'Date Added');
					echo generateselectbox('product_orderfield',$catgrsort_arr,$fetch_arr_admin['product_orderfield']); 
					echo "&nbsp;";
					$sort_ord = array('ASC'=>'Asc','DESC'=>'Desc');
					echo generateselectbox('product_orderby',$sort_ord,$row_category['product_orderby']); 
			?>	</td>
    <td colspan="2" align="left" valign="top" class="tdcolorgray" >Fields to be displayed for Products <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('ADD_PROD_CAT_FIELDS_DISP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
    <td colspan="2" align="left" valign="top" class="tdcolorgray" ><table width="100%" border="0" cellspacing="1" cellpadding="1">
      <tr>
        <td width="4%">&nbsp;</td>
        <td width="96%" align="left"><input name="product_showimage" type="checkbox" value="1" <?php echo ($fetch_arr_admin['product_showimage']==1)?'checked="checked"':''?> />
          Product Image </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="left"><input name="product_showtitle" type="checkbox" value="1" <?php echo ($fetch_arr_admin['product_showtitle']==1)?'checked="checked"':''?> />
          Product Title </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="left"><input name="product_showshortdescription" type="checkbox" value="1" <?php echo ($fetch_arr_admin['product_showshortdescription']==1)?'checked="checked"':''?> />
          Product Description </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="left"><input name="product_showprice" type="checkbox" value="1" <?php echo ($fetch_arr_admin['product_showprice']==1)?'checked="checked"':''?> />
          Product Price </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="left"><input name="product_showrating" type="checkbox" value="1" <?php echo ($fetch_arr_admin['product_showrating']==1)?'checked="checked"':''?> />
Product Rating </td>
      </tr>
       <tr>
        <td>&nbsp;</td>
        <td align="left"><input name="product_showbonuspoints" type="checkbox" value="1" <?php echo ($fetch_arr_admin['product_showbonuspoints']==1)?'checked="checked"':''?> />
Product Bonus Points </td>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
    <td align="left" valign="top" class="tdcolorgray" ><input name="chk_category_turnoff_treemenu" type="checkbox" id="chk_category_turnoff_treemenu" value="1" <?php echo ($fetch_arr_admin['category_turnoff_treemenu']==1)?'checked="checked"':''?> />
Turn Off Tree menu in Category Details page </td>
    <td colspan="2" align="left" valign="top" class="tdcolorgray" ><input name="chk_category_turnoff_pdf" type="checkbox" id="chk_category_turnoff_pdf" value="1" <?php echo ($fetch_arr_admin['category_turnoff_pdf']==1)?'checked="checked"':''?>/>
Turn Off PDF in Category Details page </td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
    <td align="left" valign="top" class="tdcolorgray" ><input name="category_turnoff_noproducts" type="checkbox" id="category_turnoff_noproducts" value="1" <?php echo ($fetch_arr_admin['category_turnoff_noproducts']==1)?'checked="checked"':''?>/>
Hide &quot;No Products&quot; message in Category Details Page </td>
    <td colspan="2" align="left" valign="top" class="tdcolorgray" ><input name="category_turnoff_mainimage" type="checkbox" id="category_turnoff_mainimage" value="1" <?php echo ($fetch_arr_admin['category_turnoff_mainimage']==1)?'checked="checked"':''?>/>
Turn Off &quot;Main Image&quot; in Category Details page</td>
    </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
    <td align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
    <td colspan="2" align="left" valign="top" class="tdcolorgray" ><input name="category_turnoff_moreimages" type="checkbox" id="category_turnoff_moreimages" value="1" <?php echo ($fetch_arr_admin['category_turnoff_moreimages']==1)?'checked="checked"':''?>/>
Turn Off &quot;More Images&quot; in Category Details page</td>
  </tr>
  <tr>
    <td colspan="4" align="left" valign="top" class="tdcolorgray" >&nbsp;</td>
  </tr>
   <tr>
    <td colspan="4" align="left" valign="top" class="seperationtd" ><strong>Default Settings for Gift Voucher Purchase by Customer</strong></td>
    </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" colspan="4">Use following section to make necessary settings to be done to the gift voucher when it is purchased by customer from the website.</td>
  </tr>
   <tr>
    <td align="left" valign="top" class="tdcolorgray" colspan="2" > <input type="checkbox" name="gift_voucher_apply_customer_direct_disc_also" id="gift_voucher_apply_customer_direct_disc_also" value="1" <?php echo ($fetch_arr_admin['gift_voucher_apply_customer_direct_disc_also']=='Y')?'checked':''?>/>
    Apply Customer Direct Discount also?
    <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_DIRECT_DISC_VOUCH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    <td align="left" valign="top" class="tdcolorgray" colspan="2">
	<input type="checkbox" name="gift_voucher_apply_customer_group_disc_also" id="gift_voucher_apply_customer_group_disc_also" value="1" <?php echo ($fetch_arr_admin['gift_voucher_apply_customer_group_disc_also']=='Y')?'checked':''?>/>
	
	Apply Customer Group Direct Discount also?
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_CUST_GROUP_DISC_VOUCH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>	</td>
  </tr>
  <tr>
    <td align="left" valign="top" class="tdcolorgray" colspan="2" > <input type="checkbox" name="gift_voucher_apply_direct_product_discount_also" id="gift_voucher_apply_direct_product_discount_also" value="1" <?php echo ($fetch_arr_admin['gift_voucher_apply_direct_product_discount_also']=='Y')?'checked':''?>/>
    Apply Product Direct Discount also?
    <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('APPLY_PROD_DIRECT_DISC_VOUCH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    <td align="left" valign="top" class="tdcolorgray" colspan="2">&nbsp;</td>
  </tr>
</table>

</td></tr>
		  </table></div>
		  </td>
		  </tr>
		  <tr>
			<td colspan="7" class="tdcolorgray">	
		<div class="listingarea_div">
		<table width="100%" border="0" cellpadding="2" cellspacing="2" >
		  <tr>
		  <td align="right" class="tdcolorgray" colspan="7"><input name="Submit" type="submit" class="red" value="Save Settings" />		  </td>
		  </tr>
</table></div>
</td>
</tr>
</table>
		<?php
	}	
	function show_security_list($alert)
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_hostname ;
		$sql 							= "SELECT * FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
		$res_admin 				= $db->query($sql);
		$fetch_arr_admin 	= $db->fetch_array($res_admin);
		
		$sql 							= "SELECT * FROM general_settings_sites_common_onoff WHERE sites_site_id=".$ecom_siteid;
		$res_admin 				= $db->query($sql);
		$fetch_arr_admin_1 	= $db->fetch_array($res_admin);
		
		// This is done to move the fields and its value from general_settings_sites_common_onoff table to the same array which have values from general_settings_sites_common table	
		foreach ($fetch_arr_admin_1 as $k=>$v)
		{
			$fetch_arr_admin[$k]=$v;	
		}
	?>
	<table width="100%" border="0" cellpadding="2" cellspacing="2" >
			<tr>
			<td  class="tdcolorgray">	
		<div class="listingarea_div">
	<table border="0" width="100%" cellspacing="2" cellpadding="2" class="tdcolorgray">
		<tr>
          <td colspan="6" align="left" valign="middle" class="seperationtd" ><a name="Security">&nbsp;</a><b>Security</b></td>
        </tr>
          <?php
			if ($alert)
			{
			?>
				<tr>
				  <td colspan="6" align="center" valign="middle" class="errormsg" id="mainerror_tr" ><?php echo $alert; ?></td>
				</tr>
			<?php
		 	}
		 ?> 
		 <?php /*?><tr>
         <td align="left" valign="middle" width="2%" ><input type="checkbox" name="encrypted_cc_numbers" value="1" <?php echo($fetch_arr_admin['encrypted_cc_numbers'] == 1)?"checked":"";?> /></td>
		 <td colspan="5" align="left" valign="middle"   >Encrypted credit card numbers in MySQL database <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_ENCCARD')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        </tr><?php */?>
		 <tr>
           <td colspan="6" align="left" valign="top" >Ban these IP Address from placing orders:(press Enter after each IP address) <br />
             <textarea name="ban_ipaddress" cols="55" rows="3"><?=stripslashes($fetch_arr_admin['ban_ipaddress'])?>
             </textarea>
             <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_BANIP')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			 <input type="hidden" name="encrypted_cc_numbers" value="1"/>
		   </td>
      </tr>
		
        <tr>
          <td colspan="6" align="left" valign="middle"   ><table width="100%" border="0" cellpadding="2" cellspacing="2">
            <tr class="seperationtd">
              <td colspan="2"><strong>Image Verification Settings in Various Sections </strong><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_IMG_VERIFICATION')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              <td colspan="2"><img src="images/checkbox.gif" border="0" onclick="checkall()"/>&nbsp; <img src="images/uncheckbox.gif" border="0" onclick="uncheckall()" /></td>
            </tr>
            <tr >
              <td width="2%" ><input type="checkbox" name="imageverification_news_req" value="1" <?php echo($fetch_arr_admin['imageverification_req_newsletter'] == 1)?"checked":"";?> /></td>
              <td width="47%" >Newsletter Image Verification <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_IMG_VERIFICATION_NEWSLETTER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              <td width="2%" ><input type="checkbox" name="imageverification_vouch_req" value="1" <?php echo($fetch_arr_admin['imageverification_req_voucher'] == 1)?"checked":"";?> /></td>
              <td width="49%" >Gift Voucher Image Verification <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_IMG_VERIFICATION_GIFTVOUCHER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            <tr >
              <td ><input type="checkbox" name="imageverification_site_req" value="1" <?php echo($fetch_arr_admin['imageverification_req_sitereview'] == 1)?"checked":"";?> /></td>
              <td >Site Reviews Image Verification <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_IMG_VERIFICATION_SITEREVIEW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              <td ><input type="checkbox" name="imageverification_cust_req" value="1" <?php echo($fetch_arr_admin['imageverification_req_customreg'] == 1)?"checked":"";?> /></td>
              <td >Customer Registration Image Verification <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_IMG_VERIFICATION_CUSTREG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            <tr >
              <td ><input type="checkbox" name="imageverification_prod_req" value="1" <?php echo($fetch_arr_admin['imageverification_req_prodreview'] == 1)?"checked":"";?> /></td>
              <td >Product review Image Verification <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_IMG_VERIFICATION_PRODREVIEW')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              <td ><input type="checkbox" name="imageverification_req_payonaccount" value="1" <?php echo($fetch_arr_admin['imageverification_req_payonaccount'] == 1)?"checked":"";?> /></td>
              <td >Pay On Account Details <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_IMG_VERIFICATION_PAYONACCOUNT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
			  <tr >
              <td ><input type="checkbox" name="imageverification_callback_req" value="1" <?php echo($fetch_arr_admin['imageverification_req_callback'] == 1)?"checked":"";?> /></td>
              <td >Callback Image Verification <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_IMG_VERIFICATION_CALLBACK')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
              <td >&nbsp;</td>
              <td >&nbsp;</td>
            </tr>
			  <tr >
			    <td colspan="4" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="4" class="seperationtd"><strong>Feed  Settings</strong></td>
                  </tr>
                  <tr>
                    <td width="18%" align="left" style="padding-left:10px">Security Key&nbsp;&nbsp;</td>
                    <td width="1%">:</td>
                    <td colspan="2"><input type="text" name="steamdesk_security_key" id="steamdesk_security_key" size="40" value="<?php echo $fetch_arr_admin['steamdesk_security_key']?>" /> <a href="#" onmouseover ="ddrivetip('This security key will be used to authenticate the export and call back webservice requests.')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
                  </tr>
                  <?php
				  if(trim($fetch_arr_admin['steamdesk_security_key'])!='')
				  {
				  ?>
                      <tr align="left">
                        <td colspan="4">&nbsp;</td>
                      </tr>
                      <tr align="left">
                        <td colspan="4"><strong>Steamdesk Settings</strong></td>
                      </tr>
                      <tr>
                        <td align="left" style="padding-left:10px" class="tdcolorgray_url">Export URL</td>
                        <td class="tdcolorgray_url">:</td>
                        <td colspan="2" class="tdcolorgray_url">http://<?php echo $ecom_hostname?>/console/steamdesk_export.php?key=<?php echo trim($fetch_arr_admin['steamdesk_security_key'])?></td>
                      </tr>
                      <tr>
                        <td align="left" style="padding-left:10px" class="tdcolorgray_url">Callback URL</td>
                        <td class="tdcolorgray_url">:</td>
                        <td colspan="2" class="tdcolorgray_url">http://<?php echo $ecom_hostname?>/console/steamdesk_callback.php?key=<?php echo trim($fetch_arr_admin['steamdesk_security_key'])?>&order_id=[order_id]</td>
                      </tr>
                      <tr>
                        <td align="left" style="padding-left:10px">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td width="11%" align="right" class="redtext"><strong>Note</strong>:&nbsp;&nbsp;</td>
                        <td width="70%" class="redtext"><strong>[order_id] should be replaced with a valid order id in the callback URL.</strong></td>
                      </tr>
                      <tr align="left">
                        <td colspan="4">&nbsp;</td>
                      </tr>
                      <tr align="left">
                        <td colspan="4"><strong>Bing Settings</strong></td>
                      </tr>
                      <tr>
                        <td align="left" style="padding-left:10px" class="tdcolorgray_url">Data Feed URL </td>
                        <td align="left" class="tdcolorgray_url">:</td>
                        <td colspan="2" align="left" class="tdcolorgray_url">http://<?php echo $ecom_hostname?>/bingfeed_export-b<?php echo trim($fetch_arr_admin['steamdesk_security_key'])?>.txt</td>
                      </tr>
                 <?php
				 }
				 ?>     
                </table></td>
		    </tr>
          </table></td>
	  </tr>				
				
</table>
     </div>
     </td>
     </tr>
     <tr>
		<td >	
		<div class="listingarea_div">
	<table border="0" width="100%" cellspacing="2" cellpadding="2" class="">
    <tr>
		  <td align="right"  ><input name="Submit" type="button" class="red" value="Save Settings" onclick="save_settings('security')"/>		  </td>
		  </tr>
     </table> 
     </div>
     </td>
     </tr>
     </table>  
	<?
	}
	function show_administration_list($alert)
	{
	global $db,$ecom_siteid,$ecom_themeid,$ecom_site_hide_console_error_msgs;
	$sql 							= "SELECT * FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
		$res_admin 				= $db->query($sql);
		$fetch_arr_admin 	= $db->fetch_array($res_admin);
		
		$sql 							= "SELECT * FROM general_settings_sites_common_onoff WHERE sites_site_id=".$ecom_siteid;
		$res_admin 				= $db->query($sql);
		$fetch_arr_admin_1 	= $db->fetch_array($res_admin);
		
		// This is done to move the fields and its value from general_settings_sites_common_onoff table to the same array which have values from general_settings_sites_common table	
		foreach ($fetch_arr_admin_1 as $k=>$v)
		{
			$fetch_arr_admin[$k]=$v;	
		}
		
	?>
	<table width="100%" border="0" cellpadding="2" cellspacing="2" >
			<tr>
			<td  class="tdcolorgray">	
		<div class="listingarea_div">
	<table border="0" cellspacing="2" cellpadding="2" width="100%" class="tdcolorgray">
      <?php
			if ($alert)
			{
			?>
      <tr>
        <td colspan="4" align="center" valign="middle" class="errormsg" id="mainerror_tr" ><?php echo $alert; ?></td>
      </tr>
      <?php
		 	}
		 ?>
      <tr>
        <td colspan="4" align="left" valign="middle" class="seperationtd" ><a name="Administration_Area">&nbsp;</a><b>Administration Area</b></td>
      </tr>
      <tr>
        <td align="left" valign="middle" width="2%" ><input type="checkbox" name="terms_and_condition_at_checkout" value="1" <?php echo($fetch_arr_admin['terms_and_condition_at_checkout'] == 1)?"checked":"";?> /></td>
        <td width="44%" align="left" valign="middle">Terms and Conditions required during checkout <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_TERMCOND')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        <td align="left" valign="middle"   >Voucher Prefix</td>
        <td align="left" valign="middle"   ><input type="text" name="voucher_prefix" value="<?PHP echo $fetch_arr_admin['voucher_prefix']; ?>" />
          <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PREFIX_GIFTCOUCHER_CODE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
      </tr>
      <tr>
        <td align="left" valign="middle" width="2%" ><input type="checkbox" name="hide_addtocart_login" value="1" <?php echo($fetch_arr_admin['hide_addtocart_login'] == 1)?"checked":"";?>/></td>
        <td align="left" valign="middle"  >Hide Add to Cart until Login<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_HIDEADDCART')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        <td width="2%" align="left" valign="middle"   >Unit of Weight</td>
        <td  align="left" valign="middle" ><input type="text" name="unit_of_weight" value="<?PHP echo $fetch_arr_admin['unit_of_weight']; ?>" />
          <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('UNIT_WEIGHT_DEFAULT_PRODUCT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
      </tr>
      <tr>
        <td align="left" valign="middle" ><input type="checkbox" name="enable_caching_in_site" value="1" <?php echo($fetch_arr_admin['enable_caching_in_site'] == 1)?"checked":"";?>/></td>
        <td align="left" valign="middle"  >Enable caching for the site <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_ENABLE_CACHING')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        <td align="left" valign="middle"  nowrap="nowrap" >Default Product Specification Heading</td>
        <td width="36%" align="left" valign="middle" nowrap="nowrap"  ><input type="text" name="product_sizechart_default_mainheading" id="product_sizechart_default_mainheading" value="<?php echo $fetch_arr_admin['product_sizechart_default_mainheading']; ?>" />
          <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('DEFAULT_HEADING_SIZECHART_SET')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
      </tr>
       <tr>
        <td align="left" valign="middle"></td>
        <td align="left" valign="middle"></td>
        <td align="left" valign="middle">Variable Additional Price Display</td>
        <td align="left" valign="middle">
			<select name='product_variable_display_type'>
					<option value='ADD' <?php echo ($fetch_arr_admin['product_variable_display_type'] == 'ADD')?'selected':''?>>Add / Less Price</option>
					<option value='FULL' <?php echo ($fetch_arr_admin['product_variable_display_type'] == 'FULL')?'selected':''?>>Full Price</option>
			</select>		</td>
      </tr>
      <tr>
        <td align="left" valign="middle" ><input type="checkbox" name="hide_price_login" value="1" <?php echo($fetch_arr_admin['hide_price_login'] == 1)?"checked":"";?>/></td>
        <td align="left" valign="middle"  >Hide Product price until Login<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_HIDEPRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        <td colspan="2" align="left" valign="middle"  nowrap="nowrap" class="seperationtd" >Javascripts to be included in head section </td>
      </tr>
      <tr>
        <td align="left" valign="middle" ><input type="checkbox" name="forcecustomer_login_checkout" value="1" <?php echo($fetch_arr_admin['forcecustomer_login_checkout'] == 1)?"checked":"";?>/></td>
        <td align="left" valign="middle"   >Force Customer to Login before Checkout<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_CHKOUTLOGIN')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        <td align="left" valign="middle"  nowrap="nowrap" >Lightbox Javascript required? </td>
        <td align="left" valign="middle"  nowrap="nowrap" ><input type="checkbox" name="javascript_lightbox" id="javascript_lightbox" value="1" <?php echo($fetch_arr_admin['javascript_lightbox'] == 1)?"checked":"";?>/></td>
      </tr>
      <tr>
        <td align="left" valign="middle" ><input type="checkbox" name="same_billing_shipping_checkout" value="1" <?php echo($fetch_arr_admin['same_billing_shipping_checkout'] == 1)?"checked":"";?>/></td>
        <td align="left" valign="middle"   >Force the Customer's billing & shipping to be the same address <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_BILLADDR')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
        <td align="left" valign="middle"  nowrap="nowrap" >Javascript Image Swapping required?</td>
        <td align="left" valign="middle"  nowrap="nowrap" ><input type="checkbox" name="javascript_imageswap" id="javascript_imageswap" value="1" <?php echo($fetch_arr_admin['javascript_imageswap'] == 1)?"checked":"";?>/></td>
      </tr>
     <tr>
        <td align="left" valign="middle" ><input type="checkbox" name="site_hide_console_error_msgs" value="1" <?php echo($ecom_site_hide_console_error_msgs == 1)?"checked":"";?>/></td>
        <td align="left" valign="middle">Hide Errors and Warnings from the console home page</td>
        <td align="left" valign="middle"  nowrap="nowrap" >Jquery required? </td>
        <td align="left" valign="middle"  nowrap="nowrap" ><input type="checkbox" name="javascript_jquery" id="javascript_jquery" value="1" <?php echo($fetch_arr_admin['javascript_jquery'] == 1)?"checked":"";?>/></td>
      </tr>
     <tr>
       <td align="left" valign="middle" ><input type="checkbox" name="printerfriendly_include_delivery_address" value="1" <?php echo($fetch_arr_admin['printerfriendly_include_delivery_address'] == 1)?"checked":"";?>/></td>
       <td align="left" valign="middle">Show both Billing and Delivery Address in Orders Printer Friendly </td>
       <td align="left" valign="middle"  nowrap="nowrap" >Enable Ajax? </td>
        <td align="left" valign="middle"  nowrap="nowrap" ><input type="checkbox" name="enable_ajax_in_site" id="enable_ajax_in_site" value="1" <?php echo($fetch_arr_admin['enable_ajax_in_site'] == 1)?"checked":"";?>/></td>
     </tr>
       <tr>
       <td align="left" valign="middle" ><input type="checkbox" name="add_barcode_to_product_keyword" value="1" <?php echo($fetch_arr_admin['add_barcode_to_product_keyword'] == 1)?"checked":"";?>/></td>
       <td align="left" valign="middle">Append Product barcodes in product keywords field? </td>
       <td align="left" valign="middle"  nowrap="nowrap" >Enable search autocomplete?  </td>
       <td align="left" valign="middle"  nowrap="nowrap" ><input type="checkbox" name="enable_search_autocomplete" id="enable_search_autocomplete" value="1" <?php echo($fetch_arr_admin['enable_search_autocomplete'] == 1)?"checked":"";?>/></td>
     </tr>
	 <!-- Product Special Display Change Starts Here -->
       <tr>
         <td align="left" valign="middle" ><input type="checkbox" name="proddet_special_display" id="proddet_special_display" value="1" <?php echo($fetch_arr_admin['proddet_special_display'] == 1)?"checked":"";?>/></td>
         <td align="left" valign="middle">Enable Special Type of dispay in product details? <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('PRODDET_SPECIAL_DISMSG')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         <td align="left" valign="middle"  nowrap="nowrap" class="seperationtd" colspan="2">Delivery Date Display Settings</td>
       </tr>
	  <!-- Product Special Display Change Ends Here --> 
      
      <!-- Enable Search Refine Code Starts Here -->
       <tr>
         <td align="left" valign="middle" ><input type="checkbox" name="enable_search_refine_category" id="enable_search_refine_category" value="1" <?php echo($fetch_arr_admin['enable_search_refine_category'] == 1)?"checked":"";?>/></td>
         <td align="left" valign="middle">Enable Search Refine in Categories Page? <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('REFINE_CATEGORY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         <td align="left" valign="middle"  nowrap="nowrap" >Activate Expected Delivery Date Display</td>
         <td align="left" valign="middle"  nowrap="nowrap" ><input type="checkbox" name="enable_exp_deliverydate" id="enable_exp_deliverydate" value="1" <?php echo($fetch_arr_admin['enable_exp_deliverydate'] == 1)?"checked":"";?>/></td>
       </tr>
       <tr>
         <td align="left" valign="middle" ><input type="checkbox" name="enable_search_refine_search" id="enable_search_refine_search" value="1" <?php echo($fetch_arr_admin['enable_search_refine_search'] == 1)?"checked":"";?>/></td>
         <td align="left" valign="middle">Enable Search Refine in Search Results Page? <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('REFINE_SEARCH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         <td align="left" valign="middle"  nowrap="nowrap" colspan="2" >Normal Delivery Will Be Done In <input type="text" name="exp_deliverydate_normal_days" id="exp_deliverydate_normal_days" value="<?php echo $fetch_arr_admin['exp_deliverydate_normal_days']?>" size="3" style="width:30px;" /> day(s)</td>
       </tr>
        <tr>
         <td align="left" valign="middle" ><input type="checkbox" name="enable_intermediate_cart" id="enable_intermediate_cart" value="1" <?php echo($fetch_arr_admin['enable_intermediate_cart'] == 1)?"checked":"";?>/></td>
         <td align="left" valign="middle">Enable Cart Intermediate Page? <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('CART_INTER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         <td align="left" valign="middle"  nowrap="nowrap" colspan="2" >Daily Delivery Start Time&nbsp;
		 <?php
         $deldate_value_arr = explode(":",$fetch_arr_admin['exp_deliverydate_normal_time']);
         $deldate_hr_val = ltrim($deldate_value_arr[0],'0');
         $deldate_min_val = ltrim($deldate_value_arr[1],'0');
         ?>
         Hour: <select name="exp_deliverydate_normal_time_hr" id="exp_deliverydate_normal_time_hr">
         <?php
         for($di=0;$di<24;$di++)
         {
		 ?>
			<option value="<?php echo $di?>" <?php echo ($di==$deldate_hr_val)?'selected="selected"':''?>><?php echo ($di<10)?'0'.$di:$di?></option>
		 <?php
		 }
         ?>
         </select>
         &nbsp;
         Minute: <select name="exp_deliverydate_normal_time_min" id="exp_deliverydate_normal_time_min">
         <?php
         for($di=0;$di<60;$di++)
         {
		 ?>
			<option value="<?php echo $di?>" <?php echo ($di==$deldate_min_val)?'selected="selected"':''?>><?php echo ($di<10)?'0'.$di:$di?></option>
		 <?php
		 }
         ?>
         </select>
         <?php /* &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<strong>Current Time :</strong> <?php echo date('H:i')?>) */?>
         </td>
       </tr>
       <!-- Enable Search Refine Code Ends Here -->
    </table>
    </div>
    </td>
    </tr>
    <tr>
	<td  class="tdcolorgray">	
	<div class="listingarea_div">
    <table width="100%" border="0" cellpadding="2" cellspacing="2" >
		 <tr>
        <td align="right" class="tdcolorgray" colspan="4"><input name="Submit2" type="button" class="red" value="Save Settings" onclick="save_settings('admin')"/>        </td>
      </tr>
    </table>
    </div>
    </td>
    </tr>
    </table>	
	<?
	}
	function show_inventory_list($alert)
	{
	global $db,$ecom_siteid,$ecom_themeid ;
	$sql 							= "SELECT * FROM general_settings_sites_common WHERE sites_site_id=".$ecom_siteid;
		$res_admin 				= $db->query($sql);
		$fetch_arr_admin 	= $db->fetch_array($res_admin);
		
		$sql 							= "SELECT * FROM general_settings_sites_common_onoff WHERE sites_site_id=".$ecom_siteid;
		$res_admin 				= $db->query($sql);
		$fetch_arr_admin_1 	= $db->fetch_array($res_admin);
		
		// This is done to move the fields and its value from general_settings_sites_common_onoff table to the same array which have values from general_settings_sites_common table	
		foreach ($fetch_arr_admin_1 as $k=>$v)
		{
			$fetch_arr_admin[$k]=$v;	
		}
	?>
	<table width="100%" border="0" cellpadding="2" cellspacing="2" >
			<tr>
			<td  class="tdcolorgray">	
		<div class="listingarea_div">
		<table border="0" cellspacing="2" cellpadding="2" width="100%" class="tdcolorgray">
		 <?php
			if ($alert)
			{
			?>
				<tr>
				  <td colspan="6" align="center" valign="middle" class="errormsg" id="mainerror_tr" ><?php echo $alert; ?></td>
				</tr>
			<?php
		 	}
		 ?> 
		<tr>
          <td colspan="6" align="left" valign="middle" class="seperationtd" ><a name="Inventory_Management">&nbsp;</a><b>Inventory Management</b></td>
        </tr>
        <tr>
          
         <td align="left" valign="middle" width="2%" ><input type="checkbox" name="product_maintainstock" value="1" <?php echo($fetch_arr_admin['product_maintainstock'] == 1)?"checked":"";?> onclick="product_decr_display()"/></td>
		 <td colspan="2" align="left" valign="middle"   >Enable Stock management <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_STKMGMNT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
         <td align="left" valign="middle" width="2%"   >&nbsp;</td>
         <td colspan="2" align="left" valign="middle"   >&nbsp;</td>
        </tr>
		<tr id="prd_decr" <?PHP if($fetch_arr_admin['product_maintainstock'] != 1) { ?> style="display:none;" <? } ?>>
		<td colspan="6" align="left"  >
		<table width="98%" border="0" align="right"><tr>
		  <td width="2%" align="left" valign="middle" ><input type="checkbox" name="product_decrstock" value="1" <?php echo($fetch_arr_admin['product_decrementstock'] == 1)?"checked":"";?> /></td>
		  <td valign="middle" align="left" >Decrement Product Stock on Purchase <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_STKDECREMENT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		  </tr>
		  <tr>
		    <td align="left" valign="middle" ><input type="checkbox" name="product_show_instock" value="1" <?php echo($fetch_arr_admin['product_show_instock'] == 1)?"checked":"";?>/></td>
		    <td valign="middle" align="left" >Display stock details to customers in product details page.<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_STCKDET')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr>
			<tr>
			<td align="left" valign="middle" width="2%"><input type="checkbox" name="product_hide_preorder_msg" value="1" <?php echo($fetch_arr_admin['product_hide_preorder_msg'] == 1)?"checked":"";?>/></td>
			<td valign="middle" align="left" >Display Preorder stock message to customers in product details page.<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_PREORDERDET_HIDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			</tr>
            <tr>
            <td colspan="2"><table width="100%" cellspacing="0" cellpadding="0">
            <tr>
            <td width="3%">
			<td valign="middle" align="left" width="10%">Reorder Quantity</td>
			<td align="left" valign="middle" ><input type="text" name="product_reorder_qty" value="<?php  echo $fetch_arr_admin['product_reorder_qty'] ?>"  size="8"/>&nbsp;<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_REORDER_QTY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
            </tr>
            </table>
            </td>
            </tr>
		 <?php /*?> <tr>
		    <td align="left" valign="middle" ><input type="checkbox" name="check_stock_management_before_checkout" value="1" <?php echo($fetch_arr_admin['check_stock_management_before_checkout'] == 1)?"checked":"";?>/></td>
		    <td valign="middle" align="left" >Checks inventory before checkout is submitted<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_MAINSHOP_INVTRY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
	      </tr><?php */?>
		</table></td>
	  </tr>
		<tr>
          <td align="left" valign="middle" width="2%" >&nbsp;</td>
          <td colspan="2" align="left" valign="middle" >&nbsp;</td>
         <td align="left" valign="middle"    >&nbsp;</td>
         <td colspan="2" align="left" valign="middle"  >&nbsp;</td>
		</tr>
		<tr>
		  <td align="center" class="tdcolorgray" colspan="6">&nbsp;
		  </td>
  </tr>		
  </tr>
</table>
</div>
	     </td>
	     </tr>
	     <tr>
			<td  class="tdcolorgray">	
		<div class="listingarea_div">
		<table border="0" cellspacing="2" cellpadding="2" width="100%" class="tdcolorgray">
			<tr>
		  <td align="right" class="tdcolorgray" ><input name="Submit" type="button" class="red" value="Save Settings" onclick="save_settings('inventory')"/>
		  </td>
  </tr>
    </table>
    </div>
    </td>
    </tr>
    </table>
	<?php
	}
	     
	function show_email_inactive($alert)
	{
	global $db,$ecom_siteid,$ecom_themeid ;
	
	$sql_inactive = "SELECT * FROM general_settings_sites_mail_inactivecustomers WHERE sites_site_id=".$ecom_siteid." LIMIT 1";
	$ret_inactive = $db->query($sql_inactive);
	if($db->num_rows($ret_inactive)>0) 
		{
		$row_inactive = $db->fetch_array($ret_inactive);
		}
	?>		
	<table width="100%" border="0" cellpadding="2" cellspacing="2" >
		<tr>
		<td  class="tdcolorgray">	
		<div class="listingarea_div">
	
		<table border="0" cellspacing="2" cellpadding="2" width="100%" class="tdcolorgray">
		 <?php
			if ($alert)
			{
			?>
				<tr>
				  <td colspan="6" align="center" valign="middle" class="errormsg" id="mainerror_tr" ><?php echo $alert; ?></td>
				</tr>
			<?php
		 	}
		 ?> 
		<tr>
          <td colspan="6" align="left" valign="middle" class="seperationtd" ><a name="Inventory_Management">&nbsp;</a><b>Sent Email To Inactive Customers</b></td>
        </tr>  
		<tr>
          <td colspan="6" align="left" valign="middle" class="listingtableheader" ><?php echo get_help_messages('PREVIEW_EMAIL_CRITERIA');?></td>
        </tr>       
		<tr >
		<td colspan="6" align="left"  >
		<table width="100%" border="0" align="right">
		<?php
		if($row_inactive['preview_status']==1)
		{
		?>
		<tr>
		<td align="left" valign="middle"  >&nbsp;</td>
		<td align="left" valign="middle"  >&nbsp;</td>
		<td  align="right"><input name="Submit" type="button" class="red" value="Email Preview" onclick="preview_settings()"/>
		  <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_EMAIL_PREV_DIRECT');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
		</tr>
		<?php
	    }
		?>
		<tr>
		 <td align="left" valign="middle" width="2%" >&nbsp;</td>
		 <td align="left" valign="middle" width="10%"  >Is Active</td>
		 <td align="left" valign="middle"  ><input type="checkbox" name="is_active" value="1" <?php echo($row_inactive['is_active'] == 1)?"checked":"";?> /><a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_EMAIL_ACTIVATE');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17"></a></td>
        </tr>
        <tr>
        <td colspan="6" align="left"  >
		<table width="100%" border="0" align="right">
			 <tr>
		 <td align="left" valign="middle" width="2%" >&nbsp;</td>
		 <td align="left" valign="middle" width="10%"  >Email Interval</td>
		 <td width="24%" align="left" valign="middle"  ><input type="text" name="email_interval" id="email_interval" value="<?php echo $row_inactive['email_interval'] ?>" onblur="document.getElementById('logged_textbox').innerHTML=document.getElementById('email_interval').value;document.getElementById('purchase_textbox').innerHTML=document.getElementById('email_interval').value" size="5"/> 
		 days <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_EMAIL_INTERVAL');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
		  <td align="left" valign="middle" width="20%"  >&nbsp;</td>
		 <td width="44%" align="left" valign="middle"  >&nbsp;</td>
        </tr>
        <?php
        $date_arry = array();
        $date_last = ''; 
        if($row_inactive['last_email_sent']!='')
        { 
        $date_arry       = explode('-',$row_inactive['last_email_sent']);        
			if(count($date_arry)>0)
			{ 
				$date_last       = $date_arry[2].'-'.$date_arry[1].'-'.$date_arry[0];
			}     
		}
        ?>
		<tr>
		 <td align="left" valign="middle" width="2%" >&nbsp;</td>
		 <td align="left" valign="middle" width="10%"  >Last Email Sent On</td>
		 <td align="left" valign="middle"  ><?php echo ($date_last)?$date_last:'00-00-0000'?>&nbsp;<a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_EMAIL_LASTDATE');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
		 <td align="left" valign="middle" width="20%"  >Next Email will be/should be Sent On</td>
		 <?php 
		 if($row_inactive['next_email_sent'])
		 {
			 $date = $row_inactive['next_email_sent'];
		  $date_arr = explode('-',$date);
		  $date     = 	$date_arr[2]."-".$date_arr[1]."-".$date_arr[0];
		 }
		 ?>
		 <td align="left" valign="middle"  ><input type="text" readonly="readonly" name="next_email_sent" id="next_email_sent" value="<?php echo $date; ?>" size="16">
		 &nbsp;&nbsp;<a href="javascript:show_calendar('frmGeneralSettings.next_email_sent');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0"  align="absmiddle"/></a>
		 <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_EMAIL_NEXTDATE');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
        </tr>
        <tr>
			<td colspan="5">&nbsp;
			</td>
			</tr>
        <tr>
			<td colspan="5">
			<table width="100%" border="0" align="right">
				<tr>
				 <td align="left" valign="middle" width="2%" >&nbsp;</td>
				 <td align="left" valign="middle" width="20%"  >Sent Email To Customers <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_EMAIL_SENDCUST');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
				 <td align="left" valign="middle"  ><!--<input type="checkbox" name="sent_email_cust" id="sent_email_cust" value="1" disabled="disabled" <?php if($row_inactive['sent_email_not_logged']== 'YES' || $row_inactive['sent_email_not_purchase'] == 'YES') {echo "checked";}else{ echo "";}?> />--></td>
				</tr>
				<tr>
				 <td align="left" valign="middle" width="2%" >&nbsp;</td>
				<td align="left" valign="middle" colspan="2"  > 
				 <table width="100%" border="0" align="right">
				<tr>
				 <td align="left" valign="middle" width="5%" >&nbsp;</td>
				 <td align="left" valign="middle" width="5%"  ><input type="checkbox" name="sent_email_not_logged" value="1" id="sent_email_not_logged" <?php echo($row_inactive['sent_email_not_logged'] == 'YES')?"checked":"";?> onclick="val_checkbox()" /></td>
				 <td align="left" valign="middle"  >Who have not logged in for a period of <label id="logged_textbox"><?php echo $row_inactive['email_interval'] ?></label> 
				 days <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_EMAIL_SENDCUSTNOLOG');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
				</tr>
				<tr>
				 <td align="left" valign="middle" width="5%" >&nbsp;</td>
				 <td align="left" valign="middle" width="5%"  ><input type="checkbox" name="sent_email_purchase" id="sent_email_purchase" value="1"  <?php echo($row_inactive['sent_email_purchase'] == 'YES')?"checked":"";?> onclick="val_checkbox()" /></td>
				 <td align="left" valign="middle"  >Who have placed orders for a period of <label id="purchase_textbox"><?php echo $row_inactive['email_interval'] ?></label> 
				 days <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_EMAIL_SENDCUSTORDER');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
				</tr>
				<tr>
				 <td align="left" valign="middle" width="5%" >&nbsp;</td>
				 <td align="left" valign="middle" width="5%"  ><input type="checkbox" name="sent_email_not_purchase" id="sent_email_not_purchase" value="1"  <?php echo($row_inactive['sent_email_not_purchase'] == 'YES')?"checked":"";?> onclick="val_checkbox()" /></td>
				 <td align="left" valign="middle"  >Who have not placed orders for a period of <label id="purchase_textbox"><?php echo $row_inactive['email_interval'] ?></label> 
				 days <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_EMAIL_SENDCUSTNOORDER');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
				</tr>
				<tr>
				 <td align="left" valign="middle" width="5%" >&nbsp;</td>
				 <td align="left" valign="middle" width="5%"  ><input type="checkbox" name="sent_email_reg_cust" id="sent_email_reg_cust" value="1"  <?php echo($row_inactive['sent_email_reg_cust'] == 'YES')?"checked":"";?> onclick="val_checkbox()" /></td>
				 <td align="left" valign="middle"  >Who have registered for a period of <label id="purchase_textbox"><?php echo $row_inactive['email_interval'] ?></label> 
				 days <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_EMAIL_SENDCUST');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
				</tr>
				<?php
				 $cat_arr = generate_category_tree(0,0,true);
				 if(count($cat_arr)>0)
				 {
				?>
				<tr>
				 <td align="left" valign="middle" width="5%" >&nbsp;</td>
				 <td align="left" valign="middle" width="5%"  ><input type="checkbox" name="sent_email_category" id="sent_email_category" value="1"  <?php echo($row_inactive['sent_email_category'] == 'YES')?"checked":"";?> onclick="val_checkbox()" /></td>
				 <td align="left" valign="middle"  >Who  bought products from <?php 
				
				if(is_array($cat_arr))
				{
					echo generateselectbox('sent_category_id',$cat_arr,$row_inactive['sent_category_id']);
				}
				 ?>  of <label id="purchase_textbox"><?php echo $row_inactive['email_interval'] ?></label> 
				 days <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_EMAIL_SENDCUST');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
				</tr>
				<?php
				}
				?>
				<tr>
				 <td align="left" valign="middle" width="5%" >&nbsp;</td>
				 <td align="left" valign="middle" width="5%"  ><input type="checkbox" name="sent_email_bonus" id="sent_email_bonus" value="1"  <?php echo($row_inactive['sent_email_bonus'] == 'YES')?"checked":"";?> onclick="val_checkbox()" /></td>
				 <td align="left" valign="middle"  >Who have bonus points <label id="purchase_textbox">
					 <input id="sent_bonus" type="text" size="5"  value="<?php echo $row_inactive['sent_bonus'] ?>" name="sent_bonus"></input>
					 </label> 
				  <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_BONUS');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
				</tr>
				</table>
				 </td>
				 </tr>
			</table>
        </td>    
        </tr>  
        <tr >
		<td colspan="6" align="left"  >
		<table width="100%" border="0" align="right">
		<tr>
		         <td align="left" valign="middle" width="2%" >&nbsp;</td>
				 <td align="left" valign="middle" width="10%"  >Email Subject </td>
				 <td align="left" valign="middle"  ><input type="text" name="email_subject" value="<?php echo $row_inactive['email_subject'] ?>" size="80"  /></td>
				</tr>
				<tr>
		         <td align="left" valign="middle" width="2%" >&nbsp;</td>
				 <td align="left" valign="top" width="10%"  >Email Content </td>
				 <td align="left" valign="middle"  ><?php 
		   
		   
						//$editor_elements = "email_content";
						//include_once("js/tinymce.php");
						/*include_once("classes/fckeditor.php");
						$editor 			= new FCKeditor('newsletter_contents') ;
						$editor->BasePath 	= '/console/js/FCKeditor/';
						$editor->Width 		= '850';
						$editor->Height 	= '500';
						$editor->ToolbarSet = 'BshopWithImages';
						$editor->Value 		= stripslashes($template);
						$editor->Create() ;*/
				       
		?>
		<textarea style="height:500px; width:850px" id="email_content" name="email_content"><?=stripslashes($row_inactive['email_content'])?></textarea></td>
				</tr>
		</table>
		</td>
		</tr>
		<tr class="listingarea" >
		 <td colspan="6" align="left" valign="middle" class="tdcolorgray" ><table width="70%" border="0" class="listingtable">
             <tr >
               <td colspan="3" class="helpmsgtd" align="left"><?=get_help_messages('EMAILCODE_REPLACE')?></td>
             </tr>
             <tr class="listingtableheader">
               <td width="17%"><div align="left"><strong>&nbsp; Code</strong></div></td>
               <td width="5%">&nbsp;</td>
               <td width="78%"><div align="left"><strong>&nbsp; Description</strong></div></td>
             </tr>
             <?PHP 
             $emailcode = array(get_help_messages('LIST_EMAIL_CODE_PRODUCT_MESS1')=>'[Products]');						

			 	foreach($emailcode AS $key=>$val) {
			 ?>
             <tr class="listingtablestyleB">
               <td align="left" > &nbsp; <?PHP echo $val; ?></td>
               <td>=&gt;</td>
               <td align="left">&nbsp; <?PHP echo $key; ?></td>
             </tr>
             <?PHP } ?>
           </table></td>
    </tr>	 
		</table>
		</td>
	  </tr>			
  </table>
  </td>
  </tr>
  </table>
  </div>
  </td>
  </tr>
			<tr>
			<td  class="tdcolorgray">	
		<div class="listingarea_div">
			  <table width="100%" border="0" cellpadding="2" cellspacing="2" >
<tr>
		  <td  class="tdcolorgray" colspan="3" align="right"><input name="Submit" type="button" class="red" value="Save Settings" onclick="save_settings_email('email','save')"/>
		    <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_EMAIL_SAVEBUTTON');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a>
		 <?php
		 if($db->num_rows($ret_inactive)>0) 
		 {
		 ?>
		 <input name="Submit" type="button" class="red" value="Save & Continue" onclick="save_settings_email('email','savec')"/>
		 <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_EMAIL_SAVECONTBUTTON');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a>
		 <input name="email_id" id="email_id" type="hidden"  value="<?php $row_inactive['id'] ?>" />
		 
		 <?php
		 }
		 ?>

		 <input name="nextdo" id="nextdo" type="hidden"  value="" />
		 </td>
  </tr>
</table>
</div>
</td>
</tr>
  <?php
  if($db->num_rows($ret_inactive)>0) 
  {
  ?>
	<tr>
	<td colspan="6" align="left" valign="bottom">
		<div class="listingarea_div">
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
	<tr >
	<td width="3%" class="seperationtd"><img id="email_prodimgtag" src="images/plus.gif" border="0" onclick="handle_expansion(this,'prodtab')" title="Click"/></td>
	<td width="97%" align="left" class="seperationtd" style="cursor:pointer"  onclick="handle_expansion(document.getElementById('email_prodimgtag'),'prodtab')" >Products</td>
	</tr>
	<tr id="prod_tr" style="display:none;">
	<td align="left" colspan="2"><div id="prodtab_div" style="text-align:center"></div></td>
	</tr>
	</table>
	</div>
	</td>
	</tr>
      <?php
  }
      ?>  
</table>
	<?
	}
	function show_product_list($editid,$alert='')
	{
		global $db,$ecom_siteid;
		 if($editid>0)
		 {
			 // Get the list of assigned products
				$sql_cat = "SELECT a.product_id,a.product_name,a.product_webprice,a.product_hide,b.product_order 
									FROM 
										products a, general_settings_sites_mail_product_map b 
									WHERE 
										a.product_id = b.products_product_id 
										AND b.product_inactive_mail_id = $editid
										AND b.product_sites_site_id    = $ecom_siteid 
									ORDER BY 
										b.product_order ASC";
				$ret_cat = $db->query($sql_cat);
		}		
	?>
					<table width="100%" cellpadding="1" cellspacing="1" border="0">
					<?php 
						$table_headers = array('<img src="images/checkbox.gif" border="0" onclick="select_all(document.frmGeneralSettings,\'checkboxprods[]\')"/><img src="images/uncheckbox.gif" border="0" onclick="select_none(document.frmGeneralSettings,\'checkboxprods[]\')"/>','Slno.','Product Name','Product Order','Hidden?');
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
						<td colspan="5" class="helpmsgtd" align="left"><?=get_help_messages('EDIT_PROD_EMAIL_PROD_SUBHEAD')?>
						</td>
						</tr>
					<tr>
					<td align="right" colspan="5" class="tdcolorgray_buttons">
						<input name="Assignmore" type="button" class="red" id="Assignmore" value="Assign More" onclick="normal_assign_ProductAssign('<?php echo $editid?>');" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_PROD_EMAIL_ASSIGNEMAIL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>&nbsp;&nbsp;
						<?php
						if ($db->num_rows($ret_cat))
						{
						?>
						<div id="produnassign_div" class="unassign_div">
						<input name="catorder_unassign" type="button" class="red" id="catorder_unassign" value="Un assign" onclick="call_ajax_deleteall('prods','checkboxprods[]')" />
						<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_SUBCAT_PROD_UNASSIGNEMAIL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
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
							<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_EMAIL_PRODLIST_SAVEORDER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
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
								  No Products mapped .</td>
								</tr>
						<?php
						}
						?>	
				</table>	
	<?php	
	}
	
	
	
	function show_product_review($alert)
	{
	global $db,$ecom_siteid,$ecom_themeid ;
	
	$sql_prdtreview = "SELECT * FROM general_settings_site_product_review WHERE sites_site_id=".$ecom_siteid." LIMIT 1";
	//echo $sql_prdtreview;return;
	$ret_prdtreview = $db->query($sql_prdtreview);
	if($db->num_rows($ret_prdtreview)>0) 
	{
		$row_prdtreview = $db->fetch_array($ret_prdtreview);
	}
	?>		
	<table width="100%" border="0" cellpadding="2" cellspacing="2" >
		<tr>
		<td  class="tdcolorgray">	
		<div class="listingarea_div">
	
		<table border="0" cellspacing="2" cellpadding="2" width="100%" class="tdcolorgray">
		 <?php
			if ($alert)
			{
			?>
				<tr>
				  <td colspan="6" align="center" valign="middle" class="errormsg" id="mainerror_tr" ><?php echo $alert; ?></td>
				</tr>
			<?php
		 	}
		 ?> 
		<tr>
          <td colspan="6" align="left" valign="middle" class="seperationtd" ><a name="Inventory_Management">&nbsp;</a><b><?=get_help_messages('GENREVIEW_EMAIL_HEAD')?></b></td>
        </tr>       
		<tr >
		<td colspan="6" align="left"  >
		<table width="100%" border="0" align="right" cellpadding="2" cellspacing="2">
		<tr>
		 <td align="left" valign="middle" width="3%" >&nbsp;</td>
		 <td align="left" valign="middle" width="15%"  >Is Active</td>
		 <td width="82%" align="left" valign="middle"  ><input type="checkbox" name="is_active" id="is_active" value="1" <?php echo($row_prdtreview['is_active'] == 1)?"checked":"";?> />
		   <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('PREVIEW_EMAIL_ACTIVATE');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a>		   <!--<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('EDIT_GIFT_VOUCH_TYPE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>--></td>
        </tr>
		<tr>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td align="left" valign="middle"  >Begin Date</td>
		  <td align="left" valign="middle"  >
		<?php 
			if($row_prdtreview['review_begin_date'])
			{
				$date = $row_prdtreview['review_begin_date'];
				$date_arr = explode('-',$date);
				$date     = 	$date_arr[2]."-".$date_arr[1]."-".$date_arr[0];
			}
		?>
		    <input type="text" name="review_begin_date" id="review_begin_date" value="<?php echo $date; ?>" size="16" />
&nbsp;&nbsp;<a href="javascript:show_calendar('frmGeneralSettings.review_begin_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="images/show-calendar.gif" width="24" height="22" border="0"  align="absmiddle"/> </a><a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('GENREVIEW_EMAIL_BEGINDATE');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td align="left" valign="middle"  >Mail Interval </td>
		  <td align="left" valign="middle"  ><input type="text" name="review_mail_interval" id="review_mail_interval" value="<?php echo $row_prdtreview['review_mail_interval'] ?>" size="15"/> days
		    <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('GENREVIEW_EMAIL_INTERVAL');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td align="left" valign="middle"  >Registered Customer Only</td>
		  <td align="left" valign="middle"  ><input type="checkbox" name="review_registered_customers" id="review_registered_customers" value="1" <?php echo($row_prdtreview['review_registered_customers'] == 1)?"checked":"";?> />
		    <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('GENREVIEW_EMAIL_REGONLY');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td align="left" valign="middle"  >Minimum Order Total</td>
		  <td align="left" valign="middle"  ><input type="text" name="review_order_total" id="review_order_total" value="<?php echo $row_prdtreview['review_order_total'] ?>" size="15"/>
		    <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('GENREVIEW_EMAIL_MINTOTAL');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
		  </tr>
		  <tr>
			 <td align="left" valign="middle">&nbsp;</td>
		  <td align="left" colspan="2" class="seperationtd"><?php echo get_help_messages('GENREVIEW_EMAIL_GIFT_CRITERIAL_MSG');?></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td align="left" valign="middle"  >Send Gift Voucher </td>
		  <td align="left" valign="middle"  ><input type="checkbox" name="review_giftvoucher_sent" id="review_giftvoucher_sent" value="1" <?php echo($row_prdtreview['review_giftvoucher_sent'] == 1)?"checked":"";?> onclick="javascript: showVoucherData();" />
		    <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('GENREVIEW_EMAIL_GIFTREQ');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
		  </tr>
		<tr>
			<td colspan="6" align="left">
			<?php
				if($row_prdtreview['review_giftvoucher_sent'] == 1)
				{
			?>		<div id="voucherdata" style="display:block;">
			<?php
				}
				else
				{
			?>		<div id="voucherdata" style="display:none;">
			<?php
				}
			?>
			<table width="100%" cellpadding="2" cellspacing="2">
			
		<tr>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td align="left" valign="middle"  >Gift Voucher Active Days </td>
		  <td align="left" valign="middle"  ><input type="text" name="review_giftvoucher_activedays" id="review_giftvoucher_activedays" value="<?php echo $row_prdtreview['review_giftvoucher_activedays'] ?>" size="15"/> days
		    <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('GENREVIEW_EMAIL_GIFTDAYSACTIVE');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
		</tr>
		<tr>
		  <td width="5%" align="left" valign="middle" >&nbsp;</td>
		  <td width="16%" align="left" valign="middle"  >Gift Voucher Type</td>
		  <td width="79%" align="left" valign="middle"  ><select name="review_giftvoucher_disctype" id="review_giftvoucher_disctype" onchange="handle_codetype(this.value)">
                   <option value="VAL" <?php echo ($row_prdtreview['review_giftvoucher_disctype']=='VAL')?'selected="selected"':''?>>Value</option>
                   <option value="PER" <?php echo ($row_prdtreview['review_giftvoucher_disctype']=='PER')?'selected="selected"':''?>>%</option>
                 </select>
		    <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('GENREVIEW_EMAIL_GIFTTYPE');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
		  </tr>
		<tr>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td align="left" valign="middle"  >Discount Value </td>
		  <td align="left" valign="middle"  ><input type="text" name="review_giftvoucher_discount" id="review_giftvoucher_discount" value="<?php echo $row_prdtreview['review_giftvoucher_discount'] ?>" size="15"/>
		    <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('GENREVIEW_EMAIL_GIFTVAL');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
		</tr>
		<tr>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td align="left" valign="middle"  >Only on Approval </td>
		  <td align="left" valign="middle"  ><input type="checkbox" name="review_only_approval" id="review_only_approval" value="1" <?php echo($row_prdtreview['review_only_approval'] == 1)?"checked":"";?> />
		    <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('GENREVIEW_EMAIL_GIFTONAPPROVAL');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
		</tr>
		<tr>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td align="left" valign="middle"  >Reviews to be approved </td>
		  <td align="left" valign="middle"  ><select name="review_giftvoucher_sent_range" id="review_giftvoucher_sent_range">
		   <option value="F" <?php echo($row_prdtreview['review_giftvoucher_sent_range'] == 'F')?' selected="selected"':"";?>>Full</option>
		    <option value="H"<?php echo($row_prdtreview['review_giftvoucher_sent_range'] == 'H')?' selected="selected"':"";?>>&gt;= Half</option>
		    <option value="L"<?php echo($row_prdtreview['review_giftvoucher_sent_range'] == 'L')?' selected="selected"':"";?>>&lt; Half</option>
                              </select>
							  <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('GENREVIEW_EMAIL_GIFTONAPPROVALRANGE');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a>
							  </td>
		</tr>
			</table></div></td>
		</tr>
  </table>
  </td>
  </tr>
  </table>
  </div>
  </td>
  </tr>
			<tr>
			<td  class="tdcolorgray">	
		<div class="listingarea_div">
			  <table width="100%" border="0" cellpadding="2" cellspacing="2" >
<tr>
		  <td  class="tdcolorgray" colspan="3" align="right">
		  <input type="hidden" name="product_review_setting_id" id="product_review_setting_id" value="<?php echo $row_prdtreview['id']?>" />
		  <input name="Submit" type="button" class="red" value="Save Settings" onclick="save_settings('review')"/>
		 </td>
  </tr>
</table>
</div>
</td>
</tr>
</table>
	<?
	}
	function show_abandoned_cart_email($alert)
	{
		global $db,$ecom_siteid,$ecom_themeid ;
	
		$sql_abandon = "SELECT abandoned_cart_active,abandoned_cart_mail_interval 
							FROM 
								general_settings_sites_common 
							WHERE 
								sites_site_id=".$ecom_siteid." 
							LIMIT 
								1";
	//echo $sql_prdtreview;return;
	$ret_abandon = $db->query($sql_abandon);
	if($db->num_rows($ret_abandon)>0) 
	{
		$row_abandon = $db->fetch_array($ret_abandon);
	}
	?>		
	<table width="100%" border="0" cellpadding="2" cellspacing="2" >
		<tr>
		<td  class="tdcolorgray">	
		<div class="listingarea_div">
	
		<table border="0" cellspacing="2" cellpadding="2" width="100%" class="tdcolorgray">
		 <?php
			if ($alert)
			{
			?>
				<tr>
				  <td colspan="6" align="center" valign="middle" class="errormsg" id="mainerror_tr" ><?php echo $alert; ?></td>
				</tr>
			<?php
		 	}
		 ?> 
		<tr>
          <td colspan="6" align="left" valign="middle" class="seperationtd" ><a name="Abandoned Cart Email">&nbsp;</a><b><?=get_help_messages('GENABANDON_EMAIL_HEAD')?></b></td>
        </tr>       
		<tr >
		<td colspan="6" align="left"  >
		<table width="100%" border="0" align="right" cellpadding="2" cellspacing="2">
		<tr>
		 <td align="left" valign="middle" width="3%" >&nbsp;</td>
		 <td align="left" valign="middle" width="15%"  >Is Active</td>
		 <td width="82%" align="left" valign="middle"  ><input type="checkbox" name="is_active" id="is_active" value="1" <?php echo($row_abandon['abandoned_cart_active'] == 1)?"checked":"";?> />
		   <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('GENABANDON_EMAIL_ACTIVATE');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
        </tr>
		
		<tr>
		  <td align="left" valign="middle" >&nbsp;</td>
		  <td align="left" valign="middle"  >Mail Interval </td>
		  <td align="left" valign="middle"  ><input type="text" name="abandon_mail_interval" id="abandon_mail_interval" value="<?php echo $row_abandon['abandoned_cart_mail_interval'] ?>" size="2"/> days
		    <a href="#" onmouseover="ddrivetip('<?php echo get_help_messages('GENABANDON_EMAIL_INTERVAL');?>')" ;="" onmouseout="hideddrivetip()"><img src="images/helpicon.png" border="0" height="13" width="17" /></a></td>
		  </tr>
		
  </table>
  </td>
  </tr>
  </table>
  </div>
  </td>
  </tr>
			<tr>
			<td  class="tdcolorgray">	
		<div class="listingarea_div">
			  <table width="100%" border="0" cellpadding="2" cellspacing="2" >
<tr>
		  <td  class="tdcolorgray" colspan="3" align="right">
		  <input name="Submit" type="button" class="red" value="Save Settings" onclick="save_settings('abandoned')"/>
		 </td>
  </tr>
</table>
</div>
</td>
</tr>
</table>
	<?
	}
  ?>
