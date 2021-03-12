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
<td align="left" width="25%">Total Keywords Imported</td>
<td align="left" width="2%">:</td>
<td align="left"><?php echo $import_saved_search['keyword_cnt']?></td>
</tr>
<tr>
<td align="left">Total Saved Searches  Imported</td>
<td align="left">:</td>
<td align="left"><?php echo $import_saved_search['saved_cnt']?></td>
</tr>
<tr>
<td align="left">Total Saved Searches Mapped to keywords</td>
<td align="left">:</td>
<td align="left"><?php echo $import_saved_search['savedmap_cnt']?></td>
</tr>
<tr>
<td colspan="3" align="middle"><input type="submit" name="state_go" value="Click to Continue >>" onclick="<?php echo $onclick?>" class="button_style"/></td>
</tr>
</table>
<input type="hidden" name="next_step" value="step6" />

<input type="hidden" name="corp_cnt" value="<?php echo $_REQUEST['corp_cnt']?>" />
<input type="hidden" name="dept_cnt" value="<?php echo $_REQUEST['dept_cnt']?>" />
<input type="hidden" name="newsgroup_cnt" value="<?php echo $_REQUEST['newsgroup_cnt']?>" />
<input type="hidden" name="comptype_cnt" value="<?php echo $_REQUEST['comptype_cnt']?>" />
<input type="hidden" name="country_cnt" value="<?php echo $_REQUEST['country_cnt']?>" />
<input type="hidden" name="state_cnt" value="<?php echo $_REQUEST['state_cnt']?>" />
<input type="hidden" name="cust_cnt" value="<?php echo $_REQUEST['cust_cnt']?>" />
<input type="hidden" name="newsgroupmap_cnt" value="<?php echo $_REQUEST['newsgroupmap_cnt']?>" />
<input type="hidden" name="mailinglist_cnt" value="<?php echo $_REQUEST['mailinglist_cnt']?>" />
<input type="hidden" name="keyword_cnt" value="<?php echo $import_saved_search['keyword_cnt']?>" />
<input type="hidden" name="saved_cnt" value="<?php echo $import_saved_search['saved_cnt']?>" />
<input type="hidden" name="savedmap_cnt" value="<?php echo $import_saved_search['savedmap_cnt']?>" />
<?php echo $process_div?>
</form>