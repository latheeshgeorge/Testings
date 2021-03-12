<form method="POST" action="index.php" name="form_step4">
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
<td colspan="3" align="left">This section allows to import the following details</td>
</tr>
<tr>
<td align="left" width="3%">1.</td>
<td align="left" colspan="2">Entire Keywords List</td>
</tr>
<tr>
<td align="left">2.</td>
<td align="left" colspan="2">Saved Searches</td>
</tr>
<tr>
<td align="left">3.</td>
<td align="left" colspan="2">Saved Search Keyword Mappings</td>
</tr>
<tr>
<td colspan="3" align="middle"><input type="submit" name="state_go" value="Click to Import" onclick="<?php echo $onclick?>" class="button_style"/></td>
</tr>
</table>
<input type="hidden" name="next_step" value="step5" />

<input type="hidden" name="corp_cnt" value="<?php echo $_REQUEST['corp_cnt']?>" />
<input type="hidden" name="dept_cnt" value="<?php echo $_REQUEST['dept_cnt']?>" />
<input type="hidden" name="newsgroup_cnt" value="<?php echo $_REQUEST['newsgroup_cnt']?>" />
<input type="hidden" name="comptype_cnt" value="<?php echo $_REQUEST['comptype_cnt']?>" />
<input type="hidden" name="country_cnt" value="<?php echo $_REQUEST['country_cnt']?>" />
<input type="hidden" name="state_cnt" value="<?php echo $_REQUEST['state_cnt']?>" />
<input type="hidden" name="cust_cnt" value="<?php echo $_REQUEST['cust_cnt']?>" />
<input type="hidden" name="newsgroupmap_cnt" value="<?php echo $_REQUEST['newsgroupmap_cnt']?>" />
<input type="hidden" name="mailinglist_cnt" value="<?php echo $_REQUEST['mailinglist_cnt']?>" />
<?php echo $process_div?>
</form>