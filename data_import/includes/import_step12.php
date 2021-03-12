<form method="POST" action="index.php" name="form_step4">
<table width="100%" cellpadding="1" cellspacing="1" border="0">
<tr>
<td colspan="3" align="left" class="main_heading_text"><b>Data Import</b></td>
</tr>
<tr>
<td colspan="3" align="left" class="heading_text"><?php echo $tree?></td>
</tr>
<tr>
<td colspan="3" align="right" class="normal_text">Click <a href="index.php" class="link_text">here</a> to go back to main page </td>
</tr>
<tr>
<td colspan="3" align="left">This section allows to import the following details</td>
</tr>
<tr>
<td align="left" width="3%">1.</td>
<td align="left" colspan="2">Products</td>
</tr>
<tr>
<td align="left">2.</td>
<td align="left" colspan="2">Product - Category Mapping</td>
</tr>
<tr>
<td align="left">3.</td>
<td align="left" colspan="2">Product - Vendor Mapping</td>
</tr>
<tr>
<td align="left">3.</td>
<td align="left" colspan="2">Product - Bulk Discounts</td>
</tr>
<tr>
<td align="left">4.</td>
<td align="left" colspan="2">Product Size Chart Heading Map</td>
</tr>
<tr>
<td align="left">5.</td>
<td align="left" colspan="2">Product Size Chart Values</td>
</tr>

<tr>
<td align="left">6.</td>
<td align="left" colspan="2">Product Reviews</td>
</tr>
<tr>
<td colspan="3" align="middle"><input type="submit" name="state_go" value="Click to Import" onclick="<?php echo $onclick?>" class="button_style"/></td>
</tr>
</table>
<input type="hidden" name="next_step" value="step13" />

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
<?php echo $process_div?>
</form>