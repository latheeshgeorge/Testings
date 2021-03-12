<form method="POST" action="index.php" name="form_step3">
<table width="100%" cellpadding="1" cellspacing="1" border="0">
<tr>
<td colspan="3" align="left" class="main_heading_textx">Data Import</td>
</tr>
<tr>
<td colspan="3" align="left" class="heading_text"><?php echo $tree?></td>
</tr>
<tr>
<td colspan="3" align="right" class="normal_text">Click <a href="index.php" class="link_text">here</a> to go back to main page </td>
</tr>
<tr>
<td align="left" width="30%">Total Corporations Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $import_cust_details['corp_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Departments Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $import_cust_details['dept_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Newsletter Groups Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $import_cust_details['newsgroup_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Customers Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $import_cust_details['cust_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Newsletter Customers Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $import_cust_details['mailinglist_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Customers Mapped with Newsletter Groups</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $import_cust_details['newsgroupmap_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Company Types Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $import_cust_details['comptype_cnt']?></td>
</tr>

<tr>
<td align="left" width="30%">Total Countries Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $import_cust_details['country_cnt']?></td>
</tr>

<?php /*?><tr>
<td align="left" width="30%">Total States Imported</td>
<td align="left" width="1%">:</td>
<td align="left"><?php echo $import_cust_details['state_cnt']?></td>
</tr><?php */?>
<tr>
<td colspan="3" align="middle"><input type="submit" name="state_go" value="Go to next Step >>" onclick="<?php echo $onclick?>" class="button_style"/></td>
</tr>
</table>
<input type="hidden" name="next_step" value="step4" />
<input type="hidden" name="corp_cnt" value="<?php echo $import_cust_details['corp_cnt']?>" />
<input type="hidden" name="dept_cnt" value="<?php echo $import_cust_details['dept_cnt']?>" />
<input type="hidden" name="newsgroup_cnt" value="<?php echo $import_cust_details['newsgroup_cnt']?>" />
<input type="hidden" name="comptype_cnt" value="<?php echo $import_cust_details['comptype_cnt']?>" />
<input type="hidden" name="country_cnt" value="<?php echo $import_cust_details['country_cnt']?>" />
<input type="hidden" name="state_cnt" value="<?php echo $import_cust_details['state_cnt']?>" />
<input type="hidden" name="cust_cnt" value="<?php echo $import_cust_details['cust_cnt']?>" />
<input type="hidden" name="newsgroupmap_cnt" value="<?php echo $import_cust_details['newsgroupmap_cnt']?>" />
<input type="hidden" name="mailinglist_cnt" value="<?php echo $import_cust_details['mailinglist_cnt']?>" />
<?php echo $process_div?>
</form>