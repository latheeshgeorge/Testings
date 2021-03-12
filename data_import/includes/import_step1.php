<form method="POST" action="index.php" name="form_step1">
<table width="100%" cellpadding="1" cellspacing="1" border="0">
<tr>
<td colspan="2" align="left" class="main_heading_text">Data Import</td>
</tr>
<tr>
<td align="left" class="heading_text"><?php echo $tree?></td>
<td align="left" class="heading_text">To go to any of the steps directly please use the following (not recommended) </td>
</tr>
<tr>
<td width="51%" align="center" valign="middle"><input class="button_style" type="submit" name="state_go" value="Click to Start the Wizard" onClick="<?php echo $onclick?>"/></td>
<td align="middle" valign="top"><table width="100%" border="0">
  <tr>
    <td width="6%" class="color_text_bold">1.</td>
    <td width="94%"><a href="index.php?next_step=step2" class="link_text">Customers</a></td>
  </tr>
  <tr>
    <td class="color_text_bold">2.</td>
    <td><a href="index.php?next_step=step4" class="link_text">SEO Keywords</a></td>
  </tr>
  <tr>
    <td class="color_text_bold">3.</td>
    <td><a href="index.php?next_step=step6" class="link_text">Product Categories </a></td>
  </tr>
  <tr>
    <td class="color_text_bold">4.</td>
    <td><a href="index.php?next_step=step8" class="link_text">Product Vendors &amp; Size Charts </a></td>
  </tr>
  <tr>
    <td class="color_text_bold">5.</td>
    <td><a href="index.php?next_step=step10" class="link_text">Custom Forms</a></td>
  </tr>
  <tr>
    <td class="color_text_bold">6.</td>
    <td><a href="index.php?next_step=step12" class="link_text">Products</a></td>
  </tr>
  <tr>
    <td class="color_text_bold">7.</td>
    <td><a href="index.php?next_step=step14" class="link_text">Featured Product, product mappings, promotional codes </a></td>
  </tr>
  <tr>
    <td class="color_text_bold">8.</td>
    <td><a href="index.php?next_step=step16" class="link_text">Shop By Brand</a></td>
  </tr>
  <tr>
    <td class="color_text_bold">9.</td>
    <td><a href="index.php?next_step=step18" class="link_text">Static Pages</a></td>
  </tr>
  <tr>
    <td class="color_text_bold">10.</td>
    <td><a href="index.php?next_step=step20" class="link_text">Survey</a></td>
  </tr>
  <tr>
    <td class="color_text_bold">11.</td>
    <td><a href="index.php?next_step=step22" class="link_text">Console Users</a></td>
  </tr>
  <tr>
    <td class="color_text_bold">12.</td>
    <td><a href="index.php?next_step=step24" class="link_text">Image Directories and Images</a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table></td>
</tr>
</table>
<input type="hidden" name="next_step" value="step2" />
<?php echo $process_div?>
</form>