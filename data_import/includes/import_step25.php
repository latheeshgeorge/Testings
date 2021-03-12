<form method="POST" action="index.php" name="form_step5">
<table width="100%" cellpadding="1" cellspacing="1" border="0">
<tr>
<td colspan="3" align="left" class="main_heading_text">Data Import</td>
</tr>
<tr>
<td colspan="3" align="left" class="heading_text"><?php echo $tree?></td>
</tr>
<tr>
<td colspan="3" align="right" class="normal_text">Click <a href="index.php" class="link_text">here</a> to go back to main page </td>
</tr>
<tr>
<td align="left" width="30%">Total Image Directories Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $import_image['imgdir_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Images Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $import_image['img_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Images Mapped with products</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $import_image['prodmap_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Images Mapped with categories</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $import_image['catmap_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Images Mapped with Shop By Brands</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $import_image['shopmap_cnt']?></td>
</tr>


<tr>
<td colspan="3" align="middle"><input type="submit" name="state_go" value="Click to Finish Wizard >>" onclick="<?php echo $onclick?>" class="button_style"/></td>
</tr>
</table>
<input type="hidden" name="next_step" value="step26" />

<input type="hidden" name="corp_cnt" value="<?php echo $_REQUEST['corp_cnt']?>" />
<input type="hidden" name="dept_cnt" value="<?php echo $_REQUEST['dept_cnt']?>" />
<input type="hidden" name="newsgroup_cnt" value="<?php echo $_REQUEST['newsgroup_cnt']?>" />
<input type="hidden" name="comptype_cnt" value="<?php echo $_REQUEST['comptype_cnt']?>" />
<input type="hidden" name="country_cnt" value="<?php echo $_REQUEST['country_cnt']?>" />
<input type="hidden" name="state_cnt" value="<?php echo $_REQUEST['state_cnt']?>" />
<input type="hidden" name="cust_cnt" value="<?php echo $_REQUEST['cust_cnt']?>" />
<input type="hidden" name="newsgroupmap_cnt" value="<?php echo $_REQUEST['newsgroupmap_cnt']?>" />
<input type="hidden" name="mailinglist_cnt" value="<?php echo $_REQUEST['mailinglist_cnt']?>" />
<input type="hidden" name="keyword_cnt" value="<?php echo $_REQUEST['keyword_cnt']?>" />
<input type="hidden" name="saved_cnt" value="<?php echo $_REQUEST['saved_cnt']?>" />
<input type="hidden" name="savedmap_cnt" value="<?php echo $_REQUEST['savedmap_cnt']?>" />
<input type="hidden" name="cat_cnt" value="<?php echo $_REQUEST['cat_cnt']?>" />
<input type="hidden" name="catmap_cnt" value="<?php echo $_REQUEST['catmap_cnt']?>" />
<input type="hidden" name="vendor_cnt" value="<?php echo $_REQUEST['vendor_cnt']?>" />
<input type="hidden" name="vendorcontact_cnt" value="<?php echo $_REQUEST['vendorcontact_cnt']?>" />
<input type="hidden" name="sizecharthead_cnt" value="<?php echo $_REQUEST['sizecharthead_cnt']?>" />
<input type="hidden" name="section_cnt" value="<?php echo $_REQUEST['section_cnt']?>" />
<input type="hidden" name="element_cnt" value="<?php echo $_REQUEST['element_cnt']?>" />
<input type="hidden" name="prod_cnt" value="<?php echo $_REQUEST['prod_cnt']?>" />
<input type="hidden" name="prodcatmap_cnt" value="<?php echo $_REQUEST['prodcatmap_cnt']?>" />
<input type="hidden" name="prodvendmap_cnt" value="<?php echo $_REQUEST['prodvendmap_cnt']?>" />
<input type="hidden" name="prodbulk_cnt" value="<?php echo $_REQUEST['prodbulk_cnt']?>" />
<input type="hidden" name="prodsizeheadmap_cnt" value="<?php echo $_REQUEST['prodsizeheadmap_cnt']?>" />
<input type="hidden" name="prodsizevalues_cnt" value="<?php echo $_REQUEST['prodsizevalues_cnt']?>" />
<input type="hidden" name="review_cnt" value="<?php echo $_REQUEST['review_cnt']?>" />
<input type="hidden" name="tab_cnt" value="<?php echo $_REQUEST['tab_cnt']?>" />
<input type="hidden" name="linked_cnt" value="<?php echo $_REQUEST['linked_cnt']?>" />
<input type="hidden" name="featured_cnt" value="<?php echo $_REQUEST['featured_cnt']?>" />
<input type="hidden" name="secprod_cnt" value="<?php echo $_REQUEST['secprod_cnt']?>" />
<input type="hidden" name="promo_cnt" value="<?php echo $_REQUEST['promo_cnt']?>" />
<input type="hidden" name="promoprod_cnt" value="<?php echo $_REQUEST['promoprod_cnt']?>" />
<input type="hidden" name="shop_cnt" value="<?php echo $_REQUEST['shop_cnt']?>" />
<input type="hidden" name="shopprod_cnt" value="<?php echo $_REQUEST['shopprod_cnt']?>" />
<input type="hidden" name="shopkw_cnt" value="<?php echo $_REQUEST['shopkw_cnt']?>" />
<input type="hidden" name="stat_cnt" value="<?php echo $_REQUEST['stat_cnt']?>" />
<input type="hidden" name="statkw_cnt" value="<?php echo $_REQUEST['statkw_cnt']?>" />
<input type="hidden" name="sur_cnt" value="<?php echo $_REQUEST['sur_cnt']?>" />
<input type="hidden" name="surres_cnt" value="<?php echo $_REQUEST['surres_cnt']?>" />
<input type="hidden" name="usr_cnt" value="<?php echo $_REQUEST['usr_cnt']?>" />
<input type="hidden" name="sav_cnt" value="<?php echo $_REQUEST['sav_cnt']?>" />
<input type="hidden" name="savkw_cnt" value="<?php echo $_REQUEST['savkw_cnt']?>" />
<input type="hidden" name="imgdir_cnt" value="<?php echo $import_image['imgdir_cnt']?>" />
<input type="hidden" name="img_cnt" value="<?php echo $import_image['img_cnt']?>" />
<input type="hidden" name="prodimgmap_cnt" value="<?php echo $import_image['prodmap_cnt']?>" />
<input type="hidden" name="catimgmap_cnt" value="<?php echo $import_image['catmap_cnt']?>" />
<input type="hidden" name="shopimgmap_cnt" value="<?php echo $import_image['shopmap_cnt']?>" />
<?php echo $process_div?>
</form>