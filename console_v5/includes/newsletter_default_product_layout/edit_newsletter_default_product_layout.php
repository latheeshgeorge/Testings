<?php
/*#################################################################
# Script Name 	: edit_colors.php
# Description 	: Page for editing Product Variable Colors
# Coded by 	: Sny
# Created on	: 11-Jan-2010
# Modified by	: 
# Modified On	: 
#################################################################*/
#Define constants for this page
$page_type      = 'Default layout for newsletter product ';
$help_msg       = get_help_messages('EDIT_NEWS_DEF_LAY_MESS1');
$sql            = "SELECT template_product_layout 
                        FROM 
                            sites  
                        WHERE 
                            site_id=$ecom_siteid 
                            LIMIT 1";
$res            = $db->query($sql);
if($db->num_rows($res)==0)
{ 
    echo " <font color='red'> You Are Not Authorised  </a>"; 
    exit;
}
$row=$db->fetch_array($res);
?>	
<form name='frmdefaulttemplate' action='home.php?request=newsletter_prod_layout' method="post">
<input type='hidden' name='fpurpose' id='fpurpose' value='Save_details'/>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3" align="left" valign="middle" class="treemenutd"><div class="treemenutd_div"><span><?php echo $page_type ?></span></div></td>
        </tr>
       <tr>
        <td align="left" valign="middle" class="helpmsgtd_main" colspan="3">
            <?php 
                Display_Main_Help_msg($help_arr,$help_msg);
            ?>
        </td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="tdcolorgray" colspan='3'>&nbsp;
          </td>
        </tr>        
        <?php 
        if($alert)
        {			
        ?>
            <tr>
            <td colspan="3" align="center" valign="middle" class="errormsg" ><?=$alert?></td>
            </tr>
        <?
        }
        ?>
		<tr>
            <td colspan="3" align="center" valign="middle" class="tdcolorgray" >
			<div class="editarea_div">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td  align="left" valign="middle" class="tdcolorgray" colspan='3' >Default layout to be used for products in newsletter&nbsp;<span class="redtext">*</span> </td>
        </tr>
        <tr>
        <td align="left" valign="middle" class="tdcolorgray" colspan='2'>
        <?php
            $editor_elements = "template_product_layout";
            include_once("js/tinymce.php");
            // Replace all </textarea> tag with <~~textarea>
            $pgcontent = str_ireplace('</textarea>','<~~textarea>',stripslashes($row['template_product_layout']));
            ?>                                                
            <textarea style="height:300px; width:700px" id="template_product_layout" name="template_product_layout"><?php echo $pgcontent?></textarea>
            <?php // Replacing <~~textarea> with </textarea> using javascript?>
            <script type="text/javascript">
            document.getElementById('template_product_layout').value = document.getElementById('template_product_layout').value.replace(/<~~textarea>/gi, "</textarea>");
            </script>
          </td>
          <td width='45%' class="tdcolorgray" align="center">
              <table class="listingtable" width="90%" border="0" cellpaddin='1' cellspacing='1'>
             <tbody><tr>
               <td colspan="3" class="helpmsgtd" align="left">This code will be replaced with their values in newsletter preview page.</td>
             </tr>
             <tr class="listingtableheader">
               <td width="30%"><div align="left"><strong>&nbsp; Code</strong></div></td>

               <td width="5%">&nbsp;</td>
               <td width="65%"><div align="left"><strong>&nbsp; Description</strong></div></td>
             </tr>
                          <tr class="listingtablestyleB">
               <td align="left"> &nbsp; [IMG]</td>
               <td>=&gt;</td>

               <td align="left">&nbsp; Product Image with link.</td>
             </tr>
                          <tr class="listingtablestyleB">
               <td align="left"> &nbsp; [TITLE]</td>
               <td>=&gt;</td>
               <td align="left">&nbsp; Product Name with link</td>

             </tr>
                          <tr class="listingtablestyleB">
            <td align="left"> &nbsp; [DESCRIPTION]</td>
               <td>=&gt;</td>
               <td align="left">&nbsp; Product Description</td>
             </tr>

                          <tr class="listingtablestyleB">
               <td align="left"> &nbsp; [PRICE]</td>
               <td>=&gt;</td>
               <td align="left">&nbsp; Price details of the product</td>
             </tr>
                        </tbody></table>
          </td>
        </tr>
		</table>
		</div>
		</td>
		</tr>
		
		<tr>
			<td align="right" valign="middle" class="tdcolorgray" colspan='3'>
				<div class="editarea_div">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td width="100%" align="right" valign="middle"><input name="Submit" type="submit" class="red" value="Save" /></td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
      </table>
</form>