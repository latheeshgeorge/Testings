<form method="POST" action="index.php" name="form_step2">
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
<td align="left" width="2%">1.</td>
<td align="left" colspan="2">Customer Corporation</td>
</tr>
<tr>
<td align="left">2.</td>
<td align="left" colspan="2">Customer Corporation Department</td>
</tr>
<tr>
<td align="left">3.</td>
<td align="left" colspan="2">Customer Newsletter Groups</td>
</tr>
<tr>
<td align="left">4.</td>
<td align="left" colspan="2">Customers</td>
</tr>
<tr>
<td align="left">5.</td>
<td align="left" colspan="2">Customer - Newsletter Mapping</td>
</tr>
<tr>
<td colspan="3" align="middle"><input type="submit" name="state_go" value="Click to Import Customer Details" onclick="<?php echo $onclick?>" class="button_style"/></td>
</tr>
</table>
<input type="hidden" name="next_step" value="step3" />
<?php echo $process_div?>
</form>