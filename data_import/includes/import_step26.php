<table width="100%" cellpadding="1" cellspacing="1" border="0">
<tr>
<td colspan="3" align="left" class="main_heading_text">Data Import</td>
</tr>
<tr>
<td colspan="3" align="left" class="heading_text"><?php echo $tree?></td>
</tr>
<tr>
<td colspan="3" align="left">&nbsp;</td>
</tr>
<tr>
<td align="center" colspan="3"><b>----- SUMMARY -----</b></td>
</tr>

<tr>
<td align="left" colspan="3" style="border-bottom:2px;border-bottom-style:solid"><b>Customer Related</b></td>
</tr>
<tr>
<td align="left" width="30%">Total Corporations Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $_REQUEST['corp_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Departments Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $_REQUEST['dept_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Newsletter Groups Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $_REQUEST['newsgroup_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Customers Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $_REQUEST['cust_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Newsletter Customers Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $_REQUEST['mailinglist_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Customers Mapped with Newsletter Groups</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $_REQUEST['newsgroupmap_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Company Types Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $_REQUEST['comptype_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Countries Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $_REQUEST['country_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total States Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $_REQUEST['state_cnt']?></td>
</tr>
<?php //############################################################# */ ?>
<tr>
<td align="left" colspan="3" style="border-bottom:2px;border-bottom-style:solid"><b>Keywords Related</b></td>
</tr>
<tr>
<td align="left" width="25%">Total Keywords Imported</td>
<td align="left" width="2%">:</td>
<td align="left"><?php echo $_REQUEST['keyword_cnt']?></td>
</tr>
<tr>
<td align="left">Total Saved Searches  Imported</td>
<td align="left">:</td>
<td align="left"><?php echo $_REQUEST['saved_cnt']?></td>
</tr>
<tr>
<td align="left">Total Saved Searches Mapped to keywords</td>
<td align="left">:</td>
<td align="left"><?php echo $_REQUEST['savedmap_cnt']?></td>
</tr>
<?php //############################################################# */ ?>
<tr>
<td align="left" colspan="3" style="border-bottom:2px;border-bottom-style:solid"><b>Product Category Related</b></td>
</tr>
<tr>
<td align="left" width="30%">Total Product Categories  Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['cat_cnt']?></td>
</tr>
<tr>
<td align="left">Total Product Categories mapped with keywords</td>
<td align="left">:</td>
<td align="left"><?php echo $_REQUEST['catmap_cnt']?></td>
</tr>
<?php //############################################################# */ ?>
<tr>
<td align="left" colspan="3" style="border-bottom:2px;border-bottom-style:solid"><b>Product Vendor/Size chart heading Related</b></td>
</tr>
<tr>
<td align="left" width="30%">Total Product Vendors Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['vendor_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Product Vendors Contacts Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['vendorcontact_cnt']?></td>
</tr>
<tr>
<td align="left">Total Product Size chart Headings Imported</td>
<td align="left">:</td>
<td align="left"><?php echo $_REQUEST['sizecharthead_cnt']?></td>
</tr>
<?php //############################################################# */ ?>
<tr>
<td align="left" colspan="3" style="border-bottom:2px;border-bottom-style:solid"><b>Custom Form Related</b></td>
</tr>
<tr>
<td align="left" width="30%">Total Custom Form Sections Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['section_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Form Elements Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['element_cnt']?></td>
</tr>
<?php //############################################################# */ ?>
<tr>
<td align="left" colspan="3" style="border-bottom:2px;border-bottom-style:solid"><b>Product Related</b></td>
</tr>
<tr>
<td align="left" width="30%">Total Products Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['prod_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Categories Mapped to Products</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['prodcatmap_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Vendors Mapped to Products</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['prodvendmap_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Bulk Discounts Mapped to Products</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['prodbulk_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total SizeChart Headings Mapped to Products</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['prodsizeheadmap_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Size Chart Value Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['prodsizevalues_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Product Reviews Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['review_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Product Tabs Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['tab_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Linked Products Mapped</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['tab_cnt']?></td>
</tr>
<?php //############################################################# */ ?>
<tr>
<td align="left" colspan="3" style="border-bottom:2px;border-bottom-style:solid"><b>Miscallaneous</b></td>
</tr>
<tr>
<td align="left" width="30%">Featured Products</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['featured_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Products mapped with Custom form sections</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['secprod_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Promotional Codes Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['promo_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Products mapped with promotional codes</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['promoprod_cnt']?></td>
</tr>
<?php //############################################################# */ ?>
<tr>
<td align="left" width="30%">Total Shop By Brands Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['shop_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Products mapped with Shop By Brands</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['shopprod_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Keywords imported for Shop By Brands</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['shopkw_cnt']?></td>
</tr>
<?php //############################################################# */ ?>
<tr>
<td align="left" colspan="3" style="border-bottom:2px;border-bottom-style:solid"><b>Static Page Related</b></td>
</tr>
<tr>
<td align="left" width="30%">Total Static Pages Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['stat_cnt']?></td>
</tr>
<tr>
<tr>
<td align="left" width="30%">Total Keywords imported for Static Pages</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['statkw_cnt']?></td>
</tr>
<?php //############################################################# */ ?>
<tr>
<td align="left" colspan="3" style="border-bottom:2px;border-bottom-style:solid"><b>Survey Related</b></td>
</tr>
<tr>
<td align="left" width="30%">Total Surveys Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['sur_cnt']?></td>
</tr>
<tr>
<tr>
<td align="left" width="30%">Total Survey Results Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['surres_cnt']?></td>
</tr>
<?php //############################################################# */ ?>
<tr>
<td align="left" colspan="3" style="border-bottom:2px;border-bottom-style:solid"><b>Console Users</b></td>
</tr>
<tr>
<td align="left" width="30%">Total Console Users Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['usr_cnt']?></td>
</tr>
<tr>
<?php //############################################################# */ ?>
<tr>
<td align="left" colspan="3" style="border-bottom:2px;border-bottom-style:solid"><b>Image Related</b></td>
</tr>
<tr>
<td align="left" width="30%">Total Image Directories Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['imgdir_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Images Imported</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['img_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Images Mapped with products</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['prodimgmap_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Images Mapped with categories</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['catimgmap_cnt']?></td>
</tr>
<tr>
<td align="left" width="30%">Total Images Mapped with Shop By Brands</td>
<td align="left" width="3%">:</td>
<td align="left"><?php echo $_REQUEST['shopimgmap_cnt']?></td>
</tr>
<?php //############################################################# */ ?>
<tr>
<td colspan="3" align="center">&nbsp;</td>
</tr>
<tr>
<td colspan="3" align="center"><b>----- Wizard Completed -----</b></td>
</tr>
</table>