<? 
	/*#################################################################
	# Script Name 	: export_for_amazon.php
	# Description 	: Page for exporting the files required to be submitted to google base
	# Coded by 		: Sny
	# Created on	: 14-Feb-2013
	# Modified by	: 
	# Modified On	: 
	#################################################################*/

	//Define constants for this page
	$page_type = 'Amazon Export';
	$help_msg = get_help_messages('LIST_GOOGLE_EXPORT_MESS1');
	
?>
<script type='text/javascript'>
function handle_select_allcategory()
{
	obj 	= document.getElementById('sel_category_id[]');
	var sel = '';
	if (document.getElementById('sel_category_all').checked)
		sel = true;
	else
		sel = false;
	for (i=obj.options.length-1;i>=0;i--)
	{
		obj.options[i].selected = sel;
	}
}
function validate_curform()
{
	/* check whether atleast one item selected for category */
	obj 		= document.getElementById('sel_category_id[]');
	atleastone 	= false;	
	var sel = '';
	for (i=0;i<obj.options.length;i++)
	{
		if(obj.options[i].selected==true)
		{
			atleastone = true;
		}	
	}
	if(atleastone)
	{
		return true;
	}
	else
	{
		alert('Please select atleast one category of products to be included in the export');
		return false;
	}
}	
</script>
<form action="export_for_amazon.php" method="post" name="frm_promo" onsubmit="return validate_curform()">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
<tr>
<td valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
	<td align="left" class="treemenutd"><div class="treemenutd_div"><span>Amazon Exporter</span></div></td>
	</tr>
	<tr>
	  <td align="left" valign="middle" class="helpmsgtd_main" >
	  <?php 
		  //Display_Main_Help_msg($help_arr,$help_msg);
		  echo "This section allows you to download a <strong>*.txt</strong> file which can be uploaded using Inventory Loader in the Amazon Seller Account
		  <br><br>To upload your Inventory Loader File in <strong>Amazon</strong>, you'll need to access Your Seller Account. Go to section <strong>'Manage Your Inventory'</strong> and click on <strong>'Upload multiple items'</strong>. 
		  ";
	  ?>
	 </td>
	</tr>
	<tr>
	<td></td>
	</tr>
	</table>
	<div class="editarea_div">
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
      <tr>
        <td width="43%" align="left" valign="top" class="advanced_seperator">
		<div class="advanced_seperator_div_1">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
          <tr>
            <td colspan="2" class="listingtableheader">Select the categories to identify the products to be included in the export file. </td>
            </tr>
          <tr>
            <td width="73%">
			<?php
				$cat_arr = generate_category_tree(0,0,false,true,false);
				echo generateselectbox('sel_category_id[]',$cat_arr,'-1','','',26);
			?>			</td>
            <td width="27%" align="left" valign="top"><span class="tdcolorgray">
              <input type="checkbox" name="sel_category_all" id="sel_category_all" value="1" onclick="handle_select_allcategory()" />
Select all </span></td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
        </table>
		</div></td>
		<td width="57%" align="left" valign="top" class="advanced_seperator">
		<div class="advanced_seperator_div_1">
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
          
         <tr>
           <td colspan="2" align="left" class="listingtableheader" >The following settings will be used to fill the respective columns for all the products in the exported file. </td>
           </tr>
         <tr>
            <td width="21%" align="left" class="listingtablestyleA" ><strong>Item Condition </strong></td>
            <td width="79%" align="left" class="listingtablestyleA" >
			<select name="cbo_itemcondition">
				<option value="1">Used; Like New</option>
				<option value="2">Used; Very Good</option>
				<option value="3">Used; Good</option>
				<option value="4">Used; Acceptable</option>
				<option value="5">Collectible; Like New</option>
				<option value="6">Collectible; Very Good</option>
				<option value="7">Collectible; Good</option>
				<option value="8">Collectible; Acceptable</option>
				<option value="9">Not Used</option>
				<option value="10">Refurbished (for computers, kitchen & house ware, electronics, camera & photo only)</option>
				<option value="11" selected="selected">New</option>
			</select>            </td>
          </tr>
         <tr>
           <td align="left" class="listingtablestyleB" ><strong>Shipment Details </strong></td>
           <td align="left" class="listingtablestyleB" >
			<select name="cbo_shipment">
				<option value="3" selected="selected">UK Only</option> 
				<option value="4">UK, EU Only</option> 
				<option value="5">UK, EU & United States</option> 
				<option value="6">UK, EU, United States and Rest of World</option> 
			</select>		   </td>
         </tr>
         <tr>
           <td align="left" class="listingtablestyleA" ><strong>Express Delivery </strong></td>
           <td align="left" class="listingtablestyleA" >
		   <select name="cbo_expressdel">
				<option value="3">3 = Express UK</option>
				<option value="N" selected="selected">N = None, no express delivery offered</option>
           </select>		   </td>
         </tr>
        </table>
		</div>	</td>
	</tr>	
	</table>
    <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td colspan="4" align="left" class="tdcolorgray" >&nbsp;</td>
          </tr>  
          
          <tr >
            <td colspan='4' align="center" class="tdcolorgray" ><span style="padding-top:8px">
              <input type="submit" name="generate_file" value="Generate File" class="red" />
              <a href="#" onmouseover ="ddrivetip('<? echo 'Click on Generate File button to download the file to be uploaded to Amazon Inventory Loader';?>')"; onmouseout="hideddrivetip()"><img src="images/helpicon.png" width="17" height="13" border="0" /></a> <br />
            </span></td>
          <tr >
            <td colspan='4' align="center" class="tdcolorgray" ><span style="padding-top:8px">* Please check the prices at the end to make sure they are correct.  If they are not please contact support@thewebclinic.co.uk </span></td>
          </table>
    </div>
      </td>
    </tr>
  </table>
   

</form>
