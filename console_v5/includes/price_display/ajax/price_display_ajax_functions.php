<?php
   function show_price_maininfo($alert)
	{
		global $db,$ecom_siteid,$ecom_themeid,$ecom_allpricewithtax;
		
		// Check whether special tax is applicable for current website
		if($ecom_allpricewithtax==1)
		{
			// Check whether price display style is price only or not
			$sql			= "SELECT * FROM general_settings_site_pricedisplay WHERE sites_site_id=".$ecom_siteid;
			$res			= $db->query($sql);
			$row 			= $db->fetch_array($res);
			if($row['price_displaytype']!='show_price_only')
			{
					$update_sql = "UPDATE general_settings_site_pricedisplay 
									SET 
										price_displaytype='show_price_only' 
									WHERE 
										sites_site_id = $ecom_siteid 
									LIMIT 
										1";
					$db->query($update_sql);
					clear_all_cache();// Clearing all cache
					// Creating the  price display settings cache files to be included in client area to save time to access the price displaysettings each time from db
					create_PriceDisplaySettings_CacheFile();
			}
			
		}
		$sql			= "SELECT * FROM general_settings_site_pricedisplay WHERE sites_site_id=".$ecom_siteid;
		$res			= $db->query($sql);
		$row 			= $db->fetch_array($res);
		?>
	   <div class="editarea_div">

		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<?php
		if($alert)
			{			
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		  <?php
		  }
		  ?>
		<tr>
		  <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Product Price Display Type:</b></td>
		</tr>
		  <tr>
			  <td width="20%"  align="left" valign="middle" class="tdcolorgray">&nbsp;		  </td>
			  <td align="left" valign="middle" class="tdcolorgray" ><input class="input" type="radio" name="price_displaytype"  value="show_price_only" <? if($row['price_displaytype']=='show_price_only') echo "checked";?> />
			  Show Price only <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_PRICEONLY')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			 
			  <td align="left" valign="middle" class="tdcolorgray" >
			  <?php 
			  if($ecom_allpricewithtax!=1)
			  {
			  ?>
			  <input class="input" type="radio" name="price_displaytype" value="show_price_inc_tax" <? if($row['price_displaytype']=='show_price_inc_tax') echo "checked";?>  />
			Show Price inc Tax<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_PRICE_INCTAX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<?php
			}
			?>
			</td>
			  <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		</tr>
		 <tr>
			 <td width="20%"  align="left" valign="middle" class="tdcolorgray">&nbsp;		  </td>
			  <td align="left" valign="middle" class="tdcolorgray" >
			   <?php 
			  if($ecom_allpricewithtax!=1)
			  {
			  ?>
			  <input class="input" type="radio" name="price_displaytype"  value="show_price_plus_tax" <? if($row['price_displaytype']=='show_price_plus_tax') echo "checked";?> />
			Show Price + Tax<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_PRICETAX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<?php
			}
			?>
			</td>
			  <td align="left" valign="middle" class="tdcolorgray" >
			   <?php 
			  if($ecom_allpricewithtax!=1)
			  {
			  ?>
			  <input class="input" type="radio" name="price_displaytype" value="show_both" <? if($row['price_displaytype']=='show_both') echo "checked";?>  />
			Show Both <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_PRICE_SHOWBOTH')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>
			<?php
			}
			?>
			</td>
			  <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		</tr>
		<? ?>
		<?php /*?><tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Shelves:</b></td>
        </tr>
		 <tr>
		  <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >(Center)One Product in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_ONEROW_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_middleshelf_1_reqbreak" value="1" <? if($row['price_middleshelf_1_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_middleshelf_1_reqbreak" value="0"  <? if($row['price_middleshelf_1_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		<tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		  <td width="28%" align="left" valign="middle" class="tdcolorgray" >(Center)Three Products in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_THREEROW_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_middleshelf_3_reqbreak" value="1" <? if($row['price_middleshelf_3_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_middleshelf_3_reqbreak" value="0" <? if($row['price_middleshelf_3_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		 <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		  <td width="28%" align="left" valign="middle" class="tdcolorgray" >Component <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_COMPONENT_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_compshelf_reqbreak" value="1" <? if($row['price_compshelf_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_compshelf_reqbreak" value="0"  <? if($row['price_compshelf_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		 
		 <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Search Results:</b></td>
        </tr>
		 <tr>
		  <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >One Product in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_ONEROW_SEARCH_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_searchresult_1_reqbreak" value="1" <? if($row['price_searchresult_1_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_searchresult_1_reqbreak" value="0" <? if($row['price_searchresult_1_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		 <tr>
		  <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Three Products in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_THREEROW_SEARCH_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_searchresult_3_reqbreak" value="1" <? if($row['price_searchresult_3_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_searchresult_3_reqbreak" value="0" <? if($row['price_searchresult_3_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		 
		 <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Category Details:</b></td>
        </tr>
		 <tr>
		 <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >One Product in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_ONEROW_CATEGORY_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_categorydetails_1_reqbreak" value="1" <? if($row['price_categorydetails_1_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_categorydetails_1_reqbreak" value="0" <? if($row['price_categorydetails_1_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		 <tr>
		 <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Three Products in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_THREEROW_CATEGORY_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_categorydetails_3_reqbreak" value="1" <? if($row['price_categorydetails_3_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_categorydetails_3_reqbreak" value="0" <? if($row['price_categorydetails_3_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		 
		 <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Combo Deals:</b></td>
        </tr>
		<tr>
		  <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >One Product in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_ONEROW_COMBO_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_combodeals_1_reqbreak" value="1" <? if($row['price_combodeals_1_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_combodeals_1_reqbreak" value="0" <? if($row['price_combodeals_1_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		<tr>
		  <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Three Products in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP__THREEROW_COMBO_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_combodeals_3_reqbreak" value="1" <? if($row['price_combodeals_3_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_combodeals_3_reqbreak" value="0" <? if($row['price_combodeals_3_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		 
		 <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Best Sellers:</b></td>
        </tr>
		<tr>
		  <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >One Product in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_ONEROW_BESTSELLER_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_best_1_reqbreak" value="1"  <? if($row['price_best_1_reqbreak']==1) echo "checked";?> /> Show price in different lines   
		  <input class="input" type="radio" name="price_best_1_reqbreak" value="0" <? if($row['price_best_1_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		<tr>
		  <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Three Products in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_THREEROW_BESTSELLER_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_best_3_reqbreak" value="1" <? if($row['price_best_3_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_best_3_reqbreak" value="0" <? if($row['price_best_3_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		 
		  <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Linked Products:</b></td>
        </tr>
		<tr>
		  <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >One Product in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_ONEROW_LINKED_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_linkedprod_1_reqbreak" value="1" <? if($row['price_linkedprod_1_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_linkedprod_1_reqbreak" value="0" <? if($row['price_linkedprod_1_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		<tr>
		  <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Three Products in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_THREEROW_LINKED_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_linkedprod_3_reqbreak" value="1" <? if($row['price_linkedprod_3_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_linkedprod_3_reqbreak" value="0" <? if($row['price_linkedprod_3_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		 <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Shops:</b></td>
        </tr>
		<tr>
		  <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >One Product in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_ONEROW_SHOP_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_shopbrand_1_reqbreak" value="1" <? if($row['price_shopbrand_1_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_shopbrand_1_reqbreak" value="0" <? if($row['price_shopbrand_1_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		<tr>
		  <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Three Products in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_THREEROW_SHOP_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_shopbrand_3_reqbreak" value="1" <? if($row['price_shopbrand_3_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_shopbrand_3_reqbreak" value="0" <? if($row['price_shopbrand_3_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		  <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><b>Other Product Listing:</b></td>
        </tr>
		 <tr>
		  <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Product Details <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_PRODDETAILS_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_proddetails_reqbreak" value="1" <? if($row['price_proddetails_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_proddetails_reqbreak" value="0" <? if($row['price_proddetails_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		 <tr>
		  <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Other(If any) One Product in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_ONEROW_ANY_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_other_1_reqbreak" value="1" <? if($row['price_other_1_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_other_1_reqbreak" value="0" <? if($row['price_other_1_reqbreak']==0) echo "checked";?>  /> Show price in a single line		  </td>
        </tr>
		 <tr>
		  <td width="20%" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray" >Other(If any) 
Three Products in a row <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_THREEROW_ANY_PRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
          <td colspan="2" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="radio" name="price_other_3_reqbreak" value="1" <? if($row['price_other_3_reqbreak']==1) echo "checked";?>  /> Show price in different lines   
		  <input class="input" type="radio" name="price_other_3_reqbreak" value="0" <? if($row['price_other_3_reqbreak']==0) echo "checked";?> /> Show price in a single line		  </td>
        </tr><?php */?>
		<? ?>
		  
		<tr>
		<td colspan="4" class="tdcolorgray" >&nbsp;</td>
		</tr>
		</table>
		</div>
		<div class="editarea_div">

		<table border="0" cellspacing="2" cellpadding="2" width="100%">
		<tr>
		<td colspan="4" class="tdcolorgray" align="right" ><input name="Submit" type="submit" class="red" value="Save Changes" /></td>
		</tr>
		</table>
		</div>
		<?
	}	
	function show_captions_list($alert)
	{
		global $db,$ecom_siteid,$ecom_themeid ;
		$sql			= "SELECT * FROM general_settings_site_pricedisplay WHERE sites_site_id=".$ecom_siteid;
		$res			= $db->query($sql);
		$row 			= $db->fetch_array($res);
		?>
		<div class="editarea_div">

	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<?php
		if($alert)
			{			
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		  <?php
		  }
		  ?>
	
		  
		   <tr>
          <td colspan="4" align="left" valign="middle" class="sorttd" ><?=get_help_messages('LIST_PRICE_CAPTIONS_SUBMESS')?></td>
        </tr>
		   <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Prefix for Normal Price </td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="price_normalprefix" value="<?php print stripslashes($row['price_normalprefix']);?>"  />	<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_PREFIX_NORMAL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>	  </td>
        
          <td width="27%" align="left" valign="middle" class="tdcolorgray" >Suffix for Normal Price</td>
          <td width="25%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="price_normalsuffix" value="<?php print stripslashes($row['price_normalsuffix']);?>"  /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_SUFFIX_NORMAL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		  </td>
        </tr>
		 <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Prefix for 'From' Price</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="price_fromprefix" value="<?php print stripslashes($row['price_fromprefix']);?>"  />		 <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_PREFIX_FROM')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> </td>
          <td width="27%" align="left" valign="middle" class="tdcolorgray" >Suffix for 'From' Price</td>
          <td width="25%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="price_fromsuffix" value="<?php print stripslashes($row['price_fromsuffix']);?>"  /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_SUFFIX_FROM')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		  </td>
        </tr>
		 <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Prefix for 'Special Offer' Price</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="price_specialofferprefix" value="<?php print stripslashes($row['price_specialofferprefix']);?>"  />	<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_PREFIX_SPECIALOFFER')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>	  </td>
          <td width="27%" align="left" valign="middle" class="tdcolorgray" >Suffix for 'Special Offer' Price</td>
          <td width="25%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="price_specialoffersuffix" value="<?php print stripslashes($row['price_specialoffersuffix']);?>"  /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_SUFFIX_OFFERPRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		  </td>
        </tr>
		
        <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Prefix for 'You Save' Price</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="price_yousaveprefix"  value="<?php print stripslashes($row['price_yousaveprefix']);?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_PREFIX_YOUSAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		  </td>
          <td width="27%" align="left" valign="middle" class="tdcolorgray" >Suffix for 'You Save' Price</td>
          <td width="25%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="price_yousavesuffix" value="<?php print stripslashes($row['price_yousavesuffix']);?>"  /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_SUFFIX_YOUSAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		  </td>
        </tr>
		<!--  <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Prefix for 'Cost Plus' Price</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="price_costplusprefix" value="<?php //print stripslashes($row['price_costplusprefix']);?>"  />		  </td>
          <td width="27%" align="left" valign="middle" class="tdcolorgray" >Suffix for 'Cost Plus' Price</td>
          <td width="25%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="price_costplussuffix" value="<?php //print stripslashes($row['price_costplussuffix']);?>" />		  </td>
        </tr> -->
		  <tr>
		    <td align="left" valign="middle" class="tdcolorgray" >Caption for no Price</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="price_noprice" value="<?php print stripslashes($row['price_noprice']);?>"  /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_CAPTION_NOPRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		    <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
    </tr>
		  <tr>
            <td align="left" valign="middle" class="tdcolorgray" > Prefix for 'Discount' </td>
		    <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="price_discountprefix"  value="<?php print stripslashes($row['price_discountprefix']);?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_PREFIX_DISCOUNT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>            </td>
		    <td align="left" valign="middle" class="tdcolorgray" >Suffix for 'Discount'</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="price_discountsuffix" value="<?php print stripslashes($row['price_discountsuffix']);?>"> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_SUFFIX_DISCOUNT')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>            </td>
    </tr>
		  <tr>
          <td width="20%" align="left" valign="middle" class="tdcolorgray" >Prefix for 'Available Date'</td>
          <td width="28%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="price_availabledateprefix" value="<?php print stripslashes($row['price_availabledateprefix']);?>"  /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_PREFIX_AVAILABLE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		  </td>
          <td width="27%" align="left" valign="middle" class="tdcolorgray" >Suffix for 'Available Date'</td>
          <td width="25%" align="left" valign="middle" class="tdcolorgray">
		  <input class="input" type="text" name="price_availabledatesuffix" value="<?php print stripslashes($row['price_availabledatesuffix']);?>"  /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_SUFFIX_AVAILABLE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>		  </td>
        </tr>
		  <tr>
		    <td align="left" valign="middle" class="tdcolorgray" >Prefix for 'Add Variable Price'</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="price_variablepriceadd_prefix" value="<?php print stripslashes($row['price_variablepriceadd_prefix']);?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_PREFIX_ADDVARIABLE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		    <td align="left" valign="middle" class="tdcolorgray" >Suffix for 'Add Variable Price'</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="price_variablepriceadd_suffix" value="<?php print stripslashes($row['price_variablepriceadd_suffix']);?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_SUFFIX_ADDVARPRICE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
		  <tr>
		    <td align="left" valign="middle" class="tdcolorgray" >Prefix for 'Less Variable Price'</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="price_variablepriceless_prefix" value="<?php print stripslashes($row['price_variablepriceless_prefix']);?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_PREFIX_LESSVARIABLE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		    <td align="left" valign="middle" class="tdcolorgray" >Suffix for 'Less Variable Price'</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="price_variablepriceless_suffix" value="<?php print stripslashes($row['price_variablepriceless_suffix']);?>" /> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_SUFFIX_LESSVAR')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
		  <tr>
		    <td align="left" valign="middle" class="tdcolorgray" >Prefix for 'Full Variable Price' </td>
		    <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="price_variablepricefull_prefix" value="<?php print stripslashes($row['price_variablepricefull_prefix']);?>"> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_PREFIX_FULLVARIABLE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		    <td align="left" valign="middle" class="tdcolorgray" >Suffix for 'Full Variable Price' </td>
		    <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="price_variablepricefull_suffix" value="<?php print stripslashes($row['price_variablepricefull_suffix']);?>"> <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_SUFFIX_FULLVARIABLE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
		  <tr>
		    <td align="left" valign="middle" class="tdcolorgray" >+ Tax </td>
		    <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="price_tax_plus" value="<?php print stripslashes($row['price_tax_plus']);?>" />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PLUS_TAX')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		    <td align="left" valign="middle" class="tdcolorgray" >Including Tax </td>
		    <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="price_tax_inc" value="<?php print stripslashes($row['price_tax_inc']);?>" />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_TAX_INCLUDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
      </tr>
		  <tr>
		    <td align="left" valign="middle" class="tdcolorgray" >Excluding Tax </td>
		    <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="text" name="price_tax_exc" value="<?php print stripslashes($row['price_tax_exc']);?>" />
              <a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_TAX_EXCLUDE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		    <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
      </tr>
      </table>
      </div>
      <div class="editarea_div">

		<table border="0" cellspacing="2" cellpadding="2" width="100%">
      
		<tr>
		<td colspan="4" class="tdcolorgray" align="right" ><input name="Submit" type="button" class="red" value="Save Changes" onclick="save_settings('prod_details')"/></td>
		</tr>
		</table>
		</div>
		<?
	}
	function show_others_list($alert)
	{
		global $db,$ecom_siteid,$ecom_themeid ;
		$sql			= "SELECT * FROM general_settings_site_pricedisplay WHERE sites_site_id=".$ecom_siteid;
		$res			= $db->query($sql);
		$row 			= $db->fetch_array($res);
		?>
		<div class="editarea_div">

		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<?php
		if($alert)
			{			
		?>
        <tr>
          <td colspan="4" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
          </tr>
		  <?php
		  }
		  ?>
		  <tr>
		    <td colspan="4" align="left" valign="middle" class="sorttd" ><strong>Variable Price Display in Product details page: </strong><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_VARIABLE_PRICE_ADDITIONAL')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
    </tr>
		  <tr>
		    <td align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
		    <td align="left" valign="middle" class="tdcolorgray"><input name="price_variableprice_display" id="price_variableprice_display" type="radio" value="1" <? if($row['price_variableprice_display']=='1') echo "checked";?>/>
	        Show additional price of variables</td>
		    <td align="left" valign="middle" class="tdcolorgray" ><input name="price_variableprice_display" id="radio" type="radio" value="2" <? if($row['price_variableprice_display']=='2') echo "checked";?>/>
	        Hide additional price of variables</td>
		    <td align="left" valign="middle" class="tdcolorgray">&nbsp;</td>
    </tr>
		<tr>
		  	<td colspan="4" align="left" valign="middle" class="sorttd" ><strong>Additional Settings </strong></td>
		</tr>
		   <tr>
			  <td width="20%" align="left" valign="middle" class="tdcolorgray" >Show 'You Save' Price</td>
			  <td width="28%" align="left" valign="middle" class="tdcolorgray">
			  <input type="checkbox" name="price_show_yousave" value="1" <? if($row['price_show_yousave']=='1') echo "checked";?>>	<a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_YUOSAVE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a>	  </td>
			
			  <td width="27%" align="left" valign="middle" class="tdcolorgray" >Strike out base price for special offers </td>
			  <td width="25%" align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="strike_baseprice" value="1" <? if($row['strike_baseprice']=='1') echo "checked";?> /><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_STRIKE_BASE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		   <tr>
			 <td align="left" valign="middle" class="tdcolorgray" >Apply Discount to Variable</td>
			 <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="price_applydiscount_tovariable" value="1" <? if($row['price_applydiscount_tovariable']=='1') echo "checked";?>  /><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_APPLY_DISC_VARIABLE')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
			 <td align="left" valign="middle" class="tdcolorgray" >Show Discount with price display </td>
			 <td align="left" valign="middle" class="tdcolorgray"><input class="input" type="checkbox" name="price_display_discount_with_price" value="1" <? if($row['price_display_discount_with_price']=='1') echo "checked";?>><a href="#" onmouseover ="ddrivetip('<?=get_help_messages('LIST_PRICEDISP_SHOW_DISC')?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a></td>
		</tr>
		 <tr>
          <td colspan="4" align="left" valign="middle" class="tdcolorgray" >&nbsp;</td>
        </tr>
        </table>
        </div>
        <div class="editarea_div">

		<table border="0" cellspacing="2" cellpadding="2" width="100%">
		<tr>
		<td colspan="4" class="tdcolorgray" align="right" ><input name="Submit" type="button" class="red" value="Save Changes" onclick="save_settings('others')"/></td>
		</tr>
		</table>
		</div>
		<?
	}
  ?> 
