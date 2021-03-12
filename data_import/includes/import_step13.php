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
<td align="left" width="30%">Total Products Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $import_prod['prod_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Categories Mapped to Products</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $import_prod['prodcatmap_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Vendors Mapped to Products</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $import_prod['prodvendmap_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Bulk Discounts Mapped to Products</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $import_prod['prodbulk_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total SizeChart Headings Mapped to Products</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $import_prod['prodsizeheadmap_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Size Chart Value Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $import_prod['prodsizevalues_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Product Reviews Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $import_prod['review_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Product Tabs Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $import_prod['tab_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Linked Products Mapped</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $import_prod['tab_cnt']?></td>
</tr>

<tr>
<td colspan="3" align="middle"><input type="submit" name="state_go" value="Click to Continue >>" class="button_style"/></td>
</tr>
</table>
<input type="hidden" name="next_step" value="step14" />

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
<input type="hidden" name="prod_cnt" value="<?php echo $import_prod['prod_cnt']?>" />
<input type="hidden" name="prodcatmap_cnt" value="<?php echo $import_prod['prodcatmap_cnt']?>" />
<input type="hidden" name="prodvendmap_cnt" value="<?php echo $import_prod['prodvendmap_cnt']?>" />
<input type="hidden" name="prodbulk_cnt" value="<?php echo $import_prod['prodbulk_cnt']?>" />
<input type="hidden" name="prodsizeheadmap_cnt" value="<?php echo $import_prod['prodsizeheadmap_cnt']?>" />
<input type="hidden" name="prodsizevalues_cnt" value="<?php echo $import_prod['prodsizevalues_cnt']?>" />
<input type="hidden" name="review_cnt" value="<?php echo $import_prod['review_cnt']?>" />
<input type="hidden" name="tab_cnt" value="<?php echo $import_prod['tab_cnt']?>" />
<input type="hidden" name="linked_cnt" value="<?php echo $import_prod['linked_cnt']?>" />
<?php echo $process_div?>
</form>